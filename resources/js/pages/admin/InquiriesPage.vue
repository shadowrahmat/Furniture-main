<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Custom Furniture Inquiries</h1>
                <p class="adm-page-sub">Bespoke furniture requests from customers</p>
            </div>
        </div>

        <!-- Status tabs -->
        <div class="status-tabs">
            <span v-for="tab in tabs" :key="tab.value"
                @click="filterStatus = tab.value; fetchInquiries()"
                class="status-tab" :class="{ 'status-tab-active': filterStatus === tab.value }">
                {{ tab.label }}
            </span>
        </div>

        <div class="space-y-3">
            <div v-if="loading">
                <div v-for="n in 3" :key="n" class="skeleton h-36 rounded-xl mb-3"></div>
            </div>

            <div v-else-if="inquiries.length === 0" class="adm-card adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No inquiries found</p>
                <p class="adm-empty-sub">Custom furniture requests will appear here.</p>
            </div>

            <div v-else v-for="inquiry in inquiries" :key="inquiry.id"
                class="adm-card" style="padding:18px 20px; cursor:pointer" @click="viewInquiry(inquiry)">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <p class="font-semibold text-sm" style="color:#0F172A">{{ inquiry.name }}</p>
                            <span class="adm-badge" :style="statusStyle(inquiry.status)">
                                {{ inquiry.status }}
                            </span>
                        </div>
                        <p class="text-xs" style="color:#94A3B8">{{ inquiry.email }} · {{ inquiry.phone }}</p>
                        <p class="text-sm mt-2 font-semibold" style="color:#8B5E3C">{{ inquiry.furniture_type }}</p>
                        <p class="text-sm mt-1 line-clamp-2" style="color:#475569">{{ inquiry.description }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs" style="color:#94A3B8">{{ new Date(inquiry.created_at).toLocaleDateString() }}</p>
                        <p v-if="inquiry.budget" class="font-bold mt-1" style="color:#8B5E3C;font-family:'Poppins',sans-serif">৳{{ Number(inquiry.budget).toLocaleString() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiry Detail Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="selectedInquiry" class="adm-modal-overlay" @click.self="selectedInquiry = null">
                    <div class="adm-modal-form">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="adm-modal-title" style="margin-bottom:0">Inquiry Details</h3>
                            <button @click="selectedInquiry = null" class="adm-act-btn">✕</button>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl" style="background:#F8FAFC">
                                    <p class="adm-form-label">Name</p>
                                    <p class="font-medium" style="color:#0F172A">{{ selectedInquiry.name }}</p>
                                </div>
                                <div class="p-3 rounded-xl" style="background:#F8FAFC">
                                    <p class="adm-form-label">Phone</p>
                                    <p class="font-medium" style="color:#0F172A">{{ selectedInquiry.phone }}</p>
                                </div>
                                <div class="p-3 rounded-xl col-span-2" style="background:#F8FAFC">
                                    <p class="adm-form-label">Email</p>
                                    <p class="font-medium" style="color:#0F172A">{{ selectedInquiry.email }}</p>
                                </div>
                                <div class="p-3 rounded-xl" style="background:#F8FAFC">
                                    <p class="adm-form-label">Furniture Type</p>
                                    <p class="font-medium" style="color:#8B5E3C">{{ selectedInquiry.furniture_type }}</p>
                                </div>
                                <div class="p-3 rounded-xl" style="background:#F8FAFC">
                                    <p class="adm-form-label">Budget</p>
                                    <p class="font-bold" style="color:#8B5E3C;font-family:'Poppins',sans-serif">{{ selectedInquiry.budget ? '৳' + Number(selectedInquiry.budget).toLocaleString() : 'Not specified' }}</p>
                                </div>
                                <div class="p-3 rounded-xl col-span-2" style="background:#F8FAFC">
                                    <p class="adm-form-label">Description</p>
                                    <p style="color:#475569">{{ selectedInquiry.description }}</p>
                                </div>
                                <div v-if="selectedInquiry.dimensions" class="p-3 rounded-xl" style="background:#F8FAFC">
                                    <p class="adm-form-label">Dimensions</p>
                                    <p style="color:#0F172A">{{ selectedInquiry.dimensions }}</p>
                                </div>
                                <div v-if="selectedInquiry.material_preference" class="p-3 rounded-xl" style="background:#F8FAFC">
                                    <p class="adm-form-label">Material</p>
                                    <p style="color:#0F172A">{{ selectedInquiry.material_preference }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="adm-form-label">Update Status</label>
                                <select v-model="selectedInquiry.status" @change="updateInquiryStatus" class="input-field">
                                    <option v-for="s in statuses" :key="s" :value="s">{{ s.charAt(0).toUpperCase() + s.slice(1) }}</option>
                                </select>
                            </div>
                        </div>

                        <button @click="selectedInquiry = null" class="adm-btn-ghost mt-5" style="width:100%">Close</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
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
    return m[s] || 'background:#F1F5F9;color:#475569'
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
