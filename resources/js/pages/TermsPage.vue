<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-12 sm:py-16 text-center">
                <p class="label-tag justify-center mb-3" style="color:#E8C47A">Legal</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white mb-3">Terms & Conditions</h1>
                <p class="text-sm" style="color:rgba(245,239,230,0.65)">Last updated: May 2026</p>
            </div>
        </section>

        <div class="max-w-3xl mx-auto px-4 py-8 sm:py-12 space-y-4">
            <div v-for="(section, idx) in sections" :key="section.title"
                class="card-premium p-6 sm:p-8 anim-fade-up" :style="`animation-delay:${idx * 0.07}s`">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 grad-walnut">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="section.icon"></svg>
                    </div>
                    <h2 class="font-heading font-bold text-lg" style="color:#1E1B18">{{ section.title }}</h2>
                </div>
                <p class="text-sm leading-relaxed pl-14" style="color:#6D655F">{{ section.body }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
const sections = [
    {
        title: 'Acceptance of Terms',
        body: 'By accessing and using Furniture Store, you accept and agree to be bound by these terms and conditions.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    },
    {
        title: 'Products & Pricing',
        body: 'All prices are in BDT. We reserve the right to modify prices without prior notice. Product images are for illustration purposes and may slightly differ from actual products.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/>',
    },
    {
        title: 'Orders & Payment',
        body: 'Orders are subject to availability. We reserve the right to cancel any order in case of payment issues or product unavailability.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m2 0h6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>',
    },
    {
        title: 'Returns & Refunds',
        body: 'Returns are accepted within 7 days for defective items only. Custom orders are non-refundable.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a4 4 0 014 4v1m0 0l-3-3m3 3l-3 3M21 14H11a4 4 0 01-4-4v-1"/>',
    },
]
</script>
