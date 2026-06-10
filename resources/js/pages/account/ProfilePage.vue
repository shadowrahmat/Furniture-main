<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Profile</h2>

        <div class="card-premium p-6 mb-6 anim-fade-up">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="font-heading font-bold text-lg" style="color:#1E1B18">Personal Information</h3>
            </div>
            <form @submit.prevent="saveProfile" class="space-y-5">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Full Name</label>
                    <input v-model="form.name" type="text" class="input-field" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email</label>
                    <input :value="user?.email" type="email" class="input-field opacity-60" readonly>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Phone</label>
                    <input v-model="form.phone" type="tel" class="input-field">
                </div>
                <button type="submit" :disabled="loading" class="btn-primary">
                    <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    {{ loading ? 'Saving...' : 'Save Changes' }}
                </button>
            </form>
        </div>

        <div class="card-premium p-6 anim-fade-up delay-100">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="font-heading font-bold text-lg" style="color:#1E1B18">Change Password</h3>
            </div>
            <form @submit.prevent="changePassword" class="space-y-4">
                <input v-model="pass.current_password" type="password" class="input-field" placeholder="Current password">
                <input v-model="pass.password" type="password" class="input-field" placeholder="New password">
                <input v-model="pass.password_confirmation" type="password" class="input-field" placeholder="Confirm new password">
                <button type="submit" :disabled="passLoading" class="btn-outline">
                    {{ passLoading ? 'Updating...' : 'Update Password' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '../../stores/auth.js'

const authStore = useAuthStore()
const user = computed(() => authStore.currentUser)
const loading = ref(false)
const passLoading = ref(false)

const form = reactive({ name: user.value?.name || '', phone: user.value?.phone || '' })
const pass = reactive({ current_password: '', password: '', password_confirmation: '' })

async function saveProfile() {
    loading.value = true
    try {
        const { data } = await axios.post('/profile', form)
        authStore.user = data.user
        localStorage.setItem('user', JSON.stringify(data.user))
        toast.success('Profile updated!')
    } catch (e) { toast.error('Failed to update profile') }
    finally { loading.value = false }
}

async function changePassword() {
    passLoading.value = true
    try {
        await axios.post('/profile/change-password', pass)
        toast.success('Password updated!')
        pass.current_password = pass.password = pass.password_confirmation = ''
    } catch (e) { toast.error(e.response?.data?.message || 'Failed') }
    finally { passLoading.value = false }
}
</script>
