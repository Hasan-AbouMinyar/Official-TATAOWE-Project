<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">All Applications</h1>
            <p v-if="organizationStore.organizationName" class="text-sm text-gray-600 mt-1">
              {{ organizationStore.organizationName }}
            </p>
          </div>
          
          <!-- Status Filter -->
          <div class="flex items-center space-x-2">
            <button
              @click="fetchOrganizationApplications"
              :disabled="loading"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition disabled:opacity-50 flex items-center gap-2 mr-4"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              Refresh
            </button>
            <button
              v-for="status in statusFilters"
              :key="status.value"
              @click="currentFilter = status.value"
              :class="[
                'px-4 py-2 rounded-lg text-sm font-medium transition',
                currentFilter === status.value
                  ? 'bg-blue-600 text-white'
                  : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
              ]"
            >
              {{ status.label }}
              <span class="ml-1 text-xs">
                ({{ getStatusCount(status.value) }})
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600">Loading applications...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <svg class="w-12 h-12 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-red-800 font-medium">{{ error }}</p>
      </div>
    </div>

    <!-- Events with Applications -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="eventsWithApplications.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-600 text-lg">No events with applications currently</p>
      </div>

      <!-- Events Grid -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div 
          v-for="event in eventsWithApplications" 
          :key="event.id"
          class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition"
        >
          <!-- Event Header -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ event.name }}</h3>
                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ event.description }}</p>
                
                <!-- Event Meta -->
                <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                  <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDate(event.start_time) }}
                  </div>
                  <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    {{ getTotalApplications(event) }} Applications
                  </div>
                </div>
              </div>
              
              <!-- Event Photo -->
              <div class="w-20 h-20 ml-4 rounded-lg overflow-hidden flex-shrink-0 shadow-md">
                <img 
                  v-if="event.photo" 
                  :src="getPhotoUrl(event.photo)" 
                  :alt="event.name"
                  class="w-full h-full object-cover"
                  @error="(e) => e.target.style.display = 'none'"
                />
                <div v-else class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Applications Stats -->
          <div class="p-6">
            <div class="grid grid-cols-3 gap-4 mb-4">
              <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <p class="text-2xl font-bold text-yellow-600">
                  {{ getEventStatusCount(event, 'pending') }}
                </p>
                <p class="text-xs text-gray-600 mt-1">Pending</p>
              </div>
              <div class="text-center p-3 bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">
                  {{ getEventStatusCount(event, 'accepted') }}
                </p>
                <p class="text-xs text-gray-600 mt-1">Accepted</p>
              </div>
              <div class="text-center p-3 bg-red-50 rounded-lg">
                <p class="text-2xl font-bold text-red-600">
                  {{ getEventStatusCount(event, 'rejected') }}
                </p>
                <p class="text-xs text-gray-600 mt-1">Rejected</p>
              </div>
            </div>

            <!-- View Details Button -->
            <button
              @click="viewEventApplications(event.id)"
              class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition font-medium text-sm flex items-center justify-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              View Application Details
            </button>
          </div>
        </div>
      </div>

      <!-- Summary Stats -->
      <div v-if="eventsWithApplications.length > 0" class="mt-8 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Overall Statistics</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-3xl font-bold text-blue-600">{{ eventsWithApplications.length }}</p>
            <p class="text-sm text-gray-600 mt-1">Active Events</p>
          </div>
          <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <p class="text-3xl font-bold text-yellow-600">{{ getStatusCount('pending') }}</p>
            <p class="text-sm text-gray-600 mt-1">Pending</p>
          </div>
          <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-3xl font-bold text-green-600">{{ getStatusCount('accepted') }}</p>
            <p class="text-sm text-gray-600 mt-1">Accepted</p>
          </div>
          <div class="text-center p-4 bg-red-50 rounded-lg">
            <p class="text-3xl font-bold text-red-600">{{ getStatusCount('rejected') }}</p>
            <p class="text-sm text-gray-600 mt-1">Rejected</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useOrganizationStore } from '@/stores/organization'
import api from '@/api'
import Avatar from '@/components/Avatar.vue'
import { getPhotoUrl } from '@/config/api'

const router = useRouter()
const organizationStore = useOrganizationStore()

const loading = ref(true)
const error = ref(null)
const events = ref([])
const currentFilter = ref('all')

const statusFilters = [
  { value: 'all', label: 'All' },
  { value: 'pending', label: 'Pending' },
  { value: 'accepted', label: 'Accepted' },
  { value: 'rejected', label: 'Rejected' }
]

const eventsWithApplications = computed(() => {
  if (!events.value) return []
  return events.value.filter(event => event.applications && event.applications.length > 0)
})

const fetchOrganizationApplications = async () => {
  try {
    loading.value = true
    error.value = null
    
    const orgId = organizationStore.organizationId
    
    if (!orgId) {
      error.value = 'Please select an organization first'
      return
    }
    
    // Fetch organization events
    const response = await api.organizations.getEvents(orgId)
    
    // For each event, fetch its applications
    const eventsWithApps = await Promise.all(
      response.data.map(async (event) => {
        try {
          const appsResponse = await api.get(`/events/${event.id}/applications`)
          return {
            ...event,
            applications: appsResponse.data || []
          }
        } catch (err) {
          console.error(`Error fetching applications for event ${event.id}:`, err)
          return {
            ...event,
            applications: []
          }
        }
      })
    )
    
    events.value = eventsWithApps
  } catch (err) {
    console.error('Error fetching organization applications:', err)
    error.value = err.response?.data?.message || 'An error occurred while loading applications'
  } finally {
    loading.value = false
  }
}

const getTotalApplications = (event) => {
  return event.applications?.length || 0
}

const getEventStatusCount = (event, status) => {
  if (!event.applications) return 0
  return event.applications.filter(app => app.status === status).length
}

const getStatusCount = (status) => {
  if (status === 'all') {
    return events.value.reduce((total, event) => total + getTotalApplications(event), 0)
  }
  return events.value.reduce((total, event) => {
    return total + (event.applications?.filter(app => app.status === status).length || 0)
  }, 0)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date)
}

const viewEventApplications = (eventId) => {
  router.push({ name: 'EventApplications', params: { id: eventId } })
}

onMounted(() => {
  fetchOrganizationApplications()
})

// Watch for organization changes and refresh data
watch(() => organizationStore.organizationId, (newOrgId, oldOrgId) => {
  if (newOrgId && newOrgId !== oldOrgId) {
    fetchOrganizationApplications()
  }
})
</script>
