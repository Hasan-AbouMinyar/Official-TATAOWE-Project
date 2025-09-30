import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: () => import('@/views/Dashboard/Index.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/orglist',
        name: 'MyOrganizations',
        component: () => import('@/views/Organization/MyOrganizations.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/orglist/create',
        name: 'OrganizationCreate',
        component: () => import('@/views/Organization/Create.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/organization/:id',
        name: 'OrganizationProfile',
        component: () => import('@/views/Organization/Profile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/organization/:id/edit',
        name: 'OrganizationEdit',
        component: () => import('@/views/Organization/Edit.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/events/create',
        name: 'EventCreate',
        component: () => import('@/views/Event/Create.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/events/:id',
        name: 'EventDetails',
        component: () => import('@/views/Event/Details.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/events/:id/edit',
        name: 'EditEvent',
        component: () => import('@/views/Event/Edit.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/events/:id/applications',
        name: 'EventApplications',
        component: () => import('@/views/Event/Applications.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/organization/applications',
        name: 'OrganizationApplications',
        component: () => import('@/views/Organization/AllApplications.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('@/views/Profile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/users/:id',
        name: 'UserProfile',
        component: () => import('@/views/User/UserProfile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/my-applications',
        name: 'MyApplications',
        component: () => import('@/views/MyApplications.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/notifications',
        name: 'Notifications',
        component: () => import('@/views/Notifications.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/search',
        name: 'SearchResults',
        component: () => import('@/views/SearchResults.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('@/views/auth/login.vue'),
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('@/views/auth/Register.vue'),
        meta: { guest: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token')
    const isAuthenticated = !!token

    // Check if route requires authentication
    if (to.meta.requiresAuth && !isAuthenticated) {
        // Redirect to login if not authenticated
        next({ name: 'Login', query: { redirect: to.fullPath } })
    } 
    // Check if route is for guests only (login, register)
    else if (to.meta.guest && isAuthenticated) {
        // Redirect to dashboard if already authenticated
        next({ name: 'Dashboard' })
    } 
    else {
        // Allow navigation
        next()
    }
})

export default router