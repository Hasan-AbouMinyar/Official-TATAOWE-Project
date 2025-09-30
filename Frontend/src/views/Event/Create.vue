<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6">
        <button 
          @click="goBack"
          class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Dashboard
        </button>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
            <p class="text-gray-600">Publish a new volunteering opportunity</p>
          </div>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
        <!-- Event Photo Upload -->
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6">
          <div class="text-center">
            <!-- Photo Preview -->
            <div v-if="photoPreview" class="mb-4">
              <div class="relative inline-block">
                <img 
                  :src="photoPreview" 
                  alt="Event preview"
                  class="w-full max-w-md h-64 object-cover rounded-xl shadow-lg mx-auto"
                />
                <button
                  type="button"
                  @click="removePhoto"
                  class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 shadow-lg"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Upload Icon -->
            <div v-else class="mb-4">
              <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>

            <label class="cursor-pointer inline-block">
              <span class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-block">
                {{ form.photo ? 'Change Photo' : 'Upload Event Photo' }}
              </span>
              <input 
                type="file" 
                accept="image/*"
                @change="handlePhotoChange"
                class="hidden"
                :disabled="submitting"
              />
            </label>
            <p class="text-sm text-gray-500 mt-2">PNG, JPG or GIF (MAX. 10MB)</p>
          </div>
        </div>

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Event Name -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Event Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
              placeholder="e.g., Beach Cleanup Campaign"
            />
          </div>

          <!-- Start Date & Time -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Start Date & Time <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.start_time"
              type="datetime-local"
              required
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
            />
          </div>

          <!-- End Date & Time -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              End Date & Time <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.end_time"
              type="datetime-local"
              required
              :disabled="submitting"
              :min="form.start_time"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
            />
          </div>

          <!-- Location -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Location <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.location"
              type="text"
              required
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
              placeholder="e.g., Tripoli Beach, Libya"
            />
          </div>

          <!-- Description -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.description"
              rows="5"
              required
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
              placeholder="Describe the event, its goals, and what volunteers will do..."
            ></textarea>
            <p class="text-sm text-gray-500 mt-1">{{ form.description?.length || 0 }} characters</p>
          </div>

          <!-- Required Skills -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Required Skills
            </label>
            <input
              v-model="form.requiredSkills"
              type="text"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100"
              placeholder="e.g., Communication, Teamwork, Environmental Awareness"
            />
            <p class="text-sm text-gray-500 mt-1">Separate skills with commas</p>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p class="text-red-700">{{ error }}</p>
          </div>
        </div>

        <!-- Success Message -->
        <div v-if="success" class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-green-700">Event created successfully!</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t">
          <button
            type="button"
            @click="goBack"
            :disabled="submitting"
            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="submitting"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
          >
            <svg v-if="submitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ submitting ? 'Creating...' : 'Create Event' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useOrganizationStore } from '@/stores/organization'
import api from '@/api'

const router = useRouter()
const organizationStore = useOrganizationStore()

const submitting = ref(false)
const error = ref(null)
const success = ref(false)
const photoPreview = ref(null)

const form = ref({
  name: '',
  description: '',
  start_time: '',
  end_time: '',
  location: '',
  requiredSkills: '',
  photo: null
})

function handlePhotoChange(event) {
  const file = event.target.files[0]
  
  if (!file) return

  // Validate file size (10MB max)
  if (file.size > 10 * 1024 * 1024) {
    error.value = 'File size must be less than 10MB'
    return
  }

  // Validate file type
  if (!file.type.startsWith('image/')) {
    error.value = 'Please select an image file'
    return
  }

  form.value.photo = file
  
  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    photoPreview.value = e.target.result
  }
  reader.readAsDataURL(file)
  
  error.value = null
}

function removePhoto() {
  form.value.photo = null
  photoPreview.value = null
}

async function handleSubmit() {
  try {
    submitting.value = true
    error.value = null
    success.value = false

    // Validate dates
    if (new Date(form.value.start_time) >= new Date(form.value.end_time)) {
      error.value = 'End date must be after start date'
      return
    }

    // Get organization ID
    const organizationId = organizationStore.organizationId
    if (!organizationId) {
      error.value = 'No organization selected. Please switch to organization mode first.'
      return
    }

    // Create FormData
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('description', form.value.description)
    formData.append('start_time', form.value.start_time)
    formData.append('end_time', form.value.end_time)
    formData.append('location', form.value.location)
    formData.append('organization_id', organizationId)
    
    if (form.value.requiredSkills) {
      formData.append('requiredSkills', form.value.requiredSkills)
    }
    
    if (form.value.photo) {
      formData.append('photo', form.value.photo)
    }

    // Submit to API
    await api.events.create(formData)
    
    success.value = true
    
    // Redirect after 1.5 seconds
    setTimeout(() => {
      router.push({ name: 'Dashboard' })
    }, 1500)
  } catch (err) {
    console.error('Failed to create event:', err)
    error.value = err.response?.data?.message || 'Failed to create event. Please try again.'
  } finally {
    submitting.value = false
  }
}

function goBack() {
  router.back()
}
</script>
