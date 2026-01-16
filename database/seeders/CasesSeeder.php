<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Iekļaujam Carbon

class CasesSeeder extends Seeder
{
    public function run(): void
    {
        // Iztīra tabulu
        DB::table('cases')->truncate(); 

        $items = DatabaseSeeder::getData('cases');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            // Konvertējam ISO datumu uz MySQL DATETIME formātu
            $arrivalTimestamp = Carbon::parse($item['arrival_ts'])->format('Y-m-d H:i:s');
            
            $processedItems[] = [
                'external_id' => $item['id'],
                'external_ref' => $item['external_ref'] ?? null,
                'status' => $item['status'], // Tagad kā STRING
                'priority' => $item['priority'], // Tagad kā STRING
                'arrival_ts' => $arrivalTimestamp, 
                'checkpoint_id' => $item['checkpoint_id'],
                'origin_country' => $item['origin_country'],
                'destination_country' => $item['destination_country'],
                'risk_flags' => json_encode($item['risk_flags'] ?? []),
                'declarant_id' => $item['declarant_id'],
                'consignee_id' => $item['consignee_id'],
                'vehicle_id' => $item['vehicle_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($processedItems)) {
            DB::table('cases')->insert($processedItems);
        }

        $this->command->info('Cases data seeded successfully.');
    }
}