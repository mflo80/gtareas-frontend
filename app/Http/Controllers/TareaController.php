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

        $filasPorPagina = 15;

        if(Cache::has('filasPorPagina')){
            $filasPorPagina = Cache::get('filasPorPagina');
        }

        if($filasPorPagina == null || $filasPorPagina == '' || $filasPorPagina == 0){
            $filasPorPagina = 15;
        }

        $filasPorPagina = $request->input('filasPorPagina', $filasPorPagina);
        $paginaActual = $request->input('pagina', 1);
        Cache::put('filasPorPagina', $filasPorPagina);

        $ordenTareas = 'asc';

        if(Cache::has('ordenTareas')){
            $ordenTareas = Cache::get('ordenTareas');
        }

        if($ordenTareas == null || $ordenTareas == ''){
            $ordenTareas = 'asc';
        }

        $ordenTareas = $request->input('ordenTareas', $ordenTareas);
        Cache::put('ordenTareas', $ordenTareas);

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

        return redirect()->route('tareas.error')->withErrors([
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
                    return redirect()->route('tareas.error')->withErrors([
                        'message' => "No tiene permisos sobre esta tarea."
                    ]);
                }
            }

            if ($usuarioCreador == null || !is_array($usuarioCreador) || $usuariosAsignados == null || !is_array($usuariosAsignados)) {
                return redirect()->route('tareas.error')->withErrors([
                    'message' => "Error al obtener los usuarios asignados a la tarea."
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

        return redirect()->route('tareas.error')->withErrors([
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

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    public function guardar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

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

        $idsUsuarios = $request->input('idsUsuarios');

        $fechaHoraInicio = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha_hora_inicio']);
        $datos['fecha_hora_inicio'] = $fechaHoraInicio->format('Y-m-d H:i:s');

        $fechaHoraFin = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha_hora_fin']);
        $datos['fecha_hora_fin'] = $fechaHoraFin->format('Y-m-d H:i:s');

        $datos['id_usuario'] = $usuarioLogueado['id'];

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

            $datos['nombre_usuario'] = $usuarioLogueado['nombre'] . ' ' . $usuarioLogueado['apellido'];
            $datos['id_tarea'] = $idTarea;
            $datos['usuarios_asignados'] = $datos_usuarios_asignados;
            $datos['usuario_email'] = $usuarioLogueado['email'];

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer $token"
            ])->post(getenv("GTAPI_CORREOS"), $datos);

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
                    return redirect()->route('tareas.error')->withErrors([
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

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }

    //
    // PENDIENTE VER COMO HACER PARA QUE SE PUEDA MODIFICAR LOS USUARIOS ASIGNADOS EN LA TAREA
    //

    public function modificar(Request $request)
    {
        $token = $this->getActiveUserToken();
        $usuarioLogueado = $this->getActiveUserData();

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

        $idsUsuarios = $request->input('idsUsuarios');
        $idUsuarioCreador = $request->input('idUsuarioCreador');

        $fechaHoraInicio = DateTime::createFromFormat('Y-m-d\TH:i', $tarea['fecha_hora_inicio']);
        $tarea['fecha_hora_inicio'] = $fechaHoraInicio->format('Y-m-d H:i:s');

        $fechaHoraFin = DateTime::createFromFormat('Y-m-d\TH:i', $tarea['fecha_hora_fin']);
        $tarea['fecha_hora_fin'] = $fechaHoraFin->format('Y-m-d H:i:s');

        $tarea['id_usuario_modificacion'] = $usuarioLogueado['id'];

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->put(getenv("GTAPI_TAREAS")."/".$tarea['id'], $tarea);

        $valores = json_decode($response->body(), true);

        $tareaAsignada = $this->getTareaAsignada($tarea['id']);
        foreach ($tareaAsignada as $usuarioAsignado) {
            $usuariosAsignados[] = $usuarioAsignado['id_usuario_asignado'];
        }

        $idsUsuarios = explode(',', $idsUsuarios);

        if($response->getStatusCode() == 200){
            if ($idsUsuarios === null) {
                return redirect()->route('tareas.modificar', ['id' => $tarea['id']])->withErrors([
                        'message' => "No se pudo asignar la tarea a los usuarios seleccionados."
                ]);
            }

            foreach($usuariosAsignados as $usuarioAsignado){
                if(!in_array($usuarioAsignado, $idsUsuarios)){
                    if($usuarioAsignado != $idUsuarioCreador){
                        $response = Http::withHeaders([
                            "Accept" => "application/json",
                            "Authorization" => "Bearer $token"
                        ])->delete(getenv("GTAPI_ASIGNA")."/".$idUsuarioCreador."/".$usuarioAsignado."/".$tarea['id']);
                    }
                }
            }

            $usuarioAsignadoCorrectamente = false;

            foreach($idsUsuarios as $idUsuario){
                if(!in_array($idUsuario, $usuariosAsignados)){
                    $datosAsigna = [
                        'id_usuario_creador' => $idUsuarioCreador,
                        'id_usuario_asignado' => $idUsuario,
                        'id_tarea' => $tarea['id'],
                    ];

                    $response = Http::withHeaders([
                        "Accept" => "application/json",
                        "Authorization" => "Bearer $token"
                    ])->post(getenv("GTAPI_ASIGNA"), $datosAsigna);

                    if($response->getStatusCode() == 200){
                        $usuarioAsignadoCorrectamente = true;
                    }
                }
            }

            if($usuarioAsignadoCorrectamente){
                return redirect()->route('tareas.modificar', ['id' => $tarea['id']])->withErrors([
                    'message' => 'La tarea fue modificada correctamente'
                ]);
            }

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

        return redirect()->route('tareas.error')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}

