<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InspectionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('inspections')->truncate();

        $items = DatabaseSeeder::getData('inspections');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            $startTimestamp = Carbon::parse($item['start_ts'])->format('Y-m-d H:i:s');

            $processedItems[] = [
                'external_id' => $item['id'],
                'case_id' => $item['case_id'],
                'type' => $item['type'],
                'requested_by' => $item['requested_by'],
                'start_ts' => $startTimestamp,
                'location' => $item['location'],
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
