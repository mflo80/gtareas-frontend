@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="contenedor-crear">
    <div class="formulario-crear">
        <div class="titulo-crear">
            <legend>Crear Tarea</legend>
        </div>

        <form class="formulario-crear-tarea" method="POST" id="crear-tarea" action="{{ route('tareas.crear') }}">
            @csrf
            <div class="formulario-columnas">
                <div class="formulario-izquierda">
                    <div class="titulo-input">
                        <input type="text" id="titulo" name="titulo" placeholder="Título" size="255" value="{{ old('titulo') }}" autofocus />
                    </div>

                    <textarea id="texto" name="texto" placeholder="Ingrese aquí el texto de la tarea..." value="{{ old('texto') }}"></textarea>

                    <div class="fecha">
                        <label for="fecha-inicio">Fecha de inicio:</label>
                        <input type="datetime-local" id="fecha-inicio" name="fecha_hora_inicio" value="{{ old('fecha_hora_inicio') }}">
                    </div>

                    <div class="fecha">
                        <label for="fecha-fin">Fecha de fin:</label>
                        <input type="datetime-local" id="fecha-fin" name="fecha_hora_fin" value="{{ old('fecha_hora_fin') }}">
                    </div>

                    <div class="categoria">
                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria">
                            @php
                                $categorias = explode(',', getenv('CATEGORIAS'));
                            @endphp
                            @foreach ( $categorias as $categoria) {
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
                            }
                            @endforeach
                        </select>
                    </div>

                    <div class="estado">
                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado">
                            @php
                                $estados = explode(',', getenv('ESTADOS'));
                            @endphp
                            @foreach ( $estados as $estado) {
                                <option value="{{ $estado }}">{{ $estado }}</option>
                            }
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="formulario-derecha">
                    <div class="usuarios">
                        <div id="usuarioLogueado" data-id="{{ $usuarioLogueado['id'] }}" data-nombre="{{ $usuarioLogueado['nombre'] }}"
                            data-apellido="{{ $usuarioLogueado['apellido'] }}" style="display: none;"></div>
                        <div class="usuarios-seleccionados" id="usuariosSeleccionados">
                            <label for="buscadorUsuarios">Usuarios:</label>
                            <input type="text" id="buscadorUsuarios" name="buscadorUsuarios" placeholder="Ingrese el nombre del usuario a buscar">
                        </div>
                        <div class="usuarios-todos" id="todosUsuarios">
                            <select id="resultadosBusqueda"></select>
                            <button type="button" class="btn-primary btn-block btn-large btn-agregar" id="agregarUsuario">
                                <img src="{{asset('/img/agregar-usuario.png')}}" alt="Agregar"></button>
                            <div id="todosUsuarios" class="usuarios-seleccionados-lista" style="display: none;">
                                @foreach ($usuarios as $usuarios)
                                    <div class="usuario" data-id="{{ $usuarios['id'] }}">{{ $usuarios['nombre'] }} {{ $usuarios['apellido'] }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="usuarios-seleccionados-lista">
                            <table class="usuarios-seleccionados-tabla">
                                <thead>
                                    <tr>
                                        <th class="tabla-columna-id">ID</th>
                                        <th class="tabla-columna-usuario">Usuario</th>
                                        <th class="tabla-columna-eliminar">Opción</th>
                                    </tr>
                                </thead>
                                <tbody id="usuariosSeleccionadosLista">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="idsUsuarios" name="idsUsuarios">
            </div>

            <div class="btn-grupo">
                <button type="button" class="btn btn-primary btn-block btn-large btn-borrar"
                    onClick="location.href='crear-tarea'">Vaciar</button>
                <button type="button" class="btn btn-primary btn-block btn-large btn-registrar"
                    data-toggle="modal" data-target="#confirmCrearModal">Crear</button>
            </div>
        </form>
    </div> <!-- Fin Clase Formulario Crear -->

    <div class="error-grupo">
        <div class="error-mensaje">
            @foreach ($errors->all() as $message)
                <p id="error">{{ $message }}</p>
            @break
        @endforeach
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmCrearModal" tabindex="-1" role="dialog" aria-labelledby="confirmCrearModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="confirmCrearModalLabel">Confirmar crear tarea</h5>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres crear esta tarea?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <a href="#" class="btn btn-primary" id="confirmCrearButton">Si</a>
            </div>
        </div>
        </div>
    </div>

</div> <!-- Fin Clase Contenedor Crear -->

<script>window.document.title = 'Gestor de Tareas - Crear Tarea';</script>

<script src="{{ asset('js/tareas/crear.js') }}"></script>

@endsection



