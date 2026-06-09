<template>
    <div class="home-page">

        <!-- ══════════════ HERO ══════════════ -->
        <section class="hero" style="margin-top:-66px">
            <!-- Background -->
            <div class="hero-bg">
                <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1600&q=85&auto=format&fit=crop"
                    alt="Luxury sofa" class="hero-bg-img" loading="eager">
                <div class="hero-overlay"></div>
            </div>

            <!-- Content -->
            <div class="hero-content">
                <div class="max-w-[640px]">
                    <div class="hero-eyebrow">
                        <span class="hero-eyebrow-dot"></span>
                        Handcrafted in Bangladesh
                    </div>
                    <h1 class="hero-title">
                        Where Homes
                        <span class="hero-title-accent">Come Alive</span>
                    </h1>
                    <p class="hero-sub">
                        Discover premium furniture crafted for modern living.
                        Timeless designs, exceptional quality.
                    </p>
                    <div class="hero-actions">
                        <RouterLink to="/shop" class="btn-primary" style="padding:15px 36px; font-size:15px">
                            Shop Collection
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </RouterLink>
                        <RouterLink to="/about" class="hero-ghost-btn">
                            Our Story
                        </RouterLink>
                    </div>

                    <!-- Stats row -->
                    <div class="hero-stats">
                        <div v-for="s in heroStats" :key="s.label" class="hero-stat">
                            <span class="hero-stat-num">{{ s.num }}</span>
                            <span class="hero-stat-label">{{ s.label }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="hero-scroll">
                <div class="hero-scroll-line"></div>
                <span>Scroll</span>
            </div>
        </section>

        <!-- ══════════════ MARQUEE ══════════════ -->
        <div class="marquee-band">
            <div class="marquee-track">
                <template v-for="i in 3" :key="i">
                    <span v-for="item in marqueeItems" :key="`${i}-${item}`" class="marquee-item">
                        <span class="marquee-dot">✦</span> {{ item }}
                    </span>
                </template>
            </div>
        </div>

        <!-- ══════════════ CATEGORIES ══════════════ -->
        <section class="section bg-white">
            <div class="container">
                <div class="section-header">
                    <div>
                        <div class="label-tag mb-3">Browse by Room</div>
                        <h2 class="section-title">Shop by Category</h2>
                    </div>
                    <RouterLink to="/shop" class="btn-outline hidden sm:inline-flex">View All</RouterLink>
                </div>

                <div class="cat-grid">
                    <RouterLink v-for="cat in categories" :key="cat.slug"
                        :to="`/shop/${cat.slug}`"
                        class="cat-card"
                        :style="`--cat-clr: ${cat.color}`">
                        <div class="cat-img-wrap">
                            <img :src="cat.image" :alt="cat.name" class="cat-img" loading="lazy">
                            <div class="cat-img-overlay"></div>
                        </div>
                        <div class="cat-info">
                            <span class="cat-emoji">{{ cat.icon }}</span>
                            <h3 class="cat-name">{{ cat.name }}</h3>
                            <span class="cat-count">{{ cat.count }}</span>
                        </div>
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- ══════════════ BEST SELLERS ══════════════ -->
        <section class="section" style="background:#FDFAF6">
            <div class="container">
                <div class="section-header">
                    <div>
                        <div class="label-tag mb-3">Trending Now</div>
                        <h2 class="section-title">Best Sellers</h2>
                        <p class="section-subtitle mt-2">Our most-loved pieces, handpicked for you.</p>
                    </div>
                    <RouterLink to="/shop?sort=popular" class="btn-outline hidden sm:inline-flex">See All</RouterLink>
                </div>

                <!-- Loading -->
                <div v-if="loadingBest" class="product-grid">
                    <div v-for="i in 4" :key="i" class="prod-skeleton"></div>
                </div>

                <!-- Products -->
                <div v-else-if="bestSellers.length" class="product-grid">
                    <ProductCard v-for="p in bestSellers" :key="p.id" :product="p"/>
                </div>

                <div v-else class="empty-state">No products found.</div>
            </div>
        </section>

        <!-- ══════════════ VALUE STRIP ══════════════ -->
        <section class="value-strip">
            <div class="container">
                <div class="value-grid">
                    <div v-for="v in values" :key="v.title" class="value-item">
                        <div class="value-icon">{{ v.icon }}</div>
                        <div>
                            <div class="value-title">{{ v.title }}</div>
                            <div class="value-desc">{{ v.desc }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ PROMO BANNER ══════════════ -->
        <section class="promo-banner">
            <div class="promo-bg">
                <img src="https://images.unsplash.com/photo-1618220179428-22790b461013?w=1400&q=80&auto=format&fit=crop"
                    alt="" class="promo-bg-img" loading="lazy">
                <div class="promo-overlay"></div>
            </div>
            <div class="container">
                <div class="promo-content">
                    <div class="label-tag mb-4" style="color:#C9A055">
                        <span style="background:linear-gradient(to right,#C9A055,#8B5E3C)"></span>
                        Limited Offer
                    </div>
                    <h2 class="promo-title">Custom Furniture<br>Made for You</h2>
                    <p class="promo-desc">Tell us your vision — we'll bring it to life.<br>Bespoke designs, delivered to your door.</p>
                    <RouterLink to="/custom-furniture" class="btn-primary" style="padding:14px 34px">
                        Start Your Order
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- ══════════════ NEW ARRIVALS ══════════════ -->
        <section class="section bg-white">
            <div class="container">
                <div class="section-header">
                    <div>
                        <div class="label-tag mb-3">Just In</div>
                        <h2 class="section-title">New Arrivals</h2>
                    </div>
                    <RouterLink to="/shop?sort=newest" class="btn-outline hidden sm:inline-flex">View All New</RouterLink>
                </div>

                <div v-if="loadingNew" class="product-grid">
                    <div v-for="i in 4" :key="i" class="prod-skeleton"></div>
                </div>
                <div v-else-if="newArrivals.length" class="product-grid">
                    <ProductCard v-for="p in newArrivals" :key="p.id" :product="p"/>
                </div>
                <div v-else class="empty-state">No new arrivals yet.</div>
            </div>
        </section>

        <!-- ══════════════ TESTIMONIALS ══════════════ -->
        <section class="section" style="background:#1E1208">
            <div class="container">
                <div class="text-center mb-10">
                    <div class="label-tag justify-center mb-3" style="color:#C9A055">
                        <span style="background:linear-gradient(to right,#C9A055,#8B5E3C);width:24px;height:2px;display:inline-block;border-radius:2px"></span>
                        Happy Customers
                    </div>
                    <h2 class="section-title" style="color:#FDFAF6">What Our Customers Say</h2>
                </div>

                <div class="testi-grid">
                    <div v-for="t in testimonials" :key="t.name" class="testi-card">
                        <div class="testi-stars">★★★★★</div>
                        <p class="testi-text">"{{ t.text }}"</p>
                        <div class="testi-author">
                            <div class="testi-avatar">{{ t.initials }}</div>
                            <div>
                                <div class="testi-name">{{ t.name }}</div>
                                <div class="testi-location">{{ t.location }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ WHY US ══════════════ -->
        <section class="section bg-white">
            <div class="container">
                <div class="text-center mb-10">
                    <div class="label-tag justify-center mb-3">Our Promise</div>
                    <h2 class="section-title">Why Choose LuxuryFurnish</h2>
                </div>

                <div class="why-grid">
                    <div v-for="w in whyUs" :key="w.title" class="why-card">
                        <div class="why-icon">{{ w.icon }}</div>
                        <h3 class="why-title">{{ w.title }}</h3>
                        <p class="why-desc">{{ w.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ SHOWROOM CTA ══════════════ -->
        <section class="showroom">
            <div class="container">
                <div class="showroom-card">
                    <div class="showroom-content">
                        <div class="label-tag mb-3">Visit Us</div>
                        <h2 class="section-title" style="color:#1E1208">See It in Person</h2>
                        <p class="section-subtitle mt-2">
                            Visit our showroom in Dhaka to experience our furniture firsthand.
                            Our design consultants are here to help you find the perfect fit.
                        </p>
                        <div class="flex gap-3 flex-wrap mt-6">
                            <RouterLink to="/contact" class="btn-primary">Get Directions</RouterLink>
                            <RouterLink to="/about" class="btn-secondary">Learn More</RouterLink>
                        </div>
                    </div>
                    <div class="showroom-img-wrap">
                        <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80&auto=format&fit=crop"
                            alt="Showroom" class="showroom-img" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ProductCard from '../components/ProductCard.vue'

const bestSellers = ref([])
const newArrivals = ref([])
const loadingBest = ref(true)
const loadingNew  = ref(true)

const heroStats = [
    { num: '500+', label: 'Products' },
    { num: '2K+',  label: 'Happy Customers' },
    { num: '4.9★', label: 'Avg Rating' },
    { num: 'Free', label: 'Delivery in Dhaka' },
]

const marqueeItems = [
    'Living Room', 'Bedroom Sets', 'Dining Tables', 'Office Desks',
    'Custom Orders', 'Home Decor', 'Storage Solutions', 'Outdoor Furniture',
]

const categories = [
    {
        slug:  'living-room-furniture',
        name:  'Living Room',
        icon:  '🛋️',
        count: 'Sofas & Seating',
        color: '#8B5E3C',
        image: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&q=80&auto=format&fit=crop',
    },
    {
        slug:  'bedroom-furniture',
        name:  'Bedroom',
        icon:  '🛏️',
        count: 'Beds & Wardrobes',
        color: '#5C6E5A',
        image: 'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=600&q=80&auto=format&fit=crop',
    },
    {
        slug:  'dining-furniture',
        name:  'Dining',
        icon:  '🍽️',
        count: 'Tables & Chairs',
        color: '#6B4E37',
        image: 'https://images.unsplash.com/photo-1549497538-303791108f95?w=600&q=80&auto=format&fit=crop',
    },
    {
        slug:  'office-furniture',
        name:  'Office',
        icon:  '💼',
        count: 'Desks & Chairs',
        color: '#3D5A6B',
        image: 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=600&q=80&auto=format&fit=crop',
    },
    {
        slug:  'storage-cabinets',
        name:  'Storage',
        icon:  '🗄️',
        count: 'Cabinets & Shelves',
        color: '#705040',
        image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80&auto=format&fit=crop',
    },
    {
        slug:  'home-decor',
        name:  'Home Decor',
        icon:  '🪴',
        count: 'Accessories',
        color: '#5A6B3D',
        image: 'https://images.unsplash.com/photo-1567538096621-38d2284b23ff?w=600&q=80&auto=format&fit=crop',
    },
]

const values = [
    { icon: '🛡️', title: 'Quality Guaranteed',  desc: '3-year warranty on all furniture' },
    { icon: '🚚', title: 'Free Delivery',        desc: 'Free delivery across Dhaka city' },
    { icon: '🔄', title: '30-Day Returns',       desc: 'Easy hassle-free return policy' },
    { icon: '💳', title: 'Easy Payment',         desc: 'EMI & multiple payment options' },
]

const testimonials = [
    {
        name: 'Fatima Rahman',
        initials: 'FR',
        location: 'Gulshan, Dhaka',
        text: 'Absolutely love my new sofa set! The quality is incredible and delivery was right on time. Will definitely order again.',
    },
    {
        name: 'Ahmed Hassan',
        initials: 'AH',
        location: 'Dhanmondi, Dhaka',
        text: 'Ordered a custom dining table and it turned out exactly as I imagined. Excellent craftsmanship and great customer service.',
    },
    {
        name: 'Nusrat Islam',
        initials: 'NI',
        location: 'Uttara, Dhaka',
        text: 'The bedroom set transformed my room completely. Premium quality at a fair price. Highly recommend LuxuryFurnish!',
    },
]

const whyUs = [
    { icon: '🪵', title: 'Premium Materials',  desc: 'Only the finest solid wood, fabric, and leather used in every piece.' },
    { icon: '✋', title: 'Handcrafted',         desc: 'Skilled artisans with decades of experience craft each piece with care.' },
    { icon: '📐', title: 'Custom Designs',      desc: 'Work with our designers to create furniture tailored to your space.' },
    { icon: '🌿', title: 'Eco-Friendly',        desc: 'Sustainably sourced materials and eco-conscious manufacturing practices.' },
    { icon: '🏆', title: 'Award Winning',       desc: 'Recognized as Bangladesh\'s best furniture brand for 3 consecutive years.' },
    { icon: '📞', title: '24/7 Support',        desc: 'Our team is always ready to help you from order to delivery and beyond.' },
]

async function fetchBestSellers() {
    loadingBest.value = true
    try {
        const res = await axios.get('/products/best-sellers', { params: { per_page: 8 } })
        bestSellers.value = res.data.data || res.data.products || res.data || []
    } catch {
        bestSellers.value = []
    } finally {
        loadingBest.value = false
    }
}

async function fetchNewArrivals() {
    loadingNew.value = true
    try {
        const res = await axios.get('/products/new-arrivals', { params: { per_page: 4 } })
        newArrivals.value = res.data.data || res.data.products || res.data || []
    } catch {
        newArrivals.value = []
    } finally {
        loadingNew.value = false
    }
}

onMounted(() => {
    fetchBestSellers()
    fetchNewArrivals()
})
</script>

<style scoped>
/* ── Hero ─────────────────────────────────────── */
.hero {
    position: relative;
    min-height: 100svh;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.hero-bg { position: absolute; inset: 0; z-index: 0; }
.hero-bg-img {
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center;
}
.hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        105deg,
        rgba(17, 10, 4, 0.90) 0%,
        rgba(17, 10, 4, 0.78) 45%,
        rgba(17, 10, 4, 0.30) 100%
    );
}

.hero-content {
    position: relative; z-index: 1;
    max-width: 1280px; margin: 0 auto;
    padding: 120px 24px 80px;
    width: 100%;
}

.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    font-size: 11px; font-weight: 700;
    letter-spacing: 0.18em; text-transform: uppercase;
    color: #C9A055; margin-bottom: 20px;
}
.hero-eyebrow-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #C9A055;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(201,160,85,0.6); }
    50%       { box-shadow: 0 0 0 8px rgba(201,160,85,0); }
}

.hero-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(44px, 7vw, 88px);
    font-weight: 800;
    line-height: 1.02;
    letter-spacing: -0.04em;
    color: #FFFFFF;
    margin-bottom: 20px;
}
.hero-title-accent {
    display: block;
    background: linear-gradient(135deg, #E8C47A, #C9A055, #A07030);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-sub {
    font-size: clamp(15px, 2vw, 18px);
    color: rgba(255, 255, 255, 0.72);
    max-width: 480px;
    line-height: 1.7;
    margin-bottom: 36px;
}

.hero-actions { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; margin-bottom: 52px; }
.hero-ghost-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 15px 32px; border-radius: 50px;
    background: transparent; color: rgba(255,255,255,0.88);
    border: 1.5px solid rgba(255,255,255,0.3);
    font-size: 15px; font-weight: 500; cursor: pointer;
    text-decoration: none; transition: all 0.22s ease;
}
.hero-ghost-btn:hover {
    background: rgba(255,255,255,0.12);
    color: #fff; border-color: rgba(255,255,255,0.6);
}

.hero-stats { display: flex; gap: 32px; flex-wrap: wrap; }
.hero-stat { display: flex; flex-direction: column; gap: 2px; }
.hero-stat-num {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(22px, 3vw, 30px);
    font-weight: 700; color: #FFFFFF;
    line-height: 1;
}
.hero-stat-label { font-size: 11.5px; color: rgba(255,255,255,0.5); font-weight: 400; }

.hero-scroll {
    position: absolute; bottom: 32px; left: 50%;
    transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    color: rgba(255,255,255,0.45); font-size: 10px; font-weight: 600;
    letter-spacing: 0.14em; text-transform: uppercase; z-index: 1;
}
.hero-scroll-line {
    width: 1px; height: 40px;
    background: linear-gradient(to bottom, rgba(255,255,255,0.5), transparent);
    animation: scroll-line 2s ease-in-out infinite;
}
@keyframes scroll-line {
    0%   { transform: scaleY(0); transform-origin: top; }
    50%  { transform: scaleY(1); transform-origin: top; }
    51%  { transform-origin: bottom; }
    100% { transform: scaleY(0); transform-origin: bottom; }
}

/* ── Marquee ──────────────────────────────────── */
.marquee-band {
    background: #1E1208;
    overflow: hidden;
    padding: 14px 0;
    border-top: 1px solid rgba(255,255,255,0.04);
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.marquee-item {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 0 20px;
    font-size: 12px; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: rgba(201, 160, 85, 0.75);
    white-space: nowrap;
}
.marquee-dot { color: #C9A055; font-size: 9px; }

/* ── Sections ─────────────────────────────────── */
.section { padding: 72px 0; }
.container { max-width: 1280px; margin: 0 auto; padding: 0 20px; }

.section-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 16px; margin-bottom: 40px; flex-wrap: wrap;
}
.section-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(26px, 4vw, 42px);
    font-weight: 700; color: #111109;
    line-height: 1.12; letter-spacing: -0.025em;
}
.section-subtitle { font-size: 15px; color: #7A6E66; line-height: 1.7; }

/* ── Category Grid ────────────────────────────── */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
@media (max-width: 768px) {
    .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}
@media (max-width: 480px) {
    .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
}

.cat-card {
    position: relative; border-radius: 20px; overflow: hidden;
    aspect-ratio: 4/3; text-decoration: none; display: block;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.cat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(17,17,9,0.15); }

.cat-img-wrap { position: absolute; inset: 0; }
.cat-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.cat-card:hover .cat-img { transform: scale(1.08); }
.cat-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(17,10,4,0.78) 0%, rgba(17,10,4,0.1) 60%);
}

