<script setup>
import heroImg from '../assets/hero.png'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const query = ref('')

const search = () => {
  const q = query.value.trim()
  if (!q) return

  // preusmeri na /research?q=...
  router.push({ name: 'research', query: { q } })
}
</script>

<template>
  <main class="home">

    <!-- HERO SEKCIJA -->
    <section class="hero">
      <h1 class="title">QAI RESEARCH PORTAL</h1>

      <p class="subtitle">
        RAZISKOVALNI PORTAL ZA KVANTNO UMETNO INTELIGENCO
      </p>
      <img :src="heroImg" alt="QAI illustration" class="hero-image" />
      <!-- SEARCH -->
    <div class="search-box">
      <input
        v-model="query"
        type="text"
        placeholder="Išči raziskave po naslovu ali avtorju…"
        @keyup.enter="search"
      />
      <button class="btn primary" @click="search">
      <b>I Š Č I</b>
      </button>
    </div>
    </section>

    <!-- OPISNA SEKCIJA -->
    <section class="info">
      <div class="card">
        <h2>Kaj je QAI?</h2>
        <p>
          Quantum Artificial Intelligence (QAI) združuje kvantno fiziko,
          teorijo informacij in umetno inteligenco za razvoj novih
          računalniških paradigem.
        </p>
      </div>

      <div class="card">
        <h2>Namen portala</h2>
        <p>
          Portal omogoča pregled najnovejših raziskav s področja kvantne umetne inteligence in nalaganje lastnih strokovnih raziskav.
        </p>
      </div>

      <div class="card">
        <h2>Tehnologije</h2>
        <p>
          Laravel REST API (backend)
         <br> 
         Vue 3 + Vite (frontend)
         <br>
          MySQL (database) 
        </p>
      </div>
    </section>

  </main>
</template>

<style scoped>
/* Glavni layout */
.home {
  display: grid;
  gap: 48px;
  padding: 1px 20px;
}

/* HERO */
.hero {
  text-align: center;
}

.title {
  font-size: 3rem;
  margin-bottom: 12px;
}

.subtitle {
  font-size: 1.5rem;
  opacity: 5.8;
  font-weight: bold;
}

/* Gumbi */
.actions {
  margin-top: 24px;
  display: flex;
  justify-content: center;
  gap: 16px;
}

.btn {
  padding: 12px 20px;
  border-radius: 10px;
  text-decoration:wavy;
  font-weight: 600;
  border: 1px solid transparent;
  width: min(150px, 90%);
}

.primary {
  background: #036542;
  color: white;
}

.primary:hover {
  background: #0ec92d;
}

.secondary {
  background: transparent;
  border-color: #646cff;
  color: #646cff;
}

.secondary:hover {
  background: rgba(100, 108, 255, 0.1);
}

/* INFO SEKCIJA */
.info {
  display: grid;
  gap: 24px;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
}

.card {
  padding: 20px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: rgba(255, 255, 255, 0.03);
}

.card h2 {
  margin-top: 0;
}
.hero-image {
  display: block;
  margin: 24px auto 0;
  width: 250px;
  height: 150px;
  object-fit: cover;
}
.search-box {
  margin-top: 28px;
  display: flex;
  justify-content: center;
  gap: 22px;
  flex-wrap: wrap;
}

.search-box input {
  width: min(520px, 90%);
  padding: 12px 100px 14px 14px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.25);
  background:white;
  color: #100d0d;
  font-size: 20px;
}

.search-box input::placeholder {
  opacity: 0.6;
}

.search-box input:focus {
  outline: none;
  border-color: #646cff;
}
</style>