<template>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Gestión de Banners
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Administra los banners del carrusel de la página principal
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button
                    @click="openCreateModal"
                    type="button"
                    class="ml-3 inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Banner
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="banners.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No hay banners</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer banner para el carrusel.</p>
            <div class="mt-6">
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Banner
                </button>
            </div>
        </div>

        <!-- Banners Grid -->
        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="banner in banners"
                :key="banner.id"
                class="relative group bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow duration-200"
            >
                <!-- Banner Image -->
                <div class="aspect-w-16 aspect-h-9">
                    <img
                        :src="banner.image"
                        :alt="banner.title"
                        class="w-full h-48 object-cover"
                        @error="handleImageError"
                    />
                </div>

                <!-- Banner Content -->
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900 truncate">
                            {{ banner.title }}
                        </h3>
                        <span
                            :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                banner.is_active 
                                    ? 'bg-green-100 text-green-800' 
                                    : 'bg-gray-100 text-gray-800'
                            ]"
                        >
                            {{ banner.is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
                    <p v-if="banner.subtitle" class="mt-1 text-sm text-gray-500 truncate">
                        {{ banner.subtitle }}
                    </p>
                    
                    <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                        <span>Orden: {{ banner.order }}</span>
                        <span>{{ formatDate(banner.created_at) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex space-x-1">
                        <button
                            @click="editBanner(banner)"
                            class="bg-white p-2 rounded-full shadow-sm text-gray-400 hover:text-blue-600"
                            title="Editar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            @click="toggleBannerStatus(banner)"
                            :class="[
                                'bg-white p-2 rounded-full shadow-sm',
                                banner.is_active 
                                    ? 'text-red-400 hover:text-red-600' 
                                    : 'text-green-400 hover:text-green-600'
                            ]"
                            :title="banner.is_active ? 'Desactivar' : 'Activar'"
                        >
                            <svg v-if="banner.is_active" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button
                            @click="deleteBanner(banner)"
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

        <!-- Modal para crear/editar banner -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div 
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="closeModal"
                ></div>

                <!-- Modal -->
                                <!-- Modal -->
                <div class="inline-block transform overflow-hidden rounded-xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl sm:align-middle">
                    <form @submit.prevent="saveBanner">
                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white">
                                    {{ editingBanner ? 'Editar Banner' : 'Crear Nuevo Banner' }}
                                </h3>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="text-blue-100 hover:text-white transition-colors duration-200"
                                >
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido del Modal -->
                                                <!-- Contenido del Modal -->
                        <div class="bg-white px-8 py-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Columna Izquierda: Información Básica -->
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Información del Banner
                                        </h4>

                                        <!-- Título -->
                                        <div class="mb-6">
                                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                                Título del Banner <span class="text-red-500">*</span>
                                            </label>
                                            <input
                                                v-model="bannerForm.title"
                                                type="text"
                                                id="title"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"
                                                :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.title }"
                                                placeholder="Ej: Innovación Tecnológica"
                                            />
                                            <p v-if="errors.title" class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ errors.title }}
                                            </p>
                                        </div>

                                        <!-- Subtítulo -->
                                        <div class="mb-6">
                                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                                Subtítulo
                                            </label>
                                            <textarea
                                                v-model="bannerForm.subtitle"
                                                id="subtitle"
                                                rows="3"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm resize-none"
                                                :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.subtitle }"
                                                placeholder="Ej: Transformando el futuro digital con tecnología innovadora"
                                            ></textarea>
                                            <p class="mt-1 text-xs text-gray-500">Descripción que aparecerá debajo del título</p>
                                            <p v-if="errors.subtitle" class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ errors.subtitle }}
                                            </p>
                                        </div>

                                        <!-- Configuración -->
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                                                <svg class="h-4 w-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                                                </svg>
                                                Configuración
                                            </h5>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <!-- Orden -->
                                                <div>
                                                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                                        Orden
                                                    </label>
                                                    <input
                                                        v-model.number="bannerForm.order"
                                                        type="number"
                                                        id="order"
                                                        min="0"
                                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"
                                                        :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.order }"
                                                        placeholder="0"
                                                    />
                                                    <p class="mt-1 text-xs text-gray-500">0 = orden automático</p>
                                                </div>

                                                <!-- Estado -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Estado
                                                    </label>
                                                    <div class="flex items-center justify-center h-12 bg-gray-50 rounded-lg border border-gray-200">
                                                        <label class="flex items-center cursor-pointer">
                                                            <div class="relative">
                                                                <input
                                                                    v-model="bannerForm.active"
                                                                    type="checkbox"
                                                                    class="sr-only"
                                                                />
                                                                <div
                                                                    :class="[
                                                                        'block w-14 h-8 rounded-full transition-colors duration-200',
                                                                        bannerForm.active ? 'bg-blue-600' : 'bg-gray-300'
                                                                    ]"
                                                                ></div>
                                                                <div
                                                                    :class="[
                                                                        'absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-200 shadow-md',
                                                                        bannerForm.active ? 'transform translate-x-6' : ''
                                                                    ]"
                                                                ></div>
                                                            </div>
                                                            <span class="ml-3 text-sm font-medium" :class="bannerForm.active ? 'text-blue-600' : 'text-gray-500'">
                                                                {{ bannerForm.active ? 'Activo' : 'Inactivo' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha: Imagen -->
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Imagen del Banner
                                            <span class="text-red-500 ml-1">*</span>
                                        </h4>

                                        <!-- Selector de tipo con diseño moderno -->
                                        <div class="mb-6">
                                            <div class="grid grid-cols-2 gap-3">
                                                <label
                                                    :class="[
                                                        'relative flex cursor-pointer rounded-lg border p-4 transition-all duration-200',
                                                        imageInputType === 'file'
                                                            ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500'
                                                            : 'border-gray-300 bg-white hover:bg-gray-50'
                                                    ]"
                                                >
                                                    <input
                                                        v-model="imageInputType"
                                                        type="radio"
                                                        value="file"
                                                        class="sr-only"
                                                    />
                                                    <div class="flex items-center w-full">
                                                        <svg class="h-5 w-5 mr-3" :class="imageInputType === 'file' ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                        </svg>
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium" :class="imageInputType === 'file' ? 'text-blue-900' : 'text-gray-900'">
                                                                Archivo
                                                            </div>
                                                            <div class="text-xs" :class="imageInputType === 'file' ? 'text-blue-700' : 'text-gray-500'">
                                                                Subir imagen
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>

                                                <label
                                                    :class="[
                                                        'relative flex cursor-pointer rounded-lg border p-4 transition-all duration-200',
                                                        imageInputType === 'url'
                                                            ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500'
                                                            : 'border-gray-300 bg-white hover:bg-gray-50'
                                                    ]"
                                                >
                                                    <input
                                                        v-model="imageInputType"
                                                        type="radio"
                                                        value="url"
                                                        class="sr-only"
                                                    />
                                                    <div class="flex items-center w-full">
                                                        <svg class="h-5 w-5 mr-3" :class="imageInputType === 'url' ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                        </svg>
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium" :class="imageInputType === 'url' ? 'text-blue-900' : 'text-gray-900'">
                                                                URL
                                                            </div>
                                                            <div class="text-xs" :class="imageInputType === 'url' ? 'text-blue-700' : 'text-gray-500'">
                                                                Desde internet
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Input de URL -->
                                        <div v-if="imageInputType === 'url'" class="space-y-3">
                                            <input
                                                v-model="bannerForm.image"
                                                type="url"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"
                                                :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.image }"
                                                placeholder="https://ejemplo.com/imagen.jpg"
                                            />
                                            <p class="text-xs text-gray-500">
                                                Recomendamos imágenes de al menos 1200x600px para mejor calidad
                                            </p>
                                        </div>

                                        <!-- Input de archivo -->
                                        <div v-if="imageInputType === 'file'" class="space-y-3">
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="space-y-2">
                                                    <label for="file-upload" class="cursor-pointer">
                                                        <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                            </svg>
                                                            Seleccionar archivo
                                                        </span>
                                                        <input
                                                            ref="fileInput"
                                                            id="file-upload"
                                                            type="file"
                                                            accept="image/*"
                                                            @change="handleFileUpload"
                                                            class="sr-only"
                                                        />
                                                    </label>
                                                    <p class="text-sm text-gray-600">o arrastra y suelta aquí</p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 5MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Preview -->
                                        <div v-if="bannerForm.image" class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                                            <div class="relative group">
                                                <img
                                                    :src="bannerForm.image"
                                                    alt="Preview"
                                                    class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm"
                                                    @error="handleImageError"
                                                />
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
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
                                class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 w-full sm:w-auto"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="saving"
                                class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 w-full sm:w-auto"
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
                                {{ saving ? 'Guardando...' : editingBanner ? 'Actualizar Banner' : 'Crear Banner' }}
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
import { useBannersSimpleWeb } from '../../composables/useBannersSimpleWeb'

