import apiClient from './axios'

export const notificationApi = {
  // Get all notifications with pagination
  getNotifications(page = 1) {
    return apiClient.get(`/notifications?page=${page}`)
  },

  // Get unread notifications count
  getUnreadCount() {
    return apiClient.get('/notifications/unread-count')
  },

  // Mark notification as read
  markAsRead(notificationId) {
    return apiClient.post(`/notifications/${notificationId}/read`)
  },

  // Mark all notifications as read
  markAllAsRead() {
    return apiClient.post('/notifications/mark-all-read')
  },

  // Delete notification
  deleteNotification(notificationId) {
    return apiClient.delete(`/notifications/${notificationId}`)
  },

  // Clear all read notifications
  clearRead() {
    return apiClient.delete('/notifications/clear-read')
  }
}

export default notificationApi
