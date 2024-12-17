<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    SupplierController,
    BorrowerController,
    CategoryController,
    ItemController,
    AdminActivityController,
    
    StafBorrowerController,
    StafItemController,
    StafTeacherController,
    StafStudentController,
    StafTransactionsInController,
    StafTransactionsOutController,
    ActivityController,
    
    TransactionsInController,
    TransactionsOutController,
    LoansItemController,
    DetailItemController,
    ProfileController,
    ReturnsItemController,
    TeacherController,
    UserController,
    StudentController
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
Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('admin.index');

// Rute khusus admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    // Route::get('/admin/dashboard', function () {
    //     return view('admin.index');
    // })->name('admin.index')

    
    Route::resource('teacher', TeacherController::class);
    Route::resource('users', UserController::class);
    Route::resource('stundent', StudentController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/Items', ItemController::class);
    Route::resource('/suppliers', SupplierController::class);

    Route::get('borrowers', [BorrowerController::class, 'index'])->name('borrowers.index');
    Route::get('borrowers/create', [BorrowerController::class, 'create'])->name('borrowers.create');
    Route::post('borrowers', [BorrowerController::class, 'store'])->name('borrowers.store');
    Route::get('borrowers/{id}/edit', [BorrowerController::class, 'edit'])->name('borrowers.edit');
    Route::put('borrowers/{id}', [BorrowerController::class, 'update'])->name('borrowers.update');
    Route::delete('/borrowers/{borrowerType}/{borrowerId}', [BorrowerController::class, 'destroy'])->name('borrowers.destroy');
    
    Route::post('/loans_item/return/{id}', [ReturnsItemController::class, 'returnItem'])->name('loans_item.return');
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
    
    Route::get('/adminactivities', [AdminActivityController::class, 'index'])->name('admin.activities.index');
    Route::delete('/adminactivities/{id}', [AdminActivityController::class, 'destroy'])->name('admin.activities.destroy');
});

// Rute khusus staff
Route::middleware(['auth', 'is_staff'])->group(function () {
    Route::get('/staff/dashboard', [StafItemController::class, 'index'])->name('staff.index');
    Route::get('/staff/items', [StafItemController::class, 'list'])->name('staff.items.list');
    
    Route::get('stafborrower', [StafBorrowerController::class, 'index'])->name('staff.borrower.index');
    Route::get('stafborrower/create', [StafBorrowerController::class, 'create'])->name('staff.borrower.create');
    Route::post('stafborrower', [StafBorrowerController::class, 'store'])->name('staff.borrower.store');
    Route::get('stafborrower/{id}/edit', [StafBorrowerController::class, 'edit'])->name('staff.borrower.edit');
    Route::put('stafborrower/{id}', [StafBorrowerController::class, 'update'])->name('staff.borrower.update');
    Route::delete('stafborrower/{borrowerType}/{borrowerId}', [StafBorrowerController::class, 'destroy'])->name('staff.borrower.destroy');
    
    Route::get('/stafteacher', [StafTeacherController::class, 'index'])->name('stafteacher.index');
    Route::get('/stafteacher/create', [StafTeacherController::class, 'create'])->name('stafteacher.create');
    Route::post('/stafteacher', [StafTeacherController::class, 'store'])->name('stafteacher.store');
    Route::get('/stafteacher/{id}/edit', [StafTeacherController::class, 'edit'])->name('stafteacher.edit');
    Route::put('/stafteacher/{id}', [StafTeacherController::class, 'update'])->name('stafteacher.update');
    Route::delete('/stafteacher/{id}', [StafTeacherController::class, 'destroy'])->name('stafteacher.destroy');
    
    Route::get('/stafstudent', [StafStudentController::class, 'index'])->name('stafstudent.index');
    Route::get('/stafstudent/create', [StafStudentController::class, 'create'])->name('stafstudent.create');
    Route::post('/stafstudent', [StafStudentController::class, 'store'])->name('stafstudent.store');
    Route::get('/stafstudent/{id}/edit', [StafStudentController::class, 'edit'])->name('stafstudent.edit');
    Route::put('/stafstudent/{id}', [StafStudentController::class, 'update'])->name('stafstudent.update');
    Route::delete('/stafstudent/{id}', [StafStudentController::class, 'destroy'])->name('stafstudent.destroy');

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

use App\Exports\TransactionsOutExport;
use App\Exports\TransactionsIntExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/transactions/export/{month}', function ($month) {
    return Excel::download(new TransactionsOutExport($month), "transactions_out_month_{$month}.xlsx");
})->name('transactions.export');
Route::get('/transactions/export/{month}', function ($month) {
    return Excel::download(new TransactionsIntExport($month), "transactions_ins_month_{$month}.xlsx");
})->name('transactions.export');
