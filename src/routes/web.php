<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/{item}/like', [LikeController::class, 'store'])->name('items.like.store');
    Route::delete('/items/{item}/like', [LikeController::class, 'destroy'])->name('items.like.destroy');
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('items.comments.store');
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

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
