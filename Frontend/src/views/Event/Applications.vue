<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <button @click="goBack" class="p-2 hover:bg-gray-100 rounded-lg transition">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Event Applications</h1>
              <p v-if="event" class="text-sm text-gray-600 mt-1">{{ event.name }}</p>
            </div>
          </div>
          
          <!-- Status Filter -->
          <div class="flex items-center space-x-2">
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

    <!-- Applications Table -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="filteredApplications.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-600 text-lg">No {{ currentFilterLabel }} applications</p>
      </div>

      <div v-else class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Applicant
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Email
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Phone
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Applied Date
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="application in filteredApplications" :key="application.id" class="hover:bg-gray-50 transition">
                <!-- User Info -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <img v-if="application.user.photo" 
                           :src="application.user.photo" 
                           :alt="application.user.name"
                           class="h-10 w-10 rounded-full object-cover">
                      <div v-else class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-medium text-sm">
                          {{ application.user.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="mr-4">
                      <div class="text-sm font-medium text-gray-900">{{ application.user.name }}</div>
                      <div class="text-sm text-gray-500">@{{ application.user.username }}</div>
                    </div>
                  </div>
                </td>

                <!-- Email -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <a :href="`mailto:${application.user.email}`" 
                     class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    {{ application.user.email }}
                  </a>
                </td>

                <!-- Phone -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <a :href="`tel:${application.user.phone}`" 
                     class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    {{ application.user.phone }}
                  </a>
                </td>

                <!-- Application Date -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ formatDate(application.created_at) }}
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(application.status)" 
                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ getStatusText(application.status) }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center space-x-2">
                    <button
                      v-if="application.status === 'pending'"
                      @click="updateStatus(application, 'accepted')"
                      :disabled="updating === application.id"
                      class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50"
                    >
                      Accept
                    </button>
                    <button
                      v-if="application.status === 'pending'"
                      @click="updateStatus(application, 'rejected')"
                      :disabled="updating === application.id"
                      class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition disabled:opacity-50"
                    >
                      Reject
                    </button>
                    <button
                      v-if="application.status !== 'pending'"
                      @click="updateStatus(application, 'pending')"
                      :disabled="updating === application.id"
                      class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition disabled:opacity-50"
                    >
                      Reset to Pending
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Summary Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between text-sm text-gray-600">
            <span>Total Applications: <strong>{{ applications.length }}</strong></span>
            <div class="flex items-center space-x-4">
              <span class="flex items-center">
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                Pending: <strong class="ml-1">{{ getStatusCount('pending') }}</strong>
              </span>
              <span class="flex items-center">
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                Accepted: <strong class="ml-1">{{ getStatusCount('accepted') }}</strong>
              </span>
              <span class="flex items-center">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                Rejected: <strong class="ml-1">{{ getStatusCount('rejected') }}</strong>
              </span>
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
import api from '@/api'

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const error = ref(null)
const applications = ref([])
const event = ref(null)
const currentFilter = ref('all')
const updating = ref(null)

const statusFilters = [
  { value: 'all', label: 'All' },
  { value: 'pending', label: 'Pending' },
  { value: 'accepted', label: 'Accepted' },
  { value: 'rejected', label: 'Rejected' }
]

const filteredApplications = computed(() => {
  if (currentFilter.value === 'all') {
    return applications.value
  }
  return applications.value.filter(app => app.status === currentFilter.value)
})

const currentFilterLabel = computed(() => {
  const filter = statusFilters.find(f => f.value === currentFilter.value)
  return filter ? filter.label.toLowerCase() : ''
})

const fetchApplications = async () => {
  try {
    loading.value = true
    error.value = null
    
    const eventId = route.params.id
    
    // Fetch event details
    const eventResponse = await api.events.getById(eventId)
    event.value = eventResponse.data
    
    // Fetch applications
    const response = await api.get(`/events/${eventId}/applications`)
    applications.value = response.data
  } catch (err) {
    console.error('Error fetching applications:', err)
    error.value = err.response?.data?.message || 'An error occurred while loading applications'
  } finally {
    loading.value = false
  }
}

const updateStatus = async (application, newStatus) => {
  try {
    updating.value = application.id
    
    await api.patch(`/applications/${application.id}/status`, {
      status: newStatus
    })
    
    // Update local state
    application.status = newStatus
    
    // Show success message (you can use a toast notification library)
    alert(`Application ${getStatusText(newStatus).toLowerCase()} successfully`)
  } catch (err) {
    console.error('Error updating status:', err)
    alert('An error occurred while updating application status')
  } finally {
    updating.value = null
  }
}

const getStatusCount = (status) => {
  if (status === 'all') return applications.value.length
  return applications.value.filter(app => app.status === status).length
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    accepted: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    pending: 'Pending',
    accepted: 'Accepted',
    rejected: 'Rejected'
  }
  return texts[status] || status
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

const goBack = () => {
  router.back()
}

onMounted(() => {
  fetchApplications()
})
</script>
