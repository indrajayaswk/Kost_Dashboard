<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\BroadcastController;

// Check tenant route
Route::post('/check-tenant', [BotController::class, 'checkTenant']);

// Broadcast message route
Route::post('/broadcast/send', [BroadcastController::class, 'send']);
