<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Custom Furniture Inquiries</h1>
            <div class="flex gap-2">
                <span v-for="tab in tabs" :key="tab.value"
                    @click="filterStatus = tab.value; fetchInquiries()"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
                    :style="filterStatus === tab.value ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                    {{ tab.label }}
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div v-if="loading" v-for="n in 3" :key="n" class="skeleton h-40 rounded-xl"></div>
            <div v-else-if="inquiries.length === 0" class="bg-white rounded-xl p-12 text-center shadow-sm border" style="border-color:#F0E8DE">
                <div class="text-5xl mb-3">🔨</div>
                <p style="color:#6D655F">No inquiries found</p>
            </div>
            <div v-else v-for="inquiry in inquiries" :key="inquiry.id"
                class="bg-white rounded-xl p-5 shadow-sm border cursor-pointer hover:shadow-md transition-shadow"
                style="border-color:#F0E8DE" @click="viewInquiry(inquiry)">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <p class="font-semibold" style="color:#1E1B18">{{ inquiry.name }}</p>
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold" :style="statusStyle(inquiry.status)">
                                {{ inquiry.status }}
                            </span>
                        </div>
                        <p class="text-sm" style="color:#6D655F">{{ inquiry.email }} · {{ inquiry.phone }}</p>
                        <p class="text-sm mt-2 font-medium" style="color:#8B5E3C">{{ inquiry.furniture_type }}</p>
                        <p class="text-sm mt-1 line-clamp-2" style="color:#4B2E1E">{{ inquiry.description }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs" style="color:#6D655F">{{ new Date(inquiry.created_at).toLocaleDateString() }}</p>
                        <p v-if="inquiry.budget" class="font-bold price-tag mt-1">৳{{ Number(inquiry.budget).toLocaleString() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiry Detail Modal -->
        <div v-if="selectedInquiry" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-heading font-bold text-xl" style="color:#1E1B18">Inquiry Details</h3>
                    <button @click="selectedInquiry = null" class="p-2 rounded-lg hover:bg-gray-100">✕</button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Name</p>
                            <p class="font-medium" style="color:#1E1B18">{{ selectedInquiry.name }}</p>
                        </div>
                        <div class="p-3 rounded-xl" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Phone</p>
                            <p class="font-medium" style="color:#1E1B18">{{ selectedInquiry.phone }}</p>
                        </div>
                        <div class="p-3 rounded-xl col-span-2" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Email</p>
                            <p class="font-medium" style="color:#1E1B18">{{ selectedInquiry.email }}</p>
                        </div>
                        <div class="p-3 rounded-xl" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Furniture Type</p>
                            <p class="font-medium" style="color:#8B5E3C">{{ selectedInquiry.furniture_type }}</p>
                        </div>
                        <div class="p-3 rounded-xl" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Budget</p>
                            <p class="font-bold price-tag">{{ selectedInquiry.budget ? '৳' + Number(selectedInquiry.budget).toLocaleString() : 'Not specified' }}</p>
                        </div>
                        <div class="p-3 rounded-xl col-span-2" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Description</p>
                            <p style="color:#4B2E1E">{{ selectedInquiry.description }}</p>
                        </div>
                        <div v-if="selectedInquiry.dimensions" class="p-3 rounded-xl" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Dimensions</p>
                            <p style="color:#1E1B18">{{ selectedInquiry.dimensions }}</p>
                        </div>
                        <div v-if="selectedInquiry.material_preference" class="p-3 rounded-xl" style="background:#FAF8F5">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Material</p>
                            <p style="color:#1E1B18">{{ selectedInquiry.material_preference }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Update Status</label>
                        <select v-model="selectedInquiry.status" @change="updateInquiryStatus" class="input-field text-sm">
                            <option v-for="s in statuses" :key="s" :value="s">{{ s.charAt(0).toUpperCase() + s.slice(1) }}</option>
                        </select>
                    </div>
                </div>

                <button @click="selectedInquiry = null" class="w-full btn-outline text-sm py-2.5 mt-5">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const inquiries = ref([])
const loading = ref(true)
const filterStatus = ref('')
const selectedInquiry = ref(null)
const statuses = ['new', 'in_progress', 'quoted', 'accepted', 'rejected', 'completed']

const tabs = [
    { label: 'All', value: '' },
    { label: 'New', value: 'new' },
    { label: 'In Progress', value: 'in_progress' },
    { label: 'Completed', value: 'completed' },
]

function statusStyle(s) {
    const m = { new: 'background:#DBEAFE;color:#1E40AF', in_progress: 'background:#FEF3C7;color:#92400E', quoted: 'background:#F3E8FF;color:#7C3AED', accepted: 'background:#D1FAE5;color:#065F46', rejected: 'background:#FEE2E2;color:#991B1B', completed: 'background:#D1FAE5;color:#065F46' }
    return m[s] || 'background:#F3F4F6;color:#374151'
}

async function fetchInquiries() {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/inquiries', { params: { status: filterStatus.value } })
        inquiries.value = data.data || data
    } catch (e) { toast.error('Failed to load inquiries') }
    finally { loading.value = false }
}

function viewInquiry(inquiry) { selectedInquiry.value = { ...inquiry } }

async function updateInquiryStatus() {
    try {
        await axios.patch(`/admin/inquiries/${selectedInquiry.value.id}/status`, { status: selectedInquiry.value.status })
        const idx = inquiries.value.findIndex(i => i.id === selectedInquiry.value.id)
        if (idx !== -1) inquiries.value[idx].status = selectedInquiry.value.status
        toast.success('Status updated')
    } catch (e) { toast.error('Failed to update status') }
}

onMounted(fetchInquiries)
</script>
