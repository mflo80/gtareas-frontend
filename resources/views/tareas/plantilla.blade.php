<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Para que funcione el formulario de cambiar categoría al arrastrar una tarea-->
    <link href="{{ asset('/css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tareas/inicio.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tareas/crear.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tareas/modificar.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tareas/ver.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tareas/buscar.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/historial/tareas.css') }}" rel="stylesheet">
    <script src="{{ asset('js/externos/jQuery.js') }}"></script>
    <script src="{{ asset('js/externos/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/tareas/inicio.js') }}"></script>
    <script src="{{ asset('js/tareas/buscar.js') }}"></script>
    <script src="{{ asset('js/historial/historialTareas.js') }}"></script>
    <title>Gestor de Tareas</title>
</head>

<body>
    <nav>
        <div class="titulo">
            <a>Gestor de Tareas</a>
            <img class="imagen-tareas" src="{{ asset('/img/lista-de-tareas.png') }}" alt="Imagen de una lista con tareas" />
        </div>

        <ul class="menu">
            <li><a href="inicio" class="menu-opcion-1">Inicio</a></li>
            <li><a href="crear-tarea" class="menu-opcion-2">Crear tarea</a></li>
            <li><a href="buscar" class="menu-opcion-3">Buscar</a></li>
            <li class="dropdown">
                <a href="#" class="menu-opcion-4 dropbtn">Historial</a>
                <div class="dropdown-content">
                    <a href="historial-tareas">... Tareas</a>
                    <a href="historial-comentarios">... Comentarios</a>
                </div>
            </li>
            <li><a href="ayuda" class="menu-opcion-5">Ayuda</a></li>
            <li><a href="logout" class="menu-opcion-6">Cerrar Sesión</a></li>
            <div class="usuario">
                <a>({{ $usuarioLogueado['nombre'] }} {{ $usuarioLogueado['apellido'] }})</a>
            </div>
        </ul>
        <a href="#" id="toggle" class="toggle"><img class="menu-img" src="{{ asset('/img/barra-de-menu.png') }}"
                alt="Menú"></a>
    </nav>

    <main>
        @yield('gtareas-inicio')
    </main>

    <footer>
        <div class="footer-texto">
            <p>Gestor de Tareas v1.00 ® by Marcelo Florio (2023)</p>
        </div>
    </footer>
</body>

</html>
