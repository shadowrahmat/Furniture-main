<template>
    <div class="min-h-screen flex flex-col" style="background:#FAF8F5">
        <AppNavbar />
        <main class="flex-1">
            <RouterView />
        </main>
        <AppFooter />
        <CartDrawer />
        <MobileBottomNav />
    </div>
</template>

<script setup>
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'
import CartDrawer from '../components/CartDrawer.vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
</script>
