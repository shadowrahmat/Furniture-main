<template>
    <!-- Overlay -->
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="cartStore.isOpen" class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm"
                @click="cartStore.closeCart()"></div>
        </Transition>

        <!-- Drawer -->
        <Transition name="slide-right">
            <div v-if="cartStore.isOpen"
                class="fixed top-0 right-0 h-full w-full max-w-sm z-50 flex flex-col"
                style="background:#FFFDF9; box-shadow:-4px 0 30px rgba(0,0,0,0.1)">

                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:#F0E8DE">
                    <div class="flex items-center gap-2">
                        <h2 class="font-heading font-bold text-lg" style="color:#1E1B18">Shopping Cart</h2>
                        <span class="w-6 h-6 rounded-full text-white text-xs flex items-center justify-center font-bold"
                            style="background:#8B5E3C">{{ cartStore.count }}</span>
                    </div>
                    <button @click="cartStore.closeCart()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Items -->
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
                    <div v-if="cartStore.items.length === 0" class="flex flex-col items-center justify-center h-full text-center">
                        <div class="text-6xl mb-4">🛒</div>
                        <h3 class="font-heading font-semibold text-lg mb-2" style="color:#1E1B18">Your cart is empty</h3>
                        <p class="text-sm mb-6" style="color:#6D655F">Add some beautiful furniture to get started</p>
                        <RouterLink to="/shop" @click="cartStore.closeCart()" class="btn-primary text-sm py-2.5 px-6">
                            Browse Furniture
                        </RouterLink>
                    </div>

                    <div v-for="item in cartStore.items" :key="item.id"
                        class="flex gap-3 bg-white rounded-xl p-3" style="border:1px solid #F0E8DE">
                        <img :src="item.product?.featured_image ? `/storage/${item.product.featured_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=80&h=80&fit=crop'"
                            class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <RouterLink :to="`/product/${item.product?.slug}`" @click="cartStore.closeCart()"
                                class="text-sm font-semibold line-clamp-1 hover:text-walnut" style="color:#1E1B18">
                                {{ item.product?.name }}
                            </RouterLink>
                            <p v-if="item.color" class="text-xs mt-0.5" style="color:#6D655F">{{ item.color }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center gap-1 rounded-lg overflow-hidden" style="border:1px solid #E8DDD4">
                                    <button @click="updateQty(item, item.quantity - 1)"
                                        class="w-6 h-6 flex items-center justify-center text-sm hover:bg-amber-50 disabled:opacity-30"
                                        :disabled="item.quantity <= 1">-</button>
                                    <span class="w-6 text-center text-xs font-semibold">{{ item.quantity }}</span>
                                    <button @click="updateQty(item, item.quantity + 1)"
                                        class="w-6 h-6 flex items-center justify-center text-sm hover:bg-amber-50">+</button>
                                </div>
                                <span class="price-tag text-sm">৳{{ (item.price * item.quantity).toLocaleString() }}</span>
                            </div>
                        </div>
                        <button @click="removeItem(item.id)" class="text-gray-300 hover:text-red-400 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div v-if="cartStore.items.length > 0" class="px-5 py-4 border-t" style="border-color:#F0E8DE; background:white">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span style="color:#6D655F">Subtotal</span>
                            <span class="font-semibold">৳{{ cartStore.subtotal.toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span style="color:#6D655F">Delivery</span>
                            <span class="font-semibold" :class="cartStore.deliveryCharge === 0 ? 'text-green-600' : ''">
                                {{ cartStore.deliveryCharge === 0 ? 'FREE' : '৳' + cartStore.deliveryCharge }}
                            </span>
                        </div>
                        <div class="flex justify-between font-bold text-base pt-2 border-t" style="border-color:#F0E8DE">
                            <span style="color:#1E1B18">Total</span>
                            <span class="price-tag">৳{{ cartStore.total.toLocaleString() }}</span>
                        </div>
                    </div>
                    <RouterLink to="/checkout" @click="cartStore.closeCart()"
                        class="btn-primary w-full justify-center text-sm py-3 mb-2">
                        Proceed to Checkout
                    </RouterLink>
                    <RouterLink to="/cart" @click="cartStore.closeCart()"
                        class="block text-center text-sm font-medium py-2 hover:text-walnut transition-colors" style="color:#6D655F">
                        View Cart
                    </RouterLink>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { useCartStore } from '../stores/cart.js'

const cartStore = useCartStore()

async function updateQty(item, qty) {
    if (qty < 1) return
    await cartStore.updateQuantity(item.id, qty)
}

async function removeItem(id) {
    await cartStore.removeItem(id)
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-right-enter-active, .slide-right-leave-active { transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
.slide-right-enter-from, .slide-right-leave-to { transform: translateX(100%); }
</style>
