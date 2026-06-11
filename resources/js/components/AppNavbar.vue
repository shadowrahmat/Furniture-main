<template>
    <header>
        <!-- Fixed navbar -->
        <nav class="nav-bar" :class="showSolidNav ? 'nav-scrolled' : 'nav-top'">
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
                        <span class="logo-name" :class="showSolidNav ? 'clr-dark' : 'clr-white'">
                            Luxury<span class="logo-accent">Furnish</span>
                        </span>
                        <span class="logo-tagline" :class="showSolidNav ? 'clr-muted' : 'clr-faint'">Premium Furniture</span>
                    </div>
                </RouterLink>

                <!-- Desktop Nav Links -->
                <ul class="nav-links hidden lg:flex">
                    <li v-for="link in navLinks" :key="link.to">
                        <RouterLink :to="link.to"
                            class="nav-link"
                            :class="showSolidNav ? 'nav-link-dark' : 'nav-link-light'">
                            {{ link.label }}
                        </RouterLink>
                    </li>
                </ul>

                <!-- Actions -->
                <div class="nav-actions">
                    <!-- Search -->
                    <button @click="uiStore.openSearch()"
                        class="action-btn"
                        :class="showSolidNav ? 'action-dark' : 'action-light'"
                        title="Search">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Wishlist (auth only) -->
                    <RouterLink v-if="isAuth" to="/wishlist"
                        class="action-btn relative"
                        :class="showSolidNav ? 'action-dark' : 'action-light'"
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
                        :class="showSolidNav ? 'action-dark' : 'action-light'"
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
                        :class="showSolidNav ? 'build-cta-dark' : 'build-cta-light'"
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
                            :class="showSolidNav ? 'login-dark' : 'login-light'">
                            Sign In
                        </RouterLink>
                    </div>

                    <!-- Mobile/tablet hamburger — hidden on desktop (lg+) -->
                    <button @click="mobileOpen = !mobileOpen"
                        class="hamburger-btn flex lg:hidden"
                        :class="showSolidNav ? 'action-dark' : 'action-light'"
                        :aria-expanded="mobileOpen"
                        aria-controls="mobile-nav-menu"
                        :aria-label="mobileOpen ? 'Close menu' : 'Open menu'">
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
                style="background:rgba(17,17,9,0.45); backdrop-filter:blur(8px) saturate(140%); -webkit-backdrop-filter:blur(8px) saturate(140%)"
                @click.self="mobileOpen = false">

                <div id="mobile-nav-menu" class="mobile-panel" @click.stop>
                    <div class="flex items-center justify-between p-5 mobile-panel-header">
                        <span style="font-family:'Playfair Display',serif; font-size:18px; font-weight:700; color:#111109">Menu</span>
                        <button @click="mobileOpen = false" class="action-btn action-dark" aria-label="Close menu">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 overflow-y-auto flex-1">
                        <nav class="space-y-1 mb-4" aria-label="Main">
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
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useCartStore }     from '../stores/cart.js'
import { useWishlistStore } from '../stores/wishlist.js'
import { useAuthStore }     from '../stores/auth.js'
import { useUiStore }       from '../stores/ui.js'

const route         = useRoute()
const cartStore     = useCartStore()
const wishlistStore = useWishlistStore()
const authStore     = useAuthStore()
const uiStore       = useUiStore()

const scrolled   = ref(false)
const mobileOpen = ref(false)

// Only the Home page has a dark hero positioned under the navbar at the top,
// so only Home may show the transparent/light variant before scrolling.
const isHome = computed(() => route.path === '/')
const showSolidNav = computed(() => scrolled.value || !isHome.value)

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

function handleKeydown(e) {
    if (e.key === 'Escape' && mobileOpen.value) mobileOpen.value = false
}

onMounted(() => {
    handleScroll()
    window.addEventListener('scroll', handleScroll, { passive: true })
    window.addEventListener('keydown', handleKeydown)
})
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
    window.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = ''
})

// Lock body scroll while the mobile menu overlay is open
watch(mobileOpen, (open) => {
    document.body.style.overflow = open ? 'hidden' : ''
})

// Recalculate navbar state immediately on every route change
watch(() => route.path, () => {
    mobileOpen.value = false
    handleScroll()
})
</script>

<style scoped>
.nav-bar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    height: 66px;
    border-radius: 0 0 24px 24px;
    transition: background 0.45s ease, box-shadow 0.45s ease, border-color 0.45s ease, backdrop-filter 0.45s ease;
}
/* Liquid glass sheen — soft specular highlight across the glass surface */
.nav-bar::before {
    content: '';
    position: absolute; inset: 0;
    border-radius: inherit;
    background: radial-gradient(120% 160% at 18% -30%, rgba(255,255,255,0.55), transparent 55%);
    mix-blend-mode: soft-light;
    opacity: 0.5;
    pointer-events: none;
    transition: opacity 0.45s ease;
    z-index: 0;
}
.nav-top {
    background: linear-gradient(180deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.02) 100%);
    backdrop-filter: blur(14px) saturate(160%);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.22), inset 0 -1px 0 rgba(255,255,255,0.06);
}
.nav-top::before { opacity: 0.32; }

