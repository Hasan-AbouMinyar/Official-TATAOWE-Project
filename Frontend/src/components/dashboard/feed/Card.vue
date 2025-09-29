<template>
  <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-lg">
    <header class="flex items-start gap-4">
      <!-- Organization Avatar -->
      <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center overflow-hidden">
        <img 
          v-if="event.organization?.photo" 
          :src="event.organization.photo" 
          :alt="event.organization.name"
          class="w-full h-full object-cover"
        />
        <span v-else class="text-white font-bold text-lg">
          {{ getInitials(event.organization?.name) }}
        </span>
      </div>
      
      <div class="flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <h3 class="text-base font-semibold text-gray-900">{{ event.organization?.name || 'Unknown Organization' }}</h3>
          <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Organization</span>
        </div>
        <p class="mt-0.5 text-xs text-gray-500">{{ formatDate(event.created_at) }} · Public</p>
      </div>
      
      <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
        </svg>
      </button>
    </header>
    
    <div class="mt-4 space-y-3">
      <h4 class="text-lg font-medium text-gray-900">{{ event.name }}</h4>
      <p class="text-sm leading-relaxed text-gray-600 line-clamp-3">{{ event.description || 'No description available.' }}</p>
      
      <!-- Event Details -->
      <div class="space-y-2 text-sm">
        <!-- Date & Time -->
        <div class="flex items-center gap-2 text-gray-600">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>{{ formatEventDate(event.start_time) }} - {{ formatEventDate(event.end_time) }}</span>
        </div>
        
        <!-- Location -->
        <div v-if="event.location" class="flex items-center gap-2 text-gray-600">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span>{{ event.location }}</span>
        </div>
      </div>
      
      <!-- Event Photo -->
      <div v-if="event.photo" class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
        <img :src="event.photo" :alt="event.name" class="w-full h-48 object-cover" />
      </div>
      
      <!-- Skills Tags -->
      <div v-if="skills.length > 0" class="flex flex-wrap gap-2 pt-1">
        <span 
          v-for="(skill, index) in skills" 
          :key="index"
          :class="getSkillColor(index)"
          class="text-xs font-medium rounded-full px-3 py-1"
        >
          #{{ skill }}
        </span>
      </div>
    </div>
    
    <footer class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
      <div class="flex items-center gap-4 text-xs">
        <button class="flex items-center gap-1.5 px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 9l-1-6H6a2 2 0 00-2 2v9h3l1 6h4l1-6h3l3-5h-7z" />
          </svg>
          <span>{{ event.applications?.length || 0 }}</span>
        </button>
        <button class="flex items-center gap-1.5 px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span>{{ event.users?.length || 0 }} Volunteers</span>
        </button>
      </div>
      <button class="bg-blue-600 px-3 py-1.5 text-xs font-medium text-white rounded-md hover:bg-blue-500">
        Apply Now
      </button>
    </footer>
  </article>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  event: {
    type: Object,
    required: true
  }
})

const skills = computed(() => {
  if (!props.event.requiredSkills) return []
  return props.event.requiredSkills.split(',').map(s => s.trim()).filter(Boolean)
})

function getInitials(name) {
  if (!name) return 'O'
  const words = name.split(' ')
  if (words.length >= 2) {
    return (words[0][0] + words[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

function formatDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date
  
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)
  
  if (minutes < 60) return `${minutes} minutes ago`
  if (hours < 24) return `${hours} hours ago`
  if (days < 7) return `${days} days ago`
  
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

function formatEventDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function getSkillColor(index) {
  const colors = [
    'bg-indigo-50 text-indigo-600',
    'bg-emerald-50 text-emerald-600',
    'bg-rose-50 text-rose-600',
    'bg-amber-50 text-amber-600',
    'bg-purple-50 text-purple-600',
    'bg-pink-50 text-pink-600',
    'bg-cyan-50 text-cyan-600',
    'bg-orange-50 text-orange-600'
  ]
  return colors[index % colors.length]
}
</script>
