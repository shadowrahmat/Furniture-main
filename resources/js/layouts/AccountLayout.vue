<template>
    <div class="min-h-screen" style="background:#FAF8F5">
        <AppNavbar />
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex gap-8">
                <!-- Sidebar -->
                <aside class="w-64 flex-shrink-0 hidden md:block">
                    <div class="card-premium p-6 sticky top-24">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-amber-100">
                            <img :src="user?.avatar_url || `https://ui-avatars.com/api/?name=${user?.name}&background=8B5E3C&color=fff`"
                                class="w-12 h-12 rounded-full object-cover" :alt="user?.name">
                            <div>
                                <p class="font-semibold text-charcoal text-sm">{{ user?.name }}</p>
                                <p class="text-xs text-muted">{{ user?.email }}</p>
                            </div>
                        </div>
                        <nav class="space-y-1">
                            <RouterLink v-for="item in menuItems" :key="item.to" :to="item.to"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                                :class="$route.path === item.to ? 'bg-walnut text-white' : 'text-muted hover:bg-amber-50 hover:text-walnut'">
                                <span class="text-base">{{ item.icon }}</span>
                                {{ item.label }}
                            </RouterLink>
                        </nav>
                        <button @click="logout"
                            class="mt-4 w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-all">
                            <span>🚪</span> Logout
                        </button>
                    </div>
                </aside>
                <!-- Content -->
                <main class="flex-1 min-w-0">
                    <RouterView />
                </main>
            </div>
        </div>
        <AppFooter />
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

const authStore = useAuthStore()
const router = useRouter()
const user = computed(() => authStore.currentUser)

const menuItems = [
    { to: '/account', icon: '🏠', label: 'Dashboard' },
    { to: '/account/orders', icon: '📦', label: 'My Orders' },
    { to: '/account/profile', icon: '👤', label: 'Profile' },
    { to: '/account/addresses', icon: '📍', label: 'Addresses' },
    { to: '/wishlist', icon: '❤️', label: 'Wishlist' },
]

async function logout() {
    await authStore.logout()
    router.push('/login')
}
</script>
