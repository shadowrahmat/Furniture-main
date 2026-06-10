<template>
    <div>
        <h3 class="bf-step-title">Your Information</h3>
        <p class="bf-step-desc">
            We'll use these details to follow up with your quotation.
            <span v-if="isAuthenticated">We've pre-filled what we have on file &mdash; feel free to update it.</span>
            <span v-else>You don't need an account to submit a request.</span>
        </p>

        <div class="bf-field-grid bf-field-grid-2">
            <div class="bf-field">
                <label class="bf-label">Full Name <span class="bf-required">*</span></label>
                <input v-model="form.name" @blur="touch('name')" type="text" class="input-field" placeholder="Your full name" />
                <p v-if="touched.name && !form.name.trim()" class="bf-error">Name is required.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Email Address <span class="bf-required">*</span></label>
                <input v-model="form.email" @blur="touch('email')" type="email" class="input-field" placeholder="you@example.com" />
                <p v-if="touched.email && !validEmail(form.email)" class="bf-error">Enter a valid email address.</p>
            </div>
        </div>

        <div class="bf-field-grid bf-field-grid-2 bf-mt">
            <div class="bf-field">
                <label class="bf-label">Phone Number <span class="bf-required">*</span></label>
                <input v-model="form.phone" @blur="touch('phone')" type="tel" class="input-field" placeholder="e.g. +880 1XXXXXXXXX" />
                <p v-if="touched.phone && !form.phone.trim()" class="bf-error">Phone number is required.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Address <span class="bf-optional">(optional)</span></label>
                <input v-model="form.customer_address" type="text" class="input-field" placeholder="Street, City" />
            </div>
        </div>

        <label class="bf-checkbox-row bf-mt">
            <input v-model="form.terms_agreed" type="checkbox" class="bf-checkbox" />
            <span>
                I agree to be contacted regarding this request and confirm the information provided is accurate.
                <span class="bf-required">*</span>
            </span>
        </label>
        <p v-if="touched.terms && !form.terms_agreed" class="bf-error">Please confirm to continue.</p>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive } from 'vue'

const props = defineProps({
    form: { type: Object, required: true },
    isAuthenticated: { type: Boolean, default: false },
    currentUser: { type: Object, default: null },
})

const touched = reactive({ name: false, email: false, phone: false, terms: false })
function touch(field) { touched[field] = true }

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
function validEmail(val) {
    return EMAIL_REGEX.test(val || '')
}

onMounted(() => {
    if (!props.isAuthenticated || !props.currentUser) return
    const user = props.currentUser
    if (!props.form.name && user.name) props.form.name = user.name
    if (!props.form.email && user.email) props.form.email = user.email
    if (!props.form.phone && user.phone) props.form.phone = user.phone
    if (!props.form.customer_address && user.address) props.form.customer_address = user.address
})

const isValid = computed(() =>
    !!props.form.name.trim() &&
    validEmail(props.form.email) &&
    !!props.form.phone.trim() &&
    props.form.terms_agreed === true
)
defineExpose({ isValid })
</script>
