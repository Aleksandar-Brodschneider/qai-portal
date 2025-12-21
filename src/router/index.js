// Uvozimo funkcije za router
import { createRouter, createWebHistory } from 'vue-router'

// Uvozimo strani (views) – te bomo ustvarili v naslednjem koraku
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AdminView from '../views/AdminView.vue'

// Definiramo poti (URL-je) v aplikaciji
const routes = [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: RegisterView },
    { path: '/admin', name: 'admin', component: AdminView },
]

// Ustvarimo router
const router = createRouter({
    history: createWebHistory(), // uporablja normalne URL-je (brez #)
    routes, // zgoraj definirane poti
})

// Router izvozimo, da ga lahko uvozimo v main.js
export default router