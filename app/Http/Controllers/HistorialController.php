<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HistorialController extends Controller
{
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

            if($filasPorPaginaHistorialTareas == null || $filasPorPaginaHistorialTareas == '' || $filasPorPaginaHistorialTareas == 0){
                $filasPorPaginaHistorialTareas = 16;
            }
        }

        $filasPorPaginaHistorialTareas = $request->input('filasPorPaginaHistorialTareas', $filasPorPaginaHistorialTareas);
        $paginaActualHistorialTareas  = $request->input('pagina', 1);

        Cache::put('filasPorPaginaHistorialTareas', $filasPorPaginaHistorialTareas);

        $ordenHistorial = 'desc';

        if(Cache::has('ordenHistorial')){
            $ordenHistorial = Cache::get('ordenHistorial');
        }

        if($ordenHistorial == null || $ordenHistorial == ''){
            $ordenHistorial = 'desc';
        }

        $ordenHistorial = $request->input('ordenHistorial', $ordenHistorial);
        Cache::put('ordenHistorial', $ordenHistorial);

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_HISTORIAL'), [
            'filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas,
            'paginaActualHistorialTareas' => $paginaActualHistorialTareas ,
        ]);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $historiales = json_decode($response->body(), true);
            $historiales = $historiales['tareas'] ?? [];

            if($ordenHistorial == 'desc'){
                $historiales = array_reverse($historiales);
            }

            $totalTareas = count($historiales);
            $totalPaginas = ceil($totalTareas / $filasPorPaginaHistorialTareas);

            if($paginaActualHistorialTareas > $totalPaginas){
                return redirect()->to('error.404');
            }

            $tareasAsignadas = $this->getTareasAsignadas();

            $idsTareas = [];
            foreach ($tareasAsignadas as $tareaAsignadaUsuario) {
                if ($tareaAsignadaUsuario['id_usuario_asignado'] == $usuarioLogueado['id']) {
                    $idsTareas[] = $tareaAsignadaUsuario['id_tarea'];
                }
            }

            $historiales = array_slice($historiales, ($paginaActualHistorialTareas - 1) * $filasPorPaginaHistorialTareas, $filasPorPaginaHistorialTareas);

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTOAUTH_USUARIOS'));

            if ($response->successful()) {
                $usuarios = json_decode($response->body(), true);
                $usuarios = $usuarios['usuarios'] ?? [];

                foreach ($historiales as &$historial) {
                    $oldUsuarioCreadorId = $historial['old_id_usuario'];
                    if ($oldUsuarioCreadorId != null) {
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id'] == $oldUsuarioCreadorId) {
                                $historial['old_nombre'] = $usuario['nombre'];
                                $historial['old_apellido'] = $usuario['apellido'];
                            }
                        }
                    }

                    if ($oldUsuarioCreadorId == null) {
                        $historial['old_nombre'] = '';
                        $historial['old_apellido'] = '';
                    }

                    $newUsuarioCreadorId = $historial['new_id_usuario'];
                    if ($newUsuarioCreadorId != null) {
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id'] == $newUsuarioCreadorId) {
                                $historial['new_nombre'] = $usuario['nombre'];
                                $historial['new_apellido'] = $usuario['apellido'];
                            }
                        }
                    }

                    if ($newUsuarioCreadorId == null) {
                        $historial['new_nombre'] = '';
                        $historial['new_apellido'] = '';
                    }

                    $UsuarioEditorId = $historial['id_usuario_modificacion'];
                    if ($UsuarioEditorId != null) {
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id'] == $UsuarioEditorId) {
                                $historial['editor_nombre'] = $usuario['nombre'];
                                $historial['editor_apellido'] = $usuario['apellido'];
                            }
                        }
                    }

                    if (in_array($historial['id_tarea'], $idsTareas)) {
                        $historial['tarea_asignada'] = 1;
                    }
                }
            }

            return view('historial.tareas', [
                'historiales' => $historiales,
                'usuarioLogueado' => $usuarioLogueado,
                'paginaActualHistorialTareas' => $paginaActualHistorialTareas,
                'filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas,
                'totalPaginas' => $totalPaginas,
                'totalTareas' => $totalTareas,
                'ordenHistorial' => $ordenHistorial,
            ]);
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}
