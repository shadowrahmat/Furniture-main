<template>
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 glass border-t" style="border-color:#F0E8DE; padding-bottom:env(safe-area-inset-bottom)">
        <div class="flex items-center justify-around px-2 py-2">
            <RouterLink to="/" class="bottom-nav-item" :class="{ active: $route.path === '/' }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Home</span>
            </RouterLink>
            <RouterLink to="/shop" class="bottom-nav-item" :class="{ active: $route.path.startsWith('/shop') }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Shop</span>
            </RouterLink>
            <button @click="cartStore.toggleCart()" class="bottom-nav-item relative">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span v-if="cartStore.count" class="absolute -top-2 -right-2 w-4 h-4 rounded-full text-white flex items-center justify-center font-bold"
                        style="background:#8B5E3C; font-size:9px">{{ cartStore.count }}</span>
                </div>
                <span>Cart</span>
            </button>
            <RouterLink v-if="isAuth" to="/wishlist" class="bottom-nav-item" :class="{ active: $route.path === '/wishlist' }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span>Wishlist</span>
            </RouterLink>
            <RouterLink :to="isAuth ? '/account' : '/login'" class="bottom-nav-item" :class="{ active: $route.path.startsWith('/account') }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>{{ isAuth ? 'Account' : 'Login' }}</span>
            </RouterLink>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '../stores/cart.js'
import { useAuthStore } from '../stores/auth.js'

const cartStore = useCartStore()
const authStore = useAuthStore()
const isAuth = computed(() => authStore.isAuthenticated)
</script>

<style scoped>
.bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    padding: 4px 12px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 500;
    color: #6D655F;
    transition: all 0.2s;
    background: none;
    border: none;
    cursor: pointer;
}
.bottom-nav-item.active {
    color: #8B5E3C;
}
.bottom-nav-item.active svg {
    stroke: #8B5E3C;
}
</style>
