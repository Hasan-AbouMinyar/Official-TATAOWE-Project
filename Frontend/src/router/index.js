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
        component: () => import('@/views/Organization/MyOrganizations.vue')
    },
    {
        path: '/orglist/create',
        name: 'OrganizationCreate',
        component: () => import('@/views/Organization/Create.vue')
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('@/views/Profile.vue')
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import ('@/views/auth/login.vue')
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;