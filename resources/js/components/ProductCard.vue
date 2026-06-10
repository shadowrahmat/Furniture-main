<template>
    <article class="p-card group" @click="goToProduct">

        <!-- Image -->
        <div class="p-img-wrap">
            <img v-if="imgSrc" :src="imgSrc" :alt="product.name"
                class="p-img" loading="lazy">
            <div v-else class="p-img-placeholder">
                <svg width="32" height="32" fill="none" stroke="#C9B99A" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>

            <!-- Hover overlay actions -->
            <div class="p-overlay">
                <button @click.stop="openQuickView"
                    class="p-action-btn" title="Quick View">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Quick View
                </button>
            </div>

            <!-- Badges -->
            <div class="p-badges">
                <span v-if="product.discount_percent" class="badge badge-sale">
                    -{{ product.discount_percent }}%
                </span>
                <span v-else-if="isNew" class="badge badge-new">New</span>
            </div>

            <!-- Wishlist -->
            <button @click.stop="toggleWishlist"
                class="p-wish-btn"
                :class="inWishlist ? 'p-wish-active' : ''"
                :title="inWishlist ? 'Remove from wishlist' : 'Add to wishlist'">
                <svg width="18" height="18" viewBox="0 0 24 24"
                    :fill="inWishlist ? '#D94040' : 'none'"
                    :stroke="inWishlist ? '#D94040' : 'currentColor'"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>

        <!-- Info -->
        <div class="p-info">
            <!-- Category -->
            <span v-if="product.category?.name" class="p-category">{{ product.category.name }}</span>

            <!-- Name -->
            <h3 class="p-name">{{ product.name }}</h3>

            <!-- Rating -->
            <div v-if="product.average_rating" class="p-rating">
                <div class="p-stars">
                    <span v-for="i in 5" :key="i"
                        :style="{ color: i <= Math.round(product.average_rating) ? '#F59E0B' : '#E5DDD4' }">★</span>
                </div>
                <span class="p-rating-count">({{ product.reviews_count || 0 }})</span>
            </div>

            <!-- Price Row -->
            <div class="p-footer">
                <div class="p-prices">
                    <span class="p-price">৳{{ fmt(product.sale_price || product.price) }}</span>
                    <span v-if="product.sale_price && product.price > product.sale_price" class="p-original">
                        ৳{{ fmt(product.price) }}
                    </span>
                </div>
                <button @click.stop="addToCart"
                    class="p-cart-btn"
                    :class="{ 'p-cart-added': added }"
                    :disabled="added">
                    <svg v-if="!added" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <svg v-else width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </article>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter }         from 'vue-router'
import { useCartStore }      from '../stores/cart.js'
import { useWishlistStore }  from '../stores/wishlist.js'
import { useUiStore }        from '../stores/ui.js'

const props = defineProps({
    product: { type: Object, required: true },
})

const router        = useRouter()
const cartStore     = useCartStore()
const wishlistStore = useWishlistStore()
const uiStore       = useUiStore()

const added = ref(false)

const imgSrc = computed(() => {
    if (!props.product.featured_image) return null
    const url = props.product.featured_image
    return url.startsWith('http') ? url : `/storage/${url}`
})

const isNew = computed(() => {
    if (!props.product.created_at) return false
    return (Date.now() - new Date(props.product.created_at).getTime()) < 30 * 24 * 60 * 60 * 1000
})

const inWishlist = computed(() => wishlistStore.isInWishlist(props.product.id))

function fmt(v) {
    return Number(v || 0).toLocaleString('en-BD')
}

function goToProduct() {
    router.push(`/product/${props.product.slug}`)
}

function openQuickView() {
    uiStore.openQuickView(props.product.slug)
}

function toggleWishlist() {
    wishlistStore.toggleWishlist(props.product.id)
}

async function addToCart() {
    if (added.value) return
    await cartStore.addToCart(props.product.id, 1)
    added.value = true
    setTimeout(() => { added.value = false }, 1800)
}
</script>

<style scoped>
.p-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1.5px solid #EDE5D8;
    overflow: hidden;
    cursor: pointer;
    transition: box-shadow 0.28s ease, transform 0.28s ease, border-color 0.2s ease;
    display: flex;
    flex-direction: column;
    position: relative;
}
.p-card:hover {
    box-shadow: 0 16px 48px rgba(92, 46, 10, 0.11);
    transform: translateY(-4px);
    border-color: #D4C4A8;
}

/* Image area */
.p-img-wrap {
    position: relative;
    aspect-ratio: 4 / 3;
    background: #F5EEE6;
    overflow: hidden;
    border-radius: 18px 18px 0 0;
}
.p-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.p-card:hover .p-img { transform: scale(1.06); }
.p-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #F5EEE6, #EDE5D8);
}

/* Overlay */
.p-overlay {
    position: absolute; inset: 0;
    background: rgba(17, 17, 9, 0.35);
    display: flex; align-items: center; justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease;
    border-radius: 18px 18px 0 0;
}
.p-card:hover .p-overlay { opacity: 1; }

.p-action-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 50px;
    background: #FFFFFF; color: #111109;
    font-size: 12.5px; font-weight: 600;
    border: none; cursor: pointer;
    transition: all 0.2s ease;
    transform: translateY(6px);
    transition: transform 0.25s ease, opacity 0.25s ease, background 0.2s ease;
    opacity: 0;
    box-shadow: 0 4px 16px rgba(17, 17, 9, 0.2);
}
.p-card:hover .p-action-btn { transform: translateY(0); opacity: 1; }
.p-action-btn:hover { background: #8B5E3C; color: #fff; }

/* Badges */
.p-badges {
    position: absolute; top: 10px; left: 10px;
    display: flex; flex-direction: column; gap: 4px;
}

/* Wishlist */
.p-wish-btn {
    position: absolute; top: 10px; right: 10px;
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: none; cursor: pointer; color: #9B9087;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.2s ease, color 0.2s ease, transform 0.18s ease;
    backdrop-filter: blur(8px);
    box-shadow: 0 2px 8px rgba(17,17,9,0.12);
}
.p-card:hover .p-wish-btn { opacity: 1; }
.p-wish-btn:hover { transform: scale(1.1); }
.p-wish-active { opacity: 1 !important; color: #D94040; }

/* Info */
.p-info {
    padding: 14px 16px 16px;
    display: flex; flex-direction: column; gap: 6px; flex: 1;
}

.p-category {
    font-size: 10.5px; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: #C9A055;
}

.p-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 15.5px; font-weight: 700;
    color: #1E1208; line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Rating */
.p-rating {
    display: flex; align-items: center; gap: 5px;
}
.p-stars { letter-spacing: -2px; font-size: 13px; line-height: 1; }
.p-rating-count { font-size: 11px; color: #9B9087; }

/* Footer */
.p-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 4px;
}
.p-prices { display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; }
.p-price {
    font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 700;
    color: #8B5E3C;
}
.p-original { font-size: 12px; color: #B0A49A; text-decoration: line-through; }

.p-cart-btn {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C);
    color: #fff; border: none; cursor: pointer; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.22s ease;
    box-shadow: 0 3px 10px rgba(92, 46, 10, 0.32);
}
.p-cart-btn:hover { box-shadow: 0 6px 18px rgba(92, 46, 10, 0.45); transform: scale(1.08); }
.p-cart-btn:active { transform: scale(0.95); }
.p-cart-added { background: #3D7A50 !important; box-shadow: 0 3px 10px rgba(61,122,80,0.3) !important; }
</style>
