<?php

use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostVotesController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
Route::post("username-exists", [AuthController::class,'userNameExists']);
Route::post("email-exists", [AuthController::class,'emailExists']);
Route::get("posts", [PostController::class,'index']);
Route::get("post/{id}", [PostController::class,'getPostByID']);

Route::get('/auth/google/redirect', [AuthController::class,'handleGoogleRedirect']);
Route::get('/auth/facebook/redirect', [AuthController::class,'handleFaceBookRedirect']);
Route::get('/auth/google/callback', [AuthController::class,'handleProviderCallBack']);
Route::get('/auth/facebook/callback', [AuthController::class,'handleFacebookCallBack']);
Route::get("reply/{id}", [ReplyController::class,'getReplyByID']);
Route::get("interests", [InterestController::class,'index']);
Route::get("accounts", [AccountTypeController::class,'index']);
Route::get("replies", [ReplyController::class,'index']);
Route::get("reply/post/{post_id}", [ReplyController::class,'getReplyByPost']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("user", [AuthController::class,'getUser']);
    Route::post("user/update", [AuthController::class,'updateUser']);
    Route::post("user/update/profile-picture", [AuthController::class,'updateProfilePicture']);
    Route::post("post/create", [PostController::class,'store']);
    Route::post("reply/create", [ReplyController::class,'store']);
    Route::post("vote-reply", [VoteController::class,'voteReply']);
    Route::post("vote-post", [PostVotesController::class,'votePost']);
});
