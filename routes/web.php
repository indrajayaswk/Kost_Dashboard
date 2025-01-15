<?php

use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\MeteranController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;
use App\Models\Meteran;
use App\Models\Tenant;
use App\Http\Controllers\KTPImageController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantRoomController;
use App\Models\Meter;
use App\Models\TenantRoom;
use App\Http\Controllers\MidtransController;




use App\Http\Controllers\MidtransNotificationController;




// Default route to login page
Route::get('/', function () {
    return view('auth.login');
});

// Group of routes protected by auth and verified middleware
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Statistics and complaints routes
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');
    Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain.index');

    // Broadcast management
    Route::resource('broadcast', BroadcastController::class)->only(['index', 'store']);
    Route::post('/broadcast/send', [BroadcastController::class, 'send'])->name('broadcast.send');

    // KTP images access route with middleware for authentication
    Route::middleware(['auth'])->get('/ktp/images/{filename}', [KTPImageController::class, 'show'])->name('ktp.image.show');

    // Admin routes for managing tenants, rooms, meters, and tenant rooms
    Route::resource('tenant', TenantController::class);
    Route::resource('room', RoomController::class);
    Route::resource('meter', MeterController::class);
    Route::resource('tenant-room', TenantRoomController::class);

    // Midtrans integration routes
    Route::get('/midtrans', [MidtransController::class, 'index'])->name('midtrans.index');
    // Route::post('/midtrans/create-payment', [MidtransController::class, 'createPayment'])->name('midtrans.create-payment');
    Route::get('/midtrans/meter', [MidtransController::class, 'showMeter'])->name('midtrans.show-meter');
    Route::post('/midtrans/create-invoice', [MidtransController::class, 'createInvoice'])->name('midtrans.create-invoice');
    
    

});


require __DIR__.'/auth.php';
