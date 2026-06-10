<template>
    <header>
        <!-- Fixed navbar -->
        <nav class="nav-bar" :class="scrolled ? 'nav-scrolled' : 'nav-top'">
            <div class="nav-inner">

                <!-- Logo -->
                <RouterLink to="/" class="logo-wrap">
                    <div class="logo-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                    <div class="logo-text">
                        <span class="logo-name" :class="scrolled ? 'clr-dark' : 'clr-white'">
                            Luxury<span class="logo-accent">Furnish</span>
                        </span>
                        <span class="logo-tagline" :class="scrolled ? 'clr-muted' : 'clr-faint'">Premium Furniture</span>
                    </div>
                </RouterLink>

                <!-- Desktop Nav Links -->
                <ul class="nav-links hidden lg:flex">
                    <li v-for="link in navLinks" :key="link.to">
                        <RouterLink :to="link.to"
                            class="nav-link"
                            :class="scrolled ? 'nav-link-dark' : 'nav-link-light'">
                            {{ link.label }}
                        </RouterLink>
                    </li>
                </ul>

                <!-- Actions -->
                <div class="nav-actions">
                    <!-- Search -->
                    <button @click="uiStore.openSearch()"
                        class="action-btn"
                        :class="scrolled ? 'action-dark' : 'action-light'"
                        title="Search">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Wishlist (auth only) -->
                    <RouterLink v-if="isAuth" to="/wishlist"
                        class="action-btn relative"
                        :class="scrolled ? 'action-dark' : 'action-light'"
                        title="Wishlist">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span v-if="wishlistCount" class="notif-dot">{{ wishlistCount > 9 ? '9+' : wishlistCount }}</span>
                    </RouterLink>

                    <!-- Cart -->
                    <button @click="cartStore.toggleCart()"
                        class="action-btn relative"
                        :class="scrolled ? 'action-dark' : 'action-light'"
                        title="Cart">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span v-if="cartCount" class="notif-dot">{{ cartCount > 9 ? '9+' : cartCount }}</span>
                    </button>

                    <!-- Build Your Furniture CTA (desktop only) -->
                    <RouterLink to="/build-your-furniture-preview"
                        class="build-cta hidden lg:inline-flex"
                        :class="scrolled ? 'build-cta-dark' : 'build-cta-light'"
                        title="Build Your Furniture">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span>Build Yours</span>
                    </RouterLink>

                    <!-- Account / Login -->
                    <div class="hidden lg:block">
                        <RouterLink v-if="isAuth" to="/account"
                            class="account-avatar"
                            title="My Account">
                            <img :src="`https://ui-avatars.com/api/?name=${user?.name || 'U'}&background=8B5E3C&color=fff&size=36`"
                                :alt="user?.name" class="w-8 h-8 rounded-full block">
                        </RouterLink>
                        <RouterLink v-else to="/login" class="login-btn"
                            :class="scrolled ? 'login-dark' : 'login-light'">
                            Sign In
                        </RouterLink>
                    </div>

                    <!-- Mobile hamburger -->
                    <button @click="mobileOpen = !mobileOpen"
                        class="action-btn lg:hidden"
                        :class="scrolled ? 'action-dark' : 'action-light'">
                        <svg v-if="!mobileOpen" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4 6h16M4 12h16M4 18h10"/>
                        </svg>
                        <svg v-else width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Spacer -->
        <div class="h-[66px] nav-spacer"></div>

        <!-- Mobile Menu Overlay -->
        <Transition name="mobile-menu">
            <div v-if="mobileOpen"
                class="fixed inset-0 z-[200]"
                style="background:rgba(17,17,9,0.55); backdrop-filter:blur(4px)"
                @click.self="mobileOpen = false">

                <div class="mobile-panel" @click.stop>
                    <div class="flex items-center justify-between p-5 border-b"
                        style="border-color:#EDE5D8">
                        <span style="font-family:'Playfair Display',serif; font-size:18px; font-weight:700; color:#111109">Menu</span>
                        <button @click="mobileOpen = false" class="action-btn action-dark">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 overflow-y-auto flex-1">
                        <nav class="space-y-1 mb-4">
                            <RouterLink v-for="link in navLinks" :key="link.to" :to="link.to"
                                @click="mobileOpen = false"
                                class="mobile-nav-link">
                                {{ link.label }}
                            </RouterLink>
                            <RouterLink to="/build-your-furniture-preview"
                                @click="mobileOpen = false"
                                class="mobile-nav-link">
                                Build Your Furniture
                            </RouterLink>
                        </nav>

                        <div class="divider my-4"></div>

                        <div class="space-y-3">
                            <RouterLink v-if="isAuth" to="/account" @click="mobileOpen = false"
                                class="btn-primary w-full" style="justify-content:center">My Account</RouterLink>
                            <template v-else>
                                <RouterLink to="/login" @click="mobileOpen = false"
                                    class="btn-primary w-full" style="justify-content:center">Sign In</RouterLink>
                                <RouterLink to="/register" @click="mobileOpen = false"
                                    class="btn-secondary w-full" style="justify-content:center">Create Account</RouterLink>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useCartStore }     from '../stores/cart.js'
