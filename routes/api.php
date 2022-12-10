<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
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

Route::get('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])->name('verification.notice');


Route::apiResource('users', UserController::class);
Route::apiResource('pesanans', PesananController::class);
Route::post('/login', [UserController::class, 'login']);
// Route::post('/register', [UserController::class, 'login']);
Route::apiResource('bookmarks', BookmarkController::class);