<?php

use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\VoteController;
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

Route::post("register", [AuthController::class,'register']);
Route::post("login", [AuthController::class,'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("user", [AuthController::class,'getUser']);

    Route::get("interests", [InterestController::class,'index']);
    Route::get("accounts", [AccountTypeController::class,'index']);
    Route::post("post/create", [PostController::class,'store']);
    Route::get("posts", [PostController::class,'index']);

    Route::post("reply/create", [ReplyController::class,'store']);
    Route::get("replies", [ReplyController::class,'index']);


    Route::post("vote", [VoteController::class,'store']);

});
