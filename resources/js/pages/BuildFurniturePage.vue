<template>
    <div>
        <!-- Hero -->
        <section class="bf-hero">
            <div class="bf-hero-bg"></div>
            <div class="bf-hero-content anim-fade-up">
                <span class="label-tag bf-label-light">Build Your Furniture</span>
                <h1 class="bf-hero-title">Design It Your Way</h1>
                <p class="bf-hero-text">
                    Choose your furniture type, pick a wood you love, and share your measurements
                    and style preferences &mdash; our craftsmen will turn your vision into a
                    made-to-measure piece for your home.
                </p>
                <div class="bf-trust-row">
                    <div class="bf-trust-item">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                        </svg>
                        Custom Craftsmanship
                    </div>
                    <div class="bf-trust-item">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2L4 12h3l-3 5h6v5h4v-5h6l-3-5h3L12 2z"/>
                        </svg>
                        Premium Wood Selection
                    </div>
                    <div class="bf-trust-item">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 3L3 21m0 0h6m-6 0v-6m4-4l2 2m3-7l2 2m3-7l2 2"/>
                        </svg>
                        Made-to-Measure
                    </div>
                </div>
                <button type="button" class="btn-primary bf-hero-cta" @click="scrollToForm">
                    Start Your Request
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </section>

        <!-- How It Works -->
        <section class="max-w-7xl mx-auto px-4 py-12 sm:py-16">
            <div class="text-center max-w-2xl mx-auto">
                <p class="label-tag justify-center mb-3">How It Works</p>
                <h2 class="section-title">From Idea to Finished Furniture</h2>
                <p class="section-subtitle mx-auto mt-3">A simple four-step process to bring your custom piece to life.</p>
            </div>
            <div class="bf-how-grid">
                <div v-for="(step, idx) in howItWorks" :key="step.title"
                    class="card-flat bf-how-card anim-fade-up" :style="`animation-delay:${idx * 0.07}s`">
                    <div class="bf-how-number grad-walnut">{{ idx + 1 }}</div>
                    <h3 class="bf-how-title">{{ step.title }}</h3>
                    <p class="bf-how-desc">{{ step.desc }}</p>
                </div>
            </div>
        </section>

        <!-- Request Form -->
        <section ref="formSection" class="max-w-4xl mx-auto px-4 pb-16 sm:pb-24">
            <template v-if="!submitted">
                <div class="text-center max-w-2xl mx-auto mb-8">
                    <p class="label-tag justify-center mb-3">Your Request</p>
                    <h2 class="section-title">Tell Us What You Have In Mind</h2>
                </div>

                <StepIndicator :steps="stepLabels" :current="currentStep" />

                <div class="card-premium bf-form-card anim-fade-up">
                    <StepFurnitureType v-if="currentStep === 1" ref="stepRef"
                        v-model="form.furniture_type_id" :items="furnitureTypes" :loading="loadingFurniture" />

                    <StepWoodType v-else-if="currentStep === 2" ref="stepRef"
                        v-model="form.wood_type_id" :items="woodTypes" :loading="loadingWood" />

                    <StepMeasurements v-else-if="currentStep === 3" ref="stepRef" :form="form" />

                    <StepFiles v-else-if="currentStep === 4" ref="stepRef" v-model="form.files" />

                    <StepBudgetDelivery v-else-if="currentStep === 5" ref="stepRef" :form="form" />

                    <StepCustomerInfo v-else-if="currentStep === 6" ref="stepRef" :form="form"
                        :is-authenticated="authStore.isAuthenticated" :current-user="authStore.currentUser" />

                    <StepReview v-else-if="currentStep === 7" ref="stepRef" :form="form"
                        :selected-furniture-type="selectedFurnitureType" :selected-wood-type="selectedWoodType"
                        :submitting="submitting" @edit="goToStep" @submit="submitRequest" />

                    <div v-if="currentStep < 7" class="bf-form-nav">
                        <button v-if="currentStep > 1" type="button" class="btn-outline" @click="goBack">
                            Back
                        </button>
                        <div class="bf-spacer"></div>
                        <button type="button" class="btn-primary" @click="goNext">
                            Continue
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>

            <SuccessScreen v-else :result="result" :is-authenticated="authStore.isAuthenticated" />
        </section>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '../stores/auth'
import '../../css/build-furniture.css'

import StepIndicator from '../components/build-furniture/StepIndicator.vue'
import StepFurnitureType from '../components/build-furniture/StepFurnitureType.vue'
import StepWoodType from '../components/build-furniture/StepWoodType.vue'
import StepMeasurements from '../components/build-furniture/StepMeasurements.vue'
import StepFiles from '../components/build-furniture/StepFiles.vue'
import StepBudgetDelivery from '../components/build-furniture/StepBudgetDelivery.vue'
import StepCustomerInfo from '../components/build-furniture/StepCustomerInfo.vue'
import StepReview from '../components/build-furniture/StepReview.vue'
import SuccessScreen from '../components/build-furniture/SuccessScreen.vue'

const authStore = useAuthStore()

const stepLabels = ['Furniture', 'Wood', 'Measurements', 'Files', 'Budget & Delivery', 'Your Info', 'Review']
const currentStep = ref(1)
const stepRef = ref(null)
const formSection = ref(null)

