import { ref } from 'vue'
import axios from 'axios'

export function useTechnologies() {
    const technologies = ref([])
    const loading = ref(false)
    const error = ref(null)

    const fetchTechnologies = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/technologies')
            // Normalizar icon: si es relativo, prefijar /storage/
            technologies.value = response.data.map(t => {
                if (t.icon && !t.icon.startsWith('http') && !t.icon.startsWith('data:')) {
                    t.icon = '/storage/' + t.icon.replace(/^\/+/, '')
                }
                return t
            })
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar las tecnologías'
            console.error('Error fetching technologies:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const createTechnology = async (techData, file=null) => {
        try {
            loading.value = true
            error.value = null
            let response
            if (file) {
                const fd = new FormData()
                Object.keys(techData).forEach(k => {
                    const v = techData[k]
                    if (v !== undefined && v !== null) {
                        if (typeof v === 'boolean') {
                            fd.append(k, v ? '1' : '0')
                        } else {
                            fd.append(k, v)
                        }
                    }
                })
                fd.append('icon', file)
                response = await axios.post('/api/technologies', fd)
            } else {
                // Eliminar claves con cadena vacía para no sobrescribir valores existentes
                const payload = { ...techData }
                Object.keys(payload).forEach(k => { if (payload[k] === '') delete payload[k] })
                response = await axios.post('/api/technologies', payload)
            }
            await fetchTechnologies()
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear la tecnología'
            console.error('Error creating technology:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const updateTechnology = async (id, techData, file=null) => {
        try {
            loading.value = true
            error.value = null
            let response
            if (file) {
                const fd = new FormData()
                Object.keys(techData).forEach(k => {
                    const v = techData[k]
                    if (v !== undefined && v !== null) {
                        if (typeof v === 'boolean') {
                            fd.append(k, v ? '1' : '0')
                        } else {
                            fd.append(k, v)
                        }
                    }
                })
                fd.append('icon', file)
                response = await axios.post(`/api/technologies/${id}?_method=PUT`, fd)
            } else {
                const payload = { ...techData }
                Object.keys(payload).forEach(k => { if (payload[k] === '') delete payload[k] })
                response = await axios.put(`/api/technologies/${id}`, payload)
            }
            await fetchTechnologies()
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al actualizar la tecnología'
            console.error('Error updating technology:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const deleteTechnology = async (id) => {
        try {
            loading.value = true
            error.value = null
            await axios.delete(`/api/technologies/${id}`)
            await fetchTechnologies()
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al eliminar la tecnología'
            console.error('Error deleting technology:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const getActiveTechnologies = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/technologies/public')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar las tecnologías activas'
            console.error('Error fetching active technologies:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    return {
        technologies,
        loading,
        error,
        fetchTechnologies,
        createTechnology,
        updateTechnology,
        deleteTechnology,
        getActiveTechnologies
    }
}
