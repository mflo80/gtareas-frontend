@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="sectores-comentarios">
    <div class="sector-comentario sector-comentario-1 formulario-ver">
        <div class="titulo-ver">
            <legend>Tarea #{{ $tarea['id'] }}</legend>
        </div>

        <form method="GET" action="{{ route('tareas.modificar', $tarea['id']) }}" id="formulario">
            @csrf

            <input type="hidden" id="id" name="id" value="{{ $tarea['id'] }}">

            <div class="titulo-input">
                <input type="text" id="titulo" name="titulo" placeholder='Titulo' size="255" value="{{ $tarea['titulo'] }}" autofocus/>
            </div>

            <textarea id="texto" name="texto" placeholder="Ingrese aquí el texto de la tarea...">{{ $tarea['texto'] }}</textarea>

            <div class="fecha">
                <label for="fecha-inicio">Fecha de inicio:</label>
                <input type="datetime-local" id="fecha-inicio" name="fecha_hora_inicio" value="{{ date('Y-m-d\TH:i', strtotime($tarea['fecha_hora_inicio'])) }}">
            </div>

            <div class="fecha">
                <label for="fecha-fin">Fecha de fin:</label>
                <input type="datetime-local" id="fecha-fin" name="fecha_hora_fin" value="{{ date('Y-m-d\TH:i', strtotime($tarea['fecha_hora_fin'])) }}">
            </div>

            <div class="categoria">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria">
                    <option value="Análisis" {{ $tarea['categoria'] == 'Análisis' ? 'selected' : '' }}>Análisis</option>
                    <option value="Diseño" {{ $tarea['categoria'] == 'Diseño' ? 'selected' : '' }}>Diseño</option>
                    <option value="Implementación" {{ $tarea['categoria'] == 'Implementación' ? 'selected' : '' }}>Implementación</option>
                    <option value="Verificación" {{ $tarea['categoria'] == 'Verificación' ? 'selected' : '' }}>Verificación</option>
                    <option value="Mantenimiento" {{ $tarea['categoria'] == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
            </div>

            <div class="categoria">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="Activa" {{ $tarea['estado'] == 'Activa' ? 'selected' : '' }}>Activa</option>
                    <option value="Atrasada" {{ $tarea['estado'] == 'Atrasada' ? 'selected' : '' }}>Atrasada</option>
                    <option value="Cancelada" {{ $tarea['estado'] == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                    <option value="En espera" {{ $tarea['estado'] == 'En espera' ? 'selected' : '' }}>En espera</option>
                    <option value="Finalizada" {{ $tarea['estado'] == 'Finalizada' ? 'selected' : '' }}>Finalizada</option>
                </select>
            </div>

            <div class="btn-grupo">
                <button id="botonModificar-{{ $tarea['id'] }}" class="btn btn-primary btn-block btn-large btn-registrar"
                    data-toggle="modal" data-target="#confirmModificarModal">Modificar</button>
            </div>
        </form>
    </div> <!-- Fin Clase Formulario Crear -->

    <div class="sector-comentario sector-comentario-2">
        <span>
            <a class="comentario-titulo">Comentarios</a>
        </span>
        <table>
            <thead>
                <tr>
                    <th class="tarea-titulo">{{ $tarea['texto'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tarea-inicia">
                        <a>Fecha comentario:</a>
                        <span>{{ $tarea['fecha_hora_inicio'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-finaliza">
                        <a>Última modificación:</a>
                        <span>{{ $tarea['fecha_hora_fin'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-datos">
                        <span class="tarea-id">
                            <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                            <span>Usuario</span>
                        </span>
                        <span class="tarea-botones">
                            <button id="botonModificar-{{ $tarea['id'] }}" class="comentar"
                                data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th class="tarea-titulo">{{ $tarea['texto'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tarea-inicia">
                        <a>Fecha comentario:</a>
                        <span>{{ $tarea['fecha_hora_inicio'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-finaliza">
                        <a>Última modificación:</a>
                        <span>{{ $tarea['fecha_hora_fin'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-datos">
                        <span class="tarea-id">
                            <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                            <span>Usuario</span>
                        </span>
                        <span class="tarea-botones">
                            <button id="botonModificar-{{ $tarea['id'] }}" class="comentar"
                                data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th class="tarea-titulo">{{ $tarea['texto'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tarea-inicia">
                        <a>Fecha comentario:</a>
                        <span>{{ $tarea['fecha_hora_inicio'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-finaliza">
                        <a>Última modificación:</a>
                        <span>{{ $tarea['fecha_hora_fin'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-datos">
                        <span class="tarea-id">
                            <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                            <span>Usuario</span>
                        </span>
                        <span class="tarea-botones">
                            <button id="botonModificar-{{ $tarea['id'] }}" class="comentar"
                                data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th class="tarea-titulo">{{ $tarea['texto'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tarea-inicia">
                        <a>Fecha comentario:</a>
                        <span>{{ $tarea['fecha_hora_inicio'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-finaliza">
                        <a>Última modificación:</a>
                        <span>{{ $tarea['fecha_hora_fin'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="tarea-datos">
                        <span class="tarea-id">
                            <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                            <span>Usuario</span>
                        </span>
                        <span class="tarea-botones">
                            <button id="botonModificar-{{ $tarea['id'] }}" class="comentar"
                                data-url="{{ route('tareas.modificar', $tarea['id']) }}">Modificar</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="botones-comentarios">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <span class="tarea-botones">
                                <button type="button" class="ver"
                                    data-toggle="modal" data-target="#comentarModal">Comentar</button>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="comentarModal" tabindex="-1" role="dialog" aria-labelledby="comentarModalLabel" aria-hidden="true">
    <form id="form-comentario">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="comentarModalLabel">Comentar Tarea #{{ $tarea['id'] }}</h5>
                </div>
                <div class="modal-body">
                    <textarea id="contenido" placeholder="Escribe tu comentario aquí"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="confirmComentarButton">Enviar comentario</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>window.document.title = 'Gestor de Tareas - Tarea Comentarios';</script>

<script src="{{ asset('js/tareas/ver.js') }}"></script>

@endsection



