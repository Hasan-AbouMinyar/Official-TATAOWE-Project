import apiClient from './axios'

export default {
  getUserApplications(userId) {
    return apiClient.get(`/users/${userId}/applications`)
  }
}
