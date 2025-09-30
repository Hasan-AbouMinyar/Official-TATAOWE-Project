<template>
  <router-link
    :to="`/users/${user.id}`"
    class="flex items-center p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200"
  >
    <!-- User Photo -->
    <div class="flex-shrink-0 w-12 h-12 ml-4">
      <img
        v-if="user.photo"
        :src="user.photo"
        :alt="user.name"
        class="w-12 h-12 rounded-full object-cover"
      />
      <div
        v-else
        class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg"
      >
        {{ user.name.charAt(0).toUpperCase() }}
      </div>
    </div>

    <!-- User Info -->
    <div class="flex-1 min-w-0">
      <h3 class="text-lg font-semibold text-gray-900 truncate">
        {{ user.name }}
      </h3>
      <p class="text-sm text-gray-500 truncate">
        @{{ user.username }}
      </p>
      <p v-if="user.email" class="text-xs text-gray-400 truncate mt-1">
        {{ user.email }}
      </p>
    </div>

    <!-- Arrow -->
    <div class="flex-shrink-0">
      <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      </svg>
    </div>
  </router-link>
</template>

<script setup>
defineProps({
  user: {
    type: Object,
    required: true
  }
})
</script>
