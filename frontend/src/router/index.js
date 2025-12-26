// Uvozimo funkcije za router
import { createRouter, createWebHistory } from 'vue-router'

// Uvozimo strani (views)
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AdminView from '../views/AdminView.vue'
import ResearchView from '../views/ResearchView.vue'
import UsersView from '../views/UsersView.vue'
import ResearchDetailView from '../views/ResearchDetailView.vue' // DODAJ


// Definiramo poti (URL-je) v aplikaciji
const routes = [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },
    { path: '/research/:id', name: 'research-detail', component: ResearchDetailView },

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
    const authOnlyPrefixes = ['/research'] // vsi prijavljeni
    const adminOnlyPrefixes = ['/admin'] // samo admin

    const raw = localStorage.getItem('qai_user')
    let user = null
    try { user = raw ? JSON.parse(raw) : null } catch { user = null }

    // auth-only
    if (authOnlyPrefixes.some((p) => to.path.startsWith(p))) {
        if (!user) return { path: '/login' }
    }

    // admin-only
    if (adminOnlyPrefixes.some((p) => to.path.startsWith(p))) {
        if (!user) return { path: '/login' }
        if (!user.is_admin) return { path: '/' }
    }

    return true
})



export default router