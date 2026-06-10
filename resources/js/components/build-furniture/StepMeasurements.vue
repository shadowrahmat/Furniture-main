<template>
    <div>
        <h3 class="bf-step-title">Measurements &amp; Preferences</h3>
        <p class="bf-step-desc">Tell us the size and style details for your furniture piece.</p>

        <div class="bf-field-grid bf-field-grid-4">
            <div class="bf-field">
                <label class="bf-label">Length <span class="bf-required">*</span></label>
                <input v-model="form.length" @blur="touch('length')" type="number" min="0.01" step="0.01"
                    class="input-field" placeholder="e.g. 60" />
                <p v-if="touched.length && !validNumber(form.length)" class="bf-error">Enter a valid length greater than 0.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Width <span class="bf-required">*</span></label>
                <input v-model="form.width" @blur="touch('width')" type="number" min="0.01" step="0.01"
                    class="input-field" placeholder="e.g. 30" />
                <p v-if="touched.width && !validNumber(form.width)" class="bf-error">Enter a valid width greater than 0.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Height <span class="bf-required">*</span></label>
                <input v-model="form.height" @blur="touch('height')" type="number" min="0.01" step="0.01"
                    class="input-field" placeholder="e.g. 40" />
                <p v-if="touched.height && !validNumber(form.height)" class="bf-error">Enter a valid height greater than 0.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Unit <span class="bf-required">*</span></label>
                <select v-model="form.dimension_unit" class="input-field">
                    <option value="inch">Inch</option>
                    <option value="feet">Feet</option>
                    <option value="cm">Centimeter</option>
                    <option value="mm">Millimeter</option>
                </select>
            </div>
        </div>

        <div class="bf-field-grid bf-field-grid-2 bf-mt">
            <div class="bf-field">
                <label class="bf-label">Quantity <span class="bf-required">*</span></label>
                <input v-model="form.quantity" @blur="touch('quantity')" type="number" min="1" step="1"
                    class="input-field" placeholder="e.g. 1" />
                <p v-if="touched.quantity && !validQuantity(form.quantity)" class="bf-error">Quantity must be at least 1.</p>
            </div>
            <div class="bf-field">
                <label class="bf-label">Design Style</label>
                <input v-model="form.design_style" type="text" list="bf-design-styles" class="input-field" placeholder="e.g. Modern, Minimalist" />
                <datalist id="bf-design-styles">
                    <option value="Modern" />
                    <option value="Minimalist" />
                    <option value="Classic" />
                    <option value="Contemporary" />
                    <option value="Industrial" />
                    <option value="Rustic" />
                    <option value="Traditional" />
                    <option value="Scandinavian" />
                </datalist>
            </div>
        </div>

        <div class="bf-field-grid bf-field-grid-2 bf-mt">
            <div class="bf-field">
                <label class="bf-label">Preferred Finish</label>
                <input v-model="form.finish" type="text" list="bf-finishes" class="input-field" placeholder="e.g. Matte, Glossy, Natural" />
                <datalist id="bf-finishes">
                    <option value="Matte" />
                    <option value="Glossy" />
                    <option value="Satin" />
                    <option value="Natural" />
                    <option value="Lacquered" />
                </datalist>
            </div>
            <div class="bf-field">
                <label class="bf-label">Preferred Color</label>
                <input v-model="form.color_preference" type="text" class="input-field" placeholder="e.g. Walnut Brown, Charcoal" />
            </div>
        </div>

        <div class="bf-field bf-mt">
            <label class="bf-label">Upholstery / Fabric <span class="bf-optional">(if applicable)</span></label>
            <input v-model="form.upholstery_fabric" type="text" class="input-field" placeholder="e.g. Linen, Velvet, Leather" />
        </div>
    </div>
</template>

<script setup>
import { computed, reactive } from 'vue'

const props = defineProps({
    form: { type: Object, required: true },
})

const touched = reactive({ length: false, width: false, height: false, quantity: false })
function touch(field) { touched[field] = true }

function validNumber(val) {
    const n = parseFloat(val)
    return Number.isFinite(n) && n > 0
}
function validQuantity(val) {
    const n = Number(val)
    return Number.isInteger(n) && n >= 1
}

const isValid = computed(() =>
    validNumber(props.form.length) &&
    validNumber(props.form.width) &&
    validNumber(props.form.height) &&
    validQuantity(props.form.quantity) &&
    !!props.form.dimension_unit
)
defineExpose({ isValid })
</script>
