<!-- src/views/AdminView.vue -->
<script setup>
// Uvozimo ref za reaktivna stanja
import { ref, onMounted } from 'vue'
// Uvozimo API_BASE (Laravel backend)
import { API_BASE } from '../config'
// Uvozimo router, da lahko preusmerimo uporabnika (če ni admin)
import { useRouter } from 'vue-router'

const router = useRouter()

// Preberemo prijavljenega uporabnika iz localStorage (DEV “session”)
const currentUser = ref(null)

// Seznami podatkov
const users = ref([])
const researches = ref([])

// Stanja (loading / napake)
const loadingUsers = ref(false)
const loadingResearch = ref(false)
const errorUsers = ref('')
const errorResearch = ref('')
const okMsg = ref('')

// Funkcija: varnostni check, ali je admin (DEV)
const ensureAdmin = () => {
  // Preberemo shranjenega uporabnika
  const raw = localStorage.getItem('qai_user')
  // Če ni prijave, preusmerimo na login
  if (!raw) {
    router.push('/login')
    return false
  }
  // Parsamo JSON v objekt
  const parsed = JSON.parse(raw)
  currentUser.value = parsed

  // Če ni admin, preusmerimo na home
  if (!parsed.is_admin) {
    router.push('/')
    return false
  }
  return true
}

// Funkcija: naloži uporabnike iz backend-a (GET /api/users)
const fetchUsers = async () => {
  errorUsers.value = ''
  okMsg.value = ''
  loadingUsers.value = true

  try {
    const res = await fetch(`${API_BASE}/users`, {
      method: 'GET',
      headers: { Accept: 'application/json' },
    })

    // Preverimo, če dobimo JSON
    const ct = res.headers.get('content-type') || ''
    const data = ct.includes('application/json') ? await res.json() : []

    if (!res.ok) {
      throw new Error(data?.message || `Napaka (HTTP ${res.status})`)
    }

    users.value = data
  } catch (e) {
    errorUsers.value = String(e?.message || e)
  } finally {
    loadingUsers.value = false
  }
}

// Funkcija: izbriše userja (DELETE /api/users/{id}), admina ne dovolimo brisati
const deleteUser = async (id, is_admin) => {
  errorUsers.value = ''
  okMsg.value = ''

  // Dodatna zaščita na frontendu (backend ima že blok)
  if (is_admin) {
    errorUsers.value = 'Admin uporabnika ne moreš brisati.'
    return
  }

  // Potrditev (da ne izbrišemo po nesreči)
  if (!confirm(`Ali res želiš izbrisati uporabnika id=${id}?`)) return

  try {
    const res = await fetch(`${API_BASE}/users/${id}`, {
      method: 'DELETE',
      headers: { Accept: 'application/json' },
    })

    const ct = res.headers.get('content-type') || ''
    const data = ct.includes('application/json') ? await res.json() : {}

    if (!res.ok) {
      throw new Error(data?.message || `Napaka (HTTP ${res.status})`)
    }

    okMsg.value = `Uporabnik id=${id} izbrisan.`
    // Po brisanju osvežimo seznam userjev
    await fetchUsers()
  } catch (e) {
    errorUsers.value = String(e?.message || e)
  }
}

// Funkcija: naloži raziskave (GET /api/research)
const fetchResearch = async () => {
  errorResearch.value = ''
  okMsg.value = ''
  loadingResearch.value = true

  try {
    const res = await fetch(`${API_BASE}/research`, {
      method: 'GET',
      headers: { Accept: 'application/json' },
    })

    const ct = res.headers.get('content-type') || ''
    const data = ct.includes('application/json') ? await res.json() : []

    if (!res.ok) {
      throw new Error(data?.message || `Napaka (HTTP ${res.status})`)
    }

    researches.value = data
  } catch (e) {
    errorResearch.value = String(e?.message || e)
  } finally {
    loadingResearch.value = false
  }
}

