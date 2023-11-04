<?php

use App\Http\Controllers\TareaController;
use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PasswordController;


Route::view('/', "index")->name('index');

Route::controller(InicioController::class)->middleware('auth')->group(function () {
    Route::get('inicio', 'index')->name('tareas.inicio');
    Route::get('ayuda', 'ayuda')->name('tareas.ayuda');
    Route::get('buscar', 'buscar')->name('tareas.buscar');
    Route::get('historial-comentarios', 'historial_comentarios')->name('historial.comentarios');
    Route::get('historial-tareas', 'historial_tareas')->name('historial.tareas');
});

Route::controller(TareaController::class)->middleware('auth')->group(function () {
    Route::get('crear-tarea', 'index')->name('tareas.crear');
    Route::post('crear-tarea', 'guardar');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('auth.login');
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware('auth')->name('auth.logout');
});

Route::controller(RegistroController::class)->middleware('guest')->group(function () {
    Route::get('registro', 'index')->name('auth.registro');
    Route::post('registro', 'registro');
});

Route::controller(PasswordController::class)->middleware('guest')->group(function () {
    Route::get('restablecer', 'form_restablecer')->name('auth.restablecer');
    Route::post('restablecer', 'send_restablecer');
    Route::get('password', 'goto_restablecer');
    Route::get('password-{token}', 'form_password')->name('auth.password');
    Route::post('password', 'cambiar_password');
});
