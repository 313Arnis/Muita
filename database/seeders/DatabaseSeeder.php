<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{

    protected static ?array $data = null;


    public function run(): void
    {

        $this->call([
            VehiclesSeeder::class,
            PartiesSeeder::class,
            SystemUsersSeeder::class,
            CasesSeeder::class,
            InspectionsSeeder::class,
            DocumentsSeeder::class,
        ]);
    }


    public static function getData(string $key): array
    {
        if (self::$data === null) {
            $json = File::get(database_path('seeders/info.json'));
            self::$data = json_decode($json, true);
        }

        return self::$data[$key] ?? [];
    }
}
