@extends('auth.plantilla')

@section('gtareas-auth')

    <div class="formulario">
        <img class="logo" src="{{ asset('/img/logo.png') }}" alt="LOGO" />

        <form action="login" method="post">
            @csrf

            <input id="email" type="email" name="email" placeholder="Correo Electrónico" size="255" autocomplete="email"
                value="{{ old('email') }}" autofocus />

            <input id="password" type="password" name="password" placeholder="Contraseña" size="255" />

            <div class="btn-grupo">
                <button type="submit" class="btn btn-primary btn-block btn-large">Iniciar Sesión</button>
            </div>
        </form>

        <div class="texto-grupo">
            <p>¿No tienes cuenta en Gestor de Tareas?
                <a href="registro" class="registro-link">Registrar</a>
            </p>

            <p>¿Olvidastes la contraseña?
                <a href="restablecer" class="restablecer-link">Restablecer contraseña</a>
            </p>

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
        window.document.title = 'Gestor de Tareas - Iniciar Sesión';
    </script>

@endsection
