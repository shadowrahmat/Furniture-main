<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CustomInquiryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\WoodTypeController;
use App\Http\Controllers\Api\FurnitureTypeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WoodTypeController as AdminWoodTypeController;
use App\Http\Controllers\Admin\FurnitureTypeController as AdminFurnitureTypeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/featured', [CategoryController::class, 'featured']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/best-sellers', [ProductController::class, 'bestSellers']);
Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Cart (public - works with session)
Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'add']);
    Route::put('/cart/{cart}', [CartController::class, 'update']);
    Route::delete('/cart/{cart}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);
});

// Coupon
Route::post('/coupons/apply', [CouponController::class, 'apply']);

// Contact
Route::post('/contact', [ContactController::class, 'store']);
Route::post('/custom-inquiry', [CustomInquiryController::class, 'store']);

// Build Your Furniture catalogs (public)
Route::get('/wood-types', [WoodTypeController::class, 'index']);
Route::get('/furniture-types', [FurnitureTypeController::class, 'index']);

// Order tracking (public)
Route::get('/orders/track/{orderNumber}', [OrderController::class, 'track']);

// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    // Cart (authenticated)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'add']);
    Route::put('/cart/{cart}', [CartController::class, 'update']);
    Route::delete('/cart/{cart}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Reviews
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);
});

// Admin routes
Route::middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Products
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::get('/products/{product}', [AdminProductController::class, 'show']);
    Route::post('/products/{product}', [AdminProductController::class, 'update']);
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);
    Route::patch('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus']);
    Route::patch('/products/{product}/toggle-active', [AdminProductController::class, 'toggleStatus']);

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::post('/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);
    Route::patch('/orders/{order}/notes', [AdminOrderController::class, 'updateNotes']);

    // Users / Customers
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    // Alias for Vue frontend
    Route::get('/customers', [UserController::class, 'index']);
    Route::patch('/customers/{user}/toggle-active', [UserController::class, 'toggleStatus']);

    // Coupons
    Route::get('/coupons', [AdminCouponController::class, 'index']);
    Route::post('/coupons', [AdminCouponController::class, 'store']);
    Route::put('/coupons/{coupon}', [AdminCouponController::class, 'update']);
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy']);

    // Banners
    Route::get('/banners', [BannerController::class, 'index']);
    Route::post('/banners', [BannerController::class, 'store']);
    Route::post('/banners/{banner}', [BannerController::class, 'update']);
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index']);
    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve']);
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy']);

    // Inquiries (legacy)
    Route::get('/inquiries', [InquiryController::class, 'index']);
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show']);
    Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus']);
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy']);

    // Wood Types (Build Your Furniture)
    Route::get('/wood-types', [AdminWoodTypeController::class, 'index']);
    Route::post('/wood-types', [AdminWoodTypeController::class, 'store']);
    Route::post('/wood-types/{woodType}', [AdminWoodTypeController::class, 'update']);
    Route::delete('/wood-types/{woodType}', [AdminWoodTypeController::class, 'destroy']);

    // Furniture Types (Build Your Furniture)
    Route::get('/furniture-types', [AdminFurnitureTypeController::class, 'index']);
    Route::post('/furniture-types', [AdminFurnitureTypeController::class, 'store']);
    Route::post('/furniture-types/{furnitureType}', [AdminFurnitureTypeController::class, 'update']);
    Route::delete('/furniture-types/{furnitureType}', [AdminFurnitureTypeController::class, 'destroy']);

    // Contact messages
    Route::get('/messages', [AdminContactController::class, 'index']);
    Route::get('/messages/{message}', [AdminContactController::class, 'show']);
    Route::patch('/messages/{message}/read', [AdminContactController::class, 'markAsRead']);
    Route::delete('/messages/{message}', [AdminContactController::class, 'destroy']);
});
