<?php

use App\Http\Controllers\Api\Admin\CandidateController as ADMINCandidateController;
use App\Http\Controllers\Api\Admin\CompetitionController as ADMINCompetitionController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidateContreller;
use App\Http\Controllers\Api\CompetitionContreller;
use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\Api\Voter\CandidateController as VoterCandidateController;
use App\Http\Controllers\Api\Voter\CompetitionController as VoterCompetitionController ;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function (Request $request) {
   return response()->json(['error' => 'Non authentifié'], 401);
})->name("login");


Route::post('/login', [AuthController::class, 'login']);
Route::post('/twofactorcode-verify', [AuthController::class, 'verifyTwoFactorCode']);

Route::post('/vote/init', [VoteController::class, 'initPayment']);
Route::post('/fedapay/callback', [VoteController::class, 'fedapayCallback'])->name('fedapay.callback');

Route::get('/voter/competitions/index', [VoterCompetitionController::class, 'index']);
Route::get('/voter/competitions/{id}/show', [VoterCompetitionController::class, 'show']);

Route::get('/voter/candidates/index', [VoterCandidateController::class, 'index']);
Route::get('/voter/candidates/{id}/show', [VoterCandidateController::class, 'show']);


Route::middleware("auth:sanctum")->group(function () {
   
   Route::post('/logout', [AuthController::class, 'logout']);
   
   Route::apiResource('/organizer/competitions', CompetitionContreller::class);
   Route::apiResource('/organizer/candidates', CandidateContreller::class);
   
   Route::middleware([SuperAdminMiddleware::class])->group(function () {
      Route::apiResource('/admin/competitions', ADMINCompetitionController::class);
      Route::apiResource('/admin/candidates', ADMINCandidateController::class);
      Route::apiResource("/admin/users", UserController::class);
      Route::post("/admin/users/{id}",[ UserController::class, 'update']);
      Route::get("/admin/users/trashed", [UserController::class, 'trash']);
   });
});
