<script setup>
/*
|--------------------------------------------------------------------------
| RESEARCH DETAIL VIEW
|--------------------------------------------------------------------------
| Prikaz ene posamezne raziskave
| - ID raziskave dobimo iz URL-ja (/research/:id)
| - podatke naložimo iz Laravel API-ja
| - view je read-only (urejanje pride kasneje za admina)
|--------------------------------------------------------------------------
*/

// Vue reaktivnost in lifecycle
import { ref, onMounted } from 'vue'

// Dostop do parametrov v URL-ju (npr. :id)
import { useRoute } from 'vue-router'

// Osnovni URL backend API-ja
import { API_BASE } from '../config'

// Route objekt (da dobimo route.params.id)
const route = useRoute()

// Reaktivni podatki
const research = ref(null)     // naložena raziskava
const loading = ref(false)     // stanje nalaganja
const errorMsg = ref('')       // morebitna napaka

/*
|--------------------------------------------------------------------------
| FETCH DETAIL
|--------------------------------------------------------------------------
| Naloži eno raziskavo glede na ID v URL-ju
| Endpoint: GET /api/research/{id}
|--------------------------------------------------------------------------
*/
const fetchDetail = async () => {
  loading.value = true
  errorMsg.value = ''

  try {
    const res = await fetch(
      `${API_BASE}/research/${route.params.id}`,
      { headers: { Accept: 'application/json' } }
    )

    if (!res.ok) {
      throw new Error('Napaka pri nalaganju raziskave.')
    }

    // Shrani odgovor v reaktivno spremenljivko
    research.value = await res.json()
  } catch (e) {
    errorMsg.value = e.message || 'Neznana napaka'
  } finally {
    loading.value = false
  }
}

// Ko se komponenta prikaže, naložimo podatke
onMounted(fetchDetail)
</script>

<template>
  <main class="page">

    <!-- Nalaganje -->
    <p v-if="loading">Nalaganje…</p>

    <!-- Napaka -->
    <p v-if="errorMsg" class="error">{{ errorMsg }}</p>

    <!-- Podrobnosti raziskave -->
    <section v-if="research" class="card">
      <h1>{{ research.title }}</h1>

      <p><b>Avtorji:</b> {{ research.authors }}</p>
      <p><b>Leto:</b> {{ research.year }}</p>

      <p v-if="research.doi">
        <b>DOI:</b> {{ research.doi }}
      </p>

      <hr />

      <p>{{ research.abstract }}</p>
    </section>

  </main>
</template>

<style scoped>
/*
|--------------------------------------------------------------------------
| STIL
|--------------------------------------------------------------------------
*/
.page {
  padding: 24px;
}

.card {
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 14px;
  padding: 16px;
}

.error {
  color: #ff8a8a;
}
</style>