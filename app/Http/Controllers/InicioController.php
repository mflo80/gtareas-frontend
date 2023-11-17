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

    public function historial_comentarios()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('historial.comentarios', ['usuarioLogueado' => $usuarioLogueado]);
    }

    public function historial_tareas(Request $request)
    {
        $usuarioLogueado = $this->getActiveUserData();
        $token = $this->getActiveUserToken();

        $filasPorPaginaHistorialTareas = 16;

        if(Cache::has('filasPorPaginaHistorialTareas')){
            $filasPorPaginaHistorialTareas = Cache::get('filasPorPaginaHistorialTareas');
        }

        $filasPorPaginaHistorialTareas = $request->input('filasPorPaginaHistorialTareas', $filasPorPaginaHistorialTareas); // Usa el valor de 'filasPorPagina' que viene en la solicitud
        $paginaActualHistorialTareas  = $request->input('pagina', 1); // Página por defecto si no se proporciona

        Cache::put('filasPorPaginaHistorialTareas', $filasPorPaginaHistorialTareas);

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_HISTORIAL'), [
            'filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas,
            'paginaActualHistorialTareas' => $paginaActualHistorialTareas ,
        ]);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $tareas = json_decode($response->body(), true);
            $tareas = $tareas['tareas'] ?? [];

            $totalTareas = count($tareas);
            $totalPaginas = ceil($totalTareas / $filasPorPaginaHistorialTareas);

            if($paginaActualHistorialTareas > $totalPaginas){
                return redirect()->to('error.404');
            }

            $tareas = array_slice($tareas, ($paginaActualHistorialTareas - 1) * $filasPorPaginaHistorialTareas, $filasPorPaginaHistorialTareas);

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTOAUTH_USUARIOS'));

            if ($response->successful()) {
                $usuarios = json_decode($response->body(), true);
                $usuarios = $usuarios['usuarios'] ?? [];

                foreach ($tareas as &$tarea) {
                    $oldUsuarioCreadorId = $tarea['old_id_usuario'];
                    if ($oldUsuarioCreadorId != null) {
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id'] == $oldUsuarioCreadorId) {
                                $tarea['old_nombre'] = $usuario['nombre'];
                                $tarea['old_apellido'] = $usuario['apellido'];
                            }
                        }
                    }

                    if ($oldUsuarioCreadorId == null) {
                        $tarea['old_nombre'] = '';
                        $tarea['old_apellido'] = '';
                    }

                    $newUsuarioCreadorId = $tarea['new_id_usuario'];
                    if ($newUsuarioCreadorId != null) {
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id'] == $newUsuarioCreadorId) {
                                $tarea['new_nombre'] = $usuario['nombre'];
                                $tarea['new_apellido'] = $usuario['apellido'];
                            }
                        }
                    }

                    if ($newUsuarioCreadorId == null) {
                        $tarea['new_nombre'] = '';
                        $tarea['new_apellido'] = '';
                    }
                }
            }

            return view('historial.tareas', [
                'tareas' => $tareas,
                'usuario' => $usuario,
                'usuarioLogueado' => $usuarioLogueado,
                'paginaActualHistorialTareas' => $paginaActualHistorialTareas,
                'filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas,
                'totalPaginas' => $totalPaginas,
                'totalTareas' => $totalTareas
            ]);
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}
