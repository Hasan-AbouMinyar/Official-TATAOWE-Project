import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useOrganizationStore = defineStore('organization', () => {
  // State
  const organizations = ref([])
  const currentOrganization = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Actions
  async function fetchOrganizations(params = {}) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.organizations.getAll(params)
      organizations.value = response.data
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch organizations.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchOrganization(id) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.organizations.getById(id)
      currentOrganization.value = response.data
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch organization.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createOrganization(organizationData) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.organizations.create(organizationData)
      organizations.value.push(response.data)
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create organization.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateOrganization(id, organizationData) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.organizations.update(id, organizationData)
      
      // Update in list
      const index = organizations.value.findIndex(org => org.id === id)
      if (index !== -1) {
        organizations.value[index] = response.data
      }
      
      // Update current if it's the same
      if (currentOrganization.value?.id === id) {
        currentOrganization.value = response.data
      }
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update organization.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteOrganization(id) {
    try {
      loading.value = true
      error.value = null
      
      await api.organizations.delete(id)
      
      // Remove from list
      organizations.value = organizations.value.filter(org => org.id !== id)
      
      // Clear current if it's the same
      if (currentOrganization.value?.id === id) {
        currentOrganization.value = null
      }
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete organization.'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    organizations,
    currentOrganization,
    loading,
    error,
    // Actions
    fetchOrganizations,
    fetchOrganization,
    createOrganization,
    updateOrganization,
    deleteOrganization
  }
})
