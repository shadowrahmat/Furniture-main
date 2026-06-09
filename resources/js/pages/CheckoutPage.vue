<template>
    <div class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="section-title mb-8">Checkout</h1>

        <div v-if="cartStore.items.length === 0" class="text-center py-20">
            <p class="text-xl mb-4" style="color:#6D655F">Your cart is empty</p>
            <RouterLink to="/shop" class="btn-primary">Browse Furniture</RouterLink>
        </div>

        <div v-else class="grid lg:grid-cols-5 gap-8">
            <!-- Form -->
            <div class="lg:col-span-3">
                <form @submit.prevent="placeOrder" class="space-y-6">
                    <!-- Delivery Info -->
                    <div class="card-premium p-6">
                        <h2 class="font-heading font-bold text-lg mb-5" style="color:#1E1B18">Delivery Information</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
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
                            <div class="col-span-2">
                                <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Address Line 1 *</label>
                                <input v-model="form.address_line1" type="text" class="input-field" required>
                            </div>
                            <div class="col-span-2">
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
                            <div class="col-span-2">
                                <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Order Notes</label>
                                <textarea v-model="form.notes" class="input-field" rows="3" placeholder="Special delivery instructions..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="card-premium p-6">
                        <h2 class="font-heading font-bold text-lg mb-5" style="color:#1E1B18">Payment Method</h2>
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
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="card-premium p-5 sticky top-24">
                    <h2 class="font-heading font-bold text-lg mb-4" style="color:#1E1B18">Order Summary</h2>

                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
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

                    <div class="mt-4 pt-4 border-t space-y-2" style="border-color:#F0E8DE">
                        <div v-for="trust in trustPoints" :key="trust" class="flex items-center gap-2 text-xs" style="color:#6D655F">
                            <span style="color:#6B7A52">✓</span> {{ trust }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
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
    'Secure payment processing',
    '100% quality guarantee',
    'Easy 7-day returns',
    'Professional delivery',
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
