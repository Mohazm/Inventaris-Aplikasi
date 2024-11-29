<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute utama dengan middleware auth
Route::get('/', [DashboardController::class, 'index'])->middleware('auth');

// Rute untuk dashboard admin dan staff tanpa menggunakan middleware can
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::get('/staff/dashboard', function () {
        return view('staff.index');
    })->name('staff.index');
});

// Rute untuk profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Menyertakan rute otentikasi (login, register, dll)
require __DIR__ . '/auth.php';
