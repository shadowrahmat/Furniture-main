<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-10 sm:py-14">
                <p class="label-tag mb-3" style="color:#E8C47A">Saved For Later</p>
                <div class="flex items-end justify-between gap-4 flex-wrap">
                    <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white">My Wishlist</h1>
                    <p v-if="products.length" class="text-sm" style="color:rgba(245,239,230,0.65)">
                        {{ products.length }} {{ products.length === 1 ? 'item' : 'items' }} saved
                    </p>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-10">
            <!-- Empty state -->
            <div v-if="products.length === 0" class="flex flex-col items-center text-center py-16 sm:py-24 anim-fade-up">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mb-6"
                    style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                    <svg class="w-14 h-14" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h2 class="font-heading font-bold text-2xl mb-3" style="color:#1E1B18">Your wishlist is empty</h2>
                <p class="mb-8 max-w-sm" style="color:#6D655F">Tap the heart icon on any product to save your favorite furniture pieces here.</p>
                <RouterLink to="/shop" class="btn-primary">
                    Browse Furniture
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </RouterLink>
            </div>

            <!-- Products grid -->
            <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                <div v-for="(item, idx) in products" :key="item.id" class="anim-fade-up" :style="`animation-delay:${Math.min(idx, 7) * 0.05}s`">
                    <ProductCard :product="item"/>
                </div>
            </div>
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
