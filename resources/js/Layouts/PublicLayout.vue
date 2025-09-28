<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 dark:text-gray-100">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left: logo -->
                <div class="flex items-center">
                    <Link href="/" class="flex items-center space-x-3 no-underline">
                        <!-- Logo switches between light/dark versions -->
                        <img :src="isDark ? '/images/ltdoscuro.svg' : '/images/ltdclaro.svg'" :alt="isDark ? 'LTD Logo (oscuro)' : 'LTD Logo (claro)'" class="w-9 h-9 object-contain" />
                        <div class="leading-tight">
                            <div class="text-2xl md:text-3xl font-extrabold tracking-tight md:tracking-tighter text-black dark:text-gray-100 uppercase font-display drop-shadow-sm brand-font">LTD</div>
                        </div>
                    </Link>
                </div>

                <!-- Right: nav links + auth (desktop) -->
                <div class="hidden md:flex md:items-center md:space-x-6">
                    <nav class="flex space-x-6">
                        <button @click.prevent="navigateTo('home')" class="relative text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 text-[15px] font-medium transition-colors duration-300 after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-indigo-600 hover:after:w-full after:transition-all">Inicio</button>
                        <button @click.prevent="navigateTo('about')" class="relative text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-[15px] font-medium transition-colors duration-300 after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-indigo-600 hover:after:w-full after:transition-all">Sobre Nosotros</button>
                        <button @click.prevent="navigateTo('projects')" class="relative text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-[15px] font-medium transition-colors duration-300 after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-indigo-600 hover:after:w-full after:transition-all">Proyectos</button>
                        <button @click.prevent="navigateTo('clients')" class="relative text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-[15px] font-medium transition-colors duration-300 after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-indigo-600 hover:after:w-full after:transition-all">Clientes</button>
                        <button @click.prevent="navigateTo('staff')" class="relative text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-[15px] font-medium transition-colors duration-300 after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-indigo-600 hover:after:w-full after:transition-all">Equipo</button>
                        <button @click.prevent="navigateTo('contact')" class="relative text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-[15px] font-medium transition-colors duration-300 after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-indigo-600 hover:after:w-full after:transition-all">Contacto</button>
                    </nav>

                    <div class="flex items-center md:space-x-4">
                        <!-- Theme toggle button -->
                        <button @click="toggleTheme" :aria-pressed="isDark" title="Cambiar tema" class="text-gray-500 hover:text-indigo-600 px-3 py-2">
                            <template v-if="isDark">
                                <i class="fa-solid fa-sun"></i>
                            </template>
                            <template v-else>
                                <i class="fa-solid fa-moon"></i>
                            </template>
                        </button>

                        <template v-if="$page.props.auth.user">
                            <!-- Dashboard icon link -->
                            <Link :href="route('dashboard')" title="Dashboard" aria-label="Dashboard" class="text-gray-500 hover:text-indigo-600 px-3 py-2">
                                <i class="fa-solid fa-user"></i>
                            </Link>

                            
                        </template>
                        <template v-else>
                            <!-- Login icon link -->
                            <Link :href="route('login')" title="Iniciar sesión" aria-label="Iniciar sesión" class="text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2">
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </Link>
                        </template>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <!-- Mobile: theme toggle + menu -->
                    <button @click="toggleTheme" :aria-pressed="isDark" title="Cambiar tema" class="text-gray-500 hover:text-gray-700 p-2 mr-2">
                        <template v-if="isDark">
                            <i class="fa-solid fa-sun"></i>
                        </template>
                        <template v-else>
                            <i class="fa-solid fa-moon"></i>
                        </template>
                    </button>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen" aria-controls="mobile-panel" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg border border-transparent hover:border-gray-700/30 transition-shadow shadow-sm bg-white/0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
            
            <!-- Mobile menu -->
            <!-- Floating mobile panel (small, rounded) -->
            <div id="mobile-panel" v-show="mobileMenuOpen" class="md:hidden fixed top-16 right-4 z-50">
                <div class="w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3">
                        <button @click.prevent="navigateTo('home')" class="w-full text-left block text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-2 text-sm font-medium">Inicio</button>
                        <button @click.prevent="navigateTo('about')" class="w-full text-left block text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-2 text-sm">Sobre Nosotros</button>
                        <button @click.prevent="navigateTo('projects')" class="w-full text-left block text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-2 text-sm">Proyectos</button>
                        <button @click.prevent="navigateTo('clients')" class="w-full text-left block text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-2 text-sm">Clientes</button>
                        <button @click.prevent="navigateTo('staff')" class="w-full text-left block text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-2 text-sm">Equipo</button>
                        <button @click.prevent="navigateTo('contact')" class="w-full text-left block text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-2 text-sm">Contacto</button>

                        <!-- Login CTA -->
                        <div class="mt-3">
                            <button @click.prevent="goToLogin" class="w-full inline-flex justify-center items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow">Iniciar sesión</button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="pt-16">
            <slot />
        </main>
        
        <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-gray-800 text-white dark:text-gray-200">
    <div class="max-w-4xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-6">
            <!-- Top: title + short description (mobile only) -->
            <div class="text-center mb-4 md:hidden">
                <h3 class="text-lg font-semibold mb-1">Laboratorio de Transformación Digital</h3>
                <p class="text-gray-400 text-xs mx-auto max-w-lg">Innovación y tecnología para el futuro. Transformamos ideas en soluciones digitales.</p>
            </div>

            <!-- Middle: two columns on phones, md keeps 2 columns and lg shows 3 columns (desktop restored) -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 items-start">
                <!-- First column on md+ contains title + description (restores desktop layout) -->
                <div class="hidden md:block md:col-span-1">
                    <h3 class="text-lg font-semibold mb-1">Laboratorio de Transformación Digital</h3>
                    <p class="text-gray-400 text-xs">Innovación y tecnología para el futuro. Transformamos ideas en soluciones digitales.</p>
                </div>

                <!-- Links column (left on small screens) -->
                <div>
                    <h4 class="text-sm font-medium mb-1 text-gray-200 text-left">Enlaces</h4>
                    <ul class="grid grid-cols-1 gap-1 text-sm text-left">
                        <li><button @click.prevent="navigateTo('about')" class="text-gray-300 hover:text-white">Sobre Nosotros</button></li>
                        <li><button @click.prevent="navigateTo('projects')" class="text-gray-300 hover:text-white">Proyectos</button></li>
                        <li><button @click.prevent="navigateTo('staff')" class="text-gray-300 hover:text-white">Equipo</button></li>
                        <li><button @click.prevent="navigateTo('contact')" class="text-gray-300 hover:text-white">Contacto</button></li>
                    </ul>
                </div>

                <!-- Contact + socials column (right on small screens) -->
                <div>
                    <h4 class="text-sm font-medium mb-1 text-gray-200 text-left">Contacto</h4>
                    <p class="text-gray-300 mb-1 text-xs text-left">Email: <span class="text-gray-100">{{ contact.emails && contact.emails.length ? contact.emails[0] : 'info@laboratorio.com' }}</span></p>
                    <p class="text-gray-300 mb-1 text-xs text-left">Teléfono: <span class="text-gray-100">{{ contact.phones && contact.phones.length ? contact.phones[0] : '+1 (555) 123-4567' }}</span></p>
                    <div class="mt-3 flex items-center justify-start space-x-2">
                        <a :href="findSocialUrl('instagram')" target="_blank" rel="noopener" :aria-label="'Instagram'" class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-white shadow text-gray-700 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>

                        <a :href="findSocialUrl('facebook')" target="_blank" rel="noopener" :aria-label="'Facebook'" class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-white shadow text-gray-700 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>

                        <a :href="findSocialUrl('tiktok')" target="_blank" rel="noopener" :aria-label="'TikTok'" class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-white shadow text-gray-700 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-brands fa-tiktok text-xs"></i>
                        </a>
                    </div>
                </div>
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
// Theme state: true when dark
const isDark = ref(false)

