import axios from 'axios'

export default function useContactSettings() {
  const base = '/api/contact-settings'

  const get = async () => {
    const res = await axios.get(base)
    return res.data
  }

  const update = async (payload) => {
    const res = await axios.put(base, payload)
    return res.data
  }

  return { get, update }
}
