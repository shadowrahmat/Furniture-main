<template>
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12 relative overflow-hidden">
        <!-- Decorative glow -->
        <div class="absolute inset-0 -z-10 opacity-40"
            style="background:radial-gradient(circle at 50% 0%, #F0E8DC 0%, transparent 60%)"></div>

        <div class="max-w-lg w-full text-center anim-scale-in">
            <div class="card-premium p-8 sm:p-12 relative overflow-hidden">
                <!-- confetti dots -->
                <span class="absolute w-2 h-2 rounded-full anim-float" style="top:18px; left:24px; background:#C9A055"></span>
                <span class="absolute w-2.5 h-2.5 rounded-full anim-float delay-200" style="top:36px; right:36px; background:#8B5E3C"></span>
                <span class="absolute w-1.5 h-1.5 rounded-full anim-float delay-100" style="bottom:28px; left:48px; background:#3D7A50"></span>
                <span class="absolute w-2 h-2 rounded-full anim-float delay-300" style="bottom:44px; right:24px; background:#C9A055"></span>

                <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6"
                    style="background:linear-gradient(135deg,#3D7A50,#6B9A6B); box-shadow:0 12px 32px rgba(61,122,80,0.3)">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <p class="label-tag justify-center mb-3" style="color:#C9A66B">Order Confirmed</p>
                <h1 class="font-heading font-bold text-3xl mb-3" style="color:#1E1B18">Thank You!</h1>
                <p class="mb-6" style="color:#6D655F">Your order has been placed successfully. We'll send a confirmation and updates as it's processed.</p>

                <div class="rounded-2xl p-5 mb-8" style="background:#F5EFE6; border:1px dashed #D4B896">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#9B9087">Order Number</p>
                    <p class="font-heading font-bold text-2xl tracking-wide" style="color:#8B5E3C">{{ $route.params.orderNumber }}</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <RouterLink :to="isAuth ? '/account/orders' : '/track-order'" class="btn-primary justify-center">
                        {{ isAuth ? 'View Orders' : 'Track Order' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </RouterLink>
                    <RouterLink to="/shop" class="btn-outline justify-center">Continue Shopping</RouterLink>
                </div>
            </div>

            <!-- Steps recap -->
            <div class="grid grid-cols-3 gap-3 mt-6 anim-fade-up delay-200">
                <div v-for="step in steps" :key="step.label" class="card-flat p-4 text-center">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center mx-auto mb-2"
                        :style="step.done ? 'background:#3D7A50' : 'background:#F0E8DE'">
                        <svg class="w-4 h-4" :style="step.done ? 'color:#fff' : 'color:#C9A66B'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="step.done" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            <circle v-else cx="12" cy="12" r="3" stroke-width="2"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold" style="color:#1E1B18">{{ step.label }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth.js'
const authStore = useAuthStore()
const isAuth = computed(() => authStore.isAuthenticated)

const steps = [
    { label: 'Order Placed', done: true },
    { label: 'Processing', done: false },
    { label: 'Delivered', done: false },
]
</script>
