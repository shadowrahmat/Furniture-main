<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-12 sm:py-16 text-center">
                <p class="label-tag justify-center mb-3" style="color:#E8C47A">Need Help?</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white mb-3">Frequently Asked Questions</h1>
                <p class="text-sm max-w-md mx-auto" style="color:rgba(245,239,230,0.65)">Quick answers to the questions we get asked most. Can't find what you need? Reach out to us anytime.</p>
            </div>
        </section>

        <div class="max-w-3xl mx-auto px-4 py-8 sm:py-12">
            <div class="space-y-3">
                <div v-for="(faq, i) in faqs" :key="i" class="card-premium overflow-hidden anim-fade-up" :style="`animation-delay:${Math.min(i, 6) * 0.05}s`">
                    <button @click="openIdx = openIdx === i ? -1 : i"
                        class="w-full flex items-center gap-4 p-5 text-left">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-heading font-bold text-sm transition-all"
                            :style="openIdx === i ? 'background:linear-gradient(135deg,#8B5E3C,#C9A66B); color:#fff' : 'background:#F5EFE6; color:#C9A66B'">
                            {{ String(i + 1).padStart(2, '0') }}
                        </span>
                        <span class="font-semibold flex-1 pr-2" style="color:#1E1B18">{{ faq.q }}</span>
                        <svg class="w-5 h-5 flex-shrink-0 transition-transform" :class="openIdx === i ? 'rotate-180' : ''"
                            style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div v-show="openIdx === i" class="px-5 pb-5 pl-[4.25rem] text-sm leading-relaxed" style="color:#6D655F">{{ faq.a }}</div>
                </div>
            </div>

            <!-- Still need help -->
            <div class="card-premium p-6 sm:p-8 mt-8 text-center anim-fade-up delay-300">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 grad-walnut">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Still have questions?</h2>
                <p class="mb-5 text-sm" style="color:#6D655F">Our support team is happy to help with anything else you need.</p>
                <RouterLink to="/contact" class="btn-primary">
                    Contact Support
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
const openIdx = ref(-1)
const faqs = [
    { q: 'What is your delivery timeframe?', a: 'Standard delivery takes 2-5 business days. Large furniture items may take 5-7 business days. You will receive tracking updates after dispatch.' },
    { q: 'Do you offer free delivery?', a: 'Yes! Orders above ৳5,000 qualify for free delivery. For orders below this amount, a flat delivery charge of ৳150 applies.' },
    { q: 'Can I return or exchange furniture?', a: 'We offer a 7-day return policy for items that are defective or damaged. Custom furniture orders are non-returnable.' },
    { q: 'Do you provide assembly services?', a: 'Yes, we provide professional assembly for large furniture items. This service is available in Dhaka and major cities.' },
    { q: 'What payment methods do you accept?', a: 'We accept Cash on Delivery (COD), bKash, and Nagad. More payment options will be added soon.' },
    { q: 'Do you offer custom furniture?', a: 'Absolutely! Visit our Custom Furniture page to submit your requirements. Our team will design and build furniture tailored to your needs.' },
    { q: 'Is the furniture quality tested?', a: 'Yes. All our furniture undergoes strict quality checks before delivery. We use premium materials and maintain high manufacturing standards.' },
]
</script>
