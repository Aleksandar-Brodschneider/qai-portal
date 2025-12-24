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
        <nav class="footer-links">
          <a href="#" class="footer-link">Kontakt</a>
          <span class="sep">•</span>
          <a href="#" class="footer-link">FAQ</a>
          <span class="sep">•</span>
          <a href="#" class="footer-link">Social Media</a>
        </nav>
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
  font-weight: 700;                  /* Krepka pisava */
  letter-spacing: 0.3px;             /* Razmik med črkami */
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
  padding-top: 12px;
  border-top: 1px solid rgba(192, 41, 41, 0.12);
  text-align: center;
  font-size: 13px;
  opacity: 0.75;
}

.footer-links {
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
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
</style>