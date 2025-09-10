import { ref } from 'vue'
import axios from 'axios'

export function useClients() {
    const clients = ref([])
    const loading = ref(false)
    const error = ref(null)

    const fetchClients = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/clients')
            // Normalizar logo: si es relativo, prefijar /storage/
            clients.value = response.data.map(c => {
                if (c.logo && !c.logo.startsWith('http') && !c.logo.startsWith('data:')) {
                    c.logo = '/storage/' + c.logo.replace(/^\/+/, '')
                }
                return c
            })
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar los clientes'
            console.error('Error fetching clients:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const createClient = async (clientData, file=null) => {
        try {
            loading.value = true
            error.value = null
            let response
            if (file) {
                const fd = new FormData()
                Object.keys(clientData).forEach(k => {
                    const v = clientData[k]
                    if (v !== undefined && v !== null) {
                        if (typeof v === 'boolean') {
                            fd.append(k, v ? '1' : '0')
                        } else {
                            fd.append(k, v)
                        }
                    }
                })
                fd.append('logo', file)
                response = await axios.post('/api/clients', fd)
            } else {
                response = await axios.post('/api/clients', clientData)
            }
            await fetchClients() // Refresh the list
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear el cliente'
            console.error('Error creating client:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const updateClient = async (id, clientData, file=null) => {
        try {
            loading.value = true
            error.value = null
            // Always send FormData via POST + _method=PUT to ensure backend receives all fields
            const fd = new FormData()
            Object.keys(clientData).forEach(k => {
                const v = clientData[k]
                if (v !== undefined && v !== null) {
                    if (typeof v === 'boolean') {
                        fd.append(k, v ? '1' : '0')
                    } else {
                        fd.append(k, v)
                    }
                }
            })
            if (file) {
                fd.append('logo', file)
            }
            const response = await axios.post(`/api/clients/${id}?_method=PUT`, fd)

            await fetchClients() // Refresh the list
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al actualizar el cliente'
            console.error('Error updating client:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const deleteClient = async (id) => {
        try {
            loading.value = true
            error.value = null
            await axios.delete(`/api/clients/${id}`)
            await fetchClients() // Refresh the list
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al eliminar el cliente'
            console.error('Error deleting client:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const getActiveClients = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/clients/active')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar los clientes activos'
            console.error('Error fetching active clients:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const getFeaturedClients = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/clients/featured')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar los clientes destacados'
            console.error('Error fetching featured clients:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const getTestimonials = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/clients/testimonials')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar los testimonios'
            console.error('Error fetching testimonials:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    return {
        clients,
        loading,
        error,
        fetchClients,
        createClient,
        updateClient,
        deleteClient,
        getActiveClients,
        getFeaturedClients,
        getTestimonials
    }
}
