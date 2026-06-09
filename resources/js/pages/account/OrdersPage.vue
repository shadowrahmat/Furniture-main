<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Orders</h2>
        <div v-if="loading" class="space-y-3">
            <div v-for="n in 5" :key="n" class="skeleton h-20 rounded-xl"></div>
        </div>
        <div v-else-if="orders.length === 0" class="text-center py-12">
            <div class="text-5xl mb-4">📦</div>
            <p class="mb-4" style="color:#6D655F">No orders yet</p>
            <RouterLink to="/shop" class="btn-primary">Start Shopping</RouterLink>
        </div>
        <div v-else class="space-y-4">
            <div v-for="order in orders" :key="order.id" class="card-premium p-5">
                <div class="flex items-start justify-between mb-3 flex-wrap gap-2">
                    <div>
                        <p class="font-semibold" style="color:#1E1B18">{{ order.order_number }}</p>
                        <p class="text-xs mt-0.5" style="color:#6D655F">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold" :style="statusStyle(order.status)">{{ order.status }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm" style="color:#6D655F">{{ order.items?.length || 0 }} items</span>
                    <div class="flex items-center gap-4">
                        <span class="price-tag font-bold">৳{{ Number(order.total).toLocaleString() }}</span>
                        <RouterLink :to="`/account/orders/${order.id}`" class="text-sm font-medium" style="color:#8B5E3C">View Details →</RouterLink>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])
const loading = ref(true)

function statusStyle(s) {
    const m = { pending: 'background:#FEF3C7;color:#92400E', completed: 'background:#D1FAE5;color:#065F46', cancelled: 'background:#FEE2E2;color:#991B1B' }
    return m[s] || 'background:#F3F4F6;color:#374151'
}

onMounted(async () => {
    try { const { data } = await axios.get('/orders'); orders.value = data.data } catch (e) {}
    finally { loading.value = false }
})
</script>
