<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\ImcController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\IniciacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');


   


require __DIR__.'/auth.php';

//Chat
Route::get('/chat', [ChatController::class, 'index'])->middleware('auth');
Route::get('/messages', [ChatController::class, 'fetchMessages'])->middleware('auth');
Route::post('/messages', [ChatController::class, 'sendMessage'])->middleware('auth');

// Admin Chat
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/chat', [App\Http\Controllers\AdminChatController::class, 'index'])->name('chat');
    Route::get('/chat/{user}', [App\Http\Controllers\AdminChatController::class, 'conversation'])->name('chat.conversation');
    Route::post('/chat/{user}/send', [App\Http\Controllers\AdminChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/formularios', [App\Http\Controllers\AdminFormularioController::class, 'index'])->name('formularios.index');
    Route::get('/formularios/{user}', [App\Http\Controllers\AdminFormularioController::class, 'show'])->name('formularios.show');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//USUARIOS


 Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');



Route::middleware('auth')->group(function () {
     Route::get('/profile', [ProfileController::class, 'index'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/estadisticas/create', [EstadisticasController::class, 'create'])->name('estadisticas.create');
    Route::post('/estadisticas', [EstadisticasController::class, 'store'])->name('estadisticas.store');
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
    Route::get('/estadisticas/pdf', [EstadisticasController::class, 'generarPdf'])->name('estadisticas.pdf');
    Route::get('/estadisticasGeneral', [EstadisticasController::class, 'generalEstadisticas'])->name('imc.generalEstadisticas');
    Route::get('/imc', [ImcController::class, 'index'])->name('imc.index');
    Route::get('/imc/create', [ImcController::class, 'create'])->name('imc.create');
    Route::post('/imc', [ImcController::class, 'calculateImc'])->name('imc.calculateImc');
    Route::get('/resultado', [ImcController::class, 'resultado'])->name('imc.resultado');
    Route::get('/formulario', function () {
        return view('training');
    });
    Route::get('/formulario/ya-enviado', function () {
        return response()->json(['enviado' => \App\Models\FormSubmission::where('user_id', Auth::id())->exists()]);
    });
    Route::post('/guardar-respuestas', [IniciacionController::class, 'guardarRespuestas']);
    Route::get('/generar-formulario', [IniciacionController::class, 'generarPdf']);
    Route::get('/grupos/{grupoid}/ejercicios', [EstadisticasController::class, 'getEjerciciosByGrupo'])
    ->name('grupos.ejercicios');

    

});
