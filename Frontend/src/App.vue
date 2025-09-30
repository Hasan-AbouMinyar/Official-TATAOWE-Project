<template>
    <div class="relative min-h-screen lg:flex">
        <!-- Show Sidebar only for authenticated routes -->
        <Sidebar v-if="!isAuthPage" />
        
        <main 
            id="content" 
            :class="[
                'flex-1 pb-12 overflow-y-auto',
                isAuthPage ? 'bg-gray-50' : 'bg-gray-100 lg:h-screen'
            ]"
        >
            <!-- Show Navigation only for authenticated routes -->
            <Navigation v-if="!isAuthPage" />
            
            <router-view />
        </main>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { useNotificationStore } from './stores/notification'
import Navigation from './components/layout/Navigation.vue'
import Sidebar from './components/layout/Sidebar.vue'

const route = useRoute()
const authStore = useAuthStore()
const notificationStore = useNotificationStore()

// Check if current route is an auth page
const isAuthPage = computed(() => {
    const authRoutes = ['Login', 'Register', 'ForgotPassword', 'ResetPassword']
    return authRoutes.includes(route.name)
})

// Start polling for notifications when component mounts
onMounted(() => {
    if (authStore.isAuthenticated) {
        notificationStore.startPolling()
    }
})

// Stop polling when component unmounts
onUnmounted(() => {
    notificationStore.stopPolling()
})
</script>