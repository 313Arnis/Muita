<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('documents')->truncate();

        $items = DatabaseSeeder::getData('documents');
        $now = now();
        $processedItems = [];

        foreach ($items as $item) {
            $processedItems[] = [
                'external_id' => $item['id'],
                'case_id' => $item['case_id'],
                'filename' => $item['filename'],
                'mime_type' => $item['mime_type'],
                'category' => $item['category'],
                'pages' => $item['pages'] ?? 0,
                'uploaded_by' => $item['uploaded_by'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($processedItems)) {
            DB::table('documents')->insert($processedItems);
        }

        $this->command->info('Documents data seeded successfully.');
    }
}
