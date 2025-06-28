<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageTokenController;
use App\Http\Middleware\TokenValidator;

Route::get('/make-token-example/{userID}', [ManageTokenController::class, 'generateTokenPostman']);
Route::get('/test-token-example', [ManageTokenController::class, 'testTokenPostman'])->middleware(TokenValidator::class);