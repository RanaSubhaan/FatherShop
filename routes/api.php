<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\CampaignController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/customers/import', [CustomerController::class, 'import']);
Route::get('/customers/filter', [CustomerController::class, 'filter']);
Route::post('/campaigns', [CampaignController::class, 'create']);
Route::post('/campaigns/send', [CampaignController::class, 'send']);