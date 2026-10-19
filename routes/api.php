<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;

// Public & Customer Controllers
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\EstimateController;
use App\Http\Controllers\Api\V1\TechnicianController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderCancelController;
use App\Http\Controllers\Api\V1\OrderPhotoController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\NotificationController;

// Partner Controllers
use App\Http\Controllers\Api\V1\Partner\DashboardController as PartnerDashboardController;
use App\Http\Controllers\Api\V1\Partner\OrderController as PartnerOrderController;
use App\Http\Controllers\Api\V1\Partner\ProfileController as PartnerProfileController;
use App\Http\Controllers\Api\V1\Partner\StatusController as PartnerStatusController;
use App\Http\Controllers\Api\V1\Partner\EarningController as PartnerEarningController;

// Admin Controllers
use App\Http\Controllers\Api\V1\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\Admin\PartnerVerificationController as AdminPartnerVerificationController;
use App\Http\Controllers\Api\V1\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\V1\Admin\PricingController as AdminPricingController;
use App\Http\Controllers\Api\V1\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\V1\Admin\ReportController as AdminReportController;

Route::prefix('v1')->group(function () {

    // ==========================================
    // Public Endpoints
    // ==========================================
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/auth/register', [RegisterController::class, 'register']);
        Route::post('/auth/login', [LoginController::class, 'login']);
    });

    Route::middleware('throttle:api')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{slug}', [CategoryController::class, 'show']);

        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{slug}', [ServiceController::class, 'show']);

        Route::post('/estimates', [EstimateController::class, 'calculate']);

        Route::get('/technicians', [TechnicianController::class, 'index']);
        Route::get('/technicians/{id}', [TechnicianController::class, 'show']);
    });

    // ==========================================
    // Authenticated Endpoints (Sanctum)
    // ==========================================
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

        // Profile & Auth
        Route::post('/auth/logout', [LogoutController::class, 'logout']);
        Route::get('/auth/me', [ProfileController::class, 'show']);
        Route::get('/auth/profile', [ProfileController::class, 'me']);
        Route::put('/auth/profile', [ProfileController::class, 'update']);

        // Notifications (All authenticated users)
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        // Shared Order Cancellation & Photo Upload
        Route::post('/orders/{id}/cancel', [OrderCancelController::class, 'cancel']);
        Route::post('/orders/{id}/photos', [OrderPhotoController::class, 'upload']);

        // ------------------------------------------
        // Customer Specific Endpoints
        // ------------------------------------------
        Route::middleware('role:customer')->group(function () {
            Route::get('/orders', [OrderController::class, 'index']);
            Route::post('/orders', [OrderController::class, 'store']);
            Route::get('/orders/{id}', [OrderController::class, 'show']);

            Route::post('/reviews', [ReviewController::class, 'store']);

            Route::apiResource('addresses', AddressController::class);
        });

        // ------------------------------------------
        // Partner / Technician Endpoints
        // ------------------------------------------
        Route::middleware('role:partner')->prefix('partner')->group(function () {
            Route::get('/dashboard', [PartnerDashboardController::class, 'index']);
            Route::get('/profile', [PartnerProfileController::class, 'show']);
            Route::post('/profile', [PartnerProfileController::class, 'update']);
            Route::post('/status', [PartnerStatusController::class, 'update']);
            Route::get('/earnings', [PartnerEarningController::class, 'index']);

            Route::get('/orders', [PartnerOrderController::class, 'index']);
            Route::get('/orders/available', [PartnerOrderController::class, 'available']);
            Route::get('/orders/{id}', [PartnerOrderController::class, 'show']);
            Route::post('/orders/{id}/accept', [PartnerOrderController::class, 'accept']);
            Route::post('/orders/{id}/reject', [PartnerOrderController::class, 'reject']);
            Route::patch('/orders/{id}/status', [PartnerOrderController::class, 'updateStatus']);
        });

        // ------------------------------------------
        // Admin Endpoints
        // ------------------------------------------
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);

            // User management
            Route::get('/users', [AdminUserController::class, 'index']);
            Route::get('/users/{id}', [AdminUserController::class, 'show']);
            Route::patch('/users/{id}/status', [AdminUserController::class, 'updateStatus']);

            // Partner verifications
            Route::get('/partners/verification', [AdminPartnerVerificationController::class, 'index']);
            Route::post('/partners/{id}/verify', [AdminPartnerVerificationController::class, 'verify']);
            Route::post('/partners/{id}/reject', [AdminPartnerVerificationController::class, 'reject']);

            // Orders management
            Route::get('/orders', [AdminOrderController::class, 'index']);
            Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
            Route::post('/orders/{id}/assign', [AdminOrderController::class, 'assignPartner']);
            Route::post('/orders/{id}/cancel', [AdminOrderController::class, 'cancel']);

            // Services & Categories
            Route::post('/categories', [AdminServiceController::class, 'storeCategory']);
            Route::put('/categories/{id}', [AdminServiceController::class, 'updateCategory']);
            Route::post('/services', [AdminServiceController::class, 'storeService']);
            Route::put('/services/{id}', [AdminServiceController::class, 'updateService']);
            Route::delete('/services/{id}', [AdminServiceController::class, 'destroyService']);

            // Pricing rules
            Route::get('/pricing-rules', [AdminPricingController::class, 'index']);
            Route::post('/pricing-rules', [AdminPricingController::class, 'store']);
            Route::put('/pricing-rules/{id}', [AdminPricingController::class, 'update']);
            Route::delete('/pricing-rules/{id}', [AdminPricingController::class, 'destroy']);

            // Review moderation
            Route::get('/reviews', [AdminReviewController::class, 'index']);
            Route::patch('/reviews/{id}/visibility', [AdminReviewController::class, 'toggleVisibility']);
            Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy']);

            // Reports
            Route::get('/reports/transactions', [AdminReportController::class, 'transactions']);
        });

    });
});
