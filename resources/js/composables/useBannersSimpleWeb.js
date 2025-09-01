import { ref } from 'vue'

export function useBannersSimpleWeb() {
    const banners = ref([])
    const loading = ref(false)
    const error = ref(null)
    
    const fetchBanners = async () => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Fetching banners from SimpleBannerController...')
            const response = await fetch('/api/test/banners', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('SimpleBannerController response:', data)
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
            console.log('Creating banner with SimpleBannerController:', bannerData)
            
            // Transformar los datos para que coincidan con la API del SimpleBannerController
            const apiData = {
                title: bannerData.title,
                subtitle: bannerData.subtitle || null,
                image: bannerData.image,
                order: bannerData.order || 0,
                is_active: bannerData.active !== undefined ? bannerData.active : true
            }
            
            const response = await fetch('/api/test/banners', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(apiData)
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
            }
            
            const result = await response.json()
            console.log('Banner created successfully:', result)
            
            // Refrescar la lista de banners
            await fetchBanners()
            
            return result
        } catch (err) {
            console.error('Error creating banner:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const updateBanner = async (bannerId, bannerData) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Updating banner with SimpleBannerController:', bannerId, bannerData)
            
            // Transformar los datos para que coincidan con la API
            const apiData = {
                title: bannerData.title,
                subtitle: bannerData.subtitle || null,
                image: bannerData.image,
                order: bannerData.order || 0,
                is_active: bannerData.active !== undefined ? bannerData.active : true
            }
            
            const response = await fetch(`/api/test/banners/${bannerId}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(apiData)
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
            }
            
            const result = await response.json()
            console.log('Banner updated successfully:', result)
            
            // Refrescar la lista de banners
            await fetchBanners()
            
            return result
        } catch (err) {
            console.error('Error updating banner:', err)
            error.value = err.message || 'Error desconocido'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    const deleteBanner = async (bannerId) => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Deleting banner with SimpleBannerController:', bannerId)
            
            const response = await fetch(`/api/test/banners/${bannerId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
            }
            
            const result = await response.json()
            console.log('Banner deleted successfully:', result)
            
            // Refrescar la lista de banners
            await fetchBanners()
            
            return result
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
