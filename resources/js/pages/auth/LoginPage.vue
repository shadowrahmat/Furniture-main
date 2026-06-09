<template>
    <div class="min-h-screen flex items-center justify-center px-4 py-12"
        style="background:linear-gradient(135deg,#FAF8F5,#F5EFE6)">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <RouterLink to="/" class="inline-flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                        style="background:linear-gradient(135deg,#8B5E3C,#C9A66B)">
                        <span class="text-white font-bold text-xl">F</span>
                    </div>
                    <span class="font-heading font-bold text-2xl" style="color:#1E1B18">Furniture Store</span>
                </RouterLink>
                <h1 class="font-heading font-bold text-3xl mb-2" style="color:#1E1B18">Welcome Back</h1>
                <p style="color:#6D655F">Sign in to your account to continue</p>
            </div>

            <div class="card-premium p-8">
                <form @submit.prevent="handleLogin" class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email Address</label>
                        <input v-model="form.email" type="email" class="input-field" required autofocus
                            placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Password</label>
                        <div class="relative">
                            <input v-model="form.password" :type="showPass ? 'text' : 'password'"
                                class="input-field pr-10" required placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        :d="showPass ? 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="accent-amber-700">
                            <span class="text-sm" style="color:#6D655F">Remember me</span>
                        </label>
                        <RouterLink to="/forgot-password" class="text-sm font-medium hover:underline" style="color:#8B5E3C">Forgot password?</RouterLink>
                    </div>

                    <button type="submit" :disabled="authStore.loading"
                        class="btn-primary w-full justify-center py-3.5 text-base disabled:opacity-50">
                        <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        {{ authStore.loading ? 'Signing In...' : 'Sign In' }}
                    </button>
                </form>
            </div>

            <p class="text-center mt-6 text-sm" style="color:#6D655F">
                Don't have an account?
                <RouterLink to="/register" class="font-semibold hover:underline" style="color:#8B5E3C">Create account</RouterLink>
            </p>
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
