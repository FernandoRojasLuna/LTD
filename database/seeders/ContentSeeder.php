<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'title' => 'Sobre el Laboratorio de Transformación Digital',
                'slug' => 'sobre-laboratorio',
                'content' => '<p>El Laboratorio de Transformación Digital es un espacio de innovación donde convergén la tecnología, la creatividad y la visión empresarial. Nuestro equipo multidisciplinario trabaja en proyectos que van desde aplicaciones web hasta soluciones de inteligencia artificial.</p>
                
                <p>Nos especializamos en:</p>
                <ul>
                    <li>Desarrollo de aplicaciones web y móviles</li>
                    <li>Soluciones de inteligencia artificial y machine learning</li>
                    <li>Automatización de procesos empresariales</li>
                    <li>Consultoría en transformación digital</li>
                    <li>Investigación y desarrollo tecnológico</li>
                </ul>
                
                <p>Nuestro compromiso es crear soluciones que no solo resuelvan problemas actuales, sino que también preparen a las organizaciones para los desafíos del futuro.</p>',
                'type' => 'general',
                'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Inteligencia Artificial y Machine Learning',
                'slug' => 'ia-machine-learning',
                'content' => '<p>Desarrollamos soluciones de IA que transforman datos en insights accionables. Nuestros algoritmos de machine learning ayudan a las empresas a automatizar procesos, predecir tendencias y optimizar operaciones.</p>',
                'type' => 'technology',
                'image' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=800&h=600&fit=crop',
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Desarrollo Web Full-Stack',
                'slug' => 'desarrollo-web-fullstack',
                'content' => '<p>Creamos aplicaciones web modernas y escalables utilizando las últimas tecnologías. Desde frontend interactivo hasta backend robusto, cubrimos todo el espectro del desarrollo web.</p>',
                'type' => 'service',
                'image' => 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?w=800&h=600&fit=crop',
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Consultoría en Transformación Digital',
                'slug' => 'consultoria-transformacion-digital',
                'content' => '<p>Acompañamos a las organizaciones en su proceso de transformación digital, desde la estrategia hasta la implementación. Nuestro enfoque personalizado garantiza resultados sostenibles.</p>',
                'type' => 'service',
                'image' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=800&h=600&fit=crop',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Investigación y Desarrollo',
                'slug' => 'investigacion-desarrollo',
                'content' => '<p>Nuestro equipo de I+D trabaja constantemente en la exploración de nuevas tecnologías y metodologías. Investigamos tendencias emergentes para mantener a nuestros clientes a la vanguardia.</p>',
                'type' => 'area',
                'image' => 'https://images.unsplash.com/photo-1532619675605-1ede6c2ed2b0?w=800&h=600&fit=crop',
                'is_featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($contents as $content) {
            Content::create($content);
        }
    }
}
