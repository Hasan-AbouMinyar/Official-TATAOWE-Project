<template>
    <div class="relative min-h-screen lg:flex">
        <Sidebar />
        <HomeView />
    </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue';
import HomeView from './components/HomeView.vue';

export default {
    components: {
        Sidebar,
        HomeView,
    },
};
</script>