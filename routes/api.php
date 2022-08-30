<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGalleryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::post('upload', [ProductGalleryController::class, 'upload']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('ganti-password', [UserController::class, 'changePassword']);

    // PRODUCTS
    Route::get('products', [ProductController::class, 'all']);
    Route::post('products', [ProductController::class, 'create']);
    Route::delete('products/{id}', [ProductController::class, 'delete']);
    Route::post('products/edit', [ProductController::class, 'updateAll']);

    // CATEGORIES
    Route::get('categories', [ProductCategoryController::class, 'all']);
    Route::post('categories', [ProductCategoryController::class, 'create']);
    Route::post('categories/edit', [ProductCategoryController::class, 'edit']);
    Route::delete('categories/{id}', [ProductCategoryController::class, 'delete']);

    // STORE
    Route::post('store', [StoreController::class, 'create']);
    Route::get('store', [StoreController::class, 'show']);
    Route::post('store-update', [StoreController::class, 'update']);

    // BANK
    Route::post('bank', [BankController::class, 'create']);
    Route::get('bank', [BankController::class, 'all']);
    Route::post('bank/edit', [BankController::class, 'edit']);
    Route::delete('bank/{id}', [BankController::class, 'delete']);

    // ADDRESS
    Route::post('address', [AddressController::class, 'create']);
    Route::get('address', [AddressController::class, 'all']);
    Route::post('address/edit', [AddressController::class, 'edit']);
    Route::delete('address/{id}', [AddressController::class, 'delete']);

    // VOUCHER
    Route::post('voucher', [VoucherController::class, 'create']);
    Route::get('voucher', [VoucherController::class, 'all']);

    // COURIER
    Route::post('courier', [CourierController::class, 'create']);
    Route::get('courier', [CourierController::class, 'show']);

    // TRANSACTION
    Route::post('transaction', [TransactionController::class, 'create']);
    Route::get('transaction', [TransactionController::class, 'all']);
    Route::post('transaction/edit', [TransactionController::class, 'edit']);

});


