@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="contenedor-buscar">
        <div class="paginas-subtitulo">
            <h2>Buscar</h2>
        </div>

        <div class="formulario-buscar-contenedor">
            <form id="paginationForm" method="GET">
                <div class="formulario-buscar-paginado">
                    <label class="formulario-buscar-titulo" for="estado">Cantidad de filas a mostrar:</label>
                    <select id="rowsPerPage" name="filasPorPagina">
                        <option value="8" @if ($filasPorPagina == 8) selected @endif>8</option>
                        <option value="15" @if ($filasPorPagina == 15) selected @endif>15</option>
                        <option value="30" @if ($filasPorPagina == 30) selected @endif>30</option>
                        <option value="50" @if ($filasPorPagina == 50) selected @endif>50</option>
                        <option value="100" @if ($filasPorPagina == 100) selected @endif>100</option>
                        <option value="250" @if ($filasPorPagina == 250) selected @endif>250</option>
                    </select>
                </div>
                <div class="formulario-buscar-orden">
                    <label class="formulario-buscar-titulo" for="orden">Ordenar por ID:</label>
                    <select id="ordenTareas" name="ordenTareas">
                        <option value="asc" @if ($ordenTareas == 'asc') selected @endif>Ascendente</option>
                        <option value="desc" @if ($ordenTareas == 'desc') selected @endif>Descendente</option>
                    </select>
                </div>
            </form>
        </div>

        <table class="tabla-buscar">
            <thead class="tabla-buscar-titulos">
                <tr>
                    <th class="columna-buscar-id">Id</th>
                    <th class="columna-buscar-titulo">Título</th>
                    <th class="columna-buscar-texto">Texto</th>
                    <th class="columna-buscar-fecha-cracion">Fecha Creación</th>
                    <th class="columna-buscar-fecha-inicio">Fecha Inicio</th>
                    <th class="columna-buscar-fecha-fin">Fecha Fin</th>
                    <th class="columna-buscar-categoria">Categoría</th>
                    <th class="columna-buscar-estado">Estado</th>
                    <th class="columna-buscar-usuario">Usuario Creador</th>
                    <th class="columna-buscar-opciones">Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-buscar-body">
                @foreach ($tareas as $tarea)
                    <tr>
                        <td class="celda-buscar-id">{{ $tarea['id'] }}</td>
                        <td class="celda-buscar-titulo">{{ $tarea['titulo'] }}</td>
                        <td class="celda-buscar-texto">{{ $tarea['texto'] }}</td>
                        <td class="celda-buscar-fecha-creacion">{{ (new DateTime($tarea['fecha_hora_creacion']))->format('Y-m-d') }}</td>
                        <td class="celda-buscar-fecha-inicio">{{ (new DateTime($tarea['fecha_hora_inicio']))->format('Y-m-d') }}</td>
                        <td class="celda-buscar-fecha-fin">{{ (new DateTime($tarea['fecha_hora_fin']))->format('Y-m-d') }}</td>
                        <td class="celda-buscar-categoria">{{ $tarea['categoria'] }}</td>
                        <td class="celda-buscar-estado">{{ $tarea['estado'] }}</td>
                        <td class="celda-buscar-usuario">{{ $tarea['creador_nombre'] }} {{ $tarea['creador_apellido'] }}</td>
                        <td class="celda-buscar-opciones">
                            @if(array_key_exists('tarea_asignada', $tarea) && $tarea['tarea_asignada'] == 1)
                                <a href="{{ route('tareas.ver', ['id' => $tarea['id']]) }}">Ver</a>
                                <a href="{{ route('tareas.modificar', ['id' => $tarea['id']]) }}">Modificar</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="paginacion">
            <button class="btn-paginacion" onclick="window.location.href =
                '{{ route('tareas.buscar', ['filasPorPagina' => $filasPorPagina, 'pagina' => 1]) }}'"
                @if ($paginaActual == 1) disabled @endif>&#x25C0;&#x25C0;</button>

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
                @if ($paginaActual == $totalPaginas) disabled @endif>&#x25B6;&#x25B6;</button>
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

<script src="{{ asset('js/tareas/buscar.js') }}"></script>

@endsection
