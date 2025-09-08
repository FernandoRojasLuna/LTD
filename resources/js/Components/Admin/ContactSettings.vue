<template>
  <div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Ajustes de Contacto</h2>

    <div class="bg-white p-6 rounded shadow">
      <div class="mb-4">
        <label class="block text-sm font-medium">Dirección</label>
        <input v-model="form.address" class="mt-1 block w-full border rounded p-2" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Teléfonos</label>
        <div v-for="(p, idx) in form.phones" :key="idx" class="flex gap-2 mt-2">
          <input v-model="form.phones[idx]" class="flex-1 border rounded p-2" />
          <button @click="removePhone(idx)" class="px-3 bg-red-500 text-white rounded">-</button>
        </div>
        <button @click="addPhone" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded">Añadir teléfono</button>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Correos</label>
        <div v-for="(e, idx) in form.emails" :key="idx" class="flex gap-2 mt-2">
          <input v-model="form.emails[idx]" class="flex-1 border rounded p-2" />
          <button @click="removeEmail(idx)" class="px-3 bg-red-500 text-white rounded">-</button>
        </div>
        <button @click="addEmail" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded">Añadir email</button>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Redes sociales</label>
        <div v-for="(s, idx) in form.socials" :key="idx" class="mt-2 border p-2 rounded">
          <div class="flex gap-2">
            <input v-model="form.socials[idx].name" placeholder="Nombre (ej. instagram)" class="flex-1 border rounded p-2" />
            <input v-model="form.socials[idx].url" placeholder="URL" class="flex-2 border rounded p-2" />
            <button @click="removeSocial(idx)" class="px-3 bg-red-500 text-white rounded">-</button>
          </div>
        </div>
        <button @click="addSocial" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded">Añadir red</button>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">WhatsApp</label>
        <input v-model="form.whatsapp" class="mt-1 block w-full border rounded p-2" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Embebido de mapa (iframe)</label>
        <textarea v-model="form.map_embed" class="mt-1 block w-full border rounded p-2"></textarea>
      </div>

      <div class="flex justify-end gap-2">
        <button @click="load" class="px-4 py-2 border rounded">Cargar</button>
        <button @click="save" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
      </div>
    </div>

    <div v-if="success" class="mt-4 p-4 bg-green-50 border border-green-200 rounded">{{ success }}</div>
    <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import useContactSettings from '@/composables/useContactSettings'

const { get, update } = useContactSettings()

const form = reactive({ address: '', phones: [], emails: [], socials: [], whatsapp: '', map_embed: '' })
const success = ref('')
const error = ref('')

const load = async () => {
  try {
    const data = await get()
    form.address = data.address || ''
    form.phones = data.phones || []
    form.emails = data.emails || []
    form.socials = data.socials || []
    form.whatsapp = data.whatsapp || ''
    form.map_embed = data.map_embed || ''
  } catch (e) {
    error.value = 'Error cargando datos'
  }
}

const save = async () => {
  try {
    await update({ ...form })
    success.value = 'Guardado'
    setTimeout(() => success.value = '', 3000)
  } catch (e) {
    error.value = 'Error guardando'
    setTimeout(() => error.value = '', 3000)
  }
}

const addPhone = () => form.phones.push('')
const removePhone = (i) => form.phones.splice(i,1)
const addEmail = () => form.emails.push('')
const removeEmail = (i) => form.emails.splice(i,1)
const addSocial = () => form.socials.push({ name: '', url: '' })
const removeSocial = (i) => form.socials.splice(i,1)

onMounted(() => load())
</script>
