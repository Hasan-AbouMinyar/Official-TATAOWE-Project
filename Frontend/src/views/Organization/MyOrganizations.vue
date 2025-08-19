<template>
  <div class="bg-gray-50 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Page Header -->
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Organizations</h1>
        <router-link :to="{ name: 'OrganizationCreate' }" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <!-- Plus Icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Create New Organization
        </router-link>
      </div>

      <!-- Grid for Organization Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <OrganizationCard 
          v-for="org in organizations" 
          :key="org.id" 
          :organization="org" 
          @delete="handleDelete"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import OrganizationCard from '@/components/OrganizationCard.vue'; // Assuming the card is in a file named OrganizationCard.vue

// Sample data for the list of organizations
const organizations = ref([
  {
    id: 1,
    name: "Innovatech Solutions",
    logo: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1780&q=80",
    location: "Silicon Valley, CA",
    field: "AI & Machine Learning",
  },
  {
    id: 2,
    name: "GreenScape Eco",
    logo: "https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1769&q=80",
    location: "Austin, TX",
    field: "Environmental Tech",
  },
  {
    id: 3,
    name: "Quantum Dynamics",
    logo: "https://images.unsplash.com/photo-1517048676732-d65bc937f952?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
    location: "Boston, MA",
    field: "Quantum Computing",
  },
]);

function handleDelete(org) {
  organizations.value = organizations.value.filter(o => o.id !== org.id);
}
</script>
