<template>
    <footer style="background:#1E1B18; color:#F5EFE6">
        <!-- Main footer -->
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Brand -->
                <div class="lg:col-span-1">
                    <RouterLink to="/" class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                            style="background: linear-gradient(135deg, #8B5E3C, #C9A66B)">
                            <span class="text-white font-bold text-lg">F</span>
                        </div>
                        <span class="font-heading font-bold text-xl text-white">Furniture Store</span>
                    </RouterLink>
                    <p class="text-sm leading-relaxed mb-6" style="color:#9B9087">
                        Our Furniture Store delivers premium modern furniture, elegant decor, and comfortable living solutions designed for stylish homes and workspaces.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Quick Links</h4>
                    <ul class="space-y-3">
                        <li v-for="link in quickLinks" :key="link.to">
                            <RouterLink :to="link.to" class="footer-link">{{ link.label }}</RouterLink>
                        </li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Categories</h4>
                    <ul class="space-y-3">
                        <li v-for="cat in footerCategories" :key="cat.to">
                            <RouterLink :to="cat.to" class="footer-link">{{ cat.label }}</RouterLink>
                        </li>
                    </ul>
                </div>

                <!-- Contact & Newsletter -->
                <div>
                    <h4 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Contact Us</h4>
                    <div class="space-y-3 mb-6">
                        <p class="flex items-start gap-2 text-sm" style="color:#9B9087">
                            <span class="mt-0.5">📍</span>
                            <span>123 Furniture Street, Dhaka 1200, Bangladesh</span>
                        </p>
                        <a href="tel:+8801700000000" class="flex items-center gap-2 text-sm footer-link">
                            <span>📞</span> +880 1700-000000
                        </a>
                        <a href="mailto:hello@furniturestore.com" class="flex items-center gap-2 text-sm footer-link">
                            <span>✉️</span> hello@furniturestore.com
                        </a>
                    </div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Newsletter</h4>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Your email" v-model="newsletterEmail"
                            class="flex-1 px-3 py-2 rounded-lg text-sm text-gray-800 bg-white outline-none">
                        <button @click="subscribeNewsletter" class="px-4 py-2 rounded-lg text-sm font-semibold text-white"
                            style="background: linear-gradient(135deg, #8B5E3C, #C9A66B)">
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div style="border-top: 1px solid rgba(255,255,255,0.08)">
            <div class="max-w-7xl mx-auto px-4 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs" style="color:#9B9087">© {{ year }} Furniture Store. All rights reserved.</p>
                <div class="flex gap-4">
                    <RouterLink to="/privacy-policy" class="text-xs footer-link">Privacy Policy</RouterLink>
                    <RouterLink to="/terms" class="text-xs footer-link">Terms & Conditions</RouterLink>
                    <RouterLink to="/faq" class="text-xs footer-link">FAQ</RouterLink>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref } from 'vue'
import { toast } from 'vue3-toastify'

const year = new Date().getFullYear()
const newsletterEmail = ref('')

const quickLinks = [
    { to: '/', label: 'Home' },
    { to: '/shop', label: 'Shop All' },
    { to: '/about', label: 'About Us' },
    { to: '/contact', label: 'Contact' },
    { to: '/custom-furniture', label: 'Custom Furniture' },
    { to: '/track-order', label: 'Track Order' },
]

const footerCategories = [
    { to: '/shop/living-room-furniture', label: 'Living Room' },
    { to: '/shop/bedroom-furniture', label: 'Bedroom' },
    { to: '/shop/dining-furniture', label: 'Dining' },
    { to: '/shop/office-furniture', label: 'Office' },
    { to: '/shop/storage-cabinets', label: 'Storage' },
    { to: '/shop/home-decor', label: 'Home Decor' },
]

function subscribeNewsletter() {
    if (!newsletterEmail.value) return
    toast.success('Thank you for subscribing!')
    newsletterEmail.value = ''
}
</script>

<style scoped>
.footer-link {
    font-size: 13px;
    color: #9B9087;
    transition: color 0.2s;
}
.footer-link:hover {
    color: #C9A66B;
}
.social-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.08);
    color: #9B9087;
    transition: all 0.2s;
}
.social-icon:hover {
    background: #8B5E3C;
    color: white;
}
</style>
