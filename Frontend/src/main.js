import { createApp } from 'vue';
import App from './App.vue';
import './assets/app.css';
import router from './router';
// Bootstrapping Vue app with router
createApp(App).use(router).mount('#app');
