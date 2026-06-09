<template>
    <Teleport to="body">
        <Transition name="search-modal">
            <div v-if="uiStore.searchOpen"
                class="fixed inset-0 z-[60] flex flex-col"
                style="background:rgba(24,20,15,0.7); backdrop-filter:blur(6px)">

                <!-- Search panel -->
                <div class="bg-white w-full shadow-2xl" style="max-height:85vh; overflow-y:auto">
                    <!-- Input row -->
                    <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-3">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 flex-shrink-0" style="color:#C9A66B"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input ref="inputEl"
                                v-model="query"
                                @keydown.escape="close"
                                @keydown.enter="submitSearch"
                                @keydown.arrow-down.prevent="moveDown"
                                @keydown.arrow-up.prevent="moveUp"
                                type="text"
                                placeholder="Search sofas, beds, dining tables…"
                                class="w-full pl-12 pr-4 py-3.5 text-base outline-none rounded-2xl"
                                style="background:#FAF8F5; border:1.5px solid #EDE5D8; color:#1E1B18"
                                autocomplete="off"
                                spellcheck="false">
                            <button v-if="query" @click="query=''" class="absolute right-4 top-1/2 -translate-y-1/2 btn-icon w-6 h-6">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <button @click="close" class="text-sm font-semibold px-4 py-3 rounded-xl transition-colors hover:bg-amber-50 flex-shrink-0" style="color:#8B5E3C">
                            Cancel
                        </button>
                    </div>

                    <!-- Results / Default state -->
                    <div class="max-w-3xl mx-auto px-4 pb-6">

                        <!-- Loading -->
                        <div v-if="loading" class="space-y-3 py-2">
                            <div v-for="n in 4" :key="n" class="flex items-center gap-3 p-3 rounded-xl">
                                <div class="skeleton w-12 h-12 rounded-xl flex-shrink-0"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="skeleton h-3 w-3/4 rounded"></div>
                                    <div class="skeleton h-3 w-1/3 rounded"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Search results -->
                        <template v-else-if="query.length >= 2 && results.length > 0">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">
                                {{ results.length }} result{{ results.length !== 1 ? 's' : '' }} for "{{ query }}"
                            </p>
                            <div class="space-y-1">
                                <RouterLink v-for="(item, i) in results" :key="item.id"
                                    :to="`/product/${item.slug}`"
                                    @click="selectProduct(item)"
                                    class="flex items-center gap-3 p-3 rounded-xl transition-all cursor-pointer"
                                    :class="activeIndex === i ? 'bg-amber-50' : 'hover:bg-gray-50'">
                                    <img :src="item.featured_image ? `/storage/${item.featured_image}` : fallbackImg"
                                        class="w-12 h-12 object-cover rounded-xl flex-shrink-0"
                                        :alt="item.name">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold truncate" style="color:#1E1B18"
                                            v-html="highlight(item.name, query)"></p>
                                        <p class="text-xs mt-0.5" style="color:#9B9087">{{ item.category?.name }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="price-tag text-sm">৳{{ fmt(item.sale_price || item.price) }}</p>
                                        <p v-if="item.sale_price" class="price-original text-xs">৳{{ fmt(item.price) }}</p>
                                    </div>
                                </RouterLink>
                            </div>
                            <div v-if="total > results.length" class="mt-4 text-center">
                                <button @click="submitSearch" class="btn-outline text-sm py-2.5 px-6">
                                    View all {{ total }} results
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <!-- No results -->
                        <div v-else-if="query.length >= 2 && results.length === 0 && !loading"
                            class="py-10 text-center">
                            <div class="text-4xl mb-3">🔍</div>
                            <p class="font-semibold text-base mb-1" style="color:#1E1B18">No results for "{{ query }}"</p>
                            <p class="text-sm mb-4" style="color:#9B9087">Try different keywords or browse our categories</p>
                            <div class="flex flex-wrap justify-center gap-2">
                                <RouterLink v-for="cat in quickCategories" :key="cat.slug"
                                    :to="`/shop/${cat.slug}`" @click="close"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors"
                                    style="background:#F5EFE6; color:#8B5E3C">
                                    {{ cat.name }}
                                </RouterLink>
                            </div>
                        </div>

                        <!-- Default: recent searches + popular categories + trending -->
                        <template v-else-if="!query">
                            <!-- Recent searches -->
                            <div v-if="recentSearches.length > 0" class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#9B9087">Recent Searches</p>
                                    <button @click="clearRecent" class="text-xs" style="color:#C9A66B">Clear</button>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button v-for="term in recentSearches" :key="term"
                                        @click="query = term"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm border transition-colors hover:border-amber-300 hover:bg-amber-50"
                                        style="border-color:#EDE5D8; color:#6D655F">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ term }}
                                    </button>
                                </div>
                            </div>

                            <!-- Quick category chips -->
                            <div class="mb-6">
                                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">Browse Categories</p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    <RouterLink v-for="cat in quickCategories" :key="cat.slug"
                                        :to="`/shop/${cat.slug}`" @click="close"
                                        class="flex items-center gap-2.5 px-4 py-3 rounded-2xl transition-all hover:-translate-y-0.5"
                                        style="background:#F9F3EC; border:1px solid #EDE5D8">
                                        <span class="text-xl">{{ catIcons[cat.slug] || '🪑' }}</span>
                                        <span class="text-sm font-medium" style="color:#1E1B18">{{ cat.name }}</span>
                                    </RouterLink>
                                </div>
                            </div>

                            <!-- Trending searches -->
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#9B9087">Trending</p>
                                <div class="flex flex-wrap gap-2">
                                    <button v-for="term in trending" :key="term"
                                        @click="query = term"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition-colors"
                                        style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8); color:#8B5E3C">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ term }}
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Click outside to close -->
                <div class="flex-1" @click="close"></div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useUiStore } from '../stores/ui.js'

