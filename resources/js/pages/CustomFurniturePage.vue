<template>
    <div class="max-w-3xl mx-auto px-4 py-12">
        <div class="text-center mb-10">
            <h1 class="section-title mb-3">Custom Furniture Request</h1>
            <p class="section-subtitle mx-auto">Tell us your requirements and our team will design the perfect furniture for you.</p>
        </div>
        <div class="card-premium p-8">
            <form @submit.prevent="submitInquiry" class="space-y-5">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Your Name *</label>
                        <input v-model="form.name" type="text" class="input-field" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Phone *</label>
                        <input v-model="form.phone" type="tel" class="input-field" required>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Email *</label>
                    <input v-model="form.email" type="email" class="input-field" required>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Furniture Type *</label>
                        <select v-model="form.furniture_type" class="input-field" required>
                            <option value="">Select type</option>
                            <option v-for="type in furnitureTypes" :key="type" :value="type">{{ type }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Space Type</label>
                        <select v-model="form.space_type" class="input-field">
                            <option value="">Select space</option>
                            <option v-for="space in spaceTypes" :key="space" :value="space">{{ space }}</option>
                        </select>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Dimensions</label>
                        <input v-model="form.dimensions" type="text" class="input-field" placeholder="L x W x H (cm)">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Material Preference</label>
                        <input v-model="form.material_preference" type="text" class="input-field" placeholder="e.g. Solid Wood">
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Budget Min (৳)</label>
                        <input v-model="form.budget_min" type="number" class="input-field" placeholder="5000">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Budget Max (৳)</label>
                        <input v-model="form.budget_max" type="number" class="input-field" placeholder="50000">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Description *</label>
                    <textarea v-model="form.description" class="input-field" rows="5" required
                        placeholder="Describe your furniture requirements in detail..."></textarea>
                </div>
                <button type="submit" :disabled="loading" class="btn-primary w-full justify-center py-4 text-base">
                    {{ loading ? 'Submitting...' : 'Submit Inquiry' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const router = useRouter()
const loading = ref(false)
const form = reactive({ name:'', email:'', phone:'', furniture_type:'', space_type:'', dimensions:'', material_preference:'', budget_min:'', budget_max:'', description:'' })

const furnitureTypes = ['Sofa', 'Bed', 'Dining Table', 'Wardrobe', 'TV Unit', 'Office Desk', 'Bookshelf', 'Cabinet', 'Other']
const spaceTypes = ['Living Room', 'Bedroom', 'Dining Room', 'Office', 'Restaurant', 'Hotel', 'Other']

async function submitInquiry() {
    loading.value = true
    try {
        await axios.post('/custom-inquiry', form)
        toast.success('Inquiry submitted! Our team will contact you within 24 hours.')
        router.push('/')
    } catch (e) { toast.error(e.response?.data?.message || 'Failed to submit') }
    finally { loading.value = false }
}
</script>
