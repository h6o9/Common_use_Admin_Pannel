<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('farmer/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('get-profile', [AuthController::class, 'getProfile']); // Get Profile
    Route::put('update-profile', [AuthController::class, 'updateProfile']); // Update Profile

    // Password reset for Admin & SubAdmin via API
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::get('/verify-reset-token/{token}', [AuthController::class, 'verifyResetToken']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

