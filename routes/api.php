<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ControllerForForm;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('/user',  'infor');
        Route::post('/logout',  'logout');
    });
    Route::get('/order_history', [ClientOrderController::class, 'orderHistory']);


    Route::middleware('role:0')->prefix('/admin')->group(function () {
        Route::apiResource('/categories', CategoryController::class);
        Route::controller(ControllerForForm::class)->group(function () {
            Route::get('parentCategories', 'parentCategories');
            Route::get('allCategories', 'allCategories');
            Route::get('allSizes', 'allSizes');
            Route::get('allColors', 'allColors');
        });
        Route::apiResource('/products', ProductController::class);
        Route::apiResource('/sizes', SizeController::class);
        Route::apiResource('/colors', ColorController::class);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });
});


Route::get('/', [HomeController::class, 'index']);
Route::get('/categories', [ClientCategoryController::class, 'index']);
Route::get('/products/{slug}', [ClientProductController::class, 'show']);
Route::post('/cart', [CartController::class, 'getItemsCart']);
Route::post('/checkout', [CartController::class, 'checkout']);
Route::post('/order', [CartController::class, 'order']);
Route::get('/ipn', [CartController::class, 'handleIPN']);

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::post('/order', [CartController::class, 'order']);