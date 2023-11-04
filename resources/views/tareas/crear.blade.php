@extends('tareas.plantilla')

@section('gtareas-inicio')

<div class="contenedor-crear">
    <div class="formulario-crear">
        <div class="titulo-crear">
            <legend>Crear Tarea</legend>
        </div>

        <form id="crear-tarea" action="crear-tarea" method="post">
            @csrf

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
                    <option value="Análisis">Análisis</option>
                    <option value="Diseño">Diseño</option>
                    <option value="Implementación">Implementación</option>
                    <option value="Verificación">Verificación</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                </select>
            </div>

            <div class="categoria">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="Activa">Activa</option>
                    <option value="Atrasada">Atrasada</option>
                    <option value="Cancelada">Cancelada</option>
                    <option value="En espera">En espera</option>
                    <option value="Finalizada">Finalizada</option>
                </select>
            </div>

            <div class="btn-grupo">
                <button type="button" class="btn btn-primary btn-block btn-large btn-borrar"
                    onClick="location.href='crear-tarea'">Borrar</button>
                <button type="submit" class="btn btn-primary btn-block btn-large btn-registrar">Crear</button>
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

</div> <!-- Fin Clase Contenedor Crear -->

<script>window.document.title = 'Gestor de Tareas - Crear Tarea';</script>

<script src="{{ asset('js/tareas/crear.js') }}"></script>

@endsection



