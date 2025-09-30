import auth from './auth'
import organizations from './organizations'
import events from './events'
import users from './users'
import notifications from './notifications'
import search from './search'
import apiClient from './axios'

export default {
  auth,
  organizations,
  events,
  users,
  notifications,
  search,
  
  // Direct axios methods for custom endpoints
  get: (url, config) => apiClient.get(url, config),
  post: (url, data, config) => apiClient.post(url, data, config),
  put: (url, data, config) => apiClient.put(url, data, config),
  patch: (url, data, config) => apiClient.patch(url, data, config),
  delete: (url, config) => apiClient.delete(url, config)
}
