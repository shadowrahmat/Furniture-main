<template>
    <div>
        <div class="flex items-center gap-4 mb-6">
            <button @click="$router.back()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" style="color:#6D655F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Order Details</h1>
        </div>

        <div v-if="loading" class="space-y-4">
            <div class="skeleton h-24 rounded-xl"></div>
            <div class="skeleton h-48 rounded-xl"></div>
        </div>

        <div v-else-if="order" class="space-y-5">
            <!-- Header -->
            <div class="bg-white rounded-xl p-5 shadow-sm border flex flex-wrap items-center justify-between gap-4" style="border-color:#F0E8DE">
                <div>
                    <h2 class="font-heading font-bold text-xl" style="color:#1E1B18">{{ order.order_number }}</h2>
                    <p class="text-sm mt-1" style="color:#6D655F">Placed on {{ new Date(order.created_at).toLocaleString() }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <select v-model="order.status" @change="updateStatus" class="text-sm px-3 py-2 rounded-lg border font-medium" style="border-color:#C9A66B;color:#1E1B18">
                        <option v-for="s in statuses" :key="s" :value="s">{{ s.charAt(0).toUpperCase() + s.slice(1) }}</option>
                    </select>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold" :style="statusStyle(order.status)">{{ order.status }}</span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                    <h3 class="font-semibold mb-3" style="color:#1E1B18">Customer</h3>
                    <p class="text-sm font-medium" style="color:#1E1B18">{{ order.full_name }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.user?.email || 'Guest' }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.phone }}</p>
                </div>

                <!-- Delivery Address -->
                <div class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                    <h3 class="font-semibold mb-3" style="color:#1E1B18">Delivery Address</h3>
                    <p class="text-sm" style="color:#6D655F">{{ order.address_line1 }}</p>
                    <p v-if="order.address_line2" class="text-sm" style="color:#6D655F">{{ order.address_line2 }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.city }}, {{ order.state }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ order.country }} {{ order.postal_code }}</p>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                    <h3 class="font-semibold mb-3" style="color:#1E1B18">Payment</h3>
                    <p class="text-sm font-semibold uppercase" style="color:#1E1B18">{{ order.payment_method }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold mt-1 inline-block"
                        :style="order.payment_status === 'paid' ? 'background:#D1FAE5;color:#065F46' : 'background:#FEF3C7;color:#92400E'">
                        {{ order.payment_status }}
                    </span>
                    <p v-if="order.notes" class="text-sm mt-3 p-2 rounded-lg" style="background:#FAF8F5;color:#6D655F">
                        Note: {{ order.notes }}
                    </p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                <h3 class="font-semibold mb-4" style="color:#1E1B18">Order Items</h3>
                <div class="space-y-3">
                    <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 p-3 rounded-xl" style="background:#FAF8F5">
                        <img :src="item.product_image ? `/storage/${item.product_image}` : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=60&h=60&fit=crop'"
                            class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1">
                            <p class="font-semibold text-sm" style="color:#1E1B18">{{ item.product_name }}</p>
                            <p class="text-xs" style="color:#6D655F">Qty: {{ item.quantity }} × ৳{{ Number(item.price).toLocaleString() }}</p>
                        </div>
                        <span class="font-bold price-tag">৳{{ Number(item.total).toLocaleString() }}</span>
                    </div>
                </div>

                <div class="border-t mt-4 pt-4 max-w-xs ml-auto space-y-2" style="border-color:#F0E8DE">
                    <div class="flex justify-between text-sm">
                        <span style="color:#6D655F">Subtotal</span>
                        <span>৳{{ Number(order.subtotal).toLocaleString() }}</span>
                    </div>
                    <div v-if="order.discount > 0" class="flex justify-between text-sm text-green-600">
                        <span>Discount</span>
                        <span>-৳{{ Number(order.discount).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color:#6D655F">Delivery</span>
                        <span>{{ order.delivery_charge == 0 ? 'FREE' : '৳' + Number(order.delivery_charge).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t" style="border-color:#F0E8DE">
                        <span>Total</span>
                        <span class="price-tag">৳{{ Number(order.total).toLocaleString() }}</span>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="bg-white rounded-xl p-5 shadow-sm border" style="border-color:#F0E8DE">
                <h3 class="font-semibold mb-3" style="color:#1E1B18">Admin Notes</h3>
                <textarea v-model="adminNotes" class="input-field" rows="3" placeholder="Internal notes..."></textarea>
                <button @click="saveNotes" :disabled="savingNotes" class="btn-primary text-sm mt-3">
                    {{ savingNotes ? 'Saving...' : 'Save Notes' }}
                </button>
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

function statusStyle(s) {
    const m = { pending: 'background:#FEF3C7;color:#92400E', processing: 'background:#DBEAFE;color:#1E40AF', shipped: 'background:#F3E8FF;color:#7C3AED', completed: 'background:#D1FAE5;color:#065F46', cancelled: 'background:#FEE2E2;color:#991B1B' }
    return m[s] || 'background:#F3F4F6;color:#374151'
}

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
