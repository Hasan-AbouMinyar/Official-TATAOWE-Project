<template>
  <!-- Enhanced Organization Card -->
  <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
    <!-- Card Content - Clickable -->
    <div 
      @click="$emit('select', organization)" 
      class="block p-6 cursor-pointer"
    >
      <!-- Organization Header -->
      <div class="flex items-start justify-between mb-4">
        <div class="flex items-center flex-1">
          <!-- Organization Logo -->
          <div class="relative group/avatar">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg blur-sm opacity-50 group-hover/avatar:opacity-75 transition-opacity"></div>
            <div class="relative">
              <Avatar 
                :photo="organization.logo"
                :name="organization.name"
                size="lg"
                :rounded="false"
                bordered
                class="shadow-lg"
              />
            </div>
          </div>
          
          <!-- Organization Info -->
          <div class="mr-4 flex-1">
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
              {{ organization?.name || 'اسم المنظمة' }}
            </h3>
            <div class="flex items-center mt-1 text-sm text-gray-600">
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span class="line-clamp-1">{{ organization?.location || 'الموقع' }}</span>
            </div>
          </div>
        </div>

        <!-- Dropdown Menu Button -->
        <button 
          @click.stop.prevent="toggleDropdown" 
          class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
          </svg>
        </button>
      </div>

      <!-- Organization Field/Category -->
      <div class="mb-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 border border-blue-200">
          <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
          {{ organization?.field || 'المجال' }}
        </span>
      </div>

      <!-- Organization Stats -->
      <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-100">
        <div class="text-center">
          <p class="text-2xl font-bold text-gray-900">{{ organization?.events_count || 0 }}</p>
          <p class="text-xs text-gray-600 mt-1">الفعاليات</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-gray-900">{{ organization?.members_count || 0 }}</p>
          <p class="text-xs text-gray-600 mt-1">الأعضاء</p>
        </div>
      </div>

      <!-- Hover Effect Indicator -->
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-purple-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
    </div>

    <!-- Dropdown Menu -->
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div 
        v-if="isDropdownOpen" 
        class="absolute left-4 top-20 w-48 bg-white rounded-xl shadow-2xl z-20 ring-1 ring-black ring-opacity-5 overflow-hidden"
      >
        <div class="py-1">
          <button 
            @click.stop.prevent="editOrganization" 
            class="w-full text-right px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 flex items-center transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span class="font-medium">تعديل</span>
          </button>
          
          <button 
            @click.stop.prevent="viewDetails" 
            class="w-full text-right px-4 py-3 text-sm text-gray-700 hover:bg-green-50 flex items-center transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span class="font-medium">عرض التفاصيل</span>
          </button>

          <div class="border-t border-gray-100"></div>
          
          <button 
            @click.stop.prevent="confirmDelete" 
            class="w-full text-right px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span class="font-medium">حذف</span>
          </button>
        </div>
      </div>
    </transition>

    <!-- Backdrop to close dropdown -->
    <div 
      v-if="isDropdownOpen" 
      @click="closeDropdown" 
      class="fixed inset-0 z-10"
    ></div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Avatar from './Avatar.vue'
import { getPhotoUrl } from '@/config/api'

const isDropdownOpen = ref(false)

const props = defineProps({
  organization: {
    type: Object,
    required: true,
    default: () => ({
      name: "اسم المنظمة",
      logo: "https://via.placeholder.com/150",
      location: "الموقع",
      field: "المجال",
      events_count: 0,
      members_count: 0,
      url: { name: 'Dashboard' },
    }),
  },
})

const emit = defineEmits(['delete', 'edit', 'view', 'select'])

function toggleDropdown() {
  isDropdownOpen.value = !isDropdownOpen.value
}

function closeDropdown() {
  isDropdownOpen.value = false
}

function editOrganization() {
  closeDropdown()
  emit('edit', props.organization)
  // TODO: Navigate to edit page or open edit modal
}

function viewDetails() {
  closeDropdown()
  emit('view', props.organization)
  // TODO: Navigate to organization details page
}

function confirmDelete() {
  closeDropdown()
  emit('delete', props.organization)
}
</script> 