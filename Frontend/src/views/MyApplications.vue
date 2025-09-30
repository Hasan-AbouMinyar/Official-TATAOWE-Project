<template>
  <div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">My Applications</h1>
      <p class="mt-1 text-sm text-gray-600">Track the status of your event applications</p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
      <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeTab = tab.value"
          :class="[
            activeTab === tab.value
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors'
          ]"
        >
          {{ tab.label }}
          <span
            v-if="getCountByStatus(tab.value) > 0"
            :class="[
              activeTab === tab.value
                ? 'bg-blue-100 text-blue-600'
                : 'bg-gray-100 text-gray-600',
              'ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium'
            ]"
          >
            {{ getCountByStatus(tab.value) }}
          </span>
        </button>
      </nav>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-600">
      <p>{{ error }}</p>
      <button @click="loadApplications" class="mt-2 text-sm underline">Try again</button>
    </div>

    <!-- Empty State -->
    <div v-else-if="filteredApplications.length === 0" class="text-center py-12">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-1">No applications found</h3>
      <p class="text-gray-500 mb-4">
        {{ activeTab === 'all' ? 'You haven\'t applied to any events yet.' : `No ${activeTab} applications.` }}
      </p>
      <router-link
        to="/dashboard"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
      >
        Browse Events
      </router-link>
    </div>

    <!-- Applications List -->
    <div v-else class="space-y-4">
      <div
        v-for="application in filteredApplications"
        :key="application.id"
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
      >
        <div class="p-6">
          <div class="flex items-start justify-between">
            <!-- Event Info -->
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <!-- Organization Logo -->
                <Avatar 
                  :photo="application.event?.organization?.logo"
                  :name="application.event?.organization?.name"
                  size="lg"
                  :rounded="false"
                  shadow
                />

                <div class="flex-1">
                  <h3 class="text-lg font-semibold text-gray-900">
                    {{ application.event?.name || 'Event Name' }}
                  </h3>
                  <p class="text-sm text-gray-600">
                    {{ application.event?.organization?.name || 'Organization' }}
                  </p>
                </div>
              </div>

              <!-- Event Details -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span>{{ formatDate(application.event?.start_time) }}</span>
                </div>

                <div class="flex items-center gap-2 text-gray-600">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>{{ application.event?.location || 'Location TBA' }}</span>
                </div>

                <div class="flex items-center gap-2 text-gray-600">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>Applied {{ formatRelativeDate(application.created_at) }}</span>
                </div>
              </div>
            </div>

            <!-- Status Badge -->
            <div class="flex flex-col items-end gap-2 ml-4">
              <span
                :class="getStatusClasses(application.status)"
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
              >
                <span class="w-2 h-2 rounded-full mr-2" :class="getStatusDotColor(application.status)"></span>
                {{ getStatusLabel(application.status) }}
              </span>
              
              <!-- View in Dashboard Button -->
              <router-link
                :to="{ name: 'Dashboard' }"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                title="Go to Dashboard to see event details"
              >
                View in Feed →
              </router-link>
            </div>
          </div>

          <!-- Event Description (if available) -->
          <p v-if="application.event?.description" class="mt-4 text-sm text-gray-600 line-clamp-2">
            {{ application.event.description }}
          </p>

          <!-- Action Buttons based on Status -->
          <div v-if="application.status === 'accepted'" class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2 text-green-600 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Congratulations! Your application has been accepted.</span>
              </div>
            </div>
          </div>

          <div v-else-if="application.status === 'rejected'" class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2 text-red-600 text-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Unfortunately, your application was not accepted this time.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/api'
import Avatar from '@/components/Avatar.vue'
import { getPhotoUrl } from '@/config/api'

const authStore = useAuthStore()

// State
const applications = ref([])
const loading = ref(true)
const error = ref(null)
const activeTab = ref('all')

// Tabs configuration
const tabs = [
  { label: 'All Applications', value: 'all' },
  { label: 'Pending', value: 'pending' },
  { label: 'Accepted', value: 'accepted' },
  { label: 'Rejected', value: 'rejected' }
]

// Computed
const filteredApplications = computed(() => {
  if (activeTab.value === 'all') {
    return applications.value
  }
  return applications.value.filter(app => app.status === activeTab.value)
})

// Methods
function getCountByStatus(status) {
  if (status === 'all') {
    return applications.value.length
  }
  return applications.value.filter(app => app.status === status).length
}

function getStatusClasses(status) {
  const classes = {
    pending: 'bg-yellow-50 text-yellow-700 border border-yellow-200',
    accepted: 'bg-green-50 text-green-700 border border-green-200',
    rejected: 'bg-red-50 text-red-700 border border-red-200'
  }
  return classes[status] || 'bg-gray-50 text-gray-700 border border-gray-200'
}

function getStatusDotColor(status) {
  const colors = {
    pending: 'bg-yellow-500',
    accepted: 'bg-green-500',
    rejected: 'bg-red-500'
  }
  return colors[status] || 'bg-gray-500'
}

function getStatusLabel(status) {
  const labels = {
    pending: 'Pending Review',
    accepted: 'Accepted',
    rejected: 'Rejected'
  }
  return labels[status] || status
}

function formatDate(dateString) {
  if (!dateString) return 'Date TBA'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function formatRelativeDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date
  
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)
  
  if (minutes < 60) return `${minutes} minutes ago`
  if (hours < 24) return `${hours} hours ago`
  if (days < 7) return `${days} days ago`
  if (days < 30) return `${Math.floor(days / 7)} weeks ago`
  if (days < 365) return `${Math.floor(days / 30)} months ago`
  return `${Math.floor(days / 365)} years ago`
}

async function loadApplications() {
  try {
    loading.value = true
    error.value = null
    
    const userId = authStore.user?.id
    if (!userId) {
      error.value = 'User not authenticated'
      return
    }
    
    // Fetch user's applications with event and organization details
    const response = await api.users.getUserApplications(userId)
    applications.value = response.data
    
  } catch (err) {
    console.error('Error loading applications:', err)
    error.value = err.response?.data?.message || 'Failed to load applications. Please try again.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadApplications()
})
</script>
