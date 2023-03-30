<?php

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

Route::get('/version', [App\Http\Controllers\Api\PatientController::class, 'version']);
Route::post('/patients', [App\Http\Controllers\Api\PatientController::class, 'store']);
Route::get('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'show']);
Route::get('/patients', [App\Http\Controllers\Api\PatientController::class, 'index']);

