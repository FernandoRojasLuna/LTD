<template>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Gestión de Clientes
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Administra los clientes y testimonios del laboratorio
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button
                    @click="openCreateModal"
                    type="button"
                    class="ml-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Cliente
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="clients.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No hay clientes</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza agregando tu primer cliente.</p>
            <div class="mt-6">
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Cliente
                </button>
            </div>
        </div>

        <!-- Clients Grid -->
        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="client in clients"
                :key="client.id"
                class="relative group bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-200"
            >
                <!-- Client Logo -->
                <div class="aspect-w-16 aspect-h-9 bg-gray-50">
                    <img
                        v-if="client.logo"
                        :src="imageUrl(client.logo)"
                        :alt="client.name"
                        class="w-full h-32 object-contain p-4"
                        @error="handleImageError"
                    />
                    <div v-else class="flex items-center justify-center h-32">
                        <div class="text-2xl font-bold text-gray-400">{{ getInitials(client.name) }}</div>
                    </div>
                </div>

                <!-- Client Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                            {{ client.name }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span
                                v-if="client.featured"
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                            >
                                ⭐ Destacado
                            </span>
                            <span
                                :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    client.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]"
                            >
                                {{ client.active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                    
                    <p v-if="client.industry" class="text-sm font-medium text-indigo-600 mb-2">
                        {{ client.industry }}
                    </p>
                    
                    <p v-if="client.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
                        {{ client.description }}
                    </p>

                    <!-- Website -->
                    <div v-if="client.website" class="flex items-center text-sm text-gray-500 mb-3">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9a9 9 0 01-9-9m9 9c0 5-4 9-9 9s-9-4-9-9m9-9c0-5 4-9 9-9s9 4 9 9" />
                        </svg>
                        <a :href="client.website" target="_blank" class="hover:text-indigo-600 truncate">
                            {{ formatWebsite(client.website) }}
                        </a>
                    </div>

                    <!-- Testimonial Preview -->
                    <div v-if="client.testimonial" class="bg-gray-50 rounded-lg p-3 mt-3">
                        <p class="text-xs text-gray-600 italic line-clamp-3">
                            "{{ client.testimonial }}"
                        </p>
                        <div class="mt-2 flex items-center justify-between">
                            <div>
                                <p v-if="client.testimonial_author" class="text-xs font-medium text-gray-800">
                                    - {{ client.testimonial_author }}
                                </p>
                                <p v-if="client.testimonial_position" class="text-xs text-gray-500">
                                    {{ client.testimonial_position }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-1">
                                <template v-for="n in 5" :key="n">
                                    <svg v-if="n <= Math.round(client.rating || 0)" class="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.067 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69L9.05 2.927z" />
                                    </svg>
                                    <svg v-else class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.067 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69L9.05 2.927z" />
                                    </svg>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex space-x-1">
                        <button
                            @click="editClient(client)"
                            class="bg-white p-2 rounded-full shadow-sm text-gray-400 hover:text-indigo-600"
                            title="Editar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            @click="toggleClientStatus(client)"
                            :class="[
                                'bg-white p-2 rounded-full shadow-sm',
                                client.active 
                                    ? 'text-red-400 hover:text-red-600' 
                                    : 'text-green-400 hover:text-green-600'
                            ]"
                            :title="client.active ? 'Desactivar' : 'Activar'"
                        >
                            <svg v-if="client.active" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button
                            @click="toggleFeatured(client)"
                            :class="[
                                'bg-white p-2 rounded-full shadow-sm',
                                client.featured 
                                    ? 'text-yellow-400 hover:text-yellow-600' 
                                    : 'text-gray-400 hover:text-yellow-600'
                            ]"
                            :title="client.featured ? 'Quitar destacado' : 'Marcar como destacado'"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </button>
                        <button
                            @click="deleteClient(client)"
                            class="bg-white p-2 rounded-full shadow-sm text-gray-400 hover:text-red-600"
                            title="Eliminar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear/editar cliente -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div 
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="closeModal"
                ></div>

                <!-- Modal -->
                <div class="inline-block transform overflow-hidden rounded-xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">
                    <form @submit.prevent="saveClient">
                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white">
                                    {{ editingClient ? 'Editar Cliente' : 'Agregar Nuevo Clienteeeee' }}
                                </h3>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="text-indigo-100 hover:text-white transition-colors duration-200"
                                >
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                        <div class="bg-white px-8 py-6 max-h-[80vh] overflow-y-auto">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Columna Izquierda: Información del Cliente -->
                                <div class="space-y-6">
                                    <!-- Nombre -->
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Nombre del Cliente
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input
                                            id="name"
                                            v-model="clientForm.name"
                                            type="text"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.name }"
                                            placeholder="Ej: Acme Corporation"
                                        />
                                        <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                                    </div>

                                    <!-- Industria -->
                                    <div>
                                        <label for="industry" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Industria
                                        </label>
                                        <input
                                            id="industry"
                                            v-model="clientForm.industry"
                                            type="text"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="Ej: Tecnología, Salud, Educación"
                                        />
                                    </div>

                                    <!-- Descripción -->
                                    <div>
                                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Descripción
                                        </label>
                                        <textarea
                                            id="description"
                                            v-model="clientForm.description"
                                            rows="3"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="Breve descripción del cliente y los servicios prestados..."
                                        ></textarea>
                                    </div>

                                    <!-- Website -->
                                    <div>
                                        <label for="website" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Sitio Web
                                        </label>
                                        <input
                                            id="website"
                                            v-model="clientForm.website"
                                            type="url"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="https://cliente.com"
                                        />
                                    </div>

                                    <!-- Logo (file) -->
                                    <div>
                                        <label for="logo" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Logo (subir archivo)
                                        </label>
                                        <input
                                            ref="logoInput"
                                            @change="onLogoChange"
                                            id="logo"
                                            type="file"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">
                                            Recomendamos logos en formato PNG con fondo transparente
                                        </p>
                                    </div>

                                    <!-- Preview del Logo -->
                                    <!-- Vista previa del logo ocultada por requerimiento del usuario -->

                                    <!-- Configuraciones -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="order" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Orden
                                            </label>
                                            <input
                                                id="order"
                                                v-model.number="clientForm.order"
                                                type="number"
                                                min="0"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="0"
                                            />
                                        </div>
                                        <div class="space-y-3">
                                            <label class="flex items-center">
                                                <input
                                                    v-model="clientForm.active"
                                                    type="checkbox"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                />
                                                <span class="ml-2 text-sm text-gray-900">Cliente activo</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input
                                                    v-model="clientForm.featured"
                                                    type="checkbox"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                />
                                                <span class="ml-2 text-sm text-gray-900">Cliente destacado</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha: Testimonial -->
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Testimonial
                                        </h4>

                                        <!-- Testimonial -->
                                        <div>
                                            <label for="testimonial" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Testimonial
                                            </label>
                                            <textarea
                                                id="testimonial"
                                                v-model="clientForm.testimonial"
                                                rows="6"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="Testimonio del cliente sobre nuestros servicios..."
                                            ></textarea>
                                        </div>

                                        <!-- Autor del testimonial -->
                                        <div>
                                            <label for="testimonial_author" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Autor del Testimonial
                                            </label>
                                            <input
                                                id="testimonial_author"
                                                v-model="clientForm.testimonial_author"
                                                type="text"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="Nombre y cargo del autor"
                                            />
                                        </div>

                                        <!-- Cargo del autor -->
                                        <div>
                                            <label for="testimonial_position" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Cargo del Autor
                                            </label>
                                            <input
                                                id="testimonial_position"
                                                v-model="clientForm.testimonial_position"
                                                type="text"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="CEO, CTO, Director, etc."
                                            />
                                        </div>

                                        <!-- Rating -->
                                        <div>
                                            <label for="rating" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Calificación (1-5)
                                            </label>
                                            <select
                                                id="rating"
                                                v-model="clientForm.rating"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            >
                                                <option value="">Sin calificación</option>
                                                <option value="1">⭐ (1 estrella)</option>
                                                <option value="2">⭐⭐ (2 estrellas)</option>
                                                <option value="3">⭐⭐⭐ (3 estrellas)</option>
                                                <option value="4">⭐⭐⭐⭐ (4 estrellas)</option>
                                                <option value="5">⭐⭐⭐⭐⭐ (5 estrellas)</option>
                                            </select>
                                        </div>

                                        <!-- Vista previa del testimonial -->
                                        <div v-if="clientForm.testimonial" class="bg-gray-50 rounded-lg p-4 border">
                                            <h5 class="text-sm font-medium text-gray-900 mb-2">Vista previa</h5>
                                            <blockquote class="text-sm text-gray-600 italic mb-2">
                                                "{{ clientForm.testimonial }}"
                                            </blockquote>
                                            <div class="flex items-center justify-between">
                                                <div v-if="clientForm.testimonial_author">
                                                    <p class="text-sm font-medium text-gray-900">{{ clientForm.testimonial_author }}</p>
                                                    <p v-if="clientForm.testimonial_position" class="text-xs text-gray-500">{{ clientForm.testimonial_position }}</p>
                                                </div>
                                                <div v-if="clientForm.rating" class="text-sm">
                                                    {{ '⭐'.repeat(parseInt(clientForm.rating)) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer del Modal -->
                        <div class="bg-gray-50 px-8 py-4 flex flex-col sm:flex-row sm:justify-end gap-3 border-t border-gray-200">
                            <button
                                type="button"
                                @click="closeModal"
                                class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 w-full sm:w-auto"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="saving"
                                class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 w-full sm:w-auto"
                            >
                                <span v-if="saving" class="mr-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <svg v-if="!saving" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ saving ? 'Guardando...' : editingClient ? 'Actualizar Cliente' : 'Agregar Cliente' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div v-if="successMessage" class="fixed bottom-4 right-4 max-w-sm bg-white border border-green-200 rounded-lg shadow-lg z-50">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">¡Éxito!</p>
                        <p class="mt-1 text-sm text-gray-500">{{ successMessage }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button
                                @click="successMessage = ''"
                                class="inline-flex bg-white rounded-md p-1.5 text-gray-400 hover:text-gray-500"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-if="errorMessage" class="fixed bottom-4 right-4 max-w-sm bg-white border border-red-200 rounded-lg shadow-lg z-50">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Error</p>
                        <p class="mt-1 text-sm text-gray-500">{{ errorMessage }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button
                                @click="errorMessage = ''"
                                class="inline-flex bg-white rounded-md p-1.5 text-gray-400 hover:text-gray-500"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useClients } from '../../composables/useClients'

// Composables
const { 
    clients, 
    loading, 
    error,
    fetchClients,
    createClient, 
    updateClient, 
    deleteClient: deleteClientApi 
} = useClients()

// State
const showModal = ref(false)
const editingClient = ref(null)
const saving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// Form data
const clientForm = reactive({
    name: '',
    industry: '',
    description: '',
    website: '',
    logo: '',
    testimonial: '',
    testimonial_author: '',
    testimonial_position: '',
    rating: '',
    active: true,
    featured: false,
    order: 0
})

// Validation errors
const errors = ref({})

const logoFile = ref(null)
const logoPreview = ref('')
// template ref for the file input (used instead of $refs)
const logoInput = ref(null)

const onLogoChange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    if (!file.type.startsWith('image/')) {
        showErrorMessage('El archivo debe ser una imagen')
        e.target.value = null
        return
    }
    if (file.size > 5 * 1024 * 1024) {
        showErrorMessage('El logo es demasiado grande (máx 5MB)')
        e.target.value = null
        return
    }
    logoFile.value = file
    clientForm.logo = '' // limpiar cualquier URL residual
    const reader = new FileReader()
    reader.onload = (ev) => {
        logoPreview.value = ev.target.result
    }
    reader.readAsDataURL(file)
}

// Methods
const openCreateModal = () => {
    editingClient.value = null
    resetForm()
    clearLogo()
    logoFile.value = null
    logoPreview.value = ''
    clientForm.logo = ''
    if (logoInput.value) {
        logoInput.value.value = ''
    }
    showModal.value = true
}

const editClient = (client) => {
    editingClient.value = client
    clientForm.name = client.name
    clientForm.industry = client.industry || ''
    clientForm.description = client.description || ''
    clientForm.website = client.website || ''
    clientForm.logo = client.logo || ''
    clientForm.testimonial = client.testimonial || ''
    clientForm.testimonial_author = client.testimonial_author || ''
    clientForm.testimonial_position = client.testimonial_position || ''
    clientForm.rating = client.rating || ''
    clientForm.active = client.active
    clientForm.featured = client.featured
    clientForm.order = client.order
    errors.value = {}
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingClient.value = null
    resetForm()
    errors.value = {}
}

const resetForm = () => {
    clientForm.name = ''
    clientForm.industry = ''
    clientForm.description = ''
    clientForm.website = ''
    clientForm.logo = ''
    clientForm.testimonial = ''
    clientForm.testimonial_author = ''
    clientForm.testimonial_position = ''
    clientForm.rating = ''
    clientForm.active = true
    clientForm.featured = false
    clientForm.order = 0
}

const clearLogo = () => {
    clientForm.logo = ''
    logoFile.value = null
    logoPreview.value = ''
}

const saveClient = async () => {
    // Validación simple
    if (!clientForm.name) {
        errors.value = {
            name: 'El nombre del cliente es requerido'
        }
        return
    }

    errors.value = {}
    saving.value = true

    try {
        // Construir payload sin sobrescribir logo si no se subió un archivo nuevo
        const payload = { ...clientForm }
        if (!logoFile.value) {
            if (editingClient.value) {
                // al editar y no cambiar logo, no enviar la propiedad para evitar sobrescribirla con vacío
                delete payload.logo
            } else {
                // al crear sin archivo, enviar logo vacío (backend lo aceptará como null/omit)
                payload.logo = ''
            }
        }

        if (editingClient.value) {
            await updateClient(editingClient.value.id, payload, logoFile.value)
            showSuccessMessage('Cliente actualizado correctamente')
        } else {
            await createClient(payload, logoFile.value)
            showSuccessMessage('Cliente agregado correctamente')
        }
        // Limpiar todo el formulario y la imagen
        resetForm()
        clearLogo()
        if (logoInput.value) {
            logoInput.value.value = ''
        }
        editingClient.value = null
        closeModal()
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            showErrorMessage('Error al guardar el cliente: ' + (error.response?.data?.message || 'Error desconocido'))
        }
    } finally {
        saving.value = false
    }
}

const toggleClientStatus = async (client) => {
    try {
        await updateClient(client.id, { active: !client.active })
        showSuccessMessage(`Cliente ${client.active ? 'desactivado' : 'activado'} correctamente`)
    } catch (error) {
        showErrorMessage('Error al cambiar el estado del cliente')
    }
}

const toggleFeatured = async (client) => {
    try {
        await updateClient(client.id, { featured: !client.featured })
        showSuccessMessage(`Cliente ${client.featured ? 'removido de' : 'marcado como'} destacado`)
    } catch (error) {
        showErrorMessage('Error al cambiar el estado destacado del cliente')
    }
}

const deleteClient = async (client) => {
    if (!confirm(`¿Estás seguro de que quieres eliminar a "${client.name}"?`)) {
        return
    }

    try {
        await deleteClientApi(client.id)
        showSuccessMessage('Cliente eliminado correctamente')
    } catch (error) {
        showErrorMessage('Error al eliminar el cliente')
    }
}

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .substring(0, 2)
}

