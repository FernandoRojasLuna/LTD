<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import AboutLab from '@/Components/AboutLab.vue';
import ProjectsSection from '@/Components/ProjectsSection.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Laboratorio de Transformación Digital" />
    
    <PublicLayout>
        <!-- Hero Section with Banner Carousel -->
        <HeroSection />
        
        <!-- About Lab Section -->
        <AboutLab />
        
        <!-- Projects Section -->
        <ProjectsSection />
        
        <!-- Placeholder for future sections -->
        <section id="staff" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestro Equipo</h2>
                <p class="text-xl text-gray-600">Próximamente: Conoce a nuestro equipo de expertos</p>
            </div>
        </section>
        
        <section id="clients" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestros Clientes</h2>
                <p class="text-xl text-gray-600">Próximamente: Empresas que confían en nosotros</p>
            </div>
        </section>
        
        <section id="contact" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Contacto</h2>
                <p class="text-xl text-gray-600">Próximamente: Formulario de contacto</p>
            </div>
        </section>
    </PublicLayout>
</template>
