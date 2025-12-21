<script setup>
// Uvozimo ref, da lahko shranjujemo rezultat klica API-ja in ga prikažemo na strani
import { ref } from 'vue'

// Spremenljivka, kamor shranimo tekst/JSON odgovor iz backend-a
const apiResult = ref('')

// Spremenljivka za prikaz napake (če backend ne dela ali je napačen URL)
const apiError = ref('')

// Funkcija, ki se izvede ob kliku na gumb "Test API"
const testApi = async () => {
  // Pred vsakim klicem počistimo stare rezultate
  apiResult.value = ''
  apiError.value = ''

  try {
    // Kličemo Laravel backend endpoint /api/test
    // Pomembno: tukaj je backend na 127.0.0.1:8000
    const response = await fetch('http://127.0.0.1:8000/api/test', {
      method: 'GET',
      headers: {
        // Povemo, da želimo JSON odgovor (ni nujno, ampak je lepo)
        Accept: 'application/json',
      },
    })

    // Če status ni 2xx, sprožimo napako
    if (!response.ok) {
      throw new Error(`HTTP ${response.status} ${response.statusText}`)
    }

    // Preberemo JSON (ker /api/test vrača JSON)
    const data = await response.json()

    // Lepo formatiramo JSON, da je berljiv na strani
    apiResult.value = JSON.stringify(data, null, 2)
  } catch (err) {
    // Če pride do napake, jo pokažemo uporabniku
    apiError.value = String(err)
  }
}
</script>

<template>
  <!-- Glavni okvir strani -->
  <main class="page">
    <!-- Naslov in kratek opis -->
    <header class="header">
      <h1 class="title">QAI Portal</h1>
      <p class="subtitle">
        Minimalni frontend (Vue + Vite) za preverjanje backend API-ja (Laravel).
      </p>
    </header>

    <!-- Akcije -->
    <section class="card">
      <h2 class="cardTitle">Povezava na backend</h2>

      <!-- Gumb za test klica -->
      <button class="btn" @click="testApi">
        Test API (/api/test)
      </button>

      <!-- Prikaz napake -->
      <p v-if="apiError" class="error">
        Napaka: {{ apiError }}
      </p>

      <!-- Prikaz rezultata -->
      <pre v-if="apiResult" class="result">{{ apiResult }}</pre>

      <!-- Namig, če še ni bilo klika -->
      <p v-if="!apiResult && !apiError" class="hint">
        Klikni gumb, da preveriš, ali backend dela.
      </p>
    </section>

    <!-- Footer -->
    <footer class="footer">
      Backend: <code>http://127.0.0.1:8000</code> • Frontend: <code>http://localhost:5173</code>
    </footer>
  </main>
</template>

<style scoped>
/* Osnovni layout strani */
.page {
  max-width: 900px;
  margin: 0 auto;
  padding: 32px 16px;
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

/* Glava */
.header {
  margin-bottom: 24px;
}
.title {
  margin: 0 0 8px 0;
  font-size: 40px;
  line-height: 1.1;
}
.subtitle {
  margin: 0;
  opacity: 0.75;
}

/* Kartica */
.card {
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 14px;
  padding: 18px;
  background: rgba(255, 255, 255, 0.03);
}
.cardTitle {
  margin: 0 0 12px 0;
  font-size: 18px;
}

/* Gumb */
.btn {
  padding: 10px 14px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.06);
  cursor: pointer;
}
.btn:hover {
  background: rgba(255, 255, 255, 0.10);
}

/* Rezultat */
.result {
  margin-top: 14px;
  padding: 12px;
  border-radius: 10px;
  background: rgba(0, 0, 0, 0.35);
  overflow: auto;
}

/* Napaka */
.error {
  margin-top: 14px;
  color: #ff8a8a;
}

/* Namig */
.hint {
  margin-top: 14px;
  opacity: 0.75;
}

/* Footer */
.footer {
  margin-top: 18px;
  opacity: 0.65;
  font-size: 12px;
}
</style>