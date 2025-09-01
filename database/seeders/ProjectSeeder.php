<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Technology;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Portal de Gestión Empresarial',
                'slug' => 'portal-gestion-empresarial',
                'description' => 'Sistema integral para la gestión de recursos humanos, inventarios y finanzas empresariales.',
                'full_description' => '<p>Un sistema completo que integra todas las áreas operativas de una empresa en una sola plataforma. Incluye módulos de RRHH, inventarios, contabilidad, CRM y reportes avanzados.</p><p>Características principales:</p><ul><li>Dashboard ejecutivo en tiempo real</li><li>Gestión de empleados y nóminas</li><li>Control de inventarios automatizado</li><li>Módulo financiero integrado</li><li>Sistema de reportes personalizables</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop'
                ],
                'status' => 'development',
                'start_date' => '2025-01-15',
                'end_date' => '2025-12-30',
                'url' => null,
                'repository' => 'https://github.com/laboratorio/portal-empresarial',
                'features' => ['Dashboard', 'RRHH', 'Inventarios', 'Finanzas', 'Reportes'],
                'is_featured' => true,
                'is_active' => true,
                'order' => 1,
                'technologies' => [1, 2, 6, 7] // Laravel, Vue.js, MySQL, Docker
            ],
            [
                'name' => 'Sistema de Análisis Predictivo',
                'slug' => 'sistema-analisis-predictivo',
                'description' => 'Plataforma de inteligencia artificial para análisis predictivo de datos empresariales.',
                'full_description' => '<p>Sistema avanzado que utiliza machine learning para predecir tendencias de ventas, comportamiento del cliente y optimización de recursos.</p><p>Implementa algoritmos de:</p><ul><li>Predicción de ventas</li><li>Segmentación de clientes</li><li>Análisis de sentimientos</li><li>Optimización de precios</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=800&h=600&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop'
                ],
                'status' => 'development',
                'start_date' => '2025-03-01',
                'end_date' => '2025-10-15',
                'url' => null,
                'repository' => 'https://github.com/laboratorio/analisis-predictivo',
                'features' => ['Machine Learning', 'Predicciones', 'Dashboards', 'APIs'],
                'is_featured' => true,
                'is_active' => true,
                'order' => 2,
                'technologies' => [5, 9, 8] // Python, TensorFlow, AWS
            ],
            [
                'name' => 'E-commerce Inteligente',
                'slug' => 'ecommerce-inteligente',
                'description' => 'Plataforma de comercio electrónico con recomendaciones basadas en IA.',
                'full_description' => '<p>Tienda online moderna con sistema de recomendaciones inteligente, gestión de inventarios en tiempo real y análisis avanzado de métricas.</p>',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=600&fit=crop',
                'gallery' => null,
                'status' => 'deployed',
                'start_date' => '2024-08-01',
                'end_date' => '2025-01-30',
                'url' => 'https://demo-ecommerce.laboratorio.com',
                'repository' => 'https://github.com/laboratorio/ecommerce-ia',
                'features' => ['IA Recomendaciones', 'Pagos Online', 'Inventario Real-time'],
                'is_featured' => false,
                'is_active' => true,
                'order' => 3,
                'technologies' => [3, 4, 5, 6] // React, Node.js, Python, MySQL
            ],
            [
                'name' => 'App Móvil de Salud',
                'slug' => 'app-movil-salud',
                'description' => 'Aplicación móvil para monitoreo de salud y telemedicina.',
                'full_description' => '<p>App que permite a los usuarios monitorear signos vitales, agendar citas médicas virtuales y llevar un historial médico digital.</p>',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=800&h=600&fit=crop',
                'gallery' => null,
                'status' => 'deployed',
                'start_date' => '2024-05-01',
                'end_date' => '2024-11-15',
                'url' => 'https://health-app.laboratorio.com',
                'repository' => null,
                'features' => ['Telemedicina', 'Monitoreo', 'Historial Digital'],
                'is_featured' => false,
                'is_active' => true,
                'order' => 4,
                'technologies' => [3, 4, 8] // React, Node.js, AWS
            ],
            [
                'name' => 'Sistema de IoT Industrial',
                'slug' => 'sistema-iot-industrial',
                'description' => 'Plataforma para monitoreo y control de dispositivos IoT en entornos industriales.',
                'full_description' => '<p>Sistema que conecta sensores industriales para monitoreo en tiempo real de temperatura, humedad, presión y otros parámetros críticos.</p>',
                'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop',
                'gallery' => null,
                'status' => 'development',
                'start_date' => '2025-06-01',
                'end_date' => '2026-03-30',
                'url' => null,
                'repository' => 'https://github.com/laboratorio/iot-industrial',
                'features' => ['Sensores IoT', 'Monitoreo Real-time', 'Alertas'],
                'is_featured' => false,
                'is_active' => true,
                'order' => 5,
                'technologies' => [4, 5, 8] // Node.js, Python, AWS
            ]
        ];

        foreach ($projects as $projectData) {
            $technologies = $projectData['technologies'];
            unset($projectData['technologies']);
            
            $project = Project::create($projectData);
            $project->technologies()->sync($technologies);
        }
    }
}
