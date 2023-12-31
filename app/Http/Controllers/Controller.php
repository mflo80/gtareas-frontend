<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $usuarios = [];

        if($response->getStatusCode() == 200){
            $usuarios = $valores['usuarios'];
            return $usuarios;
        }

        return $usuarios;
    }

    public function getTareas()
    {
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS'));

        $valores = $response->json();

        $tareas = [];

        if($response->getStatusCode() == 200){
            $tareas = $valores['tareas'];
            return $tareas;
        }

        return $tareas;
    }

    public function getTareasAsignadas(){
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_ASIGNA'));

        $valores = $response->json();

        $tareasAsignadas = [];

        if($response->getStatusCode() == 200){
            $tareasAsignadas = $valores['tareas'];
            return $tareasAsignadas;
        }

        return $tareasAsignadas;
    }

    public function getTareaAsignada($id_tarea){
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_ASIGNA') . "/tarea/" . $id_tarea);

        $valores = $response->json();

        $tareaAsignada = [];

        if($response->getStatusCode() == 200){
            $tareaAsignada = $valores['tarea_asignada'];
            return $tareaAsignada;
        }

        return $tareaAsignada;
    }

    public function getTareaAsignadaUsuario($id_usuario)
    {
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_ASIGNA') . "/usuario/asignado/" . $id_usuario);

        $valores = $response->json();

        $tareasAsignadas = [];

        if($response->getStatusCode() == 200){
            $tareasAsignadas = $valores['tareas_asignadas'];
            return $tareasAsignadas;
        }

        return $tareasAsignadas;
    }

    public function getTareasComentarios($id_tarea){
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_COMENTARIOS') . "/tarea/" . $id_tarea);

        $valores = $response->json();

        $tareaComentarios = [];

        if ($response->successful()) {
            $tareaComentarios = $valores['comentario'];
        }

        return $tareaComentarios;
    }
}
