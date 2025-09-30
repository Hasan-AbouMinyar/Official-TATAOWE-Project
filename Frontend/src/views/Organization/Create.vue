<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 font-sans flex items-center justify-center p-4">
    <div class="w-full max-w-3xl">
      <router-link :to="{ name: 'MyOrganizations' }" class="text-sm text-indigo-600 hover:text-indigo-800 mb-4 inline-flex items-center gap-2 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="font-medium">Back to Organizations</span>
      </router-link>
      
      <div class="bg-white shadow-2xl rounded-2xl p-6 md:p-8 border border-gray-100">
        <!-- Header -->
        <div class="mb-8 text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h1 class="text-3xl font-bold mb-2 text-gray-800">Create New Organization</h1>
          <p class="text-gray-500">Fill in the details below to set up your organization profile</p>
        </div>
        
        <!-- Error Message -->
        <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 rounded-lg flex items-start gap-3">
          <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <div class="flex-1">
            <p class="text-red-800 font-medium">{{ errorMessage }}</p>
          </div>
        </div>
        
        <form class="space-y-6" @submit.prevent="submit">
          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
              Organization Name <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
              </div>
              <input 
                v-model="form.name" 
                id="name" 
                type="text" 
                placeholder="Your Organization Name" 
                class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                required
                :disabled="isSubmitting"
              >
            </div>
          </div>
          
          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="form.description" 
              id="description" 
              rows="4" 
              placeholder="Tell us about your organization..." 
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              :disabled="isSubmitting"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
               <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                </div>
                <input 
                  v-model="form.email" 
                  id="email" 
                  type="email" 
                  placeholder="contact@example.com" 
                  class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                  required
                  :disabled="isSubmitting"
                >
              </div>
            </div>

            <!-- Phone -->
            <div>
              <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                   <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                  </svg>
                </div>
                <input 
                  v-model="form.phone" 
                  id="phone" 
                  type="tel" 
                  placeholder="+966 50 123 4567" 
                  class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  :disabled="isSubmitting"
                >
              </div>
            </div>
          </div>

          <!-- Address -->
          <div>
            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
            <input 
              v-model="form.address" 
              id="address" 
              type="text" 
              placeholder="123 Main St, City, Country" 
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              :disabled="isSubmitting"
            >
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Website -->
            <div>
              <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
              <input 
                v-model="form.website" 
                id="website" 
                type="url" 
                placeholder="https://example.com" 
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                :disabled="isSubmitting"
              >
            </div>

            <!-- Field -->
            <div>
              <label for="field" class="block text-sm font-semibold text-gray-700 mb-2">Field</label>
              <select 
                v-model="form.field" 
                id="field" 
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                :disabled="isSubmitting"
              >
                <option disabled value="">Select organization field</option>
                <option>Technology</option>
                <option>Healthcare</option>
                <option>Finance</option>
                <option>Education</option>
                <option>Environment</option>
                <option>Social Services</option>
                <option>Other</option>
              </select>
            </div>
          </div>
          
          <!-- Photo File Input -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Organization Logo</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
              <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600 justify-center">
                  <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <span>Upload a file</span>
                    <input 
                      @change="handleFileUpload" 
                      id="photo" 
                      name="photo" 
                      type="file" 
                      class="sr-only" 
                      accept="image/*"
                      :disabled="isSubmitting"
                    >
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500" v-if="!form.photo">PNG, JPG, GIF up to 10MB</p>
                <p class="text-sm text-blue-600 font-medium" v-if="photoName">✓ {{ photoName }}</p>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
              <svg v-if="isSubmitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ isSubmitting ? 'Saving...' : 'Create Organization' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/api'

// Initialize the router and auth store
const router = useRouter()
const authStore = useAuthStore()

// Loading and error states
const isSubmitting = ref(false)
const errorMessage = ref('')

// Create a reactive form object
const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  website: '',
  field: '',
  photo: null,
  description: '',
  location: '' // Add location field
})

// Ref to store the selected file's name for display
const photoName = ref('')

// Handle file upload
function handleFileUpload(event) {
  const file = event.target.files[0]
  if (file) {
    // Validate file size (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
      errorMessage.value = 'File size is too large. Maximum size is 10MB'
      return
    }
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
      errorMessage.value = 'Please select an image file only'
      return
    }
    
    form.photo = file
    photoName.value = file.name
    errorMessage.value = ''
  }
}

// Handle form submission
async function submit() {
  try {
    isSubmitting.value = true
    errorMessage.value = ''
    
    // Validate required fields
    if (!form.name || !form.email) {
      errorMessage.value = 'Please fill in all required fields'
      return
    }
    
    // Create FormData for file upload
    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('email', form.email)
    formData.append('description', form.description || '')
    formData.append('phone', form.phone || '')
    formData.append('address', form.address || '')
    formData.append('website', form.website || '')
    formData.append('field', form.field || '')
    formData.append('location', form.address || '') // Use address as location
    formData.append('user_id', authStore.user.id)
    
    // Append photo if exists
    if (form.photo) {
      formData.append('logo', form.photo)
    }
    
    // Send to API with proper headers for multipart/form-data
    const response = await api.organizations.create(formData)
    
    console.log('Organization created successfully:', response.data)
    
    // Show success message
    alert('✅ Organization created successfully!')
    
    // Navigate back to organizations list
    router.push({ name: 'MyOrganizations' })
    
  } catch (error) {
    console.error('Error creating organization:', error)
    errorMessage.value = error.response?.data?.message || 'An error occurred while creating the organization. Please try again'
  } finally {
    isSubmitting.value = false
  }
}
</script>
