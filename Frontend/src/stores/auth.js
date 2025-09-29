import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../api'
import router from '../router'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token') || null)
  const loading = ref(false)
  const error = ref(null)

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const currentUser = computed(() => user.value)

  // Actions
  async function login(credentials) {
    try {
      loading.value = true
      error.value = null

      const response = await api.auth.login(credentials)
      
      // Store token and user data
      if (response.data.token) {
        token.value = response.data.token
        localStorage.setItem('auth_token', response.data.token)
      }
      
      user.value = response.data.user
      localStorage.setItem('user', JSON.stringify(response.data.user))

      // Redirect to dashboard
      router.push({ name: 'Dashboard' })
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed. Please try again.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function register(userData) {
    try {
      loading.value = true
      error.value = null

      const response = await api.auth.register(userData)
      
      // Optionally auto-login after registration
      if (response.data.token) {
        token.value = response.data.token
        localStorage.setItem('auth_token', response.data.token)
        user.value = response.data.user
        localStorage.setItem('user', JSON.stringify(response.data.user))
        
        router.push({ name: 'Dashboard' })
      } else {
        // Redirect to login if no auto-login
        router.push({ name: 'Login' })
      }
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed. Please try again.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      loading.value = true
      
      // Call logout endpoint if authenticated
      if (token.value) {
        await api.auth.logout()
      }
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear local state regardless of API call result
      user.value = null
      token.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      loading.value = false
      
      router.push({ name: 'Login' })
    }
  }

  async function fetchUser() {
    try {
      loading.value = true
      const response = await api.auth.getUser()
      user.value = response.data
      localStorage.setItem('user', JSON.stringify(response.data))
      return response.data
    } catch (err) {
      console.error('Fetch user error:', err)
      // If fetch fails, clear auth state
      await logout()
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateProfile(userData) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.auth.updateProfile(userData)
      user.value = response.data.user
      localStorage.setItem('user', JSON.stringify(response.data.user))
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update profile.'
      throw err
    } finally {
      loading.value = false
    }
  }

  function initializeAuth() {
    // Try to restore user from localStorage
    const storedUser = localStorage.getItem('user')
    const storedToken = localStorage.getItem('auth_token')
    
    if (storedUser && storedToken) {
      try {
        user.value = JSON.parse(storedUser)
        token.value = storedToken
      } catch (err) {
        console.error('Failed to parse stored user:', err)
        localStorage.removeItem('user')
        localStorage.removeItem('auth_token')
      }
    }
  }

  return {
    // State
    user,
    token,
    loading,
    error,
    // Getters
    isAuthenticated,
    currentUser,
    // Actions
    login,
    register,
    logout,
    fetchUser,
    updateProfile,
    initializeAuth
  }
})
