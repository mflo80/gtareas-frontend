@extends('auth.plantilla')

@section('gtareas-auth')

    <div class="formulario">
        <img class="logo" src="{{ asset('/img/logo.png') }}" alt="LOGO" />
        <form name="restablecer" action="restablecer" method="post">
            @csrf

            <input type="email" name="email" placeholder="Correo Electrónico" size="60" value="{{ old('email') }}" />
            <input type="hidden" name="token" value="{{ csrf_token() }}" />

            <div class="btn-grupo">
                <input type="button" name="Cancelar" value="Cancelar" class="btn btn-primary btn-block btn-large btn-cancelar"
                        onClick="window.location.href='login';">
                <button type="submit" class="btn btn-primary btn-block btn-large btn-registrar">Enviar</button>
            </div>
        </form>

        <div class="texto-grupo">
            <p class="texto-mensaje">Para restablecer su contraseña, ingrese su correo electrónico</p>
            <p class="texto-mensaje">y seleccione enviar, para que le lleguen a éste las instrucciones.</p>

            <p class="registrar">¿No tienes cuenta en Gestor de Tareas?
                <a href="registro" class="registro">Registrar</a>
            </p>

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
        window.document.title = 'Gestor de Tareas - Restablecer Contraseña';
    </script>

@endsection

