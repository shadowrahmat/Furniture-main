import { defineStore } from 'pinia'
import axios from 'axios'
import { toast } from 'vue3-toastify'

export const useWishlistStore = defineStore('wishlist', {
    state: () => ({
        items: [],
        loading: false,
    }),

    getters: {
        count: (state) => state.items.length,
        isInWishlist: (state) => (productId) => state.items.some(i => i.product_id === productId),
    },

    actions: {
        async fetchWishlist() {
            try {
                const { data } = await axios.get('/wishlist')
                this.items = data
            } catch (e) {}
        },

        async toggleWishlist(productId) {
            this.loading = true
            try {
                const { data } = await axios.post('/wishlist/toggle', { product_id: productId })
                if (data.in_wishlist) {
                    toast.success('Added to wishlist!')
                } else {
                    toast.success('Removed from wishlist')
                }
                await this.fetchWishlist()
                return data
            } catch (error) {
                toast.error('Please login to use wishlist')
                throw error
            } finally {
                this.loading = false
            }
        },
    },
})
