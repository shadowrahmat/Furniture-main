<template>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-6" style="color:#1E1B18">My Addresses</h2>
        <div class="flex flex-col items-center text-center py-12 sm:py-16 anim-fade-up">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-5"
                style="background:linear-gradient(135deg,#F5EFE6,#EDE5D8)">
                <svg class="w-10 h-10" style="color:#C9A66B" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="font-heading font-bold text-lg mb-2" style="color:#1E1B18">Address book coming soon</h3>
            <p class="mb-6 text-sm max-w-sm" style="color:#6D655F">Save your delivery addresses here for faster checkout. This feature is on its way.</p>
            <RouterLink to="/account" class="btn-outline">
                Back to Dashboard
            </RouterLink>
        </div>
    </div>
</template>
