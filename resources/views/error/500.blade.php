@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="error-500-imagen">
        <img class="error-500-img" src="{{ asset('/img/error-500.png') }}" alt="Error">
    </div>

    <div class="error-subtitulo">
        <a>Upsss!</a>
    </div>

    <div class="error-texto">
        <p>Lo sentimos, estamos trabajando para solucionar el problema.</p>
    </div>

    <div class="error-grupo">
        <div class="error-mensaje">
            @foreach ($errors->all() as $message)
                <p id="error">{{ $message }}</p>
            @break
        @endforeach
    </div>

<script>
    window.document.title = 'Gestor de Tareas - Error';
</script>

@endsection
