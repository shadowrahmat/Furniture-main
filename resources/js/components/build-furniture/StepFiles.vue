<template>
    <div>
        <h3 class="bf-step-title">Reference Files</h3>
        <p class="bf-step-desc">
            Upload images or a PDF that show the style or inspiration for your piece.
            Optional &mdash; up to 5 files, 5MB each (JPG, PNG, WEBP, or PDF).
        </p>

        <label class="bf-upload-zone" :class="{ 'bf-upload-zone-disabled': modelValue.length >= 5 }">
            <input type="file" multiple :disabled="modelValue.length >= 5"
                accept="image/jpeg,image/png,image/webp,application/pdf"
                @change="onFileChange" class="bf-upload-input" />
            <svg width="30" height="30" fill="none" stroke="#8B5E3C" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 8.25L12 3.75 7.5 8.25M12 3.75v12"/>
            </svg>
            <p class="bf-upload-text">Click to upload or drag files here</p>
            <p class="bf-upload-hint">{{ modelValue.length }} / 5 files selected</p>
        </label>

        <div v-if="modelValue.length" class="bf-file-grid">
            <div v-for="(file, idx) in modelValue" :key="idx" class="bf-file-card">
                <button type="button" class="bf-file-remove" @click="removeFile(idx)" aria-label="Remove file">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="bf-file-preview">
                    <img v-if="isImage(file)" :src="previewUrl(file)" alt="" class="bf-file-thumb" />
                    <div v-else class="bf-file-pdf">
                        <svg width="28" height="28" fill="none" stroke="#8B5E3C" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M9 8h1M5 21h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v15a2 2 0 002 2z"/>
                        </svg>
                        <span>PDF</span>
                    </div>
                </div>
                <p class="bf-file-name" :title="file.name">{{ file.name }}</p>
                <p class="bf-file-size">{{ formatSize(file.size) }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount } from 'vue'
import { toast } from 'vue3-toastify'

const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf']
const MAX_SIZE = 5 * 1024 * 1024
const MAX_FILES = 5

const props = defineProps({
    modelValue: { type: Array, default: () => [] },
})
const emit = defineEmits(['update:modelValue'])

const previewCache = new Map()

function onFileChange(e) {
    const incoming = Array.from(e.target.files || [])
    e.target.value = ''

    const next = [...props.modelValue]
    for (const file of incoming) {
        if (next.length >= MAX_FILES) {
            toast.error('You can upload a maximum of 5 files.')
            break
        }
        if (!ALLOWED_TYPES.includes(file.type)) {
            toast.error(`"${file.name}" is not a supported file type.`)
            continue
        }
        if (file.size > MAX_SIZE) {
            toast.error(`"${file.name}" exceeds the 5MB size limit.`)
            continue
        }
        next.push(file)
    }
    emit('update:modelValue', next)
}

function removeFile(idx) {
    const next = [...props.modelValue]
    const [removed] = next.splice(idx, 1)
    if (removed && previewCache.has(removed)) {
        URL.revokeObjectURL(previewCache.get(removed))
        previewCache.delete(removed)
    }
    emit('update:modelValue', next)
}

function isImage(file) {
    return file.type.startsWith('image/')
}

function previewUrl(file) {
    if (!previewCache.has(file)) {
        previewCache.set(file, URL.createObjectURL(file))
    }
    return previewCache.get(file)
}

function formatSize(bytes) {
    if (bytes < 1024) return `${bytes} B`
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
    return `${(bytes / 1024 / 1024).toFixed(1)} MB`
}

onBeforeUnmount(() => {
    previewCache.forEach((url) => URL.revokeObjectURL(url))
    previewCache.clear()
})

const isValid = computed(() => true)
defineExpose({ isValid })
</script>
