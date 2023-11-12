<?php

use App\Http\Controllers\ErrorController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PasswordController;


Route::view('/', "index")->name('index');

Route::controller(InicioController::class)->middleware('autenticacion')->group(function () {
    Route::get('ayuda', 'ayuda')->name('tareas.ayuda');
    Route::get('buscar', 'buscar')->name('tareas.buscar');
    Route::get('historial-comentarios', 'historial_comentarios')->name('historial.comentarios');
    Route::get('historial-tareas', 'historial_tareas')->name('historial.tareas');
});

Route::controller(TareaController::class)->middleware('autenticacion')->group(function () {
    Route::get('inicio', 'index')->name('tareas.inicio');
    Route::get('ver-tarea-{id}', 'ver')->name('tareas.ver');
    Route::get('crear-tarea', 'form_crear')->name('tareas.crear');
    Route::post('crear-tarea', 'guardar');
    Route::get('modificar-tarea-{id}', 'form_modificar')->name('tareas.modificar');
    Route::put('modificar-tarea-{id}', 'modificar');
    Route::put('categoria-tarea-{id}', 'actualizar_categoria');
    Route::delete('eliminar-tarea-{id}', 'eliminar');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('auth.login');
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware('autenticacion')->name('auth.logout');
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
    Route::put('password', 'cambiar_password');
});

Route::controller(ErrorController::class)->middleware('autenticacion')->group(function () {
    Route::get('error-404', 'index')->name('tareas.error');
});

Route::fallback(function () {
    return redirect()->route('tareas.error');
});
