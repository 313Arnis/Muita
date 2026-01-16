<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartiesSeeder extends Seeder
{
    public function run(): void
    {
        // TRUNCATE: Iztīra tabulu
        DB::table('parties')->truncate(); 

        $items = DatabaseSeeder::getData('parties');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            $processedItems[] = [
                'external_id' => $item['id'],
                'type' => $item['type'] ?? 'company', 
                'name' => $item['name'],
                'reg_code' => $item['reg_code'] ?? null,
                'vat' => $item['vat'] ?? null,
                'country' => $item['country'],
                'email' => $item['email'] ?? null,
                'phone' => $item['phone'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($processedItems)) {
            DB::table('parties')->insert($processedItems);
        }

        $this->command->info('Parties data seeded successfully.');
    }
}