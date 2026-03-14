<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $guestIds = DB::table('guests')->pluck('id', 'email');

        $documents = [
            ['email' => 'john.carter@example.com', 'document_type' => 'passport', 'issuing_country' => 'United States', 'issued_date' => '2020-01-15', 'expiry_date' => '2030-01-14'],
            ['email' => 'emma.stone@example.com', 'document_type' => 'driver_license', 'issuing_country' => 'Canada', 'issued_date' => '2022-06-01', 'expiry_date' => '2028-06-01'],
            ['email' => 'liam.nguyen@example.com', 'document_type' => 'id_card', 'issuing_country' => 'Vietnam', 'issued_date' => '2021-03-18', 'expiry_date' => '2031-03-18'],
            ['email' => 'olivia.brown@example.com', 'document_type' => 'passport', 'issuing_country' => 'United Kingdom', 'issued_date' => '2019-11-11', 'expiry_date' => '2029-11-10'],
            ['email' => 'sophia.khan@example.com', 'document_type' => 'id_card', 'issuing_country' => 'Pakistan', 'issued_date' => '2020-08-09', 'expiry_date' => '2030-08-09'],
        ];

        foreach ($documents as $doc) {
            $guestId = $guestIds[$doc['email']] ?? null;
            if (!$guestId) {
                continue;
            }

            DB::table('guest_documents')->updateOrInsert(
                [
                    'guest_id' => $guestId,
                    'document_type' => $doc['document_type'],
                    'issued_date' => $doc['issued_date'],
                ],
                [
                    'issuing_country' => $doc['issuing_country'],
                    'expiry_date' => $doc['expiry_date'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
