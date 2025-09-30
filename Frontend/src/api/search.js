import apiClient from './axios'

export const searchApi = {
  // Search across all entities
  searchAll(query, limit = 10) {
    return apiClient.get('/search', {
      params: { q: query, limit }
    })
  },

  // Search users only
  searchUsers(query, page = 1, perPage = 15) {
    return apiClient.get('/search/users', {
      params: { q: query, page, per_page: perPage }
    })
  },

  // Search organizations only
  searchOrganizations(query, page = 1, perPage = 15) {
    return apiClient.get('/search/organizations', {
      params: { q: query, page, per_page: perPage }
    })
  },

  // Search events only
  searchEvents(query, page = 1, perPage = 15) {
    return apiClient.get('/search/events', {
      params: { q: query, page, per_page: perPage }
    })
  },

  // Advanced search with filters
  advancedSearch(query, type = 'all', filters = {}, page = 1, perPage = 15) {
    return apiClient.get('/search/advanced', {
      params: { q: query, type, filters, page, per_page: perPage }
    })
  },

  // Get search suggestions (autocomplete)
  getSuggestions(query, limit = 5) {
    return apiClient.get('/search/suggestions', {
      params: { q: query, limit }
    })
  }
}

export default searchApi
