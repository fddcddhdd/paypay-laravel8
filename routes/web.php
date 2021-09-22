<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaymentController;

// トップ画面
Route::resource('/', UserController::class);

// ログインした一般ユーザ
Route::group(['prefix' => '/user', 'middleware' => ['auth']], function () {
    // アップロードファイルの購入
    Route::get('/purchase/{id}', [PurchaseController::class,'purchase' ]);
});

// アップロードファイルのダウンロード(無料ファイルは、未ログインでもダウンロードさせたいので)
Route::get('/download/{id}', [UploadController::class,'download' ]);

// admin以下は管理者のみアクセス可
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'can:admin']], function () {
    Route::resource('/upload', UploadController::class);
    Route::resource('/', AdminController::class);
});  

// 決済完了
Route::get('/thanks', [PurchaseController::class,'thanks' ]);


Route::get('/dashboard', function () {
    // return view('dashboard');
    return view('index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
