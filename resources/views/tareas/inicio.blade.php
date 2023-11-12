@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="sectores">
    <div class="sector sector-1" data-categoria="Análisis">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)" data-categoria="Análisis">
            <span>
                <a class="sector-titulo">Análisis</a>
            </span>
            @forelse ($tareasPorCategoria['Análisis'] ?? [] as $tarea)
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
                                <span>{{ $tarea['fecha_hora_fin'] }}</span>
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
                                    <span>{{ rand(1, 20) }}</span>
                                </span>
                                <span class="tarea-usuarios">
                                    <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                    <span>{{ rand(1, 10) }}</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tarea-botones">
                                <button id="botonVer-{{ $tarea['id'] }}" class="ver"
                                    data-url="{{ route('tareas.ver', $tarea['id']) }}">Ver</button>
                                <button id="botonModificar-{{ $tarea['id'] }}" class="modificar"
                                    data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                                <button class="comentar">Comentar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
            @endforelse
        </div>
    </div>
    <div class="sector sector-2" data-categoria="Diseño">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)" data-categoria="Diseño">
            <span>
                <a class="sector-titulo">Diseño</a>
            </span>
            @forelse ($tareasPorCategoria['Diseño'] ?? [] as $tarea)
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
                                <span>{{ $tarea['fecha_hora_fin'] }}</span>
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
                                    <span>{{ rand(1, 20) }}</span>
                                </span>
                                <span class="tarea-usuarios">
                                    <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                    <span>{{ rand(1, 10) }}</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tarea-botones">
                                <button id="botonVer-{{ $tarea['id'] }}" class="ver"
                                    data-url="{{ route('tareas.ver', $tarea['id']) }}">Ver</button>
                                <button id="botonModificar-{{ $tarea['id'] }}" class="modificar"
                                    data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                                <button class="comentar">Comentar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
            @endforelse
        </div>
    </div>
    <div class="sector sector-3" data-categoria="Implementación">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)" data-categoria="Implementación">
            <span>
                <a class="sector-titulo">Implementación</a>
            </span>
            @forelse ($tareasPorCategoria['Implementación'] ?? [] as $tarea)
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
                                <span>{{ $tarea['fecha_hora_fin'] }}</span>
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
                                    <span>{{ rand(1, 20) }}</span>
                                </span>
                                <span class="tarea-usuarios">
                                    <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                    <span>{{ rand(1, 10) }}</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tarea-botones">
                                <button id="botonVer-{{ $tarea['id'] }}" class="ver"
                                    data-url="{{ route('tareas.ver', $tarea['id']) }}">Ver</button>
                                <button id="botonModificar-{{ $tarea['id'] }}" class="modificar"
                                    data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                                <button class="comentar">Comentar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
            @endforelse
        </div>
    </div>
    <div class="sector sector-4" data-categoria="Verificación">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)" data-categoria="Verificación">
            <span>
                <a class="sector-titulo">Verificación</a>
            </span>
            @forelse ($tareasPorCategoria['Verificación'] ?? [] as $tarea)
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
                                <span>{{ $tarea['fecha_hora_fin'] }}</span>
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
                                    <span>{{ rand(1, 20) }}</span>
                                </span>
                                <span class="tarea-usuarios">
                                    <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                    <span>{{ rand(1, 10) }}</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tarea-botones">
                                <button id="botonVer-{{ $tarea['id'] }}" class="ver"
                                    data-url="{{ route('tareas.ver', $tarea['id']) }}">Ver</button>
                                <button id="botonModificar-{{ $tarea['id'] }}" class="modificar"
                                    data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                                <button class="comentar">Comentar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
            @endforelse
        </div>
    </div>
    <div class="sector sector-5" data-categoria="Mantenimiento">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)" data-categoria="Mantenimiento">
            <span>
                <a class="sector-titulo">Mantenimiento</a>
            </span>
            @forelse ($tareasPorCategoria['Mantenimiento'] ?? [] as $tarea)
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
                                <span>{{ $tarea['fecha_hora_fin'] }}</span>
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
                                    <span>{{ rand(1, 20) }}</span>
                                </span>
                                <span class="tarea-usuarios">
                                    <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                    <span>{{ rand(1, 10) }}</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tarea-botones">
                                <button id="botonVer-{{ $tarea['id'] }}" class="ver"
                                    data-url="{{ route('tareas.ver', $tarea['id']) }}">Ver</button>
                                <button id="botonModificar-{{ $tarea['id'] }}" class="modificar"
                                    data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                                <button class="comentar">Comentar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
            @endforelse
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalConfirmarCambioCategoria">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Confirmar cambio de categoría</h2>
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

