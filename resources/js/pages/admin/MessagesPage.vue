<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Messages</h1>
            <div class="flex gap-2">
                <span v-for="tab in tabs" :key="tab.value"
                    @click="filterRead = tab.value; fetchMessages()"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
                    :style="filterRead === tab.value ? 'background:#8B5E3C;color:white' : 'background:#F5EFE6;color:#8B5E3C'">
                    {{ tab.label }}
                </span>
            </div>
        </div>

        <div class="space-y-3">
            <div v-if="loading" v-for="n in 5" :key="n" class="skeleton h-20 rounded-xl"></div>
            <div v-else-if="messages.length === 0" class="bg-white rounded-xl p-12 text-center shadow-sm border" style="border-color:#F0E8DE">
                <div class="text-5xl mb-3">✉️</div>
                <p style="color:#6D655F">No messages found</p>
            </div>
            <div v-else v-for="msg in messages" :key="msg.id"
                class="bg-white rounded-xl p-4 shadow-sm border cursor-pointer hover:shadow-md transition-all"
                :style="!msg.is_read ? 'border-color:#C9A66B' : 'border-color:#F0E8DE'"
                @click="viewMessage(msg)">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0" :style="!msg.is_read ? 'background:#C9A66B' : 'background:transparent'"></div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-sm" :style="!msg.is_read ? 'color:#1E1B18' : 'color:#6D655F'">{{ msg.name }}</p>
                                <span class="text-xs" style="color:#6D655F">{{ msg.email }}</span>
                            </div>
                            <p class="font-medium text-sm mt-0.5" style="color:#1E1B18">{{ msg.subject }}</p>
                            <p class="text-sm mt-1 line-clamp-1" style="color:#6D655F">{{ msg.message }}</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs" style="color:#6D655F">{{ new Date(msg.created_at).toLocaleDateString() }}</p>
                        <span v-if="!msg.is_read" class="inline-block w-2 h-2 rounded-full mt-1" style="background:#C9A66B"></span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 mt-6">
            <button v-for="page in pagination.last_page" :key="page" @click="fetchMessages(page)"
                class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                :style="page === pagination.current_page ? 'background:#8B5E3C;color:white' : 'background:white;color:#8B5E3C;border:1px solid #F0E8DE'">
                {{ page }}
            </button>
        </div>

        <!-- Message Detail Modal -->
        <div v-if="selectedMessage" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-heading font-bold text-xl" style="color:#1E1B18">Message</h3>
                    <button @click="selectedMessage = null" class="p-2 rounded-lg hover:bg-gray-100">✕</button>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl" style="background:#FAF8F5">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-xs font-semibold uppercase" style="color:#6D655F">From</span>
                                <p class="font-medium" style="color:#1E1B18">{{ selectedMessage.name }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold uppercase" style="color:#6D655F">Email</span>
                                <p style="color:#1E1B18">{{ selectedMessage.email }}</p>
                            </div>
                            <div v-if="selectedMessage.phone">
                                <span class="text-xs font-semibold uppercase" style="color:#6D655F">Phone</span>
                                <p style="color:#1E1B18">{{ selectedMessage.phone }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold uppercase" style="color:#6D655F">Date</span>
                                <p style="color:#1E1B18">{{ new Date(selectedMessage.created_at).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#6D655F">Subject</p>
                        <p class="font-semibold" style="color:#1E1B18">{{ selectedMessage.subject }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#6D655F">Message</p>
                        <div class="p-4 rounded-xl text-sm leading-relaxed" style="background:#FAF8F5;color:#4B2E1E">
                            {{ selectedMessage.message }}
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <a :href="`mailto:${selectedMessage.email}?subject=Re: ${selectedMessage.subject}`"
                            class="flex-1 btn-primary text-sm py-2.5 text-center">Reply via Email</a>
                        <button v-if="!selectedMessage.is_read" @click="markAsRead(selectedMessage)" class="flex-1 btn-outline text-sm py-2.5">Mark as Read</button>
                        <button v-else @click="selectedMessage = null" class="flex-1 btn-outline text-sm py-2.5">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
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
