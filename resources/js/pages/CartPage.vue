<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-10 sm:py-14">
                <p class="label-tag mb-3" style="color:#E8C47A">Step 1 of 3</p>
                <div class="flex items-end justify-between gap-4 flex-wrap">
                    <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white">Shopping Cart</h1>
                    <p v-if="cartStore.items.length" class="text-sm" style="color:rgba(245,239,230,0.65)">
                        {{ cartStore.count }} {{ cartStore.count === 1 ? 'item' : 'items' }} in your cart
                    </p>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-10">
            <!-- Empty state -->
            <div v-if="cartStore.items.length === 0" class="flex flex-col items-center text-center py-16 sm:py-24 anim-fade-up">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mb-6"
                    style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                    <svg class="w-14 h-14" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="font-heading font-bold text-2xl mb-3" style="color:#1E1B18">Your cart is empty</h2>
                <p class="mb-8 max-w-sm" style="color:#6D655F">Looks like you haven't added anything yet. Start exploring our collection and find something you'll love.</p>
                <RouterLink to="/shop" class="btn-primary">
                    Browse Furniture
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </RouterLink>
            </div>

            <div v-else class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Cart items -->
                <div class="lg:col-span-2 space-y-4">
                    <TransitionGroup name="cart-row">
                        <div v-for="(item, idx) in cartStore.items" :key="item.id"
                            class="card-premium p-4 sm:p-5 flex gap-4 anim-fade-up"
                            :style="`animation-delay:${Math.min(idx, 5) * 0.06}s`">
                            <RouterLink :to="`/product/${item.product?.slug}`" class="flex-shrink-0">
                                <img :src="item.product?.featured_image ? `/storage/${item.product.featured_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=200&h=200&fit=crop'"
                                    class="w-20 h-20 sm:w-28 sm:h-28 object-cover rounded-2xl">
                            </RouterLink>
                            <div class="flex-1 min-w-0 flex flex-col">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <RouterLink :to="`/product/${item.product?.slug}`"
                                            class="font-heading font-semibold text-sm sm:text-base hover:text-walnut transition-colors line-clamp-2" style="color:#1E1B18">
                                            {{ item.product?.name }}
                                        </RouterLink>
                                        <RouterLink v-if="item.product?.category" :to="`/shop/${item.product.category.slug}`"
                                            class="text-xs mt-1 inline-block font-medium" style="color:#C9A66B">
                                            {{ item.product.category.name }}
                                        </RouterLink>
                                        <p v-if="item.color" class="text-xs mt-1 flex items-center gap-1.5" style="color:#6D655F">
                                            <span class="w-2.5 h-2.5 rounded-full inline-block border" style="border-color:#E8DDD4; background:#EDE5D8"></span>
                                            {{ item.color }}
                                        </p>
                                    </div>
                                    <button @click="removeItem(item.id)"
                                        class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-all hover:bg-red-50 hover:text-red-500"
                                        style="color:#D4C4B0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex items-center justify-between mt-auto pt-3 flex-wrap gap-3">
                                    <div class="flex items-center rounded-xl overflow-hidden" style="border:1.5px solid #E8DDD4">
                                        <button @click="updateQty(item, item.quantity - 1)" :disabled="item.quantity <= 1"
                                            class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center transition-colors hover:bg-amber-50 disabled:opacity-30">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <span class="w-9 text-center font-bold text-sm" style="color:#1E1B18">{{ item.quantity }}</span>
                                        <button @click="updateQty(item, item.quantity + 1)"
                                            class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center transition-colors hover:bg-amber-50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="price-tag text-lg">৳{{ (item.price * item.quantity).toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>

                    <!-- Continue shopping -->
                    <div class="flex justify-between items-center pt-2 flex-wrap gap-3">
                        <RouterLink to="/shop" class="btn-outline text-sm py-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Continue Shopping
                        </RouterLink>
                        <button @click="cartStore.clearCart()" class="text-sm font-medium hover:text-red-500 transition-colors" style="color:#6D655F">
                            Clear Cart
                        </button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="card-premium p-6 sticky top-24 anim-fade-up delay-100">
                        <h2 class="font-heading font-bold text-xl mb-6" style="color:#1E1B18">Order Summary</h2>

                        <!-- Coupon -->
                        <div class="mb-5">
                            <div class="flex gap-2">
                                <input v-model="couponCode" type="text" placeholder="Enter coupon code"
                                    class="input-field text-sm flex-1 py-2.5 uppercase"
                                    @keyup.enter="applyCoupon">
                                <button @click="applyCoupon" class="btn-primary text-sm py-2.5 px-4">Apply</button>
                            </div>
                            <div v-if="cartStore.coupon" class="mt-2 flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ cartStore.coupon.code }} applied
                                <button @click="cartStore.removeCoupon()" class="text-red-400 hover:text-red-600 ml-auto">✕</button>
                            </div>
                        </div>

                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between text-sm">
                                <span style="color:#6D655F">Subtotal ({{ cartStore.count }} items)</span>
                                <span class="font-semibold">৳{{ cartStore.subtotal.toLocaleString() }}</span>
                            </div>
                            <div v-if="cartStore.discount > 0" class="flex justify-between text-sm text-green-600">
                                <span>Coupon Discount</span>
                                <span class="font-semibold">-৳{{ cartStore.discount.toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color:#6D655F">Delivery Charge</span>
                                <span :class="cartStore.deliveryCharge === 0 ? 'text-green-600 font-semibold' : 'font-semibold'">
                                    {{ cartStore.deliveryCharge === 0 ? 'FREE' : '৳' + cartStore.deliveryCharge }}
                                </span>
                            </div>
                            <p v-if="cartStore.deliveryCharge > 0" class="text-xs flex items-center gap-1.5" style="color:#C9A66B">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Add ৳{{ (5000 - cartStore.subtotal).toLocaleString() }} more for free delivery
                            </p>
                            <div class="border-t pt-3" style="border-color:#F0E8DE">
                                <div class="flex justify-between font-bold text-lg">
                                    <span style="color:#1E1B18">Total</span>
                                    <span class="price-tag">৳{{ cartStore.total.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>

                        <RouterLink to="/checkout" class="btn-primary w-full justify-center py-3.5 mb-4 text-base">
                            Proceed to Checkout
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </RouterLink>

                        <div class="flex justify-center gap-4 text-xs flex-wrap" style="color:#9B9087">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Secure checkout
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m2 0h6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                </svg>
                                Multiple payment
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useCartStore } from '../stores/cart.js'

const cartStore = useCartStore()
const couponCode = ref('')

async function updateQty(item, qty) {
    if (qty < 1) return
    await cartStore.updateQuantity(item.id, qty)
}

async function removeItem(id) {
    await cartStore.removeItem(id)
}

async function applyCoupon() {
    if (!couponCode.value) return
    await cartStore.applyCoupon(couponCode.value.toUpperCase())
    couponCode.value = ''
}
</script>

<style scoped>
.cart-row-enter-active, .cart-row-leave-active { transition: all 0.25s ease; }
.cart-row-enter-from, .cart-row-leave-to { opacity: 0; transform: translateX(12px); }
.cart-row-leave-active { position: absolute; }
</style>
