import { ref } from 'vue'
import axios from 'axios'

export function useBanners() {
    const banners = ref([])
    const loading = ref(false)
    const error = ref(null)
    
    const fetchBanners = async () => {
        loading.value = true
        error.value = null
        
        try {
            console.log('Fetching banners...')
            const response = await axios.get('/api/banners')
            console.log('Banners response:', response.data)
            banners.value = response.data
            return response.data
        } catch (err) {
            console.error('Error fetching banners:', err)
            error.value = err.response?.data?.message || err.message || 'Error desconocido'
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
            
            const response = await axios.post('/api/banners', apiData)
            console.log('Create banner response:', response.data)
            
            banners.value.push(response.data)
            return response.data
        } catch (err) {
            console.error('Error creating banner:', err)
            error.value = err.response?.data?.message || err.message || 'Error desconocido'
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
            
            const response = await axios.put(`/api/banners/${id}`, apiData)
            console.log('Update banner response:', response.data)
            
            const index = banners.value.findIndex(b => b.id === id)
            if (index !== -1) banners.value[index] = response.data
            return response.data
        } catch (err) {
            console.error('Error updating banner:', err)
            error.value = err.response?.data?.message || err.message || 'Error desconocido'
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
            await axios.delete(`/api/banners/${id}`)
            console.log('Banner deleted successfully')
            
            banners.value = banners.value.filter(b => b.id !== id)
        } catch (err) {
            console.error('Error deleting banner:', err)
            error.value = err.response?.data?.message || err.message || 'Error desconocido'
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