function applyHtmlClass(dark) {
    try {
        const html = document.documentElement
        if (dark) html.classList.add('dark')
        else html.classList.remove('dark')
    } catch (e) {
        // ignore
    }
}

function initTheme() {
    // Priority: explicit localStorage choice > prefers-color-scheme > default light
    try {
        const stored = localStorage.getItem('ltd-theme')
        if (stored === 'dark' || stored === 'light') {
            isDark.value = stored === 'dark'
            applyHtmlClass(isDark.value)
            return
        }

        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
        isDark.value = !!prefersDark
        applyHtmlClass(isDark.value)
    } catch (e) {
        // fallback
        isDark.value = false
        applyHtmlClass(false)
    }
}

function toggleTheme() {
    isDark.value = !isDark.value
    applyHtmlClass(isDark.value)
    try { localStorage.setItem('ltd-theme', isDark.value ? 'dark' : 'light') } catch (e) {}
}

// Contact info loaded from admin settings
const contact = reactive({ address: '', phones: [], emails: [], socials: [] })
const { get } = useContactSettings()

onMounted(async () => {
    initTheme()
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

/**
 * Close mobile menu and navigate to login page (absolute URL for local dev).
 */
function goToLogin() {
    try {
        mobileMenuOpen.value = false
    } catch (e) {}
    // Absolute redirect to the local dev login page
    window.location.href = 'http://localhost:8080/login'
}
</script>

<style>
/* Local brand font: coloca Sacana.ttf en public/fonts/sakana/Sakana.ttf */
@font-face {
    font-family: 'Sakana';
    src: url('/fonts/sakana/Sakana.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

.brand-font {
    font-family: 'Sakana', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
}
</style>
