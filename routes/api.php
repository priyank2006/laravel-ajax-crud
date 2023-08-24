<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([''], function () {
    Route::get("getUsers", [APIController::class, 'getUsers']);

    Route::post("createUser", [APIController::class, 'createOrUpdateUser']);

    Route::get("userData/{userID}", [APIController::class, 'userData']);
    Route::delete('deleteUser/{id}', [APIController::class, 'deleteUser']);
});
