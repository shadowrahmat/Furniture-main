<template>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="section-title mb-8">Shopping Cart</h1>

        <div v-if="cartStore.items.length === 0" class="text-center py-20">
            <div class="text-8xl mb-6">🛒</div>
            <h2 class="font-heading font-bold text-2xl mb-3" style="color:#1E1B18">Your cart is empty</h2>
            <p class="mb-8" style="color:#6D655F">Start adding beautiful furniture to your cart</p>
            <RouterLink to="/shop" class="btn-primary">Browse Furniture</RouterLink>
        </div>

        <div v-else class="grid lg:grid-cols-3 gap-8">
            <!-- Cart items -->
            <div class="lg:col-span-2 space-y-4">
                <div v-for="item in cartStore.items" :key="item.id"
                    class="card-premium p-5 flex gap-4 items-start">
                    <img :src="item.product?.featured_image ? `/storage/${item.product.featured_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=100&h=100&fit=crop'"
                        class="w-24 h-24 object-cover rounded-xl flex-shrink-0">
                    <div class="flex-1">
                        <RouterLink :to="`/product/${item.product?.slug}`"
                            class="font-heading font-semibold text-base hover:text-walnut transition-colors" style="color:#1E1B18">
                            {{ item.product?.name }}
                        </RouterLink>
                        <RouterLink v-if="item.product?.category" :to="`/shop/${item.product.category.slug}`"
                            class="text-xs mt-0.5 block" style="color:#C9A66B">
                            {{ item.product.category.name }}
                        </RouterLink>
                        <p v-if="item.color" class="text-xs mt-1" style="color:#6D655F">Color: {{ item.color }}</p>
                        <div class="flex items-center justify-between mt-3 flex-wrap gap-3">
                            <div class="flex items-center rounded-xl overflow-hidden border-2" style="border-color:#E8DDD4">
                                <button @click="updateQty(item, item.quantity - 1)" :disabled="item.quantity <= 1"
                                    class="w-9 h-9 flex items-center justify-center hover:bg-amber-50 disabled:opacity-30 text-lg">-</button>
                                <span class="w-10 text-center font-semibold text-sm">{{ item.quantity }}</span>
                                <button @click="updateQty(item, item.quantity + 1)"
                                    class="w-9 h-9 flex items-center justify-center hover:bg-amber-50 text-lg">+</button>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="price-tag text-lg">৳{{ (item.price * item.quantity).toLocaleString() }}</span>
                                <button @click="removeItem(item.id)" class="text-gray-300 hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Continue shopping -->
                <div class="flex justify-between items-center pt-2">
                    <RouterLink to="/shop" class="btn-outline text-sm py-2.5">← Continue Shopping</RouterLink>
                    <button @click="cartStore.clearCart()" class="text-sm font-medium hover:text-red-500 transition-colors" style="color:#6D655F">
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="card-premium p-6 sticky top-24">
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
                            <span>✓</span> {{ cartStore.coupon.code }} applied
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
                        <p v-if="cartStore.deliveryCharge > 0" class="text-xs" style="color:#C9A66B">
                            Add ৳{{ (5000 - cartStore.subtotal).toLocaleString() }} more for free delivery
                        </p>
                        <div class="border-t pt-3" style="border-color:#F0E8DE">
                            <div class="flex justify-between font-bold text-lg">
                                <span style="color:#1E1B18">Total</span>
                                <span class="price-tag">৳{{ cartStore.total.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <RouterLink to="/checkout" class="btn-primary w-full justify-center py-3.5 mb-3 text-base">
                        Proceed to Checkout
                    </RouterLink>

                    <div class="flex justify-center gap-4 text-xs" style="color:#6D655F">
                        <span>🔒 Secure checkout</span>
                        <span>💳 Multiple payment</span>
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
