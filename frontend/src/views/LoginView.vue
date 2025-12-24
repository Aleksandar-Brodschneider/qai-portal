<!-- src/views/LoginView.vue -->
<script setup>
// Uvozimo ref za reaktivna polja
import { ref } from 'vue'
// Router za preusmerjanje po prijavi
import { useRouter } from 'vue-router'
// Osnovni backend URL
import { API_BASE } from '../config'

const router = useRouter()

// Polja obrazca
const email = ref('')
const password = ref('')

// Stanja
const loading = ref(false)
const errorMsg = ref('')
const okMsg = ref('')

// Funkcija za prijavo (POST /api/login)
const login = async () => {
  errorMsg.value = ''
  okMsg.value = ''

  // Minimalna validacija
  if (email.value.trim().length === 0) {
    errorMsg.value = 'Email je obvezen.'
    return
  }
  if (password.value.length === 0) {
    errorMsg.value = 'Geslo je obvezno.'
    return
  }

  loading.value = true

  try {
    // Kličemo backend endpoint /api/login
    const res = await fetch(`${API_BASE}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        email: email.value.trim(),
        password: password.value,
      }),
    })

    // Robustno branje JSON (da se ne zalomi pri HTML odgovorih)
    const contentType = res.headers.get('content-type') || ''
    const data = contentType.includes('application/json') ? await res.json() : {}

    // Če login ni uspel (401 ali 422)
    if (!res.ok) {
      const message =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '') ||
        `Napaka (HTTP ${res.status})`

      throw new Error(message)
    }

    // Uspešna prijava: shranimo "session" v localStorage (DEV demo)
    // OPOMBA: To ni pravi auth (Sanctum pride kasneje) – to je samo demo.
    // Uspešna prijava: shrani user v localStorage
localStorage.setItem('qai_user', JSON.stringify(data))

//povej App.vue, naj se osveži
window.dispatchEvent(new Event('qai-user-changed'))

okMsg.value = `Prijavljen: ${data.name} (${data.email})`

//redirect samo ENKRAT
if (data.is_admin) router.push('/admin')
else router.push('/')

    
  } 
  
  catch (e) {
    errorMsg.value = String(e?.message || e)
  } 
  
  finally {
    loading.value = false
  }
}

// Funkcija za odjavo (počisti localStorage)
const logout = () => {
  localStorage.removeItem('qai_user')
  window.dispatchEvent(new Event('qai-user-changed'))
  okMsg.value = 'Odjavljen.'
}
</script>

<template>
  <main class="page">
    <h1>Prijava</h1>

    <form class="card" @submit.prevent="login">
      <label class="field">
        <span>Email</span>
        <input v-model.trim="email" required type="email" placeholder="admin@qai-portal.test" />
      </label>

      <label class="field">
        <span>Geslo</span>
        <input v-model="password" required type="password" placeholder="Admin12345" />
      </label>

      <div class="row">
        <button class="btn" type="submit" :disabled="loading">
          {{ loading ? 'Prijavljam...' : 'Prijavi se' }}
        </button>


      </div>

      <p v-if="errorMsg" class="error">Napaka: {{ errorMsg }}</p>
      <p v-if="okMsg" class="ok">{{ okMsg }}</p>

    
    </form>
  </main>
</template>

<style scoped>
.page {
  padding: 24px;
}
.card {
  max-width: 420px;
  padding: 16px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 14px;
}
.field {
  display: grid;
  gap: 6px;
  margin-bottom: 12px;
}
input {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.04);
  color: inherit;
}
.row {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.btn {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.06);
  cursor: pointer;
}
.btn.secondary {
  opacity: 0.85;
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
  margin-top: 10px;
  opacity: 0.7;
  font-size: 12px;
}
</style>