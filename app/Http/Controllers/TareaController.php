<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TareaController extends Controller
{
    public function index()
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS'));

        if ($response->successful()) {
            $tareas = json_decode($response->body(), true);

            $tareas = $tareas['tareas'] ?? [];

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTAPI_ASIGNA') . "/usuario/asignado/" . $usuario['id']);

            if ($response->successful()) {
                $asignaciones = json_decode($response->body(), true);
                $asignaciones = $asignaciones['tareas_asignadas'] ?? [];

                $idsTareas = [];

                foreach ($asignaciones as $asignacion) {
                    $idsTareas[] = $asignacion['id_tarea'];
                }

                $tareasPorCategoria = [];
                $estados = explode(',', getenv('ESTADOS'));

                foreach ($tareas as $tarea) {
                    if (in_array($tarea['estado'], [$estados[0], $estados[1], $estados[2]])) {
                        if (in_array($tarea['id'], $idsTareas)) {

                            $response = Http::withHeaders([
                                "Accept" => "application/json",
                                "Authorization" => "Bearer $token"
                            ])->get(getenv('GTAPI_ASIGNA') . "/tarea/" . $tarea['id']);

                            if ($response->getStatusCode() == 404) {
                                return redirect()->route('tareas.error')->withErrors([
                                    'message' => "Error al obtener las tareas asignadas.",
                                ]);
                            }

                            if ($response->successful()) {
                                $asignaciones = json_decode($response->body(), true);
                                $asignaciones = $asignaciones['tareas_asignada'] ?? [];
                                $usuariosAsignados = count($asignaciones);
                                $tarea['usuarios_asignados'] = $usuariosAsignados;
                            }

                            $tareasPorCategoria[$tarea['categoria']][] = $tarea;
                        }
                    }
                }

                return view('tareas.inicio', ['tareasPorCategoria' => $tareasPorCategoria], ['usuario' => $usuario]);
            }
        }

        return redirect()->to('logout')->withErrors([
            'message' => "Error al obtener las tareas.",
        ]);
    }

    public function buscar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $filasPorPagina = 15;

        if(Cache::has('filasPorPagina')){
            $filasPorPagina = Cache::get('filasPorPagina');
        }

        $filasPorPagina = $request->input('filasPorPagina', $filasPorPagina); // Usa el valor de 'filasPorPagina' que viene en la solicitud
        $paginaActual = $request->input('pagina', 1); // Página por defecto si no se proporciona

        Cache::put('filasPorPagina', $filasPorPagina);

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS'), [
            'filasPorPagina' => $filasPorPagina,
            'pagina' => $paginaActual,
        ]);

        if ($response->successful()) {
            $tareas = json_decode($response->body(), true);
            $tareas = $tareas['tareas'] ?? [];

            $totalTareas = count($tareas);
            $totalPaginas = ceil($totalTareas / $filasPorPagina);

            if($paginaActual > $totalPaginas){
                return redirect()->to('error.404');
            }

            $tareas = array_slice($tareas, ($paginaActual - 1) * $filasPorPagina, $filasPorPagina);

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTOAUTH_USUARIOS'));

            if ($response->successful()) {
                $usuarios = json_decode($response->body(), true);
                $usuarios = $usuarios['usuarios'] ?? [];

                foreach ($tareas as &$tarea) {
                    $usuarioCreadorId = $tarea['id_usuario'];
                    foreach ($usuarios as $usuario) {
                        if ($usuario['id'] == $usuarioCreadorId) {
                            $tarea['creador_nombre'] = $usuario['nombre'];
                            $tarea['creador_apellido'] = $usuario['apellido'];
                        }
                    }
                }
            }

            return view('tareas.buscar', [
                'tareas' => $tareas,
                'usuario' => $usuario,
                'paginaActual' => $paginaActual,
                'filasPorPagina' => $filasPorPagina,
                'totalPaginas' => $totalPaginas,
                'totalTareas' => $totalTareas
            ]);
        }

        return redirect()->to('logout')->withErrors([
            'message' => "Error al obtener las tareas.",
        ]);
    }

    public function ver($id)
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS')."/".$id);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $tarea = json_decode($response->body(), true);
            $tarea = $tarea['tarea'] ?? [];

            return view('tareas.ver', ['id' => $id, 'usuario' => $usuario, 'tarea' => $tarea]);
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function form_crear()
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTOAUTH_USUARIOS'));

        if ($response->successful()) {
            $usuarios = json_decode($response->body(), true);
            $usuarios = $usuarios['usuarios'] ?? [];
            $usuarios = collect($usuarios)->sortBy('nombre');
        }

        return view('tareas.crear', ['usuario' => $usuario, 'usuarios' => $usuarios]);
    }

    public function guardar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $datos = $request->validate([
            'titulo' => ['required', 'string'],
            'texto' => ['required', 'string'],
            'fecha_hora_inicio' => ['required', 'date_format:Y-m-d\TH:i'],
            'fecha_hora_fin' => ['required', 'date_format:Y-m-d\TH:i'],
            'categoria' => ['required', 'string'],
            'estado' => ['required', 'string'],
        ], [
            'titulo.required' => 'Debe ingresar el título de la tarea.',
            'texto.required' => 'Debe ingresar el texto de la tarea.',
        ]);

        $fechaHoraInicio = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha_hora_inicio']);
        $datos['fecha_hora_inicio'] = $fechaHoraInicio->format('Y-m-d H:i:s');

        $fechaHoraFin = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha_hora_fin']);
        $datos['fecha_hora_fin'] = $fechaHoraFin->format('Y-m-d H:i:s');

        $datos['id_usuario'] = $usuario['id'];

        $idsUsuarios = $request->input('idsUsuarios');

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->post(getenv("GTAPI_TAREAS"), $datos);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            $idTarea = $valores['tarea_id'];

            if ($idsUsuarios === null) {
                return redirect()->route('tareas.crear')->withErrors([
                    'message' => "No se pudo asignar la tarea a los usuarios seleccionados."
                ]);
            }

            $idsUsuarios = explode(',', $idsUsuarios);

            foreach ($idsUsuarios as $idUsuario) {
                $datosAsigna = [
                    'id_usuario_creador' => $usuario['id'],
                    'id_usuario_asignado' => $idUsuario,
                    'id_tarea' => $idTarea,
                ];

                $responseAsigna = Http::withHeaders([
                    "Accept" => "application/json",
                    "Authorization" => "Bearer $token"
                ])->post(getenv("GTAPI_ASIGNA"), $datosAsigna);

                if($responseAsigna->getStatusCode() != 200){
                    return redirect()->route('tareas.crear')->withErrors([
                        'message' => $valores['message'],
                    ]);
                }
            }

            return redirect()->route('tareas.crear')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.crear')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function form_modificar($id)
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS')."/".$id);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $tarea = json_decode($response->body(), true);
            $tarea = $tarea['tarea'] ?? [];

            return view('tareas.modificar', ['id' => $id, 'usuario' => $usuario, 'tarea' => $tarea]);
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function modificar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $tarea = $request->validate([
            'id' => ['required', 'string'],
            'titulo' => ['required', 'string'],
            'texto' => ['required', 'string'],
            'fecha_hora_inicio' => ['required', 'date_format:Y-m-d\TH:i'],
            'fecha_hora_fin' => ['required', 'date_format:Y-m-d\TH:i'],
            'categoria' => ['required', 'string'],
            'estado' => ['required', 'string'],
        ], [
            'titulo.required' => 'Debe ingresar el título de la tarea.',
            'texto.required' => 'Debe ingresar el texto de la tarea.',
        ]);

        $fechaHoraInicio = DateTime::createFromFormat('Y-m-d\TH:i', $tarea['fecha_hora_inicio']);
        $tarea['fecha_hora_inicio'] = $fechaHoraInicio->format('Y-m-d H:i:s');

        $fechaHoraFin = DateTime::createFromFormat('Y-m-d\TH:i', $tarea['fecha_hora_fin']);
        $tarea['fecha_hora_fin'] = $fechaHoraFin->format('Y-m-d H:i:s');

        $tarea['id_usuario'] = $usuario['id'];
        $tarea['id_usuario_modificacion'] = $usuario['id'];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->put(getenv("GTAPI_TAREAS")."/".$tarea['id'], $tarea);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            return redirect()->route('tareas.modificar', ['id' => $tarea['id']])->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.inicio')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function actualizar_categoria(Request $request, $id)
    {
        $token = $this->getActiveUserToken();
        $usuario = $this->getActiveUserData();

        $tarea = $request->validate([
            'categoria' => ['required', 'string'],
        ], [
            'categoria.required' => 'Debe ingresar la categoría de la tarea.',
        ]);

        $tarea['id_usuario'] = $usuario['id'];
        $tarea['id_usuario_modificacion'] = $usuario['id'];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->put(getenv("GTAPI_TAREAS")."/categoria/".$id, $tarea);

        if($response->getStatusCode() == 200){
            return response()->json([
                'success' => 'La categoría de la tarea fue actualizada correctamente'
            ]);
        }

        return response()->json([
            'error' => 'Hubo un error al actualizar la categoría de la tarea'
        ], 500);
    }

    public function eliminar($id){
        $token = $this->getActiveUserToken();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->delete(getenv("GTAPI_TAREAS") . "/" . $id);

        $valores = json_decode($response->body(), true);

        if ($response->getStatusCode() == 200) {
            return redirect()->route('tareas.inicio')->with(
                'success', 'La tarea fue eliminada correctamente');
        }

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}

