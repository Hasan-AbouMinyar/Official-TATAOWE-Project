<template>
    <header class="text-gray-100 bg-gray-800 lg:hidden">
        <div class="container flex items-center justify-between p-4 mx-auto">
            <a href="#" class="text-white" aria-label="Logo">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </a>
            <button @click="isOpen = !isOpen" class="p-2 text-white rounded-lg focus:outline-none hover:bg-gray-700"
                :aria-expanded="isOpen.toString()" aria-controls="sidebar">
                <svg v-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg v-show="isOpen" class="w-6 h-6 transition duration-200 ease-in-out"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </header>

    <div v-cloak :class="isOpen ? 'block' : 'hidden'" @click="isOpen = false"
        class="fixed inset-0 z-20 transition-opacity bg-black opacity-30 lg:hidden"></div>

    <aside id="sidebar" :class="isOpen ? 'translate-x-0 ease-in' : '-translate-x-full ease-out'"
        class="fixed inset-y-0 left-0 z-30 flex flex-col w-[4.5rem] min-h-screen space-y-6 overflow-y-auto text-gray-100 transition duration-200 transform bg-gray-800 lg:translate-x-0 lg:relative lg:inset-0"
        aria-label="Primary">
        <div class="flex flex-col items-center flex-1 space-y-6">
            <a href="#"
                class="flex items-center justify-center w-full p-5 lg:p-0 lg:h-20 font-bold text-white truncate bg-blue-600 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </a>
            <nav class="flex flex-col items-center space-y-6">

                <!-- Dashboard Link -->
                <router-link :to="{ name: 'Dashboard' }"
                    class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white"
                    active-class="p-3 transition-colors duration-300 bg-white rounded-lg">
                    <svg class="w-5 h-5 stroke-blue-100/50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:stroke-blue-700"
                            d="M3 9.75L12 3l9 6.75V20a2 2 0 01-2 2H5a2 2 0 01-2-2V9.75z" fill="#969CBA"
                            fill-opacity="0.2" stroke="#969CBA" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path class="group-hover:stroke-blue-700" d="M9 22V12h6v10" stroke="#969CBA" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </router-link>

                <!-- Profile Link -->
                <router-link :to="{ name: 'Profile' }"
                    class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white"
                    active-class="p-3 transition-colors duration-300 bg-white rounded-lg">
                    <svg class="w-5 h-5 stroke-blue-100/50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:stroke-blue-700"
                            d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"
                            fill="#969CBA" fill-opacity="0.2" stroke="#969CBA" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </router-link>

                <!-- My Applications Link -->
                <router-link :to="{ name: 'MyApplications' }"
                    class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white"
                    active-class="p-3 transition-colors duration-300 bg-white rounded-lg">
                    <svg class="w-5 h-5 stroke-blue-100/50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:stroke-blue-700"
                            d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5M12 12H15M12 16H15M9 12H9.01M9 16H9.01"
                            fill="#969CBA" fill-opacity="0.2" stroke="#969CBA" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </router-link>

                <!-- My Organizations List View -->
                <router-link :to="{ name: 'MyOrganizations' }"
                    class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white"
                    active-class="p-3 transition-colors duration-300 bg-white rounded-lg">
                    <svg class="w-5 h-5 stroke-blue-100/50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:stroke-blue-700"
                            d="M22 18.2222C22 18.6937 21.7893 19.1459 21.4142 19.4793C21.0391 19.8127 20.5304 20 20 20H4C3.46957 20 2.96086 19.8127 2.58579 19.4793C2.21071 19.1459 2 18.6937 2 18.2222V5.77778C2 5.30628 2.21071 4.8541 2.58579 4.5207C2.96086 4.1873 3.46957 4 4 4H9L11 6.66667H20C20.5304 6.66667 21.0391 6.85397 21.4142 7.18737C21.7893 7.52076 22 7.97295 22 8.44444V18.2222Z"
                            fill="#969CBA" fill-opacity="0.2" stroke="#969CBA" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </router-link>

                <a href="#" class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white">
                    <svg class="w-5 h-5 group-hover:stroke-blue-700" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:stroke-blue-700"
                            d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z"
                            fill="#969CBA" fill-opacity="0.2" stroke="#969CBA" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path class="group-hover:stroke-blue-700" d="M22 6L12 13L2 6" stroke="#969CBA" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="#" class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white">
                    <svg class="w-5 h-5 group-hover:stroke-blue-700" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:stroke-blue-700"
                            d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579 4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142 4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142 19.7893 21.0391 20 20.5304 20 20V9L13 2Z"
                            fill="#969CBA" fill-opacity="0.2" stroke="#969CBA" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path class="group-hover:stroke-blue-700" d="M13 2V9H20" stroke="#969CBA" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </nav>
        </div>
        <div class="flex justify-center py-5 border-t border-gray-600">
            <button @click="handleLogout" class="p-2 transition-colors duration-300 rounded-lg group hover:bg-white" title="Logout">
                <svg class="w-5 h-5 opacity-100 group-hover:stroke-red-600" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover:stroke-red-600"
                        d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12M21 12L16 7M21 12H9"
                        stroke="#969CBA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';

const isOpen = ref(false);
const authStore = useAuthStore();

const handleLogout = async () => {
    await authStore.logout();
};
</script>
