import { defineStore } from 'pinia'
import axios from 'axios'
import { toast } from 'vue3-toastify'

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
        subtotal: 0,
        count: 0,
        loading: false,
        isOpen: false,
        coupon: null,
        discount: 0,
    }),

    getters: {
        total: (state) => {
            const deliveryCharge = state.subtotal >= 5000 ? 0 : 150
            return state.subtotal - state.discount + deliveryCharge
        },
        deliveryCharge: (state) => state.subtotal >= 5000 ? 0 : 150,
        itemCount: (state) => state.count,
    },

    actions: {
        async fetchCart() {
            try {
                const { data } = await axios.get('/cart')
                this.items = data.items
                this.subtotal = data.subtotal
                this.count = data.count
            } catch (e) {}
        },

        async addToCart(productId, quantity = 1, color = null) {
            this.loading = true
            try {
                const { data } = await axios.post('/cart', { product_id: productId, quantity, color })
                await this.fetchCart()
                this.isOpen = true
                toast.success('Added to cart!')
                return data
            } catch (error) {
                toast.error(error.response?.data?.message || 'Failed to add to cart')
                throw error
            } finally {
                this.loading = false
            }
        },

        async updateQuantity(cartId, quantity) {
            try {
                await axios.put(`/cart/${cartId}`, { quantity })
                await this.fetchCart()
            } catch (error) {
                toast.error(error.response?.data?.message || 'Failed to update cart')
            }
        },

        async removeItem(cartId) {
            try {
                await axios.delete(`/cart/${cartId}`)
                await this.fetchCart()
                toast.success('Item removed from cart')
            } catch (e) {}
        },

        async clearCart() {
            try {
                await axios.delete('/cart')
                this.items = []
                this.subtotal = 0
                this.count = 0
                this.coupon = null
                this.discount = 0
            } catch (e) {}
        },

        async applyCoupon(code) {
            try {
                const { data } = await axios.post('/coupons/apply', { code, subtotal: this.subtotal })
                this.coupon = data.coupon
                this.discount = data.discount
                toast.success(data.message)
                return data
            } catch (error) {
                toast.error(error.response?.data?.message || 'Invalid coupon')
                throw error
            }
        },

        removeCoupon() {
            this.coupon = null
            this.discount = 0
        },

        openCart() { this.isOpen = true },
        closeCart() { this.isOpen = false },
        toggleCart() { this.isOpen = !this.isOpen },
    },
})
