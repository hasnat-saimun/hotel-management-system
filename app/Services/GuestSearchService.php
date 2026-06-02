<?php

namespace App\Services;

use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GuestSearchService
{
    public function search(array $filters): LengthAwarePaginator
    {
        $criteria = $this->normalizeFilters($filters);

        $query = Guest::query()
            ->select([
                'guests.id',
                'guests.first_name',
                'guests.last_name',
                'guests.email',
                'guests.phone',
                'guests.nationality',
                'guests.vip',
                'guests.blacklisted',
                'guests.company_id',
                'guests.travel_agent_id',
                'guests.loyalty_id',
                'guests.created_at',
            ])
            ->with([
                'company:id,name',
                'travelAgent:id,name',
                'loyalty:id,level_name',
                'blacklist:id,guest_id,reason,blocked_until',
            ])
            ->withCount('reservations')
            ->when($criteria['name'] !== null || $criteria['phone'] !== null || $criteria['email'] !== null, function (Builder $builder) use ($criteria) {
                $builder->where(function (Builder $searchQuery) use ($criteria) {
                    if ($criteria['email'] !== null) {
                        $email = mb_strtolower($criteria['email']);
                        $searchQuery->orWhereRaw('LOWER(guests.email) = ?', [$email]);
                        $searchQuery->orWhereRaw('LOWER(guests.email) LIKE ?', ['%' . $email . '%']);
                    }

                    if ($criteria['phone'] !== null) {
                        $normalizedPhone = $this->normalizePhone($criteria['phone']);
                        if ($normalizedPhone !== '') {
                            $searchQuery->orWhere('guests.phone', 'like', '%' . $criteria['phone'] . '%');
                            $searchQuery->orWhereRaw($this->phoneNormalizationSql('guests.phone') . ' LIKE ?', ['%' . $normalizedPhone . '%']);
                        }
                    }

                    if ($criteria['name'] !== null) {
                        $name = mb_strtolower($criteria['name']);
                        $searchQuery->orWhereRaw('LOWER(guests.first_name) LIKE ?', ['%' . $name . '%']);
                        $searchQuery->orWhereRaw('LOWER(guests.last_name) LIKE ?', ['%' . $name . '%']);
                        $searchQuery->orWhereRaw("LOWER(CONCAT_WS(' ', guests.first_name, guests.last_name)) LIKE ?", ['%' . $name . '%']);
                    }
                });
            });

        $this->applyOrdering($query, $criteria);

        $perPage = $criteria['per_page'];
        $page = $criteria['page'];

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function mapPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'items' => $paginator->getCollection()->map(fn (Guest $guest) => $this->mapGuest($guest))->values()->all(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public function searchMode(array $filters): string
    {
        $criteria = $this->normalizeFilters($filters);

        if ($criteria['email'] !== null && $criteria['phone'] !== null && $criteria['name'] !== null) {
            return 'combined';
        }

        if ($criteria['email'] !== null) {
            return 'email';
        }

        if ($criteria['phone'] !== null) {
            return 'phone';
        }

        if ($criteria['name'] !== null) {
            return 'name';
        }

        return 'combined';
    }

    public function mapGuest(Guest $guest): array
    {
        $fullName = trim((string) ($guest->first_name . ' ' . $guest->last_name));

        return [
            'id' => $guest->id,
            'full_name' => $fullName !== '' ? $fullName : null,
            'first_name' => $guest->first_name,
            'last_name' => $guest->last_name,
            'phone' => $guest->phone,
            'email' => $guest->email,
            'nationality' => $guest->nationality,
            'vip' => (bool) $guest->vip,
            'blacklisted' => (bool) $guest->blacklisted,
            'returning_guest' => (int) ($guest->reservations_count ?? 0) > 0,
            'reservations_count' => (int) ($guest->reservations_count ?? 0),
            'guest_id' => 'G-' . str_pad((string) $guest->id, 6, '0', STR_PAD_LEFT),
            'company' => $guest->company ? [
                'id' => $guest->company->id,
                'name' => $guest->company->name,
            ] : null,
            'travel_agent' => $guest->travelAgent ? [
                'id' => $guest->travelAgent->id,
                'name' => $guest->travelAgent->name,
            ] : null,
            'loyalty' => $guest->loyalty ? [
                'id' => $guest->loyalty->id,
                'level_name' => $guest->loyalty->level_name,
            ] : null,
            'blacklist' => $guest->blacklist ? [
                'reason' => $guest->blacklist->reason,
                'blocked_until' => $guest->blacklist->blocked_until,
            ] : null,
            'created_at' => optional($guest->created_at)->toIso8601String(),
        ];
    }

    private function normalizeFilters(array $filters): array
    {
        $name = $this->normalizeNullableString($filters['name'] ?? null);
        $phone = $this->normalizeNullableString($filters['phone'] ?? null);
        $email = $this->normalizeNullableString($filters['email'] ?? null);

        if ($name === null && ($filters['q'] ?? null) !== null) {
            $name = $this->normalizeNullableString($filters['q']);
        }

        return [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'page' => max((int) ($filters['page'] ?? 1), 1),
            'per_page' => min(max((int) ($filters['per_page'] ?? 10), 1), 50),
        ];
    }

    private function normalizeNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return preg_replace('/\s+/', ' ', $value);
    }

    private function normalizePhone(string $value): string
    {
        return preg_replace('/[^0-9]+/', '', $value) ?? '';
    }

    private function phoneNormalizationSql(string $column): string
    {
        return "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE({$column}, ' ', ''), '-', ''), '(', ''), ')', ''), '+', ''), '.', '')";
    }

    private function applyOrdering(Builder $query, array $criteria): void
    {
        if ($criteria['email'] !== null) {
            $email = mb_strtolower($criteria['email']);
            $query->orderByRaw('CASE WHEN LOWER(guests.email) = ? THEN 0 ELSE 1 END', [$email]);
        }

        if ($criteria['phone'] !== null) {
            $normalizedPhone = $this->normalizePhone($criteria['phone']);
            if ($normalizedPhone !== '') {
                $query->orderByRaw('CASE WHEN ' . $this->phoneNormalizationSql('guests.phone') . ' = ? THEN 0 ELSE 1 END', [$normalizedPhone]);
            }
        }

        if ($criteria['name'] !== null) {
            $name = mb_strtolower($criteria['name']);
            $query->orderByRaw("CASE WHEN LOWER(CONCAT_WS(' ', guests.first_name, guests.last_name)) = ? THEN 0 WHEN LOWER(guests.first_name) = ? OR LOWER(guests.last_name) = ? THEN 1 ELSE 2 END", [$name, $name, $name]);
        }

        $query->orderBy('guests.first_name')->orderBy('guests.last_name')->orderByDesc('guests.id');
    }
}