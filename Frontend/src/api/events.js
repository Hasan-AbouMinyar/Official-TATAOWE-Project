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
  },

  // Get event reviews
  getReviews(id) {
    return apiClient.get(`/events/${id}/reviews`)
  },

  // Add or update review
  addReview(id, reviewData) {
    return apiClient.post(`/events/${id}/reviews`, reviewData)
  },

  // Check if user applied to event
  checkApplication(id) {
    return apiClient.get(`/events/${id}/check-application`)
  }
}
