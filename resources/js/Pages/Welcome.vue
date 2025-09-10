<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import AboutLab from '@/Components/AboutLab.vue';
import ProjectsSection from '@/Components/ProjectsSection.vue';
import TeamSection from '@/Components/TeamSection.vue';
import ClientsSection from '@/Components/ClientsSection.vue';
import ContactForm from '@/Components/ContactForm.vue';

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

        <!-- Team Section -->
        <TeamSection />
        
    <!-- Clients Section -->
    <ClientsSection />

    <!-- Contact Section (form) -->
    <ContactForm />
    </PublicLayout>
</template>
