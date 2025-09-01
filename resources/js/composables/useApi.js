import { ref, reactive } from 'vue'
import axios from 'axios'

export function useApiClient() {
    const loading = ref(false)
    const error = ref(null)
    
    const handleRequest = async (requestFn) => {
        loading.value = true
        error.value = null
        
        try {
            const result = await requestFn()
            return result
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    return {
        loading,
        error,
        handleRequest
    }
}

export function useBanners() {
    const { loading, error, handleRequest } = useApiClient()
    const banners = ref([])
    
    const fetchBanners = () => handleRequest(async () => {
        const response = await axios.get('/api/banners')
        banners.value = response.data
        return response.data
    })
    
    const createBanner = (data) => handleRequest(async () => {
        const response = await axios.post('/api/banners', data)
        banners.value.push(response.data)
        return response.data
    })
    
    const updateBanner = (id, data) => handleRequest(async () => {
        const response = await axios.put(`/api/banners/${id}`, data)
        const index = banners.value.findIndex(b => b.id === id)
        if (index !== -1) banners.value[index] = response.data
        return response.data
    })
    
    const deleteBanner = (id) => handleRequest(async () => {
        await axios.delete(`/api/banners/${id}`)
        banners.value = banners.value.filter(b => b.id !== id)
    })
    
    return {
        banners,
        loading,
        error,
        fetchBanners,
        createBanner,
        updateBanner,
        deleteBanner
    }
}

export function useContents() {
    const { loading, error, handleRequest } = useApiClient()
    const contents = ref([])
    
    const fetchContents = () => handleRequest(async () => {
        const response = await axios.get('/api/contents')
        contents.value = response.data
        return response.data
    })
    
    const createContent = (data) => handleRequest(async () => {
        const response = await axios.post('/api/contents', data)
        contents.value.push(response.data)
        return response.data
    })
    
    const updateContent = (id, data) => handleRequest(async () => {
        const response = await axios.put(`/api/contents/${id}`, data)
        const index = contents.value.findIndex(c => c.id === id)
        if (index !== -1) contents.value[index] = response.data
        return response.data
    })
    
    const deleteContent = (id) => handleRequest(async () => {
        await axios.delete(`/api/contents/${id}`)
        contents.value = contents.value.filter(c => c.id !== id)
    })
    
    return {
        contents,
        loading,
        error,
        fetchContents,
        createContent,
        updateContent,
        deleteContent
    }
}

export function useProjects() {
    const { loading, error, handleRequest } = useApiClient()
    const projects = ref([])
    
    const fetchProjects = () => handleRequest(async () => {
        const response = await axios.get('/api/projects')
        projects.value = response.data
        return response.data
    })
    
    const createProject = (data) => handleRequest(async () => {
        const response = await axios.post('/api/projects', data)
        projects.value.push(response.data)
        return response.data
    })
    
    const updateProject = (id, data) => handleRequest(async () => {
        const response = await axios.put(`/api/projects/${id}`, data)
        const index = projects.value.findIndex(p => p.id === id)
        if (index !== -1) projects.value[index] = response.data
        return response.data
    })
    
    const deleteProject = (id) => handleRequest(async () => {
        await axios.delete(`/api/projects/${id}`)
        projects.value = projects.value.filter(p => p.id !== id)
    })
    
    return {
        projects,
        loading,
        error,
        fetchProjects,
        createProject,
        updateProject,
        deleteProject
    }
}

export function useTechnologies() {
    const { loading, error, handleRequest } = useApiClient()
    const technologies = ref([])
    
    const fetchTechnologies = () => handleRequest(async () => {
        const response = await axios.get('/api/technologies')
        technologies.value = response.data
        return response.data
    })
    
    const createTechnology = (data) => handleRequest(async () => {
        const response = await axios.post('/api/technologies', data)
        technologies.value.push(response.data)
        return response.data
    })
    
    const updateTechnology = (id, data) => handleRequest(async () => {
        const response = await axios.put(`/api/technologies/${id}`, data)
        const index = technologies.value.findIndex(t => t.id === id)
        if (index !== -1) technologies.value[index] = response.data
        return response.data
    })
    
    const deleteTechnology = (id) => handleRequest(async () => {
        await axios.delete(`/api/technologies/${id}`)
        technologies.value = technologies.value.filter(t => t.id !== id)
    })
    
    return {
        technologies,
        loading,
        error,
        fetchTechnologies,
        createTechnology,
        updateTechnology,
        deleteTechnology
    }
}
