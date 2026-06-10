<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Wood Types</h1>
                <p class="adm-page-sub">{{ woodTypes.length }} total wood types</p>
            </div>
            <button @click="openModal()" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Wood Type
            </button>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 5" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="woodTypes.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No wood types yet</p>
                <p class="adm-empty-sub">Add wood types for the Build Your Furniture catalog.</p>
                <button @click="openModal()" class="adm-btn-primary mt-4">Add First Wood Type</button>
            </div>

            <div v-else class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Wood Type</th>
                            <th class="hidden md:table-cell">Price Adjustment</th>
                            <th class="hidden lg:table-cell">Availability</th>
                            <th class="hidden sm:table-cell">Status</th>
                            <th class="hidden lg:table-cell">Order</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="wt in woodTypes" :key="wt.id">
                            <td>
                                <div class="flex items-center gap-3">
                                    <img v-if="wt.image_url" :src="wt.image_url" class="w-11 h-11 rounded-xl object-cover flex-shrink-0 border" style="border-color:#E8EEF4">
                                    <div v-else class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0 border" style="background:#F8FAFC;border-color:#E8EEF4">🪵</div>
                                    <div>
                                        <p class="font-semibold text-sm" style="color:#0F172A">{{ wt.name }}</p>
                                        <p class="text-xs mt-0.5" style="color:#94A3B8">{{ wt.slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="font-bold text-sm" style="color:#8B5E3C;font-family:'Poppins',sans-serif">
                                    {{ wt.price_adjustment_type === 'percentage'
                                        ? `+${Number(wt.price_adjustment_value)}%`
                                        : `+৳${Number(wt.price_adjustment_value).toLocaleString()}` }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="text-xs font-semibold capitalize" :style="availabilityColor(wt.availability)">
                                    {{ wt.availability.replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="hidden sm:table-cell">
                                <span class="adm-status-pill" :class="wt.is_active ? 'pill-active' : 'pill-inactive'">
                                    <span class="pill-dot"></span>
                                    {{ wt.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="text-sm" style="color:#64748B">{{ wt.display_order }}</span>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openModal(wt)" class="adm-act-btn">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <button @click="confirmDelete(wt)" class="adm-act-btn adm-act-danger">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add / Edit Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="showModal" class="adm-modal-overlay" @click.self="showModal = false">
                    <div class="adm-modal-form">
                        <h3 class="adm-modal-title mb-5">{{ editTarget ? 'Edit Wood Type' : 'Add Wood Type' }}</h3>
                        <form @submit.prevent="saveWoodType" class="space-y-4">
                            <div>
                                <label class="adm-form-label">Name *</label>
                                <input v-model="form.name" type="text" class="input-field" required>
                            </div>
                            <div>
                                <label class="adm-form-label">Slug</label>
                                <input v-model="form.slug" type="text" class="input-field" placeholder="auto-generated from name">
                            </div>
                            <div>
                                <label class="adm-form-label">Image</label>
                                <div class="adm-image-upload" @click="$refs.woodImg.click()" style="padding:6px; height:46px; display:flex; align-items:center; justify-content:center; overflow:hidden">
                                    <img v-if="imagePreview" :src="imagePreview" class="h-full rounded-lg object-cover" style="aspect-ratio:1">
                                    <p v-else class="text-xs" style="color:#94A3B8">Click to upload</p>
                                    <input ref="woodImg" type="file" accept="image/*" class="hidden" @change="handleImage">
                                </div>
                            </div>
                            <div>
                                <label class="adm-form-label">Short Description</label>
                                <textarea v-model="form.short_description" class="input-field" rows="2" placeholder="One-line summary shown on selection cards"></textarea>
                            </div>
                            <div>
                                <label class="adm-form-label">Full Description</label>
                                <textarea v-model="form.description" class="input-field" rows="3" placeholder="Detailed description"></textarea>
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Durability</label>
                                    <input v-model="form.durability" type="text" class="input-field" placeholder="e.g. High - 25+ years">
                                </div>
                                <div>
                                    <label class="adm-form-label">Color / Texture</label>
                                    <input v-model="form.color_texture" type="text" class="input-field" placeholder="e.g. Golden brown, straight grain">
                                </div>
                            </div>
                            <div>
                                <label class="adm-form-label">Recommended Use</label>
                                <input v-model="form.recommended_use" type="text" class="input-field" placeholder="e.g. Beds, wardrobes, dining tables">
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Price Adjustment Type</label>
                                    <select v-model="form.price_adjustment_type" class="input-field">
                                        <option value="fixed">Fixed Amount (৳)</option>
                                        <option value="percentage">Percentage (%)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="adm-form-label">Price Adjustment Value</label>
                                    <input v-model.number="form.price_adjustment_value" type="number" step="0.01" min="0" class="input-field">
                                </div>
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Availability</label>
                                    <select v-model="form.availability" class="input-field">
                                        <option value="in_stock">In Stock</option>
                                        <option value="limited">Limited</option>
                                        <option value="out_of_stock">Out of Stock</option>
                                        <option value="made_to_order">Made to Order</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="adm-form-label">Display Order</label>
                                    <input v-model.number="form.display_order" type="number" min="0" class="input-field">
                                </div>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" v-model="form.is_active" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                                <span class="text-sm" style="color:#334155">Active</span>
                            </label>
                            <div class="adm-modal-actions pt-2">
                                <button type="button" @click="showModal = false" class="adm-btn-ghost">Cancel</button>
                                <button type="submit" :disabled="saving" class="adm-btn-primary" style="flex:1; justify-content:center">
                                    {{ saving ? 'Saving…' : 'Save' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Delete Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="deleteTarget" class="adm-modal-overlay" @click.self="deleteTarget = null">
                    <div class="adm-modal">
                        <div class="adm-modal-icon-wrap">
                            <svg width="24" height="24" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <h3 class="adm-modal-title">Delete Wood Type?</h3>
                        <p class="adm-modal-desc">
                            Delete <strong>{{ deleteTarget.name }}</strong>? This cannot be undone.
                        </p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteWoodType" :disabled="deleting" class="adm-btn-danger">
                                {{ deleting ? 'Deleting…' : 'Delete' }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const woodTypes = ref([])
const loading = ref(true)
const showModal = ref(false)
const editTarget = ref(null)
const deleteTarget = ref(null)
const deleting = ref(false)
const saving = ref(false)
const imagePreview = ref(null)
let imageFile = null

const defaultForm = () => ({
    name: '', slug: '', short_description: '', description: '',
    durability: '', recommended_use: '', color_texture: '',
    price_adjustment_type: 'fixed', price_adjustment_value: 0,
    availability: 'in_stock', display_order: 0, is_active: true,
})

const form = reactive(defaultForm())

function openModal(wt = null) {
    editTarget.value = wt
    imagePreview.value = wt?.image_url || null
    imageFile = null
    if (wt) {
        Object.assign(form, {
            name: wt.name, slug: wt.slug,
            short_description: wt.short_description || '',
            description: wt.description || '',
            durability: wt.durability || '',
            recommended_use: wt.recommended_use || '',
            color_texture: wt.color_texture || '',
            price_adjustment_type: wt.price_adjustment_type || 'fixed',
            price_adjustment_value: Number(wt.price_adjustment_value) || 0,
            availability: wt.availability || 'in_stock',
            display_order: wt.display_order || 0,
            is_active: !!wt.is_active,
        })
    } else {
        Object.assign(form, defaultForm())
    }
    showModal.value = true
}

function handleImage(e) {
    imageFile = e.target.files[0]
    if (imageFile) imagePreview.value = URL.createObjectURL(imageFile)
}

function availabilityColor(availability) {
    switch (availability) {
        case 'in_stock': return 'color:#059669'
        case 'limited': return 'color:#D97706'
        case 'out_of_stock': return 'color:#DC2626'
        case 'made_to_order': return 'color:#2563EB'
        default: return 'color:#64748B'
    }
}

function buildFormData() {
    const fd = new FormData()
    fd.append('name', form.name)
    fd.append('slug', form.slug)
    fd.append('short_description', form.short_description || '')
    fd.append('description', form.description || '')
    fd.append('durability', form.durability || '')
    fd.append('recommended_use', form.recommended_use || '')
    fd.append('color_texture', form.color_texture || '')
    fd.append('price_adjustment_type', form.price_adjustment_type)
    fd.append('price_adjustment_value', form.price_adjustment_value || 0)
    fd.append('availability', form.availability)
    fd.append('display_order', form.display_order || 0)
    fd.append('is_active', form.is_active ? '1' : '0')
    if (imageFile) fd.append('image', imageFile)
    return fd
}

async function fetchWoodTypes() {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/wood-types')
        woodTypes.value = data
    } catch (e) { toast.error('Failed to load wood types') }
    finally { loading.value = false }
}

async function saveWoodType() {
    saving.value = true
    try {
        const fd = buildFormData()
        if (editTarget.value) {
            fd.append('_method', 'PUT')
            const { data } = await axios.post(`/admin/wood-types/${editTarget.value.id}`, fd)
            const idx = woodTypes.value.findIndex(w => w.id === editTarget.value.id)
            if (idx !== -1) woodTypes.value[idx] = data
            toast.success('Wood type updated!')
        } else {
            const { data } = await axios.post('/admin/wood-types', fd)
            woodTypes.value.push(data)
            toast.success('Wood type created!')
        }
        showModal.value = false
    } catch (e) {
        const errors = e.response?.data?.errors
        if (errors) toast.error(Object.values(errors)[0][0])
        else toast.error('Failed to save wood type')
    } finally { saving.value = false }
}

function confirmDelete(wt) { deleteTarget.value = wt }

async function deleteWoodType() {
    deleting.value = true
    try {
        await axios.delete(`/admin/wood-types/${deleteTarget.value.id}`)
        woodTypes.value = woodTypes.value.filter(w => w.id !== deleteTarget.value.id)
        toast.success('Wood type deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete wood type') }
    finally { deleting.value = false }
}

onMounted(fetchWoodTypes)
</script>
