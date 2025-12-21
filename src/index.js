// Router skrbi za preklapljanje med stranmi (views)
import { createRouter, createWebHistory } from 'vue-router'

// Uvozimo strani (za zdaj prazne komponente)
import LandingView from '../views/LandingView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import AdminView from '../views/AdminView.vue'

const routes = [
    { path: '/', name: 'landing', component: LandingView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },
    { path: '/dashboard', name: 'dashboard', component: DashboardView },
    { path: '/admin', name: 'admin', component: AdminView },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router