<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@laboratorio.com',
        ]);

                // Llamar a los seeders de nuestro proyecto
        $this->call([
            BannerSeeder::class,
            ContentSeeder::class,
            TechnologySeeder::class,
            ProjectSeeder::class,
            StaffSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
