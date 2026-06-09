<template>
    <Teleport to="body">
        <Transition name="qv-overlay">
            <div v-if="uiStore.quickViewSlug"
                class="fixed inset-0 z-[55] flex items-end sm:items-center justify-center p-0 sm:p-4"
                style="background:rgba(24,20,15,0.65); backdrop-filter:blur(4px)"
                @click.self="close">

                <Transition name="qv-panel">
                    <div v-if="uiStore.quickViewSlug"
                        class="bg-white w-full sm:w-auto sm:max-w-3xl rounded-t-[32px] sm:rounded-[28px] shadow-2xl overflow-hidden"
                        style="max-height:92vh; overflow-y:auto">

                        <!-- Loading skeleton -->
                        <div v-if="loading" class="p-6">
                            <div class="flex flex-col sm:flex-row gap-6">
                                <div class="skeleton rounded-2xl sm:w-64 sm:h-64 w-full h-56 flex-shrink-0"></div>
                                <div class="flex-1 space-y-4">
                                    <div class="skeleton h-4 w-1/4 rounded"></div>
                                    <div class="skeleton h-7 w-3/4 rounded"></div>
                                    <div class="skeleton h-4 w-1/3 rounded"></div>
                                    <div class="skeleton h-8 w-1/2 rounded"></div>
                                    <div class="skeleton h-10 w-full rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <template v-else-if="product">
                            <!-- Header bar -->
                            <div class="flex items-center justify-between px-5 pt-4 pb-2 border-b" style="border-color:#EDE5D8">
                                <p class="text-xs font-semibold uppercase tracking-widest" style="color:#9B9087">Quick View</p>
                                <button @click="close" class="btn-icon w-8 h-8">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-0 sm:gap-6 p-5">
                                <!-- Image gallery -->
                                <div class="flex-shrink-0 sm:w-72">
                                    <div class="rounded-2xl overflow-hidden bg-gray-50 mb-3" style="aspect-ratio:1">
                                        <img :src="activeImage" :alt="product.name"
                                            class="w-full h-full object-cover transition-opacity duration-300">
                                    </div>
                                    <div v-if="allImages.length > 1" class="flex gap-2 overflow-x-auto hide-scrollbar">
                                        <button v-for="(img, idx) in allImages" :key="idx"
                                            @click="activeImage = img"
                                            class="flex-shrink-0 w-14 h-14 rounded-xl overflow-hidden border-2 transition-all"
                                            :style="activeImage === img ? 'border-color:#8B5E3C' : 'border-color:#EDE5D8'">
                                            <img :src="img" class="w-full h-full object-cover">
                                        </button>
                                    </div>
                                </div>

                                <!-- Product info -->
                                <div class="flex-1 min-w-0">
                                    <!-- Category -->
                                    <RouterLink v-if="product.category" :to="`/shop/${product.category.slug}`"
                                        @click="close"
                                        class="inline-block text-xs font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full mb-3"
                                        style="background:#F5EFE6; color:#8B5E3C">
                                        {{ product.category.name }}
                                    </RouterLink>

                                    <h2 class="font-heading font-bold text-xl sm:text-2xl mb-3 leading-snug" style="color:#1E1B18">
                                        {{ product.name }}
                                    </h2>

                                    <!-- Rating -->
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="flex gap-0.5">
                                            <svg v-for="n in 5" :key="n" class="w-3.5 h-3.5"
                                                :class="n <= Math.round(product.avg_rating || 0) ? 'fill-amber-400' : 'fill-gray-200'"
                                                viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm" style="color:#6D655F">{{ product.avg_rating || 0 }} ({{ product.review_count || 0 }} reviews)</span>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex items-end gap-3 mb-5">
                                        <span class="font-display font-bold text-2xl sm:text-3xl" style="color:#8B5E3C">
                                            ৳{{ fmt(product.sale_price || product.price) }}
                                        </span>
                                        <template v-if="product.sale_price">
                                            <span class="text-base line-through" style="color:#6D655F">৳{{ fmt(product.price) }}</span>
                                            <span class="badge-discount">{{ product.discount_percentage }}% OFF</span>
                                        </template>
                                    </div>

                                    <!-- Short description -->
                                    <p v-if="product.short_description" class="text-sm leading-relaxed mb-5" style="color:#6D655F">
                                        {{ product.short_description }}
                                    </p>

                                    <!-- Stock badge -->
                                    <div class="mb-5">
                                        <span v-if="product.stock > 0"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                                            style="background:#F0FDF4; color:#166534">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                            In Stock{{ product.stock <= 5 ? ` — Only ${product.stock} left` : '' }}
                                        </span>
                                        <span v-else class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                                            style="background:#FFF1F2; color:#9F1239">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                                            Out of Stock
                                        </span>
                                    </div>

                                    <!-- Qty + Add to Cart -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <!-- Qty stepper -->
                                        <div class="flex items-center rounded-2xl border overflow-hidden" style="border-color:#EDE5D8">
                                            <button @click="qty = Math.max(1, qty - 1)"
                                                class="w-10 h-11 flex items-center justify-center transition-colors hover:bg-amber-50 disabled:opacity-40"
                                                :disabled="qty <= 1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="w-10 text-center text-sm font-bold" style="color:#1E1B18">{{ qty }}</span>
                                            <button @click="qty = Math.min(product.stock || 99, qty + 1)"
                                                class="w-10 h-11 flex items-center justify-center transition-colors hover:bg-amber-50 disabled:opacity-40"
                                                :disabled="qty >= (product.stock || 99)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Add to Cart -->
                                        <button @click="addToCart"
                                            :disabled="!product.stock || adding"
                                            class="flex-1 btn-primary py-3 text-sm relative overflow-hidden"
                                            :class="addedFeedback ? 'bg-green-600' : ''">
                                            <Transition name="btn-text" mode="out-in">
                                                <span v-if="addedFeedback" key="ok" class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Added!
                                                </span>
                                                <span v-else-if="adding" key="loading" class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                    </svg>
                                                    Adding…
                                                </span>
                                                <span v-else key="idle" class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Add to Cart
                                                </span>
                                            </Transition>
                                        </button>
                                    </div>

                                    <!-- View full details -->
                                    <RouterLink :to="`/product/${product.slug}`" @click="close"
                                        class="flex items-center justify-center gap-2 w-full btn-outline text-sm py-2.5">
                                        View Full Details
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </RouterLink>
                                </div>
                            </div>
                        </template>

                        <!-- Error state -->
                        <div v-else-if="error" class="p-8 text-center">
                            <div class="text-4xl mb-3">😕</div>
                            <p class="font-semibold mb-2" style="color:#1E1B18">Couldn't load product</p>
                            <button @click="close" class="btn-outline text-sm py-2 px-5 mt-2">Close</button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import axios from 'axios'
