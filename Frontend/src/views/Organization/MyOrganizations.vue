<template>
  <div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Page Header with Enhanced Design -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">My Organizations</h1>
            <p class="text-gray-600">Manage and view all your organizations</p>
          </div>
          <router-link 
            :to="{ name: 'OrganizationCreate' }" 
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-200 hover:scale-105"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Create New Organization
          </router-link>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm font-medium">Total Organizations</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ organizations.length }}</p>
              </div>
              <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm font-medium">Active Organizations</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ organizations.length }}</p>
              </div>
              <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm font-medium">Last Update</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ getLastUpdateTime() }}</p>
              </div>
              <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="text-center">
          <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto mb-4"></div>
          <p class="text-gray-600 text-lg">Loading organizations...</p>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 shadow-md">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-3 flex-1">
            <p class="text-red-800 font-medium">{{ error }}</p>
          </div>
          <button @click="loadOrganizations" class="ml-3 text-red-600 hover:text-red-800 font-medium underline">
            Retry
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="organizations.length === 0" class="text-center py-20">
        <div class="bg-white rounded-2xl shadow-xl p-12 max-w-2xl mx-auto">
          <div class="bg-blue-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-3">No Organizations Yet</h3>
          <p class="text-gray-600 mb-8">Get started by creating your first organization!</p>
          <router-link 
            :to="{ name: 'OrganizationCreate' }" 
            class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transform transition-all duration-200 hover:scale-105"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Create New Organization
          </router-link>
        </div>
      </div>

      <!-- Organizations Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <OrganizationCard 
          v-for="org in organizations" 
          :key="org.id" 
          :organization="org" 
          @delete="handleDelete"
          @select="handleSelectOrganization"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useOrganizationStore } from '@/stores/organization'
import api from '@/api'
import OrganizationCard from '@/components/OrganizationCard.vue'

const authStore = useAuthStore()
const organizationStore = useOrganizationStore()
const router = useRouter()

// State
const organizations = ref([])
const loading = ref(true)
const error = ref(null)

// Load organizations from API
async function loadOrganizations() {
  try {
    loading.value = true
    error.value = null
    
    const userId = authStore.user?.id
    if (!userId) {
      error.value = 'User not authenticated'
      return
    }
    
    // Fetch user's organizations
    const response = await api.organizations.getUserOrganizations(userId)
    
    // Use logo_url from backend or fallback to constructing URL
    organizations.value = response.data.map(org => ({
      ...org,
      logo: org.logo_url || (org.logo ? `http://localhost:8000/storage/${org.logo}` : null)
    }))
    
  } catch (err) {
    console.error('Error loading organizations:', err)
    error.value = err.response?.data?.message || 'Failed to load organizations. Please try again.'
  } finally {
    loading.value = false
  }
}

// Handle delete organization
async function handleDelete(org) {
  if (!confirm(`Are you sure you want to delete "${org.name}"?`)) {
    return
  }

  try {
    await api.organizations.delete(org.id)
    organizations.value = organizations.value.filter(o => o.id !== org.id)
    
    // Show success message (you can use a toast notification library)
    alert('Organization deleted successfully')
  } catch (err) {
    console.error('Error deleting organization:', err)
    alert(err.response?.data?.message || 'Failed to delete organization. Please try again.')
  }
}

// Handle organization selection
function handleSelectOrganization(org) {
  // Switch to organization mode
  organizationStore.switchToOrganization(org)
  
  // Navigate to organization dashboard
  router.push({ name: 'Dashboard' })
}

// Get last update time
function getLastUpdateTime() {
  if (organizations.value.length === 0) return 'N/A'
  
  const now = new Date()
  const hours = now.getHours()
  const minutes = now.getMinutes()
  const ampm = hours >= 12 ? 'PM' : 'AM'
  const displayHours = hours % 12 || 12
  
  return `${displayHours}:${minutes < 10 ? '0' + minutes : minutes} ${ampm}`
}

// Load organizations on component mount
onMounted(() => {
  loadOrganizations()
})
</script>
