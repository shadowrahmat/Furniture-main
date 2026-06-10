<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Banners</h1>
                <p class="adm-page-sub">{{ banners.length }} total banners</p>
            </div>
            <button @click="openModal()" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Banner
            </button>
        </div>

        <!-- List card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 4" :key="n" class="adm-row-skeleton" style="height:76px"></div>
            </div>

            <div v-else-if="banners.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No banners yet</p>
                <p class="adm-empty-sub">Add a homepage banner to promote offers and collections.</p>
                <button @click="openModal()" class="adm-btn-primary mt-4">Add First Banner</button>
            </div>

            <template v-else>
                <div v-for="banner in banners" :key="banner.id" class="adm-list-row">
                    <img :src="banner.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=120&h=60&fit=crop'"
                        class="w-28 h-16 rounded-lg object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm" style="color:#0F172A">{{ banner.title }}</p>
                        <p v-if="banner.subtitle" class="text-xs truncate mt-0.5" style="color:#94A3B8">{{ banner.subtitle }}</p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="adm-status-pill" :class="banner.is_active ? 'pill-active' : 'pill-inactive'">
                                <span class="pill-dot"></span>
                                {{ banner.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span v-if="banner.starts_at" class="text-xs" style="color:#94A3B8">
                                {{ new Date(banner.starts_at).toLocaleDateString() }} – {{ banner.ends_at ? new Date(banner.ends_at).toLocaleDateString() : '∞' }}
                            </span>
                            <span class="text-xs" style="color:#94A3B8">Order: {{ banner.sort_order }}</span>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1.5 flex-shrink-0">
                        <button @click="openModal(banner)" class="adm-act-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button @click="confirmDelete(banner)" class="adm-act-btn adm-act-danger">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Add / Edit Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="showModal" class="adm-modal-overlay" @click.self="showModal = false">
                    <div class="adm-modal-form">
                        <h3 class="adm-modal-title mb-5">{{ editTarget ? 'Edit Banner' : 'Add Banner' }}</h3>
                        <form @submit.prevent="saveBanner" class="space-y-4">
                            <div>
                                <label class="adm-form-label">Title *</label>
                                <input v-model="form.title" type="text" class="input-field" required>
                            </div>
                            <div>
                                <label class="adm-form-label">Subtitle</label>
                                <input v-model="form.subtitle" type="text" class="input-field">
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Button Text</label>
                                    <input v-model="form.button_text" type="text" class="input-field">
                                </div>
                                <div>
                                    <label class="adm-form-label">Button Link</label>
                                    <input v-model="form.button_link" type="text" class="input-field" placeholder="/shop">
                                </div>
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Starts At</label>
                                    <input v-model="form.starts_at" type="date" class="input-field">
                                </div>
                                <div>
                                    <label class="adm-form-label">Ends At</label>
                                    <input v-model="form.ends_at" type="date" class="input-field">
                                </div>
                            </div>
                            <div>
                                <label class="adm-form-label">Sort Order</label>
                                <input v-model="form.sort_order" type="number" min="0" class="input-field w-24">
                            </div>
                            <div>
                                <label class="adm-form-label">Banner Image</label>
                                <div class="adm-image-upload" @click="$refs.bannerImg.click()">
                                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-32 object-cover rounded-lg">
                                    <div v-else class="py-6">
                                        <svg class="mx-auto mb-2" width="28" height="28" fill="none" stroke="#C9A66B" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm" style="color:#94A3B8">Click to upload</p>
                                    </div>
                                    <input ref="bannerImg" type="file" accept="image/*" class="hidden" @change="handleImage">
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
                        <h3 class="adm-modal-title">Delete Banner?</h3>
                        <p class="adm-modal-desc">Delete banner <strong>{{ deleteTarget.title }}</strong>?</p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteBanner" :disabled="deleting" class="adm-btn-danger">
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
