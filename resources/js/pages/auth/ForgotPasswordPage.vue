<template>
    <div class="min-h-screen flex items-center justify-center px-4"
        style="background:linear-gradient(135deg,#FAF8F5,#F5EFE6)">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-3xl mb-2" style="color:#1E1B18">Reset Password</h1>
                <p style="color:#6D655F">Enter your email to receive reset instructions</p>
            </div>
            <div class="card-premium p-8">
                <div v-if="sent" class="text-center py-4">
                    <div class="text-5xl mb-4">✉️</div>
                    <h3 class="font-semibold mb-2" style="color:#1E1B18">Email Sent!</h3>
                    <p class="text-sm" style="color:#6D655F">Check your inbox for password reset instructions.</p>
                </div>
                <form v-else @submit.prevent="sendReset" class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email Address</label>
                        <input v-model="email" type="email" class="input-field" required placeholder="your@email.com">
                    </div>
                    <button type="submit" :disabled="loading" class="btn-primary w-full justify-center py-3.5">
                        {{ loading ? 'Sending...' : 'Send Reset Link' }}
                    </button>
                </form>
            </div>
            <p class="text-center mt-6 text-sm" style="color:#6D655F">
                <RouterLink to="/login" class="font-semibold hover:underline" style="color:#8B5E3C">← Back to Login</RouterLink>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const email = ref('')
const loading = ref(false)
const sent = ref(false)

async function sendReset() {
    loading.value = true
    try {
        await axios.post('/auth/forgot-password', { email: email.value })
        sent.value = true
    } catch (e) {
        toast.error(e.response?.data?.message || 'Failed to send reset link')
    } finally {
        loading.value = false
    }
}
</script>