.nav-scrolled {
    background: linear-gradient(180deg, rgba(255,255,255,0.78) 0%, rgba(253,250,246,0.6) 100%);
    backdrop-filter: blur(28px) saturate(200%);
    -webkit-backdrop-filter: blur(28px) saturate(200%);
    border-bottom: 1px solid rgba(255,255,255,0.5);
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.9),
        inset 0 -1px 0 rgba(255,255,255,0.15),
        0 12px 36px rgba(92,46,10,0.10),
        0 2px 10px rgba(17,17,9,0.05);
    animation: navGlassIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes navGlassIn {
    from { opacity: 0; transform: translateY(-6px) scale(0.99); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

.nav-inner {
    position: relative; z-index: 1;
    max-width: 1280px; margin: 0 auto; height: 66px;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 20px; gap: 16px;
}

.logo-wrap {
    display: flex; align-items: center; gap: 10px;
    text-decoration: none; flex-shrink: 0;
}
.logo-icon {
    width: 38px; height: 38px; border-radius: 12px;
    background: linear-gradient(145deg, #6B3815 0%, #8B5E3C 55%, #D9B074 130%);
    display: flex; align-items: center; justify-content: center;
    color: white; flex-shrink: 0; position: relative; overflow: hidden;
    box-shadow:
        inset 0 1px 1px rgba(255,255,255,0.55),
        inset 0 -3px 6px rgba(0,0,0,0.22),
        0 4px 14px rgba(92,46,10,0.4);
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1), box-shadow 0.35s ease;
}
/* Glossy highlight cap, like light catching the top of a glass surface */
.logo-icon::after {
    content: '';
    position: absolute; top: -40%; left: -25%; right: -25%; height: 70%;
    background: linear-gradient(rgba(255,255,255,0.5), rgba(255,255,255,0));
    border-radius: 50%;
    transform: rotate(-6deg);
}
.logo-icon svg { position: relative; z-index: 1; }
.logo-wrap:hover .logo-icon {
    transform: scale(1.07) rotate(-3deg);
    box-shadow:
        inset 0 1px 1px rgba(255,255,255,0.6),
        inset 0 -3px 6px rgba(0,0,0,0.22),
        0 6px 20px rgba(92,46,10,0.5);
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
.nav-link-light:hover {
    color: #fff;
    background: rgba(255,255,255,0.14);
    backdrop-filter: blur(10px) saturate(160%);
    -webkit-backdrop-filter: blur(10px) saturate(160%);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.3);
}
.nav-link-dark  { color: #4A3A30; }
.nav-link-dark:hover {
    color: #8B5E3C;
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(8px) saturate(160%);
    -webkit-backdrop-filter: blur(8px) saturate(160%);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
}
.nav-link.router-link-active.nav-link-dark {
    color: #8B5E3C; font-weight: 600;
    background: rgba(201,160,85,0.16);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.7), inset 0 0 0 1px rgba(201,160,85,0.18);
}
.nav-link.router-link-active.nav-link-light {
    color: #fff; font-weight: 600;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px) saturate(160%);
    -webkit-backdrop-filter: blur(10px) saturate(160%);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.35);
}

.nav-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
.action-btn {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid transparent; cursor: pointer; background: transparent;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1); position: relative; flex-shrink: 0;
}
.action-light { color: rgba(255,255,255,0.92); }
.action-light:hover {
    color: #fff;
    background: rgba(255,255,255,0.16);
    backdrop-filter: blur(12px) saturate(180%);
    -webkit-backdrop-filter: blur(12px) saturate(180%);
    border-color: rgba(255,255,255,0.3);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.45), 0 4px 16px rgba(0,0,0,0.14);
    transform: translateY(-1px) scale(1.06);
}
.action-dark  { color: #4A3A30; }
.action-dark:hover {
    color: #8B5E3C;
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(12px) saturate(180%);
    -webkit-backdrop-filter: blur(12px) saturate(180%);
    border-color: rgba(255,255,255,0.7);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.85), 0 4px 16px rgba(92,46,10,0.12);
    transform: translateY(-1px) scale(1.06);
}

/*
 * Hamburger (mobile/tablet only). Deliberately does NOT set `display` —
 * that's left to the `flex lg:hidden` Tailwind utilities on the element.
 * (.action-btn sets display:flex with [data-v-*] specificity that would
 * otherwise beat `lg:hidden`'s display:none and keep it visible on desktop.)
 */