import { useWishlistStore } from '../stores/wishlist.js'
import { useAuthStore }     from '../stores/auth.js'
import { useUiStore }       from '../stores/ui.js'

const cartStore     = useCartStore()
const wishlistStore = useWishlistStore()
const authStore     = useAuthStore()
const uiStore       = useUiStore()

const scrolled   = ref(false)
const mobileOpen = ref(false)

const isAuth        = computed(() => authStore.isAuthenticated)
const user          = computed(() => authStore.currentUser)
const cartCount     = computed(() => cartStore.count)
const wishlistCount = computed(() => wishlistStore.count)

const navLinks = [
    { to: '/',                           label: 'Home' },
    { to: '/shop',                       label: 'Shop All' },
    { to: '/shop/living-room-furniture', label: 'Living Room' },
    { to: '/shop/bedroom-furniture',     label: 'Bedroom' },
    { to: '/about',                      label: 'About' },
    { to: '/contact',                    label: 'Contact' },
]

function handleScroll() { scrolled.value = window.scrollY > 50 }
onMounted(() => window.addEventListener('scroll', handleScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))
</script>

<style scoped>
.nav-bar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    transition: background 0.35s ease, box-shadow 0.35s ease;
    height: 66px;
}
.nav-top     { background: transparent; box-shadow: none; }
.nav-scrolled {
    background: rgba(253, 250, 246, 0.97);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    box-shadow: 0 1px 0 rgba(92,46,10,0.08), 0 4px 20px rgba(17,17,9,0.06);
}

.nav-inner {
    max-width: 1280px; margin: 0 auto; height: 66px;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 20px; gap: 16px;
}

