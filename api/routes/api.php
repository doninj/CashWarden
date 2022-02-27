<?php

use App\Http\Controllers\Authentification\UserAuthController;
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

Route::post("/register", [UserAuthController::class, "register"]);
Route::post("/login", [UserAuthController::class, "login"]);

Route::middleware('auth:api')->group(function ($route) {
    $route->post("/logout", [UserAuthController::class, "logout"]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
