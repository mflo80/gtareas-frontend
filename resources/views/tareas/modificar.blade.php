@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="contenedor-modificar">
    <div class="formulario-modificar">
        <div class="titulo-modificar">
            <legend>Modificar Tarea #{{ $tarea['id'] }}</legend>
        </div>

        <form class="formulario-modificar-tarea" method="POST" action="{{ route('tareas.modificar', $tarea['id']) }}" id="formulario">
            @csrf
            <div class="formulario-columnas">
                <div class="formulario-izquierda">
                    @method('PUT')
                    <input type="hidden" id="id" name="id" value="{{ $tarea['id'] }}">

                    <div class="titulo-input">
                        <input type="text" id="titulo" name="titulo" placeholder='Titulo' size="255" value="{{ $tarea['titulo'] }}" autofocus />
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
                            @php
                                $categorias = explode(',', getenv('CATEGORIAS'));
                            @endphp
                            @foreach ( $categorias as $categoria) {
                                <option value="{{ $categoria }}" {{ $tarea['categoria'] == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
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
                                <option value="{{ $estado }}" {{ $tarea['estado'] == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                            }
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="formulario-derecha">
                    <div class="usuarios">
                        <div id="usuarioCreador" data-id="{{ $usuarioCreador['id'] }}" data-nombre="{{ $usuarioCreador['nombre'] }}"
                            data-apellido="{{ $usuarioCreador['apellido'] }}" style="display: none;"></div>
                        <div class="usuarios-seleccionados" id="usuariosSeleccionados">
                            <label for="buscadorUsuarios">Usuarios:</label>
                            <input type="text" id="buscadorUsuarios" name="buscadorUsuarios" placeholder="Ingrese el nombre del usuario a buscar">
                        </div>
                        <div class="usuarios-todos" id="todosUsuarios">
                            <select id="resultadosBusqueda"></select>
                            <button type="button" class="btn-primary btn-block btn-large btn-agregar" id="agregarUsuario">
                                <img src="{{asset('/img/agregar-usuario.png')}}" alt="Agregar"></button>
                            <div id="todosUsuarios" class="usuarios-seleccionados-lista" style="display: none;">
                                @foreach ($usuarios as $usuariosCreador)
                                    <div class="usuario" data-id="{{ $usuariosCreador['id'] }}">{{ $usuariosCreador['nombre'] }} {{ $usuariosCreador['apellido'] }}</div>
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
                <input type="hidden" id="idUsuarioCreador" name="idUsuarioCreador" value="{{ $usuarioCreador['id'] }}" >
            </div>
        </form>
    </div> <!-- Fin Clase Formulario Modificar -->

    <!-- Modal Modificar -->
    <div class="modal fade" id="confirmModificarModal" tabindex="-1" role="dialog" aria-labelledby="confirmModificarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="confirmModificarModalLabel">Confirmar modificación</h5>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres modificar esta tarea?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <a href="#" class="btn btn-primary" id="confirmModificarButton">Si</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario Eliminar -->
    <form id="deleteForm" action="{{ url('eliminar-tarea-' . $tarea['id']) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Eliminar-->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar esta tarea?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <a href="#" class="btn btn-primary" id="confirmDeleteButton">Si</a>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-grupo-modificar">
        <button type="button" class="btn btn-primary btn-block btn-large btn-eliminar btn-eliminar-primary"
            data-toggle="modal" data-target="#confirmDeleteModal">Eliminar</button>
        <button type="button" class="btn btn-primary btn-block btn-large btn-borrar"
            onClick="location.href='modificar-tarea-{{ $tarea['id'] }}'">Restablecer</button>
        <button type="button" class="btn btn-primary btn-block btn-large btn-registrar"
            data-toggle="modal" data-target="#confirmModificarModal">Modificar</button>
    </div>

    <div class="error-grupo-modificar">
        <div class="error-mensaje">
            @foreach ($errors->all() as $message)
                <p id="error">{{ $message }}</p>
            @break
        @endforeach
    </div>

</div> <!-- Fin Clase Contenedor Modificar -->

<script>window.document.title = 'Gestor de Tareas - Modificar Tarea';</script>

<script>
    var usuariosAsignados = @json($usuariosAsignados);
</script>

<script src="{{ asset('js/tareas/modificar.js') }}"></script>

@endsection



