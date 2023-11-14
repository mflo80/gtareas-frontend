@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="contenedor-modificar">
    <div class="formulario-modificar">
        <div class="titulo-modificar">
            <legend>Modificar Tarea #{{ $tarea['id'] }}</legend>
        </div>

        <form method="POST" action="{{ route('tareas.modificar', $tarea['id']) }}" id="formulario">
            @csrf
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
                        <option value={{ $categoria }} {{ $tarea['categoria'] == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                    }
                    @endforeach
             </select>
            </div>

            <div class="categoria">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    @php
                        $estados = explode(',', getenv('ESTADOS'));
                    @endphp
                    @foreach ( $estados as $estado) {
                        <option value={{ $estado }} {{ $tarea['estado'] == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    }
                    @endforeach
                </select>
            </div>

            <div class="btn-grupo-modificar">
                <button type="button" class="btn btn-primary btn-block btn-large btn-borrar"
                    onClick="location.href='modificar-tarea-{{ $tarea['id'] }}'">Restablecer</button>
                <button type="button" class="btn btn-primary btn-block btn-large btn-registrar"
                    data-toggle="modal" data-target="#confirmModificarModal">Modificar</button>
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
        <div class="btn-grupo-eliminar">
            <button type="button" class="btn btn-primary btn-block btn-large btn-eliminar btn-eliminar-primary"
                data-toggle="modal" data-target="#confirmDeleteModal">Eliminar</button>
        </div>
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

    <div class="error-grupo-modificar">
        <div class="error-mensaje">
            @foreach ($errors->all() as $message)
                <p id="error">{{ $message }}</p>
            @break
        @endforeach
    </div>

</div> <!-- Fin Clase Contenedor Crear -->

<script>window.document.title = 'Gestor de Tareas - Modificar Tarea';</script>

<script src="{{ asset('js/tareas/modificar.js') }}"></script>

@endsection



