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

Route::get('/', function () {
   return response()->json([
      'success' => true,
      'version' => "1.0.1"
  ]);
});
Route::get('/patients', [App\Http\Controllers\Api\PatientController::class, 'index']);
Route::get('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'show']);
Route::post('/patients', [App\Http\Controllers\Api\PatientController::class, 'store']);
Route::put('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'update']);
Route::delete('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'destroy']);

Route::get('/cep/{cep}', [App\Http\Controllers\Api\CepController::class, 'consult']);