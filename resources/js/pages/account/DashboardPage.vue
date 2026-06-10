<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Dashboard</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div v-for="(stat, idx) in stats" :key="stat.label" class="card-premium p-4 sm:p-5 anim-fade-up" :style="`animation-delay:${idx * 0.06}s`">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" :style="stat.bg">
                    <svg class="w-5 h-5" :style="stat.color" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="stat.icon"></svg>
                </div>
                <p class="font-heading font-bold text-2xl" style="color:#1E1B18">{{ stat.value }}</p>
                <p class="text-xs mt-1" style="color:#6D655F">{{ stat.label }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading font-semibold text-lg" style="color:#1E1B18">Recent Orders</h3>
            <RouterLink v-if="orders.length" to="/account/orders" class="text-sm font-medium" style="color:#8B5E3C">View All →</RouterLink>
        </div>

        <div v-if="orders.length === 0" class="flex flex-col items-center text-center py-12 sm:py-16 anim-fade-up">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-5"
                style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                <svg class="w-10 h-10" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="font-heading font-bold text-lg mb-2" style="color:#1E1B18">No orders yet</h3>
            <p class="mb-6 text-sm" style="color:#6D655F">Your order history will show up here once you place one.</p>
            <RouterLink to="/shop" class="btn-primary">
                Start Shopping
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </RouterLink>
        </div>
        <div v-else class="space-y-3">
            <div v-for="(order, idx) in orders" :key="order.id" class="card-premium p-4 sm:p-5 flex items-center justify-between flex-wrap gap-3 anim-fade-up" :style="`animation-delay:${idx * 0.05}s`">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                        <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-sm" style="color:#1E1B18">{{ order.order_number }}</p>
                        <p class="text-xs mt-0.5" style="color:#6D655F">{{ order.items?.length }} items • {{ formatDate(order.created_at) }}</p>
                    </div>
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
import { useWishlistStore } from '../../stores/wishlist.js'

const orders = ref([])
const wishlistStore = useWishlistStore()

const stats = ref([
    { value: '0', label: 'Total Orders', bg: 'background:#FFF1E0', color: 'color:#C9A66B', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>' },
    { value: '0', label: 'Completed', bg: 'background:#E6F4EA', color: 'color:#3D7A50', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' },
    { value: '0', label: 'In Progress', bg: 'background:#EAF1FB', color: 'color:#3B6EC9', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
    { value: '0', label: 'Wishlist', bg: 'background:#FBEAEE', color: 'color:#D9647F', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>' },
])

function formatDate(d) { return new Date(d).toLocaleDateString() }
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
    try {
        const { data } = await axios.get('/orders')
        orders.value = data.data
        stats.value[0].value = data.total
        stats.value[1].value = data.data.filter(o => o.status === 'completed').length
        stats.value[2].value = data.data.filter(o => !['completed', 'cancelled'].includes(o.status)).length
    } catch (e) {}

    try {
        await wishlistStore.fetchWishlist()
        stats.value[3].value = wishlistStore.items.length
    } catch (e) {}
})
</script>
