<template>
  <div class="relative">
    <!-- Notification Bell Button -->
    <button 
      @click="toggleDropdown"
      class="relative p-2 text-gray-400 transition-colors duration-300 rounded-full hover:bg-gray-100 hover:text-gray-600 focus:bg-gray-100 focus:outline-none z-20"
      :class="{ 'text-blue-600': hasUnread }"
    >
      <span class="sr-only">Notifications</span>
      
      <!-- Unread Badge -->
      <div v-if="hasUnread" class="absolute top-0 right-0">
        <span class="block w-2 h-2 mt-1 mr-2 bg-blue-700 rounded-full"></span>
        <span class="block w-2 h-2 mt-1 mr-2 bg-blue-700 rounded-full animate-ping absolute top-0 right-0"></span>
      </div>
      
      <!-- Bell Icon -->
      <svg 
        aria-hidden="true" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor" 
        class="w-6 h-6"
        :class="{ 'animate-wiggle': hasUnread }"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" 
        />
      </svg>
    </button>

    <!-- Notifications Dropdown -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div 
        v-show="isOpen" 
        class="absolute right-0 z-50 w-96 bg-white border rounded-lg shadow-xl top-16 lg:top-20 max-h-[32rem] overflow-hidden flex flex-col"
        role="menu"
      >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b bg-gray-50">
          <div>
            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
            <p v-if="hasUnread" class="text-sm text-gray-600">
              {{ unreadCount }} unread
            </p>
          </div>
          <div class="flex gap-2">
            <button 
              v-if="hasUnread"
              @click="handleMarkAllAsRead"
              class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors"
              title="Mark all as read"
            >
              Mark all read
            </button>
            <button 
              @click="handleClearRead"
              class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
              title="Clear read notifications"
            >
              Clear read
            </button>
          </div>
        </div>

        <!-- Notifications List -->
        <div class="flex-1 overflow-y-auto">
          <!-- Loading State -->
          <div v-if="loading" class="flex items-center justify-center p-8">
            <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <!-- Empty State -->
          <div v-else-if="notifications.length === 0" class="flex flex-col items-center justify-center p-8 text-gray-500">
            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="text-sm">No notifications yet</p>
          </div>

          <!-- Notification Items -->
          <div v-else>
            <NotificationItem
              v-for="notification in notifications"
              :key="notification.id"
              :notification="notification"
              @click="handleNotificationClick(notification)"
              @delete="handleDelete(notification.id)"
            />
          </div>
        </div>

        <!-- Footer -->
        <div v-if="notifications.length > 0" class="border-t bg-gray-50">
          <RouterLink 
            to="/notifications" 
            class="block w-full p-3 text-center text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors"
            @click="isOpen = false"
          >
            View all notifications
          </RouterLink>
        </div>
      </div>
    </transition>

    <!-- Backdrop -->
    <div 
      v-show="isOpen" 
      class="fixed inset-0 z-30" 
      @click="isOpen = false"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useNotificationStore } from '../../stores/notification'
import { useRouter } from 'vue-router'
import NotificationItem from './NotificationItem.vue'

const router = useRouter()
const notificationStore = useNotificationStore()
const isOpen = ref(false)

const notifications = computed(() => notificationStore.notifications)
const unreadCount = computed(() => notificationStore.unreadCount)
const hasUnread = computed(() => notificationStore.hasUnread)
const loading = computed(() => notificationStore.loading)

function toggleDropdown() {
  isOpen.value = !isOpen.value
  if (isOpen.value && notifications.value.length === 0) {
    notificationStore.fetchNotifications()
  }
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
  if (data.event_id) {
    router.push({ name: 'EventDetails', params: { id: data.event_id } })
  }
  
  isOpen.value = false
}

async function handleDelete(notificationId) {
  await notificationStore.deleteNotification(notificationId)
}

onMounted(() => {
  // Start polling for new notifications
  notificationStore.startPolling()
})

onUnmounted(() => {
  // Stop polling when component is destroyed
  notificationStore.stopPolling()
})
</script>

<style scoped>
@keyframes wiggle {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(-10deg); }
  75% { transform: rotate(10deg); }
}

.animate-wiggle {
  animation: wiggle 0.5s ease-in-out infinite;
}
</style>
