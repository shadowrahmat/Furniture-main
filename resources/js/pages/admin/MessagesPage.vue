<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Messages</h1>
                <p class="adm-page-sub">Enquiries submitted via the contact form</p>
            </div>
        </div>

        <!-- Status tabs -->
        <div class="status-tabs">
            <span v-for="tab in tabs" :key="tab.value"
                @click="filterRead = tab.value; fetchMessages()"
                class="status-tab" :class="{ 'status-tab-active': filterRead === tab.value }">
                {{ tab.label }}
            </span>
        </div>

        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 5" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="messages.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No messages found</p>
                <p class="adm-empty-sub">Customer messages will appear here.</p>
            </div>

            <template v-else>
                <div v-for="msg in messages" :key="msg.id" class="adm-list-row" style="cursor:pointer" @click="viewMessage(msg)">
                    <div class="w-2 h-2 rounded-full flex-shrink-0" :style="!msg.is_read ? 'background:#C9A055' : 'background:transparent'"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-sm" :style="!msg.is_read ? 'color:#0F172A' : 'color:#64748B'">{{ msg.name }}</p>
                            <span class="text-xs" style="color:#94A3B8">{{ msg.email }}</span>
                        </div>
                        <p class="font-medium text-sm mt-0.5" style="color:#334155">{{ msg.subject }}</p>
                        <p class="text-sm mt-1 truncate" style="color:#94A3B8">{{ msg.message }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs" style="color:#94A3B8">{{ new Date(msg.created_at).toLocaleDateString() }}</p>
                        <span v-if="!msg.is_read" class="adm-badge badge-pending mt-1">New</span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && pagination.last_page > 1" style="display:flex; justify-content:center; margin-top:20px">
            <div class="pag-btns">
                <button @click="fetchMessages(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1" class="pag-btn">‹</button>
                <button v-for="page in visiblePages" :key="page"
                    @click="fetchMessages(page)"
                    class="pag-btn" :class="{ 'pag-active': page === pagination.current_page }">
                    {{ page }}
                </button>
                <button @click="fetchMessages(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page" class="pag-btn">›</button>
            </div>
        </div>

        <!-- Message Detail Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="selectedMessage" class="adm-modal-overlay" @click.self="selectedMessage = null">
                    <div class="adm-modal-form">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="adm-modal-title" style="margin-bottom:0">Message</h3>
                            <button @click="selectedMessage = null" class="adm-act-btn">✕</button>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 rounded-xl" style="background:#F8FAFC">
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="adm-form-label">From</p>
                                        <p class="font-medium" style="color:#0F172A">{{ selectedMessage.name }}</p>
                                    </div>
                                    <div>
                                        <p class="adm-form-label">Email</p>
                                        <p style="color:#0F172A">{{ selectedMessage.email }}</p>
                                    </div>
                                    <div v-if="selectedMessage.phone">
                                        <p class="adm-form-label">Phone</p>
                                        <p style="color:#0F172A">{{ selectedMessage.phone }}</p>
                                    </div>
                                    <div>
                                        <p class="adm-form-label">Date</p>
                                        <p style="color:#0F172A">{{ new Date(selectedMessage.created_at).toLocaleString() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="adm-form-label">Subject</p>
                                <p class="font-semibold" style="color:#0F172A">{{ selectedMessage.subject }}</p>
                            </div>

                            <div>
                                <p class="adm-form-label">Message</p>
                                <div class="p-4 rounded-xl text-sm leading-relaxed" style="background:#F8FAFC;color:#475569">
                                    {{ selectedMessage.message }}
                                </div>
                            </div>

                            <div class="adm-modal-actions pt-2">
                                <a :href="`mailto:${selectedMessage.email}?subject=Re: ${selectedMessage.subject}`"
                                    class="adm-btn-primary" style="flex:1; justify-content:center">Reply via Email</a>
                                <button v-if="!selectedMessage.is_read" @click="markAsRead(selectedMessage)" class="adm-btn-ghost">Mark as Read</button>
                                <button v-else @click="selectedMessage = null" class="adm-btn-ghost">Close</button>
                            </div>
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

const messages = ref([])
const loading = ref(true)
const filterRead = ref('unread')
const selectedMessage = ref(null)
const pagination = reactive({ current_page: 1, last_page: 1 })

const tabs = [
    { label: 'Unread', value: 'unread' },
    { label: 'All', value: '' },
]

const visiblePages = computed(() => {
    const pages = []
    const c = pagination.current_page, l = pagination.last_page
    for (let p = Math.max(1, c - 2); p <= Math.min(l, c + 2); p++) pages.push(p)
    return pages
})

async function fetchMessages(page = 1) {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/messages', { params: { page, read: filterRead.value } })
        messages.value = data.data || data
        if (data.last_page) Object.assign(pagination, { current_page: data.current_page, last_page: data.last_page })
    } catch (e) { toast.error('Failed to load messages') }
    finally { loading.value = false }
}

async function viewMessage(msg) {
    selectedMessage.value = { ...msg }
    if (!msg.is_read) await markAsRead(msg)
}

async function markAsRead(msg) {
    try {
        await axios.patch(`/admin/messages/${msg.id}/read`)
        const idx = messages.value.findIndex(m => m.id === msg.id)
        if (idx !== -1) messages.value[idx].is_read = true
        if (selectedMessage.value?.id === msg.id) selectedMessage.value.is_read = true
    } catch (e) {}
}

onMounted(fetchMessages)
</script>
