import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { searchApi } from '../api/search'

export const useSearchStore = defineStore('search', () => {
  // State
  const query = ref('')
  const results = ref({
    users: [],
    organizations: [],
    events: [],
    total: 0
  })
  const suggestions = ref([])
  const loading = ref(false)
  const currentTab = ref('all') // all, users, organizations, events
  const filters = ref({})
  
  // Pagination
  const currentPage = ref(1)
  const lastPage = ref(1)
  const total = ref(0)
  const perPage = ref(15)

  // Getters
  const hasResults = computed(() => results.value.total > 0)
  const isEmpty = computed(() => query.value.length === 0)

  // Actions
  async function searchAll() {
    if (query.value.length < 2) {
      clearResults()
      return
    }

    loading.value = true
    try {
      const response = await searchApi.searchAll(query.value, 10)
      results.value = response.data
    } catch (error) {
      console.error('Search failed:', error)
      clearResults()
    } finally {
      loading.value = false
    }
  }

  async function searchByType(type, page = 1) {
    if (query.value.length < 2) {
      clearResults()
      return
    }

    loading.value = true
    currentTab.value = type

    try {
      let response
      switch (type) {
        case 'users':
          response = await searchApi.searchUsers(query.value, page, perPage.value)
          results.value.users = response.data.data
          break
        case 'organizations':
          response = await searchApi.searchOrganizations(query.value, page, perPage.value)
          results.value.organizations = response.data.data
          break
        case 'events':
          response = await searchApi.searchEvents(query.value, page, perPage.value)
          results.value.events = response.data.data
          break
        default:
          await searchAll()
          return
      }

      // Update pagination
      currentPage.value = response.data.current_page
      lastPage.value = response.data.last_page
      total.value = response.data.total
    } catch (error) {
      console.error('Search failed:', error)
      clearResults()
    } finally {
      loading.value = false
    }
  }

  async function advancedSearch(searchFilters = {}) {
    if (query.value.length < 2) {
      clearResults()
      return
    }

    loading.value = true
    filters.value = searchFilters

    try {
      const response = await searchApi.advancedSearch(
        query.value,
        currentTab.value,
        searchFilters,
        currentPage.value,
        perPage.value
      )

      if (currentTab.value === 'all') {
        results.value = response.data
      } else {
        results.value[currentTab.value] = response.data.data
        currentPage.value = response.data.current_page
        lastPage.value = response.data.last_page
        total.value = response.data.total
      }
    } catch (error) {
      console.error('Advanced search failed:', error)
      clearResults()
    } finally {
      loading.value = false
    }
  }

  async function fetchSuggestions(searchQuery) {
    if (searchQuery.length < 2) {
      suggestions.value = []
      return
    }

    try {
      const response = await searchApi.getSuggestions(searchQuery, 5)
      suggestions.value = response.data
    } catch (error) {
      console.error('Failed to fetch suggestions:', error)
      suggestions.value = []
    }
  }

  function clearResults() {
    results.value = {
      users: [],
      organizations: [],
      events: [],
      total: 0
    }
    suggestions.value = []
    currentPage.value = 1
    lastPage.value = 1
    total.value = 0
  }

  function clearSearch() {
    query.value = ''
    clearResults()
    filters.value = {}
    currentTab.value = 'all'
  }

  function setQuery(newQuery) {
    query.value = newQuery
  }

  function setTab(tab) {
    currentTab.value = tab
    currentPage.value = 1
  }

  function nextPage() {
    if (currentPage.value < lastPage.value) {
      currentPage.value++
      searchByType(currentTab.value, currentPage.value)
    }
  }

  function prevPage() {
    if (currentPage.value > 1) {
      currentPage.value--
      searchByType(currentTab.value, currentPage.value)
    }
  }

  function goToPage(page) {
    if (page >= 1 && page <= lastPage.value) {
      currentPage.value = page
      searchByType(currentTab.value, page)
    }
  }

  return {
    // State
    query,
    results,
    suggestions,
    loading,
    currentTab,
    filters,
    currentPage,
    lastPage,
    total,
    perPage,

    // Getters
    hasResults,
    isEmpty,

    // Actions
    searchAll,
    searchByType,
    advancedSearch,
    fetchSuggestions,
    clearResults,
    clearSearch,
    setQuery,
    setTab,
    nextPage,
    prevPage,
    goToPage
  }
})