.cat-info {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 18px 16px; display: flex; flex-direction: column; gap: 3px;
}
.cat-emoji { font-size: 22px; margin-bottom: 2px; }
.cat-name {
    font-family: 'Playfair Display', serif;
    font-size: 16px; font-weight: 700; color: #FFFFFF;
    line-height: 1.2;
}
.cat-count { font-size: 11px; color: rgba(255,255,255,0.62); font-weight: 500; }

/* ── Product Grid ────────────────────────────── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 1100px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } }
@media (max-width: 420px)  { .product-grid { grid-template-columns: 1fr; } }

.prod-skeleton {
    aspect-ratio: 3/4; border-radius: 20px;
    background: linear-gradient(90deg, #EDE7E0 25%, #E4DCCE 50%, #EDE7E0 75%);
    background-size: 1600px 100%;
    animation: shimmer 1.8s ease-in-out infinite;
}
@keyframes shimmer {
    0%   { background-position: -800px 0; }
    100% { background-position: 800px 0; }
}

.empty-state { text-align: center; padding: 40px; color: #9B9087; font-size: 15px; }

/* ── Value Strip ─────────────────────────────── */
.value-strip {
    background: linear-gradient(135deg, #5C2E0A, #7C4A1E, #8B5E3C);
    padding: 40px 0;
}
.value-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
}
@media (max-width: 900px)  { .value-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .value-grid { grid-template-columns: 1fr 1fr; gap: 16px; } }

