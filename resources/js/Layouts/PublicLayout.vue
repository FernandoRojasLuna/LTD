<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold text-indigo-600">LTD</h1>
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
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-2xl font-bold mb-4">Laboratorio de Transformación Digital</h3>
                        <p class="text-gray-300">Innovación y tecnología para el futuro. Transformamos ideas en soluciones digitales.</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Enlaces</h4>
                        <ul class="space-y-2">
                            <li><a href="#about" class="text-gray-300 hover:text-white">Sobre Nosotros</a></li>
                            <li><a href="#projects" class="text-gray-300 hover:text-white">Proyectos</a></li>
                            <li><a href="#staff" class="text-gray-300 hover:text-white">Equipo</a></li>
                            <li><a href="#contact" class="text-gray-300 hover:text-white">Contacto</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                        <p class="text-gray-300 mb-2">Email: info@laboratorio.com</p>
                        <p class="text-gray-300 mb-2">Teléfono: +1 (555) 123-4567</p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="text-gray-300 hover:text-white">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                    <p class="text-gray-300">&copy; 2025 Laboratorio de Transformación Digital. Todos los derechos reservados.</p>
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
