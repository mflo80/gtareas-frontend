@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="contenedor-buscar">
        <div class="paginas-subtitulo">
            <h2>Buscar</h2>
        </div>

        <div class="formulario-buscar-contenedor">
            <form id="paginationForm" class="formulario-buscar" method="GET">
                <label class="formulario-buscar-titulo" for="estado">Cantidad de filas a mostrar:</label>
                <select id="rowsPerPage" name="filasPorPagina">
                    <option value="15" @if ($filasPorPagina == 15) selected @endif>15</option>
                    <option value="30" @if ($filasPorPagina == 30) selected @endif>30</option>
                    <option value="50" @if ($filasPorPagina == 50) selected @endif>50</option>
                    <option value="100" @if ($filasPorPagina == 100) selected @endif>100</option>
                </select>
            </form>
        </div>

        <table class="tabla-buscar">
            <thead>
                <tr>
                    <th class="columna-id">ID</th>
                    <th class="columna-titulo">Título</th>
                    <th class="columna-fecha-cracion">Fecha Creación</th>
                    <th class="columna-fecha-inicio">Fecha Inicio</th>
                    <th class="columna-fecha-fin">Fecha Fin</th>
                    <th class="columna-categoria">Categoría</th>
                    <th class="columna-estado">Estado</th>
                    <th class="columna-usuario">Usuario Creador</th>
                    <th class="columna-opciones">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas as $tarea)
                    <tr>
                        <td class="fila-id">{{ $tarea['id'] }}</td>
                        <td class="fila-titulo">{{ $tarea['titulo'] }}</td>
                        <td class="fila-fecha-creacion">{{ (new DateTime($tarea['fecha_hora_creacion']))->format('Y-m-d') }}</td>
                        <td class="fila-fecha-inicio">{{ (new DateTime($tarea['fecha_hora_inicio']))->format('Y-m-d') }}</td>
                        <td class="fila-fecha-fin">{{ (new DateTime($tarea['fecha_hora_fin']))->format('Y-m-d') }}</td>
                        <td class="fila-categoria">{{ $tarea['categoria'] }}</td>
                        <td class="fila-estado">{{ $tarea['estado'] }}</td>
                        <td class="fila-usuario">{{ $tarea['creador_nombre'] }} {{ $tarea['creador_apellido'] }}</td>
                        <td class="fila-opciones">
                            <a href="{{ route('tareas.ver', ['id' => $tarea['id']]) }}">Ver</a>
                            <a href="{{ route('tareas.modificar', ['id' => $tarea['id']]) }}">Modificar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="paginacion">
            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('tareas.buscar', ['filasPorPagina' => $filasPorPagina, 'pagina' => 1]) }}'"
                @if ($paginaActual == 1) disabled @endif>&#x23EE;</button>

            @if ($paginaActual > 1)
                <button class="btn-paginacion" onclick="window.location.href =
                    '{{ route('tareas.buscar', ['filasPorPagina' => $filasPorPagina, 'pagina' => $paginaActual - 1]) }}'">&#x25C0;</button>
            @endif

            <span>Página {{ $paginaActual }} de {{ $totalPaginas }}</span>

            @if ($paginaActual < $totalPaginas)
                <button class="btn-paginacion" onclick="window.location.href =
                    '{{ route('tareas.buscar', ['filasPorPagina' => $filasPorPagina, 'pagina' => $paginaActual + 1]) }}'">&#x25B6;</button>
            @endif

            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('tareas.buscar', ['filasPorPagina' => $filasPorPagina, 'pagina' => $totalPaginas]) }}'"
                @if ($paginaActual == $totalPaginas) disabled @endif>&#x23ED;</button>
        </div>
    </div> <!-- Fin contenedor-buscar -->

<script>
    window.document.title = 'Gestor de Tareas - Buscar';
</script>

<script>
    window.routes = {
        buscar: '{{ route('tareas.buscar') }}'
    };
</script>

@endsection
