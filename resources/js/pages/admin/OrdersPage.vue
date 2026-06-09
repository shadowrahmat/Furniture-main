<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Orders</h1>
                <p class="adm-page-sub">{{ pagination.total }} total orders</p>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="status-tabs">
            <button v-for="tab in statusTabs" :key="tab.value"
                @click="filterStatus = tab.value; fetchOrders()"
                class="status-tab"
                :class="{ 'status-tab-active': filterStatus === tab.value }">
                {{ tab.label }}
            </button>
        </div>

        <!-- Filters -->
        <div class="filter-bar">
            <div class="filter-search-wrap">
                <svg class="filter-search-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input v-model="search" @input="debouncedFetch" type="text"
                    placeholder="Search order # or customer…" class="filter-search">
            </div>
            <div class="filter-date-wrap">
                <label class="filter-date-label">From</label>
                <input v-model="dateFrom" @change="fetchOrders" type="date" class="filter-date">
            </div>
            <div class="filter-date-wrap">
                <label class="filter-date-label">To</label>
                <input v-model="dateTo" @change="fetchOrders" type="date" class="filter-date">
            </div>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 6" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="!orders.length" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No orders found</p>
                <p class="adm-empty-sub">Orders will appear here once customers start buying.</p>
            </div>

            <template v-else>
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th class="hidden md:table-cell">Customer</th>
                                <th>Amount</th>
                                <th class="hidden lg:table-cell">Payment</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders" :key="order.id">
                                <td>
                                    <p class="font-semibold text-sm" style="color:#0F172A">{{ order.order_number }}</p>
                                    <p class="text-xs mt-0.5" style="color:#94A3B8">
                                        {{ new Date(order.created_at).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'2-digit' }) }}
                                    </p>
                                </td>
                                <td class="hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                            style="background:linear-gradient(135deg,#5C2E0A,#8B5E3C)">
                                            {{ order.full_name?.charAt(0) }}
                                        </div>
                                        <div>
                                            <p class="text-sm" style="color:#334155">{{ order.full_name }}</p>
                                            <p class="text-xs" style="color:#94A3B8">{{ order.user?.email || 'Guest' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-bold text-sm" style="color:#8B5E3C;font-family:'Poppins',sans-serif">
                                        ৳{{ Number(order.total).toLocaleString() }}
                                    </span>
                                </td>
                                <td class="hidden lg:table-cell">
                                    <span class="text-xs font-semibold uppercase" style="color:#475569">{{ order.payment_method }}</span>
                                    <p class="text-xs mt-0.5" :style="order.payment_status === 'paid' ? 'color:#059669' : 'color:#D97706'">
                                        {{ order.payment_status }}
                                    </p>
                                </td>
                                <td>
                                    <select :value="order.status"
                                        @change="updateStatus(order, $event.target.value)"
                                        class="status-select"
                                        :class="`sel-${order.status}`">
                                        <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                                    </select>
                                </td>
                                <td class="text-right">
                                    <RouterLink :to="`/admin/orders/${order.id}`"
                                        class="adm-act-btn">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </RouterLink>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="pagination.last_page > 1" class="adm-pagination">
                    <p class="pag-info">Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}</p>
                    <div class="pag-btns">
                        <button @click="fetchOrders(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1" class="pag-btn">‹</button>
                        <button v-for="page in visiblePages" :key="page"
                            @click="fetchOrders(page)"
                            class="pag-btn" :class="{ 'pag-active': page === pagination.current_page }">
                            {{ page }}
                        </button>
                        <button @click="fetchOrders(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page" class="pag-btn">›</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const orders      = ref([])
const loading     = ref(true)
const search      = ref('')
const filterStatus = ref('')
const dateFrom    = ref('')
const dateTo      = ref('')
const pagination  = reactive({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
const statuses    = ['pending', 'processing', 'shipped', 'completed', 'cancelled']

const statusTabs = [
    { label: 'All',        value: '' },
    { label: 'Pending',    value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Shipped',    value: 'shipped' },
    { label: 'Completed',  value: 'completed' },
    { label: 'Cancelled',  value: 'cancelled' },
]

const visiblePages = computed(() => {
    const pages = []
    const c = pagination.current_page, l = pagination.last_page
    for (let p = Math.max(1, c - 2); p <= Math.min(l, c + 2); p++) pages.push(p)
    return pages
})

let debounceTimer = null
function debouncedFetch() {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => fetchOrders(), 400)
}

async function fetchOrders(page = 1) {
    loading.value = true
    try {
        const params = { page, search: search.value, status: filterStatus.value, date_from: dateFrom.value, date_to: dateTo.value }
        const { data } = await axios.get('/admin/orders', { params })
        orders.value = data.data
        Object.assign(pagination, { current_page: data.current_page, last_page: data.last_page, total: data.total, from: data.from, to: data.to })
    } catch { toast.error('Failed to load orders') }
    finally   { loading.value = false }
}

async function updateStatus(order, status) {
    try {
        await axios.patch(`/admin/orders/${order.id}/status`, { status })
        order.status = status
        toast.success('Order status updated')
    } catch { toast.error('Failed to update status') }
}

onMounted(fetchOrders)
</script>

<style scoped>
.adm-page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:16px; flex-wrap:wrap; }
.adm-page-title  { font-size:22px; font-weight:700; color:#0F172A; letter-spacing:-0.02em; }
.adm-page-sub    { font-size:13px; color:#94A3B8; margin-top:2px; }

/* Status tabs */
.status-tabs { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:16px; }
.status-tab {
    padding:7px 14px; border-radius:99px;
    font-size:12.5px; font-weight:600; cursor:pointer;
    border:1.5px solid #E2E8F0; background:#FFFFFF; color:#64748B;
    transition:all 0.18s ease; white-space:nowrap;
}
.status-tab:hover { border-color:#C9A055; color:#8B5E3C; background:#FFFBEB; }
.status-tab-active { background:linear-gradient(135deg,#5C2E0A,#8B5E3C) !important; color:#fff !important; border-color:transparent !important; }

/* Filter bar */
.filter-bar { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px; }
.filter-search-wrap { flex:1; min-width:220px; position:relative; }
.filter-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; pointer-events:none; }
.filter-search {
    width:100%; padding:10px 14px 10px 36px;
    border:1.5px solid #E2E8F0; border-radius:10px;
    font-size:13.5px; background:#FFFFFF; color:#0F172A;
    outline:none; font-family:'Inter',sans-serif;
    transition:border-color 0.2s ease, box-shadow 0.2s ease;
}
.filter-search::placeholder { color:#CBD5E1; }
.filter-search:focus { border-color:#C9A055; box-shadow:0 0 0 3px rgba(201,160,85,0.12); }
.filter-date-wrap { display:flex; align-items:center; gap:8px; background:#FFFFFF; border:1.5px solid #E2E8F0; border-radius:10px; padding:0 12px; }
.filter-date-label { font-size:11px; font-weight:600; color:#94A3B8; text-transform:uppercase; letter-spacing:0.08em; white-space:nowrap; }
.filter-date { border:none; outline:none; padding:10px 0; font-size:13px; color:#334155; font-family:'Inter',sans-serif; background:transparent; }

/* Card */
.adm-card { background:#FFFFFF; border:1px solid #E8EEF4; border-radius:16px; overflow:hidden; }

/* Loading */
.adm-loading { padding:16px; display:flex; flex-direction:column; gap:10px; }
.adm-row-skeleton { height:52px; border-radius:10px; background:linear-gradient(90deg,#F1F5F9 25%,#E2E8F0 50%,#F1F5F9 75%); background-size:800px 100%; animation:sk 1.6s ease-in-out infinite; }
@keyframes sk { 0%{background-position:-400px 0} 100%{background-position:400px 0} }

/* Empty */
.adm-empty { padding:56px 24px; text-align:center; display:flex; flex-direction:column; align-items:center; }
.adm-empty-icon { width:64px; height:64px; border-radius:16px; background:#F8FAFC; border:1.5px solid #E2E8F0; display:flex; align-items:center; justify-content:center; margin-bottom:16px; }
.adm-empty-title { font-size:15px; font-weight:700; color:#1E293B; }
.adm-empty-sub   { font-size:13px; color:#94A3B8; margin-top:4px; }

/* Table */
.adm-table-wrap { overflow-x:auto; }
.adm-table { width:100%; border-collapse:collapse; }
.adm-table thead tr { background:#F8FAFC; }
.adm-table th { padding:10px 16px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#94A3B8; text-align:left; border-bottom:1px solid #F1F5F9; white-space:nowrap; }
.adm-table td { padding:13px 16px; font-size:13.5px; color:#475569; border-bottom:1px solid #F8FAFC; vertical-align:middle; }
.adm-table tbody tr:last-child td { border-bottom:none; }
.adm-table tbody tr:hover td { background:#FAFBFF; }

/* Status select */
.status-select {
    font-size:11.5px; font-weight:700; padding:4px 10px;
    border-radius:99px; border:none; cursor:pointer;
    outline:none; appearance:none; -webkit-appearance:none;
    font-family:'Inter',sans-serif;
}
.sel-pending    { background:#FEF3C7; color:#92400E; }
.sel-processing { background:#DBEAFE; color:#1E40AF; }
.sel-shipped    { background:#EDE9FE; color:#7C3AED; }
.sel-completed  { background:#D1FAE5; color:#065F46; }
.sel-cancelled  { background:#FEE2E2; color:#991B1B; }

/* Action button */
.adm-act-btn {
    display:inline-flex; align-items:center; gap:4px;
    padding:5px 10px; border-radius:7px;
    font-size:12px; font-weight:600; cursor:pointer;
    border:none; transition:all 0.18s ease;
    background:#F1F5F9; color:#475569; text-decoration:none;
}
.adm-act-btn:hover { background:#E2E8F0; color:#1E293B; }

/* Pagination */
.adm-pagination { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-top:1px solid #F1F5F9; flex-wrap:wrap; gap:10px; }
.pag-info  { font-size:12.5px; color:#94A3B8; }
.pag-btns  { display:flex; gap:4px; }
.pag-btn { min-width:32px; height:32px; border-radius:8px; background:#F8FAFC; border:1px solid #E2E8F0; color:#475569; font-size:13px; font-weight:500; cursor:pointer; transition:all 0.18s ease; padding:0 6px; }
.pag-btn:hover:not(:disabled) { background:#EFF6FF; border-color:#93C5FD; color:#1D4ED8; }
.pag-btn:disabled { opacity:0.4; cursor:default; }
.pag-active { background:linear-gradient(135deg,#5C2E0A,#8B5E3C) !important; color:#fff !important; border-color:transparent !important; }

.text-right { text-align:right; }
</style>
