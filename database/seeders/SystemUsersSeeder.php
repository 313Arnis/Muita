<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemUsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('system_users')->truncate();

        $items = DatabaseSeeder::getData('users');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {

            $password = $item['role'] . '123';

            $processedItems[] = [
                'external_id' => $item['id'],
                'username' => $item['username'],
                'full_name' => $item['full_name'],
                'role' => $item['role'],
                'active' => $item['active'] ?? true,
                'password' => bcrypt($password),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($processedItems)) {

            DB::table('system_users')->upsert(
                $processedItems,
                ['external_id'], // Unique key for matching existing records
                ['username', 'full_name', 'role', 'active', 'password', 'updated_at'] // Fields to update if record exists
            );
        }

        $this->command->info('System Users data seeded successfully.');
    }
}