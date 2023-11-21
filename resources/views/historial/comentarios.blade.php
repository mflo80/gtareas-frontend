@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="contenedor-buscar">
    <div class="paginas-subtitulo">
        <h2>Historial de los Comentarios</h2>
    </div>

    <div class="formulario-buscar-contenedor">
        <form id="paginationFormHistorialComentarios" method="GET">
            <div class="formulario-buscar-paginado">
                <label class="formulario-buscar-titulo" for="estado">Cantidad de filas a mostrar:</label>
                <select id="rowsPerPageHistorialComentarios" name="filasPorPaginaHistorialTareas">
                    <option value="8" @if ($filasPorPaginaHistorialComentarios == 16) selected @endif>8</option>
                    <option value="16" @if ($filasPorPaginaHistorialComentarios == 16) selected @endif>16</option>
                    <option value="32" @if ($filasPorPaginaHistorialComentarios == 32) selected @endif>32</option>
                    <option value="64" @if ($filasPorPaginaHistorialComentarios == 64) selected @endif>64</option>
                    <option value="96" @if ($filasPorPaginaHistorialComentarios == 96) selected @endif>96</option>
                </select>
            </div>
            <div class="formulario-buscar-orden">
                <label class="formulario-buscar-titulo" for="orden">Ordenar por ID:</label>
                <select id="ordenHistorialComentarios" name="ordenHistorial">
                    <option value="asc" @if ($ordenHistorialComentarios == 'asc') selected @endif>Ascendente</option>
                    <option value="desc" @if ($ordenHistorialComentarios == 'desc') selected @endif>Descendente</option>
                </select>
            </div>
        </form>
    </div>

    <table class="tabla-historial">
        <thead class="tabla-historial-titulos">
            <tr>
                <th class="columna-comentarios-id">Id</th>
                <th class="columna-comentarios-evento">Evento</th>
                <th class="columna-comentarios-tarea">Tarea</th>
                <th class="columna-comentarios-tipo">Usuario</th>
                <th class="columna-comentarios-texto">Fecha Creación</th>
                <th class="columna-comentarios-tipo">Tipo</th>
                <th class="columna-comentarios-texto">Comentario</th>
                <th class="columna-comentarios-fecha-inicio">Fecha Modificación</th>
                <th class="columna-comentarios-opciones">Opciones</th>
            </tr>
        </thead>
        <tbody class="tabla-historial-body">
            @foreach ($historiales as $tarea)
                <tr>
                    <td class="celda-comentarios-id" rowspan="2">{{ $tarea['id'] }}</td>
                    <td class="celda-comentarios-evento" rowspan="2">{{ $tarea['evento'] }}</td>
                    <td class="celda-comentarios-tarea" rowspan="2">{{ $tarea['id_tarea'] }}</td>
                    <td class="celda-comentarios-usuario" rowspan="2">{{ $tarea['nombre'] }} {{ $tarea['apellido'] }}</td>
                    <td class="celda-comentarios-fecha-inicio" rowspan="2">{{ empty($tarea['fecha_hora_creacion']) ? '' : (new DateTime($tarea['fecha_hora_creacion']))->format('Y-m-d') }}</td>
                    <td class="celda-comentarios-tipo">Old</td>
                    <td class="celda-comentarios-texto">{{ $tarea['old_comentario'] }}</td>
                    <td class="celda-comentarios-fecha-modificacion" rowspan="2">{{ date('Y-m-d H:i', strtotime($tarea['fecha_hora_modificacion'])) }}</td>
                    <td class="celda-comentarios-opciones" rowspan="2">
                        @if((array_key_exists('tarea_asignada', $tarea) && $tarea['tarea_asignada'] == 1) && $tarea['evento'] != 'Eliminada')
                            <a href="{{ route('tareas.ver', ['id' => $tarea['id_tarea']]) }}">Ver</a>
                            <a href="{{ route('tareas.modificar', ['id' => $tarea['id_tarea']]) }}">Modificar</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="celda-comentarios-tipo">New</td>
                    <td class="celda-comentarios-texto">{{ $tarea['new_comentario'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="paginacion">
        <button class="btn-paginacion" onclick="window.location.href =
            '{{ route('historial.comentarios', ['filasPorPaginaHistorialComentarios' => $filasPorPaginaHistorialComentarios, 'pagina' => 1]) }}'"
            @if ($paginaActualHistorialComentarios == 1) disabled @endif>&#x23EE;</button>

        @if ($paginaActualHistorialComentarios > 1)
            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('historial.comentarios', ['filasPorPaginaHistorialComentarios' => $filasPorPaginaHistorialComentarios, 'pagina' => $paginaActualHistorialComentarios - 1]) }}'">&#x25C0;</button>
        @endif

        <span>Página {{ $paginaActualHistorialComentarios }} de {{ $totalPaginas }}</span>

        @if ($paginaActualHistorialComentarios < $totalPaginas)
            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('historial.comentarios', ['filasPorPaginaHistorialComentarios' => $filasPorPaginaHistorialComentarios, 'pagina' => $paginaActualHistorialComentarios + 1]) }}'">&#x25B6;</button>
        @endif

        <button class="btn-paginacion" onclick="window.location.href =
            '{{ route('historial.comentarios', ['filasPorPaginaHistorialComentarios' => $filasPorPaginaHistorialComentarios, 'pagina' => $totalPaginas]) }}'"
            @if ($paginaActualHistorialComentarios == $totalPaginas) disabled @endif>&#x23ED;</button>
    </div>

<script>
    window.document.title = 'Gestor de Tareas - Historial Comentarios';
</script>

<script>
    window.routes = {
        buscarHistorialComentarios: '{{ route('historial.comentarios') }}'
    };
</script>

<script src="{{ asset('js/historial/historialComentarios.js') }}"></script>

@endsection
