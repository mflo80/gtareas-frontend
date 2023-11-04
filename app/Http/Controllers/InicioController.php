<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class InicioController extends Controller
{
    public function index()
    {
        $userData = session('gtuserdata');
        $token = $userData['token'] ?? null;

        $token = $this->getActiveUserToken(); // Obtiene el token del usuario activo

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token" // Incluye el token en los encabezados
        ])->get(getenv('GTAPI_TAREAS'));

        if ($response->successful()) {
            $tareas = json_decode($response->body(), true);
            $tareas = $tareas['tareas'] ?? [];

            $tareasPorCategoria = [];
            foreach ($tareas as $tarea) {
                $tareasPorCategoria[$tarea['categoria']][] = $tarea;
            }

            return view('tareas.inicio', ['tareasPorCategoria' => $tareasPorCategoria]);
        } else {
            // Maneja el caso en que la respuesta no sea exitosa
            return redirect()->to('logout')->withErrors([
                'message' => "Error al obtener las tareas",
            ]);
        }
    }

    public function ayuda()
    {
        return view('tareas.ayuda');
    }

    public function buscar()
    {
        return view('tareas.buscar');
    }

    public function historial_comentarios()
    {
        return view('historial.comentarios');
    }

    public function historial_tareas()
    {
        return view('historial.tareas');
    }
}
