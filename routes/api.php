<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/login', function (Request $request) {
   return response()->json(['error' => 'Non authentifié'], 401);
})->name("login");


Route::post('/login', [AuthController::class, 'login']);
Route::post('/twofactorcode-verify', [AuthController::class, 'verifyTwoFactorCode']);
Route::apiResource('competitions', CompetitionController::class);


Route::middleware("auth:sanctum")->group(function () {
   Route::post('/logout', [AuthController::class, 'logout']);

   Route::middleware([SuperAdminMiddleware::class])->group(function () {

      Route::apiResource("/users", UserController::class);
   });
});