.value-item {
    display: flex; align-items: center; gap: 14px;
    padding: 4px 0;
}
.value-icon { font-size: 26px; flex-shrink: 0; }
.value-title { font-size: 14px; font-weight: 700; color: #FFFFFF; margin-bottom: 2px; }
.value-desc  { font-size: 12px; color: rgba(255,255,255,0.62); }

/* ── Promo Banner ────────────────────────────── */
.promo-banner {
    position: relative; padding: 100px 0; overflow: hidden;
}
.promo-bg { position: absolute; inset: 0; }
.promo-bg-img { width: 100%; height: 100%; object-fit: cover; }
.promo-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        105deg,
        rgba(17, 10, 4, 0.92) 0%,
        rgba(17, 10, 4, 0.82) 50%,
        rgba(17, 10, 4, 0.40) 100%
    );
}
.promo-content {
    position: relative; z-index: 1;
    max-width: 520px;
}
.promo-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(32px, 5vw, 58px);
    font-weight: 800; color: #FFFFFF;
    line-height: 1.1; letter-spacing: -0.03em;
    margin-bottom: 16px;
}
.promo-desc { font-size: 15px; color: rgba(255,255,255,0.68); line-height: 1.7; margin-bottom: 30px; }

/* ── Testimonials ────────────────────────────── */
.testi-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
}
@media (max-width: 900px)  { .testi-grid { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; } }
@media (max-width: 1024px) and (min-width: 600px) { .testi-grid { grid-template-columns: repeat(2, 1fr); } }

