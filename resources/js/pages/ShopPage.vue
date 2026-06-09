<template>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="section-title mb-2">{{ pageTitle }}</h1>
            <p class="section-subtitle">Explore our premium furniture collection</p>
        </div>

        <!-- Active filter chips -->
        <div v-if="activeChips.length" class="flex flex-wrap gap-2 mb-5">
            <div v-for="chip in activeChips" :key="chip.key"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold"
                style="background:#F5EFE6; color:#8B5E3C; border:1px solid #D4B896">
                {{ chip.label }}
                <button @click="removeChip(chip.key)" class="w-3.5 h-3.5 flex items-center justify-center rounded-full hover:bg-amber-200 transition-colors" style="color:#6B4020">✕</button>
            </div>
            <button @click="resetFilters" class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors hover:bg-red-50" style="color:#DC2626; border-color:#FCA5A5">
                Clear all
            </button>
        </div>

        <!-- Mobile filter button -->
        <div class="lg:hidden mb-4">
            <button @click="mobileFilterOpen = true"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-sm font-semibold"
                style="border-color:#EDE5D8; color:#1E1B18; background:#FFFFFF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filters
                <span v-if="activeChips.length" class="px-1.5 py-0.5 rounded-full text-xs text-white" style="background:#8B5E3C">{{ activeChips.length }}</span>
            </button>
        </div>

        <!-- Mobile filter drawer -->
        <Teleport to="body">
            <Transition name="overlay-fade">
                <div v-if="mobileFilterOpen" class="fixed inset-0 z-50 lg:hidden"
                    style="background:rgba(24,20,15,0.5); backdrop-filter:blur(2px)"
                    @click="mobileFilterOpen = false">
                </div>
            </Transition>
            <Transition name="drawer-bottom">
                <div v-if="mobileFilterOpen"
                    class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-white rounded-t-[28px] shadow-2xl"
                    style="max-height:85vh; overflow-y:auto">
                    <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:#EDE5D8">
                        <h3 class="font-heading font-bold text-lg" style="color:#1E1B18">Filters</h3>
                        <button @click="mobileFilterOpen = false" class="btn-icon w-8 h-8">✕</button>
                    </div>
                    <div class="px-5 py-4 space-y-6">
                        <!-- Categories mobile -->
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">Category</h4>
                            <div class="flex flex-wrap gap-2">
                                <button @click="filters.category = ''"
                                    class="px-3 py-1.5 rounded-full text-sm font-medium border transition-all"
                                    :style="!filters.category ? 'background:#8B5E3C; color:white; border-color:#8B5E3C' : 'border-color:#EDE5D8; color:#6D655F'">
                                    All
                                </button>
                                <button v-for="cat in allCategories" :key="cat.id"
                                    @click="filters.category = cat.slug"
                                    class="px-3 py-1.5 rounded-full text-sm font-medium border transition-all"
                                    :style="filters.category === cat.slug ? 'background:#8B5E3C; color:white; border-color:#8B5E3C' : 'border-color:#EDE5D8; color:#6D655F'">
                                    {{ cat.name }}
                                </button>
                            </div>
                        </div>
                        <!-- Price range mobile -->
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">Price Range (৳)</h4>
                            <div class="flex gap-3">
                                <input type="number" v-model="filters.min_price" placeholder="Min" class="input-field text-sm py-2.5 flex-1">
                                <input type="number" v-model="filters.max_price" placeholder="Max" class="input-field text-sm py-2.5 flex-1">
                            </div>
                        </div>
                        <!-- Sort mobile -->
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">Sort By</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <button v-for="opt in sortOptions" :key="opt.value"
                                    @click="filters.sort = opt.value"
                                    class="px-3 py-2 rounded-xl text-sm font-medium border transition-all text-left"
                                    :style="filters.sort === opt.value ? 'background:#8B5E3C; color:white; border-color:#8B5E3C' : 'border-color:#EDE5D8; color:#6D655F'">
                                    {{ opt.label }}
                                </button>
                            </div>
                        </div>
                        <!-- Tags mobile -->
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                <button @click="filters.featured = !filters.featured"
                                    class="px-3 py-1.5 rounded-full text-sm font-medium border transition-all"
                                    :style="filters.featured ? 'background:#8B5E3C; color:white; border-color:#8B5E3C' : 'border-color:#EDE5D8; color:#6D655F'">
                                    ⭐ Featured
                                </button>
                                <button @click="filters.new_arrival = !filters.new_arrival"
                                    class="px-3 py-1.5 rounded-full text-sm font-medium border transition-all"
                                    :style="filters.new_arrival ? 'background:#8B5E3C; color:white; border-color:#8B5E3C' : 'border-color:#EDE5D8; color:#6D655F'">
                                    🆕 New
                                </button>
                                <button @click="filters.best_seller = !filters.best_seller"
                                    class="px-3 py-1.5 rounded-full text-sm font-medium border transition-all"
                                    :style="filters.best_seller ? 'background:#8B5E3C; color:white; border-color:#8B5E3C' : 'border-color:#EDE5D8; color:#6D655F'">
                                    🔥 Best Sellers
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t flex gap-3" style="border-color:#EDE5D8">
                        <button @click="resetFilters(); mobileFilterOpen = false" class="btn-outline flex-1 text-sm py-3">Reset</button>
                        <button @click="applyFilters(); mobileFilterOpen = false" class="btn-primary flex-1 text-sm py-3">Apply Filters</button>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <div class="flex gap-8">
            <!-- Sidebar Filter -->
            <aside class="w-64 flex-shrink-0 hidden lg:block">
                <div class="card-premium p-5 sticky top-24">
                    <h3 class="font-heading font-semibold mb-4" style="color:#1E1B18">Filters</h3>

                    <!-- Categories -->
                    <div class="mb-5">
                        <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#6D655F">Category</h4>
                        <div class="space-y-2">
                            <label v-for="cat in allCategories" :key="cat.id"
                                class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" :value="cat.slug" v-model="filters.category"
                                    class="accent-amber-700">
                                <span class="text-sm group-hover:text-walnut transition-colors" style="color:#6D655F">{{ cat.name }}</span>
                                <span class="text-xs ml-auto" style="color:#C9A66B">({{ cat.products_count }})</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="" v-model="filters.category" class="accent-amber-700">
                                <span class="text-sm" style="color:#6D655F">All Categories</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-5">
                        <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#6D655F">Price Range</h4>
                        <div class="flex gap-2">
                            <input type="number" v-model="filters.min_price" placeholder="Min" class="input-field text-sm py-2 px-3">
                            <input type="number" v-model="filters.max_price" placeholder="Max" class="input-field text-sm py-2 px-3">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-5">
                        <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#6D655F">Sort By</h4>
                        <select v-model="filters.sort" class="input-field text-sm py-2">
                            <option value="newest">Newest First</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="popular">Most Popular</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                    </div>

                    <!-- Featured -->
                    <div class="space-y-2 mb-5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" v-model="filters.featured" class="accent-amber-700">
                            <span class="text-sm" style="color:#6D655F">Featured Only</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" v-model="filters.new_arrival" class="accent-amber-700">
                            <span class="text-sm" style="color:#6D655F">New Arrivals</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" v-model="filters.best_seller" class="accent-amber-700">
                            <span class="text-sm" style="color:#6D655F">Best Sellers</span>
                        </label>
                    </div>

                    <button @click="applyFilters" class="btn-primary w-full justify-center text-sm py-2.5">Apply Filters</button>
                    <button @click="resetFilters" class="btn-outline w-full justify-center text-sm py-2.5 mt-2">Reset</button>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1 min-w-0">
                <!-- Toolbar -->
                <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
                    <p class="text-sm" style="color:#6D655F">
                        Showing {{ products.length }} of {{ total }} products
                    </p>
                    <div class="flex items-center gap-3">
                        <select v-model="filters.sort" @change="applyFilters" class="input-field text-sm py-2 w-44">
                            <option value="newest">Newest First</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="popular">Most Popular</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                        <div class="flex rounded-lg overflow-hidden border" style="border-color:#E8DDD4">
                            <button @click="viewMode = 'grid'" class="px-3 py-2 text-sm transition-colors"
                                :style="viewMode === 'grid' ? 'background:#8B5E3C; color:white' : 'color:#6D655F'">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                            </button>
                            <button @click="viewMode = 'list'" class="px-3 py-2 text-sm transition-colors"
                                :style="viewMode === 'list' ? 'background:#8B5E3C; color:white' : 'color:#6D655F'">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="2"/><rect x="3" y="11" width="18" height="2"/><rect x="3" y="17" width="18" height="2"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loading skeleton -->
                <div v-if="loading" :class="viewMode === 'grid' ? 'grid grid-cols-2 lg:grid-cols-3 gap-6' : 'space-y-4'">
                    <div v-for="n in 9" :key="n" class="skeleton h-72 rounded-2xl"></div>
                </div>

                <!-- Products -->
                <div v-else-if="products.length > 0"
                    :class="viewMode === 'grid' ? 'grid grid-cols-2 lg:grid-cols-3 gap-6' : 'space-y-4'">
                    <ProductCard v-for="product in products" :key="product.id" :product="product"/>
                </div>

                <!-- Empty state -->
                <div v-else class="text-center py-20">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="font-heading font-semibold text-xl mb-2" style="color:#1E1B18">No products found</h3>
                    <p class="text-sm mb-6" style="color:#6D655F">Try adjusting your filters or search terms</p>
                    <button @click="resetFilters" class="btn-primary">Clear Filters</button>
                </div>

                <!-- Pagination -->
                <div v-if="lastPage > 1" class="flex justify-center gap-2 mt-10">
                    <button v-for="page in lastPage" :key="page" @click="goToPage(page)"
                        class="w-10 h-10 rounded-lg text-sm font-medium transition-all"
                        :style="currentPage === page ? 'background:#8B5E3C; color:white' : 'background:white; color:#6D655F; border:1px solid #E8DDD4'">
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import ProductCard from '../components/ProductCard.vue'

