<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ComentarioController extends Controller
{
    public function crear_comentario(Request $request){
        $token = $this->getActiveUserToken();

        $datos = [
            "id_usuario" => $request->input('id_usuario'),
            "id_tarea" => $request->input('id_tarea'),
            "comentario" => $request->input('comentario')
        ];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->post(getenv('GTAPI_COMENTARIOS'), $datos);

        $valores = $response->json();

        if ($response->getStatusCode() == 200) {
            return redirect()->route('tareas.ver', [
                'id' => $datos['id_tarea']
            ]);
        }

        if ($response->getStatusCode() != 200) {
            return redirect()->route('tareas.ver', [
                'id' => $datos['id_tarea']
            ])->withErrors([
                'message' => $valores['message'],
            ]);
        }
    }

    public function modificar_comentario(Request $request){
        $token = $this->getActiveUserToken();

        $id = $request->input('id');

        $datos = [
            "id_usuario" => $request->input('id_usuario'),
            "id_tarea" => $request->input('id_tarea'),
            "comentario" => $request->input('comentario'),
            "fecha_hora_creacion" => $request->input('fecha_hora_creacion')
        ];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->put(getenv('GTAPI_COMENTARIOS') . '/' . $id, $datos);

        $valores = $response->json();

        if ($response->getStatusCode() == 200) {
            return redirect()->route('tareas.ver', [
                'id' => $datos['id_tarea']
            ]);
        }

        if ($response->getStatusCode() != 200) {
            return redirect()->route('tareas.ver', [
                'id' => $datos['id_tarea']
            ])->withErrors([
                'message' => $valores['message'],
            ]);
        }
    }

    public function eliminar_comentario(Request $request){
        $token = $this->getActiveUserToken();

        $id = $request->input('id');
        $id_tarea = $request->input('id_tarea');

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->delete(getenv('GTAPI_COMENTARIOS') . '/' . $id);

        $valores = $response->json();

        if ($response->successful()) {
            return redirect()->route('tareas.ver', [
                'id' => $id_tarea
            ]);
        }

        if ($response->getStatusCode() != 200) {
            return redirect()->route('tareas.ver', [
                'id' => $id_tarea
            ])->withErrors([
                'message' => $valores['message'],
            ]);
        }
    }
}

