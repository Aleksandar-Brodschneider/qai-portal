<script setup>
// ---------------------------------------------
// RESEARCH VIEW – FULL CRUD DEMO
// GET / POST / PATCH / DELETE
// user_id se bere iz localStorage (prijavljen user)
// ---------------------------------------------

import { ref, onMounted, computed } from 'vue'
import { API_BASE } from '../config'
import { useRoute } from 'vue-router'

const route = useRoute()

// 1) Seznam research mora biti prej!
const items = ref([])

// 2) Query filter
const q = computed(() => String(route.query.q || '').trim().toLowerCase())

// 3) Filtriranje po title ali authors
const filteredItems = computed(() => {
  if (!q.value) return items.value
  return items.value.filter((r) => {
    const title = String(r.title || '').toLowerCase()
    const authors = String(r.authors || '').toLowerCase()
    return title.includes(q.value) || authors.includes(q.value)
  })
})

// Preberemo prijavljenega uporabnika
const rawUser = localStorage.getItem('qai_user')
const currentUser = rawUser ? JSON.parse(rawUser) : null

// UX stanje
const loading = ref(false)
const errorMsg = ref('')
const okMsg = ref('')

// CREATE polja
const title = ref('')
const authors = ref('')
const abstractText = ref('')
const year = ref(2025)
const doi = ref('')

// EDIT stanje
const editing = ref(false)
const editId = ref(null)
const editTitle = ref('')
const editAuthors = ref('')
const editAbstract = ref('')
const editYear = ref(2025)
const editDoi = ref('')

// ---------------------------------------------
// FETCH – GET /api/research
// ---------------------------------------------
const fetchResearch = async () => {
  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/research`, {
      headers: { Accept: 'application/json' },
    })

    const data = await res.json().catch(() => [])

    if (!res.ok) throw new Error(`HTTP ${res.status}`)

    items.value = Array.isArray(data) ? data : []
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// ---------------------------------------------
// CREATE – POST /api/research
// ---------------------------------------------
const createResearch = async () => {
  if (!currentUser) {
    errorMsg.value = 'Ni prijavljenega uporabnika.'
    return
  }

  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/research`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        user_id: currentUser.id,
        title: title.value,
        authors: authors.value,
        abstract: abstractText.value,
        year: Number(year.value),
        doi: doi.value,
      }),
    })

    const data = await res.json().catch(() => ({}))

    if (!res.ok) {
      const msg =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '')
      throw new Error(msg || `HTTP ${res.status}`)
    }

    okMsg.value = `Raziskava ustvarjena (id=${data.id})`

    // reset
    title.value = ''
    authors.value = ''
    abstractText.value = ''
    doi.value = ''

    await fetchResearch()
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// ---------------------------------------------
// START EDIT
// ---------------------------------------------
const startEdit = (r) => {
  editing.value = true
  editId.value = r.id
  editTitle.value = r.title
  editAuthors.value = r.authors
  editAbstract.value = r.abstract
  editYear.value = r.year
  editDoi.value = r.doi
}

// ---------------------------------------------
// UPDATE – PATCH /api/research/{id}
// ---------------------------------------------
const updateResearch = async () => {
  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/research/${editId.value}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        title: editTitle.value,
        authors: editAuthors.value,
        abstract: editAbstract.value,
        year: Number(editYear.value),
        doi: editDoi.value,
      }),
    })

    const data = await res.json().catch(() => ({}))

    if (!res.ok) {
      const msg =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '')
      throw new Error(msg || `HTTP ${res.status}`)
    }

    okMsg.value = `Raziskava posodobljena (id=${editId.value})`
    editing.value = false
    editId.value = null

    await fetchResearch()
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// ---------------------------------------------
// DELETE – DELETE /api/research/{id}
// ---------------------------------------------
const deleteResearch = async (id) => {
  if (!confirm(`Res želiš izbrisati research id=${id}?`)) return

  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/research/${id}`, {
      method: 'DELETE',
      headers: { Accept: 'application/json' },
    })

    if (!res.ok) throw new Error(`HTTP ${res.status}`)

    okMsg.value = `Raziskava izbrisana (id=${id})`
    await fetchResearch()
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// Init
onMounted(fetchResearch)
</script>

<template>
  <main class="page">
    <h1>Research</h1>

    <p class="muted">
      Prijavljen uporabnik:
      <b>{{ currentUser?.name || '—' }}</b>
    </p>

    <!-- CREATE -->
    <section class="card">
      <h2>Nova raziskava</h2>

      <form class="grid" @submit.prevent="createResearch">
        <input v-model="title" placeholder="Title" />
        <input v-model="authors" placeholder="Authors" />
        <textarea v-model="abstractText" rows="4" placeholder="Abstract"></textarea>
        <input v-model="year" type="number" />
        <input v-model="doi" placeholder="DOI" />
        <button class="btn">Ustvari</button>
      </form>
    </section>

    <!-- LIST -->
        <section class="card">
      <h2>Seznam raziskav</h2>
      <p v-if="q" class="muted">Filter: <b>{{ q }}</b></p>
      <table v-if="filteredItems.length" class="table">
        <tr v-for="r in filteredItems" :key="r.id">
          <td>{{ r.id }}</td>
          <td>{{ r.title }}</td>
          <td>{{ r.year }}</td>
          <td>
            <button class="btn" @click="startEdit(r)">Edit</button>
            <button class="btn danger" @click="deleteResearch(r.id)">Delete</button>
          </td>
        </tr>
      </table>

      <p v-else class="muted">Ni zadetkov.</p>
    </section>

    <!-- EDIT -->
    <section v-if="editing" class="card">
      <h2>Uredi raziskavo</h2>

      <form class="grid" @submit.prevent="updateResearch">
        <input v-model="editTitle" />
        <input v-model="editAuthors" />
        <textarea v-model="editAbstract" rows="4"></textarea>
        <input v-model="editYear" type="number" />
        <input v-model="editDoi" />
        <button class="btn">Shrani</button>
      </form>
    </section>
    
    <p v-if="errorMsg" class="error">Napaka: {{ errorMsg }}</p>
    <p v-if="okMsg" class="ok">{{ okMsg }}</p>
  </main>
</template>

<style scoped>
.page { padding: 24px; display: grid; gap: 16px; }
.card { border: 1px solid rgba(255,255,255,.12); border-radius: 14px; padding: 16px; }
.grid { display: grid; gap: 10px; }
input, textarea { padding: 8px; }
.btn { padding: 8px 12px; }
.danger { background: rgba(255,0,0,.2); }
.muted { opacity: .7; }
.error { color: #ff8a8a; }
.ok { color: #9dffb0; }
</style>