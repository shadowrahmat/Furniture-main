<template>
    <div>
        <h3 class="bf-step-title">Budget &amp; Delivery Preference</h3>
        <p class="bf-step-desc">
            Share an estimated budget and delivery details so we can prepare an accurate quotation.
            This is not a final price &mdash; our team will review your request and follow up with you.
        </p>

        <div class="bf-field-grid bf-field-grid-2">
            <div class="bf-field">
                <label class="bf-label">Estimated Budget (Min) <span class="bf-optional">(optional)</span></label>
                <div class="bf-input-prefix">
                    <span class="bf-prefix">৳</span>
                    <input v-model="form.budget_min" type="number" min="0" step="1" class="input-field bf-input-with-prefix" placeholder="e.g. 10000" />
                </div>
            </div>
            <div class="bf-field">
                <label class="bf-label">Estimated Budget (Max) <span class="bf-optional">(optional)</span></label>
                <div class="bf-input-prefix">
                    <span class="bf-prefix">৳</span>
                    <input v-model="form.budget_max" type="number" min="0" step="1" class="input-field bf-input-with-prefix" placeholder="e.g. 25000" />
                </div>
                <p v-if="budgetRangeInvalid" class="bf-error">Maximum budget must be greater than or equal to minimum budget.</p>
            </div>
        </div>

        <div class="bf-field-grid bf-field-grid-2 bf-mt">
            <div class="bf-field">
                <label class="bf-label">Required Delivery Date <span class="bf-optional">(optional)</span></label>
                <input v-model="form.delivery_date" type="date" :min="todayStr" class="input-field" />
                <p v-if="deliveryDateInvalid" class="bf-error">Delivery date cannot be in the past.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Delivery Preference <span class="bf-optional">(optional)</span></label>
                <select v-model="form.delivery_preference" class="input-field">
                    <option value="">Select an option</option>
                    <option value="Home Delivery">Home Delivery</option>
                    <option value="Store Pickup">Store Pickup</option>
                    <option value="Flexible">Flexible</option>
                </select>
            </div>
        </div>

        <div class="bf-field bf-mt">
            <label class="bf-label">Delivery Address <span class="bf-optional">(optional)</span></label>
            <textarea v-model="form.delivery_address" rows="3" class="input-field bf-textarea" placeholder="Where should we deliver this furniture?"></textarea>
        </div>

        <div class="bf-field bf-mt">
            <label class="bf-label">Additional Instructions <span class="bf-optional">(optional)</span></label>
            <textarea v-model="form.additional_notes" rows="4" maxlength="2000" class="input-field bf-textarea" placeholder="Any other details we should know about your request..."></textarea>
            <p class="bf-hint-right">{{ (form.additional_notes || '').length }} / 2000</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    form: { type: Object, required: true },
})

const todayStr = new Date().toISOString().slice(0, 10)

const budgetRangeInvalid = computed(() => {
    const min = props.form.budget_min
    const max = props.form.budget_max
    if (min === '' || max === '' || min === null || max === null) return false
    return Number(max) < Number(min)
})

const deliveryDateInvalid = computed(() => {
    if (!props.form.delivery_date) return false
    return props.form.delivery_date < todayStr
})

const isValid = computed(() => !budgetRangeInvalid.value && !deliveryDateInvalid.value)
defineExpose({ isValid })
</script>
