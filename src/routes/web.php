<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\ProfileController;
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

// non-authenticated user route
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// authenticated user route
Route::middleware('auth')->group(function() {
    Route::get('/mypage', function () {
        return view('mypage');
    })->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('edit');
    Route::get('/mypage/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/sell', [ExhibitionController::class, 'create'])->name('sell');
});

/*Route::middleware('auth')->group(function () {
    Route::get('/profile', function() {
        return view('profile');
    })->name('profile');

    Route::get('/edit', function () {
    return view('edit');
    })->name('edit');
/*});*/

Route::get('/edit', function () {
    return view('edit');
})->name('edit');