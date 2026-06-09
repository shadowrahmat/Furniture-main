<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#18140F">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>{{ config('app.name', 'LuxuryFurnish') }} — Premium Modern Furniture</title>
    <meta name="description" content="Discover premium modern furniture for your home. Shop sofas, beds, dining tables, office furniture and home decor online. Custom furniture orders available with fast delivery across Bangladesh.">
    <meta name="keywords" content="furniture, sofa, bed, dining table, office furniture, home decor, Bangladesh furniture, premium furniture">

    <!-- Open Graph -->
    <meta property="og:title" content="LuxuryFurnish — Premium Modern Furniture">
    <meta property="og:description" content="Elegant furniture crafted for modern living. Shop our premium collection online with fast delivery.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('/og-image.jpg') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="LuxuryFurnish — Premium Modern Furniture">
    <meta name="twitter:description" content="Elegant furniture crafted for modern living.">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Google Fonts — preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased overflow-x-hidden">
    <div id="app"></div>
</body>
</html>