.testi-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(201,160,85,0.15);
    border-radius: 20px; padding: 24px;
    transition: background 0.2s ease;
}
.testi-card:hover { background: rgba(255,255,255,0.08); }
.testi-stars { color: #F59E0B; font-size: 16px; margin-bottom: 12px; letter-spacing: 2px; }
.testi-text {
    font-size: 14px; line-height: 1.75;
    color: rgba(253,250,246,0.78);
    margin-bottom: 20px;
    font-style: italic;
}
.testi-author { display: flex; align-items: center; gap: 12px; }
.testi-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #5C2E0A, #8B5E3C);
    color: #fff; font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.testi-name     { font-size: 14px; font-weight: 600; color: #FDFAF6; }
.testi-location { font-size: 12px; color: rgba(253,250,246,0.45); margin-top: 1px; }

/* ── Why Us ──────────────────────────────────── */
.why-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
}
@media (max-width: 900px)  { .why-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 500px)  { .why-grid { grid-template-columns: 1fr; } }

.why-card {
    padding: 28px 24px; border-radius: 20px;
    border: 1.5px solid #EDE5D8;
    background: #FDFAF6;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}
.why-card:hover {
    border-color: #C9A055;
    box-shadow: 0 8px 28px rgba(92,46,10,0.08);
    transform: translateY(-3px);
}
.why-icon  { font-size: 30px; margin-bottom: 14px; }
.why-title { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 700; color: #1E1208; margin-bottom: 8px; }
.why-desc  { font-size: 13.5px; color: #7A6E66; line-height: 1.65; }

/* ── Showroom ─────────────────────────────────── */
.showroom { padding: 80px 0; background: #FDFAF6; }
.showroom-card {
    background: #FFFFFF;
    border-radius: 28px;
    border: 1.5px solid #EDE5D8;
    display: grid;
    grid-template-columns: 1fr 1fr;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(92,46,10,0.08);
}
@media (max-width: 768px) { .showroom-card { grid-template-columns: 1fr; } }

.showroom-content { padding: 48px 44px; display: flex; flex-direction: column; justify-content: center; }
.showroom-img-wrap { position: relative; min-height: 320px; overflow: hidden; }
.showroom-img { width: 100%; height: 100%; object-fit: cover; }

/* ── Responsive ──────────────────────────────── */
@media (max-width: 640px) {
    .section  { padding: 52px 0; }
    .hero-content { padding: 100px 18px 64px; }
    .showroom-content { padding: 32px 24px; }
}
</style>
