<?php

use App\Http\Controllers\TareaController;
use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::view('/', "index")->name('index');

Route::controller(InicioController::class)
        ->middleware('auth.cookie')
        ->group(function () {
    Route::get('inicio', 'index')->name('tareas.inicio');
    Route::get('ayuda', 'ayuda')->name('tareas.ayuda');
    Route::get('buscar', 'buscar')->name('tareas.buscar');
    Route::get('historial-comentarios', 'historial_comentarios')->name('historial.comentarios');
    Route::get('historial-tareas', 'historial_tareas')->name('historial.tareas');
});

Route::controller(TareaController::class)
        ->middleware('auth.cookie')
        ->group(function () {
    Route::get('crear-tarea', 'index')->name('tareas.crear');
    Route::post('crear-tarea', 'guardar');
});
