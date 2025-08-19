<template>
  <!-- Entire card acts as a link using organization.url if provided -->
  <router-link :to="organization && organization.url ? organization.url : { name: 'Dashboard' }" class="block bg-white shadow-md rounded-xl p-5 m-4 flex items-center justify-between hover:shadow-lg transition-shadow cursor-pointer">
    <div class="flex items-center">
      <!-- Organization Logo -->
      <img
  :src="organization.logo"
        alt="Logo"
        class="w-16 h-16 rounded-full object-cover mr-5 border-2 border-gray-200"
      />
      <!-- Organization Info -->
      <div>
  <h3 class="text-gray-900 font-bold text-lg">{{ organization?.name }}</h3>
  <p class="text-gray-600 text-sm" v-if="organization && organization.location">{{ organization.location }}</p>
  <p class="text-gray-500 text-sm mt-1" v-if="organization && organization.field">{{ organization.field }}</p>
      </div>
    </div>

    <!-- Dropdown Menu for actions -->
    <div class="relative">
      <!-- Trigger Button (More options icon) -->
      <!-- @click.prevent stops the navigation when clicking the button -->
  <button @click.stop.prevent="toggleDropdown" class="p-2 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
        </svg>
      </button>

      <!-- Dropdown List -->
      <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-10 ring-1 ring-black ring-opacity-5">
        <div class="py-1">
          <button @click.stop.prevent="$emit('delete', organization)" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete
          </button>
          <!-- You can add more actions here if needed -->
        </div>
      </div>
    </div>
  </router-link>
</template>


<script setup>
import { ref } from 'vue';

// Reactive state for dropdown visibility
const isDropdownOpen = ref(false);

// Function to toggle the dropdown
function toggleDropdown() {
  isDropdownOpen.value = !isDropdownOpen.value;
}

defineProps({
  organization: {
    type: Object,
    required: true,
    default: () => ({
  name: "Organization Name",
      logo: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG_by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1780&q=80",
      location: "San Francisco, CA",
      field: "Technology & Innovation",
      // The URL should now be a valid route path for Vue Router
  url: { name: 'Dashboard' },
    }),
  },
});

defineEmits(['delete']);
</script> 