const route = useRoute()
const router = useRouter()

const products = ref([])
const allCategories = ref([])
const loading = ref(true)
const total = ref(0)
const currentPage = ref(1)
const lastPage = ref(1)
const viewMode = ref('grid')
const mobileFilterOpen = ref(false)

const sortOptions = [
    { value: 'newest', label: 'Newest' },
    { value: 'price_asc', label: 'Price: Low→High' },
    { value: 'price_desc', label: 'Price: High→Low' },
    { value: 'popular', label: 'Most Popular' },
    { value: 'rating', label: 'Highest Rated' },
]

const filters = reactive({
    category: route.params.category || route.query.category || '',
    search: route.query.search || '',
    min_price: route.query.min_price || '',
    max_price: route.query.max_price || '',
    sort: route.query.sort || 'newest',
    featured: !!route.query.featured,
    new_arrival: !!route.query.new_arrival,
    best_seller: !!route.query.best_seller,
})

const activeChips = computed(() => {
    const chips = []
    if (filters.category) {
        const cat = allCategories.value.find(c => c.slug === filters.category)
        chips.push({ key: 'category', label: cat ? cat.name : filters.category })
    }
    if (filters.search) chips.push({ key: 'search', label: `"${filters.search}"` })
    if (filters.min_price) chips.push({ key: 'min_price', label: `Min ৳${filters.min_price}` })
    if (filters.max_price) chips.push({ key: 'max_price', label: `Max ৳${filters.max_price}` })
    if (filters.featured) chips.push({ key: 'featured', label: 'Featured' })
    if (filters.new_arrival) chips.push({ key: 'new_arrival', label: 'New Arrivals' })
    if (filters.best_seller) chips.push({ key: 'best_seller', label: 'Best Sellers' })
    if (filters.sort !== 'newest') {
        const opt = sortOptions.find(o => o.value === filters.sort)
        if (opt) chips.push({ key: 'sort', label: opt.label })
    }
    return chips
})

