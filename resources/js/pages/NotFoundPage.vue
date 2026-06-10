<template>
    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">
        <!-- Decorative glow -->
        <div class="absolute inset-0 -z-10 opacity-50"
            style="background:radial-gradient(circle at 50% 30%, #F0E8DC 0%, transparent 60%)"></div>

        <!-- Floating shapes -->
        <span class="absolute w-16 h-16 rounded-3xl anim-float" style="top:15%; left:10%; background:linear-gradient(135deg,#F5EFE6,#EDE5D8); opacity:0.7"></span>
        <span class="absolute w-10 h-10 rounded-full anim-float delay-200" style="top:65%; left:16%; background:#F0E8DE; opacity:0.6"></span>
        <span class="absolute w-20 h-20 rounded-full anim-float delay-100" style="top:20%; right:12%; background:#F5EFE6; opacity:0.6"></span>
        <span class="absolute w-12 h-12 rounded-2xl anim-float delay-300" style="bottom:18%; right:14%; background:#EDE5D8; opacity:0.7"></span>

        <div class="relative max-w-lg w-full text-center anim-scale-in">
            <div class="relative inline-flex items-center justify-center mb-2">
                <span class="font-heading font-extrabold leading-none select-none"
                    style="font-size:clamp(6rem,22vw,10rem); background:linear-gradient(135deg,#8B5E3C,#C9A66B); -webkit-background-clip:text; background-clip:text; color:transparent;">404</span>
            </div>

            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 -mt-4"
                style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8); box-shadow:0 12px 32px rgba(139,94,60,0.15)">
                <svg class="w-10 h-10" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <p class="label-tag justify-center mb-3" style="color:#C9A66B">Lost in the showroom</p>
            <h1 class="font-heading font-bold text-2xl sm:text-3xl mb-3" style="color:#1E1B18">Page Not Found</h1>
            <p class="mb-8 max-w-sm mx-auto" style="color:#6D655F">The page you're looking for has been moved, removed, or never existed. Let's get you back on track.</p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <RouterLink to="/" class="btn-primary justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>
                    </svg>
                    Back to Home
                </RouterLink>
                <RouterLink to="/shop" class="btn-outline justify-center">
                    Browse Furniture
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </RouterLink>
            </div>
        </div>
    </div>
</template>
