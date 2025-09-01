import { ref } from 'vue'

export function useBannersWeb() {
    const banners = ref([])
    const loading = ref(false)
    const error = ref(null)
    
    const fetchBanners = async () => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Fetching banners from test route...')
            const response = await fetch('/test-banners-api', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('Banners response:', data)
            banners.value = data
            return data
        } catch (err) {
            console.error('Error fetching banners:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const createBanner = async (bannerData) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Creating banner with data:', bannerData)
            
            // Transformar los datos para que coincidan con la API
            const apiData = {
                title: bannerData.title,
                subtitle: bannerData.subtitle || null,
                image: bannerData.image,
                link: bannerData.link || null,
                order: bannerData.order || 0,
                is_active: bannerData.active || false
            }
            
            console.log('Transformed API data:', apiData)
            
            const response = await fetch('/test-banners-api/store', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(apiData)
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('Create banner response:', data)
            
            banners.value.push(data)
            return data
        } catch (err) {
            console.error('Error creating banner:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const updateBanner = async (id, bannerData) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Updating banner', id, 'with data:', bannerData)
            
            // Transformar los datos para que coincidan con la API
            const apiData = {
                title: bannerData.title,
                subtitle: bannerData.subtitle || null,
                image: bannerData.image,
                link: bannerData.link || null,
                order: bannerData.order || 0,
                is_active: bannerData.active !== undefined ? bannerData.active : false
            }
            
            console.log('Transformed API data:', apiData)
            
            const response = await fetch(`/test-banners-api/${id}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(apiData)
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('Update banner response:', data)
            
            const index = banners.value.findIndex(b => b.id === id)
            if (index !== -1) banners.value[index] = data
            return data
        } catch (err) {
            console.error('Error updating banner:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const deleteBanner = async (id) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Deleting banner', id)
            const response = await fetch(`/test-banners-api/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            console.log('Banner deleted successfully')
            
            banners.value = banners.value.filter(b => b.id !== id)
        } catch (err) {
            console.error('Error deleting banner:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
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
