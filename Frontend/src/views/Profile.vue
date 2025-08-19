<template>
  <section
    class="relative mx-auto my-8 max-w-4xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm ring-1 ring-gray-100/50 px-2 sm:px-0">
    <!-- Decorative gradient header -->
    <div class="h-28 w-full bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400" aria-hidden="true"></div>

    <!-- Removed decorative blur element for cleaner look and better performance -->

    <!-- Content wrapper -->
    <div class="px-6 pb-6 pt-0 sm:px-8">
      <!-- Avatar + name row -->
      <div class="flex flex-col gap-6 sm:flex-row sm:items-end">
        <div
          class="-mt-14 inline-flex items-center justify-center rounded-full bg-white p-1 shadow-lg ring-1 ring-black/5">
          <div class="relative h-32 w-32 rounded-full">
            <img v-if="organization.photo" :src="photoSrc" alt="Organization photo"
              class="h-full w-full rounded-full object-cover ring-2 ring-white" />
            <div v-else
              class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-slate-200 to-slate-300 text-4xl font-semibold text-slate-600 select-none">
              {{ initials }}
            </div>
            <span
              class="absolute -bottom-2 -right-2 inline-flex items-center gap-1 rounded-full bg-blue-600 px-2 py-1 text-[10px] font-medium uppercase tracking-wide text-white shadow ring-2 ring-white">
              Profile
            </span>
          </div>
        </div>

        <div class="flex-1 space-y-3">
          <div>
            <h1 class="text-3xl font-semibold tracking-tight text-gray-900">
              {{ organization.name || 'Unnamed Organization' }}
            </h1>
            <p v-if="organization.field"
              class="mt-1 inline-flex items-center gap-1 rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-200">
              {{ organization.field }}
            </p>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 pt-1">
            <button type="button"
              class="group inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/70">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4 transition-colors group-hover:text-white"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897l10.932-10.931Zm0 0L19.5 7.125" />
              </svg>
              Edit
            </button>
            <button type="button"
              class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/70">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-9-9v9m0 0 3-3m-3 3-3-3m9-6V5.25A2.25 2.25 0 0 0 16.5 3h-9A2.25 2.25 0 0 0 5.25 5.25V7.5" />
              </svg>
              Export
            </button>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="mt-8 h-px w-full bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

      <!-- Info grid -->
      <dl class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="flex gap-3">
          <div class="mt-0.5 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M21.75 7.5v9a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 16.5v-9m19.5 0A2.25 2.25 0 0 0 19.5 5.25h-15A2.25 2.25 0 0 0 2.25 7.5m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.688a2.25 2.25 0 0 1-2.36 0l-7.5-4.688A2.25 2.25 0 0 1 2.25 7.743V7.5" />
            </svg>
          </div>
          <div class="min-w-0">
            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email</dt>
            <dd class="mt-1 text-sm font-medium text-gray-900 break-all" v-if="organization.email">
              <a :href="`mailto:${organization.email}`" class="text-blue-600 hover:underline">{{ organization.email
                }}</a>
            </dd>
            <dd v-else class="mt-1 text-sm text-gray-400">Not set</dd>
          </div>
        </div>

        <div class="flex gap-3">
          <div class="mt-0.5 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.25 6.75c0 8.284 6.716 15 15 15h1.145a2.25 2.25 0 0 0 2.238-2.013l.37-3.081a2.25 2.25 0 0 0-1.282-2.278l-2.478-1.103a2.25 2.25 0 0 0-2.588.597l-.97 1.09a1.5 1.5 0 0 1-1.888.312 12.035 12.035 0 0 1-4.51-4.51 1.5 1.5 0 0 1 .312-1.888l1.09-.97a2.25 2.25 0 0 0 .597-2.588L9.622 3.78A2.25 2.25 0 0 0 7.344 2.5l-3.081.37A2.25 2.25 0 0 0 2.25 5.108V6.75Z" />
            </svg>
          </div>
          <div class="min-w-0">
            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</dt>
            <dd class="mt-1 text-sm font-medium text-gray-900" v-if="organization.phone">
              <a :href="`tel:${organization.phone}`" class="text-blue-600 hover:underline">{{ organization.phone }}</a>
            </dd>
            <dd v-else class="mt-1 text-sm text-gray-400">Not set</dd>
          </div>
        </div>

        <div class="flex gap-3">
          <div class="mt-0.5 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0c2.5 0 4.5-4 4.5-9S14.5 3 12 3m0 18c-2.5 0-4.5-4-4.5-9S9.5 3 12 3m-7.5 9h15" />
            </svg>
          </div>
          <div class="min-w-0">
            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Website</dt>
            <dd class="mt-1 text-sm font-medium text-gray-900 break-all" v-if="organization.website">
              <a :href="normalizedWebsite" target="_blank" rel="noopener" class="text-blue-600 hover:underline">{{
                organization.website }}</a>
            </dd>
            <dd v-else class="mt-1 text-sm text-gray-400">Not set</dd>
          </div>
        </div>

        <div class="flex gap-3 sm:col-span-2 lg:col-span-3">
          <div class="mt-0.5 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 0c0 7.142-9 11.25-9 11.25S3 17.642 3 10.5a9 9 0 1 1 18 0Z" />
            </svg>
          </div>
          <div class="flex-1">
            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Address</dt>
            <dd class="mt-1 text-sm font-medium text-gray-900" v-if="organization.address">{{ organization.address }}
            </dd>
            <dd v-else class="mt-1 text-sm text-gray-400">Not set</dd>
          </div>
        </div>
      </dl>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
// Props provide organization data (all optional)
const props = defineProps({
  organization: {
    type: Object,
    required: false,
    default: () => ({
      name: 'Example Organization',
      email: 'info@example.org',
      phone: '+1 (555) 123-4567',
      address: '123 Main Street, Sample City',
      website: 'https://example.org',
      field: 'Technology',
      photo: null
    })
  }
})

// Computed helpers
const photoSrc = computed(() => props.organization.photo)


const normalizedWebsite = computed(() => {
  const w = props.organization.website
  if (!w) return ''
  if (/^https?:\/\//i.test(w)) return w
  return 'https://' + w
})



const initials = computed(() => {
  const name = (props.organization.name || '').trim()
  if (!name) return '??'
  const parts = name.split(/\s+/).slice(0, 2)
  return parts.map(p => p[0]).join('').toUpperCase()
})
</script>

<style scoped>
/* Optional custom scrollbar or future overrides */
</style>