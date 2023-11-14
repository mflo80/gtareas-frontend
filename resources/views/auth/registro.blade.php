@extends('auth.plantilla')

@section('gtareas-auth')

    <div class="formulario">
        <img class="logo" src="{{ asset('/img/logo.png') }}" alt="LOGO" />
        <form name="registro" action="registro" method="post">
            @csrf

            <input type="text" id="nombre" name="nombre" placeholder="Nombre" size="255" value="{{ old('nombre') }}" autofocus />

            <input type="text" id="apellido" name="apellido" placeholder="Apellido" size="255" value="{{ old('apellido') }}" />

            <input type="email" id="email" name="email" placeholder="Correo Electrónico" size="255" value="{{ old('email') }}" />

            <input type="password" id="password" name="password" placeholder="Contraseña" size="255" min="6" />

            <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirmar Contraseña" size="255" min="6" />

            <div class="btn-grupo">
                <button type="button" class="btn btn-primary btn-block btn-large btn-borrar"
                    onClick="location.href='registro'">Borrar</button>
                <button type="submit" class="btn btn-primary btn-block btn-large btn-registrar">Registrar</button>
            </div>
        </form>

        <div class="texto-grupo">
            <p class="texto-mensaje">Una vez realizado el registro, deberá de confirmar su cuenta de</p>
            <p class="texto-mensaje">correo electrónico, ingresando a este y siguiendo los pasos en</p>
            <p class="texto-mensaje">el mensaje que le será enviado.</p>

            <div class="login-link">
                <p>¿Ya tienes cuenta?
                    <a href="login">Iniciar Sesión</a>
                </p>
            </div>

            <div class="error-grupo">
                <div class="error-mensaje">
                    @foreach ($errors->all() as $message)
                        <p id="error">{{ $message }}</p>
                    @break
                @endforeach
            </div>
        </div> <!-- Fin Clase Grupo Texto -->
    </div> <!-- Fin Clase Formulario -->

    <script>
        window.document.title = 'Gestor de Tareas - Registro';
    </script>

@endsection
