<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');

    Route::prefix('items/{item}')->name('items.')->group(function () {
        Route::post('/like', [LikeController::class, 'store'])->name('like.store');
        Route::delete('/like', [LikeController::class, 'destroy'])->name('like.destroy');
        Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    });

    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('/{item}', [PaymentController::class, 'create'])->name('create');
        Route::post('/{item}', [PaymentController::class, 'store'])->name('store');
        Route::get('/success/{item}', [PaymentController::class, 'success'])->name('success');
        Route::get('/address/{item}', [PaymentController::class, 'editAddress'])->name('address.edit');
        Route::post('/address/{item}', [PaymentController::class, 'updateAddress'])->name('address.update');
    });

    Route::prefix('mypage')->name('mypage.')->group(function () {
        Route::get('/', [MypageController::class, 'index'])->name('index');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
