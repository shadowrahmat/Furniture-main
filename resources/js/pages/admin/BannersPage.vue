<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Banners</h1>
            <button @click="openModal()" class="btn-primary text-sm">+ Add Banner</button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color:#F0E8DE">
            <div v-if="loading" class="p-8 text-center" style="color:#6D655F">Loading...</div>
            <div v-else-if="banners.length === 0" class="p-12 text-center">
                <div class="text-5xl mb-3">🖼️</div>
                <p style="color:#6D655F">No banners yet</p>
            </div>
            <div v-else class="divide-y" style="divide-color:#F0E8DE">
                <div v-for="banner in banners" :key="banner.id" class="flex items-center gap-4 p-4 hover:bg-amber-50/30 transition-colors">
                    <img :src="banner.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=120&h=60&fit=crop'"
                        class="w-28 h-16 rounded-lg object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm" style="color:#1E1B18">{{ banner.title }}</p>
                        <p v-if="banner.subtitle" class="text-xs truncate" style="color:#6D655F">{{ banner.subtitle }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                                :style="banner.is_active ? 'background:#D1FAE5;color:#065F46' : 'background:#FEE2E2;color:#991B1B'">
                                {{ banner.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span v-if="banner.starts_at" class="text-xs" style="color:#6D655F">
                                {{ new Date(banner.starts_at).toLocaleDateString() }} – {{ banner.ends_at ? new Date(banner.ends_at).toLocaleDateString() : '∞' }}
                            </span>
                            <span class="text-xs" style="color:#6D655F">Order: {{ banner.sort_order }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button @click="openModal(banner)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#F5EFE6;color:#8B5E3C">Edit</button>
                        <button @click="confirmDelete(banner)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#FEE2E2;color:#991B1B">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl max-h-[90vh] overflow-y-auto">
                <h3 class="font-heading font-bold text-xl mb-5" style="color:#1E1B18">{{ editTarget ? 'Edit Banner' : 'Add Banner' }}</h3>
                <form @submit.prevent="saveBanner" class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Title *</label>
                        <input v-model="form.title" type="text" class="input-field" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Subtitle</label>
                        <input v-model="form.subtitle" type="text" class="input-field">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Button Text</label>
                        <input v-model="form.button_text" type="text" class="input-field">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Button Link</label>
                        <input v-model="form.button_link" type="text" class="input-field" placeholder="/shop">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Starts At</label>
                            <input v-model="form.starts_at" type="date" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Ends At</label>
                            <input v-model="form.ends_at" type="date" class="input-field">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Sort Order</label>
                        <input v-model="form.sort_order" type="number" min="0" class="input-field w-24">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Banner Image</label>
                        <div class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer hover:border-amber-400 transition-colors"
                            style="border-color:#C9A66B" @click="$refs.bannerImg.click()">
                            <img v-if="imagePreview" :src="imagePreview" class="w-full h-32 object-cover rounded-lg mb-2">
                            <p v-else class="text-sm py-4" style="color:#6D655F">Click to upload</p>
                            <input ref="bannerImg" type="file" accept="image/*" class="hidden" @change="handleImage">
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="w-4 h-4 rounded" style="accent-color:#8B5E3C">
                        <span class="text-sm" style="color:#1E1B18">Active</span>
                    </label>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal = false" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                        <button type="submit" :disabled="saving" class="flex-1 btn-primary text-sm py-2">{{ saving ? 'Saving...' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
                <h3 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Delete Banner?</h3>
                <p class="mb-6 text-sm" style="color:#6D655F">Delete banner <strong>{{ deleteTarget.title }}</strong>?</p>
                <div class="flex gap-3">
                    <button @click="deleteTarget = null" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                    <button @click="deleteBanner" :disabled="deleting" class="flex-1 text-sm py-2 rounded-lg font-semibold text-white" style="background:#DC2626">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const banners = ref([])
const loading = ref(true)
const showModal = ref(false)
const editTarget = ref(null)
const deleteTarget = ref(null)
const deleting = ref(false)
const saving = ref(false)
const imagePreview = ref(null)
let imageFile = null

const form = reactive({ title: '', subtitle: '', button_text: '', button_link: '', starts_at: '', ends_at: '', sort_order: 0, is_active: true })

function handleImage(e) {
    imageFile = e.target.files[0]
    if (imageFile) imagePreview.value = URL.createObjectURL(imageFile)
}

function openModal(banner = null) {
    editTarget.value = banner
    imagePreview.value = banner?.image_url || null
    imageFile = null
    if (banner) Object.assign(form, { title: banner.title, subtitle: banner.subtitle || '', button_text: banner.button_text || '', button_link: banner.button_link || '', starts_at: banner.starts_at?.substring(0, 10) || '', ends_at: banner.ends_at?.substring(0, 10) || '', sort_order: banner.sort_order, is_active: banner.is_active })
    else Object.assign(form, { title: '', subtitle: '', button_text: '', button_link: '', starts_at: '', ends_at: '', sort_order: 0, is_active: true })
    showModal.value = true
}

async function fetchBanners() {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/banners')
        banners.value = data
    } catch (e) { toast.error('Failed to load banners') }
    finally { loading.value = false }
}

async function saveBanner() {
    saving.value = true
    try {
        const fd = new FormData()
        Object.entries(form).forEach(([k, v]) => fd.append(k, v))
        if (imageFile) fd.append('image', imageFile)

        if (editTarget.value) {
            fd.append('_method', 'PUT')
            const { data } = await axios.post(`/admin/banners/${editTarget.value.id}`, fd)
            const idx = banners.value.findIndex(b => b.id === editTarget.value.id)
            if (idx !== -1) banners.value[idx] = data
            toast.success('Banner updated!')
        } else {
            const { data } = await axios.post('/admin/banners', fd)
            banners.value.unshift(data)
            toast.success('Banner created!')
        }
        showModal.value = false
    } catch (e) { toast.error('Failed to save banner') }
    finally { saving.value = false }
}

function confirmDelete(banner) { deleteTarget.value = banner }

async function deleteBanner() {
    deleting.value = true
    try {
        await axios.delete(`/admin/banners/${deleteTarget.value.id}`)
        banners.value = banners.value.filter(b => b.id !== deleteTarget.value.id)
        toast.success('Banner deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete banner') }
    finally { deleting.value = false }
}

onMounted(fetchBanners)
</script>
