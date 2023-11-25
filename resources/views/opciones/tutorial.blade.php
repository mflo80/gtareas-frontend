@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="paginas-subtitulo">
        <h2>Tutorial</h2>
    </div>

    <div class="acerca-contenido">
        <div class="acerca-texto-uno">
            <p>En esta sección se mostrará la ayuda para el uso de la aplicación.
               Estamos trabajando en la documentación de la misma, por lo que en breve estará disponible en un formato más completo.
            </p>
        </div>

        <div class="acerca-texto-dos">
            <p>Para cualquier duda o sugerencia, puede ponerse en contacto con el administrador de la aplicación.</p>
        </div>

        <div class="tutorial-cambiar-categoria">
            <h3>1) Cómo cambiar la categoría de una tarea desde la página de inicio:</h3>
            <video controls>
                <source src="{{ asset('media/cambiar-categoria-1.mp4') }}" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>
        </div>

        <div class="acerca-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="acerca-logo">
       </div>
    </div>

<script>
    window.document.title = 'Gestor de Tareas - Ayuda';
</script>

@endsection
