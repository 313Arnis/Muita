<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthUsersSeeder extends Seeder
{
    public function run(): void
    {
       
        $items = DatabaseSeeder::getData('users');

        if (empty($items)) {
            $this->command->info('No users found in info.json');
            return;
        }

       
        $toInsert = [];
        $now = now();

        foreach (array_slice($items, 0, 6) as $item) {
            $username = $item['username'] ?? $item['id'] ?? 'user';
            $email = ($username) ? ($username . '@example.test') : null;

            $toInsert[] = [
                'username' => $username,
                'full_name' => $item['full_name'] ?? null,
                'email' => $email,
                'password' => Hash::make('Password123!'),
                'role' => $item['role'] ?? 'analyst',
                'active' => $item['active'] ?? true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($toInsert)) {
            DB::table('users')->insert($toInsert);
            $this->command->info('Auth users seeded successfully. Default password: Password123!');
        }
    }
}