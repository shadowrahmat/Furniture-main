import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'

const routes = [
    // Frontend routes
    {
        path: '/',
        component: () => import('../layouts/FrontLayout.vue'),
        children: [
            { path: '', name: 'home', component: () => import('../pages/HomePage.vue') },
            { path: 'shop', name: 'shop', component: () => import('../pages/ShopPage.vue') },
            { path: 'shop/:category', name: 'shop-category', component: () => import('../pages/ShopPage.vue') },
            { path: 'product/:slug', name: 'product', component: () => import('../pages/ProductPage.vue') },
            { path: 'cart', name: 'cart', component: () => import('../pages/CartPage.vue') },
            { path: 'checkout', name: 'checkout', component: () => import('../pages/CheckoutPage.vue') },
            { path: 'wishlist', name: 'wishlist', component: () => import('../pages/WishlistPage.vue'), meta: { requiresAuth: true } },
            { path: 'order-success/:orderNumber', name: 'order-success', component: () => import('../pages/OrderSuccessPage.vue') },
            { path: 'track-order', name: 'track-order', component: () => import('../pages/TrackOrderPage.vue') },
            { path: 'about', name: 'about', component: () => import('../pages/AboutPage.vue') },
            { path: 'contact', name: 'contact', component: () => import('../pages/ContactPage.vue') },
            { path: 'custom-furniture', name: 'custom-furniture', component: () => import('../pages/CustomFurniturePage.vue') },
            { path: 'faq', name: 'faq', component: () => import('../pages/FaqPage.vue') },
            { path: 'privacy-policy', name: 'privacy', component: () => import('../pages/PrivacyPage.vue') },
            { path: 'terms', name: 'terms', component: () => import('../pages/TermsPage.vue') },
        ],
    },

    // Auth routes
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/auth/LoginPage.vue'),
        meta: { guestOnly: true },
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../pages/auth/RegisterPage.vue'),
        meta: { guestOnly: true },
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('../pages/auth/ForgotPasswordPage.vue'),
        meta: { guestOnly: true },
    },

    // Account routes
    {
        path: '/account',
        component: () => import('../layouts/AccountLayout.vue'),
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'account', component: () => import('../pages/account/DashboardPage.vue') },
            { path: 'orders', name: 'account-orders', component: () => import('../pages/account/OrdersPage.vue') },
            { path: 'orders/:id', name: 'account-order', component: () => import('../pages/account/OrderDetailPage.vue') },
            { path: 'profile', name: 'account-profile', component: () => import('../pages/account/ProfilePage.vue') },
            { path: 'addresses', name: 'account-addresses', component: () => import('../pages/account/AddressesPage.vue') },
        ],
    },

    // Admin routes
    {
        path: '/admin',
        component: () => import('../layouts/AdminLayout.vue'),
        meta: { requiresAdmin: true },
        children: [
            { path: '', name: 'admin', redirect: '/admin/dashboard' },
            { path: 'dashboard', name: 'admin-dashboard', component: () => import('../pages/admin/DashboardPage.vue') },
            { path: 'products', name: 'admin-products', component: () => import('../pages/admin/ProductsPage.vue') },
            { path: 'products/create', name: 'admin-product-create', component: () => import('../pages/admin/ProductFormPage.vue') },
            { path: 'products/:id/edit', name: 'admin-product-edit', component: () => import('../pages/admin/ProductFormPage.vue') },
            { path: 'categories', name: 'admin-categories', component: () => import('../pages/admin/CategoriesPage.vue') },
            { path: 'orders', name: 'admin-orders', component: () => import('../pages/admin/OrdersPage.vue') },
            { path: 'orders/:id', name: 'admin-order', component: () => import('../pages/admin/OrderDetailPage.vue') },
            { path: 'customers', name: 'admin-customers', component: () => import('../pages/admin/CustomersPage.vue') },
            { path: 'coupons', name: 'admin-coupons', component: () => import('../pages/admin/CouponsPage.vue') },
            { path: 'banners', name: 'admin-banners', component: () => import('../pages/admin/BannersPage.vue') },
            { path: 'reviews', name: 'admin-reviews', component: () => import('../pages/admin/ReviewsPage.vue') },
            { path: 'inquiries', name: 'admin-inquiries', component: () => import('../pages/admin/InquiriesPage.vue') },
            { path: 'messages', name: 'admin-messages', component: () => import('../pages/admin/MessagesPage.vue') },
            { path: 'settings', name: 'admin-settings', component: () => import('../pages/admin/SettingsPage.vue') },
        ],
    },

    // 404
    { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('../pages/NotFoundPage.vue') },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition
        return { top: 0, behavior: 'smooth' }
    },
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login', query: { redirect: to.fullPath } })
    } else if (to.meta.requiresAdmin && (!authStore.isAuthenticated || !authStore.isAdmin)) {
        next({ name: 'login' })
    } else if (to.meta.guestOnly && authStore.isAuthenticated) {
        next({ name: 'home' })
    } else {
        next()
    }
})

export default router
