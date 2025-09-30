<template>
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
        <p v-if="hasUnread" class="text-gray-600 mt-1">
          You have {{ unreadCount }} unread notification{{ unreadCount > 1 ? 's' : '' }}
        </p>
      </div>
      
      <div class="flex gap-3">
        <button
          v-if="hasUnread"
          @click="handleMarkAllAsRead"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
        >
          Mark all as read
        </button>
        <button
          @click="handleClearRead"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
        >
          Clear read
        </button>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-4 border-b mb-6 overflow-x-auto">
      <button
        @click="activeTab = 'all'"
        class="px-4 py-2 font-medium transition-colors relative whitespace-nowrap"
        :class="activeTab === 'all' ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900'"
      >
        All
        <span v-if="activeTab === 'all'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></span>
      </button>
      <button
        @click="activeTab = 'unread'"
        class="px-4 py-2 font-medium transition-colors relative whitespace-nowrap"
        :class="activeTab === 'unread' ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900'"
      >
        Unread
        <span v-if="unreadCount > 0" class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-600 rounded-full">
          {{ unreadCount }}
        </span>
        <span v-if="activeTab === 'unread'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></span>
      </button>
      <button
        @click="activeTab = 'volunteer'"
        class="px-4 py-2 font-medium transition-colors relative whitespace-nowrap"
        :class="activeTab === 'volunteer' ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900'"
      >
        <span class="flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          My Applications
        </span>
        <span v-if="activeTab === 'volunteer'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></span>
      </button>
      <button
        @click="activeTab = 'organization'"
        class="px-4 py-2 font-medium transition-colors relative whitespace-nowrap"
        :class="activeTab === 'organization' ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900'"
      >
        <span class="flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          My Events
        </span>
        <span v-if="activeTab === 'organization'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></span>
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <svg class="w-10 h-10 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <!-- Empty State -->
    <div v-else-if="displayedNotifications.length === 0" class="text-center py-12">
      <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <h3 class="text-xl font-semibold text-gray-700 mb-2">No notifications</h3>
      <p class="text-gray-500">
        {{ getEmptyStateMessage() }}
      </p>
    </div>

    <!-- Notifications List -->
    <div v-else class="bg-white rounded-lg shadow-sm border overflow-hidden">
      <NotificationItem
        v-for="notification in displayedNotifications"
        :key="notification.id"
        :notification="notification"
        @click="handleNotificationClick(notification)"
        @delete="handleDelete(notification.id)"
      />
    </div>

    <!-- Pagination -->
    <div v-if="lastPage > 1" class="flex items-center justify-center gap-2 mt-6">
      <button
        @click="changePage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="px-4 py-2 border rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
      >
        Previous
      </button>
      
      <div class="flex gap-1">
        <button
          v-for="page in paginationPages"
          :key="page"
          @click="changePage(page)"
          class="w-10 h-10 rounded-lg transition-colors font-medium"
          :class="page === currentPage 
            ? 'bg-blue-600 text-white' 
            : 'border hover:bg-gray-50 text-gray-700'"
        >
          {{ page }}
        </button>
      </div>
      
      <button
        @click="changePage(currentPage + 1)"
        :disabled="currentPage === lastPage"
        class="px-4 py-2 border rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useNotificationStore } from '../stores/notification'
import { useRouter } from 'vue-router'
import NotificationItem from '../components/notifications/NotificationItem.vue'

const router = useRouter()
const notificationStore = useNotificationStore()
const activeTab = ref('all')

const notifications = computed(() => notificationStore.notifications)
const unreadCount = computed(() => notificationStore.unreadCount)
const hasUnread = computed(() => notificationStore.hasUnread)
const loading = computed(() => notificationStore.loading)
const currentPage = computed(() => notificationStore.currentPage)
const lastPage = computed(() => notificationStore.lastPage)

const displayedNotifications = computed(() => {
  let filtered = notifications.value

  // Filter by read status
  if (activeTab.value === 'unread') {
    filtered = filtered.filter(n => !n.read_at)
  }
  
  // Filter by notification type
  if (activeTab.value === 'volunteer') {
    // Notifications for volunteers (users who apply to events)
    const volunteerTypes = [
      'application_status',      // When their application is accepted/rejected
      'application_submitted',   // Confirmation of submission
      'event_updated',          // Updates to events they applied to
      'event_reminder'          // Reminders for events they're attending
    ]
    filtered = filtered.filter(n => volunteerTypes.includes(n.data.type))
  } else if (activeTab.value === 'organization') {
    // Notifications for organizations (event creators)
    const organizationTypes = [
      'new_application',        // New applications received
      'new_review',            // New reviews on their events
      'event_created',         // Event creation confirmation
      'event_updated'          // Event update confirmation (for their own events)
    ]
    filtered = filtered.filter(n => organizationTypes.includes(n.data.type))
  }

  return filtered
})

const paginationPages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(lastPage.value, start + maxVisible - 1)
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

async function changePage(page) {
  if (page < 1 || page > lastPage.value || page === currentPage.value) return
  await notificationStore.fetchNotifications(page)
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

async function handleMarkAllAsRead() {
  await notificationStore.markAllAsRead()
}

async function handleClearRead() {
  if (confirm('Are you sure you want to clear all read notifications?')) {
    await notificationStore.clearRead()
  }
}

async function handleNotificationClick(notification) {
  // Mark as read
  if (!notification.read_at) {
    await notificationStore.markAsRead(notification.id)
  }

  // Navigate based on notification type
  const data = notification.data
  const type = data.type

  switch (type) {
    case 'application_status':
      // Application status changed - go to event details or my applications
      if (data.event_id) {
        router.push({ name: 'EventDetails', params: { id: data.event_id } })
      } else {
        router.push({ name: 'MyApplications' })
      }
      break

    case 'application_submitted':
      // Application submitted - go to my applications
      router.push({ name: 'MyApplications' })
      break

    case 'new_application':
      // New application received - go to event applications page
      if (data.event_id) {
        router.push({ name: 'EventApplications', params: { id: data.event_id } })
      }
      break

    case 'event_created':
    case 'event_updated':
    case 'event_reminder':
      // Event notifications - go to event details
      if (data.event_id) {
        router.push({ name: 'EventDetails', params: { id: data.event_id } })
      }
      break

    case 'new_review':
      // New review - go to event details (reviews section)
      if (data.event_id) {
        router.push({ 
          name: 'EventDetails', 
          params: { id: data.event_id },
          hash: '#reviews'
        })
      }
      break

    default:
      // Fallback - try event_id or user_id
      if (data.event_id) {
        router.push({ name: 'EventDetails', params: { id: data.event_id } })
      } else if (data.user_id) {
        router.push({ name: 'UserProfile', params: { id: data.user_id } })
      }
  }
}

async function handleDelete(notificationId) {
  await notificationStore.deleteNotification(notificationId)
}

function getEmptyStateMessage() {
  switch (activeTab.value) {
    case 'unread':
      return 'You have no unread notifications'
    case 'volunteer':
      return 'No notifications about your applications yet'
    case 'organization':
      return 'No notifications about your events yet'
    default:
      return 'You have no notifications yet'
  }
}

onMounted(() => {
  notificationStore.fetchNotifications()
})
</script>
