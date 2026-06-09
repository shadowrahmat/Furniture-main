<template>
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40" style="padding-bottom:env(safe-area-inset-bottom)">
        <!-- Floating pill container -->
        <div class="mx-4 mb-4">
            <div class="flex items-center justify-around px-2 py-2 rounded-[28px] shadow-2xl"
                style="background:rgba(255,253,249,0.96); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(201,166,107,0.15); box-shadow:0 8px 32px rgba(30,27,24,0.18), 0 2px 8px rgba(30,27,24,0.08)">

                <!-- Home -->
                <RouterLink to="/" class="bottom-tab" :class="{ active: $route.path === '/' }">
                    <div class="tab-icon-wrap">
                        <svg class="w-[22px] h-[22px]" :fill="$route.path === '/' ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="tab-label">Home</span>
                </RouterLink>

                <!-- Shop -->
                <RouterLink to="/shop" class="bottom-tab" :class="{ active: $route.path.startsWith('/shop') }">
                    <div class="tab-icon-wrap">
                        <svg class="w-[22px] h-[22px]" :fill="$route.path.startsWith('/shop') ? 'none' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <span class="tab-label">Shop</span>
                </RouterLink>

                <!-- Cart — center elevated button -->
                <button @click="cartStore.toggleCart()" class="cart-fab relative -mt-6">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-white shadow-lg"
                        style="background:linear-gradient(145deg,#8B5E3C,#C9A66B); box-shadow:0 6px 20px rgba(139,94,60,0.5)">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span v-if="cartStore.count"
                        class="absolute -top-1 -right-1 w-5 h-5 rounded-full text-white flex items-center justify-center font-bold border-2 border-white"
                        style="background:#D64545; font-size:9px; box-shadow:0 2px 6px rgba(214,69,69,0.5)">
                        {{ cartStore.count > 9 ? '9+' : cartStore.count }}
                    </span>
                    <span class="tab-label mt-1">Cart</span>
                </button>

                <!-- Wishlist / Search -->
                <RouterLink v-if="isAuth" to="/wishlist"
                    class="bottom-tab"
                    :class="{ active: $route.path === '/wishlist' }">
                    <div class="tab-icon-wrap relative">
                        <svg class="w-[22px] h-[22px]" :fill="$route.path === '/wishlist' ? '#D64545' : 'none'"
                            :stroke="$route.path === '/wishlist' ? '#D64545' : 'currentColor'"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span v-if="wishlistStore.count"
                            class="absolute -top-1.5 -right-1.5 w-3.5 h-3.5 rounded-full"
                            style="background:#D64545; border:1.5px solid white"></span>
                    </div>
                    <span class="tab-label">Saved</span>
                </RouterLink>
                <button v-else @click="onSearchTap" class="bottom-tab">
                    <div class="tab-icon-wrap">
                        <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <span class="tab-label">Search</span>
                </button>

                <!-- Account -->
                <RouterLink :to="isAuth ? '/account' : '/login'"
                    class="bottom-tab"
                    :class="{ active: $route.path.startsWith('/account') || $route.path === '/login' }">
                    <div class="tab-icon-wrap">
                        <template v-if="isAuth">
                            <img :src="`https://ui-avatars.com/api/?name=${user?.name}&background=8B5E3C&color=fff&size=22`"
                                class="w-[22px] h-[22px] rounded-full ring-2"
                                :style="$route.path.startsWith('/account') ? 'ring-color:#8B5E3C' : 'ring-color:transparent'">
                        </template>
                        <template v-else>
                            <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </template>
                    </div>
                    <span class="tab-label">{{ isAuth ? 'Me' : 'Login' }}</span>
                </RouterLink>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '../stores/cart.js'
import { useWishlistStore } from '../stores/wishlist.js'
import { useAuthStore } from '../stores/auth.js'
import { useUiStore } from '../stores/ui.js'

const cartStore     = useCartStore()
const wishlistStore = useWishlistStore()
const authStore     = useAuthStore()
const uiStore       = useUiStore()

const isAuth = computed(() => authStore.isAuthenticated)
const user   = computed(() => authStore.currentUser)

function onSearchTap() { uiStore.openSearch() }
</script>

<style scoped>
.bottom-tab {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    padding: 6px 10px;
    border-radius: 16px;
    font-size: 10px;
    font-weight: 500;
    color: #9B9087;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: none;
    min-width: 48px;
    position: relative;
}
.bottom-tab:active {
    transform: scale(0.92);
}
.bottom-tab.active,
.bottom-tab.active .tab-label {
    color: #8B5E3C;
}
.bottom-tab.active .tab-icon-wrap {
    background: rgba(201, 166, 107, 0.15);
}

.tab-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.tab-label {
    font-size: 10px;
    font-weight: 500;
    line-height: 1;
    transition: color 0.2s ease;
}

.cart-fab {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0 8px;
    gap: 3px;
    transition: transform 0.2s ease;
}
.cart-fab:active {
    transform: scale(0.9);
}
.cart-fab .tab-label {
    color: #8B5E3C;
    font-weight: 600;
}
</style>
