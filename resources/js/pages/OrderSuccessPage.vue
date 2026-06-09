<template>
    <div class="max-w-2xl mx-auto px-4 py-16 text-center">
        <div class="card-premium p-12">
            <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6"
                style="background:linear-gradient(135deg,#6B7A52,#8a9a6a)">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="font-heading font-bold text-3xl mb-3" style="color:#1E1B18">Order Placed!</h1>
            <p class="mb-6" style="color:#6D655F">Thank you for your order. We'll confirm it shortly.</p>

            <div class="rounded-2xl p-5 mb-8" style="background:#F5EFE6">
                <p class="text-sm font-semibold mb-1" style="color:#6D655F">Order Number</p>
                <p class="font-heading font-bold text-2xl" style="color:#8B5E3C">{{ $route.params.orderNumber }}</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <RouterLink :to="isAuth ? '/account/orders' : '/track-order'" class="btn-primary">
                    {{ isAuth ? 'View Orders' : 'Track Order' }}
                </RouterLink>
                <RouterLink to="/shop" class="btn-outline">Continue Shopping</RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth.js'
const authStore = useAuthStore()
const isAuth = computed(() => authStore.isAuthenticated)
</script>
