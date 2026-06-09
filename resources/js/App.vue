<template>
    <div>
        <RouterView />
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth.js'
import { useCartStore } from './stores/cart.js'

const authStore = useAuthStore()
const cartStore = useCartStore()

onMounted(async () => {
    if (authStore.isAuthenticated) {
        await authStore.fetchUser()
    }
    await cartStore.fetchCart()
})
</script>
