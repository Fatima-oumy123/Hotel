<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            RoomtypeSeeder::class,
            RoomSeeder::class,
            TestDataSeeder::class,
        ]);
    }
}
