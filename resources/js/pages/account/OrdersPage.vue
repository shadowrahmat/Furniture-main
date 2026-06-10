<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Orders</h2>
        <div v-if="loading" class="space-y-3">
            <div v-for="n in 5" :key="n" class="skeleton h-24 rounded-2xl"></div>
        </div>
        <div v-else-if="orders.length === 0" class="flex flex-col items-center text-center py-12 sm:py-16 anim-fade-up">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-5"
                style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                <svg class="w-10 h-10" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="font-heading font-bold text-lg mb-2" style="color:#1E1B18">No orders yet</h3>
            <p class="mb-6 text-sm" style="color:#6D655F">Start exploring our collection to place your first order.</p>
            <RouterLink to="/shop" class="btn-primary">
                Start Shopping
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </RouterLink>
        </div>
        <div v-else class="space-y-4">
            <RouterLink v-for="(order, idx) in orders" :key="order.id" :to="`/account/orders/${order.id}`"
                class="card-premium p-5 flex items-center gap-4 anim-fade-up transition-transform hover:-translate-y-0.5" :style="`animation-delay:${Math.min(idx, 6) * 0.05}s`">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 grad-walnut">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-semibold" style="color:#1E1B18">{{ order.order_number }}</p>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" :style="statusStyle(order.status)">{{ order.status }}</span>
                    </div>
                    <p class="text-xs mt-1" style="color:#6D655F">{{ order.items?.length || 0 }} items • {{ new Date(order.created_at).toLocaleDateString() }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="price-tag font-bold">৳{{ Number(order.total).toLocaleString() }}</p>
                    <span class="text-xs font-medium" style="color:#8B5E3C">View Details →</span>
                </div>
            </RouterLink>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])
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
    try { const { data } = await axios.get('/orders'); orders.value = data.data } catch (e) {}
    finally { loading.value = false }
})
</script>
