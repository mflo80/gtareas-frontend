<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class InicioController extends Controller
{
    public function index()
    {
        //
    }

    public function ayuda()
    {
        $usuario = $this->getActiveUserData();

        return view('tareas.ayuda', ['usuario' => $usuario]);
    }

    public function buscar()
    {
        $usuario = $this->getActiveUserData();

        return view('tareas.buscar', ['usuario' => $usuario]);
    }

    public function historial_comentarios()
    {
        $usuario = $this->getActiveUserData();

        return view('historial.comentarios', ['usuario' => $usuario]);
    }

    public function historial_tareas()
    {
        $usuario = $this->getActiveUserData();

        return view('historial.tareas', ['usuario' => $usuario]);
    }
}
