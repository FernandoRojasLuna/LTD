import { ref } from 'vue'

export function useApiClient() {
    const loading = ref(false)
    const error = ref(null)
    
    const getCsrfToken = () => {
        const token = document.querySelector('meta[name="csrf-token"]')
        return token ? token.getAttribute('content') : null
    }
    
    const handleRequest = async (requestFn) => {
        loading.value = true
        error.value = null
        
        try {
            const result = await requestFn()
            return result
        } catch (err) {
            console.error('API Error:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const makeRequest = async (url, options = {}) => {
        const csrfToken = getCsrfToken()
        
        const defaultOptions = {
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken }),
                ...options.headers
            }
        }
        
        console.log('Making request to:', url, 'with options:', {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        })
        
        const response = await fetch(url, {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        })
        
        console.log('Response status:', response.status, response.statusText)
        
        if (!response.ok) {
            const errorText = await response.text()
            console.error('Response error:', errorText)
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`)
        }
        
        const data = await response.json()
        console.log('Response data:', data)
        return data
    }
    
    return {
        loading,
        error,
        handleRequest,
        makeRequest
    }
}

export function useBanners() {
    const { loading, error, handleRequest, makeRequest } = useApiClient()
    const banners = ref([])
    
    const fetchBanners = () => handleRequest(async () => {
        console.log('Fetching banners...')
        const data = await makeRequest('/api/banners')
        banners.value = data
        return data
    })
    
    const createBanner = (bannerData) => handleRequest(async () => {
        console.log('Creating banner with data:', bannerData)
        
        // Transformar los datos para que coincidan con la API
        const apiData = {
            title: bannerData.title,
            subtitle: bannerData.subtitle || null,
            image: bannerData.image,
            link: bannerData.link || null,
            order: bannerData.order || 0,
            is_active: bannerData.active ? 1 : 0  // Usar is_active como espera el controlador
        }
        
        console.log('Transformed API data:', apiData)
        
        const data = await makeRequest('/api/banners', {
            method: 'POST',
            body: JSON.stringify(apiData)
        })
        
        banners.value.push(data)
        return data
    })
    
    const updateBanner = (id, bannerData) => handleRequest(async () => {
        console.log('Updating banner', id, 'with data:', bannerData)
        
        // Transformar los datos para que coincidan con la API
        const apiData = {
            title: bannerData.title,
            subtitle: bannerData.subtitle || null,
            image: bannerData.image,
            link: bannerData.link || null,
            order: bannerData.order || 0,
            is_active: bannerData.active ? 1 : 0  // Usar is_active como espera el controlador
        }
        
        const data = await makeRequest(`/api/banners/${id}`, {
            method: 'PUT',
            body: JSON.stringify(apiData)
        })
        
        const index = banners.value.findIndex(b => b.id === id)
        if (index !== -1) banners.value[index] = data
        return data
    })
    
    const deleteBanner = (id) => handleRequest(async () => {
        console.log('Deleting banner', id)
        await makeRequest(`/api/banners/${id}`, {
            method: 'DELETE'
        })
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
