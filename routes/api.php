<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\BotController_V3;
use App\Http\Controllers\BotMenuController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UssdBotController;




// Check tenant route
Route::post('/check-tenant', [BotController::class, 'checkTenant']);
// api src_V2 routes for testing
Route::post('/check-tenantv2', [BotController::class, 'checkTenantv2']);

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



///-----------------------------testing pake sessiond ari laravel
// Route::get('/start-bot/{phoneNumber}', [BotController::class, 'startBot']);
// Route::get('/get-state/{phoneNumber}', [BotController::class, 'getUserState']);
// Route::get('/change-state/{phoneNumber}/{newState}', [BotController::class, 'changeUserState']);

// Route::post('/menu-options', [BotController::class, 'handleUserInput']);





Route::prefix('v3')->group(function () {
    Route::post('/handle-message', [BotController_V3::class, 'handleBotRequest']);
});


Route::prefix('v4')->group(function () {
    Route::post('/handle-message', [UssdBotController::class, 'handleMessage']);
});
