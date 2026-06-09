<template>
    <div>
        <!-- Loading skeleton -->
        <div v-if="loading" class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <div class="skeleton rounded-3xl" style="aspect-ratio:1"></div>
                <div class="space-y-4">
                    <div class="skeleton h-6 w-1/3 rounded"></div>
                    <div class="skeleton h-10 w-3/4 rounded"></div>
                    <div class="skeleton h-4 w-full rounded"></div>
                    <div class="skeleton h-4 w-2/3 rounded"></div>
                </div>
            </div>
        </div>

        <div v-else-if="product">
            <div class="max-w-7xl mx-auto px-4 py-8">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-xs mb-8" style="color:#6D655F">
                    <RouterLink to="/" class="hover:text-walnut">Home</RouterLink>
                    <span>/</span>
                    <RouterLink to="/shop" class="hover:text-walnut">Shop</RouterLink>
                    <span>/</span>
                    <RouterLink v-if="product.category" :to="`/shop/${product.category.slug}`" class="hover:text-walnut">{{ product.category.name }}</RouterLink>
                    <span>/</span>
                    <span class="font-medium" style="color:#1E1B18">{{ product.name }}</span>
                </nav>

                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Images -->
                    <div>
                        <div class="rounded-3xl overflow-hidden bg-gray-50 mb-4" style="aspect-ratio:1">
                            <img :src="activeImage" :alt="product.name" class="w-full h-full object-cover">
                        </div>
                        <div v-if="allImages.length > 1" class="grid grid-cols-4 gap-3">
                            <button v-for="(img, idx) in allImages" :key="idx"
                                @click="activeImage = img"
                                class="rounded-xl overflow-hidden border-2 transition-all"
                                :style="activeImage === img ? 'border-color:#8B5E3C' : 'border-color:transparent'">
                                <img :src="img" class="w-full object-cover" style="aspect-ratio:1">
                            </button>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="animate-fade-in">
                        <!-- Category badge -->
                        <RouterLink v-if="product.category" :to="`/shop/${product.category.slug}`"
                            class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-4"
                            style="background:#F5EFE6; color:#8B5E3C">
                            {{ product.category.name }}
                        </RouterLink>

                        <h1 class="font-heading font-bold text-3xl lg:text-4xl mb-4 leading-tight" style="color:#1E1B18">
                            {{ product.name }}
                        </h1>

                        <!-- Rating -->
                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex gap-0.5">
                                <svg v-for="n in 5" :key="n" class="w-4 h-4"
                                    :class="n <= Math.round(product.avg_rating) ? 'fill-amber-400' : 'fill-gray-200'"
                                    viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold" style="color:#1E1B18">{{ product.avg_rating }}</span>
                            <span class="text-sm" style="color:#6D655F">({{ product.review_count }} reviews)</span>
                        </div>

                        <!-- Price -->
                        <div class="flex items-end gap-3 mb-6">
                            <span class="font-display font-bold text-3xl" style="color:#8B5E3C">
                                ৳{{ formatPrice(product.sale_price || product.price) }}
                            </span>
                            <template v-if="product.sale_price">
                                <span class="text-xl" style="color:#6D655F; text-decoration:line-through">৳{{ formatPrice(product.price) }}</span>
                                <span class="badge-discount text-sm">Save {{ product.discount_percentage }}%</span>
                            </template>
                        </div>

                        <p class="text-sm leading-relaxed mb-6" style="color:#6D655F">{{ product.short_description }}</p>

                        <!-- Colors -->
                        <div v-if="product.colors?.length" class="mb-5">
                            <p class="text-sm font-semibold mb-2" style="color:#1E1B18">Color: <span class="font-normal" style="color:#6D655F">{{ selectedColor || 'Select' }}</span></p>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="color in product.colors" :key="color"
                                    @click="selectedColor = color"
                                    class="px-4 py-2 rounded-full text-xs font-medium border-2 transition-all"
                                    :style="selectedColor === color
                                        ? 'background:#8B5E3C; color:white; border-color:#8B5E3C'
                                        : 'background:white; color:#6D655F; border-color:#E8DDD4'">
                                    {{ color }}
                                </button>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center rounded-xl overflow-hidden border-2" style="border-color:#E8DDD4">
                                <button @click="qty = Math.max(1, qty-1)" class="w-10 h-10 flex items-center justify-center hover:bg-amber-50 text-lg">-</button>
                                <span class="w-12 text-center font-semibold">{{ qty }}</span>
                                <button @click="qty = Math.min(product.stock, qty+1)" class="w-10 h-10 flex items-center justify-center hover:bg-amber-50 text-lg">+</button>
                            </div>
                            <span class="text-sm" style="color:#6D655F">
                                <span v-if="product.stock > 0" :class="product.stock <= 5 ? 'font-semibold' : ''"
                                    :style="product.stock <= 5 ? 'color:#C9A66B' : 'color:#6B7A52'">
                                    {{ product.stock <= 5 ? `Only ${product.stock} left!` : `In Stock (${product.stock})` }}
                                </span>
                                <span v-else class="font-semibold" style="color:#D64545">Out of Stock</span>
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 mb-8">
                            <button @click="addToCart" :disabled="product.stock === 0 || cartLoading"
                                class="flex-1 btn-primary py-3.5 justify-center disabled:opacity-50">
                                <svg v-if="cartLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
                            </button>
                            <button @click="toggleWishlist"
                                class="w-14 rounded-xl flex items-center justify-center border-2 transition-all"
                                :style="inWishlist ? 'background:#FEF2F2; border-color:#D64545; color:#D64545' : 'border-color:#E8DDD4; color:#6D655F; background:white'">
                                <svg class="w-5 h-5 transition-all" :class="inWishlist ? 'fill-red-500' : 'fill-none'"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Product details -->
                        <div class="rounded-2xl p-5 space-y-3" style="background:#F5EFE6">
                            <div v-if="product.material" class="flex items-center gap-3 text-sm">
                                <span class="w-5 text-center">🪵</span>
                                <span style="color:#6D655F">Material:</span>
                                <span class="font-medium" style="color:#1E1B18">{{ product.material }}</span>
                            </div>
                            <div v-if="product.dimensions" class="flex items-center gap-3 text-sm">
                                <span class="w-5 text-center">📐</span>
                                <span style="color:#6D655F">Dimensions:</span>
                                <span class="font-medium" style="color:#1E1B18">{{ product.dimensions }}</span>
                            </div>
                            <div v-if="product.sku" class="flex items-center gap-3 text-sm">
                                <span class="w-5 text-center">🏷️</span>
                                <span style="color:#6D655F">SKU:</span>
                                <span class="font-medium" style="color:#1E1B18">{{ product.sku }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs: Description / Specifications / Reviews -->
                <div class="mt-16">
                    <div class="flex border-b mb-8" style="border-color:#E8DDD4">
                        <button v-for="tab in tabs" :key="tab" @click="activeTab = tab"
                            class="px-6 py-3 text-sm font-semibold border-b-2 -mb-px transition-all"
                            :style="activeTab === tab
                                ? 'border-color:#8B5E3C; color:#8B5E3C'
                                : 'border-color:transparent; color:#6D655F'">
                            {{ tab }}
                        </button>
                    </div>

                    <!-- Description -->
                    <div v-if="activeTab === 'Description'">
                        <div class="prose max-w-none text-sm leading-relaxed" style="color:#4B2E1E">
                            {{ product.description }}
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div v-if="activeTab === 'Specifications'">
                        <div v-if="product.specifications" class="grid md:grid-cols-2 gap-3">
                            <div v-for="(val, key) in product.specifications" :key="key"
                                class="flex justify-between items-center py-3 px-4 rounded-lg"
                                style="background:#F5EFE6">
                                <span class="text-sm font-medium" style="color:#6D655F">{{ key }}</span>
                                <span class="text-sm font-semibold" style="color:#1E1B18">{{ val }}</span>
                            </div>
                        </div>
                        <p v-else class="text-sm" style="color:#6D655F">No specifications available.</p>
                    </div>

                    <!-- Reviews -->
                    <div v-if="activeTab === 'Reviews'">
                        <div v-if="product.reviews?.length">
                            <div v-for="review in product.reviews" :key="review.id"
                                class="card-premium p-5 mb-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <img :src="`https://ui-avatars.com/api/?name=${review.user?.name}&background=8B5E3C&color=fff&size=40`"
                                            class="w-10 h-10 rounded-full">
                                        <div>
                                            <p class="font-semibold text-sm" style="color:#1E1B18">{{ review.user?.name }}</p>
                                            <p class="text-xs" style="color:#6D655F">{{ formatDate(review.created_at) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        <svg v-for="n in 5" :key="n" class="w-4 h-4"
                                            :class="n <= review.rating ? 'fill-amber-400' : 'fill-gray-200'"
                                            viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h4 v-if="review.title" class="font-semibold text-sm mb-1" style="color:#1E1B18">{{ review.title }}</h4>
                                <p class="text-sm" style="color:#6D655F">{{ review.body }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-center py-8" style="color:#6D655F">No reviews yet. Be the first to review!</p>
                    </div>
                </div>

                <!-- Related Products -->
                <div v-if="related.length" class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="section-title mb-0">Related Products</h2>
                        <RouterLink :to="`/shop/${product.category?.slug}`" class="text-sm font-semibold" style="color:#8B5E3C">
                            View all →
                        </RouterLink>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <ProductCard v-for="p in related" :key="p.id" :product="p"/>
                    </div>
                </div>

                <!-- Recently Viewed -->
                <div v-if="recentlyViewedFiltered.length" class="mt-16">
                    <h2 class="section-title mb-8">Recently Viewed</h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <ProductCard v-for="p in recentlyViewedFiltered" :key="p.id" :product="p"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import { useCartStore } from '../stores/cart.js'
import { useWishlistStore } from '../stores/wishlist.js'
import { useAuthStore } from '../stores/auth.js'
import { useRecentlyViewedStore } from '../stores/recentlyViewed.js'
import ProductCard from '../components/ProductCard.vue'

const route = useRoute()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const authStore = useAuthStore()
const recentlyViewedStore = useRecentlyViewedStore()

const product = ref(null)
const related = ref([])
const loading = ref(true)
const cartLoading = ref(false)
const qty = ref(1)
const selectedColor = ref('')
const activeTab = ref('Description')
const activeImage = ref('')
const tabs = ['Description', 'Specifications', 'Reviews']

const inWishlist = computed(() => product.value ? wishlistStore.isInWishlist(product.value.id) : false)

const recentlyViewedFiltered = computed(() =>
    recentlyViewedStore.products
        .filter(p => p.id !== product.value?.id)
        .slice(0, 4)
)

const allImages = computed(() => {
    if (!product.value) return []
    const imgs = []
    if (product.value.featured_image) imgs.push(`/storage/${product.value.featured_image}`)
    product.value.images?.forEach(img => {
        if (!imgs.includes(`/storage/${img.image}`)) imgs.push(`/storage/${img.image}`)
    })
    if (imgs.length === 0) imgs.push('https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=600&fit=crop&q=80')
    return imgs
})

function formatPrice(p) { return Number(p).toLocaleString('en-BD') }
function formatDate(d) { return new Date(d).toLocaleDateString('en-BD', { year: 'numeric', month: 'long', day: 'numeric' }) }

async function loadProduct() {
    loading.value = true
    try {
        const { data } = await axios.get(`/products/${route.params.slug}`)
        product.value = data.product
        related.value = data.related
        activeImage.value = allImages.value[0]
        if (product.value.colors?.length) selectedColor.value = product.value.colors[0]
        recentlyViewedStore.track(product.value)
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

async function addToCart() {
    cartLoading.value = true
    try {
        await cartStore.addToCart(product.value.id, qty.value, selectedColor.value)
    } finally {
        cartLoading.value = false
    }
}

async function toggleWishlist() {
    if (!authStore.isAuthenticated) {
        toast.error('Please login to use wishlist')
        return
    }
    await wishlistStore.toggleWishlist(product.value.id)
}

watch(() => route.params.slug, loadProduct)
onMounted(loadProduct)
</script>
