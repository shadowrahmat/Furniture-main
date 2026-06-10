<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Categories</h1>
                <p class="adm-page-sub">{{ categories.length }} total categories</p>
            </div>
            <button @click="openModal()" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Category
            </button>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 5" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="categories.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No categories yet</p>
                <p class="adm-empty-sub">Create your first category to organize products.</p>
                <button @click="openModal()" class="adm-btn-primary mt-4">Add First Category</button>
            </div>

            <div v-else class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="hidden md:table-cell">Slug</th>
                            <th class="hidden sm:table-cell">Products</th>
                            <th class="hidden lg:table-cell">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cat in categories" :key="cat.id">
                            <td>
                                <div class="flex items-center gap-3">
                                    <img v-if="cat.image_url" :src="cat.image_url" class="w-11 h-11 rounded-xl object-cover flex-shrink-0 border" style="border-color:#E8EEF4">
                                    <div v-else class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0 border" style="background:#F8FAFC;border-color:#E8EEF4">{{ cat.icon || '📂' }}</div>
                                    <div>
                                        <p class="font-semibold text-sm" style="color:#0F172A">{{ cat.name }}</p>
                                        <p v-if="cat.description" class="text-xs mt-0.5 truncate max-w-xs" style="color:#94A3B8">{{ cat.description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="text-xs font-mono" style="color:#94A3B8">{{ cat.slug }}</span>
                            </td>
                            <td class="hidden sm:table-cell">
                                <span class="font-bold text-sm" style="color:#8B5E3C;font-family:'Poppins',sans-serif">{{ cat.products_count || 0 }}</span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="adm-status-pill" :class="cat.is_active ? 'pill-active' : 'pill-inactive'">
                                    <span class="pill-dot"></span>
                                    {{ cat.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openModal(cat)" class="adm-act-btn">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <button @click="confirmDelete(cat)" class="adm-act-btn adm-act-danger">
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
                        <h3 class="adm-modal-title mb-5">{{ editTarget ? 'Edit Category' : 'Add Category' }}</h3>
                        <form @submit.prevent="saveCategory" class="space-y-4">
                            <div>
                                <label class="adm-form-label">Name *</label>
                                <input v-model="catForm.name" type="text" class="input-field" required>
                            </div>
                            <div>
                                <label class="adm-form-label">Slug</label>
                                <input v-model="catForm.slug" type="text" class="input-field" placeholder="auto-generated from name">
                            </div>
                            <div>
                                <label class="adm-form-label">Description</label>
                                <textarea v-model="catForm.description" class="input-field" rows="2"></textarea>
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Icon (emoji)</label>
                                    <input v-model="catForm.icon" type="text" class="input-field" placeholder="🪑">
                                </div>
                                <div>
                                    <label class="adm-form-label">Image</label>
                                    <div class="adm-image-upload" @click="$refs.catImg.click()" style="padding:6px; height:46px; display:flex; align-items:center; justify-content:center; overflow:hidden">
                                        <img v-if="catImagePreview" :src="catImagePreview" class="h-full rounded-lg object-cover" style="aspect-ratio:1">
                                        <p v-else class="text-xs" style="color:#94A3B8">Click to upload</p>
                                        <input ref="catImg" type="file" accept="image/*" class="hidden" @change="handleCatImage">
                                    </div>
                                </div>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" v-model="catForm.is_active" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                                <span class="text-sm" style="color:#334155">Active</span>
                            </label>
                            <div class="adm-modal-actions pt-2">
                                <button type="button" @click="showModal = false" class="adm-btn-ghost">Cancel</button>
                                <button type="submit" :disabled="catSaving" class="adm-btn-primary" style="flex:1; justify-content:center">
                                    {{ catSaving ? 'Saving…' : 'Save' }}
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
                        <h3 class="adm-modal-title">Delete Category?</h3>
                        <p class="adm-modal-desc">
                            Delete <strong>{{ deleteTarget.name }}</strong>? Products in this category will be uncategorized.
                        </p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteCategory" :disabled="deleting" class="adm-btn-danger">
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

const categories = ref([])
const loading = ref(true)
const showModal = ref(false)
const editTarget = ref(null)
const deleteTarget = ref(null)
const deleting = ref(false)
const catSaving = ref(false)
const catImagePreview = ref(null)
let catImageFile = null

const catForm = reactive({ name: '', slug: '', description: '', icon: '', is_active: true })

function openModal(cat = null) {
    editTarget.value = cat
    catImagePreview.value = cat?.image_url || null
    catImageFile = null
    if (cat) Object.assign(catForm, { name: cat.name, slug: cat.slug, description: cat.description || '', icon: cat.icon || '', is_active: cat.is_active })
    else Object.assign(catForm, { name: '', slug: '', description: '', icon: '', is_active: true })
    showModal.value = true
}

function handleCatImage(e) {
    catImageFile = e.target.files[0]
    if (catImageFile) catImagePreview.value = URL.createObjectURL(catImageFile)
}

async function fetchCategories() {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/categories')
        categories.value = data
    } catch (e) { toast.error('Failed to load categories') }
    finally { loading.value = false }
}

async function saveCategory() {
    catSaving.value = true
    try {
        const fd = new FormData()
        Object.entries(catForm).forEach(([k, v]) => fd.append(k, v))
        if (catImageFile) fd.append('image', catImageFile)

        if (editTarget.value) {
            fd.append('_method', 'PUT')
            const { data } = await axios.post(`/admin/categories/${editTarget.value.id}`, fd)
            const idx = categories.value.findIndex(c => c.id === editTarget.value.id)
            if (idx !== -1) categories.value[idx] = data
            toast.success('Category updated!')
        } else {
            const { data } = await axios.post('/admin/categories', fd)
            categories.value.unshift(data)
            toast.success('Category created!')
        }
        showModal.value = false
    } catch (e) {
        const errors = e.response?.data?.errors
        if (errors) toast.error(Object.values(errors)[0][0])
        else toast.error('Failed to save category')
    } finally { catSaving.value = false }
}

function confirmDelete(cat) { deleteTarget.value = cat }

async function deleteCategory() {
    deleting.value = true
    try {
        await axios.delete(`/admin/categories/${deleteTarget.value.id}`)
        categories.value = categories.value.filter(c => c.id !== deleteTarget.value.id)
        toast.success('Category deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete category') }
    finally { deleting.value = false }
}

onMounted(fetchCategories)
</script>
