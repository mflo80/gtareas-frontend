<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getActiveUserData()
    {
        $sessionId = session('session_id');
        $datos = Cache::get($sessionId);
        $userData = $datos['usuario'];
        return $userData;
    }

    public function getActiveUserToken()
    {
        $sessionId = session('session_id');
        $datos = Cache::get($sessionId);
        $token = $datos['token'];
        return $token;
    }

    public function getUsuarios()
    {
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTOAUTH_USUARIOS'));

        $valores = $response->json();

        if($response->getStatusCode() == 200){
            $usuarios = $valores['usuarios'];
            return $usuarios;
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function getTareas()
    {
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS'));

        $valores = $response->json();

        if($response->getStatusCode() == 200){
            $tareas = $valores['tareas'];
            return $tareas;
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function getTareasAsignadas(){
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_ASIGNA'));

        $valores = $response->json();

        if ($response->successful()) {
            $tareasAsignadas = $valores['tareas'];
            return $tareasAsignadas;
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function getTareaAsignada($id_tarea){
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_ASIGNA') . "/tarea/" . $id_tarea);

        $valores = $response->json();

        if ($response->successful()) {
            $tareaAsignada = $valores['tarea_asignada'];
            return $tareaAsignada;
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }


    public function getTareaAsignadaUsuario($id_usuario)
    {
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_ASIGNA') . "/usuario/asignado/" . $id_usuario);

        $valores = $response->json();

        if($response->getStatusCode() == 200){
            $tareasAsignadas = $valores['tareas_asignadas'];
            return $tareasAsignadas;
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}
