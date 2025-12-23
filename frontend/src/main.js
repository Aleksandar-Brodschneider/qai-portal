import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

// uvozimo router (datoteko bomo naredili v /src/router/index.js)
import router from './router'

// ustvarimo app
const app = createApp(App)

// dodamo router
app.use(router)

// šele zdaj mount
app.mount('#app')