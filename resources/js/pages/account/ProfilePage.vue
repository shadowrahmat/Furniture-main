<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Profile</h2>
        <div class="card-premium p-6 mb-6">
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
                <button type="submit" :disabled="loading" class="btn-primary">{{ loading ? 'Saving...' : 'Save Changes' }}</button>
            </form>
        </div>
        <div class="card-premium p-6">
            <h3 class="font-semibold mb-4" style="color:#1E1B18">Change Password</h3>
            <form @submit.prevent="changePassword" class="space-y-4">
                <input v-model="pass.current_password" type="password" class="input-field" placeholder="Current password">
                <input v-model="pass.password" type="password" class="input-field" placeholder="New password">
                <input v-model="pass.password_confirmation" type="password" class="input-field" placeholder="Confirm new password">
                <button type="submit" :disabled="passLoading" class="btn-outline">{{ passLoading ? 'Updating...' : 'Update Password' }}</button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
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
