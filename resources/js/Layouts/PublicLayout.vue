<template>
    <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center space-x-3">
                            <!-- SVG mark -->
                            <div class="w-10 h-10 bg-indigo-50 rounded-md flex items-center justify-center overflow-hidden">
                                <!-- Embedded ltd.svg (scaled to fit header) -->
                                    <img src="/images/ltd.svg" alt="LTD Logo" width="40" height="40" />
                            </div>
                            <div class="leading-tight">
                                <div class="text-xl font-extrabold text-indigo-600">LTD</div>
                                <div class="text-xs text-gray-500 -mt-0.5">Laboratorio de Transformación Digital</div>
                            </div>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <button @click.prevent="navigateTo('home')" class="text-gray-900 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Inicio</button>
                            <button @click.prevent="navigateTo('about')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Sobre Nosotros</button>
                            <button @click.prevent="navigateTo('projects')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Proyectos</button>
                            <button @click.prevent="navigateTo('staff')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Equipo</button>
                            <button @click.prevent="navigateTo('clients')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Clientes</button>
                            <button @click.prevent="navigateTo('contact')" class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Contacto</button>
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
                    <button @click.prevent="navigateTo('home')" class="w-full text-left block text-gray-900 hover:text-indigo-600 px-3 py-2 text-base font-medium">Inicio</button>
                    <button @click.prevent="navigateTo('about')" class="w-full text-left block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Sobre Nosotros</button>
                    <button @click.prevent="navigateTo('projects')" class="w-full text-left block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Proyectos</button>
                    <button @click.prevent="navigateTo('staff')" class="w-full text-left block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Equipo</button>
                    <button @click.prevent="navigateTo('clients')" class="w-full text-left block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Clientes</button>
                    <button @click.prevent="navigateTo('contact')" class="w-full text-left block text-gray-500 hover:text-indigo-600 px-3 py-2 text-base font-medium">Contacto</button>
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
                            <li><button @click.prevent="navigateTo('about')" class="text-gray-300 hover:text-white">Sobre Nosotros</button></li>
                            <li><button @click.prevent="navigateTo('projects')" class="text-gray-300 hover:text-white">Proyectos</button></li>
                            <li><button @click.prevent="navigateTo('staff')" class="text-gray-300 hover:text-white">Equipo</button></li>
                            <li><button @click.prevent="navigateTo('contact')" class="text-gray-300 hover:text-white">Contacto</button></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-4 text-gray-200">Contacto</h4>
                        <p class="text-gray-300 mb-2">Email: <span class="text-gray-100">{{ contact.emails && contact.emails.length ? contact.emails[0] : 'info@laboratorio.com' }}</span></p>
                        <p class="text-gray-300 mb-2">Teléfono: <span class="text-gray-100">{{ contact.phones && contact.phones.length ? contact.phones[0] : '+1 (555) 123-4567' }}</span></p>
                        <div class="mt-6 flex items-center space-x-3">
                            <!-- Match ContactForm.vue markup: always render the three social anchors, findSocialUrl returns '#' if missing -->
                            <a :href="findSocialUrl('instagram')" target="_blank" rel="noopener" :aria-label="'Instagram'" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow text-gray-700 hover:bg-indigo-600 hover:text-white transition transform hover:-translate-y-0.5">
                                <i class="fa-brands fa-instagram"></i>
                            </a>

                            <a :href="findSocialUrl('facebook')" target="_blank" rel="noopener" :aria-label="'Facebook'" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow text-gray-700 hover:bg-indigo-600 hover:text-white transition transform hover:-translate-y-0.5">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>

                            <a :href="findSocialUrl('tiktok')" target="_blank" rel="noopener" :aria-label="'TikTok'" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow text-gray-700 hover:bg-indigo-600 hover:text-white transition transform hover:-translate-y-0.5">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
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
import { ref, reactive, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import useContactSettings from '@/composables/useContactSettings'

const mobileMenuOpen = ref(false)

// Contact info loaded from admin settings
const contact = reactive({ address: '', phones: [], emails: [], socials: [] })
const { get } = useContactSettings()

onMounted(async () => {
    try {
        const data = await get()
        contact.address = data.address || ''
        contact.phones = data.phones || []
        contact.emails = data.emails || []
        contact.socials = data.socials || []
    } catch (e) {
        // ignore: keep footer default values if fetch fails
        console.warn('No se pudieron cargar ajustes de contacto:', e)
    }
})

// Return an inline SVG string for known social networks (same as ContactForm.vue)
function socialIcon(name) {
    const n = (name || '').toLowerCase()
    if (n.includes('instagram') || n.includes('ig')) return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3z"/></svg>'
    if (n.includes('facebook') || n.includes('fb')) return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3h2V1h-2c-2.757 0-5 2.243-5 5v2H6v3h2v8h3v-8h2.5l.5-3H11V6c0-.552.448-1 1-1z"/></svg>'
    if (n.includes('linkedin') || n.includes('ln')) return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM0 8h5v14H0V8zm7 0h4.7v2h.1c.7-1.3 2.4-2.7 5-2.7 5.3 0 6.3 3.5 6.3 8V22h-5v-7.5c0-1.8 0-4.1-2.5-4.1-2.5 0-2.9 2-2.9 4v7.6H7V8z"/></svg>'
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>'
}

function findSocialUrl(name) {
    const key = (name || '').toLowerCase()
    const s = (contact.socials || []).find(x => (x.name || '').toLowerCase().includes(key))
    return s ? s.url : '#'
}

/**
 * Smooth-scroll to an element id and close mobile menu.
 * If the element is not on the page, fallback to setting the hash (navigates to current route with hash).
 */
function navigateTo(id) {
    const el = document.getElementById(id)
    const header = document.querySelector('nav')
    const headerOffset = header ? header.offsetHeight : 64

    if (el) {
        const top = el.getBoundingClientRect().top + window.pageYOffset - headerOffset - 12
        window.scrollTo({ top, behavior: 'smooth' })
        mobileMenuOpen.value = false
    } else {
        // Fallback: set the URL hash so the browser navigates to the section if present on the page
        mobileMenuOpen.value = false
        const base = window.location.href.split('#')[0]
        window.location.href = `${base}#${id}`
    }
}
</script>
