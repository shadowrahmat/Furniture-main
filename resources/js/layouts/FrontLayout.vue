<template>
    <div class="min-h-screen flex flex-col" style="background:#FAF8F5">
        <AppNavbar />
        <main class="flex-1 pb-safe-mobile">
            <RouterView v-slot="{ Component }">
                <Transition name="page-fade" mode="out-in">
                    <component :is="Component"/>
                </Transition>
            </RouterView>
        </main>
        <AppFooter />
        <CartDrawer />
        <MobileBottomNav />
        <SearchModal />
        <QuickViewModal />
    </div>
</template>

<script setup>
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'
import CartDrawer from '../components/CartDrawer.vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import SearchModal from '../components/SearchModal.vue'
import QuickViewModal from '../components/QuickViewModal.vue'
</script>

<style>
/* Padding so page content doesn't hide behind floating bottom nav */
.pb-safe-mobile {
    padding-bottom: 0;
}
@media (max-width: 1023px) {
    .pb-safe-mobile {
        padding-bottom: calc(84px + env(safe-area-inset-bottom));
    }
}

/* Page transition */
.page-fade-enter-active,
.page-fade-leave-active {
    transition: opacity 0.2s ease;
}
.page-fade-enter-from,
.page-fade-leave-to {
    opacity: 0;
}
</style>
