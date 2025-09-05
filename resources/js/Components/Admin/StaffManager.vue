<template>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Gestión del Equipo
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Administra los miembros del equipo del laboratorio
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
                    Nuevo Miembro
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="staffMembers.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No hay miembros del equipo</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza agregando el primer miembro de tu equipo.</p>
            <div class="mt-6">
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Miembro
                </button>
            </div>
        </div>

        <!-- Staff Grid -->
        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="member in staffMembers"
                :key="member.id"
                class="relative group bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-200"
            >
                <!-- Member Image -->
                <div class="aspect-w-16 aspect-h-9">
                        <img
                            :src="imageUrl(member.image)"
                            :alt="member.name"
                            class="w-full h-48 object-cover"
                            @error="handleImageError"
                        />
                </div>

                <!-- Member Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                            {{ member.name }}
                        </h3>
                        <span
                            :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                member.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]"
                        >
                            {{ member.active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
                    <p class="text-sm font-medium text-indigo-600 mb-2">
                        {{ member.position }}
                    </p>
                    
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                        {{ member.bio }}
                    </p>

                    <!-- Contact Info -->
                    <div class="flex items-center space-x-3 text-sm text-gray-500">
                        <span v-if="member.email" class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email
                        </span>
                        <span v-if="member.linkedin" class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd" />
                            </svg>
                            LinkedIn
                        </span>
                        <span v-if="member.github" class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                            </svg>
                            GitHub
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex space-x-1">
                        <button
                            @click="editMember(member)"
                            class="bg-white p-2 rounded-full shadow-sm text-gray-400 hover:text-indigo-600"
                            title="Editar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            @click="toggleMemberStatus(member)"
                            :class="[
                                'bg-white p-2 rounded-full shadow-sm',
                                member.active 
                                    ? 'text-red-400 hover:text-red-600' 
                                    : 'text-green-400 hover:text-green-600'
                            ]"
                            :title="member.active ? 'Desactivar' : 'Activar'"
                        >
                            <svg v-if="member.active" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button
                            @click="deleteMember(member)"
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

        <!-- Modal para crear/editar miembro -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div 
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="closeModal"
                ></div>

                <!-- Modal -->
                <div class="inline-block transform overflow-hidden rounded-xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">
                    <form @submit.prevent="saveMember">
                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white">
                                    {{ editingMember ? 'Editar Miembro del Equipo' : 'Agregar Nuevo Miembro' }}
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
                                <!-- Columna Izquierda: Información Personal -->
                                <div class="space-y-6">
                                    <!-- Nombre -->
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Nombre Completo
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input
                                            id="name"
                                            v-model="memberForm.name"
                                            type="text"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.name }"
                                            placeholder="Ej: Dr. Fernando Rojas"
                                        />
                                        <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                                    </div>

                                    <!-- Posición -->
                                    <div>
                                        <label for="position" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Posición/Cargo
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input
                                            id="position"
                                            v-model="memberForm.position"
                                            type="text"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.position }"
                                            placeholder="Ej: Director del Laboratorio"
                                        />
                                        <p v-if="errors.position" class="mt-2 text-sm text-red-600">{{ errors.position }}</p>
                                    </div>

                                    <!-- Biografía -->
                                    <div>
                                        <label for="bio" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Biografía
                                        </label>
                                        <textarea
                                            id="bio"
                                            v-model="memberForm.bio"
                                            rows="4"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="Descripción profesional del miembro del equipo..."
                                        ></textarea>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Email
                                        </label>
                                        <input
                                            id="email"
                                            v-model="memberForm.email"
                                            type="email"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="email@ejemplo.com"
                                        />
                                    </div>

                                    <!-- LinkedIn -->
                                    <div>
                                        <label for="linkedin" class="block text-sm font-semibold text-gray-900 mb-2">
                                            LinkedIn
                                        </label>
                                        <input
                                            id="linkedin"
                                            v-model="memberForm.linkedin"
                                            type="url"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="https://linkedin.com/in/username"
                                        />
                                    </div>

                                    <!-- GitHub -->
                                    <div>
                                        <label for="github" class="block text-sm font-semibold text-gray-900 mb-2">
                                            GitHub
                                        </label>
                                        <input
                                            id="github"
                                            v-model="memberForm.github"
                                            type="url"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="https://github.com/username"
                                        />
                                    </div>

                                    <!-- Configuraciones -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="order" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Orden
                                            </label>
                                            <input
                                                id="order"
                                                v-model.number="memberForm.order"
                                                type="number"
                                                min="0"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="0"
                                            />
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center">
                                                <input
                                                    v-model="memberForm.active"
                                                    type="checkbox"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                />
                                                <span class="ml-2 text-sm text-gray-900">Miembro activo</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha: Imagen -->
                                <div class="space-y-6">
                                    <!-- Imagen de perfil -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Foto de Perfil
                                        </h4>

                                        <!-- Input file: sólo archivos locales -->
                                        <div class="space-y-3">
                                            <input
                                                ref="imageInput"
                                                @change="onFileChange"
                                                type="file"
                                                accept="image/*"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700"
                                            />
                                            <p class="text-xs text-gray-500">
                                                Sube una imagen desde tu equipo (jpeg, png, gif, webp). Recomendadas 400x400px.
                                            </p>
                                        </div>

                                        <!-- Preview -->
                                        <div v-if="imagePreview || memberForm.image" class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                                            <div class="relative group">
                                                <img
                                                    :src="imagePreview || imageUrl(memberForm.image)"
                                                    :alt="memberForm.name"
                                                    class="w-32 h-32 object-cover rounded-full border border-gray-200 shadow-sm mx-auto"
                                                    @error="handleImageError"
                                                />
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-full flex items-center justify-center">
                                                    <button
                                                        type="button"
                                                        @click="clearImage"
                                                        class="opacity-0 group-hover:opacity-100 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg transition-all duration-200"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <p v-if="errors.image" class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ errors.image }}
                                        </p>
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
                                {{ saving ? 'Guardando...' : editingMember ? 'Actualizar Miembro' : 'Agregar Miembro' }}
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
import { useStaff } from '../../composables/useStaff'

