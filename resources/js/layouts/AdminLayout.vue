<template>
    <div class="adm-shell">

        <!-- Mobile overlay -->
        <div v-if="mobileOpen" class="adm-overlay" @click="mobileOpen = false"></div>

        <!-- ════ SIDEBAR ════ -->
        <aside class="adm-sidebar" :class="{ collapsed: !open, 'mobile-open': mobileOpen }">

            <!-- Logo -->
            <div class="adm-logo">
                <div class="adm-logo-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </div>
                <Transition name="fade-label">
                    <div v-if="open" class="adm-logo-text">
                        <span>Luxury<span style="color:#C9A055">Furnish</span></span>
                        <span class="adm-logo-sub">Admin Panel</span>
                    </div>
                </Transition>
            </div>

            <!-- Nav -->
            <nav class="adm-nav">
                <div v-for="section in navSections" :key="section.label" class="adm-nav-section">
                    <Transition name="fade-label">
                        <span v-if="open" class="adm-section-label">{{ section.label }}</span>
                    </Transition>
                    <RouterLink v-for="item in section.items" :key="item.to"
                        :to="item.to"
                        class="adm-nav-link"
                        :class="{ active: $route.path.startsWith(item.to) }"
                        :title="!open ? item.label : ''">
                        <span class="adm-nav-icon" v-html="item.icon"></span>
                        <Transition name="fade-label">
                            <span v-if="open" class="adm-nav-text">{{ item.label }}</span>
                        </Transition>
                        <Transition name="fade-label">
                            <span v-if="open && item.badge" class="adm-nav-badge">{{ item.badge }}</span>
                        </Transition>
                        <!-- Tooltip when collapsed -->
                        <span v-if="!open" class="adm-tooltip">{{ item.label }}</span>
                    </RouterLink>
                </div>
            </nav>

            <!-- Footer -->
            <div class="adm-sidebar-footer">
                <div class="adm-user">
                    <img :src="`https://ui-avatars.com/api/?name=${user?.name || 'A'}&background=8B5E3C&color=fff&size=32`"
                        class="adm-user-avatar">
                    <Transition name="fade-label">
                        <div v-if="open" class="adm-user-info">
                            <span class="adm-user-name">{{ user?.name || 'Admin' }}</span>
                            <button @click="logout" class="adm-user-logout">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sign out
                            </button>
                        </div>
                    </Transition>
                </div>
            </div>
        </aside>

        <!-- ════ MAIN ════ -->
        <div class="adm-main" :class="{ 'ml-sidebar': open, 'ml-sidebar-sm': !open }">

            <!-- Top bar -->
            <header class="adm-topbar">
                <div class="adm-topbar-left">
                    <button @click="toggleSidebar" class="adm-toggle-btn" title="Toggle Sidebar">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="adm-breadcrumb">
                        <span class="adm-breadcrumb-root">Admin</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#CBD5E1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="adm-breadcrumb-current">{{ currentPageName }}</span>
                    </div>
                </div>

                <div class="adm-topbar-right">
                    <RouterLink to="/" target="_blank" class="adm-view-store">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View Store
                    </RouterLink>
                    <div class="adm-avatar-sm">
                        <img :src="`https://ui-avatars.com/api/?name=${user?.name || 'A'}&background=8B5E3C&color=fff&size=28`"
                            class="w-7 h-7 rounded-full block">
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="adm-content">
                <RouterView />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'
import '../../css/admin.css'

const router    = useRouter()
const route     = useRoute()
const authStore = useAuthStore()

const open = ref(true)
const mobileOpen = ref(false)
const user = computed(() => authStore.currentUser)

function toggleSidebar() {
    if (window.innerWidth <= 900) {
        mobileOpen.value = !mobileOpen.value
    } else {
        open.value = !open.value
    }
}

watch(() => route.path, () => { mobileOpen.value = false })

const currentPageName = computed(() => {
    const name = route.name?.replace('admin-', '') || 'Dashboard'
    return name.charAt(0).toUpperCase() + name.slice(1)
})

