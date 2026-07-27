<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

use Illuminate\Support\Facades\Route;

// ==========================================
// 共通・一般公開ルート（未認証）
// ==========================================

//商品一覧
Route::get('/', [ItemController::class, 'index'])->name('items.index');
//商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

// ==========================================
// ログインユーザー限定ルート（要認証）
// ==========================================
Route::middleware(['auth'])->group(function () {

    //商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    //商品購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchaseShow'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchaseStore'])->name('purchase.store');

    //送付先住所変更
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'addressEdit'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'addressStore'])->name('purchase.address.store');

    //コメント
    Route::post('/item/{item_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    //マイリスト
    Route::post('/item/{item_id}/favorites', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::prefix('mypage')->name('mypage.')->group(function () {
        //プロフィール画面
        Route::get('/', [ProfileController::class, 'index'])->name('index');

        //プロフィール初回設定・編集画面（表示と保存を共通化）
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); //2回目以降の更新用
        Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store'); //初回保存用
    });
});
