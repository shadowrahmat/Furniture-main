import { defineStore } from 'pinia'

const STORAGE_KEY = 'lf_recently_viewed'
const MAX_ITEMS = 12

export const useRecentlyViewedStore = defineStore('recentlyViewed', {
    state: () => ({
        products: (() => {
            try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]') }
            catch { return [] }
        })(),
    }),

    actions: {
        track(product) {
            const slim = {
                id: product.id,
                slug: product.slug,
                name: product.name,
                price: product.price,
                sale_price: product.sale_price,
                featured_image: product.featured_image,
                category: product.category,
                avg_rating: product.avg_rating,
                review_count: product.review_count,
                stock: product.stock,
                discount_percentage: product.discount_percentage,
                is_new_arrival: product.is_new_arrival,
                is_best_seller: product.is_best_seller,
            }
            this.products = this.products.filter(p => p.id !== product.id)
            this.products.unshift(slim)
            this.products = this.products.slice(0, MAX_ITEMS)
            try { localStorage.setItem(STORAGE_KEY, JSON.stringify(this.products)) } catch {}
        },

        clear() {
            this.products = []
            try { localStorage.removeItem(STORAGE_KEY) } catch {}
        },
    },
})
