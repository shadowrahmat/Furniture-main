<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Settings</h1>
                <p class="adm-page-sub">Configure your store preferences</p>
            </div>
            <button @click="saveSettings" :disabled="saving" class="adm-btn-primary">
                {{ saving ? 'Saving…' : 'Save All Settings' }}
            </button>
        </div>

        <div v-if="loading" class="space-y-4">
            <div v-for="n in 4" :key="n" class="skeleton h-48 rounded-xl"></div>
        </div>

        <div v-else class="space-y-5">
            <!-- General Settings -->
            <div class="adm-form-card">
                <h2 class="adm-form-section-title">General</h2>
                <div class="space-y-4">
                    <div class="adm-form-grid">
                        <div>
                            <label class="adm-form-label">Store Name</label>
                            <input v-model="settings.store_name" type="text" class="input-field">
                        </div>
                        <div>
                            <label class="adm-form-label">Store Email</label>
                            <input v-model="settings.store_email" type="email" class="input-field">
                        </div>
                    </div>
                    <div class="adm-form-grid">
                        <div>
                            <label class="adm-form-label">Store Phone</label>
                            <input v-model="settings.store_phone" type="text" class="input-field">
                        </div>
                        <div>
                            <label class="adm-form-label">Currency</label>
                            <select v-model="settings.currency" class="input-field">
                                <option value="BDT">BDT (৳)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="adm-form-label">Store Address</label>
                        <textarea v-model="settings.store_address" class="input-field" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="adm-form-label">Store Description</label>
                        <textarea v-model="settings.store_description" class="input-field" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Delivery Settings -->
            <div class="adm-form-card">
                <h2 class="adm-form-section-title">Delivery</h2>
                <div class="space-y-4">
                    <div class="adm-form-grid">
                        <div>
                            <label class="adm-form-label">Standard Delivery Charge (৳)</label>
                            <input v-model="settings.delivery_charge" type="number" min="0" class="input-field">
                        </div>
                        <div>
                            <label class="adm-form-label">Free Delivery Above (৳)</label>
                            <input v-model="settings.free_delivery_threshold" type="number" min="0" class="input-field">
                        </div>
                    </div>
                    <div>
                        <label class="adm-form-label">Estimated Delivery Days</label>
                        <input v-model="settings.delivery_days" type="text" class="input-field" placeholder="e.g. 3-5 business days">
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="adm-form-card">
                <h2 class="adm-form-section-title">Social Media</h2>
                <div class="adm-form-grid">
                    <div v-for="social in socialFields" :key="social.key">
                        <label class="adm-form-label">{{ social.label }}</label>
                        <input v-model="settings[social.key]" type="url" class="input-field" :placeholder="social.placeholder">
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="adm-form-card">
                <h2 class="adm-form-section-title">SEO</h2>
                <div class="space-y-4">
                    <div>
                        <label class="adm-form-label">Meta Title</label>
                        <input v-model="settings.meta_title" type="text" class="input-field">
                    </div>
                    <div>
                        <label class="adm-form-label">Meta Description</label>
                        <textarea v-model="settings.meta_description" class="input-field" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="adm-form-label">Meta Keywords</label>
                        <input v-model="settings.meta_keywords" type="text" class="input-field" placeholder="furniture, sofa, bedroom, luxury">
                    </div>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div class="adm-form-card">
                <h2 class="adm-form-section-title">Store Status</h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-4 cursor-pointer p-4 rounded-xl" style="background:#F8FAFC">
                        <input type="checkbox" v-model="settings.maintenance_mode" class="w-5 h-5 rounded flex-shrink-0" style="accent-color:#DC2626">
                        <div>
                            <p class="font-semibold text-sm" style="color:#0F172A">Maintenance Mode</p>
                            <p class="text-sm" style="color:#94A3B8">When enabled, the store will show a maintenance page to visitors</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-4 cursor-pointer p-4 rounded-xl" style="background:#F8FAFC">
                        <input type="checkbox" v-model="settings.reviews_enabled" class="w-5 h-5 rounded flex-shrink-0" style="accent-color:#8B5E3C">
                        <div>
                            <p class="font-semibold text-sm" style="color:#0F172A">Customer Reviews</p>
                            <p class="text-sm" style="color:#94A3B8">Allow customers to leave product reviews</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-4 cursor-pointer p-4 rounded-xl" style="background:#F8FAFC">
                        <input type="checkbox" v-model="settings.guest_checkout" class="w-5 h-5 rounded flex-shrink-0" style="accent-color:#8B5E3C">
                        <div>
                            <p class="font-semibold text-sm" style="color:#0F172A">Guest Checkout</p>
                            <p class="text-sm" style="color:#94A3B8">Allow customers to checkout without creating an account</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const loading = ref(true)
const saving = ref(false)

const settings = reactive({
    store_name: '', store_email: '', store_phone: '', store_address: '', store_description: '',
    currency: 'BDT', delivery_charge: 150, free_delivery_threshold: 5000, delivery_days: '3-5 business days',
    facebook_url: '', instagram_url: '', youtube_url: '', twitter_url: '',
    meta_title: '', meta_description: '', meta_keywords: '',
    maintenance_mode: false, reviews_enabled: true, guest_checkout: true
})

const socialFields = [
    { key: 'facebook_url', label: 'Facebook', placeholder: 'https://facebook.com/yourpage' },
    { key: 'instagram_url', label: 'Instagram', placeholder: 'https://instagram.com/yourpage' },
    { key: 'youtube_url', label: 'YouTube', placeholder: 'https://youtube.com/yourchannel' },
    { key: 'twitter_url', label: 'Twitter / X', placeholder: 'https://twitter.com/yourhandle' },
]

async function saveSettings() {
    saving.value = true
    try {
        await axios.post('/admin/settings', settings)
        toast.success('Settings saved!')
    } catch (e) { toast.error('Failed to save settings') }
    finally { saving.value = false }
}

onMounted(async () => {
    try {
        const { data } = await axios.get('/admin/settings')
        Object.assign(settings, data)
    } catch (e) { toast.error('Failed to load settings') }
    finally { loading.value = false }
})
</script>
