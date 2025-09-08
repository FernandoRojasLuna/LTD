<template>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Gestión de Tecnologías
                </h2>
                <p class="mt-1 text-sm text-gray-500">Administra las tecnologías y sus iconos</p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button @click="openCreateModal" class="ml-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                   <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nueva Tecnologia
                </button>
            </div>
        </div>

        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else-if="technologies.length === 0" class="text-center py-12">
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No hay tecnologías</h3>
            <p class="mt-1 text-sm text-gray-500">Agrega la primera tecnología para empezar.</p>
        </div>

        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="tech in technologies" :key="tech.id" class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-gray-50 rounded-lg">
                        <img v-if="tech.icon && isImageUrl(tech.icon)" :src="tech.icon" class="w-10 h-10 object-contain" />
                        <i v-else-if="tech.icon" :class="tech.icon + ' text-2xl'" aria-hidden="true"></i>
                        <div v-else class="text-gray-400 font-bold">{{ getInitials(tech.name) }}</div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 truncate">{{ tech.name }}</div>
                        <div class="text-sm text-gray-500">{{ tech.description }}</div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <button @click="editTechnology(tech)" class="text-indigo-600 text-sm">Editar</button>
                        <button @click="deleteTechnologyConfirm(tech)" class="text-red-600 text-sm">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>
                <div class="inline-block transform overflow-hidden rounded-xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle">
                    <form @submit.prevent="saveTechnology">
                        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                            <h3 class="text-lg font-medium">{{ editingTechnology ? 'Editar tecnología' : 'Nueva tecnología' }}</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input v-model="techForm.name" type="text" class="mt-1 block w-full border-gray-200 rounded-md" />
                                <p v-if="errors.name" class="text-xs text-red-500">{{ errors.name[0] }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color</label>
                                <input v-model="techForm.color" type="text" class="mt-1 block w-32 border-gray-200 rounded-md" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Icono (archivo o clase)</label>
                                <input ref="iconInput" @change="onIconChange" type="file" accept="image/*" class="mt-1 block w-full" />
                                <p class="text-xs text-gray-500">Puedes subir una imagen o usar una clase de icono (p. ej. "fab fa-react").</p>
                                <div v-if="iconPreview" class="mt-2">
                                    <img :src="iconPreview" class="w-24 h-24 object-contain" />
                                </div>
                                <p v-if="errors.icon" class="text-xs text-red-500">{{ errors.icon[0] }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea v-model="techForm.description" class="mt-1 block w-full border-gray-200 rounded-md"></textarea>
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" v-model="techForm.is_active" class="form-checkbox" />
                                    <span class="ml-2 text-sm text-gray-700">Activo</span>
                                </label>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2">
                            <button type="button" @click="closeModal" class="px-4 py-2 bg-white border rounded">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success/Error messages -->
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
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useTechnologies } from '@/composables/useTechnologies'

const { technologies, loading, fetchTechnologies, createTechnology, updateTechnology, deleteTechnology } = useTechnologies()

const showModal = ref(false)
const editingTechnology = ref(null)
const techForm = reactive({ name: '', color: '#6366f1', icon: '', description: '', is_active: true })
const errors = ref({})
const iconFile = ref(null)
const iconPreview = ref('')
const iconInput = ref(null)

// UI messages (like ClientManager)
const successMessage = ref('')
const errorMessage = ref('')

const showSuccessMessage = (message) => {
    successMessage.value = message
    setTimeout(() => { successMessage.value = '' }, 3000)
}

const showErrorMessage = (message) => {
    errorMessage.value = message
    setTimeout(() => { errorMessage.value = '' }, 3000)
}

const openCreateModal = () => {
    editingTechnology.value = null
    resetForm()
    showModal.value = true
    if (iconInput.value) iconInput.value.value = ''
}

const editTechnology = (tech) => {
    editingTechnology.value = tech
    techForm.name = tech.name
    techForm.color = tech.color
    techForm.icon = tech.icon || ''
    techForm.description = tech.description || ''
    techForm.is_active = tech.is_active
    errors.value = {}
    iconPreview.value = tech.icon && isImageUrl(tech.icon) ? tech.icon : ''
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingTechnology.value = null
    resetForm()
}

const resetForm = () => {
    techForm.name = ''
    techForm.color = '#6366f1'
    techForm.icon = ''
    techForm.description = ''
    techForm.is_active = true
    iconFile.value = null
    iconPreview.value = ''
}

const onIconChange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    if (!file.type.startsWith('image/')) {
        alert('El archivo debe ser una imagen')
        e.target.value = null
        return
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('El icono es demasiado grande (máx 5MB)')
        e.target.value = null
        return
    }
    iconFile.value = file
    techForm.icon = ''
    const reader = new FileReader()
    reader.onload = (ev) => iconPreview.value = ev.target.result
    reader.readAsDataURL(file)
}

const saveTechnology = async () => {
    if (!techForm.name) {
        errors.value = { name: ['El nombre es requerido'] }
        return
    }
    errors.value = {}
    try {
        // Construir payload sin enviar el campo `icon` cuando no hay archivo nuevo
        const payload = { ...techForm }
        // Si icon está vacío string eliminar para no sobrescribir con ''
        if (payload.icon === '') delete payload.icon
        // En edición: si no se seleccionó nuevo archivo, no enviar el campo icon
        if (editingTechnology.value && !iconFile.value) {
            delete payload.icon
        }

        if (editingTechnology.value) {
            await updateTechnology(editingTechnology.value.id, payload, iconFile.value)
            showSuccessMessage('Tecnología actualizada')
        } else {
            await createTechnology(payload, iconFile.value)
            showSuccessMessage('Tecnología creada')
        }
        resetForm()
        closeModal()
    } catch (err) {
        if (err.response?.data?.errors) {
            errors.value = err.response.data.errors
            // show first error if available
            const first = Object.values(err.response.data.errors)[0]
            if (first && first[0]) showErrorMessage(first[0])
        } else {
            showErrorMessage('Error al guardar la tecnología')
        }
    }
}

const deleteTechnologyConfirm = async (tech) => {
    if (!confirm(`¿Eliminar ${tech.name}?`)) return
    try {
        await deleteTechnology(tech.id)
        showSuccessMessage('Tecnología eliminada')
    } catch (err) {
        showErrorMessage('Error al eliminar')
    }
}

const getInitials = (name) => name.split(' ').map(w => w[0]).join('').toUpperCase().substring(0,2)
const isImageUrl = (url) => url && (url.startsWith('http') || url.startsWith('/storage/') || url.startsWith('data:'))

onMounted(() => fetchTechnologies())
</script>

