<template>
    <div>
        <h3 class="bf-step-title">Select Your Wood Type</h3>
        <p class="bf-step-desc">Choose the wood that will bring your furniture to life.</p>

        <div v-if="loading" class="bf-card-grid">
            <div v-for="i in 6" :key="i" class="skeleton" style="height:220px; border-radius:18px"></div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2L4 12h3l-3 5h6v5h4v-5h6l-3-5h3L12 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="bf-select-body">
                    <h4 class="bf-select-name">{{ item.name }}</h4>
                    <p v-if="item.short_description" class="bf-select-desc">{{ item.short_description }}</p>
                    <div class="bf-wood-meta">
                        <span v-if="item.durability" class="bf-meta-chip">{{ item.durability }}</span>
                        <span v-if="item.color_texture" class="bf-meta-chip">{{ item.color_texture }}</span>
                    </div>
                    <p v-if="adjustmentLabel(item)" class="bf-select-price">{{ adjustmentLabel(item) }}</p>
                </div>
            </button>
        </div>

        <div v-else class="bf-empty">
            No wood types are currently available. Please check back soon.
        </div>

        <!-- Selected wood detail panel -->
        <div v-if="selected" class="card-flat bf-detail-panel anim-fade-up">
            <h4 class="bf-detail-title">{{ selected.name }}</h4>
            <p v-if="selected.description" class="bf-detail-desc">{{ selected.description }}</p>
            <dl class="bf-detail-grid">
                <div class="bf-detail-item">
                    <dt>Durability</dt>
                    <dd>{{ selected.durability || '—' }}</dd>
                </div>
                <div class="bf-detail-item">
                    <dt>Recommended Use</dt>
                    <dd>{{ selected.recommended_use || '—' }}</dd>
                </div>
                <div class="bf-detail-item">
                    <dt>Color / Texture</dt>
                    <dd>{{ selected.color_texture || '—' }}</dd>
                </div>
                <div class="bf-detail-item">
                    <dt>Availability</dt>
                    <dd>{{ availabilityLabel(selected.availability) }}</dd>
                </div>
            </dl>
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

const selected = computed(() => props.items.find(i => i.id === props.modelValue) || null)

const isValid = computed(() => !!props.modelValue)
defineExpose({ isValid })

function adjustmentLabel(item) {
    const value = Number(item.price_adjustment_value)
    if (!value) return null
    return item.price_adjustment_type === 'percentage'
        ? `+${value}% on base price`
        : `+৳${value.toLocaleString('en-IN')} on base price`
}

function availabilityLabel(value) {
    return ({
        in_stock: 'In Stock',
        limited: 'Limited Availability',
        made_to_order: 'Made to Order',
        out_of_stock: 'Out of Stock',
    })[value] || '—'
}
</script>
