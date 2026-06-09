<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Dashboard</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div v-for="stat in stats" :key="stat.label" class="card-premium p-4 text-center">
                <div class="text-3xl mb-2">{{ stat.icon }}</div>
                <p class="font-bold text-xl price-tag">{{ stat.value }}</p>
                <p class="text-xs mt-1" style="color:#6D655F">{{ stat.label }}</p>
            </div>
        </div>

        <h3 class="font-heading font-semibold text-lg mb-4" style="color:#1E1B18">Recent Orders</h3>
        <div v-if="orders.length === 0" class="text-center py-8">
            <p style="color:#6D655F">No orders yet</p>
            <RouterLink to="/shop" class="btn-primary mt-4 inline-flex">Start Shopping</RouterLink>
        </div>
        <div v-else class="space-y-3">
            <div v-for="order in orders" :key="order.id" class="card-premium p-4 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="font-semibold text-sm" style="color:#1E1B18">{{ order.order_number }}</p>
                    <p class="text-xs mt-0.5" style="color:#6D655F">{{ order.items?.length }} items • {{ formatDate(order.created_at) }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold price-tag">৳{{ Number(order.total).toLocaleString() }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold" :style="statusStyle(order.status)">
                        {{ order.status }}
                    </span>
                    <RouterLink :to="`/account/orders/${order.id}`" class="text-xs font-medium hover:text-walnut" style="color:#8B5E3C">View →</RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])
const stats = ref([
    { icon: '📦', value: '0', label: 'Total Orders' },
    { icon: '✅', value: '0', label: 'Completed' },
    { icon: '🚚', value: '0', label: 'In Progress' },
    { icon: '❤️', value: '0', label: 'Wishlist' },
])

function formatDate(d) { return new Date(d).toLocaleDateString() }
function statusStyle(status) {
    const styles = {
        pending: 'background:#FEF3C7; color:#92400E',
        completed: 'background:#D1FAE5; color:#065F46',
        cancelled: 'background:#FEE2E2; color:#991B1B',
    }
    return styles[status] || 'background:#F3F4F6; color:#374151'
}

onMounted(async () => {
    try {
        const { data } = await axios.get('/orders')
        orders.value = data.data
        stats.value[0].value = data.total
        stats.value[1].value = data.data.filter(o => o.status === 'completed').length
        stats.value[2].value = data.data.filter(o => !['completed', 'cancelled'].includes(o.status)).length
    } catch (e) {}
})
</script>
