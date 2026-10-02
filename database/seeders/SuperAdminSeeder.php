<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check IF exists .
        $exists = DB::table('users')
            ->where('email', 'superadmin@example.com')
            ->exists();

        if ($exists) {
            return;
        }

        DB::insert(
            'INSERT INTO users
                (name, email, password, role, company_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)', // Used parameter binding tp prevent SQL injections
            [
                'Super Admin',
                'superadmin@example.com',
                Hash::make('super@123'),
                'super_admin',
                null,
                now(),
                now(),
            ]
        );
    }
}
