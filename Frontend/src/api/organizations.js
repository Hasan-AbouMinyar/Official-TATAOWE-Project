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
  create(organizationData) {
    return apiClient.post('/organizations', organizationData)
  },

  // Update organization
  update(id, organizationData) {
    return apiClient.put(`/organizations/${id}`, organizationData)
  },

  // Delete organization
  delete(id) {
    return apiClient.delete(`/organizations/${id}`)
  },

  // Get organization events
  getEvents(id) {
    return apiClient.get(`/organizations/${id}/events`)
  }
}
