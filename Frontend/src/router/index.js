import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: () => import('@/views/Dashboard/Index.vue')
    },
    {
        path: '/orglist',
        name: 'MyOrganizations',
        component: () => import('@/views/MyOrganizations.vue')
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;