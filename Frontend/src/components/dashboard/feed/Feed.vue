<template>
  <div class="mt-6 px-6 2xl:px-10">
    <div class="mx-auto flex max-w-7xl gap-8">
      <div class="flex-1 min-w-0">
        <div class="mb-6 rounded-2xl border border-dashed border-gray-300 bg-white/60 p-5 shadow-sm">
          <p class="text-sm text-gray-500">Post composer will go here...</p>
        </div>
        
        <!-- Loading State -->
        <div v-if="loading" class="space-y-6">
          <div v-for="n in 3" :key="n" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm animate-pulse">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full bg-gray-200"></div>
              <div class="flex-1 space-y-3">
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                <div class="h-20 bg-gray-200 rounded"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="rounded-2xl border border-red-200 bg-red-50 p-6 text-center">
          <p class="text-red-600">{{ error }}</p>
          <button @click="fetchEvents" class="mt-3 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
            Try Again
          </button>
        </div>

        <!-- Empty State -->
        <div v-else-if="events.length === 0" class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <h3 class="mt-4 text-lg font-medium text-gray-900">No events found</h3>
          <p class="mt-2 text-sm text-gray-500">There are no events available at the moment.</p>
        </div>

        <!-- Events List -->
        <div v-else class="space-y-6">
          <Card 
            v-for="event in events" 
            :key="event.id"
            :event="event"
          />
        </div>
        
        <!-- Load More Button -->
        <div v-if="hasMorePages" class="mt-8 flex justify-center">
          <button 
            @click="loadMore" 
            :disabled="loadingMore"
            class="rounded-full bg-gray-200 px-5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loadingMore ? 'Loading...' : 'Load more' }}
          </button>
        </div>
      </div>
      <RightRail />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Card from './Card.vue'
import RightRail from './RightRail.vue'
import api from '@/api'

const events = ref([])
const loading = ref(false)
const loadingMore = ref(false)
const error = ref(null)
const currentPage = ref(1)
const hasMorePages = ref(false)

async function fetchEvents(page = 1) {
  try {
    if (page === 1) {
      loading.value = true
    } else {
      loadingMore.value = true
    }
    error.value = null

    const response = await api.events.getAll({ page })
    
    if (page === 1) {
      events.value = response.data.data
    } else {
      events.value = [...events.value, ...response.data.data]
    }
    
    currentPage.value = response.data.current_page
    hasMorePages.value = response.data.current_page < response.data.last_page
  } catch (err) {
    console.error('Failed to fetch events:', err)
    error.value = 'Failed to load events. Please try again.'
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

function loadMore() {
  fetchEvents(currentPage.value + 1)
}

onMounted(() => {
  fetchEvents()
})
</script>
