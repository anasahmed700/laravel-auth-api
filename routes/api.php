<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//register new user
Route::post('/register', [AuthController::class, 'register']);
//login user
Route::post('/login', [AuthController::class, 'login']);
//using middleware
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::resource('users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
