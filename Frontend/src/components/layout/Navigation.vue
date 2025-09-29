<template>
  <header class="flex items-center justify-between h-20 px-6 bg-white border-b">
    <div class="relative flex items-center">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
          <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </span>
      <input type="text" class="py-2.5 pl-10 pr-4 text-gray-700 placeholder-gray-400 bg-white border border-transparent border-gray-200 rounded-lg sm:w-auto w-36 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40" placeholder="Search" />
    </div>
    <div class="flex items-center">
      <div class="relative">
        <button class="transition-colors duration-300 rounded-lg sm:px-4 sm:py-2 focus:outline-none hover:bg-gray-100" @click="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen.toString()" aria-haspopup="menu">
          <span class="sr-only">User Menu</span>
          <div class="flex items-center md:-mx-2">
            <div class="hidden md:mx-2 md:flex md:flex-col md:items-end md:leading-tight">
              <span class="font-semibold text-sm text-gray-800">{{ user?.name || 'User' }}</span>
              <span class="text-sm text-gray-600">{{ user?.email || '' }}</span>
            </div>
            <div class="flex-shrink-0 w-10 h-10 overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 rounded-full md:mx-2 flex items-center justify-center">
              <img 
                v-if="user?.photo" 
                :src="user.photo" 
                :alt="user.name" 
                class="w-full h-full object-cover"
              />
              <span v-else class="text-white font-semibold text-lg">
                {{ getUserInitials(user?.name) }}
              </span>
            </div>
          </div>
        </button>
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
          <div class="absolute right-0 z-50 w-56 p-2 bg-white border rounded-lg shadow-lg top-16 lg:top-20" v-show="dropdownOpen" role="menu">
            <RouterLink :to="{ name: 'Profile' }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
              </div>
            </RouterLink>
            <RouterLink to="/settings" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
              </div>
            </RouterLink>
            <hr class="my-2 border-gray-200" />
            <button @click="handleLogout" class="w-full text-left px-4 py-2 text-red-600 transition-colors duration-300 rounded-lg hover:bg-red-50" role="menuitem">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
              </div>
            </button>
          </div>
        </transition>
      </div>
      <div v-show="dropdownOpen" class="fixed inset-0 z-30" @click="dropdownOpen = false"></div>
      <button class="relative p-2 mx-3 text-gray-400 transition-colors duration-300 rounded-full hover:bg-gray-100 hover:text-gray-600 focus:bg-gray-100">
        <span class="sr-only">Notifications</span>
        <span class="absolute top-0 right-0 w-2 h-2 mt-1 mr-2 bg-blue-700 rounded-full"></span>
        <span class="absolute top-0 right-0 w-2 h-2 mt-1 mr-2 bg-blue-700 rounded-full animate-ping"></span>
        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useRouter } from 'vue-router'

const dropdownOpen = ref(false)
const authStore = useAuthStore()
const router = useRouter()

const user = computed(() => authStore.user)

function getUserInitials(name) {
  if (!name) return 'U'
  const names = name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

async function handleLogout() {
  try {
    await authStore.logout()
    router.push({ name: 'Login' })
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>
