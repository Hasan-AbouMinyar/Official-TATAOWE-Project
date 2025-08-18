<template>
  <div class="mt-6 px-6 2xl:px-10">
    <div class="mx-auto flex max-w-7xl gap-8">
      <!-- Feed column -->
      <div class="flex-1 min-w-0">
        <!-- Composer placeholder -->
        <div class="mb-6 rounded-2xl border border-dashed border-gray-300 bg-white/60 p-5 shadow-sm">
          <p class="text-sm text-gray-500">Post composer will go here...</p>
        </div>
        <!-- Example cards list -->
        <div class="space-y-6">
          <Card />
          <Card />
          <Card />
        </div>
        <!-- Load more -->
        <div class="mt-8 flex justify-center">
          <button class="rounded-full bg-gray-200 px-5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-300">Load more</button>
        </div>
      </div>

      <!-- Right rail -->
      <RightRail />
    </div>
  </div>
</template>

<script setup>
import Card from './Card.vue';
import RightRail from './RightRail.vue';
</script>
