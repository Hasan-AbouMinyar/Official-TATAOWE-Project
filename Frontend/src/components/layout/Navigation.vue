<template>
  <header class="flex items-center justify-between h-20 px-6 bg-white border-b">
    <!-- Organization Mode Banner -->
    <div v-if="isOrganizationMode" class="absolute top-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-1 text-sm font-medium flex items-center justify-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
      </svg>
      <span>Organization Mode: {{ organizationName }}</span>
    </div>
    
    <div class="relative flex items-center" :class="{ 'mt-6': isOrganizationMode }">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
          <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </span>
      <input type="text" class="py-2.5 pl-10 pr-4 text-gray-700 placeholder-gray-400 bg-white border border-transparent border-gray-200 rounded-lg sm:w-auto w-36 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40" placeholder="Search" />
    </div>
    <div class="flex items-center" :class="{ 'mt-6': isOrganizationMode }">
      <div class="relative">
        <button class="transition-colors duration-300 rounded-lg sm:px-4 sm:py-2 focus:outline-none hover:bg-gray-100" @click="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen.toString()" aria-haspopup="menu">
          <span class="sr-only">User Menu</span>
          <div class="flex items-center md:-mx-2">
            <div class="hidden md:mx-2 md:flex md:flex-col md:items-end md:leading-tight">
              <span class="font-semibold text-sm text-gray-800">
                {{ isOrganizationMode ? organizationName : (user?.name || 'User') }}
              </span>
              <span class="text-sm text-gray-600">
                {{ isOrganizationMode ? 'Organization Account' : (user?.email || '') }}
              </span>
            </div>
            <div class="flex-shrink-0 w-10 h-10 overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 rounded-full md:mx-2 flex items-center justify-center">
              <img 
                v-if="isOrganizationMode && activeOrganization?.logo" 
                :src="activeOrganization.logo" 
                :alt="organizationName" 
                class="w-full h-full object-cover"
              />
              <img 
                v-else-if="!isOrganizationMode && user?.photo" 
                :src="user.photo" 
                :alt="user.name" 
                class="w-full h-full object-cover"
              />
              <span v-else class="text-white font-semibold text-lg">
                {{ getUserInitials(isOrganizationMode ? organizationName : user?.name) }}
              </span>
            </div>
          </div>
        </button>
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
          <div class="absolute right-0 z-50 w-56 p-2 bg-white border rounded-lg shadow-lg top-16 lg:top-20" v-show="dropdownOpen" role="menu">
            <!-- Organization Mode Menu -->
            <template v-if="isOrganizationMode">
              <RouterLink :to="{ name: 'Dashboard' }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  Organization Dashboard
                </div>
              </RouterLink>
              <RouterLink :to="{ name: 'EventCreate' }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  Create Event
                </div>
              </RouterLink>
              <RouterLink :to="{ name: 'OrganizationProfile', params: { id: organizationId } }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  Organization Profile
                </div>
              </RouterLink>
              <RouterLink :to="{ name: 'OrganizationEdit', params: { id: organizationId } }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit Organization
                </div>
              </RouterLink>
              <RouterLink :to="{ name: 'MyOrganizations' }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5C4.46957 7 3.96086 7.21071 3.58579 7.58579C3.21071 7.96086 3 8.46957 3 9V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H14C14.5304 20 15.0391 19.7893 15.4142 19.4142C15.7893 19.0391 16 18.5304 16 18V15M16 5L21 10M21 10L16 15M21 10H9" />
                  </svg>
                  Switch Organization
                </div>
              </RouterLink>
              <hr class="my-2 border-gray-200" />
              <button @click="handleBackToPersonal" class="w-full text-left px-4 py-2 text-blue-600 transition-colors duration-300 rounded-lg hover:bg-blue-50" role="menuitem">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                  </svg>
                  Back to Personal Account
                </div>
              </button>
            </template>
            
            <!-- Personal Account Menu -->
            <template v-else>
              <RouterLink :to="{ name: 'Profile' }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
              </div>
            </RouterLink>
            <RouterLink :to="{ name: 'MyApplications' }" class="block px-4 py-2 text-gray-800 transition-colors duration-300 rounded-lg hover:bg-gray-100" role="menuitem">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                My Applications
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
          </template>
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
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useOrganizationStore } from '../../stores/organization'
import { useRouter } from 'vue-router'

const dropdownOpen = ref(false)
const authStore = useAuthStore()
const organizationStore = useOrganizationStore()
const router = useRouter()

const user = computed(() => authStore.user)
const isOrganizationMode = computed(() => organizationStore.isOrganizationMode)
const organizationName = computed(() => organizationStore.organizationName)
const organizationId = computed(() => organizationStore.organizationId)
const activeOrganization = computed(() => organizationStore.activeOrganization)

onMounted(() => {
  // Restore organization mode if it was active
  organizationStore.restoreOrganizationMode()
})

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
    organizationStore.clearOrganizationData()
    router.push({ name: 'Login' })
  } catch (error) {
    console.error('Logout failed:', error)
  }
}

function handleBackToPersonal() {
  organizationStore.switchToPersonalAccount()
  dropdownOpen.value = false
  router.push({ name: 'Dashboard' })
}
</script>
