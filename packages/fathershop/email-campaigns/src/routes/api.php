<?php

use Illuminate\Support\Facades\Route;
use FatherShop\EmailCampaigns\Http\Controllers\CampaignController;
use FatherShop\EmailCampaigns\Http\Controllers\CustomerController;

Route::prefix('api/email-campaigns')->group(function () {
    // Campaign routes
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::post('/campaigns', [CampaignController::class, 'store']);
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show']);
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy']);
    Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send']);
    
    // Customer routes
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers/filter', [CustomerController::class, 'filter']);
});