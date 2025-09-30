<template>
  <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-lg">
    <header class="flex items-start gap-4">
      <!-- Organization Avatar -->
      <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center overflow-hidden">
        <img 
          v-if="event.organization?.photo" 
          :src="event.organization.photo" 
          :alt="event.organization.name"
          class="w-full h-full object-cover"
        />
        <span v-else class="text-white font-bold text-lg">
          {{ getInitials(event.organization?.name) }}
        </span>
      </div>
      
      <div class="flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <h3 class="text-base font-semibold text-gray-900">{{ event.organization?.name || 'Unknown Organization' }}</h3>
          <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Organization</span>
        </div>
        <p class="mt-0.5 text-xs text-gray-500">{{ formatDate(event.created_at) }} · Public</p>
      </div>
      
      <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
        </svg>
      </button>
    </header>
    
    <div class="mt-4 space-y-3">
      <h4 class="text-lg font-medium text-gray-900">{{ event.name }}</h4>
      <p class="text-sm leading-relaxed text-gray-600 line-clamp-3">{{ event.description || 'No description available.' }}</p>
      
      <!-- Event Details -->
      <div class="space-y-2 text-sm">
        <!-- Date & Time -->
        <div class="flex items-center gap-2 text-gray-600">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>{{ formatEventDate(event.start_time) }} - {{ formatEventDate(event.end_time) }}</span>
        </div>
        
        <!-- Location -->
        <div v-if="event.location" class="flex items-center gap-2 text-gray-600">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span>{{ event.location }}</span>
        </div>

        <!-- Rating -->
        <div v-if="event.total_reviews > 0" class="flex items-center gap-2">
          <div class="flex items-center gap-1">
            <svg v-for="star in 5" :key="star" 
              class="w-4 h-4" 
              :class="star <= event.average_rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'"
              viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <span class="text-xs text-gray-600">{{ event.average_rating }} ({{ event.total_reviews }} reviews)</span>
        </div>
      </div>
      
      <!-- Event Photo or Placeholder -->
      <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
        <img 
          v-if="event.photo" 
          :src="event.photo" 
          :alt="event.name" 
          class="w-full h-48 object-cover" 
        />
        <div v-else class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
          <div class="text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">{{ event.name }}</p>
          </div>
        </div>
      </div>
      
      <!-- Skills Tags -->
      <div v-if="skills.length > 0" class="flex flex-wrap gap-2 pt-1">
        <span 
          v-for="(skill, index) in skills" 
          :key="index"
          :class="getSkillColor(index)"
          class="text-xs font-medium rounded-full px-3 py-1"
        >
          #{{ skill }}
        </span>
      </div>
    </div>
    
    <footer class="mt-5 space-y-3">
      <div class="flex items-center justify-between border-t border-gray-100 pt-4">
        <div class="flex items-center gap-4 text-xs">
          <button 
            @click="showReviews = !showReviews"
            class="flex items-center gap-1.5 px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v6a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <span>{{ event.total_reviews || 0 }} Reviews</span>
          </button>
          <div class="flex items-center gap-1.5 px-2.5 py-1.5 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>{{ event.applications_count || 0 }} Applications</span>
          </div>
        </div>
        
        <!-- Apply Button -->
        <button 
          v-if="!registrationEnded"
          @click="handleApply"
          :disabled="applying || hasApplied"
          :class="{
            'bg-blue-600 hover:bg-blue-500': !hasApplied,
            'bg-gray-400 cursor-not-allowed': hasApplied,
            'opacity-50 cursor-not-allowed': applying
          }"
          class="px-3 py-1.5 text-xs font-medium text-white rounded-md transition-colors"
        >
          {{ applying ? 'Applying...' : hasApplied ? 'Already Applied' : 'Apply Now' }}
        </button>
        <span v-else class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md">
          Registration Closed
        </span>
      </div>

      <!-- Reviews Section -->
      <div v-if="showReviews" class="border-t border-gray-100 pt-4 space-y-4">
        <!-- Add Review Form (if authenticated) -->
        <div v-if="isAuthenticated" class="bg-gray-50 rounded-lg p-4">
          <h5 class="text-sm font-semibold text-gray-900 mb-3">Leave a Review</h5>
          <div class="space-y-3">
            <!-- Star Rating -->
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Rating:</span>
              <div class="flex gap-1">
                <button 
                  v-for="star in 5" 
                  :key="star"
                  @click="newReview.rating = star"
                  type="button"
                  class="focus:outline-none"
                >
                  <svg 
                    class="w-6 h-6 transition-colors" 
                    :class="star <= newReview.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'"
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Comment -->
            <textarea 
              v-model="newReview.comment"
              placeholder="Share your experience..."
              rows="3"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
            
            <!-- Submit Button -->
            <button 
              @click="submitReview"
              :disabled="submittingReview || newReview.rating === 0"
              class="w-full bg-blue-600 text-white text-sm font-medium py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ submittingReview ? 'Submitting...' : 'Submit Review' }}
            </button>
          </div>
        </div>

        <!-- Reviews List -->
        <div v-if="reviews.length > 0" class="space-y-3">
          <div 
            v-for="review in reviews" 
            :key="review.id"
            class="bg-white border border-gray-200 rounded-lg p-3"
          >
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                <img 
                  v-if="review.user?.photo" 
                  :src="review.user.photo" 
                  :alt="review.user.name"
                  class="w-full h-full object-cover"
                />
                <span v-else class="text-white font-bold text-xs">
                  {{ getInitials(review.user?.name) }}
                </span>
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <h6 class="text-sm font-semibold text-gray-900">{{ review.user?.name || 'Anonymous' }}</h6>
                  <div class="flex items-center gap-2">
                    <div class="flex gap-0.5">
                      <svg v-for="star in review.rating" :key="star" 
                        class="w-3 h-3 text-yellow-400 fill-yellow-400"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                    
                    <!-- Review Options Menu (Only for review owner) -->
                    <div v-if="isAuthenticated && authStore.user?.id === review.user_id" class="relative">
                      <button 
                        @click.stop="toggleReviewMenu(review.id)"
                        class="p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors focus:outline-none"
                        :aria-label="`Review options for ${review.user?.name}`"
                      >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                      </button>
                      
                      <!-- Dropdown Menu -->
                      <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                      >
                        <div 
                          v-if="activeReviewMenu === review.id"
                          class="absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-10 overflow-hidden"
                        >
                          <button
                            @click.stop="handleDeleteReview(review)"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Review
                          </button>
                        </div>
                      </transition>
                    </div>
                  </div>
                </div>
                <p v-if="review.comment" class="mt-1 text-xs text-gray-600">{{ review.comment }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ formatDate(review.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>
        <p v-else class="text-sm text-gray-500 text-center py-4">No reviews yet. Be the first to review!</p>
      </div>
    </footer>
  </article>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/api'

const props = defineProps({
  event: {
    type: Object,
    required: true
  }
})

const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)

const showReviews = ref(false)
const reviews = ref([])
const applying = ref(false)
const hasApplied = ref(false)
const submittingReview = ref(false)
const activeReviewMenu = ref(null)

const newReview = ref({
  rating: 0,
  comment: ''
})

const skills = computed(() => {
  if (!props.event.requiredSkills) return []
  return props.event.requiredSkills.split(',').map(s => s.trim()).filter(Boolean)
})

const registrationEnded = computed(() => {
  if (!props.event.end_time) return false
  return new Date() > new Date(props.event.end_time)
})

async function checkIfApplied() {
  if (!isAuthenticated.value) return
  
  try {
    const response = await api.events.checkApplication(props.event.id)
    hasApplied.value = response.data.applied
  } catch (error) {
    console.error('Error checking application:', error)
  }
}

async function loadReviews() {
  try {
    const response = await api.events.getReviews(props.event.id)
    reviews.value = response.data
  } catch (error) {
    console.error('Error loading reviews:', error)
  }
}

async function handleApply() {
  if (!isAuthenticated.value) {
    alert('Please login to apply')
    return
  }

  if (hasApplied.value) return

  const confirmed = confirm('Are you sure you want to apply to this event?')
  if (!confirmed) return

  try {
    applying.value = true
    await api.events.apply(props.event.id)
    hasApplied.value = true
    alert('Application submitted successfully!')
  } catch (error) {
    console.error('Error applying to event:', error)
    alert(error.response?.data?.message || 'Failed to apply. Please try again.')
  } finally {
    applying.value = false
  }
}

async function submitReview() {
  if (!isAuthenticated.value || newReview.value.rating === 0) return

  try {
    submittingReview.value = true
    await api.events.addReview(props.event.id, newReview.value)
    
    // Reload reviews
    await loadReviews()
    
    // Reset form
    newReview.value = { rating: 0, comment: '' }
    
    alert('Review submitted successfully!')
  } catch (error) {
    console.error('Error submitting review:', error)
    alert(error.response?.data?.message || 'Failed to submit review. Please try again.')
  } finally {
    submittingReview.value = false
  }
}

function toggleReviewMenu(reviewId) {
  activeReviewMenu.value = activeReviewMenu.value === reviewId ? null : reviewId
}

async function handleDeleteReview(review) {
  // Close the menu
  activeReviewMenu.value = null
  
  // Show confirmation dialog
  const confirmed = confirm(
    `هل أنت متأكد من حذف تقييمك؟\n\nلا يمكن التراجع عن هذا الإجراء.\n\nAre you sure you want to delete your review?\n\nThis action cannot be undone.`
  )
  
  if (!confirmed) return

  try {
    await api.events.deleteReview(review.id)
    
    // Remove from local reviews list
    reviews.value = reviews.value.filter(r => r.id !== review.id)
    
    alert('✓ Review deleted successfully!\n✓ تم حذف التقييم بنجاح!')
  } catch (error) {
    console.error('Error deleting review:', error)
    alert(error.response?.data?.message || 'Failed to delete review. Please try again.')
  }
}

function getInitials(name) {
  if (!name) return 'O'
  const words = name.split(' ')
  if (words.length >= 2) {
    return (words[0][0] + words[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

function formatDate(dateString) {
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
  
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
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

function getSkillColor(index) {
  const colors = [
    'bg-indigo-50 text-indigo-600',
    'bg-emerald-50 text-emerald-600',
    'bg-rose-50 text-rose-600',
    'bg-amber-50 text-amber-600',
    'bg-purple-50 text-purple-600',
    'bg-pink-50 text-pink-600',
    'bg-cyan-50 text-cyan-600',
    'bg-orange-50 text-orange-600'
  ]
  return colors[index % colors.length]
}

onMounted(() => {
  checkIfApplied()
  if (showReviews.value) {
    loadReviews()
  }
  
  // Close review menu when clicking outside
  document.addEventListener('click', closeReviewMenu)
})

onUnmounted(() => {
  document.removeEventListener('click', closeReviewMenu)
})

function closeReviewMenu() {
  activeReviewMenu.value = null
}

watch(showReviews, (newValue) => {
  if (newValue && reviews.value.length === 0) {
    loadReviews()
  }
})
</script>
