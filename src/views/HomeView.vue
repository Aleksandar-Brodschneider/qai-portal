<script setup>
// HomeView je landing page aplikacije.
// Tukaj NI logike za login, admin ali CRUD.
// Namen: prvi vtis + navigacija.
</script>

<template>
  <main class="home">

    <!-- HERO SEKCIJA -->
    <section class="hero">
      <h1 class="title">QAI Portal</h1>
      <p class="subtitle">
        Raziskovalni portal za kvantno umetno inteligenco (QAI).
      </p>

      <div class="actions">
        <RouterLink to="/login" class="btn primary">
          Prijava
        </RouterLink>

        <RouterLink to="/register" class="btn secondary">
          Registracija
        </RouterLink>
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
          Portal omogoča pregled raziskav, upravljanje uporabnikov
          ter eksperimentalno povezavo med frontendom in backend API-jem.
        </p>
      </div>

      <div class="card">
        <h2>Tehnologije</h2>
        <ul>
          <li>Vue 3 + Vite (frontend)</li>
          <li>Laravel REST API (backend)</li>
          <li>MySQL / MariaDB</li>
        </ul>
      </div>
    </section>

  </main>
</template>

<style scoped>
/* Glavni layout */
.home {
  display: grid;
  gap: 48px;
  padding: 40px 20px;
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
  font-size: 1.2rem;
  opacity: 0.8;
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
  text-decoration: none;
  font-weight: 600;
  border: 1px solid transparent;
}

.primary {
  background: #646cff;
  color: white;
}

.primary:hover {
  background: #535bf2;
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
</style>