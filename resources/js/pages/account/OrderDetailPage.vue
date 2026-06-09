<template>
    <div>
        <div v-if="loading" class="space-y-4">
            <div class="skeleton h-32 rounded-xl"></div>
            <div class="skeleton h-60 rounded-xl"></div>
        </div>
        <div v-else-if="order">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-heading font-bold text-2xl" style="color:#1E1B18">Order #{{ order.order_number }}</h2>
                <span class="px-4 py-2 rounded-full text-sm font-semibold" :style="statusStyle(order.status)">{{ order.status }}</span>
            </div>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="card-premium p-5">
                    <h3 class="font-semibold mb-3" style="color:#1E1B18">Delivery Address</h3>
                    <p class="text-sm" style="color:#6D655F">{{ order.full_name }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.address_line1 }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.city }}, {{ order.country }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.phone }}</p>
                </div>
                <div class="card-premium p-5">
                    <h3 class="font-semibold mb-3" style="color:#1E1B18">Payment Info</h3>
                    <p class="text-sm" style="color:#6D655F">Method: {{ order.payment_method?.toUpperCase() }}</p>
                    <p class="text-sm" style="color:#6D655F">Status: {{ order.payment_status }}</p>
                    <p class="text-sm mt-2" style="color:#6D655F">Date: {{ new Date(order.created_at).toLocaleDateString() }}</p>
                </div>
            </div>
            <div class="card-premium p-5">
                <h3 class="font-semibold mb-4" style="color:#1E1B18">Items</h3>
                <div class="space-y-3">
                    <div v-for="item in order.items" :key="item.id" class="flex gap-3 items-center">
                        <img :src="item.product_image ? `/storage/${item.product_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=50&h=50&fit=crop'"
                            class="w-14 h-14 rounded-lg object-cover">
                        <div class="flex-1">
                            <p class="font-semibold text-sm" style="color:#1E1B18">{{ item.product_name }}</p>
                            <p class="text-xs" style="color:#6D655F">Qty: {{ item.quantity }} × ৳{{ Number(item.price).toLocaleString() }}</p>
                        </div>
                        <span class="font-bold price-tag">৳{{ Number(item.total).toLocaleString() }}</span>
                    </div>
                </div>
                <div class="border-t mt-4 pt-4 space-y-2" style="border-color:#F0E8DE">
                    <div class="flex justify-between text-sm"><span style="color:#6D655F">Subtotal</span><span>৳{{ Number(order.subtotal).toLocaleString() }}</span></div>
                    <div v-if="order.discount > 0" class="flex justify-between text-sm text-green-600"><span>Discount</span><span>-৳{{ Number(order.discount).toLocaleString() }}</span></div>
                    <div class="flex justify-between text-sm"><span style="color:#6D655F">Delivery</span><span>{{ order.delivery_charge == 0 ? 'FREE' : '৳' + Number(order.delivery_charge).toLocaleString() }}</span></div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t" style="border-color:#F0E8DE">
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

function statusStyle(s) {
    const m = { pending: 'background:#FEF3C7;color:#92400E', completed: 'background:#D1FAE5;color:#065F46', cancelled: 'background:#FEE2E2;color:#991B1B' }
    return m[s] || 'background:#F3F4F6;color:#374151'
}

onMounted(async () => {
    try { const { data } = await axios.get(`/orders/${route.params.id}`); order.value = data } catch (e) {}
    finally { loading.value = false }
})
</script>
