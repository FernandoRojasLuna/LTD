import { ref } from 'vue'
import axios from 'axios'

export function useStaff() {
    const staff = ref([])
    const loading = ref(false)
    const error = ref(null)

    const fetchStaff = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/staff')
            staff.value = response.data
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar los miembros del equipo'
            console.error('Error fetching staff:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const createStaffMember = async (memberData, file=null) => {
        try {
            loading.value = true
            error.value = null
            let response
            if (file) {
                const fd = new FormData()
                Object.keys(memberData).forEach(k => {
                    if (k === 'image') return // la imagen se añade como file separado
                    const v = memberData[k]
                    if (v !== undefined && v !== null) {
                        // Asegurar que los booleanos se envíen como '1'/'0' para que Laravel los valide con "boolean"
                        if (typeof v === 'boolean') {
                            fd.append(k, v ? '1' : '0')
                        } else {
                            fd.append(k, v)
                        }
                    }
                })
                fd.append('image', file)
                response = await axios.post('/api/staff', fd)
            } else {
                response = await axios.post('/api/staff', memberData)
            }
            await fetchStaff() // Refresh the list
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear el miembro del equipo'
            console.error('Error creating staff member:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const updateStaffMember = async (id, memberData, file=null) => {
        try {
            loading.value = true
            error.value = null
            let response
            if (file) {
                const fd = new FormData()
                Object.keys(memberData).forEach(k => {
                    if (k === 'image') return // la imagen se añade como file separado
                    const v = memberData[k]
                    if (v !== undefined && v !== null) {
                        // Asegurar que los booleanos se envíen como '1'/'0' para que Laravel los valide con "boolean"
                        if (typeof v === 'boolean') {
                            fd.append(k, v ? '1' : '0')
                        } else {
                            fd.append(k, v)
                        }
                    }
                })
                fd.append('image', file)
                response = await axios.post(`/api/staff/${id}?_method=PUT`, fd)
            } else {
                response = await axios.put(`/api/staff/${id}`, memberData)
            }
            await fetchStaff() // Refresh the list
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al actualizar el miembro del equipo'
            console.error('Error updating staff member:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const deleteStaffMember = async (id) => {
        try {
            loading.value = true
            error.value = null
            await axios.delete(`/api/staff/${id}`)
            await fetchStaff() // Refresh the list
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al eliminar el miembro del equipo'
            console.error('Error deleting staff member:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    const getActiveStaff = async () => {
        try {
            loading.value = true
            error.value = null
            const response = await axios.get('/api/staff/active')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar los miembros activos'
            console.error('Error fetching active staff:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    return {
        staff,
        loading,
        error,
        fetchStaff,
        createStaffMember,
        updateStaffMember,
        deleteStaffMember,
        getActiveStaff
    }
}
