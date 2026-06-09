<template>
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="section-title mb-3">Contact Us</h1>
            <p class="section-subtitle mx-auto">We'd love to hear from you. Reach out through any of the channels below.</p>
        </div>
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Contact info -->
            <div class="space-y-4">
                <div v-for="info in contactInfo" :key="info.title" class="card-premium p-5 flex gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0"
                        style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">{{ info.icon }}</div>
                    <div>
                        <p class="font-semibold text-sm mb-1" style="color:#1E1B18">{{ info.title }}</p>
                        <p class="text-sm" style="color:#6D655F">{{ info.value }}</p>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <div class="lg:col-span-2 card-premium p-8">
                <h2 class="font-heading font-bold text-xl mb-6" style="color:#1E1B18">Send a Message</h2>
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
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Phone</label>
                        <input v-model="form.phone" type="tel" class="input-field">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Subject</label>
                        <input v-model="form.subject" type="text" class="input-field">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Message *</label>
                        <textarea v-model="form.message" class="input-field" rows="5" required></textarea>
                    </div>
                    <button type="submit" :disabled="loading" class="btn-primary py-3.5 px-8">
                        {{ loading ? 'Sending...' : 'Send Message' }}
                    </button>
                </form>
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
    { icon: '📍', title: 'Address', value: '123 Furniture Street, Dhaka 1200, Bangladesh' },
    { icon: '📞', title: 'Phone', value: '+880 1700-000000' },
    { icon: '✉️', title: 'Email', value: 'hello@furniturestore.com' },
    { icon: '⏰', title: 'Hours', value: 'Sat-Thu: 10AM - 8PM' },
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
