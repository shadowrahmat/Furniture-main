<template>
    <div class="min-h-screen flex" style="background:#FAF8F5">

        <!-- Left brand panel — desktop only -->
        <div class="hidden lg:flex flex-col justify-between w-5/12 p-12 relative overflow-hidden"
            style="background:linear-gradient(150deg,#18140F 0%,#3A2618 100%)">
            <!-- Grid pattern -->
            <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
                style="background-image:linear-gradient(rgba(201,166,107,1) 1px,transparent 1px),linear-gradient(90deg,rgba(201,166,107,1) 1px,transparent 1px); background-size:52px 52px"></div>
            <!-- Blur orb -->
            <div class="absolute bottom-1/3 -right-16 w-64 h-64 rounded-full opacity-10 pointer-events-none"
                style="background:radial-gradient(circle,#C9A66B,transparent); filter:blur(64px)"></div>

            <!-- Logo -->
            <RouterLink to="/" class="relative inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center grad-walnut shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <span class="font-heading font-bold text-xl text-white">LuxuryFurnish</span>
            </RouterLink>

            <!-- Headline -->
            <div class="relative">
                <h2 class="font-heading font-bold text-4xl leading-tight text-white mb-4">
                    Forgot Your<br>
                    <span style="background:linear-gradient(135deg,#C9A66B,#EDD8A6); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent">
                        Password?
                    </span>
                </h2>
                <p class="text-sm leading-relaxed max-w-xs" style="color:rgba(240,232,222,0.55)">
                    No worries. Enter the email linked to your account and we'll send you instructions to reset it.
                </p>
            </div>

            <!-- Trust items -->
            <div class="relative space-y-3">
                <div v-for="item in brandItems" :key="item" class="flex items-center gap-3 text-sm" style="color:rgba(240,232,222,0.5)">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:rgba(201,166,107,0.12)">
                        <span style="color:#C9A66B; font-size:12px">✓</span>
                    </div>
                    {{ item }}
                </div>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="flex-1 flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">

                <!-- Mobile logo -->
                <div class="lg:hidden text-center mb-8">
                    <RouterLink to="/" class="inline-flex items-center gap-2.5 justify-center mb-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center grad-walnut">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <span class="font-heading font-bold text-xl" style="color:#1E1B18">LuxuryFurnish</span>
                    </RouterLink>
                </div>

                <!-- Heading -->
                <div class="mb-8">
                    <h1 class="font-heading font-bold text-3xl mb-1.5" style="color:#1E1B18">Reset Password</h1>
                    <p class="text-sm" style="color:#9B9087">Enter your email to receive reset instructions</p>
                </div>

                <!-- Form card -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border" style="border-color:#EDE5D8">
                    <div v-if="sent" class="text-center py-2 anim-scale-in">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                            style="background:linear-gradient(135deg,#3D7A50,#6B9A6B); box-shadow:0 12px 32px rgba(61,122,80,0.25)">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-heading font-bold text-lg mb-2" style="color:#1E1B18">Email Sent!</h3>
                        <p class="text-sm" style="color:#6D655F">Check your inbox for password reset instructions.</p>
                    </div>
                    <form v-else @submit.prevent="sendReset" class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#6D655F">Email Address</label>
                            <input v-model="email" type="email" class="input-field" required autofocus placeholder="your@email.com">
                        </div>
                        <button type="submit" :disabled="loading" class="btn-primary w-full justify-center py-3.5 text-base disabled:opacity-60">
                            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ loading ? 'Sending…' : 'Send Reset Link' }}
                        </button>
                    </form>
                </div>

                <p class="text-center mt-6 text-sm" style="color:#9B9087">
                    <RouterLink to="/login" class="font-semibold inline-flex items-center gap-1.5" style="color:#8B5E3C">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Login
                    </RouterLink>
                </p>
            </div>
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

const brandItems = [
    '500+ premium furniture pieces',
    'Custom orders tailored to your needs',
    'Fast, safe delivery across Bangladesh',
    '24/7 customer support',
]

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
