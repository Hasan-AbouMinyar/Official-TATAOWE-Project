<template>
  <section class="flex flex-col w-full px-6 md:justify-between md:items-center md:flex-row">
    <div>
      <h2 class="text-3xl font-medium text-gray-800">
        {{ isOrganizationMode ? 'Organization Dashboard' : 'Dashboard' }}
      </h2>
      <p class="mt-2 text-sm text-gray-500">
        {{ 
          isOrganizationMode 
            ? `Manage and view events for ${organizationName}` 
            : 'Tatawoe Platform connects volunteers with charitable organizations, empowering communities through meaningful opportunities' 
        }}
      </p>
    </div>
  </section>
</template>
<script setup>
import { computed } from 'vue'
import { useOrganizationStore } from '@/stores/organization'

const organizationStore = useOrganizationStore()

const isOrganizationMode = computed(() => organizationStore.isOrganizationMode)
const organizationName = computed(() => organizationStore.organizationName)
</script>
