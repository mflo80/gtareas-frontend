@extends('tareas.plantilla')

@section('gtareas-inicio')

    <div class="paginas-subtitulo">
        <h2>Acerca</h2>
    </div>

    <div class="acerca-contenido">
        <div class="acerca-usuario">
            <p>
                Estimado/a usuario/a:
            </p>
        </div>
        <div class="acerca-texto-uno">
            <p>
                Gestor de Tareas es una aplicación web desarrollada por Marcelo Florio (mflo80@gmail.com) en el año 2023, como parte de
                un trabajo práctico para la materia Programación II de la Tecnicatura Software y Redes realizada en el Instituto Superior
                Brazo Oriental (ISBO).
            </p>
        </div>
        <div class="acerca-texto-dos">
            <p>
                La aplicación permite gestionar tareas, permitiendo crear, editar, eliminar y marcar como finalizadas las tareas. Al crear
                una tarea, se puede asignar uno o más usuarios a la misma, y cada usuario puede ver las tareas asignadas a él. A cada uno
                de ellos se le envía un correo electrónico cuando se le asigna una tarea.
            </p>
            <p>
                En esta versión, se agregó la posibilidad de crear, modificar y eliminar comentarios en cada tarea, por parte de los
                usuarios asignados a la tarea.
            </p>
            <p>
                También cuenta con la posibilidad de buscar todas las tareas, y en mostrar un historial con las modificaciones realizadas tanto
                en las tareas como en los comentarios realizados.
            </p>
            <p>
                La aplicación fue desarrollada utilizando el framework Laravel 10, y el motor de base de datos MySQL 8, en un entorno de desarrollo
                utilizando contenedores Docker.
            </p>
            <p>
                Gracias por utilizar la aplicación. Saluda atte. Marcelo Florio.
            </p>
        </div>
        <div class="acerca-logo">
             <img src="{{ asset('img/logo.png') }}" alt="Logo" class="acerca-logo">
        </div>
    </div>

<script>
    window.document.title = 'Gestor de Tareas - Acerca';
</script>

@endsection
