import apiClient from './axios'

export default {
  // Get all organizations
  getAll(params = {}) {
    return apiClient.get('/organizations', { params })
  },

  // Get single organization
  getById(id) {
    return apiClient.get(`/organizations/${id}`)
  },

  // Create organization
  create(organizationData, config = {}) {
    return apiClient.post('/organizations', organizationData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        ...config.headers
      }
    })
  },

  // Update organization
  update(id, organizationData) {
    // Check if organizationData is FormData (for file uploads)
    const isFormData = organizationData instanceof FormData
    
    return apiClient.post(`/organizations/${id}`, organizationData, {
      headers: isFormData ? {
        'Content-Type': 'multipart/form-data'
      } : {},
      params: isFormData ? { _method: 'PUT' } : {}
    })
  },

  // Delete organization
  delete(id) {
    return apiClient.delete(`/organizations/${id}`)
  },

  // Get organization events
  getEvents(id) {
    return apiClient.get(`/organizations/${id}/events`)
  },

  // Get user's organizations
  getUserOrganizations(userId) {
    return apiClient.get(`/users/${userId}/organizations`)
  }
}
