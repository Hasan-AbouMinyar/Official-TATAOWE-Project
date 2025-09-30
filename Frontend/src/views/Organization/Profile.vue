<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex items-center justify-center min-h-screen">
      <div class="text-center">
        <p class="text-red-600 mb-4">{{ error }}</p>
        <button @click="loadOrganization" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Try Again
        </button>
      </div>
    </div>

    <!-- Organization Profile -->
    <div v-else-if="organization" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header Section -->
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
        <!-- Cover Image -->
        <div class="h-48 bg-gradient-to-r from-blue-600 to-purple-600"></div>
        
        <!-- Profile Info -->
        <div class="relative px-6 pb-6">
          <!-- Logo -->
          <div class="absolute -top-16 left-6">
            <div class="w-32 h-32 rounded-xl bg-white shadow-lg overflow-hidden border-4 border-white">
              <img 
                v-if="organization.logo" 
                :src="organization.logo" 
                :alt="organization.name"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <span class="text-white font-bold text-4xl">
                  {{ getInitials(organization.name) }}
                </span>
              </div>
            </div>
          </div>

          <!-- Organization Details -->
          <div class="pt-20">
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ organization.name }}</h1>
                <p v-if="organization.field" class="text-blue-600 font-medium mt-1">{{ organization.field }}</p>
                <p v-if="organization.description" class="text-gray-600 mt-3 max-w-3xl">{{ organization.description }}</p>
              </div>
              
              <!-- Edit Button (only for owner) -->
              <button 
                v-if="isOwner"
                @click="editOrganization"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                Edit Profile
              </button>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
              <!-- Email -->
              <div v-if="organization.email" class="flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-sm">{{ organization.email }}</span>
              </div>

              <!-- Phone -->
              <div v-if="organization.phone" class="flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="text-sm">{{ organization.phone }}</span>
              </div>

              <!-- Location -->
              <div v-if="organization.location" class="flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm">{{ organization.location }}</span>
              </div>

              <!-- Address -->
              <div v-if="organization.address" class="flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-sm">{{ organization.address }}</span>
              </div>

              <!-- Website -->
              <div v-if="organization.website" class="flex items-center gap-3 text-gray-700">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <a :href="organization.website" target="_blank" class="text-sm text-blue-600 hover:underline">
                  {{ organization.website }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm">Total Events</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ events.length }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm">Active Events</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ activeEventsCount }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm">Total Applications</p>
              <p class="text-3xl font-bold text-purple-600 mt-2">{{ totalApplications }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Events Section -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Published Events</h2>
        </div>

        <!-- Loading Events -->
        <div v-if="loadingEvents" class="space-y-4">
          <div v-for="n in 3" :key="n" class="animate-pulse">
            <div class="h-32 bg-gray-200 rounded-lg"></div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="events.length === 0" class="text-center py-12">
          <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <h3 class="mt-4 text-lg font-medium text-gray-900">No events yet</h3>
          <p class="mt-2 text-sm text-gray-500">This organization hasn't published any events.</p>
        </div>

        <!-- Events Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="event in events" 
            :key="event.id"
            class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow"
          >
            <!-- Event Image -->
            <div class="h-48 bg-gradient-to-br from-blue-100 to-purple-100 overflow-hidden">
              <img 
                v-if="event.photo" 
                :src="event.photo" 
                :alt="event.name"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>

            <!-- Event Details -->
            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ event.name }}</h3>
              <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ event.description }}</p>
              
              <!-- Event Info -->
              <div class="space-y-2 text-xs text-gray-500 mb-4">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span>{{ formatEventDate(event.start_time) }}</span>
                </div>
                <div v-if="event.location" class="flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  </svg>
                  <span>{{ event.location }}</span>
                </div>
              </div>

              <!-- Applications Count -->
              <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                  {{ event.applications_count || 0 }} Applications
                </span>
                <span 
                  :class="isEventActive(event) ? 'text-green-600 bg-green-50' : 'text-gray-600 bg-gray-100'"
                  class="text-xs font-medium px-2 py-1 rounded"
                >
                  {{ isEventActive(event) ? 'Active' : 'Ended' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/api'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const organization = ref(null)
const events = ref([])
const loading = ref(false)
const loadingEvents = ref(false)
const error = ref(null)

const isOwner = computed(() => {
  return authStore.user?.id === organization.value?.user_id
})

const activeEventsCount = computed(() => {
  return events.value.filter(event => isEventActive(event)).length
})

const totalApplications = computed(() => {
  return events.value.reduce((sum, event) => sum + (event.applications_count || 0), 0)
})

async function loadOrganization() {
  try {
    loading.value = true
    error.value = null

    const orgId = route.params.id
    
    // Load organization details
    const response = await api.organizations.getById(orgId)
    organization.value = response.data

    // Process logo URL
    if (organization.value.logo_url) {
      organization.value.logo = organization.value.logo_url
    } else if (organization.value.logo) {
      organization.value.logo = `http://localhost:8000/storage/${organization.value.logo}`
    }

    // Load organization events
    await loadEvents()
  } catch (err) {
    console.error('Failed to load organization:', err)
    error.value = 'Failed to load organization details. Please try again.'
  } finally {
    loading.value = false
  }
}

async function loadEvents() {
  try {
    loadingEvents.value = true
    
    // Get all events and filter by organization
    const response = await api.events.getAll()
    events.value = response.data.data.filter(
      event => event.organization_id === parseInt(route.params.id)
    )
  } catch (err) {
    console.error('Failed to load events:', err)
  } finally {
    loadingEvents.value = false
  }
}

function editOrganization() {
  router.push({ name: 'OrganizationEdit', params: { id: organization.value.id } })
}

function isEventActive(event) {
  if (!event.end_time) return true
  return new Date() < new Date(event.end_time)
}

function formatEventDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function getInitials(name) {
  if (!name) return 'O'
  const words = name.split(' ')
  if (words.length >= 2) {
    return (words[0][0] + words[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

onMounted(() => {
  loadOrganization()
})
</script>
