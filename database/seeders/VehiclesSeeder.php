<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiclesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vehicles')->truncate();

        $items = DatabaseSeeder::getData('vehicles');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            $processedItems[] = [
                'external_id' => $item['id'],
                'plate_no' => $item['plate_no'],
                'country' => $item['country'],
                'make' => $item['make'],
                'model' => $item['model'],
                'vin' => $item['vin'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }


        if (!empty($processedItems)) {
            DB::table('vehicles')->insert($processedItems);
        }

        $this->command->info('Vehicles data seeded successfully.');
    }
}
