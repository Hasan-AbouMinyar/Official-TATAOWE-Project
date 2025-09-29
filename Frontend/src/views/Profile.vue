<template>
  <div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
      <p class="mt-1 text-sm text-gray-600">Manage your account information and settings</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Profile Card -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <!-- Cover Image -->
          <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>
          
          <!-- Profile Info -->
          <div class="relative px-6 pb-6">
            <!-- Avatar -->
            <div class="flex justify-center -mt-16 mb-4">
              <div class="relative">
                <div class="w-32 h-32 rounded-full border-4 border-white bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center overflow-hidden shadow-lg">
                  <img 
                    v-if="user?.photo" 
                    :src="user.photo" 
                    :alt="user.name"
                    class="w-full h-full object-cover"
                  />
                  <span v-else class="text-white font-bold text-4xl">
                    {{ getUserInitials(user?.name) }}
                  </span>
                </div>
                <button 
                  @click="triggerFileInput"
                  class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition-colors shadow-lg"
                  title="Change photo"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
                <input 
                  ref="fileInput"
                  type="file"
                  accept="image/*"
                  class="hidden"
                  @change="handleFileChange"
                />
              </div>
            </div>

            <!-- User Info -->
            <div class="text-center">
              <h2 class="text-2xl font-bold text-gray-900">{{ user?.name }}</h2>
              <p class="text-gray-600 mt-1">@{{ user?.username }}</p>
              <div class="flex items-center justify-center mt-2 text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ user?.email }}
              </div>
              <div class="flex items-center justify-center mt-1 text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                {{ user?.phoneNumber }}
              </div>
            </div>

            <!-- Stats -->
                          <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100">
                <div class="text-center">
                  <div class="text-2xl font-bold text-gray-900">{{ user?.stats?.events_count || 0 }}</div>
                  <div class="text-xs text-gray-600">Events</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-gray-900">{{ user?.stats?.applications_count || 0 }}</div>
                  <div class="text-xs text-gray-600">Applications</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-gray-900">{{ user?.stats?.organizations_count || 0 }}</div>
                  <div class="text-xs text-gray-600">Organizations</div>
                </div>
              </div>
          </div>
        </div>
      </div>

      <!-- Profile Details & Edit Form -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
            <button 
              v-if="!isEditing"
              @click="startEditing"
              class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Profile
            </button>
          </div>

          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Full Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input 
                  v-model="formData.name"
                  :disabled="!isEditing"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500"
                  placeholder="John Doe"
                />
              </div>

              <!-- Username -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input 
                  v-model="formData.username"
                  :disabled="!isEditing"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500"
                  placeholder="johndoe"
                />
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input 
                  v-model="formData.email"
                  :disabled="!isEditing"
                  type="email"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500"
                  placeholder="john@example.com"
                />
              </div>

              <!-- Phone Number -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input 
                  v-model="formData.phoneNumber"
                  :disabled="!isEditing"
                  type="tel"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500"
                  placeholder="+1234567890"
                />
              </div>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="successMessage" class="p-4 bg-green-50 border border-green-200 rounded-lg">
              <p class="text-sm text-green-800">{{ successMessage }}</p>
            </div>
            <div v-if="errorMessage" class="p-4 bg-red-50 border border-red-200 rounded-lg">
              <p class="text-sm text-red-800">{{ errorMessage }}</p>
            </div>

            <!-- Action Buttons -->
            <div v-if="isEditing" class="flex items-center gap-3 pt-4 border-t">
              <button 
                type="submit"
                :disabled="loading"
                class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                <span v-if="loading">Saving...</span>
                <span v-else>Save Changes</span>
              </button>
              <button 
                type="button"
                @click="cancelEditing"
                :disabled="loading"
                class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>

        <!-- Account Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <h3 class="text-xl font-semibold text-gray-900 mb-6">Account Settings</h3>
          
          <div class="space-y-4">
            <!-- Change Password -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-medium text-gray-900">Change Password</h4>
                  <p class="text-sm text-gray-600">Update your password regularly</p>
                </div>
              </div>
              <button class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                Change
              </button>
            </div>

            <!-- Email Verification -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-medium text-gray-900">Email Verification</h4>
                  <p class="text-sm text-gray-600">
                    <span v-if="user?.email_verified_at" class="text-green-600">Verified</span>
                    <span v-else class="text-orange-600">Not verified</span>
                  </p>
                </div>
              </div>
              <button v-if="!user?.email_verified_at" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                Verify
              </button>
            </div>

            <!-- Two-Factor Authentication -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-medium text-gray-900">Two-Factor Authentication</h4>
                  <p class="text-sm text-gray-600">Add an extra layer of security</p>
                </div>
              </div>
              <button class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                Enable
              </button>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
          <h3 class="text-xl font-semibold text-red-600 mb-4">Danger Zone</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
              <div>
                <h4 class="font-medium text-gray-900">Delete Account</h4>
                <p class="text-sm text-gray-600">Permanently delete your account and all data</p>
              </div>
              <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const user = computed(() => authStore.user)

const isEditing = ref(false)
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const fileInput = ref(null)

const formData = ref({
  name: '',
  username: '',
  email: '',
  phoneNumber: ''
})

onMounted(async () => {
  // Fetch latest user data with statistics
  await authStore.fetchUser()
  
  if (user.value) {
    formData.value = {
      name: user.value.name || '',
      username: user.value.username || '',
      email: user.value.email || '',
      phoneNumber: user.value.phoneNumber || ''
    }
  }
})

function getUserInitials(name) {
  if (!name) return 'U'
  const names = name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

function startEditing() {
  isEditing.value = true
  successMessage.value = ''
  errorMessage.value = ''
}

function cancelEditing() {
  isEditing.value = false
  // Reset form data
  if (user.value) {
    formData.value = {
      name: user.value.name || '',
      username: user.value.username || '',
      email: user.value.email || '',
      phoneNumber: user.value.phoneNumber || ''
    }
  }
  successMessage.value = ''
  errorMessage.value = ''
}

async function handleSubmit() {
  try {
    loading.value = true
    errorMessage.value = ''
    successMessage.value = ''

    await authStore.updateProfile(formData.value)
    
    successMessage.value = 'Profile updated successfully!'
    isEditing.value = false
    
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error) {
    console.error('Update profile failed:', error)
    errorMessage.value = error.response?.data?.message || 'Failed to update profile. Please try again.'
  } finally {
    loading.value = false
  }
}

function triggerFileInput() {
  fileInput.value?.click()
}

function handleFileChange(event) {
  const file = event.target.files?.[0]
  if (file) {
    // Here you would typically upload the file
    console.log('Selected file:', file)
    // TODO: Implement file upload
  }
}
</script>