const form = reactive({
    furniture_type_id: null,
    wood_type_id: null,
    length: '',
    width: '',
    height: '',
    dimension_unit: 'cm',
    quantity: 1,
    design_style: '',
    finish: '',
    color_preference: '',
    upholstery_fabric: '',
    files: [],
    budget_min: '',
    budget_max: '',
    delivery_date: '',
    delivery_address: '',
    delivery_preference: '',
    additional_notes: '',
    name: '',
    email: '',
    phone: '',
    customer_address: '',
    terms_agreed: false,
})

const furnitureTypes = ref([])
const woodTypes = ref([])
const loadingFurniture = ref(true)
const loadingWood = ref(true)

const submitting = ref(false)
const submitted = ref(false)
const result = ref(null)

const selectedFurnitureType = computed(() => furnitureTypes.value.find(f => f.id === form.furniture_type_id) || null)
const selectedWoodType = computed(() => woodTypes.value.find(w => w.id === form.wood_type_id) || null)

const howItWorks = [
    { title: 'Choose Furniture', desc: 'Pick the type of furniture you want our craftsmen to build for your space.' },
    { title: 'Select Wood', desc: 'Browse our curated wood types and choose the one that fits your style and budget.' },
    { title: 'Share Requirements', desc: 'Tell us your measurements, finish, budget, and delivery preferences.' },
    { title: 'Receive a Quotation', desc: 'Our team reviews your request and follows up with a detailed quotation.' },
]

onMounted(async () => {
    try {
        const { data } = await axios.get('/furniture-types')
        furnitureTypes.value = data
    } catch (e) {
        toast.error('Could not load furniture types. Please refresh the page.')
    } finally {
        loadingFurniture.value = false
    }

    try {
        const { data } = await axios.get('/wood-types')
        woodTypes.value = data
    } catch (e) {
        toast.error('Could not load wood types. Please refresh the page.')
    } finally {
        loadingWood.value = false
    }
})

function scrollToForm() {
    formSection.value?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

function goNext() {
    if (stepRef.value && stepRef.value.isValid === false) {
        toast.error('Please complete this step before continuing.')
        return
    }
    if (currentStep.value < 7) currentStep.value++
    scrollToForm()
}

function goBack() {
    if (currentStep.value > 1) currentStep.value--
    scrollToForm()
}

function goToStep(step) {
    currentStep.value = step
    scrollToForm()
}

function appendIfPresent(formData, key, value) {
    if (value !== null && value !== undefined && value !== '') {
        formData.append(key, value)
    }
}

const FIELD_STEP_MAP = {
    furniture_type_id: 1,
    wood_type_id: 2,
    length: 3, width: 3, height: 3, dimension_unit: 3, quantity: 3,
    finish: 3, color_preference: 3, design_style: 3, upholstery_fabric: 3,
    files: 4,
    budget_min: 5, budget_max: 5, delivery_date: 5, delivery_address: 5,
    delivery_preference: 5, additional_notes: 5,
    name: 6, email: 6, phone: 6, customer_address: 6, terms_agreed: 6,
}

async function submitRequest() {
    if (submitting.value) return
    submitting.value = true

    try {
        const formData = new FormData()
        formData.append('furniture_type_id', form.furniture_type_id)
        formData.append('wood_type_id', form.wood_type_id)
        formData.append('length', form.length)
        formData.append('width', form.width)
        formData.append('height', form.height)
        formData.append('dimension_unit', form.dimension_unit)
        formData.append('quantity', form.quantity)
        appendIfPresent(formData, 'finish', form.finish)
        appendIfPresent(formData, 'color_preference', form.color_preference)
        appendIfPresent(formData, 'design_style', form.design_style)
        appendIfPresent(formData, 'upholstery_fabric', form.upholstery_fabric)
        appendIfPresent(formData, 'budget_min', form.budget_min)
        appendIfPresent(formData, 'budget_max', form.budget_max)
        appendIfPresent(formData, 'delivery_date', form.delivery_date)
        appendIfPresent(formData, 'delivery_address', form.delivery_address)
        appendIfPresent(formData, 'delivery_preference', form.delivery_preference)
        appendIfPresent(formData, 'additional_notes', form.additional_notes)
        formData.append('name', form.name)
        formData.append('email', form.email)
        formData.append('phone', form.phone)
        appendIfPresent(formData, 'customer_address', form.customer_address)
        formData.append('terms_agreed', form.terms_agreed ? '1' : '0')
        form.files.forEach((file) => formData.append('files[]', file))

        const { data } = await axios.post('/custom-furniture-requests', formData, {
            headers: { 'Content-Type': undefined },
        })

        result.value = data.data
        submitted.value = true
        window.scrollTo({ top: 0, behavior: 'smooth' })
    } catch (e) {
        const status = e.response?.status

        if (status === 422) {
            const errors = e.response?.data?.errors
            if (errors) {
                const firstField = Object.keys(errors)[0]
                toast.error(errors[firstField]?.[0] || 'Please check the form for errors.')
                const step = FIELD_STEP_MAP[firstField.split('.')[0]]
                if (step) currentStep.value = step
            } else {
                toast.error(e.response?.data?.message || 'Please check your selections and try again.')
            }
        } else if (status === 429) {
            toast.error('Too many requests. Please wait a moment and try again.')
        } else {
            toast.error('We could not submit your request right now. Please try again in a moment.')
        }
    } finally {
        submitting.value = false
    }
}
</script>
