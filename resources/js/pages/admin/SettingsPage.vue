<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Settings</h1>
            <button @click="saveSettings" :disabled="saving" class="btn-primary text-sm">{{ saving ? 'Saving...' : 'Save All Settings' }}</button>
        </div>

        <div v-if="loading" class="space-y-4">
            <div v-for="n in 4" :key="n" class="skeleton h-48 rounded-xl"></div>
        </div>

        <div v-else class="space-y-6">
            <!-- General Settings -->
            <div class="bg-white rounded-xl p-6 shadow-sm border" style="border-color:#F0E8DE">
                <h2 class="font-semibold text-lg mb-5" style="color:#1E1B18">General</h2>
                <div class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Store Name</label>
                            <input v-model="settings.store_name" type="text" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Store Email</label>
                            <input v-model="settings.store_email" type="email" class="input-field">
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Store Phone</label>
                            <input v-model="settings.store_phone" type="text" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Currency</label>
                            <select v-model="settings.currency" class="input-field">
                                <option value="BDT">BDT (৳)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Store Address</label>
                        <textarea v-model="settings.store_address" class="input-field" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Store Description</label>
                        <textarea v-model="settings.store_description" class="input-field" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Delivery Settings -->
            <div class="bg-white rounded-xl p-6 shadow-sm border" style="border-color:#F0E8DE">
                <h2 class="font-semibold text-lg mb-5" style="color:#1E1B18">Delivery</h2>
                <div class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Standard Delivery Charge (৳)</label>
                            <input v-model="settings.delivery_charge" type="number" min="0" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Free Delivery Above (৳)</label>
                            <input v-model="settings.free_delivery_threshold" type="number" min="0" class="input-field">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Estimated Delivery Days</label>
                        <input v-model="settings.delivery_days" type="text" class="input-field" placeholder="e.g. 3-5 business days">
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white rounded-xl p-6 shadow-sm border" style="border-color:#F0E8DE">
                <h2 class="font-semibold text-lg mb-5" style="color:#1E1B18">Social Media</h2>
                <div class="space-y-4">
                    <div v-for="social in socialFields" :key="social.key">
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">{{ social.label }}</label>
                        <input v-model="settings[social.key]" type="url" class="input-field" :placeholder="social.placeholder">
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="bg-white rounded-xl p-6 shadow-sm border" style="border-color:#F0E8DE">
                <h2 class="font-semibold text-lg mb-5" style="color:#1E1B18">SEO</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Meta Title</label>
                        <input v-model="settings.meta_title" type="text" class="input-field">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Meta Description</label>
                        <textarea v-model="settings.meta_description" class="input-field" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Meta Keywords</label>
                        <input v-model="settings.meta_keywords" type="text" class="input-field" placeholder="furniture, sofa, bedroom, luxury">
                    </div>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div class="bg-white rounded-xl p-6 shadow-sm border" style="border-color:#F0E8DE">
                <h2 class="font-semibold text-lg mb-5" style="color:#1E1B18">Store Status</h2>
                <div class="space-y-4">
                    <label class="flex items-center gap-4 cursor-pointer p-4 rounded-xl" style="background:#FAF8F5">
                        <input type="checkbox" v-model="settings.maintenance_mode" class="w-5 h-5 rounded" style="accent-color:#D64545">
                        <div>
                            <p class="font-semibold" style="color:#1E1B18">Maintenance Mode</p>
                            <p class="text-sm" style="color:#6D655F">When enabled, the store will show a maintenance page to visitors</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-4 cursor-pointer p-4 rounded-xl" style="background:#FAF8F5">
                        <input type="checkbox" v-model="settings.reviews_enabled" class="w-5 h-5 rounded" style="accent-color:#8B5E3C">
                        <div>
                            <p class="font-semibold" style="color:#1E1B18">Customer Reviews</p>
                            <p class="text-sm" style="color:#6D655F">Allow customers to leave product reviews</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-4 cursor-pointer p-4 rounded-xl" style="background:#FAF8F5">
                        <input type="checkbox" v-model="settings.guest_checkout" class="w-5 h-5 rounded" style="accent-color:#8B5E3C">
                        <div>
                            <p class="font-semibold" style="color:#1E1B18">Guest Checkout</p>
                            <p class="text-sm" style="color:#6D655F">Allow customers to checkout without creating an account</p>
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
