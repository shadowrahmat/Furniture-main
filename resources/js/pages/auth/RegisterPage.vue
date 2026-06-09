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
                <h1 class="font-heading font-bold text-3xl mb-2" style="color:#1E1B18">Create Account</h1>
                <p style="color:#6D655F">Join us to enjoy exclusive offers</p>
            </div>

            <div class="card-premium p-8">
                <form @submit.prevent="handleRegister" class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Full Name</label>
                        <input v-model="form.name" type="text" class="input-field" required placeholder="Your full name">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email Address</label>
                        <input v-model="form.email" type="email" class="input-field" required placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Phone Number</label>
                        <input v-model="form.phone" type="tel" class="input-field" placeholder="+880 1700-000000">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Password</label>
                        <input v-model="form.password" type="password" class="input-field" required placeholder="Min 8 characters">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Confirm Password</label>
                        <input v-model="form.password_confirmation" type="password" class="input-field" required placeholder="Repeat password">
                    </div>

                    <button type="submit" :disabled="authStore.loading"
                        class="btn-primary w-full justify-center py-3.5 text-base disabled:opacity-50">
                        {{ authStore.loading ? 'Creating Account...' : 'Create Account' }}
                    </button>
                </form>
            </div>

            <p class="text-center mt-6 text-sm" style="color:#6D655F">
                Already have an account?
                <RouterLink to="/login" class="font-semibold hover:underline" style="color:#8B5E3C">Sign in</RouterLink>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth.js'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({ name: '', email: '', phone: '', password: '', password_confirmation: '' })

async function handleRegister() {
    await authStore.register(form)
    router.push('/')
}
</script>
