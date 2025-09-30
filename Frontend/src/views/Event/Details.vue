<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="ml-3 text-gray-600">Loading event details...</span>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-semibold text-red-800 mb-2">Error Loading Event</h3>
        <p class="text-red-600">{{ error }}</p>
        <button @click="$router.back()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
          Go Back
        </button>
      </div>

      <!-- Event Details -->
      <div v-else-if="event">
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

        <!-- Event Header with Photo -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
          <!-- Event Photo -->
          <div class="h-96 bg-gradient-to-br from-purple-500 to-pink-600 relative">
            <img
              v-if="event.photo"
              :src="event.photo"
              :alt="event.name"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-white text-6xl font-bold"
            >
              {{ event.name.charAt(0).toUpperCase() }}
            </div>
          </div>

          <!-- Event Info -->
          <div class="p-8">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ event.name }}</h1>
                
                <!-- Organization -->
                <router-link
                  v-if="event.organization"
                  :to="`/organization/${event.organization.id}`"
                  class="inline-flex items-center text-lg text-gray-600 hover:text-blue-600 transition-colors"
                >
                  <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  {{ event.organization.name }}
                </router-link>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-3">
                <button
                  v-if="canEdit"
                  @click="$router.push(`/events/${event.id}/edit`)"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit
                </button>

                <button
                  v-if="!canEdit && !hasApplied"
                  @click="applyToEvent"
                  :disabled="applying"
                  class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50"
                >
                  {{ applying ? 'Applying...' : 'Apply Now' }}
                </button>

                <span
                  v-if="hasApplied"
                  class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg"
                >
                  Already Applied
                </span>
              </div>
            </div>

            <!-- Event Meta Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
              <!-- Date & Time -->
              <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div>
                  <p class="text-sm text-gray-600 font-medium">Date & Time</p>
                  <p class="text-gray-900">{{ formatDate(event.start_time) }}</p>
                  <p v-if="event.end_time" class="text-sm text-gray-600">to {{ formatDate(event.end_time) }}</p>
                </div>
              </div>

              <!-- Location -->
              <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                  <p class="text-sm text-gray-600 font-medium">Location</p>
                  <p class="text-gray-900">{{ event.location || 'Not specified' }}</p>
                </div>
              </div>

              <!-- Applications -->
              <div class="flex items-start gap-3 p-4 bg-purple-50 rounded-lg">
                <svg class="w-6 h-6 text-purple-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <div>
                  <p class="text-sm text-gray-600 font-medium">Volunteers</p>
                  <p class="text-gray-900">{{ event.applications_count || 0 }} applied</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
          <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ event.description }}</p>
        </div>

        <!-- Required Skills -->
        <div v-if="event.requiredSkills" class="bg-white rounded-2xl shadow-sm p-8 mb-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Required Skills</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="skill in skillsArray"
              :key="skill"
              class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"
            >
              {{ skill }}
            </span>
          </div>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Reviews</h2>
          
          <!-- Reviews will be loaded here -->
          <div v-if="reviews.length === 0" class="text-center py-8 text-gray-500">
            No reviews yet
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="review in reviews"
              :key="review.id"
              class="border border-gray-200 rounded-lg p-4"
            >
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ review.user?.name?.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">{{ review.user?.name }}</p>
                    <div class="flex items-center gap-1">
                      <span v-for="i in 5" :key="i" class="text-yellow-400">
                        {{ i <= review.rating ? '★' : '☆' }}
                      </span>
                    </div>
                  </div>
                </div>
                <span class="text-sm text-gray-500">{{ formatDate(review.created_at) }}</span>
              </div>
              <p v-if="review.comment" class="text-gray-700">{{ review.comment }}</p>
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
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const apiBaseUrl = 'http://localhost:8000/api'

const event = ref(null)
const reviews = ref([])
const loading = ref(true)
const error = ref(null)
const applying = ref(false)
const hasApplied = ref(false)

const canEdit = computed(() => {
  if (!event.value || !authStore.user) return false
  return event.value.organization?.user_id === authStore.user.id
})

const skillsArray = computed(() => {
  if (!event.value?.requiredSkills) return []
  return event.value.requiredSkills.split(',').map(s => s.trim())
})

onMounted(async () => {
  await loadEvent()
  await loadReviews()
  await checkApplication()
})

async function loadEvent() {
  try {
    loading.value = true
    const token = localStorage.getItem('auth_token')
    const response = await axios.get(`${apiBaseUrl}/events/${route.params.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    event.value = response.data
  } catch (err) {
    console.error('Error loading event:', err)
    error.value = err.response?.data?.message || 'Failed to load event details'
  } finally {
    loading.value = false
  }
}

async function loadReviews() {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await axios.get(`${apiBaseUrl}/events/${route.params.id}/reviews`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    reviews.value = response.data
  } catch (err) {
    console.error('Error loading reviews:', err)
  }
}

async function checkApplication() {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await axios.get(`${apiBaseUrl}/events/${route.params.id}/check-application`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    hasApplied.value = response.data.hasApplied
  } catch (err) {
    console.error('Error checking application:', err)
  }
}

async function applyToEvent() {
  try {
    applying.value = true
    const token = localStorage.getItem('auth_token')
    await axios.post(`${apiBaseUrl}/events/${route.params.id}/apply`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    })
    hasApplied.value = true
    // Reload event to update applications count
    await loadEvent()
  } catch (err) {
    console.error('Error applying to event:', err)
    error.value = err.response?.data?.message || 'Failed to apply to event'
  } finally {
    applying.value = false
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
