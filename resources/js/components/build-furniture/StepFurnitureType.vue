<template>
    <div>
        <h3 class="bf-step-title">Choose Your Furniture Type</h3>
        <p class="bf-step-desc">Select the kind of furniture you'd like our craftsmen to build for you.</p>

        <div v-if="loading" class="bf-card-grid">
            <div v-for="i in 6" :key="i" class="skeleton" style="height:200px; border-radius:18px"></div>
        </div>

        <div v-else-if="items.length" class="bf-card-grid">
            <button v-for="item in items" :key="item.id" type="button"
                class="bf-select-card" :class="{ 'bf-select-card-active': modelValue === item.id }"
                @click="$emit('update:modelValue', item.id)">
                <span v-if="modelValue === item.id" class="bf-selected-badge">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <div class="bf-select-img-wrap">
                    <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="bf-select-img" loading="lazy">
                    <div v-else class="bf-select-img-placeholder">
                        <svg width="32" height="32" fill="none" stroke="#C9B99A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 11V5a2 2 0 012-2h10a2 2 0 012 2v6M5 11h14M5 11v8m14-8v8M5 19h14M9 19v2m6-2v2"/>
                        </svg>
                    </div>
                </div>
                <div class="bf-select-body">
                    <h4 class="bf-select-name">{{ item.name }}</h4>
                    <p v-if="item.description" class="bf-select-desc">{{ item.description }}</p>
                    <p v-if="item.starting_price" class="bf-select-price">From ৳{{ fmt(item.starting_price) }}</p>
                </div>
            </button>
        </div>

        <div v-else class="bf-empty">
            No furniture types are currently available. Please check back soon.
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: { type: [Number, null], default: null },
    items: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
})
defineEmits(['update:modelValue'])

const isValid = computed(() => !!props.modelValue)
defineExpose({ isValid })

function fmt(n) {
    return Number(n).toLocaleString('en-IN', { maximumFractionDigits: 0 })
}
</script>