.hamburger-btn {
    width: 38px; height: 38px; border-radius: 50%;
    align-items: center; justify-content: center;
    border: 1px solid transparent; cursor: pointer; background: transparent;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1); position: relative; flex-shrink: 0;
}
.hamburger-btn:active { transform: scale(0.94); }
.hamburger-btn:focus-visible {
    outline: 2px solid #C9A055; outline-offset: 2px;
}

.notif-dot {
    position: absolute; top: 3px; right: 3px;
    min-width: 17px; height: 17px; border-radius: 99px;
    background: #D94040; color: white; font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px; border: 1.5px solid white;
}

.account-avatar { display: block; border-radius: 50%; text-decoration: none; transition: box-shadow 0.25s ease, transform 0.25s ease; }
.account-avatar img { border: 2px solid rgba(255,255,255,0.6); }
.account-avatar:hover { box-shadow: 0 0 0 3px rgba(201,160,85,0.45), 0 4px 16px rgba(201,160,85,0.35); transform: translateY(-1px); }

/* Glassy pill buttons — top-half glare to mimic light refracting through glass */
.login-btn, .build-cta {
    position: relative; overflow: hidden; isolation: isolate;
}
.login-btn::before, .build-cta::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 55%;
    background: linear-gradient(rgba(255,255,255,0.35), rgba(255,255,255,0));
    pointer-events: none;
}

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
    backdrop-filter: blur(10px) saturate(160%);
    -webkit-backdrop-filter: blur(10px) saturate(160%);
}
.login-light:hover { background: rgba(255,255,255,0.25); }
.login-dark {
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C); color: #fff;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.25), 0 2px 10px rgba(92,46,10,0.3);
}
.login-dark:hover { box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 4px 16px rgba(92,46,10,0.45); filter: brightness(1.08); }

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
    backdrop-filter: blur(10px) saturate(160%);
    -webkit-backdrop-filter: blur(10px) saturate(160%);
}
.build-cta-light:hover { background: rgba(255,255,255,0.25); }
.build-cta-dark {
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C); color: #fff;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.25), 0 2px 10px rgba(92,46,10,0.3);
}
.build-cta-dark:hover { box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 4px 16px rgba(92,46,10,0.45); filter: brightness(1.08); }

.mobile-panel {
    position: absolute; top: 0; right: 0; bottom: 0;
    width: min(360px, 100vw);
    border-radius: 24px 0 0 24px;
    overflow: hidden;
    background: linear-gradient(180deg, rgba(255,253,249,0.88) 0%, rgba(253,250,246,0.76) 100%);
    backdrop-filter: blur(28px) saturate(200%);
    -webkit-backdrop-filter: blur(28px) saturate(200%);
    display: flex; flex-direction: column;
    border: 1px solid rgba(255,255,255,0.6);
    border-right: none;
    box-shadow:
        inset 1px 0 0 rgba(255,255,255,0.5),
        inset 0 1px 0 rgba(255,255,255,0.7),
        -8px 0 40px rgba(17,17,9,0.14);
}
.mobile-panel-header {
    border-bottom: 1px solid rgba(201,160,85,0.18);
    background: linear-gradient(180deg, rgba(255,255,255,0.35), rgba(255,255,255,0));
}
.mobile-nav-link {
    display: flex; align-items: center;
    padding: 13px 16px; border-radius: 14px;
    font-size: 15px; font-weight: 500; color: #2C1A0C;
    text-decoration: none; border: 1px solid transparent;
    transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
}
.mobile-nav-link:hover {
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(8px) saturate(160%);
    -webkit-backdrop-filter: blur(8px) saturate(160%);
    border-color: rgba(255,255,255,0.6);
    color: #8B5E3C;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
    transform: translateX(2px);
}
.mobile-nav-link.router-link-active {
    background: rgba(201,160,85,0.16);
    color: #8B5E3C; font-weight: 600;
    border-color: rgba(201,160,85,0.25);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
}
.mobile-nav-link:focus-visible {
    outline: 2px solid #C9A055; outline-offset: 2px;
}

.mobile-menu-enter-active { transition: opacity 0.25s ease; }
.mobile-menu-leave-active { transition: opacity 0.2s ease; }
.mobile-menu-enter-from, .mobile-menu-leave-to { opacity: 0; }
.mobile-menu-enter-active .mobile-panel { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
.mobile-menu-leave-active .mobile-panel { transition: transform 0.25s cubic-bezier(0.4,0,0.2,1); }
.mobile-menu-enter-from .mobile-panel,
.mobile-menu-leave-to   .mobile-panel   { transform: translateX(100%) scale(0.97); }

/* Respect reduced-motion preferences for this component's animations */
@media (prefers-reduced-motion: reduce) {
    .nav-scrolled,
    .hamburger-btn,
    .mobile-nav-link,
    .mobile-menu-enter-active,
    .mobile-menu-leave-active,
    .mobile-menu-enter-active .mobile-panel,
    .mobile-menu-leave-active .mobile-panel {
        animation: none;
        transition: none;
    }
}
</style>
