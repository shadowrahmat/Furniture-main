<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Furniture Types</h1>
                <p class="adm-page-sub">{{ furnitureTypes.length }} total furniture types</p>
            </div>
            <button @click="openModal()" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Furniture Type
            </button>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 5" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="furnitureTypes.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No furniture types yet</p>
                <p class="adm-empty-sub">Add furniture types for the Build Your Furniture catalog.</p>
                <button @click="openModal()" class="adm-btn-primary mt-4">Add First Furniture Type</button>
            </div>

            <div v-else class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Furniture Type</th>
                            <th class="hidden md:table-cell">Starting Price</th>
                            <th class="hidden sm:table-cell">Status</th>
                            <th class="hidden lg:table-cell">Order</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ft in furnitureTypes" :key="ft.id">
                            <td>
                                <div class="flex items-center gap-3">
                                    <img v-if="ft.image_url" :src="ft.image_url" class="w-11 h-11 rounded-xl object-cover flex-shrink-0 border" style="border-color:#E8EEF4">
                                    <div v-else class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0 border" style="background:#F8FAFC;border-color:#E8EEF4">🛋️</div>
                                    <div>
                                        <p class="font-semibold text-sm" style="color:#0F172A">{{ ft.name }}</p>
                                        <p class="text-xs mt-0.5" style="color:#94A3B8">{{ ft.slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="font-bold text-sm" style="color:#8B5E3C;font-family:'Poppins',sans-serif">
                                    {{ ft.starting_price ? `৳${Number(ft.starting_price).toLocaleString()}` : '—' }}
                                </span>
                            </td>
                            <td class="hidden sm:table-cell">
                                <span class="adm-status-pill" :class="ft.is_active ? 'pill-active' : 'pill-inactive'">
                                    <span class="pill-dot"></span>
                                    {{ ft.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="text-sm" style="color:#64748B">{{ ft.display_order }}</span>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openModal(ft)" class="adm-act-btn">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <button @click="confirmDelete(ft)" class="adm-act-btn adm-act-danger">
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
                        <h3 class="adm-modal-title mb-5">{{ editTarget ? 'Edit Furniture Type' : 'Add Furniture Type' }}</h3>
                        <form @submit.prevent="saveFurnitureType" class="space-y-4">
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
                                <div class="adm-image-upload" @click="$refs.furnitureImg.click()" style="padding:6px; height:46px; display:flex; align-items:center; justify-content:center; overflow:hidden">
                                    <img v-if="imagePreview" :src="imagePreview" class="h-full rounded-lg object-cover" style="aspect-ratio:1">
                                    <p v-else class="text-xs" style="color:#94A3B8">Click to upload</p>
                                    <input ref="furnitureImg" type="file" accept="image/*" class="hidden" @change="handleImage">
                                </div>
                            </div>
                            <div>
                                <label class="adm-form-label">Description</label>
                                <textarea v-model="form.description" class="input-field" rows="3" placeholder="Shown on the Build Your Furniture step 1"></textarea>
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Starting Price (optional)</label>
                                    <input v-model.number="form.starting_price" type="number" step="0.01" min="0" class="input-field" placeholder="৳">
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
                        <h3 class="adm-modal-title">Delete Furniture Type?</h3>
                        <p class="adm-modal-desc">
                            Delete <strong>{{ deleteTarget.name }}</strong>? This cannot be undone.
                        </p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteFurnitureType" :disabled="deleting" class="adm-btn-danger">
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

const furnitureTypes = ref([])
const loading = ref(true)
const showModal = ref(false)
const editTarget = ref(null)
const deleteTarget = ref(null)
const deleting = ref(false)
const saving = ref(false)
const imagePreview = ref(null)
let imageFile = null

const defaultForm = () => ({
    name: '', slug: '', description: '',
    starting_price: '', display_order: 0, is_active: true,
})

const form = reactive(defaultForm())

function openModal(ft = null) {
    editTarget.value = ft
    imagePreview.value = ft?.image_url || null
    imageFile = null
    if (ft) {
        Object.assign(form, {
            name: ft.name, slug: ft.slug,
            description: ft.description || '',
            starting_price: ft.starting_price ? Number(ft.starting_price) : '',
            display_order: ft.display_order || 0,
            is_active: !!ft.is_active,
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

function buildFormData() {
    const fd = new FormData()
    fd.append('name', form.name)
    fd.append('slug', form.slug)
    fd.append('description', form.description || '')
    fd.append('starting_price', form.starting_price === '' ? '' : form.starting_price)
    fd.append('display_order', form.display_order || 0)
    fd.append('is_active', form.is_active ? '1' : '0')
    if (imageFile) fd.append('image', imageFile)
    return fd
}

async function fetchFurnitureTypes() {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/furniture-types')
        furnitureTypes.value = data
    } catch (e) { toast.error('Failed to load furniture types') }
    finally { loading.value = false }
}

async function saveFurnitureType() {
    saving.value = true
    try {
        const fd = buildFormData()
        if (editTarget.value) {
            fd.append('_method', 'PUT')
            const { data } = await axios.post(`/admin/furniture-types/${editTarget.value.id}`, fd)
            const idx = furnitureTypes.value.findIndex(f => f.id === editTarget.value.id)
            if (idx !== -1) furnitureTypes.value[idx] = data
            toast.success('Furniture type updated!')
        } else {
            const { data } = await axios.post('/admin/furniture-types', fd)
            furnitureTypes.value.push(data)
            toast.success('Furniture type created!')
        }
        showModal.value = false
    } catch (e) {
        const errors = e.response?.data?.errors
        if (errors) toast.error(Object.values(errors)[0][0])
        else toast.error('Failed to save furniture type')
    } finally { saving.value = false }
}

function confirmDelete(ft) { deleteTarget.value = ft }

async function deleteFurnitureType() {
    deleting.value = true
    try {
        await axios.delete(`/admin/furniture-types/${deleteTarget.value.id}`)
        furnitureTypes.value = furnitureTypes.value.filter(f => f.id !== deleteTarget.value.id)
        toast.success('Furniture type deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete furniture type') }
    finally { deleting.value = false }
}

onMounted(fetchFurnitureTypes)
</script>
