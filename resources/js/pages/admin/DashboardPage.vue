<template>
    <div>

        <!-- Page Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Dashboard</h1>
                <p class="adm-page-sub">{{ today }}</p>
            </div>
            <RouterLink to="/admin/products/create" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </RouterLink>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <template v-if="loading">
                <div v-for="i in 8" :key="i" class="stat-skeleton"></div>
            </template>
            <template v-else>
                <div v-for="s in stats" :key="s.label" class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">{{ s.label }}</div>
                        <div class="stat-icon-wrap" :style="`background:${s.bg}`">
                            <span v-html="s.svg" class="stat-icon-svg"></span>
                        </div>
                    </div>
                    <div class="stat-value">{{ s.value }}</div>
                    <div v-if="s.change !== 0" class="stat-change" :class="s.change > 0 ? 'positive' : 'negative'">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                :d="s.change > 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"/>
                        </svg>
                        {{ Math.abs(s.change) }}% this month
                    </div>
                    <div v-else class="stat-neutral">No change</div>
                </div>
            </template>
        </div>

        <!-- Middle Row -->
        <div class="mid-grid">

            <!-- Recent Orders -->
            <div class="adm-card col-span-2">
                <div class="adm-card-header">
                    <h2 class="adm-card-title">Recent Orders</h2>
                    <RouterLink to="/admin/orders" class="adm-card-link">View all →</RouterLink>
                </div>
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th class="hidden md:table-cell">Customer</th>
                                <th>Amount</th>
                                <th class="hidden sm:table-cell">Status</th>
                                <th class="hidden lg:table-cell">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="ordersLoading">
                                <td colspan="5" class="py-10 text-center" style="color:#94A3B8">Loading orders…</td>
                            </tr>
                            <tr v-else-if="!recentOrders.length">
                                <td colspan="5" class="py-10 text-center" style="color:#94A3B8">No orders yet</td>
                            </tr>
                            <tr v-else v-for="order in recentOrders" :key="order.id">
                                <td>
                                    <RouterLink :to="`/admin/orders/${order.id}`" class="adm-link font-semibold">
                                        {{ order.order_number }}
                                    </RouterLink>
                                </td>
                                <td class="hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                            style="background:#8B5E3C">
                                            {{ order.full_name?.charAt(0) }}
                                        </div>
                                        <span style="color:#334155">{{ order.full_name }}</span>
                                    </div>
                                </td>
                                <td class="font-bold" style="color:#8B5E3C; font-family:'Poppins',sans-serif">
                                    ৳{{ Number(order.total).toLocaleString() }}
                                </td>
                                <td class="hidden sm:table-cell">
                                    <span class="adm-badge" :class="`badge-${order.status}`">{{ order.status }}</span>
                                </td>
                                <td class="hidden lg:table-cell" style="color:#94A3B8">
                                    {{ new Date(order.created_at).toLocaleDateString('en-GB', { day:'numeric', month:'short' }) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Products -->
            <div class="adm-card">
                <div class="adm-card-header">
                    <h2 class="adm-card-title">Top Products</h2>
                    <RouterLink to="/admin/products" class="adm-card-link">View all →</RouterLink>
                </div>
                <div class="top-prod-list">
                    <div v-if="productsLoading" class="top-prod-empty">
                        <p>Loading…</p>
                    </div>
                    <div v-else-if="!topProducts.length" class="top-prod-empty">
                        <svg width="28" height="28" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                        <p>No sales data yet</p>
                    </div>
                    <div v-else v-for="(p, i) in topProducts" :key="p.id" class="top-prod-row">
                        <div class="top-prod-rank" :class="`rank-${i + 1}`">{{ i + 1 }}</div>
                        <img :src="p.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=40&h=40&fit=crop'"
                            class="top-prod-img">
                        <div class="top-prod-info">
                            <p class="top-prod-name">{{ p.name }}</p>
                            <div class="top-prod-meta">
                                <span class="top-prod-sold">{{ p.sales_count || 0 }} sold</span>
                                <span class="top-prod-price">৳{{ Number(p.price).toLocaleString() }}</span>
                            </div>
                            <div class="top-prod-bar-track">
                                <div class="top-prod-bar-fill" :style="`width:${salesPercent(p)}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div v-if="lowStock.length" class="adm-card mt-6">
            <div class="adm-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#FEF2F2">
                        <svg width="14" height="14" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <h2 class="adm-card-title">Low Stock Alert</h2>
                    <span class="adm-badge badge-danger ml-1">{{ lowStock.length }}</span>
                </div>
                <RouterLink to="/admin/products?status=low_stock" class="adm-card-link">Manage →</RouterLink>
            </div>
            <div class="low-stock-grid">
                <div v-for="p in lowStock" :key="p.id" class="low-stock-item">
                    <img :src="p.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=40&h=40&fit=crop'"
                        class="low-stock-img">
                    <div class="low-stock-info">
                        <p class="low-stock-name">{{ p.name }}</p>
                        <span class="low-stock-pill" :class="stockSeverity(p.stock)">
                            <span class="low-stock-dot"></span>
                            {{ p.stock === 0 ? 'Out of stock' : `${p.stock} left` }}
                        </span>
                    </div>
                    <RouterLink :to="`/admin/products/${p.id}/edit`" class="low-stock-edit" title="Edit product">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </RouterLink>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-grid mt-6">
            <RouterLink v-for="q in quickActions" :key="q.to" :to="q.to" class="quick-action-card">
                <div class="quick-action-icon" :style="`background:${q.bg}`" v-html="q.svg"></div>
                <div>
                    <div class="quick-action-label">{{ q.label }}</div>
                    <div class="quick-action-desc">{{ q.desc }}</div>
                </div>
                <svg class="ml-auto flex-shrink-0" width="16" height="16" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </RouterLink>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const loading         = ref(true)
const ordersLoading   = ref(true)
const productsLoading = ref(true)
const stats           = ref([])
const recentOrders    = ref([])
const topProducts     = ref([])
const lowStock        = ref([])

const today = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })

