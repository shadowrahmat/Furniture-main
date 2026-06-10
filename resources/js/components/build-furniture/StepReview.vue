<template>
    <div>
        <h3 class="bf-step-title">Review Your Request</h3>
        <p class="bf-step-desc">Please check everything below before submitting. You can edit any section.</p>

        <div class="bf-review-list">
            <!-- Furniture Type -->
            <div class="bf-review-section">
                <div class="bf-review-head">
                    <h4>Furniture Type</h4>
                    <button type="button" class="bf-edit-btn" @click="$emit('edit', 1)">Edit</button>
                </div>
                <div class="bf-review-body bf-review-flex">
                    <img v-if="selectedFurnitureType?.image_url" :src="selectedFurnitureType.image_url" alt="" class="bf-review-thumb" />
                    <div>
                        <p class="bf-review-strong">{{ selectedFurnitureType?.name || '—' }}</p>
                        <p v-if="selectedFurnitureType?.description" class="bf-review-muted">{{ selectedFurnitureType.description }}</p>
                    </div>
                </div>
            </div>

            <!-- Wood Type -->
            <div class="bf-review-section">
                <div class="bf-review-head">
                    <h4>Wood Type</h4>
                    <button type="button" class="bf-edit-btn" @click="$emit('edit', 2)">Edit</button>
                </div>
                <div class="bf-review-body bf-review-flex">
                    <img v-if="selectedWoodType?.image_url" :src="selectedWoodType.image_url" alt="" class="bf-review-thumb" />
                    <div>
                        <p class="bf-review-strong">{{ selectedWoodType?.name || '—' }}</p>
                        <p v-if="selectedWoodType?.recommended_use" class="bf-review-muted">Recommended for: {{ selectedWoodType.recommended_use }}</p>
                    </div>
                </div>
            </div>

            <!-- Measurements -->
            <div class="bf-review-section">
                <div class="bf-review-head">
                    <h4>Measurements &amp; Preferences</h4>
                    <button type="button" class="bf-edit-btn" @click="$emit('edit', 3)">Edit</button>
                </div>
                <div class="bf-review-body">
                    <dl class="bf-review-grid">
                        <div><dt>Dimensions</dt><dd>{{ form.length }} x {{ form.width }} x {{ form.height }} {{ unitLabel }}</dd></div>
                        <div><dt>Quantity</dt><dd>{{ form.quantity }}</dd></div>
                        <div v-if="form.design_style"><dt>Design Style</dt><dd>{{ form.design_style }}</dd></div>
                        <div v-if="form.finish"><dt>Finish</dt><dd>{{ form.finish }}</dd></div>
                        <div v-if="form.color_preference"><dt>Color</dt><dd>{{ form.color_preference }}</dd></div>
                        <div v-if="form.upholstery_fabric"><dt>Upholstery / Fabric</dt><dd>{{ form.upholstery_fabric }}</dd></div>
                    </dl>
                </div>
            </div>

            <!-- Files -->
            <div class="bf-review-section">
                <div class="bf-review-head">
                    <h4>Reference Files</h4>
                    <button type="button" class="bf-edit-btn" @click="$emit('edit', 4)">Edit</button>
                </div>
                <div class="bf-review-body">
                    <ul v-if="form.files.length" class="bf-review-file-list">
                        <li v-for="(file, idx) in form.files" :key="idx">{{ file.name }}</li>
                    </ul>
                    <p v-else class="bf-review-muted">No files attached.</p>
                </div>
            </div>

            <!-- Budget & Delivery -->
            <div class="bf-review-section">
                <div class="bf-review-head">
                    <h4>Budget &amp; Delivery</h4>
                    <button type="button" class="bf-edit-btn" @click="$emit('edit', 5)">Edit</button>
                </div>
                <div class="bf-review-body">
                    <dl class="bf-review-grid">
                        <div v-if="form.budget_min || form.budget_max"><dt>Estimated Budget</dt><dd>{{ budgetRange }}</dd></div>
                        <div v-if="form.delivery_date"><dt>Delivery Date</dt><dd>{{ form.delivery_date }}</dd></div>
                        <div v-if="form.delivery_preference"><dt>Delivery Preference</dt><dd>{{ form.delivery_preference }}</dd></div>
                        <div v-if="form.delivery_address"><dt>Delivery Address</dt><dd>{{ form.delivery_address }}</dd></div>
                    </dl>
                    <p v-if="form.additional_notes" class="bf-review-notes"><strong>Notes:</strong> {{ form.additional_notes }}</p>
                    <p v-if="!hasBudgetDelivery" class="bf-review-muted">No additional details provided.</p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bf-review-section">
                <div class="bf-review-head">
                    <h4>Your Information</h4>
                    <button type="button" class="bf-edit-btn" @click="$emit('edit', 6)">Edit</button>
                </div>
                <div class="bf-review-body">
                    <dl class="bf-review-grid">
                        <div><dt>Name</dt><dd>{{ form.name }}</dd></div>
                        <div><dt>Email</dt><dd>{{ form.email }}</dd></div>
                        <div><dt>Phone</dt><dd>{{ form.phone }}</dd></div>
                        <div v-if="form.customer_address"><dt>Address</dt><dd>{{ form.customer_address }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>

        <label class="bf-checkbox-row bf-mt">
            <input v-model="form.terms_agreed" type="checkbox" class="bf-checkbox" />
            <span>I confirm the details above are accurate and I'd like to submit this request for a quotation. <span class="bf-required">*</span></span>
        </label>

        <button type="button" class="btn-primary bf-submit-btn" :disabled="submitting || !form.terms_agreed" @click="$emit('submit')">
            <svg v-if="submitting" class="bf-spinner" width="18" height="18" viewBox="0 0 24 24" fill="none">
                <circle class="bf-spinner-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/>
                <path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
            {{ submitting ? 'Submitting...' : 'Submit Request' }}
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    form: { type: Object, required: true },
    selectedFurnitureType: { type: Object, default: null },
    selectedWoodType: { type: Object, default: null },
    submitting: { type: Boolean, default: false },
})
defineEmits(['edit', 'submit'])

const unitLabels = { cm: 'cm', mm: 'mm', inch: 'in', feet: 'ft' }
const unitLabel = computed(() => unitLabels[props.form.dimension_unit] || props.form.dimension_unit)

const hasBudgetDelivery = computed(() =>
    !!(props.form.budget_min || props.form.budget_max || props.form.delivery_date ||
       props.form.delivery_preference || props.form.delivery_address || props.form.additional_notes)
)

const budgetRange = computed(() => {
    const min = props.form.budget_min
    const max = props.form.budget_max
    const fmt = (n) => `৳${Number(n).toLocaleString('en-IN')}`
    if (min && max) return `${fmt(min)} - ${fmt(max)}`
    if (min) return `From ${fmt(min)}`
    if (max) return `Up to ${fmt(max)}`
    return '—'
})
</script>
