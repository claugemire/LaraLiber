<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/books/{user_id}/{api_secret}', [App\Http\Controllers\BookController::class, 'apiBooks']);

Route::post('/books/store', [App\Http\Controllers\BookController::class, 'store'])->name('store');

Route::delete('/books/{id}', [App\Http\Controllers\BookController::class, 'destroy'])->name('delete');
Route::put('/books/{id}', [App\Http\Controllers\BookController::class, 'update'])->name('update');
Route::patch('/books/{id}', [App\Http\Controllers\BookController::class, 'patch'])->name('patch');
