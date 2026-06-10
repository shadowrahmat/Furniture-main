<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-10 sm:py-14">
                <p class="label-tag mb-3" style="color:#E8C47A">Step 2 of 3</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white">Checkout</h1>
            </div>
        </section>

        <div class="max-w-5xl mx-auto px-4 py-8 sm:py-10">
            <div v-if="cartStore.items.length === 0" class="flex flex-col items-center text-center py-16 sm:py-24 anim-fade-up">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mb-6"
                    style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                    <svg class="w-14 h-14" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="font-heading font-bold text-2xl mb-3" style="color:#1E1B18">Your cart is empty</h2>
                <p class="mb-8" style="color:#6D655F">Add some products to your cart before checking out.</p>
                <RouterLink to="/shop" class="btn-primary">Browse Furniture</RouterLink>
            </div>

            <div v-else class="grid lg:grid-cols-5 gap-6 lg:gap-8">
                <!-- Form -->
                <div class="lg:col-span-3">
                    <form @submit.prevent="placeOrder" class="space-y-6">
                        <!-- Delivery Info -->
                        <div class="card-premium p-6 anim-fade-up">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h2 class="font-heading font-bold text-lg" style="color:#1E1B18">Delivery Information</h2>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Full Name *</label>
                                    <input v-model="form.full_name" type="text" class="input-field" required>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email *</label>
                                    <input v-model="form.email" type="email" class="input-field" required>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Phone *</label>
                                    <input v-model="form.phone" type="tel" class="input-field" required>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Address Line 1 *</label>
                                    <input v-model="form.address_line1" type="text" class="input-field" required>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Address Line 2</label>
                                    <input v-model="form.address_line2" type="text" class="input-field">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">City *</label>
                                    <input v-model="form.city" type="text" class="input-field" required>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Postal Code</label>
                                    <input v-model="form.postal_code" type="text" class="input-field">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Order Notes</label>
                                    <textarea v-model="form.notes" class="input-field" rows="3" placeholder="Special delivery instructions..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="card-premium p-6 anim-fade-up delay-100">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m2 0h6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                    </svg>
                                </div>
                                <h2 class="font-heading font-bold text-lg" style="color:#1E1B18">Payment Method</h2>
                            </div>
                            <div class="space-y-3">
                                <label v-for="method in paymentMethods" :key="method.value"
                                    class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all"
                                    :style="form.payment_method === method.value
                                        ? 'border-color:#8B5E3C; background:#FFF8F2'
                                        : 'border-color:#E8DDD4; background:white'">
                                    <input type="radio" :value="method.value" v-model="form.payment_method" class="accent-amber-700">
                                    <span class="text-2xl">{{ method.icon }}</span>
                                    <div>
                                        <p class="font-semibold text-sm" style="color:#1E1B18">{{ method.name }}</p>
                                        <p class="text-xs" style="color:#6D655F">{{ method.desc }}</p>
                                    </div>
                                    <span v-if="method.badge" class="ml-auto text-xs px-2 py-0.5 rounded-full" style="background:#F0E8DE; color:#8B5E3C; font-weight:600">{{ method.badge }}</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" :disabled="loading"
                            class="btn-primary w-full justify-center py-4 text-base disabled:opacity-50">
                            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ loading ? 'Placing Order...' : 'Place Order' }}
                            <svg v-if="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-2">
                    <div class="card-premium p-5 sticky top-24 anim-fade-up delay-200">
                        <h2 class="font-heading font-bold text-lg mb-4" style="color:#1E1B18">Order Summary</h2>

                        <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-1">
                            <div v-for="item in cartStore.items" :key="item.id" class="flex gap-3 items-center">
                                <img :src="item.product?.featured_image ? `/storage/${item.product.featured_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=50&h=50&fit=crop'"
                                    class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold line-clamp-1" style="color:#1E1B18">{{ item.product?.name }}</p>
                                    <p class="text-xs" style="color:#6D655F">Qty: {{ item.quantity }}</p>
                                </div>
                                <span class="text-sm font-semibold price-tag flex-shrink-0">৳{{ (item.price * item.quantity).toLocaleString() }}</span>
                            </div>
                        </div>

                        <div class="space-y-2 border-t pt-4" style="border-color:#F0E8DE">
                            <div class="flex justify-between text-sm">
                                <span style="color:#6D655F">Subtotal</span>
                                <span class="font-semibold">৳{{ cartStore.subtotal.toLocaleString() }}</span>
                            </div>
                            <div v-if="cartStore.discount > 0" class="flex justify-between text-sm text-green-600">
                                <span>Discount</span>
                                <span class="font-semibold">-৳{{ cartStore.discount.toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color:#6D655F">Delivery</span>
                                <span :class="cartStore.deliveryCharge === 0 ? 'text-green-600 font-semibold' : 'font-semibold'">
                                    {{ cartStore.deliveryCharge === 0 ? 'FREE' : '৳' + cartStore.deliveryCharge }}
                                </span>
                            </div>
                            <div class="border-t pt-3 mt-3" style="border-color:#F0E8DE">
                                <div class="flex justify-between font-bold text-xl">
                                    <span>Total</span>
                                    <span class="price-tag">৳{{ cartStore.total.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t space-y-2.5" style="border-color:#F0E8DE">
                            <div v-for="trust in trustPoints" :key="trust.label" class="flex items-center gap-2.5 text-xs" style="color:#6D655F">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" style="background:#F0FDF4; color:#3D7A50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                {{ trust.label }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import { useCartStore } from '../stores/cart.js'
import { useAuthStore } from '../stores/auth.js'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()
const loading = ref(false)

const user = computed(() => authStore.currentUser)

const form = reactive({
    full_name: user.value?.name || '',
    email: user.value?.email || '',
    phone: user.value?.phone || '',
    address_line1: '',
    address_line2: '',
    city: '',
    state: '',
    postal_code: '',
    country: 'Bangladesh',
    payment_method: 'cod',
    notes: '',
    coupon_code: cartStore.coupon?.code || '',
})

const paymentMethods = [
    { value: 'cod', icon: '💵', name: 'Cash on Delivery', desc: 'Pay when your furniture arrives', badge: 'Popular' },
    { value: 'bkash', icon: '📱', name: 'bKash', desc: 'Pay via bKash mobile banking', badge: 'Soon' },
    { value: 'nagad', icon: '💳', name: 'Nagad', desc: 'Pay via Nagad mobile banking', badge: 'Soon' },
]

const trustPoints = [
    { label: 'Secure payment processing' },
    { label: '100% quality guarantee' },
    { label: 'Easy 7-day returns' },
    { label: 'Professional delivery' },
]

async function placeOrder() {
    loading.value = true
    try {
        const { data } = await axios.post('/orders', {
            ...form,
            coupon_code: cartStore.coupon?.code || null,
        })
        await cartStore.fetchCart()
        router.push({ name: 'order-success', params: { orderNumber: data.order.order_number } })
    } catch (error) {
        const errors = error.response?.data?.errors
        if (errors) {
            const firstError = Object.values(errors)[0][0]
            toast.error(firstError)
        } else {
            toast.error(error.response?.data?.message || 'Order failed. Please try again.')
        }
    } finally {
        loading.value = false
    }
}
</script>
