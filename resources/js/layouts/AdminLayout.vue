<template>
    <div class="min-h-screen flex" style="background:#F5F5F0">
        <!-- Sidebar -->
        <aside :class="['fixed top-0 left-0 h-full z-40 transition-all duration-300 flex flex-col', sidebarOpen ? 'w-64' : 'w-16']"
            style="background:#1E1B18">
            <!-- Logo -->
            <div class="flex items-center h-16 px-4 border-b" style="border-color:rgba(255,255,255,0.1)">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background:linear-gradient(135deg,#8B5E3C,#C9A66B)">
                    <span class="text-white font-bold">F</span>
                </div>
                <Transition name="fade">
                    <span v-if="sidebarOpen" class="font-heading font-bold text-white ml-2 whitespace-nowrap">Admin Panel</span>
                </Transition>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto py-4">
                <div v-for="section in navSections" :key="section.label" class="mb-4">
                    <Transition name="fade">
                        <p v-if="sidebarOpen" class="px-4 py-1 text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,0.3)">{{ section.label }}</p>
                    </Transition>
                    <RouterLink v-for="item in section.items" :key="item.to" :to="item.to"
                        class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg transition-all text-sm font-medium"
                        :class="$route.path.startsWith(item.to) ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                        :style="$route.path.startsWith(item.to) ? 'background:rgba(201,166,107,0.2)' : ''">
                        <span class="text-lg flex-shrink-0">{{ item.icon }}</span>
                        <Transition name="fade">
                            <span v-if="sidebarOpen" class="whitespace-nowrap">{{ item.label }}</span>
                        </Transition>
                        <Transition name="fade">
                            <span v-if="sidebarOpen && item.badge" class="ml-auto px-1.5 py-0.5 rounded-full text-xs font-bold"
                                style="background:#D64545; color:white">{{ item.badge }}</span>
                        </Transition>
                    </RouterLink>
                </div>
            </nav>

            <!-- User -->
            <div class="p-4 border-t" style="border-color:rgba(255,255,255,0.1)">
                <div class="flex items-center gap-3">
                    <img :src="`https://ui-avatars.com/api/?name=${user?.name}&background=8B5E3C&color=fff&size=32`"
                        class="w-8 h-8 rounded-full flex-shrink-0">
                    <Transition name="fade">
                        <div v-if="sidebarOpen" class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white truncate">{{ user?.name }}</p>
                            <button @click="logout" class="text-xs hover:text-white transition-colors" style="color:rgba(255,255,255,0.4)">Logout</button>
                        </div>
                    </Transition>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div :class="['flex-1 flex flex-col transition-all duration-300', sidebarOpen ? 'ml-64' : 'ml-16']">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 h-16 flex items-center justify-between px-6 bg-white shadow-sm">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <nav class="hidden sm:flex items-center gap-2 text-sm" style="color:#6D655F">
                        <span>Admin</span>
                        <span>/</span>
                        <span class="font-medium capitalize" style="color:#1E1B18">{{ currentPageName }}</span>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <RouterLink to="/" target="_blank" class="text-sm px-3 py-1.5 rounded-lg hover:bg-amber-50 transition-colors" style="color:#8B5E3C">
                        View Store ↗
                    </RouterLink>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6">
                <RouterView />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const sidebarOpen = ref(true)
const user = computed(() => authStore.currentUser)
const currentPageName = computed(() => route.name?.replace('admin-', '') || 'Dashboard')

const navSections = [
    {
        label: 'Main',
        items: [
            { to: '/admin/dashboard', icon: '📊', label: 'Dashboard' },
        ]
    },
    {
        label: 'Catalog',
        items: [
            { to: '/admin/products', icon: '🪑', label: 'Products' },
            { to: '/admin/categories', icon: '📂', label: 'Categories' },
        ]
    },
    {
        label: 'Sales',
        items: [
            { to: '/admin/orders', icon: '📦', label: 'Orders' },
            { to: '/admin/customers', icon: '👥', label: 'Customers' },
            { to: '/admin/coupons', icon: '🏷️', label: 'Coupons' },
        ]
    },
    {
        label: 'Content',
        items: [
            { to: '/admin/banners', icon: '🖼️', label: 'Banners' },
            { to: '/admin/reviews', icon: '⭐', label: 'Reviews' },
            { to: '/admin/inquiries', icon: '🔨', label: 'Inquiries' },
            { to: '/admin/messages', icon: '✉️', label: 'Messages' },
        ]
    },
    {
        label: 'System',
        items: [
            { to: '/admin/settings', icon: '⚙️', label: 'Settings' },
        ]
    },
]

async function logout() {
    await authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
