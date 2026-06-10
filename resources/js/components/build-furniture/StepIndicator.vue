<template>
    <div class="bf-indicator anim-fade-up">
        <div class="bf-indicator-track hide-scrollbar">
            <template v-for="(label, idx) in steps" :key="label">
                <div class="bf-indicator-item" :class="{
                    'bf-indicator-active': idx + 1 === current,
                    'bf-indicator-done': idx + 1 < current,
                }">
                    <span class="bf-indicator-circle">
                        <svg v-if="idx + 1 < current" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span v-else>{{ idx + 1 }}</span>
                    </span>
                    <span class="bf-indicator-label">{{ label }}</span>
                </div>
                <div v-if="idx < steps.length - 1" class="bf-indicator-line" :class="{ 'bf-indicator-line-done': idx + 1 < current }"></div>
            </template>
        </div>
        <p class="bf-indicator-progress">
            Step {{ current }} of {{ steps.length }}: <strong>{{ steps[current - 1] }}</strong>
        </p>
    </div>
</template>

<script setup>
defineProps({
    steps: { type: Array, required: true },
    current: { type: Number, required: true },
})
</script>

<style scoped>
.bf-indicator { margin-bottom: 24px; }
.bf-indicator-track {
    display: flex;
    align-items: center;
    overflow-x: auto;
    padding-bottom: 4px;
}
.bf-indicator-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    min-width: 64px;
}
.bf-indicator-circle {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: #FFFFFF; border: 2px solid #E5DDD4;
    color: #B0A49A; font-size: 13px; font-weight: 700;
    transition: all 0.25s ease; flex-shrink: 0;
}
.bf-indicator-active .bf-indicator-circle {
    background: linear-gradient(135deg, #5C2E0A 0%, #8B5E3C 50%, #C9A055 100%);
    border-color: transparent; color: #fff;
    box-shadow: 0 4px 14px rgba(92,46,10,0.3);
}
.bf-indicator-done .bf-indicator-circle {
    background: #3D7A50; border-color: transparent; color: #fff;
}
.bf-indicator-label {
    font-size: 10.5px; font-weight: 600; color: #B0A49A;
    text-align: center; white-space: nowrap; letter-spacing: 0.02em;
    max-width: 80px; overflow: hidden; text-overflow: ellipsis;
}
.bf-indicator-active .bf-indicator-label { color: #8B5E3C; }
.bf-indicator-done .bf-indicator-label { color: #3D7A50; }
.bf-indicator-line {
    height: 2px; flex: 1; min-width: 16px;
    background: #E5DDD4; margin: 0 4px 22px;
    transition: background 0.25s ease;
}
.bf-indicator-line-done { background: #3D7A50; }
.bf-indicator-progress {
    font-size: 12px; color: #7A6E66; margin-top: 10px;
}
.bf-indicator-progress strong { color: #1E1B18; }
</style>
