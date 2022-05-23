<?php

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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/', [\App\Http\Controllers\Api\V1\IndexController::class, 'index']);

// auth
Route::post('/login', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'login']);
Route::post('/registration', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'registration']);
Route::post('/logout', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'logout']);

// user
Route::get('/profile', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'profile']);

// post
Route::get('/posts', [\App\Http\Controllers\Api\V1\Post\PostController::class, 'posts']);
Route::get('/my-posts', [\App\Http\Controllers\Api\V1\Post\PostController::class, 'myPosts']);
Route::get('/post/{id}', [\App\Http\Controllers\Api\V1\Post\PostController::class, 'show']);
Route::post('/post/up', [\App\Http\Controllers\Api\V1\Post\PostController::class, 'up']);
Route::post('/post/down', [\App\Http\Controllers\Api\V1\Post\PostController::class, 'down']);
Route::post('/post/create', [\App\Http\Controllers\Api\V1\Post\PostController::class, 'store']);

// comments
Route::get('/post/comments/{postId}', [\App\Http\Controllers\Api\V1\Post\PostCommentController::class, 'commentsByPost']);
Route::get('/post/comments/parent/{postId}', [\App\Http\Controllers\Api\V1\Post\PostCommentController::class, 'commentsParentByPost']);
Route::get('/post/comments/branch/{branchId}', [\App\Http\Controllers\Api\V1\Post\PostCommentController::class, 'commentsByBranch']);
Route::post('/post/comment/create', [\App\Http\Controllers\Api\V1\Post\PostCommentController::class, 'store']);
Route::post('/post/comment/up', [\App\Http\Controllers\Api\V1\Post\PostCommentController::class, 'up']);
Route::post('/post/comment/down', [\App\Http\Controllers\Api\V1\Post\PostCommentController::class, 'down']);

