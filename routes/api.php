<?php

use App\Http\Controllers\kingon;
use App\Http\Controllers\KingOnAPI;
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

Route::post('/devices', [KingOnAPI::class, 'store']);
Route::put('/devices/{id}/port', [KingOnAPI::class, 'update']);