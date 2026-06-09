<template>
    <div>
        <!-- Hero -->
        <section class="py-24 px-4 relative overflow-hidden"
            style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="absolute inset-0 opacity-10"
                style="background-image:url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1600&q=60'); background-size:cover"></div>
            <div class="relative max-w-3xl mx-auto text-center">
                <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#C9A66B">Our Story</p>
                <h1 class="font-heading font-bold text-white mb-6" style="font-size:clamp(32px,5vw,60px)">
                    More Than Furniture.<br>
                    <span style="color:#C9A66B">A Better Living Experience.</span>
                </h1>
                <p class="text-base leading-relaxed" style="color:rgba(245,239,230,0.75)">
                    We combine modern craftsmanship, elegant design, and premium comfort to create furniture that transforms everyday living.
                </p>
            </div>
        </section>

        <!-- Story -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#C9A66B">Our Story</p>
                    <h2 class="section-title mb-6">Crafting Beautiful Spaces Since Day One</h2>
                    <p class="text-base leading-relaxed mb-4" style="color:#6D655F">
                        Our Furniture Store was created to provide high-quality modern furniture for homes, offices, and lifestyle spaces. From luxurious sofas and elegant dining tables to stylish storage solutions and decor, we focus on craftsmanship, comfort, and timeless design.
                    </p>
                    <p class="text-base leading-relaxed" style="color:#6D655F">
                        We believe furniture should combine beauty, comfort, and functionality while helping customers create spaces they truly love.
                    </p>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&h=450&fit=crop&q=80"
                        class="w-full rounded-3xl shadow-2xl object-cover" style="aspect-ratio:4/3">
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-16 px-4" style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
            <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div v-for="stat in stats" :key="stat.label">
                    <p class="font-heading font-bold text-4xl mb-2" style="color:#8B5E3C">{{ stat.value }}</p>
                    <p class="text-sm" style="color:#6D655F">{{ stat.label }}</p>
                </div>
            </div>
        </section>

        <!-- What we offer -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="section-title">What We Offer</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div v-for="offer in offers" :key="offer.title" class="card-premium p-6 text-center hover:-translate-y-1">
                        <div class="text-4xl mb-3">{{ offer.icon }}</div>
                        <h3 class="font-semibold mb-2" style="color:#1E1B18">{{ offer.title }}</h3>
                        <p class="text-sm" style="color:#6D655F">{{ offer.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="py-20 px-4" style="background:linear-gradient(135deg,#1E1B18,#2D2420)">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-8">
                <div class="rounded-3xl p-8" style="background:rgba(255,255,255,0.05); border:1px solid rgba(201,166,107,0.2)">
                    <h3 class="font-heading font-bold text-2xl text-white mb-4">Our Mission</h3>
                    <p style="color:rgba(245,239,230,0.7)">To make premium modern furniture accessible, stylish, and comfortable for every home and workspace.</p>
                </div>
                <div class="rounded-3xl p-8" style="background:rgba(201,166,107,0.1); border:1px solid rgba(201,166,107,0.2)">
                    <h3 class="font-heading font-bold text-2xl text-white mb-4">Our Vision</h3>
                    <p style="color:rgba(245,239,230,0.7)">To become a trusted modern furniture ecommerce brand known for exceptional quality, elegant design, and customer satisfaction.</p>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
const stats = [
    { value: '500+', label: 'Products' },
    { value: '2,000+', label: 'Happy Customers' },
    { value: '⭐ 4.9', label: 'Average Rating' },
    { value: '5+', label: 'Years Experience' },
]

const offers = [
    { icon: '🛋️', title: 'Luxury Sofas', desc: 'Premium fabric and leather sofas' },
    { icon: '🛏️', title: 'Modern Beds', desc: 'Elegant beds for restful sleep' },
    { icon: '🍽️', title: 'Dining Tables', desc: 'Stylish dining sets' },
    { icon: '💼', title: 'Office Furniture', desc: 'Ergonomic workspaces' },
    { icon: '🗄️', title: 'Storage Solutions', desc: 'Smart organization' },
    { icon: '🔨', title: 'Custom Furniture', desc: 'Personalized pieces' },
]
</script>
