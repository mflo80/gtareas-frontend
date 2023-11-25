@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="formulario-modificar-password">
        <div class="titulo-modificar-password">
            <legend>Modificar contraseña</legend>
        </div>
        <form name="modificar-password" action="modificar-password" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="nombre" value="{{ $usuarioLogueado['nombre'] }}" />
            <input type="hidden" name="apellido" value="{{ $usuarioLogueado['apellido'] }}" />
            <input type="hidden" name="email" value="{{ $usuarioLogueado['email'] }}" />

            <input type="password" id="password" name="password" placeholder="Nueva Contraseña" size="255" min="6" />

            <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirmar Contraseña" size="255" min="6" />

            <div class="btn-modificar-password">
                <button type="submit" class="btn btn-primary btn-block btn-large btn-registrar">Aceptar</button>
            </div>
        </form>

        <div class="texto-grupo-modificar-password">
            <p class="texto-modificar-password">Ingrese la nueva contraseña, para realizar el cambio.</p>

            <div class="error-grupo-modificar-password">
                <div class="error-mensaje-modificar-password">
                    @foreach ($errors->all() as $message)
                        <p id="error">{{ $message }}</p>
                    @break
                @endforeach
            </div>
        </div> <!-- Fin Clase Grupo Texto -->
    </div> <!-- Fin Clase Formulario -->

    <script>
        window.document.title = 'Gestor de Tareas - Modificar Contraseña';
    </script>

@endsection