// Funkcija: izbriše raziskavo (DELETE /api/research/{id})
const deleteResearch = async (id) => {
  errorResearch.value = ''
  okMsg.value = ''

  if (!confirm(`Ali res želiš izbrisati raziskavo id=${id}?`)) return

  try {
    const res = await fetch(`${API_BASE}/research/${id}`, {
      method: 'DELETE',
      headers: { Accept: 'application/json' },
    })

    const ct = res.headers.get('content-type') || ''
    const data = ct.includes('application/json') ? await res.json() : {}

    if (!res.ok) {
      throw new Error(data?.message || `Napaka (HTTP ${res.status})`)
    }

    okMsg.value = `Raziskava id=${id} izbrisana.`
    // Osvežimo seznam raziskav
    await fetchResearch()
  } catch (e) {
    errorResearch.value = String(e?.message || e)
  }
}

// Ob prvem nalaganju strani naredimo: admin check + fetch data
onMounted(async () => {
  // Če ni admin, tukaj prekinemo (router preusmeri)
  if (!ensureAdmin()) return

  // Naložimo oba seznama
  await fetchUsers()
  await fetchResearch()
})
</script>

<template>
  <main class="page">
    <h1>Admin (DEV)</h1>

    <p class="hint">
      Ta stran je “demo admin panel” brez Sanctuma. V produkciji bo zaščiten z middleware/tokeni.
    </p>

    <p v-if="currentUser" class="who">
      Prijavljen: <b>{{ currentUser.name }}</b> ({{ currentUser.email }}) • is_admin={{ currentUser.is_admin }}
    </p>

    <p v-if="okMsg" class="ok">{{ okMsg }}</p>
    <div class="row">
    <RouterLink class="btn" to="/admin/users">Uredi uporabnike</RouterLink>
    <RouterLink class="btn" to="/research">Uredi raziskave</RouterLink>
    </div>
    <!-- USERS -->
    <section class="card">
      <div class="row">
        <h2>Uporabniki</h2>
        <button class="btn" @click="fetchUsers" :disabled="loadingUsers">
          {{ loadingUsers ? 'Nalagam...' : 'Osveži users' }}
        </button>
      </div>

      <p v-if="errorUsers" class="error">Napaka: {{ errorUsers }}</p>

      <table v-if="users.length" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Ime</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Ustvarjen</th>
            
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users" :key="u.id">
            <td>{{ u.id }}</td>
            <td>{{ u.name }}</td>
            <td>{{ u.email }}</td>
            <td>{{ u.is_admin ? 'DA' : 'NE' }}</td>
            <td>{{ u.created_at }}</td>
          </tr>
        </tbody>
      </table>

      <p v-else class="hint">Ni uporabnikov ali še niso naloženi.</p>
    </section>

    <!-- RESEARCH -->
    <section class="card">
      <div class="row">
        <h2>Raziskave</h2>
        <button class="btn" @click="fetchResearch" :disabled="loadingResearch">
          {{ loadingResearch ? 'Nalagam...' : 'Osveži research' }}
        </button>
      </div>

      <p v-if="errorResearch" class="error">Napaka: {{ errorResearch }}</p>

      <table v-if="researches.length" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Naslov</th>
            <th>Avtorji</th>
            <th>Leto</th>
            <th>DOI</th>
            <th>Akcije</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in researches" :key="r.id">
            <td>{{ r.id }}</td>
            <td>{{ r.user_id }}</td>
            <td class="wrap">{{ r.title }}</td>
            <td class="wrap">{{ r.authors }}</td>
            <td>{{ r.year }}</td>
            <td class="wrap">{{ r.doi }}</td>
            
          </tr>
        </tbody>
      </table>

      <p v-else class="hint">Ni raziskav ali še niso naložene.</p>
    </section>
  </main>
</template>

<style scoped>
.page {
  padding: 24px;
}
.card {
  margin-top: 16px;
  padding: 16px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.03);
}
.row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}
.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
}
.table th, .table td {
  border-bottom: 1px solid rgba(255,255,255,0.10);
  padding: 10px;
  text-align: left;
  vertical-align: top;
}
.wrap {
  max-width: 360px;
  white-space: normal;
  word-break: break-word;
}
.btn {
  padding: 8px 10px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.06);
  cursor: pointer;
}
.btn.danger {
  border-color: rgba(255, 120, 120, 0.45);
}
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.error {
  color: #ff8a8a;
  margin-top: 10px;
}
.ok {
  color: #9dffb0;
  margin-top: 10px;
}
.hint {
  opacity: 0.7;
  font-size: 12px;
}
.who {
  margin-top: 6px;
  opacity: 0.9;
}
</style>