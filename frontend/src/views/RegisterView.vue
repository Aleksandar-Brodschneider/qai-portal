<!-- src/views/RegisterView.vue -->
<script setup>
// Uvozimo ref za reaktivne spremenljivke
import { ref } from 'vue'
// Uvozimo router, da po uspešni registraciji preusmerimo na /login
import { useRouter } from 'vue-router'
// Uvozimo API_BASE (osnovni URL do Laravel backend-a)
import { API_BASE } from '../config'

const router = useRouter()

// Polja obrazca (vnos uporabnika)
const name = ref('')
const email = ref('')
const password = ref('')

// Stanja za UX
const loading = ref(false)
const errorMsg = ref('')
const okMsg = ref('')

// Funkcija za registracijo (kliče /api/users z metodo POST)
const register = async () => {
  // Počistimo sporočila
  errorMsg.value = ''
  okMsg.value = ''

  // Minimalna front-end validacija (da ne pošiljamo očitno napačnih podatkov)
  if (name.value.trim().length === 0) {
    errorMsg.value = 'Ime je obvezno.'
    return
  }
  if (email.value.trim().length === 0) {
    errorMsg.value = 'Email je obvezen.'
    return
  }
  if (password.value.length < 8) {
    errorMsg.value = 'Geslo mora imeti vsaj 8 znakov.'
    return
  }

  loading.value = true

  try {
    // Pošljemo POST na backend: /api/users
    const res = await fetch(`${API_BASE}/users`, {
      method: 'POST',
      headers: {
        // Povemo, da pošiljamo JSON
        'Content-Type': 'application/json',
        // Povemo, da želimo JSON odgovor
        Accept: 'application/json',
      },
      body: JSON.stringify({
        // trim pri name/email, da ne pošiljamo presledkov
        name: name.value.trim(),
        email: email.value.trim(),
        password: password.value,
      }),
    })

    // Preverimo Content-Type, da se izognemo JSON errorjem (npr. 302/HTML)
    const contentType = res.headers.get('content-type') || ''
    const data = contentType.includes('application/json') ? await res.json() : {}

    // Če odgovor ni uspešen (npr. 422 validacija)
    if (!res.ok) {
      // Laravel pogosto vrne: { message: "...", errors: { field: ["..."] } }
      const message =
        data?.message ||
        (data?.errors ? Object.values(data.errors).flat().join(' ') : '') ||
        `Napaka (HTTP ${res.status})`

      throw new Error(message)
    }

    // Uspeh: prikažemo sporočilo
    okMsg.value = `Uporabnik ustvarjen: ${data.name} (id=${data.id})`

    // Po 600ms preusmerimo na login
    setTimeout(() => router.push('/login'), 600)
  } catch (e) {
    // Prikažemo napako (npr. email že obstaja)
    errorMsg.value = String(e?.message || e)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <!-- Osnovni layout strani -->
  <main class="page">
    <h1>Registracija</h1>

    <!-- Obrazec -->
    <form class="card" @submit.prevent="register">
      <label class="field">
        <span>Ime</span>
        <input v-model.trim="name" required placeholder="Andreas Wichert" />
      </label>

      <label class="field">
        <span>Email</span>
        <input v-model.trim="email" required type="email" placeholder="andreas.wichert@qai.test" />
      </label>

      <label class="field">
        <span>Geslo (min 8 znakov)</span>
        <input v-model="password" required type="password" placeholder="Password123" />
      </label>

      <button class="btn" type="submit" :disabled="loading">
        {{ loading ? 'Ustvarjam...' : 'Registriraj' }}
      </button>

      <!-- Napaka -->
      <p v-if="errorMsg" class="error">Napaka: {{ errorMsg }}</p>

      <!-- OK -->
      <p v-if="okMsg" class="ok">{{ okMsg }}</p>
    </form>
  </main>
</template>

<style scoped>
/* Osnovni razmik */
.page {
  min-height: calc(100vh - 220px); /* header+footer rezerva */
  display: flex;
  flex-direction: column;  
  align-items: center;     /* horizontalno */
  justify-content: center; /* vertikalno */
  gap: 16px;
  padding: 14px;
}


.card {
  width: 100%;
  max-width: 420px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 14px;
}


h1 {
  margin: 0;
  text-align: center;
}

/* Polje */
.field {
  display: grid;
  gap: 6px;
  margin-bottom: 12px;
}

/* Input */
input {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.04);
  color: inherit;
}

/* Gumb */
.btn {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.06);
  cursor: pointer;
}

/* Napaka */
.error {
  color: #ff8a8a;
  margin-top: 10px;
}

/* OK */
.ok {
  color: #9dffb0;
  margin-top: 10px;
}
</style>