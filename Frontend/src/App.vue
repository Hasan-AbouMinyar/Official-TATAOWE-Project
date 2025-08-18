<template>
    <div class="relative min-h-screen lg:flex">
        <Sidebar />
        <main id="content" class="flex-1 pb-12 overflow-y-auto bg-gray-100 lg:h-screen">
            <Navigation />
            <router-view />
        </main>
    </div>
</template>

<script>
import Navigation from './components/layout/Navigation.vue';
import Sidebar from './components/layout/Sidebar.vue';

export default { components: { Sidebar, Navigation } };
</script>