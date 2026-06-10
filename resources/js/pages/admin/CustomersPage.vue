<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Customers</h1>
                <p class="adm-page-sub">{{ pagination.total }} total customers</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-bar">
            <div class="filter-search-wrap">
                <svg class="filter-search-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input v-model="search" @input="debouncedFetch" type="text"
                    placeholder="Search by name or email…" class="filter-search">
            </div>
            <select v-model="filterStatus" @change="fetchCustomers()" class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 6" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="customers.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 10-8 0"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No customers found</p>
                <p class="adm-empty-sub">Try adjusting your search or filters.</p>
            </div>

            <template v-else>
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="hidden md:table-cell">Email</th>
                                <th class="hidden sm:table-cell">Orders</th>
                                <th class="hidden lg:table-cell">Spent</th>
                                <th class="hidden lg:table-cell">Joined</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="customer in customers" :key="customer.id">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img :src="`https://ui-avatars.com/api/?name=${customer.name}&background=8B5E3C&color=fff&size=36`"
                                            class="w-9 h-9 rounded-full flex-shrink-0">
                                        <div>
                                            <p class="font-semibold text-sm" style="color:#0F172A">{{ customer.name }}</p>
                                            <p v-if="customer.phone" class="text-xs mt-0.5" style="color:#94A3B8">{{ customer.phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell">{{ customer.email }}</td>
                                <td class="hidden sm:table-cell">
                                    <span class="font-semibold" style="color:#0F172A">{{ customer.orders_count || 0 }}</span>
                                </td>
                                <td class="hidden lg:table-cell">
                                    <span class="font-bold" style="color:#8B5E3C;font-family:'Poppins',sans-serif">৳{{ Number(customer.total_spent || 0).toLocaleString() }}</span>
                                </td>
                                <td class="hidden lg:table-cell">{{ new Date(customer.created_at).toLocaleDateString() }}</td>
                                <td>
                                    <span class="adm-status-pill" :class="customer.is_active ? 'pill-active' : 'pill-inactive'">
                                        <span class="pill-dot"></span>
                                        {{ customer.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button @click="viewCustomer(customer)" class="adm-act-btn">View</button>
                                        <button @click="toggleActive(customer)" class="adm-act-btn"
                                            :class="customer.is_active ? 'adm-act-warn' : 'adm-act-success'">
                                            {{ customer.is_active ? 'Block' : 'Unblock' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="pagination.last_page > 1" class="adm-pagination">
                    <p class="pag-info">Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}</p>
                    <div class="pag-btns">
                        <button @click="fetchCustomers(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1" class="pag-btn">‹</button>
                        <button v-for="page in visiblePages" :key="page"
                            @click="fetchCustomers(page)"
                            class="pag-btn" :class="{ 'pag-active': page === pagination.current_page }">
                            {{ page }}
                        </button>
                        <button @click="fetchCustomers(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page" class="pag-btn">›</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Customer Detail Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="selectedCustomer" class="adm-modal-overlay" @click.self="selectedCustomer = null">
                    <div class="adm-modal-form" style="text-align:center">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="adm-modal-title" style="margin-bottom:0">Customer Details</h3>
                            <button @click="selectedCustomer = null" class="adm-act-btn">✕</button>
                        </div>
                        <div class="flex items-center gap-4 mb-5 p-4 rounded-xl text-left" style="background:#F8FAFC">
                            <img :src="`https://ui-avatars.com/api/?name=${selectedCustomer.name}&background=8B5E3C&color=fff&size=60`"
                                class="w-15 h-15 rounded-full">
                            <div>
                                <p class="font-semibold text-lg" style="color:#0F172A">{{ selectedCustomer.name }}</p>
                                <p class="text-sm" style="color:#64748B">{{ selectedCustomer.email }}</p>
                                <p v-if="selectedCustomer.phone" class="text-sm" style="color:#64748B">{{ selectedCustomer.phone }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <div class="text-center p-3 rounded-xl" style="background:#F8FAFC">
                                <p class="font-bold text-xl" style="color:#8B5E3C;font-family:'Poppins',sans-serif">{{ selectedCustomer.orders_count || 0 }}</p>
                                <p class="text-xs mt-0.5" style="color:#94A3B8">Orders</p>
                            </div>
                            <div class="text-center p-3 rounded-xl" style="background:#F8FAFC">
                                <p class="font-bold text-lg" style="color:#8B5E3C;font-family:'Poppins',sans-serif">৳{{ Number(selectedCustomer.total_spent || 0).toLocaleString() }}</p>
                                <p class="text-xs mt-0.5" style="color:#94A3B8">Total Spent</p>
                            </div>
                            <div class="text-center p-3 rounded-xl" style="background:#F8FAFC">
                                <p class="font-bold text-sm" style="color:#0F172A">{{ new Date(selectedCustomer.created_at).toLocaleDateString() }}</p>
                                <p class="text-xs mt-0.5" style="color:#94A3B8">Joined</p>
                            </div>
                        </div>
                        <button @click="selectedCustomer = null" class="adm-btn-ghost" style="width:100%">Close</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const customers = ref([])
const loading = ref(true)
const search = ref('')
const filterStatus = ref('')
const selectedCustomer = ref(null)
const pagination = reactive({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const visiblePages = computed(() => {
    const pages = []
    const c = pagination.current_page, l = pagination.last_page
    for (let p = Math.max(1, c - 2); p <= Math.min(l, c + 2); p++) pages.push(p)
    return pages
})

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

onMounted(() => fetchCustomers())
</script>