// Composables
const { 
    staff: staffMembers, 
    loading, 
    error,
    fetchStaff,
    createStaffMember, 
    updateStaffMember, 
    deleteStaffMember 
} = useStaff()

// State
const showModal = ref(false)
const editingMember = ref(null)
const saving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// small inline SVG placeholder to avoid 404
const avatarPlaceholder = `data:image/svg+xml;base64,${btoa(`<svg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 200 200'><rect width='100%' height='100%' fill='%23f3f4f6'/><circle cx='100' cy='70' r='30' fill='%23d1d5db'/><path d='M60 140 Q60 120 100 120 Q140 120 140 140 L140 160 Q140 180 100 180 Q60 180 60 160 Z' fill='%23d1d5db'/><text x='50%' y='90%' font-family='Arial, sans-serif' font-size='10' fill='%239ca3af' text-anchor='middle'>Sin foto</text></svg>`)}`

// Form data
const memberForm = reactive({
    name: '',
    position: '',
    bio: '',
    image: '',
    email: '',
    linkedin: '',
    github: '',
    active: true,
    order: 0
})

// Validation errors
const errors = ref({})

// Methods
const openCreateModal = () => {
    editingMember.value = null
    resetForm()
    showModal.value = true
}

const editMember = (member) => {
    editingMember.value = member
    memberForm.name = member.name
    memberForm.position = member.position
    memberForm.bio = member.bio || ''
    memberForm.image = member.image || ''
    memberForm.email = member.email || ''
    memberForm.linkedin = member.linkedin || ''
    memberForm.github = member.github || ''
    memberForm.active = member.active
    memberForm.order = member.order
    errors.value = {}
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingMember.value = null
    resetForm()
    errors.value = {}
}

