<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\ImcController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/register', [RegisterController::class, 'create'])->name('create');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Chat
Route::get('/chat', [ChatController::class, 'index'])->middleware('auth');
Route::get('/messages', [ChatController::class, 'fetchMessages']);
Route::post('/messages', [ChatController::class, 'sendMessage']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/grupos/{grupoId}/ejercicios', [EstadisticasController::class, 'getEjerciciosByGrupo']);



Route::middleware('auth')->group(function () {
    Route::get('/estadisticas/create', [EstadisticasController::class, 'create'])->name('estadisticas.create');
    Route::post('/estadisticas', [EstadisticasController::class, 'store'])->name('estadisticas.store');
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
    Route::get('/estadisticasGeneral', [EstadisticasController::class, 'generalEstadisticas'])->name('imc.generalEstadisticas');
    Route::get('/imc', [ImcController::class, 'index'])->name('imc.index');
    Route::get('/imc/create', [ImcController::class, 'create'])->name('imc.create');
    Route::post('/imc', [ImcController::class, 'calculateImc'])->name('imc.calculateImc');
    Route::get('/resultado', [ImcController::class, 'resultado'])->name('imc.resultado');
});
