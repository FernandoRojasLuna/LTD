import { ref } from 'vue'

export default function useContents() {
  const base = '/api/contents'

  const list = async (params = '') => {
    const res = await fetch(base + (params ? '?' + params : ''))
    return await res.json()
  }

  const get = async (id) => {
    const res = await fetch(`${base}/${id}`)
    return await res.json()
  }

  const create = async (payload) => {
    const opts = { method: 'POST' }
    if (payload instanceof FormData) {
      opts.body = payload
    } else {
      opts.headers = { 'Content-Type': 'application/json' }
      opts.body = JSON.stringify(payload)
    }
    const res = await fetch(base, opts)
    return await res.json()
  }

  const update = async (id, payload) => {
    const opts = { method: 'POST', headers: {} }
    // Laravel expects POST with _method=PUT when using FormData
    if (payload instanceof FormData) {
      payload.append('_method', 'PUT')
      opts.body = payload
    } else {
      opts.method = 'PUT'
      opts.headers = { 'Content-Type': 'application/json' }
      opts.body = JSON.stringify(payload)
    }
    const res = await fetch(`${base}/${id}`, opts)
    return await res.json()
  }

  const remove = async (id) => {
    const res = await fetch(`${base}/${id}`, { method: 'DELETE' })
    return res.ok
  }

  const featured = async () => {
    const res = await fetch(`${base}/featured`)
    return await res.json()
  }

  return { list, get, create, update, remove, featured }
}
