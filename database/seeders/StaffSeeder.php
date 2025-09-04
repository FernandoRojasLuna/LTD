<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = [
            [
                'name' => 'Dr. Fernando Rojas',
                'position' => 'Director del Laboratorio',
                'bio' => 'Experto en transformación digital con más de 15 años de experiencia en tecnologías emergentes y liderazgo de equipos de innovación.',
                'image' => '/images/staff/fernando-rojas.jpg',
                'email' => 'fernando.rojas@ltd.com',
                'linkedin' => 'https://linkedin.com/in/fernandorojas',
                'github' => 'https://github.com/fernandorojas',
                'active' => true,
                'order' => 1
            ],
            [
                'name' => 'Dra. María González',
                'position' => 'Investigadora Senior',
                'bio' => 'Especialista en inteligencia artificial y machine learning, con un doctorado en Ciencias de la Computación y múltiples publicaciones en revistas indexadas.',
                'image' => '/images/staff/maria-gonzalez.jpg',
                'email' => 'maria.gonzalez@ltd.com',
                'linkedin' => 'https://linkedin.com/in/mariagonzalez',
                'github' => 'https://github.com/mariagonzalez',
                'active' => true,
                'order' => 2
            ],
            [
                'name' => 'Ing. Carlos Mendoza',
                'position' => 'Desarrollador Full Stack',
                'bio' => 'Ingeniero de software especializado en arquitecturas cloud y desarrollo de aplicaciones escalables. Experto en tecnologías web modernas.',
                'image' => '/images/staff/carlos-mendoza.jpg',
                'email' => 'carlos.mendoza@ltd.com',
                'linkedin' => 'https://linkedin.com/in/carlosmendoza',
                'github' => 'https://github.com/carlosmendoza',
                'active' => true,
                'order' => 3
            ],
            [
                'name' => 'Lic. Ana Martínez',
                'position' => 'Analista de UX/UI',
                'bio' => 'Diseñadora de experiencias digitales con enfoque en usabilidad y accesibilidad. Especialista en design thinking y metodologías ágiles.',
                'image' => '/images/staff/ana-martinez.jpg',
                'email' => 'ana.martinez@ltd.com',
                'linkedin' => 'https://linkedin.com/in/anamartinez',
                'github' => null,
                'active' => true,
                'order' => 4
            ],
            [
                'name' => 'MSc. Roberto Silva',
                'position' => 'DevOps Engineer',
                'bio' => 'Especialista en infraestructura cloud y automatización de procesos. Experto en Docker, Kubernetes y CI/CD pipelines.',
                'image' => '/images/staff/roberto-silva.jpg',
                'email' => 'roberto.silva@ltd.com',
                'linkedin' => 'https://linkedin.com/in/robertosilva',
                'github' => 'https://github.com/robertosilva',
                'active' => true,
                'order' => 5
            ],
            [
                'name' => 'Dra. Laura Vega',
                'position' => 'Data Scientist',
                'bio' => 'Científica de datos con especialización en análisis predictivo y big data. Experiencia en sectores financiero y healthcare.',
                'image' => '/images/staff/laura-vega.jpg',
                'email' => 'laura.vega@ltd.com',
                'linkedin' => 'https://linkedin.com/in/lauravega',
                'github' => 'https://github.com/lauravega',
                'active' => true,
                'order' => 6
            ]
        ];

        foreach ($staffMembers as $staff) {
            Staff::create($staff);
        }
    }
}
