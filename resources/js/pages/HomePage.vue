<template>
    <div>
        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center overflow-hidden" style="background:linear-gradient(135deg,#1E1B18 0%,#2D2420 40%,#3D2B1F 100%)">
            <!-- Decorative background -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-20"
                    style="background-image:url('https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1600&q=80'); background-size:cover; background-position:center"></div>
                <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(30,27,24,0.9) 0%,rgba(75,46,30,0.7) 100%)"></div>
            </div>

            <!-- Floating decorative blobs -->
            <div class="absolute top-20 right-1/4 w-96 h-96 rounded-full opacity-10 animate-float"
                style="background:radial-gradient(circle, #C9A66B, transparent); filter:blur(60px)"></div>
            <div class="absolute bottom-20 left-1/3 w-64 h-64 rounded-full opacity-10"
                style="background:radial-gradient(circle, #8B5E3C, transparent); filter:blur(40px); animation:float 4s ease-in-out infinite reverse"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 w-full">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left: Text -->
                    <div class="animate-fade-in">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6"
                            style="background:rgba(201,166,107,0.15); border:1px solid rgba(201,166,107,0.3)">
                            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#C9A66B"></span>
                            <span class="text-xs font-semibold uppercase tracking-widest" style="color:#C9A66B">Premium Collection 2026</span>
                        </div>
                        <h1 class="font-heading font-bold mb-6 leading-tight text-white"
                            style="font-size:clamp(38px,6vw,72px); line-height:1.1">
                            Elegant Furniture<br>
                            <span style="background:linear-gradient(135deg,#C9A66B,#F5EFE6); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent">for Modern Living</span>
                        </h1>
                        <p class="text-base leading-relaxed mb-8 max-w-lg" style="color:rgba(245,239,230,0.75)">
                            Discover premium sofas, beds, dining tables, chairs, storage solutions, and home decor crafted for comfort and style.
                        </p>

                        <div class="flex flex-wrap gap-4 mb-10">
                            <RouterLink to="/shop" class="btn-primary text-sm py-3.5 px-8">
                                Shop Furniture
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </RouterLink>
                            <RouterLink to="/shop" class="btn-outline text-sm py-3.5 px-8"
                                style="color:#C9A66B; border-color:rgba(201,166,107,0.5)">
                                Explore Collections
                            </RouterLink>
                        </div>

                        <!-- Trust badges -->
                        <div class="flex flex-wrap gap-4">
                            <div v-for="badge in trustBadges" :key="badge"
                                class="flex items-center gap-2 text-xs font-medium" style="color:rgba(245,239,230,0.6)">
                                <span style="color:#C9A66B">✓</span> {{ badge }}
                            </div>
                        </div>
                    </div>

                    <!-- Right: Floating product cards -->
                    <div class="relative hidden lg:flex items-center justify-center h-[500px]">
                        <div class="absolute inset-0 rounded-3xl opacity-5" style="background:rgba(201,166,107,0.3); border:1px solid rgba(201,166,107,0.2)"></div>

                        <!-- Main product card -->
                        <div class="relative z-10 w-72 card-premium animate-float">
                            <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&h=300&fit=crop&q=80"
                                class="w-full h-48 object-cover rounded-t-2xl">
                            <div class="p-4">
                                <span class="text-xs font-semibold uppercase tracking-wider" style="color:#C9A66B">Living Room</span>
                                <h3 class="font-heading font-semibold mt-1 mb-1" style="color:#1E1B18">Modern Luxury Sofa</h3>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="price-tag text-lg">৳38,000</span>
                                        <span class="price-original text-sm ml-2">৳45,000</span>
                                    </div>
                                    <span class="badge-discount">-16%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Floating stat cards -->
                        <div class="absolute top-8 right-4 glass rounded-xl p-3 text-center" style="animation:float 2.5s ease-in-out infinite">
                            <p class="font-heading font-bold text-xl" style="color:#C9A66B">500+</p>
                            <p class="text-xs" style="color:#6D655F">Products</p>
                        </div>
                        <div class="absolute bottom-8 left-4 glass rounded-xl p-3 text-center" style="animation:float 3.5s ease-in-out infinite reverse">
                            <p class="font-heading font-bold text-xl" style="color:#8B5E3C">2K+</p>
                            <p class="text-xs" style="color:#6D655F">Happy Customers</p>
                        </div>
                        <div class="absolute top-1/3 right-0 glass rounded-xl p-3 text-center" style="animation:float 4s ease-in-out infinite">
                            <p class="font-heading font-bold text-xl" style="color:#6B7A52">⭐ 4.9</p>
                            <p class="text-xs" style="color:#6D655F">Rating</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#C9A66B">Browse By Room</p>
                    <h2 class="section-title mb-4">Explore Furniture Categories</h2>
                    <p class="section-subtitle mx-auto">Find stylish furniture and decor pieces designed to elevate every corner of your home.</p>
                </div>

                <div v-if="categoriesLoading" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div v-for="n in 6" :key="n" class="skeleton h-36 rounded-2xl"></div>
                </div>

                <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <RouterLink v-for="cat in categories" :key="cat.id" :to="`/shop/${cat.slug}`"
                        class="group relative rounded-2xl overflow-hidden cursor-pointer transition-all duration-300 hover:-translate-y-1"
                        style="aspect-ratio:3/4; background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                        <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center relative">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 transition-all duration-300 group-hover:scale-110"
                                style="background:linear-gradient(135deg,#8B5E3C,#C9A66B)">
                                <span class="text-2xl text-white">{{ catIcons[cat.slug] || '🪑' }}</span>
                            </div>
                            <h3 class="font-semibold text-xs leading-tight" style="color:#1E1B18">{{ cat.name }}</h3>
                            <p class="text-xs mt-1" style="color:#6D655F">{{ cat.products_count }} items</p>
                        </div>
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"
                            style="background:linear-gradient(135deg,rgba(139,94,60,0.05),rgba(201,166,107,0.1))"></div>
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Best Sellers Section -->
        <section style="background:white" class="py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#C9A66B">Customer Favorites</p>
                        <h2 class="section-title">Best Selling Furniture</h2>
                        <p class="section-subtitle mt-3">A curated collection of customer-favorite furniture pieces crafted for modern homes.</p>
                    </div>
                    <RouterLink to="/shop?best_seller=1" class="btn-outline flex-shrink-0">View All</RouterLink>
                </div>

                <div v-if="bestSellersLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="n in 4" :key="n" class="skeleton h-80 rounded-2xl"></div>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard v-for="product in bestSellers" :key="product.id" :product="product"/>
                </div>
            </div>
        </section>

        <!-- Craftsmanship Banner -->
        <section class="py-20 px-4" style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#8B5E3C">Our Promise</p>
                    <h2 class="section-title mb-4">Crafted for Comfort & Durability</h2>
                    <p class="section-subtitle mx-auto">Every furniture piece is designed with premium materials, attention to detail, and modern craftsmanship to create lasting comfort and elegance.</p>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="feature in craftFeatures" :key="feature.title"
                        class="card-premium p-6 text-center hover:-translate-y-1">
                        <div class="text-4xl mb-4">{{ feature.icon }}</div>
                        <h3 class="font-heading font-semibold mb-2" style="color:#1E1B18">{{ feature.title }}</h3>
                        <p class="text-sm leading-relaxed" style="color:#6D655F">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- New Arrivals Section -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#C9A66B">Fresh Collection</p>
                        <h2 class="section-title">New Arrivals</h2>
                    </div>
                    <RouterLink to="/shop?new_arrival=1" class="btn-outline flex-shrink-0">View All</RouterLink>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard v-for="product in newArrivals" :key="product.id" :product="product"/>
                </div>
            </div>
        </section>

        <!-- Delivery Section -->
        <section class="py-20 px-4 overflow-hidden" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#C9A66B">Hassle-Free Delivery</p>
                        <h2 class="font-heading font-bold text-white mb-6" style="font-size:clamp(28px,4vw,48px)">Reliable Furniture Delivery</h2>
                        <p class="text-base leading-relaxed mb-8" style="color:rgba(245,239,230,0.7)">
                            Get your furniture delivered safely and professionally with smooth order tracking and reliable customer support.
                        </p>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div v-for="stat in deliveryStats" :key="stat.label" class="rounded-xl p-4"
                                style="background:rgba(255,255,255,0.05); border:1px solid rgba(201,166,107,0.15)">
                                <p class="font-heading font-bold text-2xl mb-1" style="color:#C9A66B">{{ stat.value }}</p>
                                <p class="text-xs" style="color:rgba(245,239,230,0.6)">{{ stat.label }}</p>
                            </div>
                        </div>
                        <RouterLink to="/shop" class="btn-primary inline-flex">Start Shopping</RouterLink>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="step in deliverySteps" :key="step.step" class="rounded-2xl p-5"
                            :class="step.step % 2 === 0 ? 'mt-8' : ''"
                            style="background:rgba(255,255,255,0.05); border:1px solid rgba(201,166,107,0.1)">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(201,166,107,0.2)">
                                <span class="text-xl">{{ step.icon }}</span>
                            </div>
                            <h4 class="font-semibold text-white text-sm mb-1">{{ step.title }}</h4>
                            <p class="text-xs leading-relaxed" style="color:rgba(245,239,230,0.5)">{{ step.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Custom Furniture Section -->
        <section class="py-20 px-4" style="background:linear-gradient(135deg,#F5EFE6,#FFFDF9)">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#8B5E3C">Personalized For You</p>
                        <h2 class="section-title mb-4">Custom Furniture Solutions</h2>
                        <p class="section-subtitle mb-6">Need personalized furniture for your home, office, restaurant, or commercial space? We provide custom furniture solutions tailored to your exact needs.</p>
                        <div class="space-y-3 mb-8">
                            <div v-for="point in customPoints" :key="point" class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0" style="background:#8B5E3C">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-sm" style="color:#4B2E1E">{{ point }}</span>
                            </div>
                        </div>
                        <RouterLink to="/custom-furniture" class="btn-primary">Request Custom Design</RouterLink>
                    </div>
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&h=450&fit=crop&q=80"
                            class="w-full rounded-3xl shadow-2xl object-cover" style="aspect-ratio:4/3">
                        <div class="absolute -bottom-6 -left-6 glass rounded-2xl p-4 shadow-xl">
                            <p class="font-heading font-bold text-lg" style="color:#8B5E3C">100%</p>
                            <p class="text-xs" style="color:#6D655F">Custom Made</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 px-4" style="background:white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#C9A66B">Customer Stories</p>
                    <h2 class="section-title">Why Customers Love Our Furniture</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="review in testimonials" :key="review.name"
                        class="card-premium p-6 hover:-translate-y-1">
                        <div class="flex gap-0.5 mb-4">
                            <svg v-for="n in 5" :key="n" class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-sm leading-relaxed mb-5 italic" style="color:#4B2E1E">"{{ review.text }}"</p>
                        <div class="flex items-center gap-3">
                            <img :src="`https://ui-avatars.com/api/?name=${review.name}&background=${review.color}&color=fff&size=40`"
                                class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold text-sm" style="color:#1E1B18">{{ review.name }}</p>
                                <p class="text-xs" style="color:#6D655F">{{ review.location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="py-20 px-4" style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="section-title">Why Choose Our Store</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    <div v-for="reason in whyUs" :key="reason.title" class="text-center">
                        <div class="text-4xl mb-3">{{ reason.icon }}</div>
                        <h4 class="font-semibold text-sm mb-1" style="color:#1E1B18">{{ reason.title }}</h4>
                        <p class="text-xs leading-relaxed" style="color:#6D655F">{{ reason.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Showroom Section -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#C9A66B">Come Visit Us</p>
                    <h2 class="section-title mb-4">Visit Our Furniture Showroom</h2>
                    <p class="section-subtitle mx-auto">Experience our premium furniture collections in person or shop online with convenient delivery options.</p>
                </div>
                <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                    <!-- Map placeholder -->
                    <div class="rounded-3xl overflow-hidden shadow-xl h-80 bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-6xl mb-3">🗺️</div>
                            <p class="font-semibold" style="color:#8B5E3C">Our Showroom</p>
                            <p class="text-sm" style="color:#6D655F">123 Furniture Street, Dhaka</p>
                        </div>
                    </div>
                    <!-- Contact cards -->
                    <div class="space-y-4">
                        <div v-for="info in showroomInfo" :key="info.title"
                            class="card-premium p-5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl flex-shrink-0"
                                style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                                {{ info.icon }}
                            </div>
                            <div>
                                <p class="font-semibold text-sm mb-0.5" style="color:#1E1B18">{{ info.title }}</p>
                                <p class="text-sm" style="color:#6D655F">{{ info.value }}</p>
                            </div>
                        </div>
                        <a href="tel:+8801700000000" class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call Us: +880 1700-000000
                        </a>
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

const categories = ref([])
const bestSellers = ref([])
const newArrivals = ref([])
const categoriesLoading = ref(true)
const bestSellersLoading = ref(true)

const catIcons = {
    'living-room-furniture': '🛋️',
    'bedroom-furniture': '🛏️',
    'dining-furniture': '🍽️',
    'office-furniture': '💼',
    'storage-cabinets': '🗄️',
    'home-decor': '🪴',
}

const trustBadges = ['Premium quality', 'Modern craftsmanship', 'Fast delivery', 'Elegant living']

const craftFeatures = [
    { icon: '🪵', title: 'Premium Materials', desc: 'Solid wood, genuine leather, and premium fabrics for lasting quality.' },
    { icon: '🔨', title: 'Modern Craftsmanship', desc: 'Expert artisans crafting every piece with precision and care.' },
    { icon: '😌', title: 'Comfort-Focused', desc: 'Ergonomic designs ensuring maximum comfort and relaxation.' },
    { icon: '⏰', title: 'Long-lasting Durability', desc: 'Built to last with quality joinery and reinforced construction.' },
]

const deliveryStats = [
    { value: '2-5 Days', label: 'Delivery Time' },
    { value: '100%', label: 'Safe Packaging' },
    { value: 'Free', label: 'Above ৳5,000' },
    { value: '24/7', label: 'Support' },
]

const deliverySteps = [
    { step: 1, icon: '🛍️', title: 'Shop Online', desc: 'Browse and select your dream furniture' },
    { step: 2, icon: '📦', title: 'Secure Packaging', desc: 'Safe and professional packaging' },
    { step: 3, icon: '🚚', title: 'Fast Delivery', desc: 'Delivered to your doorstep' },
    { step: 4, icon: '✨', title: 'Assembly Support', desc: 'Expert assembly assistance' },
]

const customPoints = [
    'Custom dimensions and sizes',
    'Choice of materials and colors',
    'Personalized designs',
    'Commercial and residential projects',
    'Competitive pricing',
]

const testimonials = [
    { name: 'Rahim Ahmed', location: 'Dhaka, Bangladesh', color: '8B5E3C', text: 'Absolutely stunning furniture! The quality is beyond my expectations. The sofa I purchased is incredibly comfortable and looks luxurious in my living room.' },
    { name: 'Fatima Khan', location: 'Chittagong, Bangladesh', color: '6B7A52', text: 'I ordered a custom dining set and I am amazed by the craftsmanship. Perfect fit for my dining room. Delivery was fast and professional.' },
    { name: 'Karim Hassan', location: 'Sylhet, Bangladesh', color: 'C9A66B', text: 'The bedroom set is beautiful and high quality. The wardrobe assembly was smooth and customer service was excellent throughout.' },
]

const whyUs = [
    { icon: '🌟', title: 'Premium Quality', desc: 'Handpicked premium materials' },
    { icon: '✏️', title: 'Elegant Designs', desc: 'Modern & timeless aesthetics' },
    { icon: '😊', title: 'Comfort First', desc: 'Ergonomic craftsmanship' },
    { icon: '🚚', title: 'Fast Delivery', desc: 'Quick & secure shipping' },
    { icon: '🔧', title: 'Custom Solutions', desc: 'Tailored to your needs' },
    { icon: '💬', title: 'Great Support', desc: '24/7 customer service' },
]

const showroomInfo = [
    { icon: '📍', title: 'Showroom Address', value: '123 Furniture Street, Dhaka 1200, Bangladesh' },
    { icon: '⏰', title: 'Working Hours', value: 'Sat-Thu: 10AM - 8PM | Fri: 2PM - 8PM' },
    { icon: '📞', title: 'Phone', value: '+880 1700-000000 | +880 1800-000001' },
    { icon: '✉️', title: 'Email', value: 'hello@furniturestore.com' },
]

onMounted(async () => {
    try {
        const [catRes, bsRes, naRes] = await Promise.all([
            axios.get('/categories/featured'),
            axios.get('/products/best-sellers'),
            axios.get('/products/new-arrivals'),
        ])
        categories.value = catRes.data
        bestSellers.value = bsRes.data
        newArrivals.value = naRes.data
    } catch (e) {
        console.error(e)
    } finally {
        categoriesLoading.value = false
        bestSellersLoading.value = false
    }
})
</script>
