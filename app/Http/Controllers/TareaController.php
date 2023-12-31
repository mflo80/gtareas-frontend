<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TareaController extends Controller
{
    public function index()
    {
        $usuarioLogueado = $this->getActiveUserData();
        $tareas = $this->getTareas();
        $tareasAsignadas = $this->getTareasAsignadas();

        if (empty($tareasAsignadas)) {
            return view('tareas.inicio', [
                'tareasPorCategoria' => [],
                'usuarioLogueado' => $usuarioLogueado
            ]);
        }

        $idsTareas = [];
        foreach ($tareasAsignadas as $tareaAsignadaUsuario) {
            if ($tareaAsignadaUsuario['id_usuario_asignado'] == $usuarioLogueado['id']) {
                $idsTareas[] = $tareaAsignadaUsuario['id_tarea'];
            }
        }

        $tareasPorCategoria = [];
        $estados = explode(',', getenv('ESTADOS'));

        foreach ($tareas as $tarea) {
            $tarea['cantidad_comentarios'] = 0;

            if (in_array($tarea['estado'], [$estados[0], $estados[1], $estados[2]])) {
                if (in_array($tarea['id'], $idsTareas)) {

                    $tareasComentarios = $this->getTareasComentarios($tarea['id']);
                    $tarea['cantidad_comentarios'] = count($tareasComentarios);

                    $cantidadUsuariosPorTarea = [];

                    foreach ($tareasAsignadas as $tareaAsignada) {
                        if ($tareaAsignada['id_tarea'] == $tarea['id']) {
                            $cantidadUsuariosPorTarea[] = $tareaAsignada['id_usuario_asignado'];
                        }
                    }

                    $tarea['usuarios_asignados'] = count($cantidadUsuariosPorTarea);
                    $tareasPorCategoria[$tarea['categoria']][] = $tarea;
                }
            }
        }

        return view('tareas.inicio', [
            'tareasPorCategoria' => $tareasPorCategoria,
            'usuarioLogueado' => $usuarioLogueado
        ]);
    }

    public function buscar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

        $paginaActual = $request->input('pagina', 1);
        $filasPorPaginaInput = $request->input('filasPorPagina');
        $ordenTareasInput = $request->input('ordenTareas');

        if ($filasPorPaginaInput) {
            Cache::put('filasPorPagina'.$usuarioLogueado['id'], $filasPorPaginaInput, Carbon::now()->addMinutes(60));
            $filasPorPagina = $filasPorPaginaInput;
        } else {
            $filasPorPagina = Cache::remember('filasPorPagina'.$usuarioLogueado['id'], Carbon::now()->addMinutes(60), function () {
                return 15;
            });
        }

        if ($ordenTareasInput) {
            Cache::put('ordenTareas'.$usuarioLogueado['id'], $ordenTareasInput, Carbon::now()->addMinutes(60));
            $ordenTareas = $ordenTareasInput;
        } else {
            $ordenTareas = Cache::remember('ordenTareas'.$usuarioLogueado['id'], Carbon::now()->addMinutes(60), function () {
                return 'desc';
            });
        }

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS'), [
            'filasPorPagina' => $filasPorPagina,
            'pagina' => $paginaActual,
        ]);

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $tareas = json_decode($response->body(), true);
            $tareas = $tareas['tareas'] ?? [];

            if($ordenTareas == 'desc'){
                $tareas = array_reverse($tareas);
            }

            $totalTareas = count($tareas);

            $totalPaginas = ceil($totalTareas / intval($filasPorPagina));

            if($paginaActual > $totalPaginas){
                return redirect()->to('error.404');
            }

            $tareasAsignadas = $this->getTareasAsignadas();

            $idsTareas = [];
            foreach ($tareasAsignadas as $tareaAsignadaUsuario) {
                if ($tareaAsignadaUsuario['id_usuario_asignado'] == $usuarioLogueado['id']) {
                    $idsTareas[] = $tareaAsignadaUsuario['id_tarea'];
                }
            }

            $tareas = array_slice($tareas, ($paginaActual - 1) * $filasPorPagina, $filasPorPagina);

            $usuarios = $this->getUsuarios();

            foreach ($tareas as &$tarea) {
                $usuarioCreadorId = $tarea['id_usuario'];
                foreach ($usuarios as $usuario) {
                    if ($usuario['id'] == $usuarioCreadorId) {
                        $tarea['creador_nombre'] = $usuario['nombre'];
                        $tarea['creador_apellido'] = $usuario['apellido'];
                    }
                }

                if (in_array($tarea['id'], $idsTareas)) {
                    $tarea['tarea_asignada'] = 1;
                }
            }

            return view('tareas.buscar', [
                'tareas' => $tareas,
                'usuarioLogueado' => $usuarioLogueado,
                'paginaActual' => $paginaActual,
                'filasPorPagina' => $filasPorPagina,
                'totalPaginas' => $totalPaginas,
                'totalTareas' => $totalTareas,
                'ordenTareas' => $ordenTareas,
            ]);
        }

        if($response->getStatusCode() == 404){
            return redirect()->route('tareas.error-404')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.error-500')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function ver($id)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();
        $tareaComentarios = $this->getTareasComentarios($id);

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS')."/".$id);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            $tarea = $valores['tarea'] ?? [];

            $usuarioCreadorId = $tarea['id_usuario'];

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTOAUTH_USUARIOS'));

            $valores = json_decode($response->body(), true);

            if($response->getStatusCode() == 200){
                $usuarios = $valores['usuarios'] ?? [];
                $usuarios = collect($usuarios)->sortBy('nombre');

                $usuariosAsignados = [];
                $valoresAsigna = [];
                $usuarioCreador = [];

                $response = Http::withHeaders([
                    "Accept" => "application/json",
                    "Authorization" => "Bearer $token"
                ])->get(getenv('GTAPI_ASIGNA') . "/tarea/" . $tarea['id']);

                $valoresAsigna = json_decode(   $response->body(), true);

                if($response->getStatusCode() == 200){
                    $tareaAsignada = $valoresAsigna['tarea_asignada'];

                    foreach($usuarios as $usuario){
                        foreach($tareaAsignada as $tareaAsignadaUsuario){
                            if($usuario['id'] == $usuarioCreadorId){
                                $usuarioCreador = $usuario;
                            }
                            if($usuario['id'] == $tareaAsignadaUsuario['id_usuario_asignado'] && $usuario['id'] != $usuarioCreadorId){
                                $usuariosAsignados[] = $usuario;
                            }
                        }

                        foreach ($tareaComentarios as &$comentario) {
                            if (is_array($usuario) && isset($usuario['id']) && is_array($comentario) && isset($comentario['id_usuario'])) {
                                if ($usuario['id'] == $comentario['id_usuario']) {
                                    $comentario['nombre_usuario'] = $usuario['nombre'];
                                    $comentario['apellido_usuario'] = $usuario['apellido'];
                                }
                            }
                        }
                    }
                }

                $idsUsuariosAsignados = array_map(function($usuario) {
                    return $usuario['id'];
                }, $usuariosAsignados);

                if($usuarioLogueado['id'] != $usuarioCreadorId && !in_array($usuarioLogueado['id'], $idsUsuariosAsignados)){
                    return redirect()->route('tareas.error-404')->withErrors([
                        'message' => "No tiene permisos sobre esta tarea."
                    ]);
                }
            }

            if ($usuarioCreador == null || !is_array($usuarioCreador)) {
                return redirect()->route('tareas.error-404')->withErrors([
                    'message' => "Error al obtener los usuarios creador de la tarea."
                ]);
            }



            return view('tareas.ver', [
                'id' => $id,
                'tarea' => $tarea,
                'tareaComentarios' => $tareaComentarios,
                'usuarios' => $usuarios,
                'usuarioLogueado' => $usuarioLogueado,
                'usuarioCreador' => $usuarioCreador,
                'usuariosAsignados' => $usuariosAsignados,
            ]);
        }

        if($response->getStatusCode() == 404){
            return redirect()->route('tareas.error-404')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.error-500')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function form_crear()
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTOAUTH_USUARIOS'));

        $valores = json_decode($response->body(), true);

        if ($response->successful()) {
            $usuarios = $valores['usuarios'] ?? [];
            $usuarios = collect($usuarios)->sortBy('nombre');

            return view('tareas.crear', [
                'usuarioLogueado' => $usuarioLogueado,
                'usuarios' => $usuarios
            ]);
        }

        if($response->getStatusCode() == 404){
            return redirect()->route('tareas.error-404')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.error-500')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function guardar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

        $tarea = $request->validate([
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

        $idsUsuarios = $request->input('idsUsuarios');

        $fechaHoraInicio = DateTime::createFromFormat('Y-m-d\TH:i', $tarea['fecha_hora_inicio']);
        $tarea['fecha_hora_inicio'] = $fechaHoraInicio->format('Y-m-d H:i:s');

        $fechaHoraFin = DateTime::createFromFormat('Y-m-d\TH:i', $tarea['fecha_hora_fin']);
        $tarea['fecha_hora_fin'] = $fechaHoraFin->format('Y-m-d H:i:s');

        $tarea['id_usuario'] = $usuarioLogueado['id'];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->post(getenv("GTAPI_TAREAS"), $tarea);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            $idTarea = $valores['tarea_id'];

            if ($idsUsuarios === null) {
                return redirect()->route('tareas.crear')->withErrors([
                    'message' => "No se pudo asignar la tarea a los usuarios seleccionados."
                ]);
            }

            $idsUsuarios = explode(',', $idsUsuarios);
            $usuarios = $this->getUsuarios();
            $datos_usuarios_asignados = [];

            foreach ($idsUsuarios as $idUsuario) {
                $datosAsigna = [
                    'id_usuario_creador' => $usuarioLogueado['id'],
                    'id_usuario_asignado' => $idUsuario,
                    'id_tarea' => $idTarea,
                ];

                $usuario = array_filter($usuarios, function($usuario) use ($idUsuario) {
                    return $usuario['id'] == $idUsuario;
                });

                if (!empty($usuario)) {
                    $usuario = array_shift($usuario);
                    $datos_usuarios_asignados[] = [
                        'email' => $usuario['email'],
                        'nombre' => $usuario['nombre'],
                        'apellido' => $usuario['apellido'],
                    ];
                }

                $response = Http::withHeaders([
                    "Accept" => "application/json",
                    "Authorization" => "Bearer $token"
                ])->post(getenv("GTAPI_ASIGNA"), $datosAsigna);

                if($response->getStatusCode() != 200){
                    return redirect()->route('tareas.crear')->withErrors([
                        'message' => $valores['message'],
                    ]);
                }
            }

            $tarea['nombre_usuario'] = $usuarioLogueado['nombre'] . ' ' . $usuarioLogueado['apellido'];
            $tarea['id_tarea'] = $idTarea;
            $tarea['usuarios_asignados'] = $datos_usuarios_asignados;
            $tarea['usuario_email'] = $usuarioLogueado['email'];
            $tarea['tipo'] = 'creación';

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->post(getenv("GTAPI_CORREOS"), $tarea);

            if($response->getStatusCode() == 200){
                return redirect()->route('tareas.crear')->withErrors([
                    'message' => $valores['message'],
                ]);
            }
        }

        return redirect()->route('tareas.crear')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function form_modificar($id)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->get(getenv('GTAPI_TAREAS')."/".$id);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            $tarea = $valores['tarea'] ?? [];

            $usuarioCreadorId = $tarea['id_usuario'];

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->get(getenv('GTOAUTH_USUARIOS'));

            $valores = json_decode($response->body(), true);

            if($response->getStatusCode() == 200){
                $usuarios = $valores['usuarios'] ?? [];
                $usuarios = collect($usuarios)->sortBy('nombre');

                $usuariosAsignados = [];
                $valoresAsigna = [];

                $response = Http::withHeaders([
                    "Accept" => "application/json",
                    "Authorization" => "Bearer $token"
                ])->get(getenv('GTAPI_ASIGNA') . "/tarea/" . $tarea['id']);

                $valoresAsigna = json_decode(   $response->body(), true);

                if($response->getStatusCode() == 200){
                    $tareaAsignada = $valoresAsigna['tarea_asignada'];
                    $usuarioCreador = null;

                    foreach($usuarios as $usuario){
                        foreach($tareaAsignada as $tareaAsignadaUsuario){
                            if($usuario['id'] == $usuarioCreadorId){
                                $usuarioCreador = $usuario;
                            }
                            if($usuario['id'] == $tareaAsignadaUsuario['id_usuario_asignado'] && $usuario['id'] != $usuarioCreadorId){
                                $usuariosAsignados[] = $usuario;
                            }
                        }
                    }
                }

                $idsUsuariosAsignados = array_map(function($usuario) {
                    return $usuario['id'];
                }, $usuariosAsignados);

                if($usuarioLogueado['id'] != $usuarioCreadorId && !in_array($usuarioLogueado['id'], $idsUsuariosAsignados)){
                    return redirect()->route('tareas.error-404')->withErrors([
                        'message' => "No tiene permisos para modificar esta tarea."
                    ]);
                }
            }

            return view('tareas.modificar', [
                'id' => $id,
                'tarea' => $tarea,
                'usuarios' => $usuarios,
                'usuarioLogueado' => $usuarioLogueado,
                'usuarioCreador' => $usuarioCreador,
                'usuariosAsignados' => $usuariosAsignados,
            ]);
        }


        if($response->getStatusCode() == 404){
            return redirect()->route('tareas.error-404')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.error-500')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function modificar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

        $idsUsuarios = $request->input('idsUsuarios');
        $idUsuarioCreador = $request->input('idUsuarioCreador');

        $usuariosAsignados = [];
        $usuariosEliminados = [];
        $datos_usuarios_asignados = [];
        $datos_usuarios_eliminados = [];

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

        $tarea['id_usuario_modificacion'] = $usuarioLogueado['id'];
        $tarea['id_usuario'] = $idUsuarioCreador;

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->put(getenv("GTAPI_TAREAS")."/".$tarea['id'], $tarea);

        if($response->getStatusCode() == 200){
            $valores = json_decode($response->body(), true);
            $tareaAsignada = $this->getTareaAsignada($tarea['id']);

            foreach ($tareaAsignada as $usuarioAsignado) {
                $usuariosAsignados[] = $usuarioAsignado['id_usuario_asignado'];
            }

            $usuarios = $this->getUsuarios();
            $idsUsuarios = explode(',', $idsUsuarios);

            if ($idsUsuarios === null) {
                return redirect()->route('tareas.modificar', ['id' => $tarea['id']])->withErrors([
                        'message' => "No se pudo asignar la tarea a los usuarios seleccionados."
                ]);
            }

            $usuarioAsignadoAgregadoCorrectamente = false;
            $usuarioAsignadoEliminadoCorrectamente = false;

            $usuariosAsignadosAgregados = array_diff($idsUsuarios, $usuariosAsignados);
            $usuariosAsignadosEliminados = array_diff($usuariosAsignados, $idsUsuarios);

            foreach($usuariosAsignadosAgregados as $idUsuario){
                if($idUsuario != $idUsuarioCreador){
                    $datosAsigna = [
                        'id_usuario_creador' => $idUsuarioCreador,
                        'id_usuario_asignado' => $idUsuario,
                        'id_tarea' => $tarea['id'],
                    ];

                    $response = Http::withHeaders([
                        "Accept" => "application/json",
                        "Authorization" => "Bearer $token"
                    ])->post(getenv("GTAPI_ASIGNA"), $datosAsigna);

                    $valores = json_decode($response->body(), true);

                    if($response->getStatusCode() == 200){
                        $usuarioAsignadoAgregadoCorrectamente = true;
                        $usuariosAsignados[] = $idUsuario;
                    }

                    if($response->getStatusCode() != 200){
                        return redirect()->route('tareas.modificar', ['id' => $tarea['id']])->withErrors([
                            'message' => $valores['message'],
                        ]);
                    }
                }
            }

            foreach($usuariosAsignadosEliminados as $idUsuario){
                if($idUsuario != $idUsuarioCreador){
                    $response = Http::withHeaders([
                        "Accept" => "application/json",
                        "Authorization" => "Bearer $token"
                    ])->delete(getenv("GTAPI_ASIGNA")."/".$idUsuarioCreador."/".$idUsuario."/".$tarea['id']);

                    $valores = json_decode($response->body(), true);

                    if($response->getStatusCode() == 200){
                        $usuarioAsignadoEliminadoCorrectamente = true;
                        $usuariosEliminados[] = $idUsuario;
                        $clave = array_search($idUsuario, $usuariosAsignados);
                        if ($clave !== false) {
                            unset($usuariosAsignados[$clave]);
                        }
                    }

                    if($response->getStatusCode() != 200){
                        return redirect()->route('tareas.modificar', ['id' => $tarea['id']])->withErrors([
                            'message' => $valores['message'],
                        ]);
                    }
                }
            }

            if($usuarioAsignadoAgregadoCorrectamente || $usuarioAsignadoEliminadoCorrectamente){
                foreach ($usuariosAsignados as $idUsuario) {
                    $usuario = array_filter($usuarios, function($usuario) use ($idUsuario) {
                        return $usuario['id'] == $idUsuario;
                    });

                    if (!empty($usuario)) {
                        $usuario = array_shift($usuario);
                        $datos_usuarios_asignados[] = [
                            'email' => $usuario['email'],
                            'nombre' => $usuario['nombre'],
                            'apellido' => $usuario['apellido'],
                        ];
                    }
                }

                foreach ($usuariosEliminados as $idUsuario) {
                    $usuario = array_filter($usuarios, function($usuario) use ($idUsuario) {
                        return $usuario['id'] == $idUsuario;
                    });

                    if (!empty($usuario)) {
                        $usuario = array_shift($usuario);
                        $datos_usuarios_eliminados[] = [
                            'email' => $usuario['email'],
                            'nombre' => $usuario['nombre'],
                            'apellido' => $usuario['apellido'],
                        ];
                    }
                }

                $tarea['nombre_usuario'] = $usuarioLogueado['nombre'] . ' ' . $usuarioLogueado['apellido'];
                $tarea['id_tarea'] = $tarea['id'];
                $tarea['usuarios_asignados'] = $datos_usuarios_asignados;
                $tarea['usuarios_eliminados'] = $datos_usuarios_eliminados;
                $tarea['usuario_email'] = $usuarioLogueado['email'];
                $tarea['tipo'] = 'modificación';

                $response = Http::withHeaders([
                    "Accept" => "application/json",
                    "Authorization" => "Bearer $token"
                ])->post(getenv("GTAPI_CORREOS"), $tarea);
            }

            return redirect()->route('tareas.modificar', [
                'id' => $tarea['id'],
            ])->withErrors([
                'message' => $valores['message'],
            ]);
        }

        if($response->getStatusCode() != 200){
            $valores = json_decode($response->body(), true);
            return redirect()->route('tareas.modificar', [
                'id' => $tarea['id'],
            ])->withErrors([
                'message' => $valores['message'] ?? 'No se pudo modificar la tarea, intente nuevamente.',
            ]);
        }
    }

    public function actualizar_categoria(Request $request, $id)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

        $tarea = $request->validate([
            'categoria' => ['required', 'string'],
        ], [
            'categoria.required' => 'Debe ingresar la categoría de la tarea.',
        ]);

        $tarea['id_usuario_modificacion'] = $usuarioLogueado['id'];

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

        if($response->getStatusCode() == 404){
            return redirect()->route('tareas.error-404')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.error-500')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}

