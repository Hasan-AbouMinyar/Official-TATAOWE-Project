import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useEventStore = defineStore('event', () => {
  // State
  const events = ref([])
  const currentEvent = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Actions
  async function fetchEvents(params = {}) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.events.getAll(params)
      events.value = response.data
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch events.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchEvent(id) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.events.getById(id)
      currentEvent.value = response.data
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch event.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createEvent(eventData) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.events.create(eventData)
      events.value.push(response.data)
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create event.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateEvent(id, eventData) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.events.update(id, eventData)
      
      // Update in list
      const index = events.value.findIndex(event => event.id === id)
      if (index !== -1) {
        events.value[index] = response.data
      }
      
      // Update current if it's the same
      if (currentEvent.value?.id === id) {
        currentEvent.value = response.data
      }
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update event.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteEvent(id) {
    try {
      loading.value = true
      error.value = null
      
      await api.events.delete(id)
      
      // Remove from list
      events.value = events.value.filter(event => event.id !== id)
      
      // Clear current if it's the same
      if (currentEvent.value?.id === id) {
        currentEvent.value = null
      }
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete event.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function applyToEvent(id, applicationData) {
    try {
      loading.value = true
      error.value = null
      
      const response = await api.events.apply(id, applicationData)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to apply to event.'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    events,
    currentEvent,
    loading,
    error,
    // Actions
    fetchEvents,
    fetchEvent,
    createEvent,
    updateEvent,
    deleteEvent,
    applyToEvent
  }
})
