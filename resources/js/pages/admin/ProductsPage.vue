<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Products</h1>
                <p class="adm-page-sub">{{ pagination.total }} total products</p>
            </div>
            <RouterLink to="/admin/products/create" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </RouterLink>
        </div>

        <!-- Filters bar -->
        <div class="filter-bar">
            <div class="filter-search-wrap">
                <svg class="filter-search-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input v-model="search" @input="debouncedFetch" type="text"
                    placeholder="Search products…" class="filter-search">
            </div>
            <select v-model="filters.category_id" @change="fetchProducts" class="filter-select">
                <option value="">All Categories</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <select v-model="filters.status" @change="fetchProducts" class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="low_stock">Low Stock</option>
            </select>
            <select v-model="filters.sort" @change="fetchProducts" class="filter-select">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price_asc">Price: Low → High</option>
                <option value="price_desc">Price: High → Low</option>
                <option value="name">Name A–Z</option>
            </select>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <!-- Loading / empty -->
            <div v-if="loading" class="adm-loading">
                <div v-for="n in 6" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="!products.length" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No products found</p>
                <p class="adm-empty-sub">Try adjusting your filters or add a new product.</p>
                <RouterLink to="/admin/products/create" class="adm-btn-primary mt-4">Add First Product</RouterLink>
            </div>

            <template v-else>
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="hidden md:table-cell">Category</th>
                                <th class="hidden sm:table-cell">Price</th>
                                <th class="hidden lg:table-cell">Stock</th>
                                <th class="hidden lg:table-cell">Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img :src="product.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=48&h=48&fit=crop'"
                                            class="w-11 h-11 rounded-xl object-cover flex-shrink-0 border"
                                            style="border-color:#E8EEF4">
                                        <div>
                                            <p class="font-semibold text-sm" style="color:#0F172A">{{ product.name }}</p>
                                            <p class="text-xs mt-0.5" style="color:#94A3B8">SKU: {{ product.sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell">
                                    <span class="text-sm" style="color:#475569">{{ product.category?.name || '—' }}</span>
                                </td>
                                <td class="hidden sm:table-cell">
                                    <span class="font-bold text-sm" style="color:#8B5E3C;font-family:'Poppins',sans-serif">
                                        ৳{{ Number(product.price).toLocaleString() }}
                                    </span>
                                    <span v-if="product.compare_price" class="text-xs line-through ml-1.5" style="color:#94A3B8">
                                        ৳{{ Number(product.compare_price).toLocaleString() }}
                                    </span>
                                </td>
                                <td class="hidden lg:table-cell">
                                    <span class="text-sm font-semibold"
                                        :style="product.stock <= 5 ? 'color:#DC2626' : product.stock <= 20 ? 'color:#D97706' : 'color:#059669'">
                                        {{ product.stock }}
                                    </span>
                                </td>
                                <td class="hidden lg:table-cell">
                                    <span class="adm-status-pill"
                                        :class="product.is_active ? 'pill-active' : 'pill-inactive'">
                                        <span class="pill-dot"></span>
                                        {{ product.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <RouterLink :to="`/admin/products/${product.id}/edit`"
                                            class="adm-act-btn">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </RouterLink>
                                        <button @click="toggleActive(product)"
                                            class="adm-act-btn"
                                            :class="product.is_active ? 'adm-act-warn' : 'adm-act-success'">
                                            {{ product.is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        <button @click="confirmDelete(product)"
                                            class="adm-act-btn adm-act-danger">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
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
                        <button @click="fetchProducts(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            class="pag-btn">‹</button>
                        <button v-for="page in visiblePages" :key="page"
                            @click="fetchProducts(page)"
                            class="pag-btn"
                            :class="{ 'pag-active': page === pagination.current_page }">
                            {{ page }}
                        </button>
                        <button @click="fetchProducts(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            class="pag-btn">›</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Delete Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="deleteTarget" class="adm-modal-overlay" @click.self="deleteTarget = null">
                    <div class="adm-modal">
                        <div class="adm-modal-icon-wrap">
                            <svg width="24" height="24" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <h3 class="adm-modal-title">Delete Product?</h3>
                        <p class="adm-modal-desc">
                            This will permanently delete <strong>{{ deleteTarget.name }}</strong>.<br>This action cannot be undone.
                        </p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteProduct" :disabled="deleting"
                                class="adm-btn-danger">
                                {{ deleting ? 'Deleting…' : 'Delete Product' }}
                            </button>
                        </div>
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

const products    = ref([])
const categories  = ref([])
const loading     = ref(true)
const search      = ref('')
const deleteTarget = ref(null)
const deleting    = ref(false)
const filters     = reactive({ category_id: '', status: '', sort: 'newest' })
const pagination  = reactive({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

const visiblePages = computed(() => {
    const pages = []
    const c = pagination.current_page, l = pagination.last_page
    for (let p = Math.max(1, c - 2); p <= Math.min(l, c + 2); p++) pages.push(p)
    return pages
})

let debounceTimer = null
function debouncedFetch() {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => fetchProducts(), 400)
}

async function fetchProducts(page = 1) {
    loading.value = true
    try {
        const params = { page, search: search.value, ...filters }
        const { data } = await axios.get('/admin/products', { params })
        products.value = data.data
        Object.assign(pagination, { current_page: data.current_page, last_page: data.last_page, total: data.total, from: data.from, to: data.to })
    } catch { toast.error('Failed to load products') }
    finally   { loading.value = false }
}

async function toggleActive(product) {
    try {
        await axios.patch(`/admin/products/${product.id}/toggle-active`)
        product.is_active = !product.is_active
        toast.success(`Product ${product.is_active ? 'activated' : 'deactivated'}`)
    } catch { toast.error('Failed to update product') }
}

function confirmDelete(product) { deleteTarget.value = product }

async function deleteProduct() {
    deleting.value = true
    try {
        await axios.delete(`/admin/products/${deleteTarget.value.id}`)
        products.value = products.value.filter(p => p.id !== deleteTarget.value.id)
        pagination.total--
        toast.success('Product deleted')
        deleteTarget.value = null
    } catch { toast.error('Failed to delete product') }
    finally   { deleting.value = false }
}

onMounted(async () => {
    const [, cats] = await Promise.allSettled([fetchProducts(), axios.get('/categories')])
    if (cats.status === 'fulfilled') categories.value = cats.value.data
})
</script>
