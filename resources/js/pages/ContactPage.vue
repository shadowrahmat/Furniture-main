<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-12 sm:py-16 text-center">
                <p class="label-tag justify-center mb-3" style="color:#E8C47A">Get In Touch</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white mb-3">Contact Us</h1>
                <p class="text-sm max-w-md mx-auto" style="color:rgba(245,239,230,0.65)">We'd love to hear from you. Reach out through any of the channels below and our team will respond shortly.</p>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-12">
            <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Contact info -->
                <div class="space-y-4">
                    <div v-for="(info, idx) in contactInfo" :key="info.title"
                        class="card-premium p-5 flex gap-4 anim-fade-up" :style="`animation-delay:${idx * 0.07}s`">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 grad-walnut">
                            <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="info.icon"></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-sm mb-1" style="color:#1E1B18">{{ info.title }}</p>
                            <p class="text-sm" style="color:#6D655F">{{ info.value }}</p>
                        </div>
                    </div>

                    <!-- Social -->
                    <div class="card-premium p-5 anim-fade-up delay-300">
                        <p class="font-semibold text-sm mb-3" style="color:#1E1B18">Follow Us</p>
                        <div class="flex gap-2">
                            <a v-for="social in socials" :key="social.label" href="#"
                                class="btn-icon" :title="social.label">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="social.icon"></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="lg:col-span-2 card-premium p-6 sm:p-8 anim-fade-up delay-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-xl" style="color:#1E1B18">Send a Message</h2>
                    </div>
                    <form @submit.prevent="sendMessage" class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Name *</label>
                                <input v-model="form.name" type="text" class="input-field" required>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email *</label>
                                <input v-model="form.email" type="email" class="input-field" required>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Phone</label>
                                <input v-model="form.phone" type="tel" class="input-field">
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Subject</label>
                                <input v-model="form.subject" type="text" class="input-field">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Message *</label>
                            <textarea v-model="form.message" class="input-field" rows="5" required></textarea>
                        </div>
                        <button type="submit" :disabled="loading" class="btn-primary py-3.5 px-8">
                            <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <template v-else>
                                {{ 'Send Message' }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </template>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const loading = ref(false)
const form = reactive({ name: '', email: '', phone: '', subject: '', message: '' })

const contactInfo = [
    { icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>', title: 'Address', value: '123 Furniture Street, Dhaka 1200, Bangladesh' },
    { icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>', title: 'Phone', value: '+880 1700-000000' },
    { icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', title: 'Email', value: 'hello@furniturestore.com' },
    { icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>', title: 'Hours', value: 'Sat-Thu: 10AM - 8PM' },
]

const socials = [
    { label: 'Facebook', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>' },
    { label: 'Instagram', icon: '<rect x="3" y="3" width="18" height="18" rx="5" stroke-width="2"/><circle cx="12" cy="12" r="3.5" stroke-width="2"/><circle cx="17.5" cy="6.5" r="0.8" fill="currentColor"/>' },
    { label: 'WhatsApp', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21l1.65-3.8a9 9 0 113.4 3.2L3 21z"/>' },
    { label: 'YouTube', icon: '<rect x="3" y="6" width="18" height="12" rx="3" stroke-width="2"/><path d="M10 9.5l5 2.5-5 2.5v-5z" fill="currentColor"/>' },
]

async function sendMessage() {
    loading.value = true
    try {
        await axios.post('/contact', form)
        toast.success('Message sent! We will reply soon.')
        Object.keys(form).forEach(k => form[k] = '')
    } catch (e) { toast.error('Failed to send message') }
    finally { loading.value = false }
}
</script>
