<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Laboratorio de Transformación Digital',
                'subtitle' => 'Innovación y tecnología para el futuro',
                'image' => 'https://images.unsplash.com/photo-1581472723648-909f4851d4ae?w=1200&h=600&fit=crop',
                'description' => 'Desarrollamos soluciones tecnológicas innovadoras que transforman negocios y crean impacto social.',
                'link' => '#about',
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Proyectos en Desarrollo',
                'subtitle' => 'Construyendo el mañana hoy',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=600&fit=crop',
                'description' => 'Conoce nuestros proyectos actuales en inteligencia artificial, blockchain y IoT.',
                'link' => '#projects',
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Únete a Nuestro Equipo',
                'subtitle' => 'Talento excepcional para retos extraordinarios',
                'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&h=600&fit=crop',
                'description' => 'Buscamos desarrolladores, diseñadores y visionarios que quieran cambiar el mundo.',
                'link' => '#staff',
                'order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Alianzas Estratégicas',
                'subtitle' => 'Colaboramos con los mejores',
                'image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=1200&h=600&fit=crop',
                'description' => 'Nuestros clientes y socios confían en nosotros para impulsar su transformación digital.',
                'link' => '#clients',
                'order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
