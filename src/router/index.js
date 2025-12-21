// Uvozimo funkcije za router
import { createRouter, createWebHistory } from 'vue-router'

// Uvozimo strani (views)
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AdminView from '../views/AdminView.vue'
import ResearchView from '../views/ResearchView.vue'
import UsersView from '../views/UsersView.vue'

// Definiramo poti (URL-je) v aplikaciji
const routes = [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },

    // Admin-only
    { path: '/admin', name: 'admin', component: AdminView },
    { path: '/admin/users', name: 'admin-users', component: UsersView },
    { path: '/research', name: 'research', component: ResearchView },
]

// Ustvarimo router
const router = createRouter({
    history: createWebHistory(),
    routes,
})

// ROUTER GUARD (DEV)
router.beforeEach((to) => {
    const adminOnlyPrefixes = ['/admin', '/research']

    // Varujemo admin-only poti
    if (adminOnlyPrefixes.some((p) => to.path.startsWith(p))) {
        const raw = localStorage.getItem('qai_user')

        // Če ni prijave → login
        if (!raw) return { path: '/login' }

        let user = null
        try { user = JSON.parse(raw) } catch { user = null }

        // če user ne obstaja ALI ni admin → domov
        if (!user || !user.is_admin) return { path: '/' }
    }

    return true
})

export default router