// Composables
const { 
    banners, 
    loading, 
    error,
    fetchBanners, 
    createBanner, 
    updateBanner, 
    deleteBanner: deleteBannerApi 
} = useBannersSimpleWeb()

// State
const showModal = ref(false)
const editingBanner = ref(null)
const saving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const imageInputType = ref('file') // 'file' o 'url'
const fileInput = ref(null)

// Form data
const bannerForm = reactive({
    title: '',
    subtitle: '',
    image: '',
    order: 0,
    active: true
})

// Validation errors
const errors = ref({})

// Methods
const openCreateModal = () => {
    editingBanner.value = null
    resetForm()
    imageInputType.value = 'file'
    showModal.value = true
}

const editBanner = (banner) => {
    editingBanner.value = banner
    bannerForm.title = banner.title
    bannerForm.subtitle = banner.subtitle || ''
    bannerForm.image = banner.image
    bannerForm.order = banner.order
    bannerForm.active = banner.is_active
    imageInputType.value = 'file'
    errors.value = {}
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingBanner.value = null
    resetForm()
    errors.value = {}
}

const resetForm = () => {
    bannerForm.title = ''
    bannerForm.subtitle = ''
    bannerForm.image = ''
    bannerForm.order = 0
    bannerForm.active = true
    imageInputType.value = 'file'
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const clearImage = () => {
    bannerForm.image = ''
    if (fileInput.value) {
        fileInput.value.value = ''
    }
    imageInputType.value = 'file'
}

const handleFileUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return

    // Validar tipo de archivo
    if (!file.type.startsWith('image/')) {
        showErrorMessage('Por favor selecciona un archivo de imagen válido')
        return
    }

    // Validar tamaño (5MB máximo)
    if (file.size > 5 * 1024 * 1024) {
        showErrorMessage('El archivo es muy grande. Máximo 5MB permitido')
        return
    }

    try {
        // Crear una URL temporal para la vista previa
        const imageUrl = URL.createObjectURL(file)
        bannerForm.image = imageUrl

        // Aquí podrías implementar la subida real del archivo a tu servidor
        // Por ahora usamos la URL temporal para la vista previa
        console.log('Archivo seleccionado:', file.name)
        
    } catch (error) {
        showErrorMessage('Error al procesar el archivo')
        console.error('Error:', error)
    }
}

