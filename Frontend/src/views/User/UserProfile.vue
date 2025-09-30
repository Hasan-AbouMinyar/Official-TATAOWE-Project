<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="ml-3 text-gray-600">Loading profile...</span>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-semibold text-red-800 mb-2">Error Loading Profile</h3>
        <p class="text-red-600">{{ error }}</p>
        <button @click="$router.back()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
          Go Back
        </button>
      </div>

      <!-- User Profile -->
      <div v-else-if="user">
        <!-- Back Button -->
        <button
          @click="$router.back()"
          class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back
        </button>

        <!-- Profile Header -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
          <!-- Cover Image -->
          <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600"></div>
          
          <!-- Profile Info -->
          <div class="px-8 pb-8">
            <!-- Avatar -->
            <div class="-mt-16 mb-4">
              <div class="inline-block relative">
                <img
                  v-if="user.photo"
                  :src="user.photo"
                  :alt="user.name"
                  class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover"
                />
                <div
                  v-else
                  class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold"
                >
                  {{ user.name?.charAt(0).toUpperCase() || '?' }}
                </div>
              </div>
            </div>

            <!-- User Info -->
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ user.name }}</h1>
                <p class="text-lg text-gray-600 mb-2">@{{ user.username }}</p>
                
                <!-- Contact Info -->
                <div class="flex flex-wrap gap-4 text-gray-600 mb-4">
                  <div v-if="user.email" class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ user.email }}
                  </div>
                  
                  <div v-if="user.phoneNumber" class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ user.phoneNumber }}
                  </div>
                </div>

                <!-- Stats -->
                <div class="flex gap-6">
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ organizations.length }}</p>
                    <p class="text-sm text-gray-600">Organizations</p>
                  </div>
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ applications.length }}</p>
                    <p class="text-sm text-gray-600">Applications</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Skills Section -->
        <div v-if="user.skills && user.skills.length > 0" class="bg-white rounded-2xl shadow-sm p-8 mb-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Skills</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="skill in user.skills"
              :key="skill.id"
              class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"
            >
              {{ skill.name }}
            </span>
          </div>
        </div>

        <!-- Organizations Section -->
        <div v-if="organizations.length > 0" class="bg-white rounded-2xl shadow-sm p-8 mb-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Organizations</h2>
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <router-link
              v-for="org in organizations"
              :key="org.id"
              :to="`/organization/${org.id}`"
              class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                  {{ org.name?.charAt(0).toUpperCase() || '?' }}
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-gray-900 truncate">{{ org.name }}</h3>
                  <p v-if="org.events_count !== undefined" class="text-sm text-gray-600">
                    {{ org.events_count }} {{ org.events_count === 1 ? 'event' : 'events' }}
                  </p>
                </div>
              </div>
            </router-link>
          </div>
        </div>

        <!-- Applications Section -->
        <div v-if="applications.length > 0" class="bg-white rounded-2xl shadow-sm p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Applications</h2>
          <div class="space-y-4">
            <router-link
              v-for="app in applications"
              :key="app.id"
              :to="`/events/${app.event?.id}`"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow"
            >
              <div class="flex items-center gap-4 flex-1 min-w-0">
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-semibold text-xl flex-shrink-0">
                  {{ app.event?.name?.charAt(0).toUpperCase() || '?' }}
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-gray-900 truncate">{{ app.event?.name }}</h3>
                  <p class="text-sm text-gray-600">{{ app.event?.organization?.name }}</p>
                  <span
                    class="inline-block mt-1 px-2 py-1 text-xs rounded-full"
                    :class="{
                      'bg-yellow-100 text-yellow-800': app.status === 'pending',
                      'bg-green-100 text-green-800': app.status === 'accepted',
                      'bg-red-100 text-red-800': app.status === 'rejected'
                    }"
                  >
                    {{ app.status }}
                  </span>
                </div>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const apiBaseUrl = 'http://localhost:8000/api'

const user = ref(null)
const organizations = ref([])
const applications = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  await loadUser()
})

async function loadUser() {
  try {
    loading.value = true
    const token = localStorage.getItem('auth_token')
    
    // Load user
    const userResponse = await axios.get(`${apiBaseUrl}/users/${route.params.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    user.value = userResponse.data
    
    // Load organizations
    try {
      const orgsResponse = await axios.get(`${apiBaseUrl}/users/${route.params.id}/organizations`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      organizations.value = orgsResponse.data || []
    } catch (err) {
      console.log('No organizations found')
      organizations.value = []
    }
    
    // Load applications
    try {
      const appsResponse = await axios.get(`${apiBaseUrl}/users/${route.params.id}/applications`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      applications.value = (appsResponse.data || []).slice(0, 5)
    } catch (err) {
      console.log('No applications found')
      applications.value = []
    }
    
  } catch (err) {
    console.error('Error loading user:', err)
    error.value = err.response?.data?.message || 'Failed to load user profile'
  } finally {
    loading.value = false
  }
}
</script>
