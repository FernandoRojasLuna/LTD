<template>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Gestión de Proyectos
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Administra los proyectos del laboratorio tecnológico
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
                    Nuevo Proyecto
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="projects.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No hay proyectos</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer proyecto para el laboratorio.</p>
            <div class="mt-6">
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Proyecto
                </button>
            </div>
        </div>

        <!-- Projects Grid -->
        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="project in projects"
                :key="project.id"
                class="relative group bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-200"
            >
                <!-- Project Image -->
                <div class="aspect-w-16 aspect-h-9">
                    <img
                        :src="project.image"
                        :alt="project.name"
                        class="w-full h-48 object-cover"
                        @error="handleImageError"
                    />
                </div>

                <!-- Project Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                            {{ project.name }}
                        </h3>
                        <span
                            :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                getStatusClasses(project.status)
                            ]"
                        >
                            {{ getStatusLabel(project.status) }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                        {{ project.description }}
                    </p>

                    <!-- Technologies -->
                    <div v-if="project.technologies && project.technologies.length > 0" class="mb-3">
                        <div class="flex flex-wrap gap-1">
                            <span
                                v-for="tech in project.technologies.slice(0, 3)"
                                :key="tech.id"
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800"
                            >
                                {{ tech.name }}
                            </span>
                            <span
                                v-if="project.technologies.length > 3"
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800"
                            >
                                +{{ project.technologies.length - 3 }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div class="flex items-center space-x-4">
                            <span v-if="project.is_featured" class="text-yellow-600">
                                ⭐ Destacado
                            </span>
                            <span :class="project.is_active ? 'text-green-600' : 'text-red-600'">
                                {{ project.is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <span>{{ formatDate(project.start_date) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex space-x-1">
                        <button
                            @click="editProject(project)"
                            class="bg-white p-2 rounded-full shadow-sm text-gray-400 hover:text-indigo-600"
                            title="Editar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            @click="toggleProjectStatus(project)"
                            :class="[
                                'bg-white p-2 rounded-full shadow-sm',
                                project.is_active 
                                    ? 'text-red-400 hover:text-red-600' 
                                    : 'text-green-400 hover:text-green-600'
                            ]"
                            :title="project.is_active ? 'Desactivar' : 'Activar'"
                        >
                            <svg v-if="project.is_active" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button
                            @click="toggleFeatured(project)"
                            :class="[
                                'bg-white p-2 rounded-full shadow-sm',
                                project.is_featured 
                                    ? 'text-yellow-400 hover:text-yellow-600' 
                                    : 'text-gray-400 hover:text-yellow-600'
                            ]"
                            :title="project.is_featured ? 'Quitar destacado' : 'Marcar como destacado'"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </button>
                        <button
                            @click="deleteProject(project)"
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

        <!-- Modal para crear/editar proyecto -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div 
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="closeModal"
                ></div>

                <!-- Modal -->
                <div class="inline-block transform overflow-hidden rounded-xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl sm:align-middle">
                    <form @submit.prevent="saveProject">
                        <!-- Header del Modal -->
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white">
                                    {{ editingProject ? 'Editar Proyecto' : 'Crear Nuevo Proyecto' }}
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
                                <!-- Columna Izquierda: Información Básica -->
                                <div class="space-y-6">
                                    <!-- Nombre del proyecto -->
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Nombre del Proyecto
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input
                                            id="name"
                                            v-model="projectForm.name"
                                            type="text"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.name }"
                                            placeholder="Ej: Sistema de Gestión Académica"
                                        />
                                        <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                                    </div>

                                    <!-- Descripción corta -->
                                    <div>
                                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Descripción Corta
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <textarea
                                            id="description"
                                            v-model="projectForm.description"
                                            rows="3"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.description }"
                                            placeholder="Descripción breve del proyecto..."
                                        ></textarea>
                                        <p v-if="errors.description" class="mt-2 text-sm text-red-600">{{ errors.description }}</p>
                                    </div>

                                    <!-- Descripción completa -->
                                    <div>
                                        <label for="full_description" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Descripción Completa
                                        </label>
                                        <textarea
                                            id="full_description"
                                            v-model="projectForm.full_description"
                                            rows="5"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            placeholder="Descripción detallada del proyecto, objetivos, alcance..."
                                        ></textarea>
                                    </div>

                                    <!-- Estado y fechas -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Estado
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <select
                                                id="status"
                                                v-model="projectForm.status"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            >
                                                <option value="development">En Desarrollo</option>
                                                <option value="deployed">Desplegado</option>
                                                <option value="paused">Pausado</option>
                                                <option value="completed">Completado</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="order" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Orden
                                            </label>
                                            <input
                                                id="order"
                                                v-model.number="projectForm.order"
                                                type="number"
                                                min="0"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="0"
                                            />
                                        </div>
                                    </div>

                                    <!-- Fechas -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="start_date" class="block text-sm font-semibold text-gray-900 mb-1">
                                                Fecha de Inicio
                                                <span class="text-gray-500 text-xs font-normal">(Opcional)</span>
                                            </label>
                                            <p class="text-xs text-gray-500 mb-2">Para mostrar cronología del proyecto</p>
                                            <input
                                                id="start_date"
                                                v-model="projectForm.start_date"
                                                type="date"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.start_date }"
                                            />
                                            <p v-if="errors.start_date" class="mt-2 text-sm text-red-600">{{ errors.start_date }}</p>
                                        </div>

                                        <div>
                                            <label for="end_date" class="block text-sm font-semibold text-gray-900 mb-1">
                                                Fecha de Finalización
                                                <span class="text-gray-500 text-xs font-normal">(Opcional)</span>
                                            </label>
                                            <p class="text-xs text-gray-500 mb-2">Deja vacío si está en desarrollo</p>
                                            <input
                                                id="end_date"
                                                v-model="projectForm.end_date"
                                                type="date"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                            />
                                        </div>
                                    </div>

                                    <!-- URLs -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="url" class="block text-sm font-semibold text-gray-900 mb-2">
                                                URL del Proyecto
                                            </label>
                                            <input
                                                id="url"
                                                v-model="projectForm.url"
                                                type="url"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="https://ejemplo.com"
                                            />
                                        </div>

                                        <div>
                                            <label for="repository" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Repositorio
                                            </label>
                                            <input
                                                id="repository"
                                                v-model="projectForm.repository"
                                                type="url"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                placeholder="https://github.com/usuario/repo"
                                            />
                                        </div>
                                    </div>

                                    <!-- Configuraciones -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Configuraciones</label>
                                        <div class="space-y-3">
                                            <label class="flex items-center">
                                                <input
                                                    v-model="projectForm.is_active"
                                                    type="checkbox"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                />
                                                <span class="ml-2 text-sm text-gray-900">Proyecto activo</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input
                                                    v-model="projectForm.is_featured"
                                                    type="checkbox"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                />
                                                <span class="ml-2 text-sm text-gray-900">Proyecto destacado</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha: Imagen y Tecnologías -->
                                <div class="space-y-6">
                                    <!-- Imagen principal -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Imagen Principal
                                            <span class="text-red-500 ml-1">*</span>
                                        </h4>

                                        <!-- Selector de tipo -->
                                        <div class="mb-6">
                                            <div class="grid grid-cols-2 gap-3">
                                                <label
                                                    :class="[
                                                        'relative flex cursor-pointer rounded-lg border p-4 focus:outline-none',
                                                        imageInputType === 'file' 
                                                            ? 'border-indigo-600 ring-2 ring-indigo-600 bg-indigo-50' 
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
                                                        <div class="text-sm flex-1">
                                                            <p class="font-medium text-gray-900">Subir Archivo</p>
                                                            <p class="text-gray-500">JPG, PNG, GIF</p>
                                                        </div>
                                                    </div>
                                                </label>

                                                <label
                                                    :class="[
                                                        'relative flex cursor-pointer rounded-lg border p-4 focus:outline-none',
                                                        imageInputType === 'url' 
                                                            ? 'border-indigo-600 ring-2 ring-indigo-600 bg-indigo-50' 
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
                                                        <div class="text-sm flex-1">
                                                            <p class="font-medium text-gray-900">URL Externa</p>
                                                            <p class="text-gray-500">Enlace directo</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Input de URL -->
                                        <div v-if="imageInputType === 'url'" class="space-y-3">
                                            <input
                                                v-model="projectForm.image"
                                                type="url"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                                :class="{ 'border-red-300 focus:ring-red-500 focus:border-red-500': errors.image }"
                                                placeholder="https://ejemplo.com/imagen.jpg"
                                            />
                                            <p class="text-xs text-gray-500">
                                                Recomendamos imágenes de al menos 800x600px para mejor calidad
                                            </p>
                                        </div>

                                        <!-- Input de archivo -->
                                        <div v-if="imageInputType === 'file'" class="space-y-3">
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 transition-colors duration-200">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="space-y-2">
                                                    <label for="image-upload" class="cursor-pointer">
                                                        <span class="text-indigo-600 font-medium hover:text-indigo-500">
                                                            Selecciona un archivo
                                                        </span>
                                                        <input
                                                            id="image-upload"
                                                            ref="fileInput"
                                                            type="file"
                                                            accept="image/*"
                                                            class="sr-only"
                                                            @change="handleFileUpload"
                                                        />
                                                    </label>
                                                    <p class="text-sm text-gray-600">o arrastra y suelta aquí</p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 5MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Preview -->
                                        <div v-if="imagePreview || projectForm.image" class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                                            <div class="relative group">
                                                <img
                                                    :src="imagePreview || projectForm.image"
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

                                    <!-- Tecnologías -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Tecnologías
                                        </h4>

                                        <div v-if="technologies.length > 0" class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-4">
                                            <label
                                                v-for="tech in technologies"
                                                :key="tech.id"
                                                class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded"
                                            >
                                                <input
                                                    v-model="projectForm.technologies"
                                                    :value="tech.id"
                                                    type="checkbox"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                />
                                                <span class="text-sm text-gray-900">{{ tech.name }}</span>
                                                <span v-if="tech.category" class="text-xs text-gray-500">({{ tech.category }})</span>
                                            </label>
                                        </div>
                                        <div v-else class="text-sm text-gray-500 italic">
                                            No hay tecnologías disponibles
                                        </div>
                                    </div>

                                    <!-- Features -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Características
                                        </h4>
                                        
                                        <div class="space-y-3">
                                            <div
                                                v-for="(feature, index) in projectForm.features"
                                                :key="index"
                                                class="flex items-center space-x-2"
                                            >
                                                <input
                                                    v-model="projectForm.features[index]"
                                                    type="text"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                    placeholder="Característica del proyecto"
                                                />
                                                <button
                                                    type="button"
                                                    @click="removeFeature(index)"
                                                    class="text-red-500 hover:text-red-700 p-1"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <button
                                                type="button"
                                                @click="addFeature"
                                                class="w-full px-3 py-2 border border-dashed border-gray-300 rounded-md text-gray-500 hover:text-gray-700 hover:border-gray-400 transition-colors duration-200"
                                            >
                                                + Agregar característica
                                            </button>
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
                                {{ saving ? 'Guardando...' : editingProject ? 'Actualizar Proyecto' : 'Crear Proyecto' }}
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
import { useProjects } from '../../composables/useProjects'

// Composables
const { 
    projects, 
    technologies,
    loading, 
    error,
    fetchProjects,
    fetchTechnologies,
    createProject, 
    updateProject, 
    deleteProject: deleteProjectApi 
} = useProjects()

// State
const showModal = ref(false)
const editingProject = ref(null)
const saving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const imageInputType = ref('file') // 'file' o 'url'
const fileInput = ref(null)
const imagePreview = ref('') // Para la vista previa de archivos

// Form data
const projectForm = reactive({
    name: '',
    description: '',
    full_description: '',
    image: '',
    status: 'development',
    start_date: '',
    end_date: '',
    url: '',
    repository: '',
    features: [],
    technologies: [],
    is_featured: false,
    is_active: true,
    order: 0
})

// Validation errors
const errors = ref({})

// Methods
const openCreateModal = () => {
    editingProject.value = null
    resetForm()
    imageInputType.value = 'file'
    showModal.value = true
}

const editProject = (project) => {
    editingProject.value = project
    projectForm.name = project.name
    projectForm.description = project.description
    projectForm.full_description = project.full_description || ''
    projectForm.image = project.image
    projectForm.status = project.status
    // Convertir fechas de formato ISO a yyyy-MM-dd para inputs de tipo date
    projectForm.start_date = project.start_date ? project.start_date.split('T')[0] : ''
    projectForm.end_date = project.end_date ? project.end_date.split('T')[0] : ''
    projectForm.url = project.url || ''
    projectForm.repository = project.repository || ''
    projectForm.features = project.features || []
    projectForm.technologies = project.technologies ? project.technologies.map(t => t.id) : []
    projectForm.is_featured = project.is_featured
    projectForm.is_active = project.is_active
    projectForm.order = project.order
    imagePreview.value = project.image // Usar la imagen actual como preview
    imageInputType.value = 'file'
    errors.value = {}
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingProject.value = null
    resetForm()
    errors.value = {}
}

const resetForm = () => {
    projectForm.name = ''
    projectForm.description = ''
    projectForm.full_description = ''
    projectForm.image = ''
    projectForm.status = 'development'
    projectForm.start_date = ''
    projectForm.end_date = ''
    projectForm.url = ''
    projectForm.repository = ''
    projectForm.features = []
    projectForm.technologies = []
    projectForm.is_featured = false
    projectForm.is_active = true
    projectForm.order = 0
    imagePreview.value = ''
    imageInputType.value = 'file'
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const clearImage = () => {
    projectForm.image = ''
    imagePreview.value = ''
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
        // Guardar el archivo para enviarlo al servidor
        projectForm.image = file
        
        // Crear una URL temporal para la vista previa
        imagePreview.value = URL.createObjectURL(file)

        console.log('Archivo seleccionado:', file.name)
        
    } catch (error) {
        showErrorMessage('Error al procesar el archivo')
        console.error('Error:', error)
    }
}

const addFeature = () => {
    projectForm.features.push('')
}

const removeFeature = (index) => {
    projectForm.features.splice(index, 1)
}

const saveProject = async () => {
    // Validación simple
    if (!projectForm.name || !projectForm.description || !projectForm.image) {
        errors.value = {
            name: !projectForm.name ? 'El nombre es requerido' : '',
            description: !projectForm.description ? 'La descripción es requerida' : '',
            image: !projectForm.image ? 'La imagen es requerida' : ''
        }
        return
    }

    errors.value = {}
    saving.value = true

    try {
        // Preparar datos del formulario
        const formData = { ...projectForm }
        
        // Filtrar características vacías
        formData.features = projectForm.features.filter(feature => feature && feature.trim() !== '')
        
        if (editingProject.value) {
            await updateProject(editingProject.value.id, formData)
            showSuccessMessage('Proyecto actualizado correctamente')
        } else {
            await createProject(formData)
            showSuccessMessage('Proyecto creado correctamente')
        }
        closeModal()
        await fetchProjects()
    } catch (error) {
        showErrorMessage('Error al guardar el proyecto: ' + (error.message || 'Error desconocido'))
    } finally {
        saving.value = false
    }
}

const toggleProjectStatus = async (project) => {
    try {
        await updateProject(project.id, { is_active: !project.is_active })
        showSuccessMessage(`Proyecto ${project.is_active ? 'desactivado' : 'activado'} correctamente`)
        await fetchProjects()
    } catch (error) {
        showErrorMessage('Error al cambiar el estado del proyecto')
    }
}

const toggleFeatured = async (project) => {
    try {
        await updateProject(project.id, { is_featured: !project.is_featured })
        showSuccessMessage(`Proyecto ${project.is_featured ? 'removido de' : 'marcado como'} destacado`)
        await fetchProjects()
    } catch (error) {
        showErrorMessage('Error al cambiar el estado destacado del proyecto')
    }
}

const deleteProject = async (project) => {
    if (!confirm(`¿Estás seguro de que quieres eliminar el proyecto "${project.name}"?`)) {
        return
    }

    try {
        await deleteProjectApi(project.id)
        showSuccessMessage('Proyecto eliminado correctamente')
        await fetchProjects()
    } catch (error) {
        showErrorMessage('Error al eliminar el proyecto')
    }
}

const handleImageError = (event) => {
    // Evitar loop infinito - solo cambiar si no es ya el placeholder
    if (!event.target.src.includes('data:image/svg+xml')) {
        // Crear un SVG placeholder en base64 para evitar peticiones HTTP
        const svgPlaceholder = `data:image/svg+xml;base64,${btoa(`
            <svg width="400" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f3f4f6"/>
                <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#9ca3af" text-anchor="middle" dy=".3em">
                    Imagen no disponible
                </text>
            </svg>
        `)}`
        event.target.src = svgPlaceholder
    }
}

const getStatusLabel = (status) => {
    const labels = {
        'development': 'En Desarrollo',
        'deployed': 'Desplegado',
        'paused': 'Pausado',
        'completed': 'Completado'
    }
    return labels[status] || 'Desconocido'
}

const getStatusClasses = (status) => {
    const classes = {
        'development': 'bg-blue-100 text-blue-800',
        'deployed': 'bg-green-100 text-green-800',
        'paused': 'bg-orange-100 text-orange-800',
        'completed': 'bg-purple-100 text-purple-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
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
onMounted(async () => {
    await fetchProjects()
    await fetchTechnologies()
})
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
