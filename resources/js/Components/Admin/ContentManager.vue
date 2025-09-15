<template>
  <div class="max-w-6xl mx-auto py-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-semibold">Contenido / Especialidades</h2>
      <button @click="openForm()" class="px-4 py-2 bg-indigo-600 text-white rounded">Nuevo</button>
    </div>

    <div class="bg-white rounded shadow p-4">
      <table class="w-full text-left">
        <thead>
          <tr>
            <th class="py-2">#</th>
            <th class="py-2">Título</th>
            <th class="py-2">Tipo</th>
            <th class="py-2">Activo</th>
            <th class="py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in contents" :key="c.id" class="border-t">
            <td class="py-2">{{ c.id }}</td>
            <td class="py-2">{{ c.title }}</td>
            <td class="py-2">{{ c.type }}</td>
            <td class="py-2">{{ c.is_active ? 'Sí' : 'No' }}</td>
            <td class="py-2">
              <button @click="edit(c)" class="px-3 py-1 border rounded">Editar</button>
              <button @click="removeContent(c.id)" class="px-3 py-1 border rounded ms-2 text-red-600">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- simple modal form -->
    <div v-if="showForm" class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
      <div class="bg-white rounded p-6 w-3/4 max-w-2xl">
        <h3 class="text-lg font-semibold mb-3">{{ form.id ? 'Editar' : 'Nuevo' }} contenido</h3>
        <div class="grid grid-cols-1 gap-3">
          <input v-model="form.title" placeholder="Título" class="border p-2 rounded" />
          <input v-model="form.subtitle" placeholder="Subtítulo" class="border p-2 rounded" />
          <select v-model="form.type" class="border p-2 rounded">
            <option value="general">General</option>
            <option value="service">Service</option>
            <option value="technology">Technology</option>
            <option value="area">Area</option>
          </select>
          <textarea v-model="form.content" placeholder="Contenido" class="border p-2 rounded h-36"></textarea>
          <div class="flex items-center gap-2">
            <label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_active" /> Activo</label>
            <label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_featured" /> Destacado</label>
          </div>

          <div class="mt-2">
            <label class="block text-sm text-gray-600">Imagen</label>
            <input type="file" @change="onFileChange" accept="image/*" />
            <div v-if="preview" class="mt-2">
              <img :src="preview" class="max-h-40 rounded" />
            </div>
          </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
          <button @click="closeForm" class="px-3 py-1 border rounded">Cancelar</button>
          <button @click="save" class="px-3 py-1 bg-indigo-600 text-white rounded">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import useContents from '@/composables/useContents'

const { list, create, update, remove } = useContents()
const contents = ref([])
const showForm = ref(false)
const form = ref({ id: null, title: '', subtitle: '', content: '', type: 'service', image: '', is_featured: false, is_active: true })

async function load() {
  contents.value = await list()
}

function openForm() {
  form.value = { id: null, title: '', subtitle: '', content: '', type: 'service', image: '', is_featured: false, is_active: true }
  preview.value = null
  file.value = null
  showForm.value = true
}

function edit(c) {
  form.value = { ...c }
  preview.value = c.image_url || null
  file.value = null
  showForm.value = true
}

function closeForm() { showForm.value = false }

async function save() {
  // build payload; prefer sending JSON when no file is selected
  let payload

  if (file.value) {
    // when there's a file, send FormData
    const fd = new FormData()
    const allowed = ['title', 'subtitle', 'content', 'type', 'is_featured', 'is_active']
    for (const k of allowed) {
      const v = form.value[k]
      if (v === null || v === undefined) continue
      if (k === 'is_featured' || k === 'is_active') {
        fd.append(k, v ? 1 : 0)
      } else {
        fd.append(k, v)
      }
    }
    fd.append('image', file.value)
    payload = fd
  } else {
    // send JSON but remove `image` key to avoid Laravel's image validator failing on a string/path
    const obj = { ...form.value }
    if ('image' in obj) delete obj.image
    payload = obj
  }

  if (form.value.id) {
    await update(form.value.id, payload)
  } else {
    await create(payload)
  }
  await load()
  showForm.value = false
}

async function removeContent(id) {
  if (!confirm('Eliminar contenido?')) return
  await remove(id)
  await load()
}

onMounted(() => load())

const file = ref(null)
const preview = ref(null)

function onFileChange(e) {
  const f = e.target.files[0]
  if (!f) return
  file.value = f
  preview.value = URL.createObjectURL(f)
}
</script>
