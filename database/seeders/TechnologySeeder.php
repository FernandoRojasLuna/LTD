<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Technology;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technologies = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'icon' => 'fab fa-laravel',
                'color' => '#f55247',
                'description' => 'Framework PHP moderno para desarrollo web',
                'is_active' => true
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vuejs',
                'icon' => 'fab fa-vuejs',
                'color' => '#4fc08d',
                'description' => 'Framework JavaScript progresivo',
                'is_active' => true
            ],
            [
                'name' => 'React',
                'slug' => 'react',
                'icon' => 'fab fa-react',
                'color' => '#61dafb',
                'description' => 'Biblioteca JavaScript para interfaces',
                'is_active' => true
            ],
            [
                'name' => 'Node.js',
                'slug' => 'nodejs',
                'icon' => 'fab fa-node-js',
                'color' => '#68a063',
                'description' => 'Entorno de ejecución para JavaScript',
                'is_active' => true
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'icon' => 'fab fa-python',
                'color' => '#3776ab',
                'description' => 'Lenguaje de programación versátil',
                'is_active' => true
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'icon' => 'fas fa-database',
                'color' => '#4479a1',
                'description' => 'Sistema de gestión de bases de datos',
                'is_active' => true
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'icon' => 'fab fa-docker',
                'color' => '#2496ed',
                'description' => 'Plataforma de contenedores',
                'is_active' => true
            ],
            [
                'name' => 'AWS',
                'slug' => 'aws',
                'icon' => 'fab fa-aws',
                'color' => '#ff9900',
                'description' => 'Servicios de computación en la nube',
                'is_active' => true
            ],
            [
                'name' => 'TensorFlow',
                'slug' => 'tensorflow',
                'icon' => 'fas fa-brain',
                'color' => '#ff6f00',
                'description' => 'Plataforma de machine learning',
                'is_active' => true
            ],
            [
                'name' => 'Tailwind CSS',
                'slug' => 'tailwindcss',
                'icon' => 'fas fa-paint-brush',
                'color' => '#06b6d4',
                'description' => 'Framework CSS utility-first',
                'is_active' => true
            ]
        ];

        foreach ($technologies as $technology) {
            Technology::create($technology);
        }
    }
}
