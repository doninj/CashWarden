<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Authentification\UserAuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LimitedBudgetController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\User\UserController;
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

// Récupération des banques
Route::get("/banks", [BankController::class, "index"]);

Route::middleware('auth:api')->group(function ($route) {
    $route->post("/requisition", [RequisitionController::class, "store"]);
    $route->get("/nordigen/accounts", [AccountController::class, "nordigenAccount"]);

    $route->get("/user", [UserController::class, "index"]);
    $route->get("/userWhenConnected", [UserController::class, "showUserWhenConnected"]);

    $route->get("/transactionsPerMonth", [TransactionController::class, "transactionsPerMonth"]);
    $route->post("/user", [UserController::class, "update"]);

    $route->get("/limitedBudgets", [LimitedBudgetController::class, "index"]);
    $route->post("/limitedBudgets", [LimitedBudgetController::class, "store"]);
    $route->put("/limitedBudgets/{limitedBudget}", [LimitedBudgetController::class, "update"]);

    $route->get("/transactions", [TransactionController::class, "index"]);

    $route->post("/logout", [UserAuthController::class, "logout"]);
});
