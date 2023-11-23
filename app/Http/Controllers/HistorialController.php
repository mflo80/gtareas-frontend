<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HistorialController extends Controller
{
    public function historial_tareas(Request $request)
    {
        $usuarioLogueado = $this->getActiveUserData();
        $token = $this->getActiveUserToken();

        $paginaActualHistorialTareas  = $request->input('pagina', 1);
        $filasPorPaginaHistorialTareasInput = $request->input('filasPorPaginaHistorialTareas');
        $ordenHistorialTareasInput = $request->input('ordenHistorialTareas');

        if ($filasPorPaginaHistorialTareasInput) {
            Cache::put('filasPorPaginaHistorialTareas'.$usuarioLogueado['id'], $filasPorPaginaHistorialTareasInput, Carbon::now()->addMinutes(60));
            $filasPorPaginaHistorialTareas = $filasPorPaginaHistorialTareasInput;
        } else {
            $filasPorPaginaHistorialTareas = Cache::remember('filasPorPaginaHistorialTareas'.$usuarioLogueado['id'], Carbon::now()->addMinutes(60), function () {
                return 8;
            });
        }

        if ($ordenHistorialTareasInput) {
            Cache::put('ordenHistorialTareas'.$usuarioLogueado['id'], $ordenHistorialTareasInput, Carbon::now()->addMinutes(60));
            $ordenHistorialTareas = $ordenHistorialTareasInput;
        } else {
            $ordenHistorialTareas = Cache::remember('ordenHistorialTareas'.$usuarioLogueado['id'], Carbon::now()->addMinutes(60), function () {
                return 'asc';
            });
        }

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_HISTORIAL_TAREAS'), [
            'filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas,
            'paginaActualHistorialTareas' => $paginaActualHistorialTareas ,
        ]);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $historiales = $valores['tareas'] ?? [];

            if($ordenHistorialTareas == 'desc'){
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
                'ordenHistorialTareas' => $ordenHistorialTareas,
            ]);
        }

        return view('historial.tareas', [
            'historiales' => [],
            'usuarioLogueado' => $usuarioLogueado,
            'paginaActualHistorialTareas' => "1",
            'filasPorPaginaHistorialTareas' => "16",
            'totalPaginas' => "1",
            'totalTareas' => "1",
            'ordenHistorialTareas' => $ordenHistorialTareas,
        ]);
    }

    public function historial_comentarios(Request $request)
    {
        $usuarioLogueado = $this->getActiveUserData();
        $token = $this->getActiveUserToken();

        $paginaActualHistorialComentarios  = $request->input('pagina', 1);
        $filasPorPaginaHistorialComentariosInput = $request->input('filasPorPaginaHistorialComentarios');
        $ordenHistorialComentariosInput = $request->input('ordenHistorialComentarios');

        if ($filasPorPaginaHistorialComentariosInput) {
            Cache::put('filasPorPaginaHistorialComentarios'.$usuarioLogueado['id'], $filasPorPaginaHistorialComentariosInput, Carbon::now()->addMinutes(60));
            $filasPorPaginaHistorialComentarios = $filasPorPaginaHistorialComentariosInput;
        } else {
            $filasPorPaginaHistorialComentarios = Cache::remember('filasPorPaginaHistorialComentarios'.$usuarioLogueado['id'], Carbon::now()->addMinutes(60), function () {
                return 8;
            });
        }

        if ($ordenHistorialComentariosInput) {
            Cache::put('ordenHistorialComentarios'.$usuarioLogueado['id'], $ordenHistorialComentariosInput, Carbon::now()->addMinutes(60));
            $ordenHistorialComentarios = $ordenHistorialComentariosInput;
        } else {
            $ordenHistorialComentarios = Cache::remember('ordenHistorialComentarios'.$usuarioLogueado['id'], Carbon::now()->addMinutes(60), function () {
                return 'asc';
            });
        }

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_HISTORIAL_COMENTARIOS'), [
            'filasPorPaginaHistorialComentarios' => $filasPorPaginaHistorialComentarios,
            'paginaActualHistorialComentarios' => $paginaActualHistorialComentarios ,
        ]);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $historiales = $valores['comentarios']  ?? [];

            if($ordenHistorialComentarios == 'desc'){
                $historiales = array_reverse($historiales);
            }

            $totalTareas = count($historiales);
            $totalPaginas = ceil($totalTareas / $filasPorPaginaHistorialComentarios);

            if($paginaActualHistorialComentarios > $totalPaginas){
                return redirect()->to('error.404');
            }

            $tareasAsignadas = $this->getTareasAsignadas();

            $idsTareas = [];
            foreach ($tareasAsignadas as $tareaAsignadaUsuario) {
                if ($tareaAsignadaUsuario['id_usuario_asignado'] == $usuarioLogueado['id']) {
                    $idsTareas[] = $tareaAsignadaUsuario['id_tarea'];
                }
            }

            $historiales = array_slice($historiales, ($paginaActualHistorialComentarios - 1) * $filasPorPaginaHistorialComentarios, $filasPorPaginaHistorialComentarios);

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTOAUTH_USUARIOS'));

            if ($response->successful()) {
                $usuarios = json_decode($response->body(), true);
                $usuarios = $usuarios['usuarios'] ?? [];

                foreach ($historiales as &$historial) {
                    $usuarioCreadorId = $historial['id_usuario'];
                    if ($usuarioCreadorId != null) {
                        foreach ($usuarios as $usuario) {
                            if ($usuario['id'] == $usuarioCreadorId) {
                                $historial['nombre'] = $usuario['nombre'];
                                $historial['apellido'] = $usuario['apellido'];
                            }
                        }
                    }

                    if ($usuarioCreadorId == null) {
                        $historial['nombre'] = '';
                        $historial['apellido'] = '';
                    }

                    if (in_array($historial['id_tarea'], $idsTareas)) {
                        $historial['tarea_asignada'] = 1;
                    }
                }
            }

            return view('historial.comentarios', [
                'historiales' => $historiales,
                'usuarioLogueado' => $usuarioLogueado,
                'paginaActualHistorialComentarios' => $paginaActualHistorialComentarios,
                'filasPorPaginaHistorialComentarios' => $filasPorPaginaHistorialComentarios,
                'totalPaginas' => $totalPaginas,
                'totalTareas' => $totalTareas,
                'ordenHistorialComentarios' => $ordenHistorialComentarios,
            ]);
        }

        return view('historial.comentarios', [
            'historiales' => [],
            'usuarioLogueado' => $usuarioLogueado,
            'paginaActualHistorialComentarios' => '1',
            'filasPorPaginaHistorialComentarios' => '16',
            'totalPaginas' => '1',
            'totalTareas' => '0',
            'ordenHistorialComentarios' => $ordenHistorialComentarios,
        ]);
    }
}
