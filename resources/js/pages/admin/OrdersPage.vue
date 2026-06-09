<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Orders</h1>
            <div class="flex gap-2">
                <span v-for="tab in statusTabs" :key="tab.value"
                    @click="filterStatus = tab.value; fetchOrders()"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
                    :style="filterStatus === tab.value ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                    {{ tab.label }}
                </span>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl p-4 shadow-sm border mb-5 flex flex-wrap gap-3" style="border-color:#F0E8DE">
            <input v-model="search" @input="debouncedFetch" type="text" placeholder="Search order # or customer..." class="input-field flex-1 min-w-48 text-sm py-2">
            <input v-model="dateFrom" @change="fetchOrders" type="date" class="input-field text-sm py-2">
            <input v-model="dateTo" @change="fetchOrders" type="date" class="input-field text-sm py-2">
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color:#F0E8DE">
            <div v-if="loading" class="p-8 text-center" style="color:#6D655F">Loading...</div>
            <div v-else-if="orders.length === 0" class="p-12 text-center">
                <div class="text-5xl mb-3">📦</div>
                <p style="color:#6D655F">No orders found</p>
            </div>
            <table v-else class="w-full">
                <thead class="text-xs font-semibold uppercase tracking-wider" style="background:#FAF8F5;color:#6D655F">
                    <tr>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Customer</th>
                        <th class="px-4 py-3 text-left hidden sm:table-cell">Total</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Payment</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8DE">
                    <tr v-for="order in orders" :key="order.id" class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-sm" style="color:#1E1B18">{{ order.order_number }}</p>
                            <p class="text-xs" style="color:#6D655F">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <p class="text-sm" style="color:#1E1B18">{{ order.full_name }}</p>
                            <p class="text-xs" style="color:#6D655F">{{ order.user?.email || 'Guest' }}</p>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="font-bold price-tag text-sm">৳{{ Number(order.total).toLocaleString() }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-xs font-semibold uppercase">{{ order.payment_method }}</span>
                            <p class="text-xs" :style="order.payment_status === 'paid' ? 'color:#059669' : 'color:#D97706'">{{ order.payment_status }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <select :value="order.status" @change="updateStatus(order, $event.target.value)"
                                class="text-xs px-2 py-1 rounded-lg font-semibold border-0 cursor-pointer"
                                :style="statusStyle(order.status)">
                                <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <RouterLink :to="`/admin/orders/${order.id}`" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#F5EFE6;color:#8B5E3C">View</RouterLink>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="pagination.last_page > 1" class="flex items-center justify-between px-4 py-3 border-t" style="border-color:#F0E8DE">
                <p class="text-sm" style="color:#6D655F">Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}</p>
                <div class="flex gap-1">
                    <button v-for="page in pagination.last_page" :key="page" @click="fetchOrders(page)"
                        class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                        :style="page === pagination.current_page ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const orders = ref([])
const loading = ref(true)
const search = ref('')
const filterStatus = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const pagination = reactive({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
const statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled']
const statusTabs = [
    { label: 'All', value: '' },
    { label: 'Pending', value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Shipped', value: 'shipped' },
    { label: 'Completed', value: 'completed' },
    { label: 'Cancelled', value: 'cancelled' },
]

function statusStyle(s) {
    const m = { pending: 'background:#FEF3C7;color:#92400E', processing: 'background:#DBEAFE;color:#1E40AF', shipped: 'background:#F3E8FF;color:#7C3AED', completed: 'background:#D1FAE5;color:#065F46', cancelled: 'background:#FEE2E2;color:#991B1B' }
    return m[s] || 'background:#F3F4F6;color:#374151'
}

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
    } catch (e) { toast.error('Failed to load orders') }
    finally { loading.value = false }
}

async function updateStatus(order, status) {
    try {
        await axios.patch(`/admin/orders/${order.id}/status`, { status })
        order.status = status
        toast.success('Order status updated')
    } catch (e) { toast.error('Failed to update status') }
}

onMounted(fetchOrders)
</script>
