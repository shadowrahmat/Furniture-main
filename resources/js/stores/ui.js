import { defineStore } from 'pinia'

export const useUiStore = defineStore('ui', {
    state: () => ({
        quickViewSlug: null,
        searchOpen: false,
    }),
    actions: {
        openQuickView(slug) { this.quickViewSlug = slug },
        closeQuickView() { this.quickViewSlug = null },
        openSearch() { this.searchOpen = true },
        closeSearch() { this.searchOpen = false },
        toggleSearch() { this.searchOpen = !this.searchOpen },
    },
})
