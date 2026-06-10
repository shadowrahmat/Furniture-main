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
                    Premium Furniture<br>
                    <span style="background:linear-gradient(135deg,#C9A66B,#EDD8A6); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent">
                        For Modern Homes
                    </span>
                </h2>
                <p class="text-sm leading-relaxed max-w-xs" style="color:rgba(240,232,222,0.55)">
                    Sign in to track orders, manage your wishlist, and access exclusive member offers.
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
                    <h1 class="font-heading font-bold text-3xl mb-1.5" style="color:#1E1B18">Welcome back</h1>
                    <p class="text-sm" style="color:#9B9087">Sign in to your account to continue</p>
                </div>

                <!-- Form card -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border" style="border-color:#EDE5D8">
                    <form @submit.prevent="handleLogin" class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#6D655F">Email Address</label>
                            <input v-model="form.email" type="email" class="input-field" required autofocus
                                placeholder="your@email.com">
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider" style="color:#6D655F">Password</label>
                                <RouterLink to="/forgot-password" class="text-xs font-semibold hover:underline" style="color:#8B5E3C">Forgot password?</RouterLink>
                            </div>
                            <div class="relative">
                                <input v-model="form.password" :type="showPass ? 'text' : 'password'"
                                    class="input-field pr-11" required placeholder="Enter your password">
                                <button type="button" @click="showPass = !showPass"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 rounded-lg transition-colors hover:bg-amber-50"
                                    style="color:#9B9087">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            :d="showPass
                                                ? 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21'
                                                : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" v-model="rememberMe" class="w-4 h-4 rounded accent-amber-700">
                            <span class="text-sm" style="color:#6D655F">Keep me signed in</span>
                        </label>

                        <button type="submit" :disabled="authStore.loading"
                            class="btn-primary w-full justify-center py-3.5 text-base disabled:opacity-60">
                            <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ authStore.loading ? 'Signing In…' : 'Sign In' }}
                        </button>
                    </form>
                </div>

                <p class="text-center mt-6 text-sm" style="color:#9B9087">
                    Don't have an account?
                    <RouterLink to="/register" class="font-semibold" style="color:#8B5E3C"> Create one free</RouterLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../../stores/auth.js'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = reactive({ email: '', password: '' })
const showPass = ref(false)
const rememberMe = ref(false)

const brandItems = [
    '500+ premium furniture pieces',
    'Custom orders tailored to your needs',
    'Fast, safe delivery across Bangladesh',
    '24/7 customer support',
]

async function handleLogin() {
    const data = await authStore.login(form)
    if (data) {
        if (data.is_admin) {
            router.push('/admin/dashboard')
        } else {
            router.push(route.query.redirect || '/')
        }
    }
}
</script>
