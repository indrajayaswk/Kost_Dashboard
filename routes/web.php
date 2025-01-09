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
// ---------------------------------------------------------------
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantRoomController;
use App\Models\Meter;
use App\Models\TenantRoom;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // Penghuni (Tenant) routes
    // Route::resource('penghuni', PenghuniController::class);

    // //Kamar rouites
    // Route::resource('kamar', KamarController::class);
    // //Meteran routes
    // Route::resource('meteran', MeteranController::class);


    // Other routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Other management routes
    // Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');
    // Route::get('/meteran', [MeteranController::class, 'index'])->name('meteran.index');
    Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain.index');
    // Route::get('/broadcast', [BroadcastController::class, 'index'])->name('broadcast.index');

    // broadcast routes
    //INI ROUTE RESOURCE broadcast cuman buat 2 index dan store aja karena broadcast perlu 2 itu aja untuk sekarang
    Route::resource('broadcast', BroadcastController::class)->only(['index', 'store']);
    Route::post('/broadcast/send', [BroadcastController::class, 'send'])->name('broadcast.send');
    //----------------middleware untuk cek dan akses foto ktp di private--------------------------
    Route::middleware(['auth'])->get('/ktp/images/{filename}', [KTPImageController::class, 'show'])->name('ktp.image.show');




    // ---------------------------------------------------------ADMIN2-------------------------------------------
    Route::resource('tenant',TenantController::class);
    Route::resource('room', RoomController::class);
    Route::resource('meter',MeterController::class);
    Route::resource('tenant-room',TenantRoomController::class);
    
    //-----------------------------------testing----------------------------------
    
});

require __DIR__.'/auth.php';
