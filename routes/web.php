<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    SupplierController,
    BorrowerController,
    CategoryController,
    ItemController,
    TendikController,
    StafItemController,
    StafTendikController,
    StafTransactionsInController,
    StafTransactionsOutController,
    TransactionsInController,
    TransactionsOutController,
    LoansItemController,
    ActivityController,
    DetailItemController,
    ProfileController
};

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
    Route::resource('borrowers', BorrowerController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/Items', ItemController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/tendiks', TendikController::class);

    Route::get('items/{itemId}/details', [DetailItemController::class, 'index'])->name('details.index');
    Route::get('details/{kode_barang}/edit', [DetailItemController::class, 'edit'])->name('details.edit');
    Route::put('details/{kode_barang}', [DetailItemController::class, 'update'])->name('details.update');
    Route::delete('details/{kode_barang}', [DetailItemController::class, 'destroy'])->name('details.destroy');

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
    Route::post('/loans_item/checkOverdue', [LoansItemController::class, 'checkOverdueLoans'])->name('loans_item.checkOverdue');
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

    // Rute Untuk Transactions Out
    Route::get('/staff/transactions_out', [StafTransactionsOutController::class, 'index'])->name('StafTransactions_out.index');
    Route::get('/staff/transactions_out/create', [StafTransactionsOutController::class, 'create'])->name('StafTransactions_out.create');
    Route::post('/staff/transactions_out', [StafTransactionsOutController::class, 'store'])->name('StafTransactions_out.store');
    Route::get('/staff/transactions_out/{id}/edit', [StafTransactionsOutController::class, 'edit'])->name('StafTransactions_out.edit');
    Route::put('/staff/transactions_out/{id}', [StafTransactionsOutController::class, 'update'])->name('StafTransactions_out.update');
    Route::delete('/staff/transactions_out/{id}', [StafTransactionsOutController::class, 'destroy'])->name('StafTransactions_out.destroy');

    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
});

// Rute untuk profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Menyertakan rute otentikasi (login, register, dll)
require __DIR__ . '/auth.php';
