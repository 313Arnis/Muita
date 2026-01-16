<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemUsersSeeder extends Seeder
{
    public function run(): void
    {
        // TRUNCATE: Iztīra tabulu
        DB::table('system_users')->truncate(); 
        
        // JSON atslēga ir 'users', bet tabula ir 'system_users'
        $items = DatabaseSeeder::getData('users');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            $processedItems[] = [
                'external_id' => $item['id'],
                'username' => $item['username'],
                'full_name' => $item['full_name'],
                'role' => $item['role'],
                'active' => $item['active'] ?? true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($processedItems)) {
            DB::table('system_users')->insert($processedItems);
        }

        $this->command->info('System Users data seeded successfully.');
    }
}