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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API para cargar equipos por área
Route::get('/equipos-por-area/{areaId}', function ($areaId) {
    return \App\Models\equipo::where('ARE_ID', $areaId)
        ->where('EQU_ESTADO', 1)
        ->select('EQU_ID', 'EQU_NOMBRE', 'EQU_SERIAL')
        ->orderBy('EQU_NOMBRE')
        ->get();
});
