var inputs = document.querySelectorAll('#formulario input, #formulario textarea, #formulario select');

// Recorre cada elemento y añade el atributo 'disabled'
for (var i = 0; i < inputs.length; i++) {
    inputs[i].disabled = true;
}

$(document).ready(function() {
    $('#confirmComentarButton').on('click', function() {
        $('#form-comentario').submit();
    });
});
