<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SchemeController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\SathiController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\AlertController;
use App\Http\Controllers\Api\V1\FamilyController;
use App\Http\Controllers\Api\V1\LifeEventController;
use App\Http\Controllers\Api\V1\OpportunityController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\Csc\CscDashboardController;
use App\Http\Controllers\Api\V1\Csc\CscCustomerController;
use App\Http\Controllers\Api\V1\Csc\CscEarningController;
use App\Http\Controllers\Api\V1\Csc\CscAgentController;
use App\Http\Controllers\Api\V1\Csc\CscToolkitController;
use App\Http\Controllers\Api\V1\EmailAuthController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\WebhookController;
use App\Http\Controllers\Api\V1\HunarController;
use App\Http\Controllers\Api\V1\AgentController;

// ---------- PUBLIC ROUTES ----------

Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/auth/otp/send',   [AuthController::class, 'sendOtp']);
    Route::post('/auth/otp/verify', [AuthController::class, 'verifyOtp']);

    // Email Auth
    Route::post('/auth/email/otp/send',   [EmailAuthController::class, 'sendOtp']);
    Route::post('/auth/email/otp/verify', [EmailAuthController::class, 'verifyOtp']);

    // Search
    Route::get('/search',         [SearchController::class, 'search']);
    Route::get('/search/suggest', [SearchController::class, 'suggest']);

    // Life events
    Route::get('/life-events', [LifeEventController::class, 'index']);

    // Location
    Route::prefix('location')->group(function () {
        Route::get('/states',                          [LocationController::class, 'states']);
        Route::get('/districts/{stateId}',             [LocationController::class, 'districts']);
        Route::get('/districts-by-code/{code}',        [LocationController::class, 'districtsByCode']);
        Route::get('/blocks/{districtId}',             [LocationController::class, 'blocks']);
        Route::get('/pincode/{pincode}',               [LocationController::class, 'lookupPincode']);
        Route::get('/subdistricts/{districtId}',       [LocationController::class, 'subdistricts']);
        Route::get('/gram-panchayats/{subdistrictId}', [LocationController::class, 'gramPanchayats']);
        Route::get('/blocks-search', [LocationController::class, 'blocksSearch']);
    });

    // Agents - public
    Route::get('/agents/nearby', [CscAgentController::class, 'nearby']);
    Route::get('/agents/{id}',   [CscAgentController::class, 'show']);

    // Plans - public
    Route::get('/plans', [SubscriptionController::class, 'plans']);

    // Portal status - public
    Route::get('/csc/toolkit/portal-status', [CscToolkitController::class, 'portalStatus']);

    // Razorpay webhook
    Route::post('/webhooks/razorpay', [WebhookController::class, 'razorpay']);

    // Hunar Directory - PUBLIC (no auth needed)
    Route::prefix('hunar')->group(function () {
        Route::get('/categories',             [HunarController::class, 'categories']);
        Route::get('/categories/{id}/types',  [HunarController::class, 'types']);
        Route::get('/search',                 [HunarController::class, 'search']);
        Route::get('/providers/{id}',         [HunarController::class, 'providerDetail']);
        Route::get('/providers/{id}/reviews', [HunarController::class, 'reviews']);
    });

    // Opportunities — public read (listing, detail, categories, deadlines)
    Route::prefix('opportunities')->group(function () {
        Route::get('/',            [OpportunityController::class, 'index']);
        Route::get('/categories',  [OpportunityController::class, 'categories']);
        Route::get('/deadlines',   [OpportunityController::class, 'deadlines']);
        // Constraint ensures /saved and /matched in auth group take priority over this wildcard
        Route::get('/{opportunity}', [OpportunityController::class, 'show'])->where('opportunity', '[0-9]+');
    });

});

