<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

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

// public route
Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

// non-authenticated user route
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// authenticated user route
Route::middleware('auth')->group(function() {
    Route::post('/items/{item}/like', [LikeController::class, 'store'])->name('item.like');
    Route::post('/item/{item}/comment', [CommentController::class, 'store'])->name('item.comment');

    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.purchase');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'edit'])->name('purchase.address');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::get('/sell', [ExhibitionController::class, 'create'])->name('sell');
    Route::post('/sell', [ExhibitionController::class, 'store'])->name('sell');
});




