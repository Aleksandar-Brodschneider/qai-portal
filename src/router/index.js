// Uvozimo funkcije za router
import { createRouter, createWebHistory } from 'vue-router'

// Uvozimo strani (views)
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AdminView from '../views/AdminView.vue'
// Dodamo še za raziskave
import ResearchView from '../views/ResearchView.vue'
// Dodamo še za UsersView
import UsersView from '../views/UsersView.vue'


// Definiramo poti (URL-je) v aplikaciji
const routes = [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },
    { path: '/admin', name: 'admin', component: AdminView },
    { path: '/research', name: 'research', component: ResearchView },
    // Dodamo še za usersView
    { path: '/users', name: 'users', component: UsersView },
]

// Ustvarimo router
const router = createRouter({
    history: createWebHistory(), // uporablja normalne URL-je (brez #)
    routes,
})

/*
|--------------------------------------------------------------------------
| ROUTER GUARD (DEV)
|--------------------------------------------------------------------------
| Namen:
| - /admin je dostopen samo, če obstaja 'qai_user' v localStorage
| - in ima is_admin === true
*/
router.beforeEach((to) => {
    // Varujemo samo admin pot
    if (to.path === '/admin') {
        const raw = localStorage.getItem('qai_user')

        // Če ni prijave → login
        if (!raw) {
            return { path: '/login' }
        }

        const user = JSON.parse(raw)

        // Če ni admin → home
        if (!user.is_admin) {
            return { path: '/' }
        }
    }

    // V vseh ostalih primerih nadaljuj
    return true
})

// Router izvozimo NA KONCU
export default router