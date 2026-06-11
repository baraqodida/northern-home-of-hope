<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if an administrative account already exists to avoid duplication errors
        User::updateOrCreate(
            ['email' => 'admin@homeofhope.org'], // Unique target identifier
            [
                'name'              => 'Portal Administrator',
                'phone_number'      => '+254700000000',
                'county'            => 'Nairobi',
                'sub_county'        => 'Central',
                'ward'              => 'Administrative',
                'password'          => Hash::make('Security2026!'), // Securely encrypt the password
                'email_verified_at' => now(),
            ]
        );
    }
}