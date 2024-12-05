<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TendikController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StafItemController;
use App\Http\Controllers\StafTendikController;
use App\Http\Controllers\StafTransactionsInController;
use App\Http\Controllers\TransactionsInController;
use App\Http\Controllers\TransactionsOutController;
use App\Http\Controllers\LoansItemController;
use App\Http\Controllers\cekbarangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute utama untuk semua pengguna dengan middleware auth
Route::get('/', [DashboardController::class, 'index'])->middleware('auth');

// Rute khusus admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::resource('/category', CategoryController::class);
    Route::resource('/Items', ItemController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/tendiks', TendikController::class);
    Route::get('cekbarang', [cekbarangcontroller::class, 'index'])->name('cekbarang.index');
    Route::get('cekbarang/create', [cekbarangcontroller::class, 'create'])->name('cekbarang.create');
    Route::post('cekbarang', [cekbarangcontroller::class, 'store'])->name('cekbarang.store');
    Route::get('cekbarang/{id}/edit', [cekbarangcontroller::class, 'edit'])->name('cekbarang.edit');
    Route::put('cekbarang/{id}', [cekbarangcontroller::class, 'update'])->name('cekbarang.update');
    Route::delete('cekbarang/{id}', [cekbarangcontroller::class, 'destroy'])->name('cekbarang.destroy');
    Route::get('cekbarang/{id}', [cekbarangcontroller::class, 'show'])->name('cekbarang.show');


    // Rute Untuk Transactions In
    Route::get('transactions_in', [TransactionsInController::class, 'index'])->name('Transactions_in.index');
    Route::get('transactions_in/create', [TransactionsInController::class, 'create'])->name('Transactions_in.create');
    Route::post('transactions_in', [TransactionsInController::class, 'store'])->name('Transactions_in.store');
    Route::get('transactions_in/{id}/edit', [TransactionsInController::class, 'edit'])->name('Transactions_in.edit');
    Route::put('transactions_in/{id}', [TransactionsInController::class, 'update'])->name('Transactions_in.update');
    Route::delete('transactions_in/{id}', [TransactionsInController::class, 'destroy'])->name('Transactions_in.destroy');
    
    Route::get('transactions_out', [TransactionsOutController::class, 'index'])->name('Transactions_out.index');
    Route::get('transactions_out/create', [TransactionsOutController::class, 'create'])->name('Transactions_out.create');
    Route::post('transactions_out', [TransactionsOutController::class, 'store'])->name('Transactions_out.store');
    Route::get('transactions_out/{id}/edit', [TransactionsOutController::class, 'edit'])->name('Transactions_out.edit');
    Route::put('transactions_out/{id}', [TransactionsOutController::class, 'update'])->name('Transactions_out.update');
    Route::delete('transactions_out/{id}', [TransactionsOutController::class, 'destroy'])->name('Transactions_out.destroy');
    
    Route::get('loans_item', [LoansItemController::class, 'index'])->name('loans_item.index');
    Route::get('loans_item/create', [LoansItemController::class, 'create'])->name('loans_item.create');
    Route::post('loans_item', [LoansItemController::class, 'store'])->name('loans_item.store');
    Route::get('loans_item/{id}/edit', [LoansItemController::class, 'edit'])->name('loans_item.edit');
    Route::put('loans_item/{id}', [LoansItemController::class, 'update'])->name('loans_item.update');
    Route::delete('loans_item/{id}', [LoansItemController::class, 'destroy'])->name('loans_item.destroy');
    Route::patch('/loans_item/accept/{id}', [LoansItemController::class, 'accept'])->name('loans_item.accept');
    Route::patch('/loans_item/cancel/{id}', [LoansItemController::class, 'cancel'])->name('loans_item.cancel');

});

// Rute khusus staff
Route::middleware(['auth', 'is_staff'])->group(function () {
    Route::get('/staff/dashboard', [StafItemController::class, 'index'])->name('staff.index');
    Route::get('/staff/items', [StafItemController::class, 'list'])->name('staff.items.list');
    Route::get('/staff/tendiks', [StafTendikController::class, 'index'])->name('staff.tendiks.index');

    
    // Rute Untuk Transactions In
    Route::get('/staff/transactions_in', [StafTransactionsInController::class, 'index'])->name('StafTransactions_in.index');
    Route::get('/staff/transactions_in/create', [StafTransactionsInController::class, 'create'])->name('StafTransactions_in.create');
    Route::post('/staff/transactions_in', [StafTransactionsInController::class, 'store'])->name('StafTransactions_in.store');
    Route::get('/staff/transactions_in/{id}/edit', [StafTransactionsInController::class, 'edit'])->name('StafTransactions_in.edit');
    Route::put('/staff/transactions_in/{id}', [StafTransactionsInController::class, 'update'])->name('StafTransactions_in.update');
    Route::delete('/staff/transactions_in/{id}', [StafTransactionsInController::class, 'destroy'])->name('StafTransactions_in.destroy');
});

// Rute untuk profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Menyertakan rute otentikasi (login, register, dll)
require __DIR__ . '/auth.php';
