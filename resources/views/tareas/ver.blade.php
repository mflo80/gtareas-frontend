@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="sectores-comentarios">
    <div class="sector-comentario sector-comentario-1 formulario-modificar">
        <div class="titulo-modificar">
            <legend>Tarea #{{ $tarea['id'] }}</legend>
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

        <div class="btn-grupo-ver">
            <button type="button" class="btn btn-primary btn-block btn-large btn-borrar"
                onClick="location.href='modificar-tarea-{{ $tarea['id'] }}'">Modificar</button>
        </div>
    </div> <!-- Fin Clase Formulario Modificar -->

    <div class="sector-comentario sector-comentario-2">
        <span>
            <a class="comentario-titulo">Comentarios</a>
        </span>
        <div class="listaComentarios">
            @foreach($tareaComentarios as $index => $tareaComentario)
                <form id="formComentario{{ $index }}" class="formComentario" method="POST" action="{{ route('comentarios.modificar') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id{{ $index }}" name="id" value="{{ $tareaComentario['id'] }}">
                    <input type="hidden" id="id_usuario{{ $index }}" name="id_usuario" value="{{ $usuarioLogueado['id'] }}">
                    <input type="hidden" id="id_tarea{{ $index }}" name="id_tarea" value="{{ $tarea['id'] }}">
                    <input type="hidden" id="fecha_hora_creacion{{ $index }}" name="fecha_hora_creacion" value="{{ $tareaComentario['fecha_hora_creacion'] }}">
                    <div class="listaComentario">
                        <p id="parrafoComentario{{ $index }}" class="parrafoComentario">{{ $tareaComentario['comentario'] }}</p>
                        <textarea id="comentario{{ $index }}" name="comentario" placeholder="Escribe tu comentario aquí"
                            value="{{ old($tareaComentario['comentario']) }}" style="display: none;" maxlength="500">{{ $tareaComentario['comentario'] }}</textarea>
                    </div>
                    <div class="fechaComentario">
                        <span>Fecha de creación: {{ $tareaComentario['fecha_hora_creacion'] }}</span>
                        <span>Última modificación: {{ $tareaComentario['fecha_hora_modificacion'] }}</span>
                    </div>
                    <div class="footerComentario">
                        <span class="ver-usuario">
                            <img class="icono-usuario" src="{{ asset('/img/usuario-96.png') }}" alt="Ícono de Usuario" />
                            <span>{{ $tareaComentario['nombre_usuario'] }} {{ $tareaComentario['apellido_usuario'] }}</span>
                        </span>
                        <span class="ver-botones">
                            <form method="POST" action="{{ route('comentarios.eliminar', $tareaComentario['id']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-rojo" id="eliminar{{ $index }}" style="display: none;">Eliminar</button>
                            </form>
                            <button type="button" class="btn-gris" id="cancelar{{ $index }}" onclick="cancelarModificacion({{ $index }});" style="display: none;">Cancelar</button>
                            <button type="button" class="btn-gris" id="modificar{{ $index }}" onclick="activarTextarea({{ $index }}),
                                document.getElementById('formComentario{{ $index }}');"
                                {{ $tareaComentario['id_usuario'] != $usuarioLogueado['id'] ? 'disabled' : '' }}>Modificar</button>
                            <button type="submit" class="btn-azul" id="enviar{{ $index }}" style="display: none;">Enviar</button>
                        </span>
                    </div>
                </form>
            @endforeach
        </div>
        <div class="contenedorComentario" id="contenedorComentario">
            <form id="formComentario" class="formComentario" method="POST" action="{{ route('comentarios.crear') }}">
                @csrf
                <input type="hidden" id="id_usuario" name="id_usuario" value="{{ $usuarioLogueado['id'] }}">
                <input type="hidden" id="id_tarea" name="id_tarea" value="{{ $tarea['id'] }}">
                <div class="textoComentario">
                    <textarea id="comentario" name="comentario" placeholder="Escribe tu comentario aquí"></textarea>
                </div>
                <div class="tarea-botones">
                    <button type="button" class="btn-rojo"
                        onclick="document.getElementById('contenedorComentario').style.display='none';
                        document.getElementById('comentario').value='';">Cancelar</button>
                    <button type="submit" id="btn-enviar" class="btn-azul">Enviar</button>
                </div>
            </form>
        </div>
        <div class="tarea-botones">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <span class="botonComentar">
                                <button type="button" class="btn-azul-oscuro"
                                    onclick="activarComentario({{ $index }})">Comentar</button>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>window.document.title = 'Gestor de Tareas - Tarea Comentarios';</script>

<script>
    var usuariosAsignados = @json($usuariosAsignados);
</script>

<script src="{{ asset('js/tareas/ver.js') }}"></script>

@endsection



