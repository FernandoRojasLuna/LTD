import { ref } from 'vue'

export function useProjects() {
    const projects = ref([])
    const technologies = ref([])
    const loading = ref(false)
    const error = ref(null)
    
    // Obtener token CSRF
    const getCSRFToken = () => {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }
    
    const fetchProjects = async () => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Fetching projects from API...')
            const response = await fetch('/api/projects', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('Projects API response:', data)
            projects.value = data
            return data
        } catch (err) {
            console.error('Error fetching projects:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const fetchTechnologies = async () => {
        try {
            console.log('Fetching technologies from API...')
            const response = await fetch('/api/technologies', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('Technologies API response:', data)
            technologies.value = data
            return data
        } catch (err) {
            console.error('Error fetching technologies:', err)
            throw err
        }
    }
    
    const createProject = async (projectData) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Creating project with data:', projectData)
            
            // Crear FormData para manejar archivos
            const formData = new FormData()
            formData.append('name', projectData.name)
            formData.append('description', projectData.description)
            
            if (projectData.full_description) {
                formData.append('full_description', projectData.full_description)
            }
            
            // Manejar la imagen (archivo o URL)
            if (projectData.image instanceof File) {
                formData.append('image', projectData.image)
            } else if (typeof projectData.image === 'string') {
                formData.append('image', projectData.image)
            }
            
            // Manejar galería
            if (projectData.gallery && Array.isArray(projectData.gallery)) {
                projectData.gallery.forEach((item, index) => {
                    if (item instanceof File) {
                        formData.append(`gallery[${index}]`, item)
                    } else if (typeof item === 'string') {
                        formData.append(`gallery[${index}]`, item)
                    }
                })
            }
            
            formData.append('status', projectData.status || 'development')
            formData.append('start_date', projectData.start_date)
            
            if (projectData.end_date) {
                formData.append('end_date', projectData.end_date)
            }
            
            if (projectData.url) {
                formData.append('url', projectData.url)
            }
            
            if (projectData.repository) {
                formData.append('repository', projectData.repository)
            }
            
            // Manejar características - filtrar elementos vacíos
            if (projectData.features && Array.isArray(projectData.features)) {
                const filteredFeatures = projectData.features.filter(feature => feature && feature.trim())
                filteredFeatures.forEach((feature, index) => {
                    formData.append(`features[${index}]`, feature)
                })
            }
            
            formData.append('is_featured', projectData.is_featured ? '1' : '0')
            formData.append('is_active', projectData.is_active !== undefined ? (projectData.is_active ? '1' : '0') : '1')
            formData.append('order', projectData.order || 0)
            
            // Manejar tecnologías
            if (projectData.technologies && Array.isArray(projectData.technologies)) {
                projectData.technologies.forEach((techId, index) => {
                    formData.append(`technologies[${index}]`, techId)
                })
            }
            
            const response = await fetch('/api/projects', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                body: formData
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                console.error('Server response error:', errorData)
                
                if (errorData.errors) {
                    // Laravel validation errors
                    const errorMessages = Object.values(errorData.errors).flat()
                    throw new Error(errorMessages.join('. '))
                } else if (errorData.message) {
                    throw new Error(errorData.message)
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`)
                }
            }
            
            const result = await response.json()
            console.log('Project created successfully:', result)
            
            // Refrescar la lista de proyectos
            await fetchProjects()
            
            return result
        } catch (err) {
            console.error('Error creating project:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const updateProject = async (projectId, projectData) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Updating project:', projectId, projectData)
            
            // Crear FormData para manejar archivos
            const formData = new FormData()
            formData.append('_method', 'PUT') // Para simular PUT en formularios
            
            if (projectData.name) {
                formData.append('name', projectData.name)
            }
            
            if (projectData.description) {
                formData.append('description', projectData.description)
            }
            
            if (projectData.full_description) {
                formData.append('full_description', projectData.full_description)
            }
            
            // Manejar la imagen (archivo o URL)
            if (projectData.image instanceof File) {
                formData.append('image', projectData.image)
            } else if (typeof projectData.image === 'string') {
                formData.append('image', projectData.image)
            }
            
            if (projectData.status) {
                formData.append('status', projectData.status)
            }
            
            if (projectData.start_date) {
                formData.append('start_date', projectData.start_date)
            }
            
            if (projectData.end_date) {
                formData.append('end_date', projectData.end_date)
            }
            
            if (projectData.url) {
                formData.append('url', projectData.url)
            }
            
            if (projectData.repository) {
                formData.append('repository', projectData.repository)
            }
            
            if (projectData.is_featured !== undefined) {
                formData.append('is_featured', projectData.is_featured ? '1' : '0')
            }
            
            if (projectData.is_active !== undefined) {
                formData.append('is_active', projectData.is_active ? '1' : '0')
            }
            
            if (projectData.order !== undefined) {
                formData.append('order', projectData.order)
            }

            // Manejar características - filtrar elementos vacíos
            if (projectData.features && Array.isArray(projectData.features)) {
                const filteredFeatures = projectData.features.filter(feature => feature && feature.trim())
                filteredFeatures.forEach((feature, index) => {
                    formData.append(`features[${index}]`, feature)
                })
            }

            // Manejar tecnologías
            if (projectData.technologies && Array.isArray(projectData.technologies)) {
                projectData.technologies.forEach((techId, index) => {
                    formData.append(`technologies[${index}]`, techId)
                })
            }

            // Debug: Mostrar qué se está enviando
            console.log('FormData contents for update:')
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`)
            }

            const response = await fetch(`/api/projects/${projectId}`, {
                method: 'POST', // Usamos POST con _method=PUT
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                body: formData
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
            }
            
            const result = await response.json()
            console.log('Project updated successfully:', result)
            
            // Refrescar la lista de proyectos
            await fetchProjects()
            
            return result
        } catch (err) {
            console.error('Error updating project:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const deleteProject = async (projectId) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Deleting project:', projectId)
            
            const response = await fetch(`/api/projects/${projectId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
            }
            
            console.log('Project deleted successfully')
            
            // Refrescar la lista de proyectos
            await fetchProjects()
            
            return true
        } catch (err) {
            console.error('Error deleting project:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    return {
        projects,
        technologies,
        loading,
        error,
        fetchProjects,
        fetchTechnologies,
        createProject,
        updateProject,
        deleteProject
    }
}
