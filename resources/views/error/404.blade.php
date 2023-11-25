@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="error-404-imagen">
        <img class="error-404-img" src="{{ asset('/img/error-404.png') }}" alt="Error">
    </div>

    <div class="error-subtitulo">
        <a>Página no encontrada</a>
    </div>

    <div class="error-texto">
        <p>Lo sentimos, la página que estás buscando no se pudo encontrar.</p>
    </div>

    <div class="error-grupo-404">
        <div class="error-mensaje-404">
            @foreach ($errors->all() as $message)
                <p id="error">{{ $message }}</p>
            @break
        @endforeach
    </div>

<script>
    window.document.title = 'Gestor de Tareas - Error';
</script>

@endsection
