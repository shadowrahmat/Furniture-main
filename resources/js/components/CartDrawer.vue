<template>
    <Teleport to="body">
        <!-- Overlay -->
        <Transition name="overlay-fade">
            <div v-if="cartStore.isOpen"
                class="fixed inset-0 z-40"
                style="background:rgba(30,27,24,0.5); backdrop-filter:blur(2px)"
                @click="cartStore.closeCart()">
            </div>
        </Transition>

        <!-- Drawer Panel -->
        <Transition name="drawer-slide">
            <div v-if="cartStore.isOpen"
                class="fixed top-0 right-0 h-full z-50 flex flex-col"
                style="width:min(420px,100vw); background:#FDFBF8; box-shadow:-8px 0 48px rgba(30,27,24,0.12)">

                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:#EDE5D8">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center gradient-walnut">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-lg leading-tight" style="color:#1E1B18">Shopping Cart</h2>
                            <p class="text-xs" style="color:#9B9087">{{ cartStore.count }} {{ cartStore.count === 1 ? 'item' : 'items' }}</p>
                        </div>
                    </div>
                    <button @click="cartStore.closeCart()"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-colors hover:bg-gray-100"
                        style="color:#6D655F">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Items List -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Empty State -->
                    <div v-if="cartStore.items.length === 0"
                        class="flex flex-col items-center justify-center h-full text-center px-8">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-5"
                            style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                            <svg class="w-12 h-12" style="color:#D4C4B0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Your cart is empty</h3>
                        <p class="text-sm mb-6 leading-relaxed" style="color:#9B9087">
                            Discover our beautiful furniture collection and add items to your cart
                        </p>
                        <RouterLink to="/shop" @click="cartStore.closeCart()"
                            class="btn-primary text-sm py-3 px-7">
                            Browse Collection
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </RouterLink>
                    </div>

                    <!-- Items -->
                    <div v-else class="px-5 py-4 space-y-3">
                        <TransitionGroup name="cart-item">
                            <div v-for="item in cartStore.items" :key="item.id"
                                class="flex gap-3 p-3 rounded-2xl transition-all"
                                style="background:#FFFFFF; border:1px solid #EDE5D8">
                                <!-- Product image -->
                                <RouterLink :to="`/product/${item.product?.slug}`" @click="cartStore.closeCart()" class="flex-shrink-0">
                                    <img :src="item.product?.featured_image
                                            ? `/storage/${item.product.featured_image}`
                                            : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=80&h=80&fit=crop'"
                                        class="w-16 h-16 object-cover rounded-xl"
                                        :alt="item.product?.name">
                                </RouterLink>

                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <RouterLink :to="`/product/${item.product?.slug}`" @click="cartStore.closeCart()"
                                        class="text-sm font-semibold leading-tight line-clamp-2 hover:text-walnut transition-colors"
                                        style="color:#1E1B18">
                                        {{ item.product?.name }}
                                    </RouterLink>
                                    <p v-if="item.color" class="text-xs mt-0.5 font-medium" style="color:#9B9087">
                                        Color: {{ item.color }}
                                    </p>

                                    <div class="flex items-center justify-between mt-2">
                                        <!-- Qty control -->
                                        <div class="flex items-center rounded-xl overflow-hidden"
                                            style="border:1px solid #EDE5D8; background:#FAF8F5">
                                            <button @click="updateQty(item, item.quantity - 1)"
                                                class="w-7 h-7 flex items-center justify-center text-base font-medium transition-colors hover:bg-amber-50 disabled:opacity-30"
                                                :disabled="item.quantity <= 1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="w-8 text-center text-sm font-bold" style="color:#1E1B18">{{ item.quantity }}</span>
                                            <button @click="updateQty(item, item.quantity + 1)"
                                                class="w-7 h-7 flex items-center justify-center transition-colors hover:bg-amber-50">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span class="price-tag text-sm font-bold">৳{{ (item.price * item.quantity).toLocaleString() }}</span>
                                    </div>
                                </div>

                                <!-- Remove -->
                                <button @click="removeItem(item.id)"
                                    class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center transition-all hover:bg-red-50 self-start mt-0.5"
                                    style="color:#D4C4B0">
                                    <svg class="w-4 h-4 hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </TransitionGroup>
                    </div>
                </div>

                <!-- Footer Summary -->
                <div v-if="cartStore.items.length > 0"
                    class="border-t"
                    style="border-color:#EDE5D8; background:#FFFFFF">
                    <div class="px-5 pt-4 pb-2 space-y-2.5">
                        <div class="flex items-center justify-between text-sm">
                            <span style="color:#9B9087">Subtotal ({{ cartStore.count }} items)</span>
                            <span class="font-semibold" style="color:#1E1B18">৳{{ cartStore.subtotal.toLocaleString() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span style="color:#9B9087">Delivery</span>
                            <span class="font-semibold"
                                :class="cartStore.deliveryCharge === 0 ? 'text-green-600' : ''"
                                :style="cartStore.deliveryCharge > 0 ? 'color:#1E1B18' : ''">
                                {{ cartStore.deliveryCharge === 0 ? 'FREE' : '৳' + cartStore.deliveryCharge.toLocaleString() }}
                            </span>
                        </div>

                        <!-- Promo code -->
                        <div class="pt-1">
                            <div v-if="cartStore.coupon" class="flex items-center justify-between p-2.5 rounded-xl" style="background:#F0FDF4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                    <span class="text-xs font-bold text-green-700 uppercase tracking-wide">{{ cartStore.coupon.code }}</span>
                                    <span class="text-xs text-green-600">applied</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-green-600">-৳{{ cartStore.discount.toLocaleString() }}</span>
                                    <button @click="cartStore.removeCoupon()" class="text-xs" style="color:#9B9087">✕</button>
                                </div>
                            </div>
                            <div v-else class="flex gap-2">
                                <input v-model="promoInput"
                                    @keyup.enter="applyPromo"
                                    type="text"
                                    placeholder="Promo code"
                                    class="flex-1 px-3 py-2 text-sm rounded-xl outline-none border"
                                    style="border-color:#EDE5D8; background:#FAF8F5; color:#1E1B18"
                                    :disabled="promoLoading">
                                <button @click="applyPromo"
                                    :disabled="!promoInput.trim() || promoLoading"
                                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all disabled:opacity-50"
                                    style="background:#8B5E3C; color:white">
                                    <span v-if="promoLoading">...</span>
                                    <span v-else>Apply</span>
                                </button>
                            </div>
                            <p v-if="promoError" class="text-xs mt-1.5 font-medium" style="color:#DC2626">{{ promoError }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t" style="border-color:#EDE5D8">
                            <span class="font-bold text-base" style="color:#1E1B18">Total</span>
                            <span class="price-tag text-xl font-bold">৳{{ cartStore.total.toLocaleString() }}</span>
                        </div>
                    </div>

                    <div class="px-5 pb-5 pt-3 space-y-2.5 safe-bottom">
                        <RouterLink to="/checkout" @click="cartStore.closeCart()"
                            class="btn-primary w-full justify-center py-3.5 text-sm">
                            Checkout
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </RouterLink>
                        <RouterLink to="/cart" @click="cartStore.closeCart()"
                            class="btn-ghost w-full justify-center text-sm">
                            View Full Cart
                        </RouterLink>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import { useCartStore } from '../stores/cart.js'

const cartStore = useCartStore()

const promoInput = ref('')
const promoLoading = ref(false)
const promoError = ref('')

async function updateQty(item, qty) {
    if (qty < 1) return
    await cartStore.updateQuantity(item.id, qty)
}

async function removeItem(id) {
    await cartStore.removeItem(id)
}

async function applyPromo() {
    if (!promoInput.value.trim()) return
    promoLoading.value = true
    promoError.value = ''
    try {
        await cartStore.applyCoupon(promoInput.value.trim())
        promoInput.value = ''
    } catch (e) {
        promoError.value = e?.response?.data?.message || 'Invalid promo code'
    } finally {
        promoLoading.value = false
    }
}
</script>

<style scoped>
/* Overlay */
.overlay-fade-enter-active, .overlay-fade-leave-active { transition: opacity 0.28s ease; }
.overlay-fade-enter-from, .overlay-fade-leave-to { opacity: 0; }

/* Drawer */
.drawer-slide-enter-active, .drawer-slide-leave-active {
    transition: transform 0.35s cubic-bezier(0.32, 0, 0.15, 1);
}
.drawer-slide-enter-from, .drawer-slide-leave-to { transform: translateX(100%); }

/* Cart item list animation */
.cart-item-enter-active { transition: all 0.25s ease; }
.cart-item-leave-active { transition: all 0.2s ease; }
.cart-item-enter-from  { opacity: 0; transform: translateX(10px); }
.cart-item-leave-to    { opacity: 0; transform: translateX(-10px); }
</style>
