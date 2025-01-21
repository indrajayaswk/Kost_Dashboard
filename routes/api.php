<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\BotMenuController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MenuController;



// Check tenant route
Route::post('/check-tenant', [BotController::class, 'checkTenant']);

// Broadcast message route
Route::post('/broadcast/send', [BroadcastController::class, 'send']);
Route::post('/midtrans/webhook', [MidtransController::class, 'handleWebhook']);



// ----------------------------------------------------------

Route::post('/handle-message', [BotController::class, 'handleMessage']);
// Route::post('/broadcast', [BotController::class, 'broadcast']);




//-----------------------------------
//this is testing changes where logic for bot option to controller
Route::post('/whatsapp-message', [BotController::class, 'handleMessage']);



//-----------------------------------testing-----saved in git
Route::get('/rooms/available', [RoomController::class, 'availableRooms']);


///-------------------------testing new bot design/cleaned version
Route::post('/menu-options', [BotMenuController::class, 'getMenuOptions']);



