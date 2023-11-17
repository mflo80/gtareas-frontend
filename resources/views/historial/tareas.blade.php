@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="contenedor-buscar">
        <div class="paginas-subtitulo">
            <h1>Historial de las Tareas</h1>
        </div>

        <div class="formulario-buscar-contenedor">
            <form id="paginationFormHistorialTareas" class="formulario-buscar" method="GET">
                <label class="formulario-buscar-titulo" for="estado">Cantidad de filas a mostrar:</label>
                <select id="rowsPerPageHistorialTareas" name="filasPorPaginaHistorialTareas">
                    <option value="16" @if ($filasPorPaginaHistorialTareas == 16) selected @endif>16</option>
                    <option value="32" @if ($filasPorPaginaHistorialTareas == 32) selected @endif>32</option>
                    <option value="64" @if ($filasPorPaginaHistorialTareas == 64) selected @endif>64</option>
                    <option value="96" @if ($filasPorPaginaHistorialTareas == 96) selected @endif>96</option>
                </select>
            </form>
        </div>

        <table class="tabla-buscar">
            <thead>
                <tr>
                    <th class="columna-id">ID</th>
                    <th class="columna-evento">Evento</th>
                    <th class="columna-tarea">Tarea</th>
                    <th class="columna-titulo">Título</th>
                    <th class="columna-texto">Texto</th>
                    <th class="columna-fecha-cracion">Fecha Creación</th>
                    <th class="columna-fecha-inicio">Fecha Inicio</th>
                    <th class="columna-fecha-fin">Fecha Fin</th>
                    <th class="columna-categoria">Categoría</th>
                    <th class="columna-estado">Estado</th>
                    <th class="columna-usuario">Usuario</th>
                    <th class="columna-opciones">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas as $tarea)
                    <tr>
                        <td class="fila-id" rowspan="2">{{ $tarea['id'] }}</td>
                        <td class="fila-evento" rowspan="2">{{ $tarea['evento'] }}</td>
                        <td class="fila-tarea" rowspan="2">{{ $tarea['id_tarea'] }}</td>
                        <td class="fila-titulo">{{ $tarea['old_titulo'] }}</td>
                        <td class="fila-texto">{{ $tarea['old_texto'] }}</td>
                        <td class="fila-fecha-creacion">{{ empty($tarea['old_fecha_hora_creacion']) ? '' : (new DateTime($tarea['old_fecha_hora_creacion']))->format('Y-m-d') }}</td>
                        <td class="fila-fecha-inicio">{{ empty($tarea['old_fecha_hora_inicio']) ? '' : (new DateTime($tarea['old_fecha_hora_inicio']))->format('Y-m-d') }}</td>
                        <td class="fila-fecha-fin">{{ empty($tarea['old_fecha_hora_fin']) ? '' : (new DateTime($tarea['old_fecha_hora_fin']))->format('Y-m-d') }}</td>
                        <td class="fila-categoria">{{ $tarea['old_categoria'] }}</td>
                        <td class="fila-estado">{{ $tarea['old_estado'] }}</td>
                        <td class="fila-usuario">{{ $tarea['old_nombre'] }} {{ $tarea['old_apellido'] }}</td>
                        <td class="fila-opciones" rowspan="2">
                            <a href="{{ route('tareas.ver', ['id' => $tarea['id']]) }}">Ver</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="fila-titulo">{{ $tarea['new_titulo'] }}</td>
                        <td class="fila-titulo">{{ $tarea['new_texto'] }}</td>
                        <td class="fila-fecha-creacion">{{ empty($tarea['new_fecha_hora_creacion']) ? '' : (new DateTime($tarea['new_fecha_hora_creacion']))->format('Y-m-d') }}</td>
                        <td class="fila-fecha-inicio">{{ empty($tarea['new_fecha_hora_inicio']) ? '' : (new DateTime($tarea['new_fecha_hora_inicio']))->format('Y-m-d') }}</td>
                        <td class="fila-fecha-fin">{{ empty($tarea['new_fecha_hora_fin']) ? '' : (new DateTime($tarea['new_fecha_hora_fin']))->format('Y-m-d') }}</td>
                        <td class="fila-categoria">{{ $tarea['new_categoria'] }}</td>
                        <td class="fila-estado">{{ $tarea['new_estado'] }}</td>
                        <td class="fila-usuario">{{ $tarea['new_nombre'] }} {{ $tarea['new_apellido'] }}</td>
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

@endsection
