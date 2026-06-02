<?php

namespace App\Services;

use App\Models\Guest;
use Illuminate\Support\Facades\DB;

class GuestQuickAddService
{
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
}