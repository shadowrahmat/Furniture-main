<template>
    <div>
        <div v-if="loading" class="space-y-4">
            <div class="skeleton h-10 w-48 rounded-xl"></div>
            <div class="skeleton h-32 rounded-2xl"></div>
            <div class="skeleton h-60 rounded-2xl"></div>
        </div>
        <div v-else-if="order">
            <RouterLink to="/account/orders" class="inline-flex items-center gap-1.5 text-sm font-medium mb-4" style="color:#8B5E3C">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Orders
            </RouterLink>

            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#9B9087">Order Number</p>
                    <h2 class="font-heading font-bold text-2xl" style="color:#1E1B18">{{ order.order_number }}</h2>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold" :style="statusStyle(order.status)">{{ order.status }}</span>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div class="card-premium p-5 anim-fade-up">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold" style="color:#1E1B18">Delivery Address</h3>
                    </div>
                    <div class="text-sm space-y-1 pl-13" style="color:#6D655F">
                        <p class="font-medium" style="color:#1E1B18">{{ order.full_name }}</p>
                        <p>{{ order.address_line1 }}</p>
                        <p v-if="order.address_line2">{{ order.address_line2 }}</p>
                        <p>{{ order.city }}, {{ order.country }}</p>
                        <p>{{ order.phone }}</p>
                    </div>
                </div>
                <div class="card-premium p-5 anim-fade-up delay-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m2 0h6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold" style="color:#1E1B18">Payment Info</h3>
                    </div>
                    <div class="text-sm space-y-1 pl-13" style="color:#6D655F">
                        <p>Method: <span class="font-medium" style="color:#1E1B18">{{ order.payment_method?.toUpperCase() }}</span></p>
                        <p>Status: <span class="font-medium" style="color:#1E1B18">{{ order.payment_status }}</span></p>
                        <p class="mt-2">Date: {{ new Date(order.created_at).toLocaleDateString() }}</p>
                    </div>
                </div>
            </div>

            <div class="card-premium p-5 anim-fade-up delay-200">
                <h3 class="font-semibold mb-4" style="color:#1E1B18">Items</h3>
                <div class="space-y-3">
                    <div v-for="item in order.items" :key="item.id" class="flex gap-3 items-center">
                        <img :src="item.product_image ? `/storage/${item.product_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=50&h=50&fit=crop'"
                            class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm" style="color:#1E1B18">{{ item.product_name }}</p>
                            <p class="text-xs" style="color:#6D655F">Qty: {{ item.quantity }} × ৳{{ Number(item.price).toLocaleString() }}</p>
                        </div>
                        <span class="font-bold price-tag flex-shrink-0">৳{{ Number(item.total).toLocaleString() }}</span>
                    </div>
                </div>
                <div class="border-t mt-4 pt-4 space-y-2" style="border-color:#F0E8DE">
                    <div class="flex justify-between text-sm"><span style="color:#6D655F">Subtotal</span><span class="font-semibold">৳{{ Number(order.subtotal).toLocaleString() }}</span></div>
                    <div v-if="order.discount > 0" class="flex justify-between text-sm text-green-600"><span>Discount</span><span class="font-semibold">-৳{{ Number(order.discount).toLocaleString() }}</span></div>
                    <div class="flex justify-between text-sm">
                        <span style="color:#6D655F">Delivery</span>
                        <span :class="Number(order.delivery_charge) === 0 ? 'text-green-600 font-semibold' : 'font-semibold'">{{ Number(order.delivery_charge) === 0 ? 'FREE' : '৳' + Number(order.delivery_charge).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-3 border-t" style="border-color:#F0E8DE">
                        <span>Total</span><span class="price-tag">৳{{ Number(order.total).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const order = ref(null)
const loading = ref(true)

function statusStyle(status) {
    const styles = {
        pending: 'background:#FEF3C7; color:#92400E',
        confirmed: 'background:#DBEAFE; color:#1E40AF',
        processing: 'background:#EDE9FE; color:#5B21B6',
        out_for_delivery: 'background:#FED7AA; color:#C2410C',
        completed: 'background:#D1FAE5; color:#065F46',
        cancelled: 'background:#FEE2E2; color:#991B1B',
    }
    return styles[status] || 'background:#F3F4F6; color:#374151'
}

onMounted(async () => {
    try { const { data } = await axios.get(`/orders/${route.params.id}`); order.value = data } catch (e) {}
    finally { loading.value = false }
})
</script>