const revSvg  = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
const ordSvg  = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`
const pendSvg = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 3"/></svg>`
const doneSvg = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
const custSvg = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
const prodSvg = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>`
const lowSvg  = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`
const cupSvg  = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>`

const maxTopProductSales = computed(() => Math.max(...topProducts.value.map(p => p.sales_count || 0), 1))

function salesPercent(p) {
    return Math.max(((p.sales_count || 0) / maxTopProductSales.value) * 100, 4)
}

function stockSeverity(stock) {
    if (stock === 0) return 'severity-out'
    if (stock <= 2) return 'severity-critical'
    return 'severity-warn'
}

const quickActions = [
    { to: '/admin/products/create', label: 'Add Product',    desc: 'List a new item',      bg: 'rgba(139,94,60,0.1)',  svg: prodSvg },
    { to: '/admin/orders',          label: 'Manage Orders',  desc: 'View & update orders', bg: 'rgba(59,130,246,0.1)', svg: ordSvg  },
    { to: '/admin/coupons',         label: 'Create Coupon',  desc: 'Add a discount code',  bg: 'rgba(16,185,129,0.1)', svg: cupSvg  },
    { to: '/admin/settings',        label: 'Store Settings', desc: 'Configure your store', bg: 'rgba(99,102,241,0.1)', svg: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>` },
]

onMounted(async () => {
    try {
        const { data } = await axios.get('/admin/dashboard')
        stats.value = [
            { label: 'Total Revenue',     value: '৳' + Number(data.revenue || 0).toLocaleString(),    change: data.revenue_change   || 0, svg: revSvg,  bg: '#EFF6FF' },
            { label: 'Total Orders',      value: data.total_orders     || 0,                          change: data.orders_change    || 0, svg: ordSvg,  bg: '#F0FDF4' },
            { label: 'Pending Orders',    value: data.pending_orders   || 0,                          change: 0,                         svg: pendSvg, bg: '#FFFBEB' },
            { label: 'Completed',         value: data.completed_orders || 0,                          change: data.completed_change || 0, svg: doneSvg, bg: '#F0FDF4' },
            { label: 'Total Customers',   value: data.total_customers  || 0,                          change: data.customers_change || 0, svg: custSvg, bg: '#F5F3FF' },
            { label: 'Total Products',    value: data.total_products   || 0,                          change: 0,                         svg: prodSvg, bg: '#FFF7ED' },
            { label: 'Low Stock',         value: data.low_stock_count  || 0,                          change: 0,                         svg: lowSvg,  bg: '#FFF1F2' },
            { label: 'Active Coupons',    value: data.active_coupons   || 0,                          change: 0,                         svg: cupSvg,  bg: '#F0F9FF' },
        ]
        recentOrders.value  = data.recent_orders || []
        topProducts.value   = data.top_products  || []
        lowStock.value      = data.low_stock      || []
    } catch {
        stats.value = [
            { label: 'Total Revenue',   value: '৳0', change: 0, svg: revSvg,  bg: '#EFF6FF' },
            { label: 'Total Orders',    value: 0,    change: 0, svg: ordSvg,  bg: '#F0FDF4' },
            { label: 'Pending Orders',  value: 0,    change: 0, svg: pendSvg, bg: '#FFFBEB' },
            { label: 'Completed',       value: 0,    change: 0, svg: doneSvg, bg: '#F0FDF4' },
            { label: 'Total Customers', value: 0,    change: 0, svg: custSvg, bg: '#F5F3FF' },
            { label: 'Total Products',  value: 0,    change: 0, svg: prodSvg, bg: '#FFF7ED' },
            { label: 'Low Stock',       value: 0,    change: 0, svg: lowSvg,  bg: '#FFF1F2' },
            { label: 'Active Coupons',  value: 0,    change: 0, svg: cupSvg,  bg: '#F0F9FF' },
        ]
    } finally {
        loading.value = ordersLoading.value = productsLoading.value = false
    }
})
</script>
