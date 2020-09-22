<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoListController;
use App\Models\Todo;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function() {

    Route::post('todos',[TodoController::class,'store']);
    Route::get('todos/{id}',[TodoController::class,'show']);
    Route::put('todos',[TodoController::class,'update']);
    Route::delete('todos/{id}',[TodoController::class,'destroy']);

    Route::get('todo/{list_id}/todos',[TodoController::class,'index']);

    Route::post('todo',[TodoListController::class,'store']);
    Route::get('todo',[TodoListController::class,'index']);
    Route::get('todo/{id}',[TodoListController::class,'show']);
    Route::put('todo',[TodoListController::class,'update']);
    Route::delete('todo/{id}',[TodoListController::class,'destroy']);
});

