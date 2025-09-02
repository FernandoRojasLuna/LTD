import { ref } from 'vue'

export function useBannersSimpleWeb() {
    const banners = ref([])
    const loading = ref(false)
    const error = ref(null)
    
    const fetchBanners = async () => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Fetching banners from API...')
            const response = await fetch('/api/banners', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            
            const data = await response.json()
            console.log('API response:', data)
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
            
            // Crear FormData para manejar archivos
            const formData = new FormData()
            formData.append('title', bannerData.title)
            
            if (bannerData.subtitle) {
                formData.append('subtitle', bannerData.subtitle)
            }
            
            // Manejar la imagen (archivo o URL)
            if (bannerData.image instanceof File) {
                formData.append('image', bannerData.image)
            } else if (typeof bannerData.image === 'string') {
                formData.append('image', bannerData.image)
            }
            
            formData.append('order', bannerData.order || 0)
            formData.append('is_active', bannerData.active !== undefined ? (bannerData.active ? '1' : '0') : '1')
            
            const response = await fetch('/api/banners', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
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
            console.log('Updating banner:', bannerId, bannerData)
            
            // Crear FormData para manejar archivos
            const formData = new FormData()
            formData.append('_method', 'PUT') // Para simular PUT en formularios
            
            if (bannerData.title) {
                formData.append('title', bannerData.title)
            }
            
            if (bannerData.subtitle) {
                formData.append('subtitle', bannerData.subtitle)
            }
            
            // Manejar la imagen (archivo o URL)
            if (bannerData.image instanceof File) {
                formData.append('image', bannerData.image)
            } else if (typeof bannerData.image === 'string') {
                formData.append('image', bannerData.image)
            }
            
            if (bannerData.order !== undefined) {
                formData.append('order', bannerData.order)
            }
            
            if (bannerData.active !== undefined) {
                formData.append('is_active', bannerData.active ? '1' : '0')
            }
            
            const response = await fetch(`/api/banners/${bannerId}`, {
                method: 'POST', // Usamos POST con _method=PUT
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
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
            console.log('Deleting banner:', bannerId)
            
            const response = await fetch(`/api/banners/${bannerId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            
            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
            }
            
            console.log('Banner deleted successfully')
            
            // Refrescar la lista de banners
            await fetchBanners()
            
            return true
            
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
