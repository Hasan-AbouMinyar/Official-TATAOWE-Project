import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { notificationApi } from '../api/notifications'

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref([])
  const unreadCount = ref(0)
  const loading = ref(false)
  const currentPage = ref(1)
  const lastPage = ref(1)
  const total = ref(0)

  const hasUnread = computed(() => unreadCount.value > 0)
  const unreadNotifications = computed(() => 
    notifications.value.filter(n => !n.read_at)
  )

  // Fetch notifications
  async function fetchNotifications(page = 1) {
    loading.value = true
    try {
      const response = await notificationApi.getNotifications(page)
      notifications.value = response.data.notifications
      unreadCount.value = response.data.unread_count
      currentPage.value = response.data.current_page
      lastPage.value = response.data.last_page
      total.value = response.data.total
    } catch (error) {
      console.error('Failed to fetch notifications:', error)
    } finally {
      loading.value = false
    }
  }

  // Fetch unread count only
  async function fetchUnreadCount() {
    try {
      const response = await notificationApi.getUnreadCount()
      unreadCount.value = response.data.count
    } catch (error) {
      console.error('Failed to fetch unread count:', error)
    }
  }

  // Mark notification as read
  async function markAsRead(notificationId) {
    try {
      await notificationApi.markAsRead(notificationId)
      
      // Update local state
      const notification = notifications.value.find(n => n.id === notificationId)
      if (notification && !notification.read_at) {
        notification.read_at = new Date().toISOString()
        unreadCount.value = Math.max(0, unreadCount.value - 1)
      }
    } catch (error) {
      console.error('Failed to mark notification as read:', error)
    }
  }

  // Mark all as read
  async function markAllAsRead() {
    try {
      await notificationApi.markAllAsRead()
      
      // Update local state
      notifications.value.forEach(n => {
        if (!n.read_at) {
          n.read_at = new Date().toISOString()
        }
      })
      unreadCount.value = 0
    } catch (error) {
      console.error('Failed to mark all as read:', error)
    }
  }

  // Delete notification
  async function deleteNotification(notificationId) {
    try {
      await notificationApi.deleteNotification(notificationId)
      
      // Update local state
      const index = notifications.value.findIndex(n => n.id === notificationId)
      if (index !== -1) {
        const notification = notifications.value[index]
        if (!notification.read_at) {
          unreadCount.value = Math.max(0, unreadCount.value - 1)
        }
        notifications.value.splice(index, 1)
        total.value = Math.max(0, total.value - 1)
      }
    } catch (error) {
      console.error('Failed to delete notification:', error)
    }
  }

  // Clear all read notifications
  async function clearRead() {
    try {
      await notificationApi.clearRead()
      
      // Update local state - keep only unread
      notifications.value = notifications.value.filter(n => !n.read_at)
      total.value = notifications.value.length
    } catch (error) {
      console.error('Failed to clear read notifications:', error)
    }
  }

  // Start polling for new notifications (every 30 seconds)
  let pollInterval = null
  function startPolling() {
    if (pollInterval) return
    
    // Fetch immediately
    fetchUnreadCount()
    
    // Then poll every 30 seconds
    pollInterval = setInterval(() => {
      fetchUnreadCount()
    }, 30000)
  }

  function stopPolling() {
    if (pollInterval) {
      clearInterval(pollInterval)
      pollInterval = null
    }
  }

  return {
    // State
    notifications,
    unreadCount,
    loading,
    currentPage,
    lastPage,
    total,
    
    // Getters
    hasUnread,
    unreadNotifications,
    
    // Actions
    fetchNotifications,
    fetchUnreadCount,
    markAsRead,
    markAllAsRead,
    deleteNotification,
    clearRead,
    startPolling,
    stopPolling
  }
})
