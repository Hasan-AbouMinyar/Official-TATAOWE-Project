<template>
  <div 
    :class="[
      'overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center',
      sizeClasses,
      rounded ? 'rounded-full' : 'rounded-lg',
      bordered ? 'ring-4 ring-white shadow-xl' : shadow ? 'shadow-md' : ''
    ]"
    :style="{ backgroundColor: backgroundColor }"
  >
    <!-- Loading Overlay -->
    <div 
      v-if="loading" 
      class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-10"
    >
      <svg class="w-1/3 h-1/3 text-white animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>
    
    <!-- Photo -->
    <img 
      v-if="photoUrl && !imageError" 
      :src="photoUrl" 
      :alt="name || 'Avatar'"
      class="w-full h-full object-cover"
      @error="handleImageError"
      @load="handleImageLoad"
    />
    
    <!-- Initials Fallback -->
    <span 
      v-else 
      :class="[
        'font-bold select-none',
        textSizeClasses,
        textColor || 'text-white'
      ]"
    >
      {{ initials }}
    </span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { getPhotoUrl } from '../config/api'

const props = defineProps({
  photo: {
    type: String,
    default: ''
  },
  name: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'md', // xs, sm, md, lg, xl, 2xl
    validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl', '2xl'].includes(value)
  },
  rounded: {
    type: Boolean,
    default: true
  },
  bordered: {
    type: Boolean,
    default: false
  },
  shadow: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  backgroundColor: {
    type: String,
    default: ''
  },
  textColor: {
    type: String,
    default: ''
  }
})

const imageError = ref(false)

const photoUrl = computed(() => {
  if (!props.photo) return ''
  return getPhotoUrl(props.photo)
})

const initials = computed(() => {
  if (!props.name) return 'U'
  const names = props.name.trim().split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[1][0]).toUpperCase()
  }
  return props.name.substring(0, 2).toUpperCase()
})

const sizeClasses = computed(() => {
  const sizes = {
    'xs': 'w-6 h-6',
    'sm': 'w-8 h-8',
    'md': 'w-10 h-10',
    'lg': 'w-16 h-16',
    'xl': 'w-24 h-24',
    '2xl': 'w-32 h-32'
  }
  return sizes[props.size] || sizes.md
})

const textSizeClasses = computed(() => {
  const sizes = {
    'xs': 'text-xs',
    'sm': 'text-sm',
    'md': 'text-lg',
    'lg': 'text-2xl',
    'xl': 'text-3xl',
    '2xl': 'text-4xl'
  }
  return sizes[props.size] || sizes.md
})

function handleImageError() {
  imageError.value = true
  console.warn('Failed to load avatar image:', photoUrl.value)
}

function handleImageLoad() {
  imageError.value = false
}
</script>
