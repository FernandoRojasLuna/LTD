<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Juan Pérez',
                'company' => 'TechSolutions S.A.',
                'logo' => '/images/clients/techsolutions-logo.png',
                'website' => 'https://techsolutions.com',
                'testimonial' => 'El Laboratorio de Transformación Digital transformó completamente nuestra infraestructura tecnológica. Los resultados superaron nuestras expectativas.',
                'project_count' => 3,
                'active' => true,
                'order' => 1
            ],
            [
                'name' => 'María Rodríguez',
                'company' => 'InnovateCorp',
                'logo' => '/images/clients/innovatecorp-logo.png',
                'website' => 'https://innovatecorp.com',
                'testimonial' => 'Profesionales excepcionales que nos ayudaron a modernizar todos nuestros procesos. Su enfoque en la transformación digital es ejemplar.',
                'project_count' => 2,
                'active' => true,
                'order' => 2
            ],
            [
                'name' => 'Carlos Mendez',
                'company' => 'FutureFinance',
                'logo' => '/images/clients/futurefinance-logo.png',
                'website' => 'https://futurefinance.com',
                'testimonial' => 'La implementación de IA en nuestros sistemas financieros fue un éxito rotundo. Aumentamos la eficiencia en un 40%.',
                'project_count' => 5,
                'active' => true,
                'order' => 3
            ],
            [
                'name' => 'Ana Gómez',
                'company' => 'HealthTech Solutions',
                'logo' => '/images/clients/healthtech-logo.png',
                'website' => 'https://healthtech.com',
                'testimonial' => 'Su trabajo en el desarrollo de nuestra plataforma de telemedicina ha sido fundamental para nuestro crecimiento.',
                'project_count' => 1,
                'active' => true,
                'order' => 4
            ],
            [
                'name' => 'Roberto Díaz',
                'company' => 'EduDigital',
                'logo' => '/images/clients/edudigital-logo.png',
                'website' => 'https://edudigital.com',
                'testimonial' => 'La transformación digital de nuestra plataforma educativa aumentó el engagement de estudiantes en un 60%.',
                'project_count' => 2,
                'active' => true,
                'order' => 5
            ],
            [
                'name' => 'Laura Vásquez',
                'company' => 'SmartRetail Inc.',
                'logo' => '/images/clients/smartretail-logo.png',
                'website' => 'https://smartretail.com',
                'testimonial' => 'El sistema de análisis predictivo que desarrollaron revolucionó nuestra gestión de inventarios.',
                'project_count' => 4,
                'active' => true,
                'order' => 6
            ],
            [
                'name' => 'Miguel Torres',
                'company' => 'GreenEnergy Corp',
                'logo' => '/images/clients/greenenergy-logo.png',
                'website' => 'https://greenenergy.com',
                'testimonial' => null, // Cliente sin testimonial
                'project_count' => 1,
                'active' => true,
                'order' => 7
            ],
            [
                'name' => 'Patricia López',
                'company' => 'DataDriven Analytics',
                'logo' => '/images/clients/datadriven-logo.png',
                'website' => 'https://datadriven.com',
                'testimonial' => 'Su expertise en big data nos permitió obtener insights valiosos de nuestros datos históricos.',
                'project_count' => 3,
                'active' => true,
                'order' => 8
            ]
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
