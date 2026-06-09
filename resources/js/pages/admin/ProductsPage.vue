<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Products</h1>
            <RouterLink to="/admin/products/create" class="btn-primary text-sm">+ Add Product</RouterLink>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl p-4 shadow-sm border mb-5 flex flex-wrap gap-3" style="border-color:#F0E8DE">
            <input v-model="search" @input="debouncedFetch" type="text" placeholder="Search products..." class="input-field flex-1 min-w-48 text-sm py-2">
            <select v-model="filters.category_id" @change="fetchProducts" class="input-field text-sm py-2">
                <option value="">All Categories</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <select v-model="filters.status" @change="fetchProducts" class="input-field text-sm py-2">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="low_stock">Low Stock</option>
            </select>
            <select v-model="filters.sort" @change="fetchProducts" class="input-field text-sm py-2">
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="name">Name A-Z</option>
            </select>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color:#F0E8DE">
            <div v-if="loading" class="p-8 text-center" style="color:#6D655F">Loading...</div>
            <div v-else-if="products.length === 0" class="p-12 text-center">
                <div class="text-5xl mb-3">🪑</div>
                <p style="color:#6D655F">No products found</p>
                <RouterLink to="/admin/products/create" class="btn-primary text-sm mt-4 inline-block">Add First Product</RouterLink>
            </div>
            <table v-else class="w-full">
                <thead class="text-xs font-semibold uppercase tracking-wider" style="background:#FAF8F5;color:#6D655F">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Category</th>
                        <th class="px-4 py-3 text-left hidden sm:table-cell">Price</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Stock</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8DE">
                    <tr v-for="product in products" :key="product.id" class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img :src="product.featured_image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=50&h=50&fit=crop'"
                                    class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                <div>
                                    <p class="font-medium text-sm" style="color:#1E1B18">{{ product.name }}</p>
                                    <p class="text-xs" style="color:#6D655F">SKU: {{ product.sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-sm" style="color:#6D655F">{{ product.category?.name }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="font-bold price-tag text-sm">৳{{ Number(product.price).toLocaleString() }}</span>
                            <span v-if="product.compare_price" class="text-xs line-through ml-1" style="color:#6D655F">৳{{ Number(product.compare_price).toLocaleString() }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-sm font-medium" :style="product.stock <= 5 ? 'color:#DC2626' : product.stock <= 20 ? 'color:#D97706' : 'color:#059669'">
                                {{ product.stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                :style="product.is_active ? 'background:#D1FAE5;color:#065F46' : 'background:#FEE2E2;color:#991B1B'">
                                {{ product.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <RouterLink :to="`/admin/products/${product.id}/edit`"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                                    style="background:#F5EFE6;color:#8B5E3C">Edit</RouterLink>
                                <button @click="toggleActive(product)"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                                    :style="product.is_active ? 'background:#FEE2E2;color:#991B1B' : 'background:#D1FAE5;color:#065F46'">
                                    {{ product.is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button @click="confirmDelete(product)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#FEE2E2;color:#991B1B">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" class="flex items-center justify-between px-4 py-3 border-t" style="border-color:#F0E8DE">
                <p class="text-sm" style="color:#6D655F">Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}</p>
                <div class="flex gap-1">
                    <button v-for="page in pagination.last_page" :key="page" @click="goToPage(page)"
                        class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                        :style="page === pagination.current_page ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
                <h3 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Delete Product?</h3>
                <p class="mb-6 text-sm" style="color:#6D655F">Are you sure you want to delete <strong>{{ deleteTarget.name }}</strong>? This cannot be undone.</p>
                <div class="flex gap-3">
                    <button @click="deleteTarget = null" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                    <button @click="deleteProduct" :disabled="deleting" class="flex-1 text-sm py-2 rounded-lg font-semibold text-white" style="background:#DC2626">
                        {{ deleting ? 'Deleting...' : 'Delete' }}
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

const products = ref([])
const categories = ref([])
const loading = ref(true)
const search = ref('')
const deleteTarget = ref(null)
const deleting = ref(false)
const filters = reactive({ category_id: '', status: '', sort: 'newest' })
const pagination = reactive({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })

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
    } catch (e) { toast.error('Failed to load products') }
    finally { loading.value = false }
}

async function toggleActive(product) {
    try {
        await axios.patch(`/admin/products/${product.id}/toggle-active`)
        product.is_active = !product.is_active
        toast.success(`Product ${product.is_active ? 'activated' : 'deactivated'}`)
    } catch (e) { toast.error('Failed to update product') }
}

function confirmDelete(product) { deleteTarget.value = product }

async function deleteProduct() {
    deleting.value = true
    try {
        await axios.delete(`/admin/products/${deleteTarget.value.id}`)
        products.value = products.value.filter(p => p.id !== deleteTarget.value.id)
        toast.success('Product deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete product') }
    finally { deleting.value = false }
}

function goToPage(page) { fetchProducts(page) }

onMounted(async () => {
    const [, cats] = await Promise.allSettled([fetchProducts(), axios.get('/categories')])
    if (cats.status === 'fulfilled') categories.value = cats.value.data
})
</script>
