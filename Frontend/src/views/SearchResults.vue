<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Search Header -->
    <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
        Search Results
      </h1>
      <p v-if="query" class="mt-2 text-gray-600">
        Results for: <span class="font-semibold">{{ query }}</span>
      </p>
    </div>

    <!-- Search Tabs -->
    <div class="mb-6 border-b border-gray-200">
      <nav class="-mb-px flex space-x-8 space-x-reverse">
        <button
          @click="searchStore.setTab('all')"
          class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          :class="searchStore.currentTab === 'all' 
            ? 'border-blue-500 text-blue-600' 
            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          All
          <span v-if="searchStore.results.total > 0" class="mr-2 py-0.5 px-2 rounded-full text-xs bg-gray-100">
            {{ searchStore.results.total }}
          </span>
        </button>

        <button
          @click="searchStore.setTab('users')"
          class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          :class="searchStore.currentTab === 'users' 
            ? 'border-blue-500 text-blue-600' 
            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Users
          <span v-if="searchStore.results.users.length > 0" class="mr-2 py-0.5 px-2 rounded-full text-xs bg-gray-100">
            {{ searchStore.results.users.length }}
          </span>
        </button>

        <button
          @click="searchStore.setTab('organizations')"
          class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          :class="searchStore.currentTab === 'organizations' 
            ? 'border-blue-500 text-blue-600' 
            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Organizations
          <span v-if="searchStore.results.organizations.length > 0" class="mr-2 py-0.5 px-2 rounded-full text-xs bg-gray-100">
            {{ searchStore.results.organizations.length }}
          </span>
        </button>

        <button
          @click="searchStore.setTab('events')"
          class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          :class="searchStore.currentTab === 'events' 
            ? 'border-blue-500 text-blue-600' 
            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Events
          <span v-if="searchStore.results.events.length > 0" class="mr-2 py-0.5 px-2 rounded-full text-xs bg-gray-100">
            {{ searchStore.results.events.length }}
          </span>
        </button>
      </nav>
    </div>

    <!-- Loading State -->
    <div v-if="searchStore.loading" class="flex justify-center items-center py-12">
      <div class="text-center">
        <svg class="inline w-12 h-12 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600">Searching...</p>
      </div>
    </div>

    <!-- Results -->
    <div v-else-if="searchStore.hasResults">
      <!-- All Results View -->
      <div v-if="searchStore.currentTab === 'all'" class="space-y-8">
        <!-- Users Section -->
        <div v-if="searchStore.results.users.length > 0">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Users</h2>
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <UserCard
              v-for="user in searchStore.results.users"
              :key="user.id"
              :user="user"
            />
          </div>
        </div>

        <!-- Organizations Section -->
        <div v-if="searchStore.results.organizations.length > 0">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Organizations</h2>
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <OrganizationCard
              v-for="org in searchStore.results.organizations"
              :key="org.id"
              :organization="org"
            />
          </div>
        </div>

        <!-- Events Section -->
        <div v-if="searchStore.results.events.length > 0">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Events</h2>
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <EventCard
              v-for="event in searchStore.results.events"
              :key="event.id"
              :event="event"
            />
          </div>
        </div>
      </div>

      <!-- Single Type View -->
      <div v-else>
        <div v-if="searchStore.currentTab === 'users'" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <UserCard
            v-for="user in searchStore.results.users"
            :key="user.id"
            :user="user"
          />
        </div>

        <div v-else-if="searchStore.currentTab === 'organizations'" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <OrganizationCard
            v-for="org in searchStore.results.organizations"
            :key="org.id"
            :organization="org"
          />
        </div>

        <div v-else-if="searchStore.currentTab === 'events'" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <EventCard
            v-for="event in searchStore.results.events"
            :key="event.id"
            :event="event"
          />
        </div>

        <!-- Pagination -->
        <div v-if="searchStore.lastPage > 1" class="mt-8 flex justify-center">
          <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button
              @click="searchStore.prevPage()"
              :disabled="searchStore.currentPage === 1"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="sr-only">Previous</span>
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </button>

            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
              {{ searchStore.currentPage }} / {{ searchStore.lastPage }}
            </span>

            <button
              @click="searchStore.nextPage()"
              :disabled="searchStore.currentPage === searchStore.lastPage"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="sr-only">Next</span>
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
      <p class="mt-1 text-sm text-gray-500">
        Try searching with different keywords
      </p>
    </div>
  </div>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useSearchStore } from '@/stores/search'
import UserCard from '@/components/users/UserCard.vue'
import OrganizationCard from '@/components/organizations/OrganizationCard.vue'
import EventCard from '@/components/events/EventCard.vue'

const route = useRoute()
const searchStore = useSearchStore()

onMounted(() => {
  const query = route.query.q
  if (query) {
    searchStore.setQuery(query)
    performSearch()
  }
})

watch(() => route.query.q, (newQuery) => {
  if (newQuery) {
    searchStore.setQuery(newQuery)
    performSearch()
  }
})

watch(() => searchStore.currentTab, () => {
  if (searchStore.query) {
    performSearch()
  }
})

function performSearch() {
  if (searchStore.currentTab === 'all') {
    searchStore.searchAll()
  } else {
    searchStore.searchByType(searchStore.currentTab)
  }
}
</script>
