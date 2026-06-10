<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-12 sm:py-16 text-center">
                <p class="label-tag justify-center mb-3" style="color:#E8C47A">Legal</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white mb-3">Privacy Policy</h1>
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
        title: 'Information We Collect',
        body: 'We collect information you provide directly including name, email, phone number, shipping address, and payment information when you make purchases.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
    },
    {
        title: 'How We Use Your Information',
        body: 'We use your information to process orders, send confirmations, provide customer support, and improve our services.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
    },
    {
        title: 'Data Security',
        body: 'We implement appropriate security measures to protect your personal information against unauthorized access, alteration, or disclosure.',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
    },
    {
        title: 'Contact Us',
        body: 'For privacy concerns or questions about how your data is handled, contact us at privacy@furniturestore.com',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    },
]
</script>