const navSections = [
    {
        label: 'Overview',
        items: [
            {
                to: '/admin/dashboard', label: 'Dashboard',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.8"/><rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.8"/><rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.8"/><rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.8"/></svg>`
            },
        ]
    },
    {
        label: 'Catalog',
        items: [
            {
                to: '/admin/products', label: 'Products',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>`
            },
            {
                to: '/admin/categories', label: 'Categories',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>`
            },
        ]
    },
    {
        label: 'Sales',
        items: [
            {
                to: '/admin/orders', label: 'Orders',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`
            },
            {
                to: '/admin/customers', label: 'Customers',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
            },
            {
                to: '/admin/coupons', label: 'Coupons',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>`
            },
        ]
    },
    {
        label: 'Content',
        items: [
            {
                to: '/admin/banners', label: 'Banners',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`
            },
            {
                to: '/admin/reviews', label: 'Reviews',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`
            },
            {
                to: '/admin/inquiries', label: 'Legacy Inquiries',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
            },
            {
                to: '/admin/messages', label: 'Messages',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
            },
        ]
    },
    {
        label: 'Custom Furniture',
        items: [
            {
                to: '/admin/wood-types', label: 'Wood Types',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2L4 12h3l-3 5h6v5h4v-5h6l-3-5h3L12 2z"/></svg>`
            },
            {
                to: '/admin/furniture-types', label: 'Furniture Types',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 11V5a2 2 0 012-2h10a2 2 0 012 2v6M5 11h14M5 11v8m14-8v8M5 19h14M9 19v2m6-2v2"/></svg>`
            },
        ]
    },
    {
        label: 'System',
        items: [
            {
                to: '/admin/settings', label: 'Settings',
                icon: `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>`
            },
        ]
    },
]

async function logout() {
    await authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
/* ── Shell ───────────────────────────────────────────── */
.adm-shell {
    display: flex;
    min-height: 100vh;
    background: #F1F5F9;
    font-family: 'Inter', sans-serif;
}

/* ── Sidebar ─────────────────────────────────────────── */
.adm-sidebar {
    position: fixed;
    top: 0; left: 0; bottom: 0;
    width: 240px;
    display: flex;
    flex-direction: column;
    z-index: 40;
    transition: width 0.26s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    background: #141414;
}
.adm-sidebar.collapsed { width: 60px; }

/* ── Mobile overlay & drawer ──────────────────────────── */
.adm-overlay {
    display: none;
}
@media (max-width: 900px) {
    .adm-sidebar, .adm-sidebar.collapsed {
        width: 240px;
        transform: translateX(-100%);
        box-shadow: 0 0 40px rgba(0,0,0,0.25);
    }
    .adm-sidebar.mobile-open {
        transform: translateX(0);
    }
    .adm-overlay {
        display: block;
        position: fixed; inset: 0;
        background: rgba(15,23,42,0.45);
        z-index: 39;
        animation: fade-in 0.18s ease;
    }
    .ml-sidebar, .ml-sidebar-sm { margin-left: 0; }
}
@keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }

/* ── Logo ─────────────────────────────────────────────── */
.adm-logo {
    height: 60px;
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 0 14px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}
.adm-logo-icon {
    width: 32px; height: 32px; border-radius: 9px;
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C);
    display: flex; align-items: center; justify-content: center;
    color: white; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(92, 46, 10, 0.5);
}
.adm-logo-text {
    display: flex; flex-direction: column; gap: 1px;
    overflow: hidden; white-space: nowrap;
    font-family: 'Playfair Display', serif;
    font-size: 15px; font-weight: 800;
    color: #FFFFFF; letter-spacing: -0.02em;
}
.adm-logo-sub {
    font-family: 'Inter', sans-serif;
    font-size: 9px; font-weight: 500;
    letter-spacing: 0.14em; text-transform: uppercase;
    color: rgba(255,255,255,0.3);
}

/* ── Nav ──────────────────────────────────────────────── */
.adm-nav {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 12px 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.adm-nav::-webkit-scrollbar { width: 2px; }
.adm-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }

.adm-nav-section {
    margin-bottom: 8px;
}
.adm-section-label {
    display: block;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.25);
    padding: 8px 10px 4px;
    white-space: nowrap;
}

