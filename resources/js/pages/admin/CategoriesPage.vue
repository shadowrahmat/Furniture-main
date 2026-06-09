<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Categories</h1>
            <button @click="openModal()" class="btn-primary text-sm">+ Add Category</button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color:#F0E8DE">
            <div v-if="loading" class="p-8 text-center" style="color:#6D655F">Loading...</div>
            <div v-else-if="categories.length === 0" class="p-12 text-center">
                <div class="text-5xl mb-3">📂</div>
                <p style="color:#6D655F">No categories yet</p>
            </div>
            <table v-else class="w-full">
                <thead class="text-xs font-semibold uppercase tracking-wider" style="background:#FAF8F5;color:#6D655F">
                    <tr>
                        <th class="px-5 py-3 text-left">Category</th>
                        <th class="px-5 py-3 text-left hidden md:table-cell">Slug</th>
                        <th class="px-5 py-3 text-left hidden sm:table-cell">Products</th>
                        <th class="px-5 py-3 text-left hidden lg:table-cell">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8DE">
                    <tr v-for="cat in categories" :key="cat.id" class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img v-if="cat.image_url" :src="cat.image_url" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                <div v-else class="w-10 h-10 rounded-lg flex items-center justify-center text-xl flex-shrink-0" style="background:#F5EFE6">{{ cat.icon || '📂' }}</div>
                                <div>
                                    <p class="font-medium text-sm" style="color:#1E1B18">{{ cat.name }}</p>
                                    <p v-if="cat.description" class="text-xs truncate max-w-xs" style="color:#6D655F">{{ cat.description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 hidden md:table-cell text-sm font-mono" style="color:#6D655F">{{ cat.slug }}</td>
                        <td class="px-5 py-3 hidden sm:table-cell text-sm font-semibold" style="color:#1E1B18">{{ cat.products_count || 0 }}</td>
                        <td class="px-5 py-3 hidden lg:table-cell">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                :style="cat.is_active ? 'background:#D1FAE5;color:#065F46' : 'background:#FEE2E2;color:#991B1B'">
                                {{ cat.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openModal(cat)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#F5EFE6;color:#8B5E3C">Edit</button>
                                <button @click="confirmDelete(cat)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#FEE2E2;color:#991B1B">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Category Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
                <h3 class="font-heading font-bold text-xl mb-5" style="color:#1E1B18">{{ editTarget ? 'Edit Category' : 'Add Category' }}</h3>
                <form @submit.prevent="saveCategory" class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Name *</label>
                        <input v-model="catForm.name" type="text" class="input-field" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Slug</label>
                        <input v-model="catForm.slug" type="text" class="input-field" placeholder="auto-generated from name">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Description</label>
                        <textarea v-model="catForm.description" class="input-field" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Icon (emoji)</label>
                        <input v-model="catForm.icon" type="text" class="input-field w-24" placeholder="🪑">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Image</label>
                        <input type="file" accept="image/*" @change="handleCatImage" class="text-sm">
                        <img v-if="catImagePreview" :src="catImagePreview" class="w-20 h-20 object-cover rounded-lg mt-2">
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" v-model="catForm.is_active" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                        <span class="text-sm" style="color:#1E1B18">Active</span>
                    </label>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal = false" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                        <button type="submit" :disabled="catSaving" class="flex-1 btn-primary text-sm py-2">{{ catSaving ? 'Saving...' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
                <h3 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Delete Category?</h3>
                <p class="mb-6 text-sm" style="color:#6D655F">Delete <strong>{{ deleteTarget.name }}</strong>? Products in this category will be uncategorized.</p>
                <div class="flex gap-3">
                    <button @click="deleteTarget = null" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                    <button @click="deleteCategory" :disabled="deleting" class="flex-1 text-sm py-2 rounded-lg font-semibold text-white" style="background:#DC2626">
                        {{ deleting ? 'Deleting...' : 'Delete' }}
                    </button>
                </div>
            </div>
        </div>
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
