<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;

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

// show all blogs

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{id}', [BlogController::class, 'show']);
Route::get('/blog/{id}/comments', [BlogController::class, 'showBlogComments']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/blog', [BlogController::class, 'store']);
    Route::post('/blog/comment', [BlogController::class, 'blogCommentStore']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});





Route::controller(UserController::class)->group(function(){
    Route::post("/login",'login');
    Route::post("/register",'create');
});
