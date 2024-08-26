<?php

use App\Http\Controllers\API\CastsController;
use App\Http\Controllers\API\GenresController;
use App\Http\Controllers\API\MoviesController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfilesController;
use App\Http\Controllers\API\ReviewsController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\CastMoviesController;
use App\Http\Middleware\isVerificationAccount;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::prefix('v1')->group(function (){
    Route::apiResource("movie", MoviesController::class);
    Route::apiResource("genre", GenresController::class);
    Route::apiResource("cast", CastsController::class);
    Route::apiResource("role", RolesController::class);
    Route::apiResource("cast-movie", CastMoviesController::class);
    Route::prefix('auth')->group(function (){
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/signin', [AuthController::class, 'signin']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('/generate-otp-code', [AuthController::class, 'generateOtpCode'])->middleware('auth:api');
        Route::post('/account-verification', [AuthController::class, 'accountVerification'])->middleware('auth:api');
    });
    Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');
    Route::post('update-user', [AuthController::class, 'updateUser'])->middleware('auth:api', 'isVerificationAccount');
    Route::post('profile', [ProfilesController::class, 'store'])->middleware('auth:api', 'isVerificationAccount');
    Route::get('get-profile', [ProfilesController::class, 'index'])->middleware('auth:api', 'isVerificationAccount');
    Route::post('review', [ReviewsController::class, 'store'])->middleware('auth:api', 'isVerificationAccount');
});

