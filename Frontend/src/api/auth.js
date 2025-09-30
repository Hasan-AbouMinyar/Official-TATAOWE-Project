import apiClient from './axios'

export default {
  // Register new user
  register(userData) {
    return apiClient.post('/register', userData)
  },

  // Login user
  login(credentials) {
    return apiClient.post('/login', credentials)
  },

  // Logout user
  logout() {
    return apiClient.post('/logout')
  },

  // Get authenticated user
  getUser() {
    return apiClient.get('/user')
  },

  // Update user profile
  updateProfile(userData) {
    return apiClient.put('/user/profile', userData)
  },

  // Upload profile photo
  uploadPhoto(formData) {
    return apiClient.post('/user/photo', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  },

  // Change password
  changePassword(passwordData) {
    return apiClient.put('/user/password', passwordData)
  }
}
