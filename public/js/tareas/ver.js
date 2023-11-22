// Buscar usuarios

var usuariosAgregados = [];
var usuarioCreadorElement = document.getElementById('usuarioCreador');
var usuarioCreador = usuarioCreadorElement.dataset.nombre + ' ' + usuarioCreadorElement.dataset.apellido;
var usuarioCreadorId = usuarioCreadorElement.dataset.id;

document.getElementById('buscadorUsuarios').addEventListener('input', function(e) {
    var consulta = e.target.value.toLowerCase();
    var todosUsuarios = document.querySelectorAll('#todosUsuarios .usuario');
    var resultadosBusqueda = document.getElementById('resultadosBusqueda');
    resultadosBusqueda.innerHTML = '';

    todosUsuarios.forEach(function(usuario) {
        if (usuario.textContent.toLowerCase().includes(consulta) && usuario.textContent !== usuarioCreador) {
            var filas = document.querySelectorAll('.usuarios-seleccionados-tabla tbody tr');
            var usuarioYaEnTabla = Array.from(filas).some(function(fila) {
                return fila.dataset.id === usuario.dataset.id;
            });

            if (!usuarioYaEnTabla) {
                var option = document.createElement('option');
                option.value = usuario.dataset.id;
                option.textContent = usuario.textContent;
                resultadosBusqueda.appendChild(option);
            }
        }
    });
});

document.getElementById('usuariosSeleccionados').addEventListener('click', function(e) {
    if (e.target.tagName === 'OPTION') {
        e.target.remove();
    }
});

window.addEventListener('DOMContentLoaded', (event) => {
    var tabla = document.querySelector('.usuarios-seleccionados-tabla tbody');
    var usuarioCreadorElement = document.getElementById('usuarioCreador');
    var usuarioCreador = usuarioCreadorElement.dataset.nombre + ' ' + usuarioCreadorElement.dataset.apellido;
    var usuarioCreadorId = usuarioCreadorElement.dataset.id;

    // Itera sobre los usuarios y crea un elemento de tabla para cada uno
    usuariosAsignados.forEach(function(usuario) {
        var fila = document.createElement('tr');
        fila.dataset.id = usuario.id;

        var celdaId = document.createElement('td');
        celdaId.textContent = usuario.id;
        fila.appendChild(celdaId);

        var celdaUsuario = document.createElement('td');
        celdaUsuario.textContent = usuario.nombre + ' ' + usuario.apellido;
        fila.appendChild(celdaUsuario);

        var celdaEliminar = document.createElement('td');
        var botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.disabled = true;
        celdaEliminar.appendChild(botonEliminar);
        fila.appendChild(celdaEliminar);

        tabla.appendChild(fila);
    });

    var fila = document.createElement('tr');
    fila.dataset.id = usuarioCreadorId;

    var celdaId = document.createElement('td');
    celdaId.className = 'celdaId';
    celdaId.textContent = usuarioCreadorId;
    fila.appendChild(celdaId);

    var celdaNombre = document.createElement('td');
    celdaNombre.className = 'celdaNombre';
    celdaNombre.textContent = usuarioCreador;
    fila.appendChild(celdaNombre);

    var celdaEliminar = document.createElement('td');
    var botonEliminar = document.createElement('button');
    botonEliminar.textContent = 'Eliminar';
    celdaEliminar.className = 'celdaEliminar';
    botonEliminar.disabled = true;
    celdaEliminar.appendChild(botonEliminar);
    fila.appendChild(celdaEliminar);

    tabla.insertBefore(fila, tabla.firstChild);

    var todosUsuarios = document.querySelectorAll('#todosUsuarios .usuario');
    var resultadosBusqueda = document.getElementById('resultadosBusqueda');

    todosUsuarios.forEach(function(usuario) {
        if (usuario.textContent !== usuarioCreador) {
            var option = document.createElement('option');
            option.value = usuario.dataset.id;
            option.textContent = usuario.textContent;
            resultadosBusqueda.appendChild(option);
        }
    });
});

var inputs = document.querySelectorAll('#formulario input, #formulario textarea, #formulario select');

// Recorre cada elemento y añade el atributo 'disabled'
for (var i = 0; i < inputs.length; i++) {
    inputs[i].disabled = true;
}

// Comentarios

$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var comentar = urlParams.get('comentar');

    if (comentar === 'true') {
        activarComentario();
    }
});

function activarTextarea(index) {
    var textarea = document.getElementById('comentario' + index);
    var texto = textarea.value;
    textarea.style.display = 'block';
    textarea.style.textAlign = 'left';
    textarea.style.fontSize = 'reset';
    textarea.style.fontWeight = '400';
    textarea.focus();
    textarea.setSelectionRange(texto.length, texto.length);
    textarea.disabled = false;
    document.getElementById('parrafoComentario' + index).style.display = 'none';
    document.getElementById('eliminar' + index).style.display = 'block';
    document.getElementById('modificar' + index).style.display = 'none';
    document.getElementById('cancelar' + index).style.display = 'block';
    document.getElementById('enviar' + index).style.display = 'block';
    document.getElementById('contenedorComentario').style.display='none';
    document.getElementById('comentario').value='';

    var comentarios = document.querySelectorAll('[id^="comentario"]');
    for (var i = 0; i < comentarios.length; i++) {
        if(i != index) {
            cancelarModificacion(i);
        }
    }

    form.onsubmit = function(e) {
        e.preventDefault();
    };
}

function cancelarModificacion(index) {
    document.getElementById('comentario' + index).style.display = 'none';
    document.getElementById('parrafoComentario' + index).style.display = 'block';
    document.getElementById('eliminar' + index).style.display = 'none';
    document.getElementById('modificar' + index).style.display = 'block';
    document.getElementById('enviar' + index).style.display = 'none';
    document.getElementById('cancelar' + index).style.display = 'none';
    document.getElementById('formComentario' + index).reset();
}

function activarComentario() {
    $('html, body').animate({ scrollTop: $(document).height() }, 'slow');

    document.getElementById('contenedorComentario').style.display='flex';
    document.getElementById('comentario').focus();

    var comentarios = document.querySelectorAll('[id^="comentario"]');
    for (var i = 0; i < comentarios.length; i++) {
        cancelarModificacion(i);
    }

    form.onsubmit = function(e) {
        e.preventDefault();
    };
}

// Máximo de caracteres en el textarea
var textarea = document.getElementById('comentario' + index);

textarea.addEventListener('input', function() {
    if (this.value.length > 500) {
        this.value = this.value.slice(0, 500);
    }
});

document.getElementById('comentario').addEventListener('input', function() {
    if (this.value.length > 500) {
        this.value = this.value.slice(0, 500);
    }
});


// Si textarea está vacia se deshabilita el botón de enviar
window.onload = function() {
    document.getElementById('btn-enviar').disabled = true;
    document.getElementById('comentario' + index).setAttribute('maxlength', '500');
}

document.getElementById('comentario').addEventListener('input', function() {
    if (this.value.trim() === '') {
        document.getElementById('btn-enviar').disabled = true;
    } else {
        document.getElementById('btn-enviar').disabled = false;
    }
});

