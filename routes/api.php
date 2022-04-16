<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ConversationController;
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

Route::middleware(['web'])->group(function () {
    //
});
Route::middleware('auth:sanctum') ->group(function () {
    Route::post('conversation-store', [ConversationController::class, 'store']);
    Route::get('conversation-index', [ConversationController::class, 'index']);
    Route::get('conversation-show/{id}', [ConversationController::class, 'show']);
    Route::put('conversation-update/{id}', [ConversationController::class, 'update']);
    Route::post('message-send', [ConversationController::class, 'sendMessage']);
    Route::get('message-show/{id}', [ConversationController::class, 'showMessage']);
    Route::get('message-index', [ConversationController::class, 'indexMessage']);
});

Route::post('/tokens/create', function (Request $request) {
    return  $request->user()->createToken($request->token_name);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


