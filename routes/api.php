<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return "hello";
})->middleware('auth:sanctum');


Route::post('/v1/register', [AuthController::class, 'register'])->name('register');
Route::post('/v1/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/posts/create', [PostController::class, 'store']);
    Route::get('/v1/posts/get-all', [PostController::class, 'index']);
    Route::put('/v1/posts/update/{id}', [PostController::class, 'update']);
    Route::get('/v1/posts/get-all-paginated', [PostController::class, 'getAllPublished']);
    Route::get('/v1/posts/get-all-paginated-filtered', [PostController::class, 'getPostsFiltered']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/comments/create', [CommentController::class, 'store']);
    Route::get('/v1/posts/{postId}/comments/get-all', [CommentController::class, 'index']);
    Route::get('/v1/comments/get-all', [CommentController::class, 'allComments']);
    Route::put('/v1/comments/update/{id}', [CommentController::class, 'update']);

});

Route::middleware(['auth:sanctum','AdminMiddleware'])->group(function () {
    Route::delete('/v1/posts/delete/{id}', [PostController::class, 'destroy']);
    Route::delete('/v1/comments/delete/{id}', [CommentController::class, 'destroy']);
});

