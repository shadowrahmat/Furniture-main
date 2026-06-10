<template>
    <div class="card-premium bf-success anim-fade-up">
        <div class="bf-success-icon">
            <svg width="36" height="36" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="bf-success-title">Request Submitted Successfully</h3>
        <p class="bf-success-text">
            Thank you! Our team will review your request and get back to you with a quotation soon.
        </p>

        <div class="bf-success-number">
            <span class="bf-success-number-label">Your Request Number</span>
            <span class="bf-success-number-value">{{ result?.request_number }}</span>
        </div>

        <dl class="bf-review-grid bf-success-grid">
            <div v-if="result?.furniture_type"><dt>Furniture Type</dt><dd>{{ result.furniture_type }}</dd></div>
            <div v-if="result?.wood_type"><dt>Wood Type</dt><dd>{{ result.wood_type }}</dd></div>
            <div v-if="result?.quantity"><dt>Quantity</dt><dd>{{ result.quantity }}</dd></div>
            <div v-if="result?.status_label"><dt>Status</dt><dd>{{ result.status_label }}</dd></div>
            <div v-if="submittedDate"><dt>Submitted</dt><dd>{{ submittedDate }}</dd></div>
        </dl>

        <p class="bf-success-note">
            Please save your request number for reference. Our team typically responds within 1-2 business days
            with a detailed quotation based on your requirements.
        </p>

        <p v-if="isAuthenticated" class="bf-success-account-note">
            You can track this request from your account once "My Custom Requests" becomes available.
        </p>

        <div class="bf-success-actions">
            <router-link to="/contact" class="btn-secondary">Contact Support</router-link>
            <router-link to="/" class="btn-primary">Return to Home</router-link>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    result: { type: Object, default: null },
    isAuthenticated: { type: Boolean, default: false },
})

const submittedDate = computed(() => {
    if (!props.result?.submitted_at) return null
    return new Date(props.result.submitted_at).toLocaleString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit',
    })
})
</script>
