<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InicioController extends Controller
{
    public function index()
    {
        //
    }

    public function ayuda()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('tareas.ayuda', ['usuarioLogueado' => $usuarioLogueado]);
    }

    public function buscar()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('tareas.buscar', ['usuarioLogueado' => $usuarioLogueado]);
    }
}
