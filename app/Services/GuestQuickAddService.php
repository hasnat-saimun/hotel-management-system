<?php

namespace App\Services;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;

class GuestQuickAddService
{
    public function findDuplicateMatches(array $data): array
    {
        $matches = new EloquentCollection();

        $email = $this->normalizeNullableString($data['email'] ?? null);
        if ($email !== null) {
            $emailMatches = Guest::withTrashed()
                ->withCount('reservations')
                ->whereRaw('LOWER(guests.email) = ?', [mb_strtolower($email)])
                ->orderByDesc('updated_at')
                ->get();

            $matches = $matches->merge($emailMatches);
        }

        $phone = $this->normalizePhone((string) ($data['phone'] ?? ''));
        if ($phone !== '') {
            $phoneMatches = Guest::withTrashed()
                ->withCount('reservations')
                ->whereRaw($this->phoneNormalizationSql('guests.phone') . ' = ?', [$phone])
                ->orderByDesc('updated_at')
                ->get();

            $matches = $matches->merge($phoneMatches);
        }

        return $matches->unique('id')->values()->all();
    }

    public function create(array $data): Guest
    {
        return DB::transaction(function () use ($data) {
            [$firstName, $lastName] = $this->splitName((string) $data['full_name']);

            $guest = Guest::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'id_number' => $data['id_number'] ?? null,
                'vip' => (bool) ($data['vip'] ?? false),
                'blacklisted' => (bool) ($data['blacklisted'] ?? false),
            ]);

            return $guest->loadCount('reservations');
        });
    }

    public function mapDuplicateMatches(array $matches): array
    {
        return collect($matches)
            ->map(fn (Guest $guest) => $this->mapGuest($guest))
            ->values()
            ->all();
    }

    public function mapGuest(Guest $guest): array
    {
        $fullName = trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? ''));

        return [
            'id' => $guest->id,
            'full_name' => $fullName !== '' ? $fullName : null,
            'first_name' => $guest->first_name,
            'last_name' => $guest->last_name,
            'phone' => $guest->phone,
            'email' => $guest->email,
            'address' => $guest->address,
            'nationality' => $guest->nationality,
            'id_number' => $guest->id_number,
            'vip' => (bool) $guest->vip,
            'blacklisted' => (bool) $guest->blacklisted,
            'returning_guest' => (int) ($guest->reservations_count ?? 0) > 0,
            'guest_id' => 'G-' . str_pad((string) $guest->id, 6, '0', STR_PAD_LEFT),
        ];
    }

    private function splitName(string $fullName): array
    {
        $fullName = trim(preg_replace('/\s+/', ' ', $fullName) ?? $fullName);

        if ($fullName === '') {
            return ['', ''];
        }

        $parts = preg_split('/\s+/', $fullName) ?: [];

        if (count($parts) === 1) {
            return [$parts[0], $parts[0]];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [$firstName, $lastName];
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
}