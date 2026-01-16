<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    // Šeit glabāsim ielādētos datus, lai nevajadzētu lasīt JSON atkārtoti.
    protected static ?array $data = null;

    /**
     * Palaist datu sēšanas funkcijas.
     */
    public function run(): void
    {
        // Palaist visus specifiskos seederus
        $this->call([
            VehiclesSeeder::class,
            PartiesSeeder::class,
            SystemUsersSeeder::class,
            AuthUsersSeeder::class,
            CasesSeeder::class,
            InspectionsSeeder::class,
            DocumentsSeeder::class,
        ]);
    }

    /**
     * Palīgfunkcija, lai iegūtu datus no JSON.
     */
    public static function getData(string $key): array
    {
        if (self::$data === null) {
            $json = File::get(database_path('seeders/info.json'));
            self::$data = json_decode($json, true);
        }

        return self::$data[$key] ?? [];
    }
}
