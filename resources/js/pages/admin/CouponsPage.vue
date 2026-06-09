<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-bold text-2xl" style="color:#1E1B18">Coupons</h1>
            <button @click="openModal()" class="btn-primary text-sm">+ Add Coupon</button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color:#F0E8DE">
            <div v-if="loading" class="p-8 text-center" style="color:#6D655F">Loading...</div>
            <div v-else-if="coupons.length === 0" class="p-12 text-center">
                <div class="text-5xl mb-3">🏷️</div>
                <p style="color:#6D655F">No coupons yet</p>
            </div>
            <table v-else class="w-full">
                <thead class="text-xs font-semibold uppercase tracking-wider" style="background:#FAF8F5;color:#6D655F">
                    <tr>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Type / Value</th>
                        <th class="px-4 py-3 text-left hidden sm:table-cell">Usage</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Expires</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8DE">
                    <tr v-for="coupon in coupons" :key="coupon.id" class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-mono font-bold text-sm px-2 py-1 rounded" style="background:#F5EFE6;color:#8B5E3C">{{ coupon.code }}</span>
                            <p v-if="coupon.description" class="text-xs mt-1" style="color:#6D655F">{{ coupon.description }}</p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="text-sm font-semibold" style="color:#1E1B18">
                                {{ coupon.type === 'percentage' ? coupon.value + '%' : '৳' + Number(coupon.value).toLocaleString() }}
                            </span>
                            <span class="text-xs ml-1" style="color:#6D655F">{{ coupon.type }}</span>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell text-sm" style="color:#6D655F">
                            {{ coupon.used_count }} / {{ coupon.max_uses || '∞' }}
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-sm" style="color:#6D655F">
                            {{ coupon.expires_at ? new Date(coupon.expires_at).toLocaleDateString() : 'No expiry' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                :style="coupon.is_active ? 'background:#D1FAE5;color:#065F46' : 'background:#FEE2E2;color:#991B1B'">
                                {{ coupon.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openModal(coupon)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#F5EFE6;color:#8B5E3C">Edit</button>
                                <button @click="confirmDelete(coupon)" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#FEE2E2;color:#991B1B">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Coupon Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
                <h3 class="font-heading font-bold text-xl mb-5" style="color:#1E1B18">{{ editTarget ? 'Edit Coupon' : 'Add Coupon' }}</h3>
                <form @submit.prevent="saveCoupon" class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Code *</label>
                        <div class="flex gap-2">
                            <input v-model="form.code" type="text" class="input-field flex-1 uppercase" required placeholder="SAVE20">
                            <button type="button" @click="generateCode" class="px-3 py-2 rounded-lg text-sm font-medium" style="background:#F5EFE6;color:#8B5E3C">Generate</button>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Description</label>
                        <input v-model="form.description" type="text" class="input-field">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Type *</label>
                            <select v-model="form.type" class="input-field" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (৳)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Value *</label>
                            <input v-model="form.value" type="number" step="0.01" min="0" class="input-field" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Min Order (৳)</label>
                            <input v-model="form.min_order_amount" type="number" min="0" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Max Uses</label>
                            <input v-model="form.max_uses" type="number" min="0" class="input-field" placeholder="Unlimited">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider mb-1.5 block" style="color:#6D655F">Expires At</label>
                        <input v-model="form.expires_at" type="date" class="input-field">
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
                <h3 class="font-heading font-bold text-xl mb-2" style="color:#1E1B18">Delete Coupon?</h3>
                <p class="mb-6 text-sm" style="color:#6D655F">Delete coupon <strong>{{ deleteTarget.code }}</strong>?</p>
                <div class="flex gap-3">
                    <button @click="deleteTarget = null" class="flex-1 btn-outline text-sm py-2">Cancel</button>
                    <button @click="deleteCoupon" :disabled="deleting" class="flex-1 text-sm py-2 rounded-lg font-semibold text-white" style="background:#DC2626">
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
