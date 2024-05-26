<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\XMLHttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware([XMLHttpRequest::class])->group(function () {
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('budgets', BudgetController::class);
});