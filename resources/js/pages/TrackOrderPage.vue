<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-10 sm:py-14 text-center">
                <p class="label-tag justify-center mb-3" style="color:#E8C47A">Order Status</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white mb-3">Track Your Order</h1>
                <p class="text-sm max-w-md mx-auto" style="color:rgba(245,239,230,0.65)">Enter your order number to check the status of your delivery.</p>
            </div>
        </section>

        <div class="max-w-2xl mx-auto px-4 py-8 sm:py-10">
            <div class="card-premium p-6 mb-8 anim-fade-up">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input v-model="orderNumber" type="text" placeholder="Enter order number (e.g. FS-ABC123)"
                        class="input-field flex-1" @keyup.enter="trackOrder">
                    <button @click="trackOrder" :disabled="loading" class="btn-primary justify-center px-6">
                        <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <template v-else>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Track
                        </template>
                    </button>
                </div>
            </div>

            <!-- Result -->
            <div v-if="order" class="card-premium p-6 anim-fade-up">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6D655F">Order Number</p>
                        <p class="font-heading font-bold text-xl" style="color:#8B5E3C">{{ order.order_number }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold"
                        :style="statusStyle(order.status)">
                        {{ order.status_label || order.status }}
                    </span>
                </div>

                <!-- Timeline -->
                <div class="space-y-4 mb-6">
                    <div v-for="(step, idx) in timeline" :key="step.status"
                        class="flex items-center gap-4 relative">
                        <span v-if="idx < timeline.length - 1" class="absolute left-4 top-8 w-0.5 h-6"
                            :style="step.done ? 'background:#C9A66B' : 'background:#EDE5D8'"></span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-all relative z-10"
                            :style="step.done ? 'background:linear-gradient(135deg,#8B5E3C,#C9A66B)' : 'background:#F5EFE6; border:2px solid #E8DDD4'">
                            <svg v-if="step.done" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span v-else class="w-2 h-2 rounded-full" style="background:#D0C8C0"></span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm" :style="step.done ? 'color:#1E1B18' : 'color:#9B9087'">{{ step.label }}</p>
                            <p class="text-xs" style="color:#6D655F">{{ step.desc }}</p>
                        </div>
                        <span v-if="step.done" class="text-xs" style="color:#C9A66B">✓</span>
                    </div>
                </div>

                <!-- Items -->
                <div>
                    <h3 class="font-semibold mb-3" style="color:#1E1B18">Items Ordered</h3>
                    <div class="space-y-2">
                        <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm py-2 border-b" style="border-color:#F0E8DE">
                            <span style="color:#6D655F">{{ item.product_name }} × {{ item.quantity }}</span>
                            <span class="font-semibold" style="color:#1E1B18">৳{{ Number(item.total).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold pt-2">
                            <span>Total</span>
                            <span class="price-tag">৳{{ Number(order.total).toLocaleString() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Initial / empty hint -->
            <div v-else class="flex flex-col items-center text-center py-10 anim-fade-up">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mb-5"
                    style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                    <svg class="w-10 h-10" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm" style="color:#9B9087">Your order details will appear here once you search.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const orderNumber = ref('')
const order = ref(null)
const loading = ref(false)

const statuses = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'completed']

const timeline = computed(() => {
    if (!order.value) return []
    const currentIdx = statuses.indexOf(order.value.status)
    return [
        { status: 'pending', label: 'Order Placed', desc: 'Your order has been received', done: currentIdx >= 0 },
        { status: 'confirmed', label: 'Order Confirmed', desc: 'We are preparing your furniture', done: currentIdx >= 1 },
        { status: 'processing', label: 'Processing', desc: 'Quality check and packaging', done: currentIdx >= 2 },
        { status: 'out_for_delivery', label: 'Out for Delivery', desc: 'Your order is on its way', done: currentIdx >= 3 },
        { status: 'completed', label: 'Delivered', desc: 'Order delivered successfully', done: currentIdx >= 4 },
    ]
})

function statusStyle(status) {
    const styles = {
        pending: 'background:#FEF3C7; color:#92400E',
        confirmed: 'background:#DBEAFE; color:#1E40AF',
        processing: 'background:#EDE9FE; color:#5B21B6',
        out_for_delivery: 'background:#FED7AA; color:#C2410C',
        completed: 'background:#D1FAE5; color:#065F46',
        cancelled: 'background:#FEE2E2; color:#991B1B',
    }
    return styles[status] || 'background:#F3F4F6; color:#374151'
}

async function trackOrder() {
    if (!orderNumber.value.trim()) return
    loading.value = true
    try {
        const { data } = await axios.get(`/orders/track/${orderNumber.value}`)
        order.value = data
    } catch (e) {
        toast.error('Order not found. Please check the order number.')
        order.value = null
    } finally {
        loading.value = false
    }
}
</script>
