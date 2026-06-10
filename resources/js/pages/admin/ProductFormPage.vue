<template>
    <div>
        <RouterLink to="/admin/products" class="adm-back-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Products
        </RouterLink>

        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">{{ isEdit ? 'Edit Product' : 'Add Product' }}</h1>
                <p class="adm-page-sub">{{ isEdit ? 'Update product details and inventory' : 'Create a new product listing' }}</p>
            </div>
        </div>

        <form @submit.prevent="save" class="grid lg:grid-cols-3 gap-5">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-5">
                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">Basic Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="adm-form-label">Product Name *</label>
                            <input v-model="form.name" type="text" class="input-field" required placeholder="e.g. Luxe Velvet Sofa">
                        </div>
                        <div class="adm-form-grid">
                            <div>
                                <label class="adm-form-label">SKU</label>
                                <input v-model="form.sku" type="text" class="input-field" placeholder="e.g. SOF-001">
                            </div>
                            <div>
                                <label class="adm-form-label">Category *</label>
                                <select v-model="form.category_id" class="input-field" required>
                                    <option value="">Select category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="adm-form-label">Short Description</label>
                            <textarea v-model="form.short_description" class="input-field" rows="2" placeholder="Brief product summary..."></textarea>
                        </div>
                        <div>
                            <label class="adm-form-label">Full Description</label>
                            <textarea v-model="form.description" class="input-field" rows="5" placeholder="Detailed product description..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">Pricing & Inventory</h2>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="adm-form-label">Price (৳) *</label>
                            <input v-model="form.price" type="number" step="0.01" min="0" class="input-field" required>
                        </div>
                        <div>
                            <label class="adm-form-label">Compare Price (৳)</label>
                            <input v-model="form.compare_price" type="number" step="0.01" min="0" class="input-field">
                        </div>
                        <div>
                            <label class="adm-form-label">Stock Quantity *</label>
                            <input v-model="form.stock" type="number" min="0" class="input-field" required>
                        </div>
                    </div>
                </div>

                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">Product Details</h2>
                    <div class="space-y-4">
                        <div class="adm-form-grid">
                            <div>
                                <label class="adm-form-label">Material</label>
                                <input v-model="form.material" type="text" class="input-field" placeholder="e.g. Solid Teak Wood">
                            </div>
                            <div>
                                <label class="adm-form-label">Dimensions</label>
                                <input v-model="form.dimensions" type="text" class="input-field" placeholder='e.g. 220×90×85 cm'>
                            </div>
                        </div>
                        <div>
                            <label class="adm-form-label">Available Colors (comma-separated)</label>
                            <input v-model="colorsInput" type="text" class="input-field" placeholder="Walnut Brown, Ivory White, Charcoal">
                        </div>
                        <div>
                            <label class="adm-form-label">Tags (comma-separated)</label>
                            <input v-model="tagsInput" type="text" class="input-field" placeholder="sofa, living room, luxury">
                        </div>
                        <div>
                            <label class="adm-form-label">Weight (kg)</label>
                            <input v-model="form.weight" type="number" step="0.1" min="0" class="input-field w-40">
                        </div>
                    </div>
                </div>

                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">SEO</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="adm-form-label">Meta Title</label>
                            <input v-model="form.meta_title" type="text" class="input-field">
                        </div>
                        <div>
                            <label class="adm-form-label">Meta Description</label>
                            <textarea v-model="form.meta_description" class="input-field" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-5">
                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">Status</h2>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" v-model="form.is_active" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                            <span class="text-sm" style="color:#334155">Active (visible in store)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" v-model="form.is_featured" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                            <span class="text-sm" style="color:#334155">Featured product</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" v-model="form.is_new" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                            <span class="text-sm" style="color:#334155">New arrival</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" v-model="form.is_best_seller" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                            <span class="text-sm" style="color:#334155">Best seller</span>
                        </label>
                    </div>
                </div>

                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">Featured Image</h2>
                    <div class="adm-image-upload" @click="$refs.imageInput.click()">
                        <img v-if="imagePreview" :src="imagePreview" class="w-full h-40 object-cover rounded-lg">
                        <div v-else class="py-6">
                            <svg class="mx-auto mb-2" width="28" height="28" fill="none" stroke="#C9A66B" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm" style="color:#94A3B8">Click to upload image</p>
                        </div>
                        <input ref="imageInput" type="file" accept="image/*" class="hidden" @change="handleImage">
                    </div>
                    <p class="text-xs mt-2" style="color:#94A3B8">Recommended: 800×800px, max 2MB</p>
                </div>

                <div class="adm-form-card">
                    <h2 class="adm-form-section-title">Gallery Images</h2>
                    <div class="adm-image-upload" @click="$refs.galleryInput.click()">
                        <div class="grid grid-cols-3 gap-2 mb-2" v-if="galleryPreviews.length">
                            <img v-for="(p, i) in galleryPreviews" :key="i" :src="p" class="aspect-square object-cover rounded-lg">
                        </div>
                        <div v-else class="py-4">
                            <p class="text-sm" style="color:#94A3B8">Click to add gallery images</p>
                        </div>
                        <input ref="galleryInput" type="file" accept="image/*" multiple class="hidden" @change="handleGallery">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="$router.back()" class="adm-btn-ghost">Cancel</button>
                    <button type="submit" :disabled="saving" class="adm-btn-primary" style="flex:1; justify-content:center; padding:11px 18px">
                        {{ saving ? 'Saving…' : (isEdit ? 'Update Product' : 'Create Product') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const route = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)
const categories = ref([])
const saving = ref(false)
const imagePreview = ref(null)
const galleryPreviews = ref([])
let imageFile = null
let galleryFiles = []
const colorsInput = ref('')
const tagsInput = ref('')

const form = reactive({
    name: '', sku: '', category_id: '', short_description: '', description: '',
    price: '', compare_price: '', stock: 0, material: '', dimensions: '',
    weight: '', meta_title: '', meta_description: '',
    is_active: true, is_featured: false, is_new: false, is_best_seller: false
})

function handleImage(e) {
    imageFile = e.target.files[0]
    if (imageFile) imagePreview.value = URL.createObjectURL(imageFile)
}

function handleGallery(e) {
    galleryFiles = Array.from(e.target.files)
    galleryPreviews.value = galleryFiles.map(f => URL.createObjectURL(f))
}

async function save() {
    saving.value = true
    try {
        const fd = new FormData()
        Object.entries(form).forEach(([k, v]) => fd.append(k, v))
        fd.append('colors', JSON.stringify(colorsInput.value.split(',').map(s => s.trim()).filter(Boolean)))
        fd.append('tags', JSON.stringify(tagsInput.value.split(',').map(s => s.trim()).filter(Boolean)))
        if (imageFile) fd.append('featured_image', imageFile)
        galleryFiles.forEach(f => fd.append('gallery[]', f))

        if (isEdit.value) {
            fd.append('_method', 'PUT')
            await axios.post(`/admin/products/${route.params.id}`, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
            toast.success('Product updated!')
        } else {
            await axios.post('/admin/products', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
            toast.success('Product created!')
        }
        router.push('/admin/products')
    } catch (e) {
        const errors = e.response?.data?.errors
        if (errors) toast.error(Object.values(errors)[0][0])
        else toast.error('Failed to save product')
    } finally { saving.value = false }
}

onMounted(async () => {
    const { data: cats } = await axios.get('/categories')
    categories.value = cats

    if (isEdit.value) {
        try {
            const { data } = await axios.get(`/admin/products/${route.params.id}`)
            Object.assign(form, data)
            colorsInput.value = (data.colors || []).join(', ')
            tagsInput.value = (data.tags || []).join(', ')
            if (data.featured_image_url) imagePreview.value = data.featured_image_url
        } catch (e) { toast.error('Failed to load product') }
    }
})
</script>
