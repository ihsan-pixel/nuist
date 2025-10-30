<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubWebhookController;

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

// Session check endpoint for PWA
Route::middleware('auth')->get('/session-check', function (Request $request) {
    return response()->json([
        'authenticated' => true,
        'user' => $request->user()->only(['id', 'name', 'email']),
        'timestamp' => now()->timestamp
    ]);
});

Route::post('github-commit', [GithubWebhookController::class, 'handle']);
