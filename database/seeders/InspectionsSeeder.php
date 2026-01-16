<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Nodrošinām Carbon klases izmantošanu datumu apstrādei

class InspectionsSeeder extends Seeder
{
    public function run(): void
    {
        // TRUNCATE: Iztīra tabulu
        DB::table('inspections')->truncate(); 

        $items = DatabaseSeeder::getData('inspections');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            // SVARĪGS LABOJUMS: Konvertējam ISO datumu (piem., 2025-11-06T01:00:00Z)
            // uz MySQL DATETIME formātu (piem., 2025-11-06 01:00:00)
            $startTimestamp = Carbon::parse($item['start_ts'])->format('Y-m-d H:i:s');
            
            $processedItems[] = [
                'external_id' => $item['id'],
                'case_id' => $item['case_id'], 
                'type' => $item['type'],
                'requested_by' => $item['requested_by'],
                'start_ts' => $startTimestamp, // Izmantojam formatēto laiku
                'location' => $item['location'],
                // Masīvs jāsaglabā kā JSON virkne
                'checks' => json_encode($item['checks'] ?? []),
                'assigned_to' => $item['assigned_to'], 
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($processedItems)) {
            DB::table('inspections')->insert($processedItems);
        }

        $this->command->info('Inspections data seeded successfully.');
    }
}