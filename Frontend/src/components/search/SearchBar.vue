<template>
  <div class="relative flex-1 max-w-2xl mx-4" ref="searchContainer">
    <!-- Search Input -->
    <div class="relative">
      <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      
      <input
        v-model="searchQuery"
        @input="onInput"
        @focus="showSuggestions = true"
        @keydown.enter="performSearch"
        @keydown.down.prevent="navigateSuggestions(1)"
        @keydown.up.prevent="navigateSuggestions(-1)"
        @keydown.esc="closeSuggestions"
        type="text"
        placeholder="Search for users, organizations, or events..."
        class="w-full py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />

      <!-- Clear Button -->
      <button
        v-if="searchQuery"
        @click="clearSearch"
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Suggestions Dropdown -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div
        v-if="showSuggestions && (suggestions.length > 0 || searchQuery.length >= 2)"
        class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl max-h-96 overflow-auto"
      >
        <!-- Loading -->
        <div v-if="loading" class="p-4 text-center text-gray-500">
          <svg class="inline w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="ml-2">Searching...</span>
        </div>

        <!-- Suggestions List -->
        <div v-else-if="suggestions.length > 0">
          <router-link
            v-for="(suggestion, index) in suggestions"
            :key="`${suggestion.type}-${suggestion.id}`"
            :to="suggestion.url"
            @click="closeSuggestions"
            class="flex items-center p-3 hover:bg-gray-50 transition-colors"
            :class="{ 'bg-blue-50': index === selectedIndex }"
          >
            <!-- Icon/Photo -->
            <div class="flex-shrink-0 w-10 h-10 ml-3">
              <img
                v-if="suggestion.photo"
                :src="suggestion.photo"
                :alt="suggestion.text"
                class="w-10 h-10 rounded-full object-cover"
              />
              <div
                v-else
                class="w-10 h-10 rounded-full flex items-center justify-center text-white"
                :class="{
                  'bg-blue-500': suggestion.type === 'user',
                  'bg-green-500': suggestion.type === 'organization',
                  'bg-purple-500': suggestion.type === 'event'
                }"
              >
                <svg v-if="suggestion.type === 'user'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                <svg v-else-if="suggestion.type === 'organization'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <svg v-else class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>

            <!-- Text -->
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">
                {{ suggestion.text }}
              </p>
              <p class="text-sm text-gray-500 truncate">
                {{ suggestion.subtext }}
              </p>
            </div>

            <!-- Type Badge -->
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
              :class="{
                'bg-blue-100 text-blue-800': suggestion.type === 'user',
                'bg-green-100 text-green-800': suggestion.type === 'organization',
                'bg-purple-100 text-purple-800': suggestion.type === 'event'
              }"
            >
              {{ suggestion.type }}
            </span>
          </router-link>

          <!-- View All Results -->
          <router-link
            :to="`/search?q=${searchQuery}`"
            @click="closeSuggestions"
            class="flex items-center justify-center p-3 text-sm font-medium text-blue-600 border-t border-gray-200 hover:bg-blue-50 transition-colors"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            View all results
          </router-link>
        </div>

        <!-- No Results -->
        <div v-else class="p-4 text-center text-gray-500">
          No results found for "{{ searchQuery }}"
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useSearchStore } from '@/stores/search'
import { useDebounceFn } from '@vueuse/core'

const router = useRouter()
const searchStore = useSearchStore()
const searchContainer = ref(null)

const searchQuery = ref('')
const showSuggestions = ref(false)
const selectedIndex = ref(-1)

const { suggestions, loading } = searchStore

// Debounced search
const debouncedSearch = useDebounceFn(() => {
  if (searchQuery.value.length >= 2) {
    searchStore.fetchSuggestions(searchQuery.value)
  }
}, 300)

function onInput() {
  selectedIndex.value = -1
  if (searchQuery.value.length >= 2) {
    debouncedSearch()
    showSuggestions.value = true
  } else {
    searchStore.clearResults()
    showSuggestions.value = false
  }
}

function performSearch() {
  if (searchQuery.value.length >= 2) {
    closeSuggestions()
    router.push(`/search?q=${searchQuery.value}`)
  }
}

function clearSearch() {
  searchQuery.value = ''
  searchStore.clearSearch()
  closeSuggestions()
}

function closeSuggestions() {
  showSuggestions.value = false
  selectedIndex.value = -1
}

function navigateSuggestions(direction) {
  const maxIndex = suggestions.value.length - 1
  selectedIndex.value = Math.max(-1, Math.min(maxIndex, selectedIndex.value + direction))
  
  if (selectedIndex.value >= 0 && selectedIndex.value <= maxIndex) {
    const suggestion = suggestions.value[selectedIndex.value]
    if (direction === 1 && selectedIndex.value === maxIndex) {
      // At the end, allow enter to go to search results
      return
    }
  }
}

// Click outside to close
function handleClickOutside(event) {
  if (searchContainer.value && !searchContainer.value.contains(event.target)) {
    closeSuggestions()
  }
}

// Add event listener
watch(showSuggestions, (value) => {
  if (value) {
    document.addEventListener('click', handleClickOutside)
  } else {
    document.removeEventListener('click', handleClickOutside)
  }
})
</script>

<style scoped>
/* Smooth scrolling for suggestions */
.overflow-auto {
  scroll-behavior: smooth;
}
</style>
