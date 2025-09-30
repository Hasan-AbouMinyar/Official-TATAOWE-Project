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
          Back to Profile
        </button>
        <h1 class="text-3xl font-bold text-gray-900">Edit Organization</h1>
        <p class="text-gray-600 mt-2">Update your organization information</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="bg-white rounded-2xl shadow-sm p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading organization data...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
        <p class="text-red-600">{{ error }}</p>
        <button @click="loadOrganization" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
          Try Again
        </button>
      </div>

      <!-- Edit Form -->
      <form v-else-if="form" @submit.prevent="handleSubmit" class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
        <!-- Logo Upload Section -->
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
          <div class="mb-4">
            <!-- Current Logo Preview -->
            <div v-if="logoPreview || form.logo" class="mb-4 inline-block">
              <div class="relative w-32 h-32 mx-auto">
                <img 
                  :src="logoPreview || form.logo" 
                  alt="Organization logo"
                  class="w-full h-full object-cover rounded-xl border-4 border-white shadow-lg"
                />
                <button
                  type="button"
                  @click="removeLogo"
                  class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-lg"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Upload Button -->
            <div v-else class="mb-4">
              <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
            </div>
          </div>

          <label class="cursor-pointer inline-block">
            <span class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-block">
              {{ form.photo ? 'Change Logo' : 'Upload Logo' }}
            </span>
            <input 
              type="file" 
              accept="image/*"
              @change="handleFileChange"
              class="hidden"
              :disabled="submitting"
            />
          </label>
          <p class="text-sm text-gray-500 mt-2">PNG, JPG or GIF (MAX. 10MB)</p>
        </div>

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Organization Name -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Organization Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="Enter organization name"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Email <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="organization@example.com"
            />
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Phone Number
            </label>
            <input
              v-model="form.phone"
              type="tel"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="+218 91 234 5678"
            />
          </div>

          <!-- Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Field/Sector
            </label>
            <input
              v-model="form.field"
              type="text"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="e.g., Education, Healthcare, Environment"
            />
          </div>

          <!-- Location -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Location/City
            </label>
            <input
              v-model="form.location"
              type="text"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="e.g., Tripoli, Benghazi"
            />
          </div>

          <!-- Website -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Website
            </label>
            <input
              v-model="form.website"
              type="url"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="https://www.example.com"
            />
          </div>

          <!-- Address -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Full Address
            </label>
            <input
              v-model="form.address"
              type="text"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="Street, Building, Floor"
            />
          </div>

          <!-- Description -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Description
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              :disabled="submitting"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="Describe your organization's mission and activities..."
            ></textarea>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="submitError" class="bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p class="text-red-700">{{ submitError }}</p>
          </div>
        </div>

        <!-- Success Message -->
        <div v-if="submitSuccess" class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-green-700">Organization updated successfully!</p>
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
            {{ submitting ? 'Updating...' : 'Update Organization' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const submitting = ref(false)
const error = ref(null)
const submitError = ref(null)
const submitSuccess = ref(false)

const form = ref(null)
const logoPreview = ref(null)
const originalLogo = ref(null)

async function loadOrganization() {
  try {
    loading.value = true
    error.value = null

    const orgId = route.params.id
    const response = await api.organizations.getById(orgId)
    
    const org = response.data
    
    // Store original logo
    originalLogo.value = org.logo_url || (org.logo ? `http://localhost:8000/storage/${org.logo}` : null)
    
    // Initialize form with organization data
    form.value = {
      name: org.name || '',
      email: org.email || '',
      phone: org.phone || '',
      address: org.address || '',
      location: org.location || '',
      website: org.website || '',
      field: org.field || '',
      description: org.description || '',
      logo: originalLogo.value,
      photo: null // For new file upload
    }
  } catch (err) {
    console.error('Failed to load organization:', err)
    error.value = 'Failed to load organization details. Please try again.'
  } finally {
    loading.value = false
  }
}

function handleFileChange(event) {
  const file = event.target.files[0]
  
  if (!file) return

  // Validate file size (10MB max)
  if (file.size > 10 * 1024 * 1024) {
    submitError.value = 'File size must be less than 10MB'
    return
  }

  // Validate file type
  if (!file.type.startsWith('image/')) {
    submitError.value = 'Please select an image file'
    return
  }

  form.value.photo = file
  
  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    logoPreview.value = e.target.result
  }
  reader.readAsDataURL(file)
  
  submitError.value = null
}

function removeLogo() {
  form.value.photo = null
  logoPreview.value = null
  form.value.logo = null
}

async function handleSubmit() {
  try {
    submitting.value = true
    submitError.value = null
    submitSuccess.value = false

    const orgId = route.params.id

    // Create FormData for file upload
    const formData = new FormData()
    
    // Add all form fields
    formData.append('name', form.value.name)
    formData.append('email', form.value.email)
    
    if (form.value.phone) formData.append('phone', form.value.phone)
    if (form.value.address) formData.append('address', form.value.address)
    if (form.value.location) formData.append('location', form.value.location)
    if (form.value.website) formData.append('website', form.value.website)
    if (form.value.field) formData.append('field', form.value.field)
    if (form.value.description) formData.append('description', form.value.description)
    
    // Add logo if new file selected
    if (form.value.photo) {
      formData.append('logo', form.value.photo)
    }

    // Update organization
    await api.organizations.update(orgId, formData)
    
    submitSuccess.value = true
    
    // Redirect after 2 seconds
    setTimeout(() => {
      router.push({ name: 'OrganizationProfile', params: { id: orgId } })
    }, 2000)
  } catch (err) {
    console.error('Failed to update organization:', err)
    submitError.value = err.response?.data?.message || 'Failed to update organization. Please try again.'
  } finally {
    submitting.value = false
  }
}

function goBack() {
  router.back()
}

onMounted(() => {
  loadOrganization()
})
</script>
