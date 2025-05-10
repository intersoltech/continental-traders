<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::middleware(['auth'])->group(function () {    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('/vendors', VendorController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/sales', SaleController::class);
    Route::resource('/purchases', PurchaseController::class);
    Route::get('/search-customer', [CustomerController::class, 'search'])->name('customer.search');
    // routes/web.php
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings')->middleware('auth');
    Route::get('/help', [HelpController::class, 'index'])->name('help')->middleware('auth');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/pdf', [ReportController::class, 'downloadPDF'])->name('reports.pdf');
    Route::get('/receipts/{id}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('/receipts/{id}/download', [ReceiptController::class, 'download'])->name('receipts.download');

});
