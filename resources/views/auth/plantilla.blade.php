<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/auth/index.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/auth/login.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/auth/registro.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/auth/restablecer.css') }}" rel="stylesheet">
    <script src="{{ asset('js/externos/jQuery.js') }}"></script>
    <script src="{{ asset('js/auth/plantilla.js') }}"></script>
    <title>Gestor de Tareas</title>
</head>

<body>
    <main>
        @yield('gtareas-auth')
    </main>

    <footer>
        <div class="footer-texto">
            <p>Gestor de Tareas v1.00 ® by Marcelo Florio (2023)</p>
        </div>
    </footer>
</body>
</html>
