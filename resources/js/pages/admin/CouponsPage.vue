<template>
    <div>

        <!-- Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Coupons</h1>
                <p class="adm-page-sub">{{ coupons.length }} total coupons</p>
            </div>
            <button @click="openModal()" class="adm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Coupon
            </button>
        </div>

        <!-- Table card -->
        <div class="adm-card">

            <div v-if="loading" class="adm-loading">
                <div v-for="n in 5" :key="n" class="adm-row-skeleton"></div>
            </div>

            <div v-else-if="coupons.length === 0" class="adm-empty">
                <div class="adm-empty-icon">
                    <svg width="32" height="32" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 11V6a3 3 0 013-3z"/>
                    </svg>
                </div>
                <p class="adm-empty-title">No coupons yet</p>
                <p class="adm-empty-sub">Create a coupon to offer discounts to customers.</p>
                <button @click="openModal()" class="adm-btn-primary mt-4">Add First Coupon</button>
            </div>

            <div v-else class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th class="hidden md:table-cell">Type / Value</th>
                            <th class="hidden sm:table-cell">Usage</th>
                            <th class="hidden lg:table-cell">Expires</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="coupon in coupons" :key="coupon.id">
                            <td>
                                <span class="font-mono font-bold text-sm px-2 py-1 rounded-lg" style="background:#FFFBEB;color:#8B5E3C">{{ coupon.code }}</span>
                                <p v-if="coupon.description" class="text-xs mt-1" style="color:#94A3B8">{{ coupon.description }}</p>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="font-bold text-sm" style="color:#0F172A;font-family:'Poppins',sans-serif">
                                    {{ coupon.type === 'percentage' ? coupon.value + '%' : '৳' + Number(coupon.value).toLocaleString() }}
                                </span>
                                <span class="text-xs ml-1 capitalize" style="color:#94A3B8">{{ coupon.type }}</span>
                            </td>
                            <td class="hidden sm:table-cell">
                                <span class="text-sm" style="color:#475569">{{ coupon.used_count }} / {{ coupon.max_uses || '∞' }}</span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="text-sm" style="color:#475569">
                                    {{ coupon.expires_at ? new Date(coupon.expires_at).toLocaleDateString() : 'No expiry' }}
                                </span>
                            </td>
                            <td>
                                <span class="adm-status-pill" :class="coupon.is_active ? 'pill-active' : 'pill-inactive'">
                                    <span class="pill-dot"></span>
                                    {{ coupon.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openModal(coupon)" class="adm-act-btn">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <button @click="confirmDelete(coupon)" class="adm-act-btn adm-act-danger">
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
                        <h3 class="adm-modal-title mb-5">{{ editTarget ? 'Edit Coupon' : 'Add Coupon' }}</h3>
                        <form @submit.prevent="saveCoupon" class="space-y-4">
                            <div>
                                <label class="adm-form-label">Code *</label>
                                <div class="flex gap-2">
                                    <input v-model="form.code" type="text" class="input-field flex-1 uppercase" required placeholder="SAVE20">
                                    <button type="button" @click="generateCode" class="adm-btn-ghost" style="flex:none; padding:0 16px">Generate</button>
                                </div>
                            </div>
                            <div>
                                <label class="adm-form-label">Description</label>
                                <input v-model="form.description" type="text" class="input-field">
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Type *</label>
                                    <select v-model="form.type" class="input-field" required>
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount (৳)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="adm-form-label">Value *</label>
                                    <input v-model="form.value" type="number" step="0.01" min="0" class="input-field" required>
                                </div>
                            </div>
                            <div class="adm-form-grid">
                                <div>
                                    <label class="adm-form-label">Min Order (৳)</label>
                                    <input v-model="form.min_order_amount" type="number" min="0" class="input-field">
                                </div>
                                <div>
                                    <label class="adm-form-label">Max Uses</label>
                                    <input v-model="form.max_uses" type="number" min="0" class="input-field" placeholder="Unlimited">
                                </div>
                            </div>
                            <div>
                                <label class="adm-form-label">Expires At</label>
                                <input v-model="form.expires_at" type="date" class="input-field">
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
                        <h3 class="adm-modal-title">Delete Coupon?</h3>
                        <p class="adm-modal-desc">Delete coupon <strong>{{ deleteTarget.code }}</strong>?</p>
                        <div class="adm-modal-actions">
                            <button @click="deleteTarget = null" class="adm-btn-ghost">Cancel</button>
                            <button @click="deleteCoupon" :disabled="deleting" class="adm-btn-danger">
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

const coupons = ref([])
const loading = ref(true)
const showModal = ref(false)
const editTarget = ref(null)
const deleteTarget = ref(null)
const deleting = ref(false)
const saving = ref(false)

const form = reactive({ code: '', description: '', type: 'percentage', value: '', min_order_amount: '', max_uses: '', expires_at: '', is_active: true })

function generateCode() {
    form.code = Math.random().toString(36).substring(2, 8).toUpperCase()
}

function openModal(coupon = null) {
    editTarget.value = coupon
    if (coupon) Object.assign(form, { code: coupon.code, description: coupon.description || '', type: coupon.type, value: coupon.value, min_order_amount: coupon.min_order_amount || '', max_uses: coupon.max_uses || '', expires_at: coupon.expires_at ? coupon.expires_at.substring(0, 10) : '', is_active: coupon.is_active })
    else Object.assign(form, { code: '', description: '', type: 'percentage', value: '', min_order_amount: '', max_uses: '', expires_at: '', is_active: true })
    showModal.value = true
}

async function fetchCoupons() {
    loading.value = true
    try {
        const { data } = await axios.get('/admin/coupons')
        coupons.value = data
    } catch (e) { toast.error('Failed to load coupons') }
    finally { loading.value = false }
}

async function saveCoupon() {
    saving.value = true
    try {
        if (editTarget.value) {
            const { data } = await axios.put(`/admin/coupons/${editTarget.value.id}`, form)
            const idx = coupons.value.findIndex(c => c.id === editTarget.value.id)
            if (idx !== -1) coupons.value[idx] = data
            toast.success('Coupon updated!')
        } else {
            const { data } = await axios.post('/admin/coupons', form)
            coupons.value.unshift(data)
            toast.success('Coupon created!')
        }
        showModal.value = false
    } catch (e) {
        const errors = e.response?.data?.errors
        if (errors) toast.error(Object.values(errors)[0][0])
        else toast.error('Failed to save coupon')
    } finally { saving.value = false }
}

function confirmDelete(coupon) { deleteTarget.value = coupon }

async function deleteCoupon() {
    deleting.value = true
    try {
        await axios.delete(`/admin/coupons/${deleteTarget.value.id}`)
        coupons.value = coupons.value.filter(c => c.id !== deleteTarget.value.id)
        toast.success('Coupon deleted')
        deleteTarget.value = null
    } catch (e) { toast.error('Failed to delete coupon') }
    finally { deleting.value = false }
}

onMounted(fetchCoupons)
</script>
