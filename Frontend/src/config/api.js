/**
 * API Configuration
 * Centralized configuration for API endpoints and URLs
 */

export const API_CONFIG = {
  // Base URL for API requests
  BASE_URL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  
  // Base URL for the backend server (without /api)
  SERVER_URL: import.meta.env.VITE_SERVER_URL || 'http://localhost:8000',
  
  // Storage URL for uploaded files
  STORAGE_URL: import.meta.env.VITE_STORAGE_URL || 'http://localhost:8000/storage',
}

/**
 * Get full URL for uploaded file
 * @param {string} path - The file path (can be relative or absolute)
 * @returns {string} - Full URL to the file
 */
export function getFileUrl(path) {
  if (!path) return ''
  
  // If it's already a full URL (http/https) or data URL, return as is
  if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:')) {
    return path
  }
  
  // If it starts with /storage/, construct the full URL
  if (path.startsWith('/storage/')) {
    return API_CONFIG.SERVER_URL + path
  }
  
  // If it starts with storage/ (without leading slash)
  if (path.startsWith('storage/')) {
    return API_CONFIG.SERVER_URL + '/' + path
  }
  
  // Otherwise, assume it's a relative path and add /storage/ prefix
  return API_CONFIG.STORAGE_URL + '/' + path
}

/**
 * Get full URL for profile photo
 * @param {string} photo - The photo path
 * @returns {string} - Full URL to the photo
 */
export function getPhotoUrl(photo) {
  return getFileUrl(photo)
}

export default API_CONFIG
