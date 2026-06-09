import { defineStore } from 'pinia'
import axios from 'axios'
import { toast } from 'vue3-toastify'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user') || 'null'),
        token: localStorage.getItem('auth_token') || null,
        loading: false,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token && !!state.user,
        isAdmin: (state) => state.user?.is_admin === true,
        currentUser: (state) => state.user,
    },

    actions: {
        async login(credentials) {
            this.loading = true
            try {
                const { data } = await axios.post('/auth/login', credentials)
                this.token = data.token
                this.user = data.user
                localStorage.setItem('auth_token', data.token)
                localStorage.setItem('user', JSON.stringify(data.user))
                toast.success('Welcome back, ' + data.user.name + '!')
                return data
            } catch (error) {
                const message = error.response?.data?.message || 'Login failed'
                toast.error(message)
                throw error
            } finally {
                this.loading = false
            }
        },

        async register(userData) {
            this.loading = true
            try {
                const { data } = await axios.post('/auth/register', userData)
                this.token = data.token
                this.user = data.user
                localStorage.setItem('auth_token', data.token)
                localStorage.setItem('user', JSON.stringify(data.user))
                toast.success('Welcome to Furniture Store!')
                return data
            } catch (error) {
                toast.error(error.response?.data?.message || 'Registration failed')
                throw error
            } finally {
                this.loading = false
            }
        },

        async logout() {
            try {
                await axios.post('/auth/logout')
            } catch (e) {}
            this.token = null
            this.user = null
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user')
            toast.success('Logged out successfully')
        },

        async fetchUser() {
            if (!this.token) return
            try {
                const { data } = await axios.get('/auth/me')
                this.user = data
                localStorage.setItem('user', JSON.stringify(data))
            } catch (e) {
                this.logout()
            }
        },
    },
})
