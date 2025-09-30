import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../api'

export const useOrganizationStore = defineStore('organization', () => {
  // State
  const organizations = ref([])
  const currentOrganization = ref(null)
  const loading = ref(false)
  const error = ref(null)
  
  // Organization Mode State
  const activeOrganization = ref(null)
  const isOrganizationMode = ref(false)

  // Getters
  const hasActiveOrganization = computed(() => activeOrganization.value !== null)
  const organizationName = computed(() => activeOrganization.value?.name || '')
  const organizationId = computed(() => activeOrganization.value?.id || null)

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

  // Organization Mode Actions
  function switchToOrganization(organization) {
    activeOrganization.value = organization
    isOrganizationMode.value = true
    
    // Save to localStorage for persistence
    localStorage.setItem('active_organization', JSON.stringify(organization))
    localStorage.setItem('is_organization_mode', 'true')
  }

  function switchToPersonalAccount() {
    activeOrganization.value = null
    isOrganizationMode.value = false
    
    // Clear from localStorage
    localStorage.removeItem('active_organization')
    localStorage.removeItem('is_organization_mode')
  }

  function restoreOrganizationMode() {
    const savedOrg = localStorage.getItem('active_organization')
    const savedMode = localStorage.getItem('is_organization_mode')
    
    if (savedOrg && savedMode === 'true') {
      try {
        activeOrganization.value = JSON.parse(savedOrg)
        isOrganizationMode.value = true
      } catch (e) {
        console.error('Failed to restore organization mode:', e)
        switchToPersonalAccount()
      }
    }
  }

  function clearOrganizationData() {
    organizations.value = []
    currentOrganization.value = null
    activeOrganization.value = null
    isOrganizationMode.value = false
    localStorage.removeItem('active_organization')
    localStorage.removeItem('is_organization_mode')
  }

  return {
    // State
    organizations,
    currentOrganization,
    loading,
    error,
    activeOrganization,
    isOrganizationMode,
    
    // Getters
    hasActiveOrganization,
    organizationName,
    organizationId,
    
    // Actions
    fetchOrganizations,
    fetchOrganization,
    createOrganization,
    updateOrganization,
    deleteOrganization,
    switchToOrganization,
    switchToPersonalAccount,
    restoreOrganizationMode,
    clearOrganizationData
  }
})
