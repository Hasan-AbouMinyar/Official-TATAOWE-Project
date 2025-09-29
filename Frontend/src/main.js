import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import './assets/app.css'
import router from './router'
import { useAuthStore } from './stores/auth'

// Create Pinia instance
const pinia = createPinia()

// Create Vue app
const app = createApp(App)

// Use Pinia and Router
app.use(pinia)
app.use(router)

// Initialize auth state from localStorage
const authStore = useAuthStore()
authStore.initializeAuth()

// Mount app
app.mount('#app')
