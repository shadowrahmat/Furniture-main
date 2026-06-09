<template>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="section-title mb-8">My Wishlist</h1>

        <div v-if="wishlistStore.items.length === 0" class="text-center py-20">
            <div class="text-8xl mb-6">❤️</div>
            <h2 class="font-heading font-bold text-2xl mb-3" style="color:#1E1B18">Your wishlist is empty</h2>
            <p class="mb-8" style="color:#6D655F">Save your favorite furniture pieces</p>
            <RouterLink to="/shop" class="btn-primary">Browse Furniture</RouterLink>
        </div>

        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <ProductCard v-for="item in products" :key="item.id" :product="item"/>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useWishlistStore } from '../stores/wishlist.js'
import ProductCard from '../components/ProductCard.vue'

const wishlistStore = useWishlistStore()
const products = computed(() => wishlistStore.items.map(i => i.product).filter(Boolean))

onMounted(() => wishlistStore.fetchWishlist())
</script>