import { useUiStore } from '../stores/ui.js'
import { useCartStore } from '../stores/cart.js'

const uiStore = useUiStore()
const cartStore = useCartStore()

const product = ref(null)
const loading = ref(false)
const error = ref(false)
const qty = ref(1)
const activeImage = ref(null)
const adding = ref(false)
const addedFeedback = ref(false)

const allImages = computed(() => {
    if (!product.value) return []
    const imgs = []
    if (product.value.featured_image) {
        imgs.push(`/storage/${product.value.featured_image}`)
    }
    if (product.value.images?.length) {
        product.value.images.forEach(img => {
            const src = img.image_path ? `/storage/${img.image_path}` : img.url
            if (src && !imgs.includes(src)) imgs.push(src)
        })
    }
    return imgs
})

function fmt(v) { return Number(v).toLocaleString('en-BD') }
function close() { uiStore.closeQuickView() }

async function loadProduct(slug) {
    loading.value = true
    error.value = false
    product.value = null
    qty.value = 1
    addedFeedback.value = false
    try {
        const { data } = await axios.get(`/products/${slug}`)
        product.value = data.product || data
        activeImage.value = allImages.value[0] || null
    } catch {
        error.value = true
    } finally {
        loading.value = false
    }
}

async function addToCart() {
    if (!product.value || adding.value) return
    adding.value = true
    try {
        await cartStore.addToCart(product.value.id, qty.value)
        addedFeedback.value = true
        setTimeout(() => { addedFeedback.value = false }, 1800)
    } catch {}
    finally { adding.value = false }
}

watch(() => uiStore.quickViewSlug, (slug) => {
    if (slug) {
        loadProduct(slug)
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
        product.value = null
    }
})
</script>

<style scoped>
.qv-overlay-enter-active { transition: opacity 0.2s ease; }
.qv-overlay-leave-active { transition: opacity 0.18s ease; }
.qv-overlay-enter-from, .qv-overlay-leave-to { opacity: 0; }

.qv-panel-enter-active { transition: transform 0.3s cubic-bezier(0.32, 0, 0.15, 1), opacity 0.25s ease; }
.qv-panel-leave-active { transition: transform 0.22s cubic-bezier(0.32, 0, 0.15, 1), opacity 0.18s ease; }
.qv-panel-enter-from { transform: translateY(40px); opacity: 0; }
.qv-panel-leave-to { transform: translateY(20px); opacity: 0; }

@media (min-width: 640px) {
    .qv-panel-enter-from { transform: scale(0.95) translateY(10px); }
    .qv-panel-leave-to { transform: scale(0.96) translateY(6px); }
}

.btn-text-enter-active, .btn-text-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.btn-text-enter-from { opacity: 0; transform: translateY(6px); }
.btn-text-leave-to { opacity: 0; transform: translateY(-6px); }
</style>
