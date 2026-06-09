<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Customers</h1>
            <span class="text-sm" style="color:#6D655F">Total: {{ pagination.total }}</span>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border mb-5 flex flex-wrap gap-3" style="border-color:#F0E8DE">
            <input v-model="search" @input="debouncedFetch" type="text" placeholder="Search by name or email..." class="input-field flex-1 min-w-48 text-sm py-2">
            <select v-model="filterStatus" @change="fetchCustomers" class="input-field text-sm py-2">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color:#F0E8DE">
            <div v-if="loading" class="p-8 text-center" style="color:#6D655F">Loading...</div>
            <div v-else-if="customers.length === 0" class="p-12 text-center">
                <div class="text-5xl mb-3">👥</div>
                <p style="color:#6D655F">No customers found</p>
            </div>
            <table v-else class="w-full">
                <thead class="text-xs font-semibold uppercase tracking-wider" style="background:#FAF8F5;color:#6D655F">
                    <tr>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Email</th>
                        <th class="px-4 py-3 text-left hidden sm:table-cell">Orders</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Spent</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Joined</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8DE">
                    <tr v-for="customer in customers" :key="customer.id" class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img :src="`https://ui-avatars.com/api/?name=${customer.name}&background=8B5E3C&color=fff&size=36`"
                                    class="w-9 h-9 rounded-full flex-shrink-0">
                                <div>
                                    <p class="font-medium text-sm" style="color:#1E1B18">{{ customer.name }}</p>
                                    <p v-if="customer.phone" class="text-xs" style="color:#6D655F">{{ customer.phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-sm" style="color:#6D655F">{{ customer.email }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell text-sm font-semibold" style="color:#1E1B18">{{ customer.orders_count || 0 }}</td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="font-bold price-tag text-sm">৳{{ Number(customer.total_spent || 0).toLocaleString() }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-sm" style="color:#6D655F">{{ new Date(customer.created_at).toLocaleDateString() }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                :style="customer.is_active ? 'background:#D1FAE5;color:#065F46' : 'background:#FEE2E2;color:#991B1B'">
                                {{ customer.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="viewCustomer(customer)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#F5EFE6;color:#8B5E3C">View</button>
                                <button @click="toggleActive(customer)" class="text-xs px-3 py-1.5 rounded-lg font-medium"
                                    :style="customer.is_active ? 'background:#FEE2E2;color:#991B1B' : 'background:#D1FAE5;color:#065F46'">
                                    {{ customer.is_active ? 'Block' : 'Unblock' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="pagination.last_page > 1" class="flex items-center justify-between px-4 py-3 border-t" style="border-color:#F0E8DE">
                <p class="text-sm" style="color:#6D655F">Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}</p>
                <div class="flex gap-1">
                    <button v-for="page in pagination.last_page" :key="page" @click="fetchCustomers(page)"
                        class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                        :style="page === pagination.current_page ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Detail Modal -->
        <div v-if="selectedCustomer" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl max-h-[80vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-heading font-bold text-xl" style="color:#1E1B18">Customer Details</h3>
                    <button @click="selectedCustomer = null" class="p-2 rounded-lg hover:bg-gray-100">✕</button>
                </div>
                <div class="flex items-center gap-4 mb-5 p-4 rounded-xl" style="background:#FAF8F5">
                    <img :src="`https://ui-avatars.com/api/?name=${selectedCustomer.name}&background=8B5E3C&color=fff&size=60`"
                        class="w-15 h-15 rounded-full">
                    <div>
                        <p class="font-semibold text-lg" style="color:#1E1B18">{{ selectedCustomer.name }}</p>
                        <p style="color:#6D655F">{{ selectedCustomer.email }}</p>
                        <p v-if="selectedCustomer.phone" style="color:#6D655F">{{ selectedCustomer.phone }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div class="text-center p-3 rounded-xl" style="background:#F5EFE6">
                        <p class="font-bold text-xl" style="color:#8B5E3C">{{ selectedCustomer.orders_count || 0 }}</p>
                        <p class="text-xs" style="color:#6D655F">Orders</p>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background:#F5EFE6">
                        <p class="font-bold text-lg price-tag">৳{{ Number(selectedCustomer.total_spent || 0).toLocaleString() }}</p>
                        <p class="text-xs" style="color:#6D655F">Total Spent</p>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background:#F5EFE6">
                        <p class="font-bold" style="color:#8B5E3C">{{ new Date(selectedCustomer.created_at).toLocaleDateString() }}</p>
                        <p class="text-xs" style="color:#6D655F">Joined</p>
                    </div>
                </div>
                <button @click="selectedCustomer = null" class="w-full btn-outline text-sm py-2.5">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const customers = ref([])
const loading = ref(true)
const search = ref('')
const filterStatus = ref('')
const selectedCustomer = ref(null)
const pagination = reactive({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

let debounceTimer = null
function debouncedFetch() {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => fetchCustomers(), 400)
}

async function fetchCustomers(page = 1) {
    loading.value = true
    try {
        const params = { page, search: search.value, status: filterStatus.value }
        const { data } = await axios.get('/admin/customers', { params })
        customers.value = data.data
        Object.assign(pagination, { current_page: data.current_page, last_page: data.last_page, total: data.total, from: data.from, to: data.to })
    } catch (e) { toast.error('Failed to load customers') }
    finally { loading.value = false }
}

async function toggleActive(customer) {
    try {
        await axios.patch(`/admin/customers/${customer.id}/toggle-active`)
        customer.is_active = !customer.is_active
        toast.success(`Customer ${customer.is_active ? 'unblocked' : 'blocked'}`)
    } catch (e) { toast.error('Failed to update customer') }
}

function viewCustomer(customer) { selectedCustomer.value = customer }

onMounted(fetchCustomers)
</script>
