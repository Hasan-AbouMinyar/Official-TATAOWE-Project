<template>
  <router-link
    :to="`/organization/${organization.id}`"
    class="block p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200"
  >
    <!-- Organization Photo -->
    <div class="flex items-start mb-3">
      <div class="flex-shrink-0 w-12 h-12 ml-3">
        <img
          v-if="organization.photo"
          :src="organization.photo"
          :alt="organization.name"
          class="w-12 h-12 rounded-lg object-cover"
        />
        <div
          v-else
          class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-semibold text-lg"
        >
          {{ organization.name.charAt(0).toUpperCase() }}
        </div>
      </div>

      <!-- Organization Name & Owner -->
      <div class="flex-1 min-w-0">
        <h3 class="text-lg font-semibold text-gray-900 truncate">
          {{ organization.name }}
        </h3>
        <p v-if="organization.user" class="text-sm text-gray-500 truncate">
          by {{ organization.user.name }}
        </p>
      </div>
    </div>

    <!-- Description -->
    <p v-if="organization.description" class="text-sm text-gray-600 line-clamp-2 mb-3">
      {{ organization.description }}
    </p>

    <!-- Stats -->
    <div class="flex items-center justify-between text-sm text-gray-500">
      <span v-if="organization.events_count !== undefined" class="flex items-center">
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        {{ organization.events_count }} {{ organization.events_count === 1 ? 'event' : 'events' }}
      </span>
      
      <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      </svg>
    </div>
  </router-link>
</template>

<script setup>
defineProps({
  organization: {
    type: Object,
    required: true
  }
})
</script>
