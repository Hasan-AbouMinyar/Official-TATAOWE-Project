import apiClient from './axios'

export default {
  // Get all events
  getAll(params = {}) {
    return apiClient.get('/events', { params })
  },

  // Get single event
  getById(id) {
    return apiClient.get(`/events/${id}`)
  },

  // Create event
  create(eventData) {
    return apiClient.post('/events', eventData)
  },

  // Update event
  update(id, eventData) {
    return apiClient.put(`/events/${id}`, eventData)
  },

  // Delete event
  delete(id) {
    return apiClient.delete(`/events/${id}`)
  },

  // Get event applications
  getApplications(id) {
    return apiClient.get(`/events/${id}/applications`)
  },

  // Apply to event
  apply(id, applicationData) {
    return apiClient.post(`/events/${id}/apply`, applicationData)
  },

  // Get event users (volunteers)
  getUsers(id) {
    return apiClient.get(`/events/${id}/users`)
  }
}