const saveBanner = async () => {
    // Validación simple
    if (!bannerForm.title || !bannerForm.image) {
        errors.value = {
            title: !bannerForm.title ? 'El título es requerido' : '',
            image: !bannerForm.image ? 'La imagen es requerida' : ''
        }
        return
    }

    errors.value = {}
    saving.value = true

    try {
        if (editingBanner.value) {
            await updateBanner(editingBanner.value.id, bannerForm)
            showSuccessMessage('Banner actualizado correctamente')
        } else {
            await createBanner(bannerForm)
            showSuccessMessage('Banner creado correctamente')
        }
        closeModal()
        await fetchBanners()
    } catch (error) {
        showErrorMessage('Error al guardar el banner: ' + (error.message || 'Error desconocido'))
    } finally {
        saving.value = false
    }
}

const toggleBannerStatus = async (banner) => {
    try {
        await updateBanner(banner.id, { active: !banner.is_active })
        showSuccessMessage(`Banner ${banner.is_active ? 'desactivado' : 'activado'} correctamente`)
        await fetchBanners()
    } catch (error) {
        showErrorMessage('Error al cambiar el estado del banner')
    }
}

const deleteBanner = async (banner) => {
    if (!confirm(`¿Estás seguro de que quieres eliminar el banner "${banner.title}"?`)) {
        return
    }

    try {
        await deleteBannerApi(banner.id)
        showSuccessMessage('Banner eliminado correctamente')
        await fetchBanners()
    } catch (error) {
        showErrorMessage('Error al eliminar el banner')
    }
}

const handleImageError = (event) => {
    event.target.src = '/images/placeholder.jpg'
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('es-ES')
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
    fetchBanners()
})
</script>
