<script setup>
// ---------------------------------------------
// USERS VIEW (CRUD DEMO) – povezava na Laravel API
// Endpointi:
//   GET    /api/users
//   POST   /api/users
//   PATCH  /api/users/{id}
//   DELETE /api/users/{id}
// ---------------------------------------------

// Uvozimo ref in onMounted za stanje in inicialno nalaganje
import { ref, onMounted } from 'vue'

// Uvozimo API_BASE (npr. "http://127.0.0.1:8000/api")
import { API_BASE } from '../config'

// Seznam uporabnikov
const users = ref([])

// UX statusi
const loading = ref(false)
const errorMsg = ref('')
const okMsg = ref('')

// -----------------------------
// CREATE (POST /api/users)
// -----------------------------
const name = ref('')
const email = ref('')
const password = ref('')

// Funkcija: naloži vse uporabnike
const fetchUsers = async () => {
  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/users`, {
      method: 'GET',
      headers: { Accept: 'application/json' },
    })

    const data = await res.json().catch(() => ([]))

    if (!res.ok) {
      const message =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '') ||
        `Napaka (HTTP ${res.status})`
      throw new Error(message)
    }

    users.value = Array.isArray(data) ? data : []
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// Funkcija: ustvari novega uporabnika
const createUser = async () => {
  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/users`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
      }),
    })

    const data = await res.json().catch(() => ({}))

    if (!res.ok) {
      const message =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '') ||
        `Napaka (HTTP ${res.status})`
      throw new Error(message)
    }

    okMsg.value = `Uporabnik ustvarjen (id=${data.id}).`

    // Po uspehu počistimo obrazec
    name.value = ''
    email.value = ''
    password.value = ''

    // Osvežimo seznam
    await fetchUsers()
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// -----------------------------
// UPDATE (PATCH /api/users/{id})
// -----------------------------
const editing = ref(false)
const editId = ref(null)
const editName = ref('')
const editEmail = ref('')
const editPassword = ref('')

// Začetek urejanja: napolnimo polja iz izbranega userja
const startEdit = (u) => {
  editing.value = true
  editId.value = u.id
  editName.value = u.name || ''
  editEmail.value = u.email || ''
  editPassword.value = '' // gesla nikoli ne prikazujemo
  okMsg.value = ''
  errorMsg.value = ''
}

// Prekliči urejanje
const cancelEdit = () => {
  editing.value = false
  editId.value = null
  editName.value = ''
  editEmail.value = ''
  editPassword.value = ''
}

