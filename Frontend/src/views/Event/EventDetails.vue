<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="ml-3 text-gray-600">Loading...</span>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <h3 class="text-lg font-semibold text-red-800 mb-2">Error</h3>
        <p class="text-red-600">{{ error }}</p>
        <button @click="$router.back()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
          Go Back
        </button>
      </div>

      <!-- Event Content -->
      <div v-else-if="event" class="space-y-6">
        <!-- Back Button -->
        <button @click="$router.back()" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back
        </button>

        <!-- Event Card -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
          <!-- Photo -->
          <div class="h-96 relative">
            <img
              v-if="event.photo"
              :src="event.photo"
              :alt="event.name"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-6xl font-bold"
            >
              {{ event.name?.charAt(0).toUpperCase() || '?' }}
            </div>
          </div>

          <!-- Content -->
          <div class="p-8">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ event.name }}</h1>
                <router-link
                  v-if="event.organization"
                  :to="`/organization/${event.organization.id}`"
                  class="inline-flex items-center text-lg text-gray-600 hover:text-blue-600"
                >
                  <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  {{ event.organization.name }}
                </router-link>
              </div>

              <!-- Actions -->
              <div v-if="!hasApplied" class="flex gap-3">
                <button
                  @click="applyToEvent"
                  :disabled="applying"
                  class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
                >
                  {{ applying ? 'Applying...' : 'Apply Now' }}
                </button>
              </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
              <p class="text-gray-700 whitespace-pre-wrap">{{ event.description }}</p>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <!-- Date & Time -->
              <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div>
                  <p class="font-semibold text-gray-900">Date & Time</p>
                  <p class="text-gray-600">{{ formatDate(event.start_time) }}</p>
                  <p v-if="event.end_time" class="text-gray-600 text-sm">to {{ formatDate(event.end_time) }}</p>
                </div>
              </div>

              <!-- Location -->
              <div v-if="event.location" class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                  <p class="font-semibold text-gray-900">Location</p>
                  <p class="text-gray-600">{{ event.location }}</p>
                </div>
              </div>

              <!-- Applications -->
              <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div>
                  <p class="font-semibold text-gray-900">Volunteers</p>
                  <p class="text-gray-600">{{ event.applications_count || 0 }} applications</p>
                </div>
              </div>
            </div>

            <!-- Skills -->
            <div v-if="skillsArray.length > 0" class="mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-3">Required Skills</h2>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="(skill, index) in skillsArray"
                  :key="index"
                  class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"
                >
                  {{ skill }}
                </span>
              </div>
            </div>

            <!-- Reviews -->
            <div v-if="reviews.length > 0" id="reviews" class="mt-8">
              <h2 class="text-2xl font-semibold text-gray-900 mb-4">Reviews</h2>
              <div class="space-y-4">
                <div
                  v-for="review in reviews"
                  :key="review.id"
                  class="border border-gray-200 rounded-lg p-4"
                >
                  <div class="flex items-center justify-between mb-2">
                    <p class="font-semibold text-gray-900">{{ review.user?.name || 'Anonymous' }}</p>
                    <div class="flex">
                      <svg
                        v-for="star in 5"
                        :key="star"
                        class="w-5 h-5"
                        :class="star <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                  </div>
                  <p v-if="review.comment" class="text-gray-700">{{ review.comment }}</p>
                  <p class="text-xs text-gray-400 mt-2">{{ formatDate(review.created_at) }}</p>
                </div>
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

const skillsArray = computed(() => {
  if (!event.value?.requiredSkills) return []
  return event.value.requiredSkills.split(',').map(s => s.trim()).filter(s => s)
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
    error.value = err.response?.data?.message || 'Failed to load event'
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
    reviews.value = response.data || []
  } catch (err) {
    console.error('Error loading reviews:', err)
    reviews.value = []
  }
}

async function checkApplication() {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await axios.get(`${apiBaseUrl}/events/${route.params.id}/check-application`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    hasApplied.value = response.data.hasApplied || false
  } catch (err) {
    console.error('Error checking application:', err)
    hasApplied.value = false
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
    await loadEvent()
  } catch (err) {
    console.error('Error applying:', err)
    error.value = err.response?.data?.message || 'Failed to apply'
  } finally {
    applying.value = false
  }
}

function formatDate(date) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