.logo-wrap {
    display: flex; align-items: center; gap: 10px;
    text-decoration: none; flex-shrink: 0;
}
.logo-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C);
    display: flex; align-items: center; justify-content: center;
    color: white; flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(92,46,10,0.4);
}
.logo-text   { display: flex; flex-direction: column; line-height: 1; gap: 2px; }
.logo-name   { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 800; letter-spacing: -0.02em; transition: color 0.3s ease; }
.logo-accent { color: #C9A055; }
.logo-tagline { font-size: 9px; font-weight: 500; letter-spacing: 0.16em; text-transform: uppercase; transition: color 0.3s ease; }

.clr-dark  { color: #111109; }
.clr-white { color: #ffffff; }
.clr-muted { color: #7A6E66; }
.clr-faint { color: rgba(255,255,255,0.5); }

.nav-links   { list-style: none; gap: 2px; align-items: center; }
.nav-link {
    display: inline-block; padding: 7px 13px; border-radius: 10px;
    font-size: 13.5px; font-weight: 500; text-decoration: none;
    transition: all 0.2s ease; white-space: nowrap;
}
.nav-link-light { color: rgba(255,255,255,0.88); }
.nav-link-light:hover { color: #fff; background: rgba(255,255,255,0.12); }
.nav-link-dark  { color: #4A3A30; }
.nav-link-dark:hover { color: #8B5E3C; background: rgba(139,94,60,0.08); }
.nav-link.router-link-active.nav-link-dark  { color: #8B5E3C; font-weight: 600; }
.nav-link.router-link-active.nav-link-light { color: #fff; font-weight: 600; }

.nav-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
.action-btn {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; background: transparent;
    transition: all 0.2s ease; position: relative; flex-shrink: 0;
}
.action-light { color: rgba(255,255,255,0.9); }
.action-light:hover { background: rgba(255,255,255,0.15); color: #fff; }
.action-dark  { color: #4A3A30; }
.action-dark:hover  { background: rgba(139,94,60,0.08); color: #8B5E3C; }

.notif-dot {
    position: absolute; top: 3px; right: 3px;
    min-width: 17px; height: 17px; border-radius: 99px;
    background: #D94040; color: white; font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px; border: 1.5px solid white;
}

.account-avatar { display: block; border-radius: 50%; text-decoration: none; transition: box-shadow 0.2s ease; }
.account-avatar:hover { box-shadow: 0 0 0 3px #C9A055; }

.login-btn {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 8px 18px; border-radius: 50px;
    font-size: 13.5px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: all 0.22s ease; white-space: nowrap;
}
.login-light {
    background: rgba(255,255,255,0.15); color: #fff;
    border: 1px solid rgba(255,255,255,0.25);
}
.login-light:hover { background: rgba(255,255,255,0.25); }
.login-dark {
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C); color: #fff;
    box-shadow: 0 2px 10px rgba(92,46,10,0.3);
}
.login-dark:hover { box-shadow: 0 4px 16px rgba(92,46,10,0.45); filter: brightness(1.08); }

.build-cta {
    align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 50px; margin-right: 2px;
    font-size: 12.5px; font-weight: 600;
    cursor: pointer; text-decoration: none; white-space: nowrap;
    transition: all 0.22s ease;
}
.build-cta-light {
    background: rgba(255,255,255,0.15); color: #fff;
    border: 1px solid rgba(255,255,255,0.25);
}
.build-cta-light:hover { background: rgba(255,255,255,0.25); }
.build-cta-dark {
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C); color: #fff;
    box-shadow: 0 2px 10px rgba(92,46,10,0.3);
}
.build-cta-dark:hover { box-shadow: 0 4px 16px rgba(92,46,10,0.45); filter: brightness(1.08); }

.mobile-panel {
    position: absolute; top: 0; right: 0; bottom: 0;
    width: min(360px, 100vw); background: #FDFAF6;
    display: flex; flex-direction: column;
    box-shadow: -8px 0 32px rgba(17,17,9,0.12);
}
.mobile-nav-link {
    display: flex; align-items: center;
    padding: 12px 14px; border-radius: 12px;
    font-size: 15px; font-weight: 500; color: #2C1A0C;
    text-decoration: none; transition: all 0.18s ease;
}
.mobile-nav-link:hover { background: #F0E8DC; color: #8B5E3C; padding-left: 18px; }
.mobile-nav-link.router-link-active { background: rgba(139,94,60,0.1); color: #8B5E3C; font-weight: 600; }

.mobile-menu-enter-active { transition: opacity 0.25s ease; }
.mobile-menu-leave-active { transition: opacity 0.2s ease; }
.mobile-menu-enter-from, .mobile-menu-leave-to { opacity: 0; }
.mobile-menu-enter-active .mobile-panel { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
.mobile-menu-leave-active .mobile-panel { transition: transform 0.25s cubic-bezier(0.4,0,0.2,1); }
.mobile-menu-enter-from .mobile-panel,
.mobile-menu-leave-to   .mobile-panel   { transform: translateX(100%); }
</style>
