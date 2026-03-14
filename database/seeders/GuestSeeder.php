<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $guests = [
            ['first_name' => 'John', 'last_name' => 'Carter', 'email' => 'john.carter@example.com', 'phone' => '+1555000101', 'date_of_birth' => '1989-03-14', 'address' => '14 Pine Street, Austin', 'nationality' => 'American', 'gender' => 'male', 'id_type' => 'passport', 'id_number' => 'PST1234501', 'vip' => false, 'blacklisted' => false, 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Emma', 'last_name' => 'Stone', 'email' => 'emma.stone@example.com', 'phone' => '+1555000102', 'date_of_birth' => '1992-07-21', 'address' => '8 Rose Avenue, Denver', 'nationality' => 'Canadian', 'gender' => 'female', 'id_type' => 'driver_license', 'id_number' => 'DL5543221', 'vip' => true, 'blacklisted' => false, 'notes' => 'Prefers high floor rooms.', 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Liam', 'last_name' => 'Nguyen', 'email' => 'liam.nguyen@example.com', 'phone' => '+1555000103', 'date_of_birth' => '1985-11-02', 'address' => '42 Harbor Lane, Seattle', 'nationality' => 'Vietnamese', 'gender' => 'male', 'id_type' => 'national_id', 'id_number' => 'VN992211', 'vip' => false, 'blacklisted' => false, 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Olivia', 'last_name' => 'Brown', 'email' => 'olivia.brown@example.com', 'phone' => '+1555000104', 'date_of_birth' => '1995-04-10', 'address' => '91 Lake Drive, Orlando', 'nationality' => 'British', 'gender' => 'female', 'id_type' => 'passport', 'id_number' => 'GB990033', 'vip' => false, 'blacklisted' => false, 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Noah', 'last_name' => 'Silva', 'email' => 'noah.silva@example.com', 'phone' => '+1555000105', 'date_of_birth' => '1979-12-24', 'address' => '5 Garden Way, Miami', 'nationality' => 'Brazilian', 'gender' => 'male', 'id_type' => 'passport', 'id_number' => 'BR881122', 'vip' => false, 'blacklisted' => false, 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Sophia', 'last_name' => 'Khan', 'email' => 'sophia.khan@example.com', 'phone' => '+1555000106', 'date_of_birth' => '1990-01-05', 'address' => '33 Cedar Road, Boston', 'nationality' => 'Pakistani', 'gender' => 'female', 'id_type' => 'national_id', 'id_number' => 'PK441009', 'vip' => false, 'blacklisted' => false, 'notes' => 'Allergic to peanuts.', 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Ethan', 'last_name' => 'Garcia', 'email' => 'ethan.garcia@example.com', 'phone' => '+1555000107', 'date_of_birth' => '1988-06-17', 'address' => '77 North Street, Phoenix', 'nationality' => 'Mexican', 'gender' => 'male', 'id_type' => 'driver_license', 'id_number' => 'MXDL1012', 'vip' => true, 'blacklisted' => false, 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['first_name' => 'Mia', 'last_name' => 'Lee', 'email' => 'mia.lee@example.com', 'phone' => '+1555000108', 'date_of_birth' => '1998-08-29', 'address' => '12 Maple Court, San Diego', 'nationality' => 'Korean', 'gender' => 'female', 'id_type' => 'passport', 'id_number' => 'KR881901', 'vip' => false, 'blacklisted' => false, 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('guests')->upsert(
            $guests,
            ['email'],
            ['first_name', 'last_name', 'phone', 'date_of_birth', 'address', 'nationality', 'gender', 'id_type', 'id_number', 'vip', 'blacklisted', 'notes', 'updated_at']
        );
    }
}
