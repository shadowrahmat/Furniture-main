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
                <div class="space-y-1 px-1">
                    <div v-if="productsLoading" class="py-8 text-center" style="color:#94A3B8">Loading…</div>
                    <div v-else-if="!topProducts.length" class="py-8 text-center" style="color:#94A3B8">No data</div>
                    <div v-else v-for="(p, i) in topProducts" :key="p.id" class="top-prod-row">
                        <span class="top-prod-rank">#{{ i + 1 }}</span>
                        <img :src="p.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=40&h=40&fit=crop'"
                            class="w-9 h-9 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color:#1E293B">{{ p.name }}</p>
                            <p class="text-xs" style="color:#94A3B8">{{ p.sales_count || 0 }} sold</p>
                        </div>
                        <span class="text-sm font-bold flex-shrink-0" style="color:#8B5E3C;font-family:'Poppins',sans-serif">
                            ৳{{ Number(p.price).toLocaleString() }}
                        </span>
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
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <div v-for="p in lowStock" :key="p.id" class="low-stock-item">
                    <img :src="p.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=40&h=40&fit=crop'"
                        class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" style="color:#1E293B">{{ p.name }}</p>
                        <p class="text-xs font-semibold" style="color:#DC2626">{{ p.stock }} units left</p>
                    </div>
                    <RouterLink :to="`/admin/products/${p.id}/edit`"
                        class="text-xs px-2.5 py-1 rounded-lg font-semibold flex-shrink-0"
                        style="background:#FEE2E2; color:#DC2626">Edit</RouterLink>
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
import { ref, onMounted } from 'vue'
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

<style scoped>
/* ── Page header ──────────────────────────────── */
.adm-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
}
.adm-page-title {
    font-family: 'Inter', sans-serif;
    font-size: 22px; font-weight: 700; color: #0F172A;
    letter-spacing: -0.02em;
}
.adm-page-sub { font-size: 13px; color: #94A3B8; margin-top: 2px; }
.adm-btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C);
    color: #fff; border: none; border-radius: 10px;
    padding: 9px 18px; font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none; white-space: nowrap;
    transition: all 0.2s ease;
    box-shadow: 0 2px 10px rgba(92,46,10,0.3);
}
.adm-btn-primary:hover { box-shadow: 0 4px 18px rgba(92,46,10,0.45); filter: brightness(1.08); }

/* ── Stats grid ──────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}
@media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

.stat-skeleton {
    height: 110px; border-radius: 14px;
    background: linear-gradient(90deg, #E2E8F0 25%, #CBD5E1 50%, #E2E8F0 75%);
    background-size: 800px 100%;
    animation: sk 1.6s ease-in-out infinite;
}
@keyframes sk { 0% { background-position: -400px 0 } 100% { background-position: 400px 0 } }

.stat-card {
    background: #FFFFFF;
    border: 1px solid #E8EEF4;
    border-radius: 14px;
    padding: 18px 18px 16px;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.stat-card:hover { box-shadow: 0 6px 20px rgba(15,23,42,0.07); transform: translateY(-2px); }

.stat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
.stat-label { font-size: 11.5px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.08em; }
.stat-icon-wrap {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #64748B;
}
.stat-icon-svg :deep(svg) { stroke: currentColor; }

.stat-value {
    font-family: 'Poppins', sans-serif;
    font-size: 24px; font-weight: 700;
    color: #0F172A; letter-spacing: -0.03em;
    line-height: 1;
}
.stat-change {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 11.5px; font-weight: 600; margin-top: 8px;
}
.stat-change.positive { color: #059669; }
.stat-change.negative { color: #DC2626; }
.stat-neutral { font-size: 11px; color: #CBD5E1; margin-top: 8px; }

/* ── Mid grid ─────────────────────────────────── */
.mid-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
}
@media (max-width: 900px) { .mid-grid { grid-template-columns: 1fr; } }

/* ── Card ─────────────────────────────────────── */
.adm-card {
    background: #FFFFFF;
    border: 1px solid #E8EEF4;
    border-radius: 16px;
    overflow: hidden;
}
.adm-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #F1F5F9;
}
.adm-card-title { font-size: 14px; font-weight: 700; color: #0F172A; }
.adm-card-link { font-size: 12px; font-weight: 600; color: #8B5E3C; text-decoration: none; }
.adm-card-link:hover { color: #5C2E0A; }

/* ── Table ─────────────────────────────────────── */
.adm-table-wrap { overflow-x: auto; }
.adm-table { width: 100%; border-collapse: collapse; }
.adm-table thead tr { background: #F8FAFC; }
.adm-table th {
    padding: 10px 16px;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: #94A3B8; text-align: left;
    border-bottom: 1px solid #F1F5F9;
    white-space: nowrap;
}
.adm-table td {
    padding: 12px 16px;
    font-size: 13.5px; color: #475569;
    border-bottom: 1px solid #F8FAFC;
    vertical-align: middle;
}
.adm-table tbody tr:last-child td { border-bottom: none; }
.adm-table tbody tr:hover td { background: #F8FAFC; }

.adm-link { color: #8B5E3C; text-decoration: none; }
.adm-link:hover { color: #5C2E0A; text-decoration: underline; }

/* ── Badges ─────────────────────────────────────── */
.adm-badge {
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 10.5px; font-weight: 700;
    padding: 3px 9px; border-radius: 99px;
    white-space: nowrap; text-transform: capitalize;
}
.badge-pending    { background: #FEF3C7; color: #92400E; }
.badge-processing { background: #DBEAFE; color: #1E40AF; }
.badge-shipped    { background: #EDE9FE; color: #7C3AED; }
.badge-completed  { background: #D1FAE5; color: #065F46; }
.badge-cancelled  { background: #FEE2E2; color: #991B1B; }
.badge-danger     { background: #FEE2E2; color: #DC2626; }

/* ── Top products ─────────────────────────────── */
.top-prod-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 10px;
    transition: background 0.15s ease;
}
.top-prod-row:hover { background: #F8FAFC; }
.top-prod-rank {
    width: 20px; font-size: 11px; font-weight: 700;
    color: #C9A055; flex-shrink: 0; text-align: center;
}

/* ── Low stock ────────────────────────────────── */
.low-stock-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    background: #FFF5F5;
    border: 1px solid #FED7D7;
    border-radius: 10px;
}

/* ── Quick actions ────────────────────────────── */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
@media (max-width: 900px)  { .quick-actions-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .quick-actions-grid { grid-template-columns: 1fr; } }

.quick-action-card {
    background: #FFFFFF;
    border: 1px solid #E8EEF4;
    border-radius: 14px;
    padding: 16px;
    display: flex; align-items: center; gap: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.quick-action-card:hover {
    border-color: #C9A055;
    box-shadow: 0 4px 16px rgba(92,46,10,0.08);
    transform: translateY(-2px);
}
.quick-action-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: currentColor;
}
.quick-action-icon :deep(svg) { stroke: currentColor; }
.quick-action-label { font-size: 13px; font-weight: 700; color: #1E293B; }
.quick-action-desc  { font-size: 11.5px; color: #94A3B8; margin-top: 1px; }

.col-span-2 { grid-column: span 2; }
@media (max-width: 900px) { .col-span-2 { grid-column: span 1; } }
</style>
