<template>
    <div class="max-w-3xl mx-auto px-4 py-12">
        <div class="text-center mb-10">
            <h1 class="section-title mb-3">Frequently Asked Questions</h1>
        </div>
        <div class="space-y-3">
            <div v-for="(faq, i) in faqs" :key="i" class="card-premium overflow-hidden">
                <button @click="openIdx = openIdx === i ? -1 : i"
                    class="w-full flex items-center justify-between p-5 text-left">
                    <span class="font-semibold pr-4" style="color:#1E1B18">{{ faq.q }}</span>
                    <svg class="w-5 h-5 flex-shrink-0 transition-transform" :class="openIdx === i ? 'rotate-180' : ''"
                        style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div v-if="openIdx === i" class="px-5 pb-5 text-sm leading-relaxed" style="color:#6D655F">{{ faq.a }}</div>
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
