<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Reviews</h1>
                <p class="adm-page-sub">Moderate customer ratings & feedback</p>
            </div>
        </div>

        <!-- Status tabs -->
        <div class="status-tabs">
            <span v-for="tab in tabs" :key="tab.value"
                @click="filterStatus = tab.value; fetchReviews()"
                class="status-tab" :class="{ 'status-tab-active': filterStatus === tab.value }">
                {{ tab.label }}
            </span>
        </div>

        <div class="space-y-3">
            <div v-if="loading">
                <div v-for="n in 4" :key="n" class="skeleton h-28 rounded-xl mb-3"></div>
            </div>

            <div v-else-if="reviews.length === 0" class="adm-card adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No reviews found</p>
                <p class="adm-empty-sub">Reviews matching this filter will appear here.</p>
            </div>

            <div v-else v-for="review in reviews" :key="review.id" class="adm-card" style="padding:18px 20px">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <img :src="`https://ui-avatars.com/api/?name=${review.user?.name || 'G'}&background=8B5E3C&color=fff&size=40`"
                            class="w-10 h-10 rounded-full flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-sm" style="color:#0F172A">{{ review.user?.name || 'Anonymous' }}</p>
                                <div class="flex">
                                    <span v-for="n in 5" :key="n" class="text-sm" :style="n <= review.rating ? 'color:#C9A055' : 'color:#E2E8F0'">★</span>
                                </div>
                                <span class="text-xs" style="color:#94A3B8">{{ new Date(review.created_at).toLocaleDateString() }}</span>
                            </div>
                            <p v-if="review.product" class="text-xs mt-1 font-semibold" style="color:#8B5E3C">
                                On: {{ review.product.name }}
                            </p>
                            <p class="text-sm mt-2" style="color:#475569">{{ review.comment }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="adm-badge" :class="review.is_approved ? 'badge-completed' : 'badge-pending'">
                            {{ review.is_approved ? 'Approved' : 'Pending' }}
                        </span>
                        <button v-if="!review.is_approved" @click="approveReview(review)" class="adm-act-btn adm-act-success">
                            Approve
                        </button>
                        <button @click="confirmDelete(review)" class="adm-act-btn adm-act-danger">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && pagination.last_page > 1" style="display:flex; justify-content:center; margin-top:20px">
            <div class="pag-btns">
                <button @click="fetchReviews(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1" class="pag-btn">‹</button>
                <button v-for="page in visiblePages" :key="page"
                    @click="fetchReviews(page)"
                    class="pag-btn" :class="{ 'pag-active': page === pagination.current_page }">
                    {{ page }}
                </button>
                <button @click="fetchReviews(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page" class="pag-btn">›</button>
            </div>
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
                        <h3 class="adm-modal-title">Delete Review?</h3>
                        <p class="adm-modal-desc">This action cannot be undone.</p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteReview" :disabled="deleting" class="adm-btn-danger">
                                {{ deleting ? 'Deleting…' : 'Delete' }}
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

const reviews = ref([])
const loading = ref(true)
const filterStatus = ref('pending')
const deleteTarget = ref(null)
const deleting = ref(false)
const pagination = reactive({ current_page: 1, last_page: 1 })

const tabs = [
    { label: 'Pending', value: 'pending' },
    { label: 'Approved', value: 'approved' },
    { label: 'All', value: '' },
]

const visiblePages = computed(() => {
    const pages = []
    const c = pagination.current_page, l = pagination.last_page
    for (let p = Math.max(1, c - 2); p <= Math.min(l, c + 2); p++) pages.push(p)
    return pages
})

async function fetchReviews(page = 1) {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/reviews', { params: { page, status: filterStatus.value } })
        reviews.value = data.data
        Object.assign(pagination, { current_page: data.current_page, last_page: data.last_page })
    } catch (e) { toast.error('Failed to load reviews') }
    finally { loading.value = false }
}

async function approveReview(review) {
    try {
        await axios.patch(`/admin/reviews/${review.id}/approve`)
        review.is_approved = true
        toast.success('Review approved')
    } catch (e) { toast.error('Failed to approve review') }
}

function confirmDelete(review) { deleteTarget.value = review }

async function deleteReview() {
    deleting.value = true
    try {
        await axios.delete(`/admin/reviews/${deleteTarget.value.id}`)
        reviews.value = reviews.value.filter(r => r.id !== deleteTarget.value.id)
        toast.success('Review deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete review') }
    finally { deleting.value = false }
}

onMounted(fetchReviews)
</script>
