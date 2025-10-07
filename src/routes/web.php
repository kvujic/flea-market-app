<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RatingController;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Support\Facades\Route;



// public route
Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

// non-authenticated users route
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// email-verified users route
Route::middleware(['auth', 'verified'])->group(function() {
    Route::post('/item/{item}/like', [LikeController::class, 'store'])->name('item.like');
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

// authenticated users route
Route::middleware('auth')->group(function() {
    // mail verification
    Route::get('/email/verify', [EmailVerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
});

// transaction
Route::middleware(['auth'])->group(function() {
    // transaction list　？
    //Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    //start chat from item page (purchase_id null)
    Route::get('/item/{item}/transaction', [TransactionController::class, 'openChat'])->name('item.chats.open');
    // show conversation
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    // send message
    Route::post('/transactions/{chat}/messages', [ChatController::class, 'store'])->name('transactions.messages.store');
    // mark as read
    Route::post('/transactions/{chat}/read', [ChatController::class, 'markRead'])->name('transactions.messages.read');
    // linking completed transactions to purchases
    Route::post('/transactions/{chat}/bind-purchase', [TransactionController::class, 'bindPurchase'])->name('transactions.bindPurchase');
    // edit and delete messages
    Route::patch('/transactions/{chat}/messages/{message}', [ChatController::class, 'update'])->name('transactions.messages.update');
    Route::delete('/transactions/{chat}/messages/{message}', [ChatController::class, 'destroy'])->name('transactions.messages.destroy');

    // rating
    Route::post('/transaction/{chat}/rate', [RatingController::class, 'rate'])->name('transactions.rate');
});



