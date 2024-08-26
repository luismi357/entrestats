<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\imcController;

Route::middleware('auth')->group(function () {
    Route::get('/estadisticas/create', [EstadisticasController::class, 'create'])->name('estadisticas.create');
    Route::post('/estadisticas', [EstadisticasController::class, 'store'])->name('estadisticas.store');
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
    Route::get('/imc', [imcController::class, 'index'])->name('imc.index');
    Route::get('/imc/create', [imcController::class, 'create'])->name('imc.create');
    Route::post('/imc', [imcController::class, 'calculateImc'])->name('imc.calculateImc');
    Route::get('/resultado', [imcController::class, 'resultado'])->name('imc.resultado');
    
});
