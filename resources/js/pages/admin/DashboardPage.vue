<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Dashboard</h1>
            <span class="text-sm" style="color:#6D655F">{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
        </div>

        <!-- Stats Grid -->
        <div v-if="loading" class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div v-for="n in 8" :key="n" class="skeleton h-28 rounded-xl"></div>
        </div>
        <div v-else class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">{{ stat.label }}</p>
                        <p class="font-heading font-bold text-2xl" style="color:#1E1B18">{{ stat.value }}</p>
                        <p class="text-xs mt-1" :style="stat.change >= 0 ? 'color:#059669' : 'color:#DC2626'">
                            {{ stat.change >= 0 ? '↑' : '↓' }} {{ Math.abs(stat.change) }}% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl" :style="`background:${stat.bg}`">
                        {{ stat.icon }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <!-- Recent Orders -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border" style="border-color:#F0E8DE">
                <div class="flex items-center justify-between p-5 border-b" style="border-color:#F0E8DE">
                    <h2 class="font-semibold" style="color:#1E1B18">Recent Orders</h2>
                    <RouterLink to="/admin/orders" class="text-xs font-medium" style="color:#8B5E3C">View All →</RouterLink>
                </div>
                <div class="divide-y" style="divide-color:#F0E8DE">
                    <div v-if="ordersLoading" class="p-5">
                        <div v-for="n in 5" :key="n" class="skeleton h-10 rounded mb-3"></div>
                    </div>
                    <div v-else-if="recentOrders.length === 0" class="p-8 text-center" style="color:#6D655F">No orders yet</div>
                    <div v-else v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:#8B5E3C">
                                {{ order.full_name?.charAt(0) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium" style="color:#1E1B18">{{ order.order_number }}</p>
                                <p class="text-xs" style="color:#6D655F">{{ order.full_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold price-tag">৳{{ Number(order.total).toLocaleString() }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full" :style="statusStyle(order.status)">{{ order.status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-xl shadow-sm border" style="border-color:#F0E8DE">
                <div class="flex items-center justify-between p-5 border-b" style="border-color:#F0E8DE">
                    <h2 class="font-semibold" style="color:#1E1B18">Top Products</h2>
                    <RouterLink to="/admin/products" class="text-xs font-medium" style="color:#8B5E3C">View All →</RouterLink>
                </div>
                <div class="p-4 space-y-3">
                    <div v-if="productsLoading" v-for="n in 5" :key="n" class="skeleton h-12 rounded"></div>
                    <div v-else-if="topProducts.length === 0" class="py-8 text-center" style="color:#6D655F">No products</div>
                    <div v-else v-for="(product, i) in topProducts" :key="product.id" class="flex items-center gap-3">
                        <span class="w-5 text-xs font-bold" style="color:#C9A66B">{{ i + 1 }}</span>
                        <img :src="product.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=40&h=40&fit=crop'"
                            class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color:#1E1B18">{{ product.name }}</p>
                            <p class="text-xs" style="color:#6D655F">{{ product.sales_count || 0 }} sold</p>
                        </div>
                        <span class="text-sm font-bold price-tag flex-shrink-0">৳{{ Number(product.price).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div v-if="lowStock.length > 0" class="bg-white rounded-xl shadow-sm border mb-6" style="border-color:#F0E8DE">
            <div class="flex items-center gap-2 p-5 border-b" style="border-color:#F0E8DE">
                <span class="text-xl">⚠️</span>
                <h2 class="font-semibold" style="color:#1E1B18">Low Stock Alert</h2>
                <span class="ml-auto px-2 py-0.5 rounded-full text-xs font-bold text-white" style="background:#D64545">{{ lowStock.length }}</span>
            </div>
            <div class="p-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <div v-for="product in lowStock" :key="product.id" class="flex items-center gap-3 p-3 rounded-lg" style="background:#FEF2F2">
                    <img :src="product.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=40&h=40&fit=crop'"
                        class="w-10 h-10 rounded-lg object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" style="color:#1E1B18">{{ product.name }}</p>
                        <p class="text-xs font-semibold" style="color:#DC2626">Only {{ product.stock }} left</p>
                    </div>
                    <RouterLink :to="`/admin/products/${product.id}/edit`" class="text-xs px-2 py-1 rounded" style="background:#DC2626;color:white">Edit</RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const loading = ref(true)
const ordersLoading = ref(true)
const productsLoading = ref(true)
const stats = ref([])
const recentOrders = ref([])
const topProducts = ref([])
const lowStock = ref([])

function statusStyle(s) {
    const m = { pending: 'background:#FEF3C7;color:#92400E', processing: 'background:#DBEAFE;color:#1E40AF', shipped: 'background:#F3E8FF;color:#7C3AED', completed: 'background:#D1FAE5;color:#065F46', cancelled: 'background:#FEE2E2;color:#991B1B' }
    return m[s] || 'background:#F3F4F6;color:#374151'
}

onMounted(async () => {
    try {
        const { data } = await axios.get('/admin/dashboard')
        stats.value = [
            { label: 'Total Revenue', value: '৳' + Number(data.revenue || 0).toLocaleString(), change: data.revenue_change || 0, icon: '💰', bg: '#FEF9EE' },
            { label: 'Total Orders', value: data.total_orders || 0, change: data.orders_change || 0, icon: '📦', bg: '#EEF2FF' },
            { label: 'Pending Orders', value: data.pending_orders || 0, change: 0, icon: '⏳', bg: '#FEF3C7' },
            { label: 'Completed', value: data.completed_orders || 0, change: data.completed_change || 0, icon: '✅', bg: '#D1FAE5' },
            { label: 'Total Customers', value: data.total_customers || 0, change: data.customers_change || 0, icon: '👥', bg: '#F0FDF4' },
            { label: 'Total Products', value: data.total_products || 0, change: 0, icon: '🪑', bg: '#FFF7ED' },
            { label: 'Low Stock', value: data.low_stock_count || 0, change: 0, icon: '⚠️', bg: '#FFF1F2' },
            { label: 'Active Coupons', value: data.active_coupons || 0, change: 0, icon: '🏷️', bg: '#F0F9FF' },
        ]
        recentOrders.value = data.recent_orders || []
        topProducts.value = data.top_products || []
        lowStock.value = data.low_stock || []
    } catch (e) {
        stats.value = [
            { label: 'Total Revenue', value: '৳0', change: 0, icon: '💰', bg: '#FEF9EE' },
            { label: 'Total Orders', value: 0, change: 0, icon: '📦', bg: '#EEF2FF' },
            { label: 'Pending Orders', value: 0, change: 0, icon: '⏳', bg: '#FEF3C7' },
            { label: 'Completed', value: 0, change: 0, icon: '✅', bg: '#D1FAE5' },
            { label: 'Total Customers', value: 0, change: 0, icon: '👥', bg: '#F0FDF4' },
            { label: 'Total Products', value: 0, change: 0, icon: '🪑', bg: '#FFF7ED' },
            { label: 'Low Stock', value: 0, change: 0, icon: '⚠️', bg: '#FFF1F2' },
            { label: 'Active Coupons', value: 0, change: 0, icon: '🏷️', bg: '#F0F9FF' },
        ]
    } finally {
        loading.value = false
        ordersLoading.value = false
        productsLoading.value = false
    }
})
</script>