const formatWebsite = (url) => {
    return url.replace(/^https?:\/\//, '').replace(/\/$/, '')
}

const handleImageError = (event) => {
    if (!event.target.src.includes('data:image/svg+xml')) {
        const svgPlaceholder = `data:image/svg+xml;base64,${btoa(`
            <svg width="200" height="100" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f3f4f6"/>
                <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="12" fill="#9ca3af" text-anchor="middle" dy=".3em">
                    Logo no disponible
                </text>
            </svg>
        `)}`
        event.target.src = svgPlaceholder
    }
}

const showSuccessMessage = (message) => {
    successMessage.value = message
    setTimeout(() => {
        successMessage.value = ''
    }, 3000)
}

const showErrorMessage = (message) => {
    errorMessage.value = message
    setTimeout(() => {
        errorMessage.value = ''
    }, 3000)
}

// Lifecycle
onMounted(() => {
    fetchClients()
})

const imageUrl = (path) => {
    if (!path) return '/'
    if (path.startsWith('http') || path.startsWith('data:')) return path
    // Si ya contiene '/storage/' no añadir doble prefijo
    if (path.startsWith('/storage/') || path.startsWith('storage/')) return path.replace(/^\/+/, '/')
    const normalized = path.replace(/^\/+/, '')
    return `/storage/${normalized}`
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 2;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 3;
}
</style>
