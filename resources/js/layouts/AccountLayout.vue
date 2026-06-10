<template>
    <div class="min-h-screen" style="background:#FAF8F5">
        <AppNavbar />

        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-8 sm:py-10 flex items-center gap-4">
                <img :src="user?.avatar_url || `https://ui-avatars.com/api/?name=${user?.name}&background=8B5E3C&color=fff`"
                    class="w-14 h-14 rounded-2xl object-cover flex-shrink-0" :alt="user?.name">
                <div class="min-w-0">
                    <p class="label-tag mb-1.5" style="color:#E8C47A">My Account</p>
                    <h1 class="font-heading font-bold text-xl sm:text-2xl text-white truncate">{{ user?.name }}</h1>
                    <p class="text-xs sm:text-sm truncate" style="color:rgba(245,239,230,0.6)">{{ user?.email }}</p>
                </div>
            </div>
        </section>

        <!-- Mobile tab nav -->
        <div class="md:hidden sticky top-0 z-20 border-b" style="background:#FDFAF6; border-color:#EFE6D9">
            <nav class="flex overflow-x-auto px-4 py-2.5 gap-2 no-scrollbar">
                <RouterLink v-for="item in menuItems" :key="item.to" :to="item.to"
                    class="flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all"
                    :class="$route.path === item.to ? '' : 'hover:bg-[#F5EFE6]'"
                    :style="$route.path === item.to ? 'background:linear-gradient(135deg,#8B5E3C,#C9A66B); color:#fff' : 'background:#F5EFE6; color:#6D655F'">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="item.icon"></svg>
                    {{ item.label }}
                </RouterLink>
            </nav>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 sm:py-8">
            <div class="flex gap-8">
                <!-- Sidebar -->
                <aside class="w-64 flex-shrink-0 hidden md:block">
                    <div class="card-premium p-4 sticky top-24">
                        <nav class="space-y-1">
                            <RouterLink v-for="item in menuItems" :key="item.to" :to="item.to"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all"
                                :class="$route.path === item.to ? 'text-white' : 'hover:bg-[#F5EFE6]'"
                                :style="$route.path === item.to ? 'background:linear-gradient(135deg,#8B5E3C,#C9A66B)' : 'color:#6D655F'">
                                <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="item.icon"></svg>
                                {{ item.label }}
                            </RouterLink>
                        </nav>
                        <div class="border-t mt-3 pt-3" style="border-color:#F0E8DE">
                            <button @click="logout"
                                class="w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-all">
                                <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </div>
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
    { to: '/account', label: 'Dashboard', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>' },
    { to: '/account/orders', label: 'My Orders', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>' },
    { to: '/account/profile', label: 'Profile', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>' },
    { to: '/account/addresses', label: 'Addresses', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>' },
    { to: '/wishlist', label: 'Wishlist', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>' },
]

async function logout() {
    await authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
