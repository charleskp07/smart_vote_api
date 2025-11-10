<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/login', function (Request $request) {
   return response()->json(['error' => 'Non authentifié'], 401);
})->name("login");


Route::post('/login', [AuthController::class, 'login']);
Route::post('/twofactorcode-verify', [AuthController::class, 'verifyTwoFactorCode']);

Route::post('/vote/init', [VoteController::class, 'initPayment']);
Route::post('/fedapay/callback', [VoteController::class, 'fedapayCallback'])->name('fedapay.callback');

Route::middleware("auth:sanctum")->group(function () {
   Route::post('/logout', [AuthController::class, 'logout']);
   Route::apiResource('/competitions', CompetitionController::class);

   Route::middleware([SuperAdminMiddleware::class])->group(function () {

      Route::apiResource("/users", UserController::class);
   });
});