.adm-nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 10px;
    border-radius: 8px;
    text-decoration: none;
    color: rgba(255,255,255,0.55);
    font-size: 13.5px;
    font-weight: 500;
    transition: all 0.18s ease;
    white-space: nowrap;
    position: relative;
    overflow: visible;
}
.adm-nav-link:hover {
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.9);
}
.adm-nav-link.active {
    background: rgba(201, 160, 85, 0.15);
    color: #E8C47A;
}
.adm-nav-link.active .adm-nav-icon :deep(svg),
.adm-nav-link.active .adm-nav-icon :deep(path),
.adm-nav-link.active .adm-nav-icon :deep(circle),
.adm-nav-link.active .adm-nav-icon :deep(rect) { stroke: #E8C47A; }

.adm-nav-icon {
    width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: inherit;
}
.adm-nav-icon :deep(svg) { stroke: currentColor; }

.adm-nav-text { flex: 1; overflow: hidden; text-overflow: ellipsis; }

.adm-nav-badge {
    background: #E53E3E; color: white;
    font-size: 9px; font-weight: 700;
    padding: 1px 5px; border-radius: 99px;
    flex-shrink: 0;
}

/* Tooltip on collapsed */
.adm-tooltip {
    position: absolute;
    left: calc(100% + 12px);
    top: 50%; transform: translateY(-50%);
    background: #1E293B; color: white;
    font-size: 12px; font-weight: 500;
    padding: 5px 10px; border-radius: 6px;
    white-space: nowrap; pointer-events: none;
    opacity: 0;
    transition: opacity 0.15s ease;
    z-index: 100;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.adm-nav-link:hover .adm-tooltip { opacity: 1; }

/* ── Sidebar Footer ───────────────────────────────────── */
.adm-sidebar-footer {
    padding: 12px 10px;
    border-top: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}
.adm-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    border-radius: 8px;
    transition: background 0.18s ease;
}
.adm-user:hover { background: rgba(255,255,255,0.05); }
.adm-user-avatar {
    width: 30px; height: 30px;
    border-radius: 50%; flex-shrink: 0;
    border: 1.5px solid rgba(201, 160, 85, 0.3);
}
.adm-user-info {
    display: flex; flex-direction: column; gap: 1px;
    overflow: hidden; flex: 1;
    white-space: nowrap;
}
.adm-user-name {
    font-size: 12.5px; font-weight: 600;
    color: rgba(255,255,255,0.85);
    overflow: hidden; text-overflow: ellipsis;
}
.adm-user-logout {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; color: rgba(255,255,255,0.35);
    background: none; border: none; cursor: pointer;
    padding: 0; transition: color 0.15s ease;
}
.adm-user-logout:hover { color: #F87171; }

/* ── Main Area ────────────────────────────────────────── */
.adm-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    transition: margin-left 0.26s cubic-bezier(0.4, 0, 0.2, 1);
}
.ml-sidebar    { margin-left: 240px; }
.ml-sidebar-sm { margin-left: 60px; }

/* ── Topbar ───────────────────────────────────────────── */
.adm-topbar {
    position: sticky; top: 0; z-index: 30;
    height: 60px;
    background: #FFFFFF;
    border-bottom: 1px solid #E8EEF4;
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.adm-topbar-left { display: flex; align-items: center; gap: 14px; }
.adm-topbar-right { display: flex; align-items: center; gap: 12px; }

.adm-toggle-btn {
    width: 34px; height: 34px; border-radius: 8px;
    background: transparent; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #64748B; transition: all 0.18s ease;
}
.adm-toggle-btn:hover { background: #F1F5F9; color: #334155; }

.adm-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px;
}
.adm-breadcrumb-root { color: #94A3B8; font-weight: 400; }
.adm-breadcrumb-current { color: #1E293B; font-weight: 600; }

.adm-view-store {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12.5px; font-weight: 600;
    color: #8B5E3C; text-decoration: none;
    padding: 7px 14px; border-radius: 8px;
    background: rgba(139, 94, 60, 0.08);
    transition: all 0.18s ease;
    border: 1px solid rgba(139,94,60,0.15);
}
.adm-view-store:hover { background: rgba(139,94,60,0.14); }

.adm-avatar-sm { cursor: pointer; }

/* ── Content ──────────────────────────────────────────── */
.adm-content {
    flex: 1;
    padding: 28px 28px 48px;
    min-width: 0;
}
@media (max-width: 640px) {
    .adm-content { padding: 20px 16px 40px; }
}

/* ── Transitions ──────────────────────────────────────── */
.fade-label-enter-active { transition: opacity 0.18s ease, transform 0.18s ease; }
.fade-label-leave-active { transition: opacity 0.12s ease; }
.fade-label-enter-from   { opacity: 0; transform: translateX(-6px); }
.fade-label-leave-to     { opacity: 0; }
</style>
