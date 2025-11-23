<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PerkController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\StaticPageController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PageContentController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\InboxController;
use App\Http\Controllers\Api\Admin\JournalController as AdminJournalController;
use App\Http\Controllers\Api\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Api\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Api\Admin\PerkController as AdminPerkController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\Admin\StaticPageController as AdminStaticPageController;
use App\Http\Controllers\Api\Admin\SubcategoryController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\PageContentController as AdminPageContentController;
use App\Http\Controllers\Api\Admin\AnalyticsController;
use App\Http\Controllers\Api\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::prefix('v1')->group(function () {
    
    // Perks
    Route::get('/perks', [PerkController::class, 'index']);
    Route::get('/perks/{slug}', [PerkController::class, 'show']);
    Route::post('/perks/{slug}/view', [PerkController::class, 'incrementView']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    
    // Locations
    Route::get('/locations', [LocationController::class, 'index']);
    
    // Static Pages
    Route::get('/pages', [StaticPageController::class, 'index']);
    Route::get('/pages/{slug}', [StaticPageController::class, 'show']);

    // Journal (uses StaticPage under the hood)
    Route::get('/journal', [JournalController::class, 'index']);
    Route::get('/journal/{slug}', [JournalController::class, 'show']);

    // Media proxy for external images
    Route::get('/media/proxy', [MediaController::class, 'proxy'])->name('media.proxy');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index']);

    // Page Content (CMS)
    Route::get('/page-content/{pageName}', [PageContentController::class, 'show']);
    Route::get('/page-content/section/{sectionKey}', [PageContentController::class, 'getSection']);

    // Lead Forms
    Route::post('/leads/perk-claim', [LeadController::class, 'perkClaim']);
    Route::post('/leads/partner-inquiry', [LeadController::class, 'partnerInquiry']);
    Route::post('/leads/contact', [LeadController::class, 'contact']);

    // Analytics Tracking
    Route::post('/track/impression', [TrackingController::class, 'trackImpression']);
    Route::post('/track/click', [TrackingController::class, 'trackClick']);
    Route::post('/track/form-submission', [TrackingController::class, 'trackFormSubmission']);
    Route::post('/track/affiliate-click', [TrackingController::class, 'trackAffiliateClick']);

    // Auth
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Protected Auth Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });
});

// Admin Routes (Protected)
Route::prefix('v1/admin')->middleware(['auth:sanctum'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/chart-data', [DashboardController::class, 'chartData']);
    
    // Perks Management
    Route::apiResource('perks', AdminPerkController::class);
    Route::post('/perks/{id}/publish', [AdminPerkController::class, 'publish']);
    Route::post('/perks/bulk-update', [AdminPerkController::class, 'bulkUpdate']);
    
    // Categories Management
    Route::apiResource('categories', AdminCategoryController::class);
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('locations', AdminLocationController::class);
    
    // Leads Management
    Route::get('/leads', [AdminLeadController::class, 'index']);
    Route::get('/leads/{id}', [AdminLeadController::class, 'show']);
    Route::delete('/leads/{id}', [AdminLeadController::class, 'destroy']);
    Route::get('/leads/export', [AdminLeadController::class, 'export']);

    // Inbox Management
    Route::get('/inbox', [InboxController::class, 'index']);
    Route::get('/inbox/{id}', [InboxController::class, 'show']);
    Route::post('/inbox/{id}/mark-read', [InboxController::class, 'markAsRead']);
    Route::post('/inbox/{id}/mark-unread', [InboxController::class, 'markAsUnread']);
    Route::delete('/inbox/{id}', [InboxController::class, 'destroy']);
    Route::post('/inbox/bulk-mark-read', [InboxController::class, 'bulkMarkAsRead']);
    Route::post('/inbox/bulk-delete', [InboxController::class, 'bulkDelete']);

    // Journal Management
    Route::apiResource('journal', AdminJournalController::class);

    // Static Pages Management
    Route::apiResource('pages', AdminStaticPageController::class);
    
    // Settings Management
    Route::get('/settings', [AdminSettingController::class, 'index']);
    Route::post('/settings', [AdminSettingController::class, 'update']);

    // Page Content Management (CMS for Content Editor)
    Route::get('/page-content', [AdminPageContentController::class, 'index']);
    Route::get('/page-content/pages', [AdminPageContentController::class, 'getPages']);
    Route::get('/page-content/{pageName}', [AdminPageContentController::class, 'show']);
    Route::post('/page-content/update', [AdminPageContentController::class, 'update']);
    Route::post('/page-content/bulk-update', [AdminPageContentController::class, 'bulkUpdate']);
    Route::post('/page-content/upload-image', [AdminPageContentController::class, 'uploadImage']);
    Route::delete('/page-content/{id}', [AdminPageContentController::class, 'destroy']);

    // Analytics
    Route::get('/analytics/dashboard', [AnalyticsController::class, 'getDashboardStats']);
    Route::get('/analytics/perk/{perkId}', [AnalyticsController::class, 'getPerformanceByPerk']);

    // User Management (Super Admin only)
    // Route::apiResource('users', AdminUserController::class);
});
