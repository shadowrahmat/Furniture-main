<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Reviews</h1>
            <div class="flex gap-2">
                <span v-for="tab in tabs" :key="tab.value"
                    @click="filterStatus = tab.value; fetchReviews()"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
                    :style="filterStatus === tab.value ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                    {{ tab.label }}
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div v-if="loading" v-for="n in 5" :key="n" class="skeleton h-32 rounded-xl"></div>
            <div v-else-if="reviews.length === 0" class="bg-white rounded-xl p-12 text-center shadow-sm border" style="border-color:#F0E8DE">
                <div class="text-5xl mb-3">⭐</div>
                <p style="color:#6D655F">No reviews found</p>
            </div>
            <div v-else v-for="review in reviews" :key="review.id" class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <img :src="`https://ui-avatars.com/api/?name=${review.user?.name || 'G'}&background=8B5E3C&color=fff&size=40`"
                            class="w-10 h-10 rounded-full flex-shrink-0">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-sm" style="color:#1E1B18">{{ review.user?.name || 'Anonymous' }}</p>
                                <div class="flex">
                                    <span v-for="n in 5" :key="n" class="text-sm" :style="n <= review.rating ? 'color:#C9A66B' : 'color:#E5E7EB'">★</span>
                                </div>
                                <span class="text-xs" style="color:#6D655F">{{ new Date(review.created_at).toLocaleDateString() }}</span>
                            </div>
                            <p v-if="review.product" class="text-xs mt-0.5 font-medium" style="color:#8B5E3C">
                                On: {{ review.product.name }}
                            </p>
                            <p class="text-sm mt-2" style="color:#4B2E1E">{{ review.comment }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                            :style="review.is_approved ? 'background:#D1FAE5;color:#065F46' : 'background:#FEF3C7;color:#92400E'">
                            {{ review.is_approved ? 'Approved' : 'Pending' }}
                        </span>
                        <button v-if="!review.is_approved" @click="approveReview(review)"
                            class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#D1FAE5;color:#065F46">
                            Approve
                        </button>
                        <button @click="confirmDelete(review)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#FEE2E2;color:#991B1B">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 mt-6">
            <button v-for="page in pagination.last_page" :key="page" @click="fetchReviews(page)"
                class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                :style="page === pagination.current_page ? 'background:#8B5E3C;color:white' : 'background:white;color:#8B5E3C;border:1px solid #F0E8DE'">
                {{ page }}
            </button>
        </div>

        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
                <h3 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Delete Review?</h3>
                <p class="mb-6 text-sm" style="color:#6D655F">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button @click="deleteTarget = null" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                    <button @click="deleteReview" :disabled="deleting" class="flex-1 text-sm py-2 rounded-lg font-semibold text-white" style="background:#DC2626">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
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
