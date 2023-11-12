@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="error-imagen">
        <img class="error-404-img" src="{{ asset('/img/error-404.png') }}" alt="Error">
    </div>

    <div class="error-subtitulo">
        <a>Página no encontrada</a>
    </div>

    <div class="error-texto">
        <p>Lo sentimos, la página que estás buscando no se pudo encontrar.</p>
    </div>

<script>
    window.document.title = 'Gestor de Tareas - Error';
</script>

@endsection
