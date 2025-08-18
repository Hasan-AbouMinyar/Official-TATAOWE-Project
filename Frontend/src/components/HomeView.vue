<template>
  <main id="content" class="flex-1 pb-12 overflow-y-auto bg-gray-100 lg:h-screen">
    <Navigation />
    <DashboardHeader />
    <Feed />
  </main>
</template>

<script setup>
import Navigation from './Navigation.vue';
import DashboardHeader from './DashboardHeader.vue';
import Feed from './Feed.vue';
</script>
