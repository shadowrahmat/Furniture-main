<template>
    <header :class="['fixed w-full top-0 z-50 transition-all duration-300', scrolled ? 'glass shadow-md' : 'bg-transparent']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <RouterLink to="/" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                        style="background: linear-gradient(135deg, #8B5E3C, #C9A66B)">
                        <span class="text-white font-bold text-lg">F</span>
                    </div>
                    <div>
                        <span class="font-heading font-bold text-lg leading-none"
                            style="color:#1E1B18">Furniture</span>
                        <span class="font-heading font-bold text-lg leading-none"
                            style="color:#C9A66B"> Store</span>
                    </div>
                </RouterLink>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-8">
                    <RouterLink to="/" class="nav-link">Home</RouterLink>
                    <RouterLink to="/shop" class="nav-link">Shop</RouterLink>
                    <div class="relative group">
                        <button class="nav-link flex items-center gap-1">
                            Collections
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-56 card-premium py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <RouterLink v-for="cat in categories" :key="cat.slug" :to="`/shop/${cat.slug}`"
                                class="block px-4 py-2 text-sm hover:bg-amber-50 transition-colors"
                                style="color:#6D655F">
                                {{ cat.name }}
                            </RouterLink>
                        </div>
                    </div>
                    <RouterLink to="/custom-furniture" class="nav-link">Custom</RouterLink>
                    <RouterLink to="/about" class="nav-link">About</RouterLink>
                    <RouterLink to="/contact" class="nav-link">Contact</RouterLink>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <button @click="searchOpen = !searchOpen"
                        class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-amber-50 transition-all">
                        <svg class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Wishlist -->
                    <RouterLink v-if="isAuth" to="/wishlist"
                        class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-amber-50 transition-all relative">
                        <svg class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span v-if="wishlistCount" class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-white text-xs flex items-center justify-center font-bold"
                            style="background:#C9A66B; font-size:10px">{{ wishlistCount }}</span>
                    </RouterLink>

                    <!-- Cart -->
                    <button @click="cartStore.toggleCart"
                        class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-amber-50 transition-all relative">
                        <svg class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span v-if="cartCount" class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-white text-xs flex items-center justify-center font-bold"
                            style="background:#8B5E3C; font-size:10px">{{ cartCount }}</span>
                    </button>

                    <!-- User -->
                    <div class="relative group">
                        <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-amber-50 transition-all overflow-hidden">
                            <img v-if="isAuth" :src="user?.avatar_url || `https://ui-avatars.com/api/?name=${user?.name}&background=8B5E3C&color=fff&size=36`"
                                class="w-full h-full object-cover rounded-full" :alt="user?.name">
                            <svg v-else class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>
                        <div class="absolute top-full right-0 mt-2 w-48 card-premium py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <template v-if="isAuth">
                                <div class="px-4 py-2 border-b border-amber-50">
                                    <p class="font-semibold text-sm truncate" style="color:#1E1B18">{{ user?.name }}</p>
                                </div>
                                <RouterLink to="/account" class="block px-4 py-2 text-sm hover:bg-amber-50 transition-colors" style="color:#6D655F">My Account</RouterLink>
                                <RouterLink to="/account/orders" class="block px-4 py-2 text-sm hover:bg-amber-50 transition-colors" style="color:#6D655F">My Orders</RouterLink>
                                <RouterLink v-if="isAdmin" to="/admin/dashboard" class="block px-4 py-2 text-sm hover:bg-amber-50 transition-colors" style="color:#8B5E3C; font-weight:600">Admin Panel</RouterLink>
                                <button @click="logout" class="w-full text-left px-4 py-2 text-sm hover:bg-red-50 transition-colors" style="color:#D64545">Logout</button>
                            </template>
                            <template v-else>
                                <RouterLink to="/login" class="block px-4 py-2 text-sm hover:bg-amber-50 transition-colors" style="color:#6D655F">Login</RouterLink>
                                <RouterLink to="/register" class="block px-4 py-2 text-sm hover:bg-amber-50 transition-colors" style="color:#6D655F">Register</RouterLink>
                            </template>
                        </div>
                    </div>

                    <!-- Mobile menu -->
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden w-9 h-9 rounded-full flex items-center justify-center hover:bg-amber-50">
                        <svg class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="mobileOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Search bar -->
        <div v-if="searchOpen" class="glass border-t border-amber-100 px-4 py-3">
            <div class="max-w-2xl mx-auto flex items-center gap-3">
                <input v-model="searchQuery" @keyup.enter="doSearch" type="text"
                    placeholder="Search for furniture, sofas, beds..."
                    class="input-field flex-1" autofocus>
                <button @click="doSearch" class="btn-primary py-3 px-6">Search</button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="mobileOpen" class="lg:hidden glass border-t border-amber-100">
            <nav class="px-4 py-3 space-y-1">
                <RouterLink to="/" class="block py-2 text-sm font-medium" style="color:#6D655F" @click="mobileOpen = false">Home</RouterLink>
                <RouterLink to="/shop" class="block py-2 text-sm font-medium" style="color:#6D655F" @click="mobileOpen = false">Shop</RouterLink>
                <RouterLink to="/custom-furniture" class="block py-2 text-sm font-medium" style="color:#6D655F" @click="mobileOpen = false">Custom Furniture</RouterLink>
                <RouterLink to="/about" class="block py-2 text-sm font-medium" style="color:#6D655F" @click="mobileOpen = false">About</RouterLink>
                <RouterLink to="/contact" class="block py-2 text-sm font-medium" style="color:#6D655F" @click="mobileOpen = false">Contact</RouterLink>
            </nav>
        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-16"></div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'
import { useCartStore } from '../stores/cart.js'
import { useWishlistStore } from '../stores/wishlist.js'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()

const scrolled = ref(false)
const mobileOpen = ref(false)
const searchOpen = ref(false)
const searchQuery = ref('')
const categories = ref([])

const isAuth = computed(() => authStore.isAuthenticated)
const isAdmin = computed(() => authStore.isAdmin)
const user = computed(() => authStore.currentUser)
const cartCount = computed(() => cartStore.count)
const wishlistCount = computed(() => wishlistStore.count)

async function loadCategories() {
    try {
        const { data } = await axios.get('/categories')
        categories.value = data
    } catch (e) {}
}

function doSearch() {
    if (searchQuery.value.trim()) {
        router.push({ name: 'shop', query: { search: searchQuery.value } })
        searchOpen.value = false
        searchQuery.value = ''
    }
}

async function logout() {
    await authStore.logout()
    router.push('/')
}

function onScroll() {
    scrolled.value = window.scrollY > 20
}

onMounted(() => {
    window.addEventListener('scroll', onScroll)
    loadCategories()
    if (isAuth.value) wishlistStore.fetchWishlist()
})
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
.nav-link {
    font-size: 14px;
    font-weight: 500;
    color: #6D655F;
    transition: color 0.2s;
}
.nav-link:hover, .nav-link.router-link-active {
    color: #8B5E3C;
}
</style>
