import apiClient from './axios'

export default {
  // Get user by ID
  getById(id) {
    return apiClient.get(`/users/${id}`)
  },

  // Get user applications
  getUserApplications(userId) {
    return apiClient.get(`/users/${userId}/applications`)
  },

  // Get user organizations (if user is owner)
  getUserOrganizations(userId) {
    return apiClient.get(`/users/${userId}/organizations`)
  }
}
