<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\UserAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'mainPage'])->name('main');
Route::get('/about', [MainController::class, 'aboutPage'])->name('about');
Route::get('/books', [BookController::class, 'index'])->name('books.index');

Route::middleware(['guest'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });
});

Route::middleware('auth')->group(function () {
    Route::middleware(UserAccess::class . ':user')->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/transaction', [CartController::class, 'storeTransaction'])->name('cart.transaction');
        Route::get('/transaction', [CartController::class, 'index'])->name('transaction.index');

        Route::get('/checkout/history', [TransactionController::class, 'paymentHistory'])->name('transaction.paymentHistory');
        Route::post('/checkout/history', [TransactionController::class, 'updateStatus'])->name('transaction.updateStatus');
        Route::get('/transaction/details/{id}', [TransactionController::class, 'paymentDetails'])->name('transaction.paymentDetails');
        Route::get('/checkout/invoice/pdf', [TransactionController::class, 'generatePDF'])->name('transaction.pdf');
        Route::get('/checkout/invoice/pdf/{id}', [TransactionController::class, 'generateSinglePDF'])->name('transaction.pdf.single');

        Route::get('/profile', [MainController::class, 'profilePage'])->name('profile');

        Route::get('/contact-admin', [ContactController::class, 'create'])->name('contact');
        Route::post('/contact-admin', [ContactController::class, 'store'])->name('contact.store');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