// ---------- AUTHENTICATED ROUTES ----------

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // Agent register & review
    Route::post('/agents/register',    [CscAgentController::class, 'register']);
    Route::post('/agents/{id}/review', [CscAgentController::class, 'review']);

    // Profile
    Route::get('/profile',              [ProfileController::class, 'show']);
    Route::put('/profile',              [ProfileController::class, 'update']);
    Route::get('/profile/nagrik-score', [ProfileController::class, 'nagrikScore']);
    Route::put('/profile/location',     [LocationController::class, 'updateLocation']);

    // Schemes
    Route::get('/schemes',               [SchemeController::class, 'index']);
    Route::get('/schemes/matched',       [SchemeController::class, 'matched']);
    Route::get('/schemes/benefit-total', [SchemeController::class, 'benefitTotal']);
    Route::get('/schemes/{id}',          [SchemeController::class, 'show']);

    // Sathi AI
    Route::post('/sathi/message', [SathiController::class, 'message'])->middleware('check.ai.limit');
    Route::get('/sathi/history',  [SathiController::class, 'history']);
    Route::post('/sathi/task',    [SathiController::class, 'createTask']);

    // Documents
    Route::get('/documents',           [DocumentController::class, 'index']);
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('check.doc.limit');
    Route::post('/documents/{id}/ocr', [DocumentController::class, 'ocr']);
    Route::get('/documents/expiring',  [DocumentController::class, 'expiring']);

    // Applications
    Route::get('/applications',                 [ApplicationController::class, 'index']);
    Route::post('/applications',                [ApplicationController::class, 'store']);
    Route::post('/applications/{id}/grievance', [ApplicationController::class, 'grievance']);

    // Alerts
    Route::get('/alerts',           [AlertController::class, 'index']);
    Route::put('/alerts/{id}/read', [AlertController::class, 'markRead']);

    // Family
    Route::get('/family/members',         [FamilyController::class, 'index']);
    Route::post('/family/members', [FamilyController::class, 'store'])->middleware('check.family.limit');
    Route::delete('/family/members/{id}', [FamilyController::class, 'destroy']);

    // Life Events
    Route::post('/life-events/{type}', [LifeEventController::class, 'trigger']);

    // Opportunities — auth-only write + matched + saved list
    Route::prefix('opportunities')->group(function () {
        Route::get('/matched',                [OpportunityController::class, 'matched']);
        Route::get('/saved',                  [OpportunityController::class, 'saved']);
        Route::post('/{opportunity}/save',    [OpportunityController::class, 'save']);
        Route::post('/{opportunity}/applied', [OpportunityController::class, 'markApplied']);
    });

    // Subscriptions
    Route::prefix('subscriptions')->group(function () {
        Route::get('/current',         [SubscriptionController::class, 'current']);
        Route::post('/create-order',   [SubscriptionController::class, 'createOrder']);
        Route::post('/verify-payment', [SubscriptionController::class, 'verifyPayment']);
        Route::post('/cancel',         [SubscriptionController::class, 'cancel']);
        Route::get('/history',         [SubscriptionController::class, 'history']);
    });

    // Hunar Directory - PROTECTED
    Route::prefix('hunar')->group(function () {
        Route::post('/providers/{id}/contact', [HunarController::class, 'logContact']);
        Route::get('/my-services',             [HunarController::class, 'myServices']);
        Route::post('/my-services',            [HunarController::class, 'addService']);
        Route::put('/my-services/{id}',        [HunarController::class, 'updateService']);
        Route::delete('/my-services/{id}',     [HunarController::class, 'removeService']);
        Route::put('/my-services/{id}/pause',  [HunarController::class, 'pauseService']);
        Route::put('/my-services/{id}/resume', [HunarController::class, 'resumeService']);
        Route::get('/privacy',                 [HunarController::class, 'getPrivacy']);
        Route::put('/privacy',                 [HunarController::class, 'updatePrivacy']);
        Route::post('/reviews',                [HunarController::class, 'addReview']);
        Route::post('/report',                 [HunarController::class, 'report']);
    });

});

// ---------- CSC / SEVA MITRA ROUTES ----------

Route::prefix('v1/csc')->middleware(['auth:sanctum', 'role:seva_mitra|admin'])->group(function () {

    Route::get('/dashboard', [CscDashboardController::class, 'index']);

    // Customers
    Route::get('/customers',               [CscCustomerController::class, 'index']);
    Route::post('/customers', [CscCustomerController::class, 'store'])->middleware('check.csc.queue');
    Route::put('/customers/{id}/start',    [CscCustomerController::class, 'start']);
    Route::put('/customers/{id}/complete', [CscCustomerController::class, 'complete']);
    Route::put('/customers/{id}/cancel',   [CscCustomerController::class, 'cancel']);
    Route::get('/customers/report',        [CscCustomerController::class, 'dailyReport']);

    // Earnings
    Route::get('/earnings',           [CscEarningController::class, 'index']);
    Route::get('/earnings/summary',   [CscEarningController::class, 'summary']);
    Route::post('/earnings/withdraw', [CscEarningController::class, 'requestWithdrawal']);

    // Agent Profile
    Route::get('/profile', [CscAgentController::class, 'profile']);
    Route::put('/profile', [CscAgentController::class, 'updateProfile']);

    // Toolkit
    Route::prefix('toolkit')->group(function () {
        Route::get('/queue',               [CscToolkitController::class, 'queue']);
        Route::post('/queue',              [CscToolkitController::class, 'addToQueue']);
        Route::put('/queue/{id}/start',    [CscToolkitController::class, 'startTask']);
        Route::put('/queue/{id}/complete', [CscToolkitController::class, 'completeTask']);
        Route::put('/queue/{id}/cancel',   [CscToolkitController::class, 'cancelTask']);
        Route::get('/queue/report',        [CscToolkitController::class, 'dailyReport']);
        Route::get('/vault/{customerId}',  [CscToolkitController::class, 'vault']);
        Route::post('/vault/upload',       [CscToolkitController::class, 'uploadForCustomer']);
    });

});