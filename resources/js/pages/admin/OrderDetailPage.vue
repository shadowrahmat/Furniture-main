<template>
    <div>
        <RouterLink to="/admin/orders" class="adm-back-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </RouterLink>

        <div v-if="loading" class="space-y-4">
            <div class="skeleton h-24 rounded-xl"></div>
            <div class="skeleton h-48 rounded-xl"></div>
        </div>

        <div v-else-if="order" class="space-y-5">
            <!-- Header -->
            <div class="adm-page-header" style="margin-bottom:0">
                <div>
                    <h1 class="adm-page-title">{{ order.order_number }}</h1>
                    <p class="adm-page-sub">Placed on {{ new Date(order.created_at).toLocaleString() }}</p>
                </div>
                <select v-model="order.status" @change="updateStatus" class="status-select" :class="`sel-${order.status}`" style="font-size:13px;padding:8px 16px">
                    <option v-for="s in statuses" :key="s" :value="s">{{ s.charAt(0).toUpperCase() + s.slice(1) }}</option>
                </select>
            </div>

            <div class="adm-detail-grid">
                <div class="space-y-5">
                    <!-- Order Items -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="flex items-center gap-3">
                                <div class="adm-icon-badge grad-walnut">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <p class="adm-card-title">Order Items</p>
                            </div>
                        </div>
                        <div class="adm-card-body space-y-3">
                            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 p-3 rounded-xl" style="background:#F8FAFC">
                                <img :src="item.product_image ? `/storage/${item.product_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=60&h=60&fit=crop'"
                                    class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm" style="color:#0F172A">{{ item.product_name }}</p>
                                    <p class="text-xs mt-0.5" style="color:#94A3B8">Qty: {{ item.quantity }} × ৳{{ Number(item.price).toLocaleString() }}</p>
                                </div>
                                <span class="font-bold text-sm" style="color:#8B5E3C;font-family:'Poppins',sans-serif">৳{{ Number(item.total).toLocaleString() }}</span>
                            </div>

                            <div class="border-t mt-2 pt-4 max-w-xs ml-auto space-y-2" style="border-color:#F1F5F9">
                                <div class="flex justify-between text-sm">
                                    <span style="color:#94A3B8">Subtotal</span>
                                    <span style="color:#475569">৳{{ Number(order.subtotal).toLocaleString() }}</span>
                                </div>
                                <div v-if="order.discount > 0" class="flex justify-between text-sm" style="color:#059669">
                                    <span>Discount</span>
                                    <span>-৳{{ Number(order.discount).toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span style="color:#94A3B8">Delivery</span>
                                    <span style="color:#475569">{{ order.delivery_charge == 0 ? 'FREE' : '৳' + Number(order.delivery_charge).toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-lg pt-2 border-t" style="border-color:#F1F5F9;color:#0F172A">
                                    <span>Total</span>
                                    <span style="color:#8B5E3C;font-family:'Poppins',sans-serif">৳{{ Number(order.total).toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="flex items-center gap-3">
                                <div class="adm-icon-badge grad-walnut">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="adm-card-title">Admin Notes</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <textarea v-model="adminNotes" class="input-field" rows="3" placeholder="Internal notes..."></textarea>
                            <button @click="saveNotes" :disabled="savingNotes" class="adm-btn-primary mt-3">
                                {{ savingNotes ? 'Saving…' : 'Save Notes' }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <!-- Customer Info -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="flex items-center gap-3">
                                <div class="adm-icon-badge grad-walnut">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <p class="adm-card-title">Customer</p>
                            </div>
                        </div>
                        <div class="adm-card-body text-sm" style="color:#475569; line-height:1.8">
                            <p class="font-semibold" style="color:#0F172A">{{ order.full_name }}</p>
                            <p>{{ order.user?.email || 'Guest' }}</p>
                            <p>{{ order.phone }}</p>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="flex items-center gap-3">
                                <div class="adm-icon-badge grad-walnut">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="adm-card-title">Delivery Address</p>
                            </div>
                        </div>
                        <div class="adm-card-body text-sm" style="color:#475569; line-height:1.8">
                            <p>{{ order.address_line1 }}</p>
                            <p v-if="order.address_line2">{{ order.address_line2 }}</p>
                            <p>{{ order.city }}, {{ order.state }}</p>
                            <p>{{ order.country }} {{ order.postal_code }}</p>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="flex items-center gap-3">
                                <div class="adm-icon-badge grad-walnut">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="adm-card-title">Payment</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <p class="text-sm font-semibold uppercase" style="color:#0F172A">{{ order.payment_method }}</p>
                            <span class="adm-badge mt-1.5" :class="order.payment_status === 'paid' ? 'badge-completed' : 'badge-pending'">
                                {{ order.payment_status }}
                            </span>
                            <p v-if="order.notes" class="text-sm mt-3 p-2.5 rounded-lg" style="background:#F8FAFC;color:#64748B">
                                Note: {{ order.notes }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const route = useRoute()
const order = ref(null)
const loading = ref(true)
const savingNotes = ref(false)
const adminNotes = ref('')
const statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled']

async function updateStatus() {
    try {
        await axios.patch(`/admin/orders/${order.value.id}/status`, { status: order.value.status })
        toast.success('Status updated')
    } catch (e) { toast.error('Failed to update status') }
}

async function saveNotes() {
    savingNotes.value = true
    try {
        await axios.patch(`/admin/orders/${order.value.id}/notes`, { admin_notes: adminNotes.value })
        toast.success('Notes saved')
    } catch (e) { toast.error('Failed to save notes') }
    finally { savingNotes.value = false }
}

onMounted(async () => {
    try {
        const { data } = await axios.get(`/admin/orders/${route.params.id}`)
        order.value = data
        adminNotes.value = data.admin_notes || ''
    } catch (e) { toast.error('Failed to load order') }
    finally { loading.value = false }
})
</script>
