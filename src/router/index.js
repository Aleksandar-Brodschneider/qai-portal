// Uvozimo funkcije za router
import { createRouter, createWebHistory } from 'vue-router'

// Uvozimo strani (views) – te bomo ustvarili v naslednjem koraku
import HomeView from '../views/HomeView.vue'

// Definiramo poti (URL-je) v aplikaciji
const routes = [{
    path: '/', // osnovna pot
    name: 'home',
    component: HomeView, // komponenta, ki se prikaže
}, ]

// Ustvarimo router
const router = createRouter({
    history: createWebHistory(), // uporablja normalne URL-je (brez #)
    routes, // zgoraj definirane poti
})

// Router izvozimo, da ga lahko uvozimo v main.js
export default router