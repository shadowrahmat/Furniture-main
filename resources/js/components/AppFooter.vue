<template>
    <footer style="background:#18140F; color:#F0E8DE">

        <!-- Newsletter -->
        <div style="background:#1E1A15; border-top:1px solid rgba(255,255,255,0.04)">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 p-6 sm:p-8 rounded-2xl"
                    style="background:linear-gradient(135deg,rgba(201,166,107,0.08),rgba(139,94,60,0.05)); border:1px solid rgba(201,166,107,0.15)">
                    <div class="text-center md:text-left">
                        <h3 class="font-heading font-bold text-lg text-white mb-1">Get Exclusive Deals</h3>
                        <p class="text-sm" style="color:rgba(240,232,222,0.55)">New arrivals, offers & design inspiration — straight to your inbox.</p>
                    </div>
                    <form @submit.prevent="subscribeNewsletter" class="flex gap-2 w-full md:w-auto flex-shrink-0">
                        <input v-model="newsletterEmail" type="email" placeholder="Your email address"
                            class="flex-1 md:w-64 px-4 py-3 rounded-xl text-sm outline-none"
                            style="background:rgba(255,255,255,0.07); border:1px solid rgba(201,166,107,0.2); color:#F0E8DE; font-family:'Inter',sans-serif">
                        <button type="submit" class="btn-primary py-3 px-6 text-sm flex-shrink-0">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 lg:gap-12">

                <!-- Brand -->
                <div class="col-span-2 lg:col-span-2">
                    <RouterLink to="/" class="inline-flex items-center gap-3 mb-5 group">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#5C2E0A,#8B5E3C)">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-heading font-bold text-[17px] text-white leading-none">
                                Luxury<span style="color:#C9A66B">Furnish</span>
                            </p>
                            <p class="text-[9px] tracking-[0.2em] uppercase mt-0.5" style="color:rgba(240,232,222,0.35)">Premium Furniture</p>
                        </div>
                    </RouterLink>

                    <p class="text-sm leading-relaxed mb-6 max-w-xs" style="color:rgba(240,232,222,0.5)">
                        Premium handcrafted furniture for modern homes. Timeless designs that blend comfort with lasting elegance.
                    </p>

                    <!-- Socials -->
                    <div class="flex gap-2">
                        <a v-for="s in socials" :key="s.name" :href="s.href"
                            target="_blank" rel="noopener noreferrer"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:-translate-y-0.5"
                            style="background:rgba(255,255,255,0.06); color:rgba(240,232,222,0.5)"
                            :title="s.name"
                            onmouseover="this.style.background='rgba(201,166,107,0.18)'; this.style.color='#C9A66B'"
                            onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.color='rgba(240,232,222,0.5)'">
                            <span v-html="s.icon" class="social-icon"></span>
                        </a>
                    </div>
                </div>

                <!-- Explore -->
                <div>
                    <h4 class="f-heading">Explore</h4>
                    <ul class="space-y-2.5">
                        <li v-for="link in quickLinks" :key="link.to">
                            <RouterLink :to="link.to" class="f-link">{{ link.label }}</RouterLink>
                        </li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="f-heading">Categories</h4>
                    <ul class="space-y-2.5">
                        <li v-for="cat in footerCats" :key="cat.to">
                            <RouterLink :to="cat.to" class="f-link">{{ cat.label }}</RouterLink>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-span-2 md:col-span-1">
                    <h4 class="f-heading">Contact</h4>
                    <div class="space-y-3">
                        <a v-for="c in contacts" :key="c.label" :href="c.href"
                            class="flex items-start gap-3 group">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 text-sm"
                                style="background:rgba(201,166,107,0.1)">
                                {{ c.icon }}
                            </div>
                            <span class="text-sm leading-relaxed transition-colors group-hover:text-amber-300"
                                style="color:rgba(240,232,222,0.5)">{{ c.value }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div style="border-top:1px solid rgba(255,255,255,0.05)">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs" style="color:rgba(240,232,222,0.3)">
                    © {{ year }} LuxuryFurnish. All rights reserved. Made with ❤️ in Bangladesh.
                </p>
                <div class="flex items-center gap-5">
                    <RouterLink to="/privacy-policy" class="f-link text-xs">Privacy Policy</RouterLink>
                    <RouterLink to="/terms" class="f-link text-xs">Terms of Service</RouterLink>
                    <RouterLink to="/faq" class="f-link text-xs">FAQ</RouterLink>
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
    { to: '/',                label: 'Home' },
    { to: '/shop',            label: 'Shop All' },
    { to: '/about',           label: 'About Us' },
    { to: '/custom-furniture',label: 'Custom Orders' },
    { to: '/track-order',     label: 'Track Order' },
    { to: '/contact',         label: 'Contact Us' },
]

const footerCats = [
    { to: '/shop/living-room-furniture', label: 'Living Room' },
    { to: '/shop/bedroom-furniture',     label: 'Bedroom' },
    { to: '/shop/dining-furniture',      label: 'Dining' },
    { to: '/shop/office-furniture',      label: 'Office' },
    { to: '/shop/storage-cabinets',      label: 'Storage' },
    { to: '/shop/home-decor',            label: 'Home Decor' },
]

const contacts = [
    { label: 'Address', icon: '📍', href: '#',                                    value: '123 Furniture Street, Dhaka 1200, BD' },
    { label: 'Phone',   icon: '📞', href: 'tel:+8801700000000',                   value: '+880 1700-000000' },
    { label: 'Email',   icon: '✉️', href: 'mailto:hello@luxuryfurnish.com',        value: 'hello@luxuryfurnish.com' },
    { label: 'Hours',   icon: '🕐', href: '#',                                    value: 'Sat–Thu: 10AM – 8PM' },
]

const socials = [
    {
        name: 'Facebook',
        href: '#',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>`,
    },
    {
        name: 'Instagram',
        href: '#',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>`,
    },
    {
        name: 'YouTube',
        href: '#',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>`,
    },
]

function subscribeNewsletter() {
    if (!newsletterEmail.value.trim()) return
    toast.success("You're subscribed! Welcome to the family 🎉")
    newsletterEmail.value = ''
}
</script>

<style scoped>
.f-heading {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(240, 232, 222, 0.35);
    margin-bottom: 14px;
}

.f-link {
    display: inline-block;
    font-size: 13px;
    font-weight: 400;
    color: rgba(240, 232, 222, 0.52);
    transition: color 0.2s ease;
    text-decoration: none;
    line-height: 1.4;
}
.f-link:hover { color: #C9A66B; }

.social-icon :deep(svg) { width: 16px; height: 16px; }
</style>
