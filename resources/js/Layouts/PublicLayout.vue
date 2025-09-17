<template>
    <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                <h1 class="text-2xl font-semibold text-indigo-600 tracking-tight">LTD</h1>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <a href="#home" class="text-gray-900 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Inicio</a>
                            <a href="#about" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Sobre Nosotros</a>
                            <a href="#projects" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Proyectos</a>
                            <a href="#staff" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Equipo</a>
                            <a href="#clients" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Clientes</a>
                            <a href="#contact" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Contacto</a>
                        </div>
                    </div>
                    
                    <!-- Auth Links -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                                Admin
                            </Link>
                        </template>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 p-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div v-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#home" class="block text-gray-900 hover:text-indigo-600 px-3 py-2 text-base font-medium">Inicio</a>
                    <a href="#about" class="block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Sobre Nosotros</a>
                    <a href="#projects" class="block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Proyectos</a>
                    <a href="#staff" class="block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Equipo</a>
                    <a href="#clients" class="block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Clientes</a>
                    <a href="#contact" class="block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Contacto</a>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="pt-16">
            <slot />
        </main>
        
        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Laboratorio de Transformación Digital</h3>
                        <p class="text-gray-400">Innovación y tecnología para el futuro. Transformamos ideas en soluciones digitales.</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-4 text-gray-200">Enlaces</h4>
                        <ul class="space-y-2">
                            <li><a href="#about" class="text-gray-300 hover:text-white">Sobre Nosotros</a></li>
                            <li><a href="#projects" class="text-gray-300 hover:text-white">Proyectos</a></li>
                            <li><a href="#staff" class="text-gray-300 hover:text-white">Equipo</a></li>
                            <li><a href="#contact" class="text-gray-300 hover:text-white">Contacto</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-4 text-gray-200">Contacto</h4>
                        <p class="text-gray-300 mb-2">Email: info@laboratorio.com</p>
                        <p class="text-gray-300 mb-2">Teléfono: +1 (555) 123-4567</p>
                        <div class="flex space-x-4 mt-4 text-gray-300">
                            <a href="#" class="hover:text-white transition"><i class="fa-brands fa-twitter fa-lg"></i></a>
                            <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin fa-lg"></i></a>
                        </div>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-gray-800 text-center">
                    <p class="text-gray-400">&copy; 2025 Laboratorio de Transformación Digital. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const mobileMenuOpen = ref(false)
</script>