const resetForm = () => {
    memberForm.name = ''
    memberForm.position = ''
    memberForm.bio = ''
    memberForm.image = ''
    imageFile.value = null
    imagePreview.value = ''
    memberForm.email = ''
    memberForm.linkedin = ''
    memberForm.github = ''
    memberForm.active = true
    memberForm.order = 0
}

const clearImage = () => {
    memberForm.image = ''
    imageFile.value = null
    imagePreview.value = ''
}

const imageFile = ref(null)
const imagePreview = ref('')

const onFileChange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    // validar tipo y tamaño client-side
    if (!file.type.startsWith('image/')) {
        showErrorMessage('El archivo debe ser una imagen')
        e.target.value = null
        return
    }
    if (file.size > 5 * 1024 * 1024) {
        showErrorMessage('La imagen es demasiado grande (máx 5MB)')
        e.target.value = null
        return
    }
    imageFile.value = file
    memberForm.image = '' // limpiar cualquier URL residual
    const reader = new FileReader()
    reader.onload = (ev) => {
        imagePreview.value = ev.target.result
    }
    reader.readAsDataURL(file)
}

const saveMember = async () => {
    // Validación simple
    if (!memberForm.name || !memberForm.position) {
        errors.value = {
            name: !memberForm.name ? 'El nombre es requerido' : '',
            position: !memberForm.position ? 'La posición es requerida' : ''
        }
        return
    }

    errors.value = {}
    saving.value = true

    try {
                if (editingMember.value) {
            console.log('Saving member (update):', memberForm, imageFile.value)
            await updateStaffMember(editingMember.value.id, memberForm, imageFile.value)
            showSuccessMessage('Miembro del equipo actualizado correctamente')
        } else {
            console.log('Saving member (create):', memberForm, imageFile.value)
            await createStaffMember(memberForm, imageFile.value)
            showSuccessMessage('Miembro del equipo agregado correctamente')
        }
        closeModal()
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            showErrorMessage('Error al guardar el miembro del equipo: ' + (error.response?.data?.message || 'Error desconocido'))
        }
    } finally {
        saving.value = false
    }
}

const toggleMemberStatus = async (member) => {
    try {
        await updateStaffMember(member.id, { active: !member.active })
        showSuccessMessage(`Miembro ${member.active ? 'desactivado' : 'activado'} correctamente`)
    } catch (error) {
        showErrorMessage('Error al cambiar el estado del miembro')
    }
}

const deleteMember = async (member) => {
    if (!confirm(`¿Estás seguro de que quieres eliminar a "${member.name}" del equipo?`)) {
        return
    }

    try {
        await deleteStaffMember(member.id)
        showSuccessMessage('Miembro del equipo eliminado correctamente')
    } catch (error) {
        showErrorMessage('Error al eliminar el miembro del equipo')
    }
}

const handleImageError = (event) => {
    if (!event.target.src.includes('data:image/svg+xml')) {
        const svgPlaceholder = `data:image/svg+xml;base64,${btoa(`
            <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f3f4f6"/>
                <circle cx="100" cy="75" r="25" fill="#d1d5db"/>
                <path d="M60 140 Q60 120 100 120 Q140 120 140 140 L140 160 Q140 180 100 180 Q60 180 60 160 Z" fill="#d1d5db"/>
                <text x="50%" y="90%" font-family="Arial, sans-serif" font-size="10" fill="#9ca3af" text-anchor="middle">
                    Sin foto
                </text>
            </svg>
        `)}`
        event.target.src = svgPlaceholder
    }
}

const imageUrl = (path) => {
    if (!path) return avatarPlaceholder
    // Si la ruta ya es una URL absoluta, devolverla
    if (path.startsWith('http') || path.startsWith('data:')) return path
    // Si ya contiene '/storage/' no añadir doble prefijo
    if (path.startsWith('/storage/') || path.startsWith('storage/')) return path.replace(/^\/+/, '/')
    // Normalizar y prefijar /storage/
    const normalized = path.replace(/^\/+/, '')
    return `/storage/${normalized}`
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
    fetchStaff()
})
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 2;
}
</style>
