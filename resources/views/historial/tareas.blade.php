@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="contenedor-buscar">
        <div class="paginas-subtitulo">
            <h2>Historial de las Tareas</h2>
        </div>

        <div class="formulario-buscar-contenedor">
            <form id="paginationFormHistorialTareas" method="GET">
                <div class="formulario-buscar-paginado">
                    <label class="formulario-buscar-titulo" for="estado">Cantidad de filas a mostrar:</label>
                    <select id="rowsPerPageHistorialTareas" name="filasPorPaginaHistorialTareas">
                        <option value="4" @if ($filasPorPaginaHistorialTareas == 4) selected @endif>4</option>
                        <option value="8" @if ($filasPorPaginaHistorialTareas == 8) selected @endif>8</option>
                        <option value="16" @if ($filasPorPaginaHistorialTareas == 16) selected @endif>16</option>
                        <option value="32" @if ($filasPorPaginaHistorialTareas == 32) selected @endif>32</option>
                        <option value="64" @if ($filasPorPaginaHistorialTareas == 64) selected @endif>64</option>
                        <option value="96" @if ($filasPorPaginaHistorialTareas == 96) selected @endif>96</option>
                    </select>
                </div>
                <div class="formulario-buscar-orden">
                    <label class="formulario-buscar-titulo" for="orden">Ordenar por ID:</label>
                    <select id="ordenHistorial" name="ordenHistorial">
                        <option value="asc" @if ($ordenHistorial == 'asc') selected @endif>Ascendente</option>
                        <option value="desc" @if ($ordenHistorial == 'desc') selected @endif>Descendente</option>
                    </select>
                </div>
            </form>
        </div>

        <table class="tabla-historial">
            <thead class="tabla-historial-titulos">
                <tr>
                    <th class="columna-historial-id">Id</th>
                    <th class="columna-historial-evento">Evento</th>
                    <th class="columna-historial-tarea">Tarea</th>
                    <th class="columna-historial-tipo">Tipo</th>
                    <th class="columna-historial-titulo">Título</th>
                    <th class="columna-historial-texto">Texto</th>
                    <th class="columna-historial-fecha-inicio">Fecha Inicio</th>
                    <th class="columna-historial-fecha-fin">Fecha Fin</th>
                    <th class="columna-historial-categoria">Categoría</th>
                    <th class="columna-historial-estado">Estado</th>
                    <th class="columna-historial-usuario">Autor</th>
                    <th class="columna-historial-fecha-modificacion">Registro</th>
                    <th class="columna-historial-editor">Editor</th>
                    <th class="columna-historial-opciones">Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-historial-body">
                @foreach ($historiales as $tarea)
                    <tr>
                        <td class="celda-historial-id" rowspan="2">{{ $tarea['id'] }}</td>
                        <td class="celda-historial-evento" rowspan="2">{{ $tarea['evento'] }}</td>
                        <td class="celda-historial-tarea" rowspan="2">{{ $tarea['id_tarea'] }}</td>
                        <td class="celda-historial-tipo">Old</td>
                        <td class="celda-historial-titulo">{{ $tarea['old_titulo'] }}</td>
                        <td class="celda-historial-texto">{{ $tarea['old_texto'] }}</td>
                        <td class="celda-historial-fecha-inicio">{{ empty($tarea['old_fecha_hora_inicio']) ? '' : (new DateTime($tarea['old_fecha_hora_inicio']))->format('Y-m-d') }}</td>
                        <td class="celda-historial-fecha-fin">{{ empty($tarea['old_fecha_hora_fin']) ? '' : (new DateTime($tarea['old_fecha_hora_fin']))->format('Y-m-d') }}</td>
                        <td class="celda-historial-categoria">{{ $tarea['old_categoria'] }}</td>
                        <td class="celda-historial-estado">{{ $tarea['old_estado'] }}</td>
                        <td class="celda-historial-usuario">{{ $tarea['old_nombre'] }} {{ $tarea['old_apellido'] }}</td>
                        <td class="celda-historial-fecha-modificacion" rowspan="2">{{ date('Y-m-d H:i', strtotime($tarea['fecha_hora_modificacion'])) }}</td>
                        <td class="celda-historial-editor" rowspan="2">{{ $tarea['editor_nombre'] }} {{ $tarea['editor_apellido'] }}</td>
                        <td class="celda-historial-opciones" rowspan="2">
                            @if((array_key_exists('tarea_asignada', $tarea) && $tarea['tarea_asignada'] == 1) && $tarea['evento'] != 'Eliminada')
                                <a href="{{ route('tareas.ver', ['id' => $tarea['id_tarea']]) }}">Ver</a>
                                <a href="{{ route('tareas.modificar', ['id' => $tarea['id_tarea']]) }}">Modificar</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="celda-historial-tipo">New</td>
                        <td class="celda-historial-titulo">{{ $tarea['new_titulo'] }}</td>
                        <td class="celda-historial-texto">{{ $tarea['new_texto'] }}</td>
                        <td class="celda-historial-fecha-inicio">{{ empty($tarea['new_fecha_hora_inicio']) ? '' : (new DateTime($tarea['new_fecha_hora_inicio']))->format('Y-m-d') }}</td>
                        <td class="celda-historial-fecha-fin">{{ empty($tarea['new_fecha_hora_fin']) ? '' : (new DateTime($tarea['new_fecha_hora_fin']))->format('Y-m-d') }}</td>
                        <td class="celda-historial-categoria">{{ $tarea['new_categoria'] }}</td>
                        <td class="celda-historial-estado">{{ $tarea['new_estado'] }}</td>
                        <td class="celda-historial-usuario">{{ $tarea['new_nombre'] }} {{ $tarea['new_apellido'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="paginacion">
            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('historial.tareas', ['filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas, 'pagina' => 1]) }}'"
                @if ($paginaActualHistorialTareas == 1) disabled @endif>&#x23EE;</button>

            @if ($paginaActualHistorialTareas > 1)
                <button class="btn-paginacion" onclick="window.location.href =
                    '{{ route('historial.tareas', ['filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas, 'pagina' => $paginaActualHistorialTareas - 1]) }}'">&#x25C0;</button>
            @endif

            <span>Página {{ $paginaActualHistorialTareas }} de {{ $totalPaginas }}</span>

            @if ($paginaActualHistorialTareas < $totalPaginas)
                <button class="btn-paginacion" onclick="window.location.href =
                    '{{ route('historial.tareas', ['filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas, 'pagina' => $paginaActualHistorialTareas + 1]) }}'">&#x25B6;</button>
            @endif

            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('historial.tareas', ['filasPorPaginaHistorialTareas' => $filasPorPaginaHistorialTareas, 'pagina' => $totalPaginas]) }}'"
                @if ($paginaActualHistorialTareas == $totalPaginas) disabled @endif>&#x23ED;</button>
        </div>


<script>
    window.document.title = 'Gestor de Tareas - Historial Tareas';
</script>

<script>
    window.routes = {
        buscarHistorialTareas: '{{ route('historial.tareas') }}'
    };
</script>

<script src="{{ asset('js/historial/historialTareas.js') }}"></script>

@endsection