// Shrani urejanje (PATCH)
const updateUser = async () => {
  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    // Sestavimo body – password pošljemo le, če je nekaj vpisano
    const body = {
      name: editName.value,
      email: editEmail.value,
    }
    if (editPassword.value.trim().length > 0) {
      body.password = editPassword.value
    }

    const res = await fetch(`${API_BASE}/users/${editId.value}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify(body),
    })

    const data = await res.json().catch(() => ({}))

    if (!res.ok) {
      // Če je 403 (admin block), bo backend poslal message – pokažemo ga
      const message =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '') ||
        `Napaka (HTTP ${res.status})`
      throw new Error(message)
    }

    okMsg.value = `Uporabnik posodobljen (id=${data.id}).`

    cancelEdit()
    await fetchUsers()
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// -----------------------------
// DELETE (DELETE /api/users/{id})
// -----------------------------
const deleteUser = async (id) => {
  const ok = confirm(`Res želiš izbrisati uporabnika id=${id}?`)
  if (!ok) return

  loading.value = true
  errorMsg.value = ''
  okMsg.value = ''

  try {
    const res = await fetch(`${API_BASE}/users/${id}`, {
      method: 'DELETE',
      headers: { Accept: 'application/json' },
    })

    const data = await res.json().catch(() => ({}))

    if (!res.ok) {
      const message =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '') ||
        `Napaka (HTTP ${res.status})`
      throw new Error(message)
    }

    okMsg.value = `Uporabnik izbrisan (id=${id}).`
    await fetchUsers()
  } catch (e) {
    errorMsg.value = String(e.message || e)
  } finally {
    loading.value = false
  }
}

// Ob prvem prikazu strani naložimo uporabnike
onMounted(fetchUsers)
</script>

<template>
  <main class="page">
    <header class="head">
      <h1>Users</h1>
    </header>

    <!-- CREATE -->
    <section class="card">
      <h2>Ustvari novega uporabnika</h2>

      <form class="grid" @submit.prevent="createUser">
        <label class="field">
          <span>Ime</span>
          <input v-model="name" placeholder="Giulio Chiribella" />
        </label>

        <label class="field">
          <span>Email</span>
          <input v-model="email" type="email" placeholder="giulio.chiribella@qai.test" />
        </label>

        <label class="field full">
          <span>Geslo (min 8 znakov)</span>
          <input v-model="password" type="password" placeholder="Password123" />
        </label>
      <div class="actions full">
        <button class="btn" type="submit" :disabled="loading">
          {{ loading ? 'Ustvarjam...' : 'Ustvari' }}
        </button>
      </div>
      </form>

      <p v-if="errorMsg" class="error">Napaka: {{ errorMsg }}</p>
      <p v-if="okMsg" class="ok">{{ okMsg }}</p>
    </section>

    <!-- LIST -->
    <section class="card">
      <div class="row">
        <h2>Seznam uporabnikov</h2>
        <button class="btn" type="button" @click="fetchUsers" :disabled="loading">Osveži</button>
      </div>

      <p v-if="loading" class="muted">Nalaganje...</p>

      <table v-if="!loading && users.length" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Ime</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Created</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="u in users" :key="u.id">
            <td>{{ u.id }}</td>
            <td class="wrap">{{ u.name }}</td>
            <td class="wrap">{{ u.email }}</td>
            <td>{{ u.is_admin ? 'DA' : 'NE' }}</td>
            <td class="wrap">{{ u.created_at }}</td>
            <td class="right">
              <button class="btn" type="button" @click="startEdit(u)" :disabled="loading">
                Edit
              </button>
              <button class="btn danger" type="button" @click="deleteUser(u.id)" :disabled="loading">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <p v-if="!loading && !users.length" class="muted">Ni uporabnikov (ali endpoint vrača prazno).</p>
    </section>

    <!-- UPDATE -->
    <section v-if="editing" class="card">
      <h2>Uredi uporabnika (id={{ editId }})</h2>

      <div class="grid">
        <label class="field">
          <span>Ime</span>
          <input v-model="editName" />
        </label>

        <label class="field">
          <span>Email</span>
          <input v-model="editEmail" type="email" />
        </label>

        <label class="field full">
          <span>Novo geslo (pusti prazno, če ne želiš spreminjati)</span>
          <input v-model="editPassword" type="password" placeholder="NovoGeslo123" />
        </label>

        <div class="row">
          <button class="btn" type="button" @click="updateUser" :disabled="loading">
            Shrani (PATCH)
          </button>
          <button class="btn" type="button" @click="cancelEdit" :disabled="loading">
            Prekliči
          </button>
        </div>
      </div>

      <p v-if="errorMsg" class="error">Napaka: {{ errorMsg }}</p>
      <p v-if="okMsg" class="ok">{{ okMsg }}</p>
    </section>
  </main>
</template>

<!-- CSS-->
<style scoped>
.page { padding: 24px; display: grid; gap: 16px; }
.head h1 { margin: 0 0 6px 0; }
.muted { opacity: .75; margin: 0; }

.card {
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 14px;
  padding: 16px;
  background: rgba(255,255,255,.03);
}

.grid { display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
.full { grid-column: 1 / -1; }

.field { display: grid; gap: 6px; }
input {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,.18);
  background: rgba(255,255,255,.04);
  color: inherit;
}

.btn {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,.18);
  background: rgba(255,255,255,.06);
  cursor: pointer;
}
.btn:hover { background: rgba(255,255,255,.10); }
.danger { border-color: rgba(255,80,80,.35); }
.danger:hover { background: rgba(255,80,80,.15); }

.row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border-bottom: 1px solid rgba(255,255,255,.10); padding: 10px; text-align: left; vertical-align: top; }
.right { text-align: right; }
.wrap { max-width: 420px; word-break: break-word; }

.error { color: #ff8a8a; margin-top: 10px; }
.ok { color: #9dffb0; margin-top: 10px; }

.actions {
  display: flex;
  justify-content: center;
}
</style>