const router = useRouter()
const uiStore = useUiStore()

const query = ref('')
const results = ref([])
const total = ref(0)
const loading = ref(false)
const activeIndex = ref(-1)
const inputEl = ref(null)
const recentSearches = ref([])

const RECENT_KEY = 'lf_recent_searches'
const fallbackImg = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=80&h=80&fit=crop&q=80'

const catIcons = {
    'living-room-furniture': '🛋️',
    'bedroom-furniture': '🛏️',
    'dining-furniture': '🍽️',
    'office-furniture': '💼',
    'storage-cabinets': '🗄️',
    'home-decor': '🪴',
}

const quickCategories = ref([])
const trending = ['Modern Sofa', 'King Bed', 'Dining Table', 'Office Chair', 'Wardrobe', 'Coffee Table']

let debounceTimer = null

function close() { uiStore.closeSearch() }

function fmt(v) { return Number(v).toLocaleString('en-BD') }

function highlight(text, q) {
    if (!q) return text
    const re = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi')
    return text.replace(re, '<mark style="background:#FFF3CD; color:#8B5E3C; border-radius:2px; padding:0 1px">$1</mark>')
}

function moveDown() {
    activeIndex.value = Math.min(activeIndex.value + 1, results.value.length - 1)
}
function moveUp() {
    activeIndex.value = Math.max(activeIndex.value - 1, -1)
}

function submitSearch() {
    const term = activeIndex.value >= 0 && results.value[activeIndex.value]
        ? results.value[activeIndex.value].name
        : query.value.trim()
    if (!term) return
    saveRecent(term)
    router.push({ name: 'shop', query: { search: term } })
    close()
}

function selectProduct(product) {
    saveRecent(product.name)
    close()
}

function saveRecent(term) {
    if (!term) return
    let list = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]')
    list = list.filter(t => t !== term)
    list.unshift(term)
    list = list.slice(0, 6)
    localStorage.setItem(RECENT_KEY, JSON.stringify(list))
    recentSearches.value = list
}

function clearRecent() {
    localStorage.removeItem(RECENT_KEY)
    recentSearches.value = []
}

async function doSearch(q) {
    if (q.length < 2) { results.value = []; total.value = 0; return }
    loading.value = true
    activeIndex.value = -1
    try {
        const { data } = await axios.get('/products', { params: { search: q, per_page: 6 } })
        results.value = data.data || []
        total.value = data.total || 0
    } catch { results.value = []; total.value = 0 }
    finally { loading.value = false }
}

async function loadCategories() {
    try {
        const { data } = await axios.get('/categories')
        quickCategories.value = data.slice(0, 6)
    } catch {}
}

watch(query, (val) => {
    clearTimeout(debounceTimer)
    activeIndex.value = -1
    if (val.length >= 2) {
        loading.value = true
        debounceTimer = setTimeout(() => doSearch(val), 280)
    } else {
        results.value = []
        total.value = 0
        loading.value = false
    }
})

watch(() => uiStore.searchOpen, async (open) => {
    if (open) {
        query.value = ''
        results.value = []
        recentSearches.value = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]')
        document.body.style.overflow = 'hidden'
        await nextTick()
        inputEl.value?.focus()
    } else {
        document.body.style.overflow = ''
    }
})

function onKeydown(e) {
    if (e.key === 'Escape' && uiStore.searchOpen) close()
}

onMounted(() => {
    loadCategories()
    window.addEventListener('keydown', onKeydown)
})
onUnmounted(() => window.removeEventListener('keydown', onKeydown))
</script>

<style scoped>
.search-modal-enter-active { transition: opacity 0.2s ease; }
.search-modal-leave-active { transition: opacity 0.15s ease; }
.search-modal-enter-from, .search-modal-leave-to { opacity: 0; }
.search-modal-enter-active > div,
.search-modal-leave-active > div {
    transition: transform 0.25s cubic-bezier(0.32, 0, 0.15, 1);
}
.search-modal-enter-from > div { transform: translateY(-12px); }
.search-modal-leave-to > div { transform: translateY(-8px); }
</style>
