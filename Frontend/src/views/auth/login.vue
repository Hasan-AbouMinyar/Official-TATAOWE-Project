<template>
  <div class="min-h-screen bg-slate-50 font-sans flex items-center justify-center p-4">
    <div class="w-full max-w-3xl">
      <router-link :to="{ name: 'MyOrganizations' }" class="text-sm text-indigo-600 hover:underline mb-4 inline-block">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Organizations
      </router-link>
      <div class="bg-white shadow-lg rounded-xl p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-800">Create New Organization</h1>
        <p class="text-gray-500 mb-8">Fill in the details below to set up a new organization profile.</p>
        
        <form class="space-y-6" @submit.prevent="submit">
          

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
               <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                </div>
                <input v-model="form.email" id="email" type="email" placeholder="contact@example.com" class="w-full pl-10 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
              </div>
            </div>


          <!-- Address -->
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input v-model="form.address" id="address" type="text" placeholder="123 Main St, Anytown, USA" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Website -->
            <div>
              <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
              <input v-model="form.website" id="website" type="url" placeholder="https://example.com" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <!-- Field -->
            <div>
              <label for="field" class="block text-sm font-medium text-gray-700 mb-1">Field</label>
              <select v-model="form.field" id="field" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <option disabled value="">Please select one</option>
                <option>Technology</option>
                <option>Healthcare</option>
                <option>Finance</option>
                <option>Education</option>
                <option>Retail</option>
                <option>Other</option>
              </select>
            </div>
          </div>
          
          <!-- Photo File Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo (Logo)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
              <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                  <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <span>Upload a file</span>
                    <input @change="handleFileUpload" id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500" v-if="!form.photo">PNG, JPG, GIF up to 10MB</p>
                <p class="text-sm text-gray-600 font-medium" v-if="photoName">{{ photoName }}</p>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">Save Organization</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

// Initialize the router
const router = useRouter();

// Create a reactive form object
const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  website: '',
  field: '',
  photo: null,
  description: ''
});

// Ref to store the selected file's name for display
const photoName = ref('');

// Handle file upload
function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.photo = file;
    photoName.value = file.name;
  }
}

// Handle form submission
function submit() {
  // In a real application, you would use FormData to send 
  // the form data, including the file, to your API.
  console.log('Form submitted:', form);
  
  // Example of creating FormData
  // const formData = new FormData();
  // Object.keys(form).forEach(key => {
  //   formData.append(key, form[key]);
  // });
  
  // After successful submission, navigate back
  router.push({ name: 'MyOrganizations' });
}
</script>
