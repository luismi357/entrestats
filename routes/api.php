<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImcApiController;
use App\Http\Controllers\Api\EstadisticasApiController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\FormularioApiController;
use App\Http\Controllers\Api\UserApiController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/profile', [UserApiController::class, 'profile']);
    Route::put('/profile', [UserApiController::class, 'update']);
    Route::post('/profile/photo', [UserApiController::class, 'uploadPhoto']);

    Route::get('/imc', [ImcApiController::class, 'index']);
    Route::post('/imc', [ImcApiController::class, 'store']);

    Route::get('/grupos-musculares', [EstadisticasApiController::class, 'gruposMusculares']);
    Route::get('/ejercicios/{grupoId}', [EstadisticasApiController::class, 'ejerciciosPorGrupo']);
    Route::get('/estadisticas', [EstadisticasApiController::class, 'index']);
    Route::post('/estadisticas', [EstadisticasApiController::class, 'store']);
    Route::post('/estadisticas/pdf', [EstadisticasApiController::class, 'generarPdf']);

    Route::get('/messages', [ChatApiController::class, 'index']);
    Route::post('/messages', [ChatApiController::class, 'store']);

    Route::post('/generar-pdf', [FormularioApiController::class, 'generarPdf']);
    Route::get('/formulario/ya-enviado', [FormularioApiController::class, 'yaEnviado']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/chat/users', [App\Http\Controllers\Api\AdminChatApiController::class, 'users']);
    Route::get('/admin/chat/{user}/messages', [App\Http\Controllers\Api\AdminChatApiController::class, 'messages']);
    Route::post('/admin/chat/{user}/messages', [App\Http\Controllers\Api\AdminChatApiController::class, 'store']);

    Route::get('/admin/formularios', [App\Http\Controllers\Api\AdminFormularioApiController::class, 'index']);
    Route::get('/admin/formularios/{user}', [App\Http\Controllers\Api\AdminFormularioApiController::class, 'show']);
});
