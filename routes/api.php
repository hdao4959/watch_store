<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ControllerForForm;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\HomeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/admin')->group(function(){
    Route::apiResource('/categories', CategoryController::class);
    Route::controller(ControllerForForm::class)->group(function(){
            Route::get('parentCategories', 'parentCategories');
            Route::get('allCategories', 'allCategories');
            Route::get('allSizes', 'allSizes');
            Route::get('allColors', 'allColors');
    });
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/sizes', SizeController::class);
    Route::apiResource('/colors', ColorController::class);
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/categories', [ClientCategoryController::class, 'index']);