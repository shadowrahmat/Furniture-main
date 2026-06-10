<template>
    <div>
        <!-- Page Header -->
        <section class="relative overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image:radial-gradient(circle at 20% 20%, #C9A66B 0, transparent 45%), radial-gradient(circle at 80% 80%, #8B5E3C 0, transparent 45%)"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-12 sm:py-16 text-center">
                <p class="label-tag justify-center mb-3" style="color:#E8C47A">Made For You</p>
                <h1 class="font-heading font-bold text-3xl sm:text-4xl text-white mb-3">Custom Furniture Request</h1>
                <p class="text-sm max-w-md mx-auto" style="color:rgba(245,239,230,0.65)">Tell us your requirements and our craftsmen will design and build the perfect piece for your space.</p>
            </div>
        </section>

        <div class="max-w-5xl mx-auto px-4 py-8 sm:py-12">
            <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Process steps -->
                <div class="lg:col-span-1 space-y-4 order-2 lg:order-1">
                    <div v-for="(step, idx) in steps" :key="step.title" class="card-flat p-5 anim-fade-up" :style="`animation-delay:${idx * 0.07}s`">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut text-white font-heading font-bold text-sm">
                                {{ idx + 1 }}
                            </span>
                            <p class="font-semibold text-sm" style="color:#1E1B18">{{ step.title }}</p>
                        </div>
                        <p class="text-xs leading-relaxed pl-12" style="color:#6D655F">{{ step.desc }}</p>
                    </div>

                    <div class="card-flat p-5 anim-fade-up delay-300">
                        <div class="flex items-center gap-2.5 mb-1.5" style="color:#3D7A50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <p class="font-semibold text-sm">Fast Response</p>
                        </div>
                        <p class="text-xs leading-relaxed" style="color:#6D655F">Our design team will reach out within 24 hours to discuss your project.</p>
                    </div>
                </div>

                <!-- Form -->
                <div class="lg:col-span-2 card-premium p-6 sm:p-8 anim-fade-up delay-100 order-1 lg:order-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 grad-walnut">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-xl" style="color:#1E1B18">Project Details</h2>
                    </div>
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
                            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <template v-else>
                                Submit Inquiry
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </template>
                        </button>
                    </form>
                </div>
            </div>
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

const steps = [
    { title: 'Share Your Vision', desc: 'Fill out the form with your dimensions, materials, and style preferences.' },
    { title: 'Design Consultation', desc: 'Our designers review your request and propose a tailored design.' },
    { title: 'Crafted & Delivered', desc: 'Skilled artisans build your piece and deliver it to your door.' },
]

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
