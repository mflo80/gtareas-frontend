@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="sectores">
    @php
        $categorias = explode(',', getenv('CATEGORIAS'));
    @endphp
    @foreach($categorias as $index => $categoria)
        <div class="sector sector-{{ $index+1 }}" data-categoria={{ $categoria }}>
            <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)" data-categoria={{ $categoria }}>
                <span>
                    <a class="sector-titulo">{{ $categoria }}</a>
                </span>
                @forelse ($tareasPorCategoria[ $categoria ] ?? [] as $tarea)
                    <table draggable="true" ondragstart="drag(event)" id="{{ $tarea['id'] }}">
                        <thead>
                            <tr>
                                <th class="tarea-titulo">{{ $tarea['titulo'] }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="tarea-texto">{{ $tarea['texto'] }}</td>
                            </tr>
                            <tr>
                                <td class="tarea-inicia">
                                    <a>Inicia:</a>
                                    <span>{{ $tarea['fecha_hora_inicio'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-finaliza">
                                    <a>Finaliza:</a>
                                    <span style="{{ \Carbon\Carbon::now() > $tarea['fecha_hora_fin'] ? 'color: red;' : '' }}">
                                        {{ $tarea['fecha_hora_fin'] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-datos">
                                    <span class="tarea-id">
                                        <img class="icono-id" src="{{ asset('/img/tarea-id.png') }}" alt="Ícono de Identificación de Tarea" />
                                        <span>{{ $tarea['id'] }}</span>
                                    </span>
                                    <span class="tarea-comentarios">
                                        <img class="icono-comentario" src="{{ asset('/img/comentario-100.png') }}" alt="Ícono de Comentario" />
                                        <span>{{ $tarea['cantidad_comentarios'] }}</span>
                                    </span>
                                    <span class="tarea-usuarios">
                                        <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                        <span>{{ $tarea['usuarios_asignados'] }}</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-botones">
                                    <button id="botonVer-{{ $tarea['id'] }}" class="btn-azul"
                                        data-url="{{ route('tareas.ver', $tarea['id']) }}">Ver</button>
                                    <button id="botonModificar-{{ $tarea['id'] }}" class="btn-rojo"
                                        data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                                    <button id="botonComentar-{{ $tarea['id'] }}" class="btn-gris"
                                        data-url="{{ route('tareas.ver', $tarea['id']) }}">Comentar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @empty
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalConfirmarCambioCategoria">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmar cambio de categoría</h3>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres cambiar la categoría de esta tarea?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCancelarCambioCategoria" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarCambioCategoria">Sí, cambiar categoría</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.document.title = 'Gestor de Tareas';
</script>

@endsection

