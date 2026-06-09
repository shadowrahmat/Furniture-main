<template>
    <div class="card-premium group overflow-hidden cursor-pointer">
        <!-- Image -->
        <div class="relative product-image-wrap bg-gray-50" style="aspect-ratio:4/3">
            <RouterLink :to="`/product/${product.slug}`">
                <img :src="productImage" :alt="product.name"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            </RouterLink>

            <!-- Badges -->
            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                <span v-if="product.sale_price" class="badge-discount">-{{ product.discount_percentage }}%</span>
                <span v-if="product.is_new_arrival" class="badge-new">NEW</span>
                <span v-if="product.is_best_seller" class="badge-discount" style="background:linear-gradient(135deg,#C9A66B,#8B5E3C)">BEST</span>
            </div>

            <!-- Wishlist -->
            <button @click.prevent="toggleWishlist"
                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center transition-all hover:scale-110 opacity-0 group-hover:opacity-100"
                :class="{ '!opacity-100': inWishlist }">
                <svg class="w-4 h-4 transition-colors" :class="inWishlist ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-gray-400'"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>

            <!-- Quick View -->
            <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                <RouterLink :to="`/product/${product.slug}`"
                    class="block w-full text-center py-2 rounded-lg text-sm font-semibold text-white"
                    style="background:rgba(139,94,60,0.9); backdrop-filter:blur(4px)">
                    Quick View
                </RouterLink>
            </div>
        </div>

        <!-- Info -->
        <div class="p-4">
            <!-- Category -->
            <RouterLink v-if="product.category" :to="`/shop/${product.category.slug}`"
                class="text-xs font-medium uppercase tracking-wider"
                style="color:#C9A66B">
                {{ product.category.name }}
            </RouterLink>

            <!-- Name -->
            <RouterLink :to="`/product/${product.slug}`">
                <h3 class="font-semibold text-sm mt-1 mb-2 line-clamp-2 leading-snug hover:text-walnut transition-colors"
                    style="color:#1E1B18">
                    {{ product.name }}
                </h3>
            </RouterLink>

            <!-- Rating -->
            <div v-if="product.avg_rating" class="flex items-center gap-1.5 mb-3">
                <div class="flex">
                    <svg v-for="n in 5" :key="n" class="w-3 h-3"
                        :class="n <= Math.round(product.avg_rating) ? 'fill-amber-400' : 'fill-gray-200'"
                        viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <span class="text-xs" style="color:#6D655F">({{ product.review_count }})</span>
            </div>

            <!-- Price & Cart -->
            <div class="flex items-center justify-between">
                <div>
                    <span class="price-tag text-base">৳{{ formatPrice(product.sale_price || product.price) }}</span>
                    <span v-if="product.sale_price" class="price-original text-sm ml-2">৳{{ formatPrice(product.price) }}</span>
                </div>
                <button @click="addToCart"
                    :disabled="cartStore.loading || product.stock === 0"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-white transition-all hover:scale-110 disabled:opacity-50"
                    :style="product.stock === 0 ? 'background:#9B9087' : 'background:linear-gradient(135deg,#8B5E3C,#C9A66B)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>

            <p v-if="product.stock === 0" class="text-xs text-red-500 mt-1 font-medium">Out of Stock</p>
            <p v-else-if="product.stock <= 5" class="text-xs mt-1 font-medium" style="color:#C9A66B">Only {{ product.stock }} left!</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '../stores/cart.js'
import { useWishlistStore } from '../stores/wishlist.js'
import { useAuthStore } from '../stores/auth.js'
import { toast } from 'vue3-toastify'

const props = defineProps({
    product: { type: Object, required: true }
})

const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const authStore = useAuthStore()

const productImage = computed(() => {
    if (props.product.featured_image) {
        return `/storage/${props.product.featured_image}`
    }
    return `https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&h=300&fit=crop&q=80`
})

const inWishlist = computed(() => wishlistStore.isInWishlist(props.product.id))

function formatPrice(price) {
    return Number(price).toLocaleString('en-BD')
}

async function addToCart() {
    if (props.product.stock === 0) return
    await cartStore.addToCart(props.product.id)
}

async function toggleWishlist() {
    if (!authStore.isAuthenticated) {
        toast.error('Please login to add to wishlist')
        return
    }
    await wishlistStore.toggleWishlist(props.product.id)
}
</script>
