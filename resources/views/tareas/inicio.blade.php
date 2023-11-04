@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="sectores">
    <div class="sector sector-1">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)">
            <div>
                <a class="sector-titulo">Análisis</a>
            </div>
            @if (!empty($tareasPorCategoria['Análisis'] ?? []))
                @foreach ($tareasPorCategoria['Análisis'] ?? [] as $tarea)
                    <table draggable="true" ondragstart="drag(event)" id="table-{{ $tarea['id'] }}">
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
                                    <div class="tarea-comentarios">
                                        <img class="icono-comentario" src="{{ asset('/img/comentario-100.png') }}" alt="Ícono de Comentario" />
                                        <span>{{ rand(1, 20) }}</span>
                                    </div>
                                    <div class="tarea-usuarios">
                                        <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                        <span>{{ rand(1, 10) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-botones">
                                    <button class="ver">Ver</button>
                                    <button class="modificar">Modificar</button>
                                    <button class="comentar">Comentar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
    <div class="sector sector-2">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)">
            <div>
                <a class="sector-titulo">Diseño</a>
            </div>
            @if (!empty($tareasPorCategoria['Diseño'] ?? []))
                @foreach ($tareasPorCategoria['Diseño'] ?? [] as $tarea)
                    <table draggable="true" ondragstart="drag(event)" id="table-{{ $tarea['id'] }}">
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
                                    <div class="tarea-comentarios">
                                        <img class="icono-comentario" src="{{ asset('/img/comentario-100.png') }}" alt="Ícono de Comentario" />
                                        <span>{{ rand(1, 20) }}</span>
                                    </div>
                                    <div class="tarea-usuarios">
                                        <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                        <span>{{ rand(1, 10) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-botones">
                                    <button class="ver">Ver</button>
                                    <button class="modificar">Modificar</button>
                                    <button class="comentar">Comentar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
    <div class="sector sector-3">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)">
            <div>
                <a class="sector-titulo">Implementación</a>
            </div>
            @if (!empty($tareasPorCategoria['Implementación'] ?? []))
                @foreach ($tareasPorCategoria['Implementación'] ?? [] as $tarea)
                    <table draggable="true" ondragstart="drag(event)" id="table-{{ $tarea['id'] }}">
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
                                    <div class="tarea-comentarios">
                                        <img class="icono-comentario" src="{{ asset('/img/comentario-100.png') }}" alt="Ícono de Comentario" />
                                        <span>{{ rand(1, 20) }}</span>
                                    </div>
                                    <div class="tarea-usuarios">
                                        <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                        <span>{{ rand(1, 10) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-botones">
                                    <button class="ver">Ver</button>
                                    <button class="modificar">Modificar</button>
                                    <button class="comentar">Comentar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
    <div class="sector sector-4">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)">
            <div>
                <a class="sector-titulo">Verificación</a>
            </div>
            @if (!empty($tareasPorCategoria['Verificación'] ?? []))
                @foreach ($tareasPorCategoria['Verificación'] ?? [] as $tarea)
                    <table draggable="true" ondragstart="drag(event)" id="table-{{ $tarea['id'] }}">
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
                                    <div class="tarea-comentarios">
                                        <img class="icono-comentario" src="{{ asset('/img/comentario-100.png') }}" alt="Ícono de Comentario" />
                                        <span>{{ rand(1, 20) }}</span>
                                    </div>
                                    <div class="tarea-usuarios">
                                        <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                        <span>{{ rand(1, 10) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-botones">
                                    <button class="ver">Ver</button>
                                    <button class="modificar">Modificar</button>
                                    <button class="comentar">Comentar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
    <div class="sector sector-5">
        <div class="tabla" ondragover="allowDrop(event)" ondrop="drop(event)">
            <div>
                <a class="sector-titulo">Mantenimiento</a>
            </div>
            @if (!empty($tareasPorCategoria['Mantenimiento'] ?? []))
                @foreach ($tareasPorCategoria['Mantenimiento'] ?? [] as $tarea)
                    <table draggable="true" ondragstart="drag(event)" id="table-{{ $tarea['id'] }}">
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
                                    <div class="tarea-comentarios">
                                        <img class="icono-comentario" src="{{ asset('/img/comentario-100.png') }}" alt="Ícono de Comentario" />
                                        <span>{{ rand(1, 20) }}</span>
                                    </div>
                                    <div class="tarea-usuarios">
                                        <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                                        <span>{{ rand(1, 10) }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tarea-botones">
                                    <button class="ver">Ver</button>
                                    <button class="modificar">Modificar</button>
                                    <button class="comentar">Comentar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
    window.document.title = 'Gestor de Tareas';
</script>

@endsection