function removeChip(key) {
    if (key === 'category') filters.category = ''
    else if (key === 'search') filters.search = ''
    else if (key === 'min_price') filters.min_price = ''
    else if (key === 'max_price') filters.max_price = ''
    else if (key === 'featured') filters.featured = false
    else if (key === 'new_arrival') filters.new_arrival = false
    else if (key === 'best_seller') filters.best_seller = false
    else if (key === 'sort') filters.sort = 'newest'
    loadProducts(1)
}

const pageTitle = computed(() => {
    if (filters.category) {
        const cat = allCategories.value.find(c => c.slug === filters.category)
        return cat ? cat.name : 'Shop'
    }
    if (filters.search) return `Search: "${filters.search}"`
    return 'Shop All Furniture'
})

async function loadProducts(page = 1) {
    loading.value = true
    try {
        const params = { page, per_page: 12 }
        if (filters.category) params.category = filters.category
        if (filters.search) params.search = filters.search
        if (filters.min_price) params.min_price = filters.min_price
        if (filters.max_price) params.max_price = filters.max_price
        if (filters.sort) params.sort = filters.sort
        if (filters.featured) params.featured = 1
        if (filters.new_arrival) params.new_arrival = 1
        if (filters.best_seller) params.best_seller = 1

        const { data } = await axios.get('/products', { params })
        products.value = data.data
        total.value = data.total
        currentPage.value = data.current_page
        lastPage.value = data.last_page
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

async function loadCategories() {
    try {
        const { data } = await axios.get('/categories')
        allCategories.value = data
    } catch (e) {}
}

function applyFilters() {
    loadProducts(1)
}

function resetFilters() {
    filters.category = ''
    filters.search = ''
    filters.min_price = ''
    filters.max_price = ''
    filters.sort = 'newest'
    filters.featured = false
    filters.new_arrival = false
    filters.best_seller = false
    loadProducts(1)
}

function goToPage(page) {
    currentPage.value = page
    loadProducts(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
}

watch(() => route.params.category, (newCat) => {
    filters.category = newCat || ''
    loadProducts(1)
})

watch(() => route.query.search, (newSearch) => {
    filters.search = newSearch || ''
    loadProducts(1)
})

onMounted(() => {
    loadCategories()
    loadProducts()
})
</script>

<style scoped>
.overlay-fade-enter-active, .overlay-fade-leave-active { transition: opacity 0.25s ease; }
.overlay-fade-enter-from, .overlay-fade-leave-to { opacity: 0; }

.drawer-bottom-enter-active { transition: transform 0.3s cubic-bezier(0.32, 0, 0.15, 1); }
.drawer-bottom-leave-active { transition: transform 0.22s cubic-bezier(0.32, 0, 0.15, 1); }
.drawer-bottom-enter-from, .drawer-bottom-leave-to { transform: translateY(100%); }
</style>
