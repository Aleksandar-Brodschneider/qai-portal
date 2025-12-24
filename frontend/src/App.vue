<script setup>
/*
|--------------------------------------------------------------------------
| UVOZI
|--------------------------------------------------------------------------
*/

// Uvozimo computed in ref za reaktivne spremenljivke
import { computed, ref } from 'vue'

// Uvozimo router, da lahko po odjavi preusmerimo uporabnika
import { useRouter } from 'vue-router'

// Inicializiramo router
const router = useRouter()

// Pomožna spremenljivka, ki prisili ponovno izrisovanje (re-render)
// Uporabimo jo ob logout-u, ker localStorage sam po sebi ni reaktiven
const tick = ref(0)

/*
|--------------------------------------------------------------------------
| REAKTIVNI PODATEK: USER
|--------------------------------------------------------------------------
| - bere prijavljenega uporabnika iz localStorage
| - če ni prijave, vrne null
*/
const user = computed(() => {
  // Vsakič, ko se tick spremeni, se computed ponovno izračuna
  tick.value

  try {
    // Poskusimo prebrati uporabnika iz localStorage
    return JSON.parse(localStorage.getItem('qai_user') || 'null')
  } catch {
    // Če pride do napake (npr. pokvarjen JSON), vrnemo null
    return null
  }
})
// Prikazno ime za navbar (fallback, če kaj manjka)
const displayName = computed(() => {
  if (!user.value) return ''
  return user.value.name || user.value.email || 'Uporabnik'
})
/*
|--------------------------------------------------------------------------
| FUNKCIJA: LOGOUT
|--------------------------------------------------------------------------
| - izbriše uporabnika iz localStorage
| - osveži meni
| - preusmeri na Home
*/
const logout = () => {
  // Odstranimo prijavljenega uporabnika
  localStorage.removeItem('qai_user')

  // Prisili ponovno izrisovanje menija
  tick.value++

  // Preusmerimo uporabnika na začetno stran
  router.push('/')
}
</script>

<template>
  <!-- Glavni ovoj aplikacije -->
  <div class="app">

    <!-- ================= NAVBAR ================= -->
    <header class="nav">

      <!-- Ime aplikacije -->
      <div class="brand">
        QAI Portal
      </div>
      <div class="nav-center">
      <span v-if="user" class="user-pill">
      {{ user.name }}
      </span>
      </div>
      <!-- Navigacijski meni -->
      <nav class="links">

        <!-- Vedno vidna povezava -->
        <RouterLink to="/" class="link">
          Home
        </RouterLink>

        <!-- ================= NI PRIJAVLJEN ================= -->
        <!-- Če uporabnik NI prijavljen -->
        <template v-if="!user">

          <!-- Povezava na prijavo -->
          <RouterLink to="/login" class="link">
            Login
          </RouterLink>

          <!-- Povezava na registracijo -->
          <RouterLink to="/register" class="link">
            Register
          </RouterLink>

        </template>

        <!-- ================= PRIJAVLJEN ================= -->
        <!-- Če uporabnik JE prijavljen -->
        <template v-else>

          <!-- ================= SAMO ADMIN ================= -->
          <!-- Če ima uporabnik administratorske pravice -->
          <template v-if="user.is_admin">

            <!-- Admin nadzorna plošča -->
            <RouterLink to="/admin" class="link">
              Admin
            </RouterLink>

            <!-- Upravljanje uporabnikov -->
            <RouterLink to="/admin/users" class="link">
              Users
            </RouterLink>

            <!-- Upravljanje raziskav -->
            <RouterLink to="/research" class="link">
              Research
            </RouterLink>

          </template>

          <!-- Gumb za odjavo -->
          <button
            class="link btnlink"
            type="button"
            @click="logout"
          >
            Logout
          </button>

        </template>
      </nav>
    </header>

    <!-- ================= VSEBINA STRANI ================= -->
    <!-- RouterView prikaže trenutno aktivni View -->
    <main class="content">
      <RouterView />
    </main>

    <!-- ================= FOOTER ================= -->
    
      <footer class="footer">
  <!-- LEVO -->
  <div class="footer-left">
    QAI PORTAL
  </div>

  <!-- SREDINA -->
  <nav class="footer-center">
    <a href="#" class="footer-link">Kontakt</a>
    <span class="sep">•</span>
    <a href="#" class="footer-link">FAQ</a>
    <span class="sep">•</span>
    <a href="#" class="footer-link">Social Media</a>
  </nav>

  <!-- DESNO -->
  <div class="footer-right">
    © {{ new Date().getFullYear() }} QAI Portal Group
  </div>
</footer>

  </div>
</template>

<style scoped>
/*
|--------------------------------------------------------------------------
| OSNOVNI LAYOUT
|--------------------------------------------------------------------------
*/
:global(body) {
  background: #111; /* test */
}
.app {
  max-width: 1000px;                 /* Maksimalna širina aplikacije */
  margin: 0 auto;                    /* Centriranje */
  padding: 16px;                     /* Notranji odmik */
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

/*
|--------------------------------------------------------------------------
| NAVBAR
|--------------------------------------------------------------------------
*/

.nav {
  display: flex;                     /* Flex layout */
  align-items: center;               /* Vertikalna poravnava */
  justify-content: space-between;    /* Razmak med logotipom in menijem */
  gap: 12px;
  padding: 14px 16px;
  border-radius: 14px;
  border: 1px solid rgba(185, 44, 44, 0.12);
  background: rgba(190, 230, 13, 0.5);
}

.brand {
  justify-self: start;
  font-weight: 800;
  font-size: 18px;
  letter-spacing: 0.4px;
}

.links {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;                   /* Omogoča prelom vrstic */
}

/*
|--------------------------------------------------------------------------
| POVEZAVE IN GUMBI
|--------------------------------------------------------------------------
*/

.link {
  text-decoration: none;
  padding: 8px 10px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: rgba(255, 255, 255, 0.04);
  color: inherit;
}

.link:hover {
  background: rgba(255, 255, 255, 0.08);
}

.btnlink {
  cursor: pointer;                   /* Kazalec miške */
}

/*
|--------------------------------------------------------------------------
| GLAVNA VSEBINA
|--------------------------------------------------------------------------
*/

.content {
  margin-top: 16px;
  padding: 16px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: rgba(171, 238, 15, 0.3);
  min-height: 320px;
}

/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.footer {
  margin-top: 24px;
  padding: 14px 16px;
  border-top: 5px solid rgba(243, 14, 14, 0.12);
  display: grid;
  grid-template-columns: auto 1fr auto; /* levo | sredina | desno */
  align-items: center;
  line-height: 1;
  font-size: 13px;
  opacity: 0.8;
}

.footer-left {
  justify-self: start;
}

.footer-center {
  justify-self: center;
  transform: translateX(30px); /* premakne sredino desno */
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.footer-right {
  justify-self: end;
}

.footer-link {
  color: inherit;
  text-decoration: none;
}

.footer-link:hover {
  text-decoration: underline;
}

.sep {
  opacity: 0.5;
}
.nav {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
}

.links {
  justify-self: end;
}

/* sredina */
.nav-center {
  justify-self: center;
}

.user-pill {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.06);
  font-weight: 800;
}
</style>