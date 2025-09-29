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
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navigation from './components/layout/Navigation.vue'
import Sidebar from './components/layout/Sidebar.vue'

const route = useRoute()

// Check if current route is an auth page
const isAuthPage = computed(() => {
    const authRoutes = ['Login', 'Register', 'ForgotPassword', 'ResetPassword']
    return authRoutes.includes(route.name)
})
</script>