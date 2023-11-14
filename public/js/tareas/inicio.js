// Arrastar y soltar tareas entre los sectores
function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text/plain", event.target.id);
}

function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text/plain");
    var table = document.getElementById(data);
    var target = event.target;
    while (target && target.className.indexOf('tabla') === -1) {
        target = target.parentNode;
    }
    if (target) {
        var parent = table.parentNode;
        if (parent !== target) {
            target.appendChild(table);
            if (table.parentNode === parent) {
                parent.removeChild(table);
            }

            var categoria = target.getAttribute('data-categoria');

            // Mostrar el modal de confirmación
            jQuery("#modalConfirmarCambioCategoria").modal("show");

            // Cuando se hace clic en el botón de cancelación, recargar la página
            jQuery("#btnCancelarCambioCategoria").on("click", function() {
                jQuery("#modalConfirmarCambioCategoria").modal("hide");
                location.reload();
            });

            jQuery("#btnConfirmarCambioCategoria").on("click", function() {
                updateCategoria(data, categoria);
                jQuery("#modalConfirmarCambioCategoria").modal("hide");
            });
        }
    }
}

function updateCategoria(tareaId, categoria) {
    $.ajax({
        url: 'categoria-tarea-' + tareaId,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            _method: 'PUT',
            categoria: categoria
        },
        success: function(response) {
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
}

// Botón para modificar las tareas

$(document).ready(function(){
    $("[id^='botonModificar']").click(function(){
        window.location.href = $(this).data('url');
    });
});

$(document).ready(function(){
    $("[id^='botonVer']").click(function(){
        window.location.href = $(this).data('url');
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.querySelector('.toggle');
    const menu = document.querySelector('.menu');


    toggle.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
});
