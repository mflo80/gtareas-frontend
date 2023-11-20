// Modal de confirmación de eliminación y modificación

$(document).ready(function() {
    $('#confirmModificarButton').on('click', function() {
        var idsUsuarios = Array.from(document.querySelectorAll('.usuarios-seleccionados-tabla tbody tr')).map(function(fila) {
            return fila.dataset.id;
        }).join(',');

        document.getElementById('idsUsuarios').value = idsUsuarios;

        $('#formulario').submit();
    });

    $('#confirmDeleteButton').on('click', function() {
        $('#deleteForm').submit();
    });
});

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
        var filas = document.querySelectorAll('.usuarios-seleccionados-tabla tbody tr');
        var usuarioYaEnTabla = Array.from(filas).some(function(fila) {
            return fila.dataset.id === usuario.dataset.id;
        });

        if (usuario.textContent.toLowerCase().includes(consulta) && !usuarioYaEnTabla) {
            var option = document.createElement('option');
            option.value = usuario.dataset.id;
            option.textContent = usuario.textContent;
            resultadosBusqueda.appendChild(option);
        }
    });
});

document.getElementById('agregarUsuario').addEventListener('click', function() {
    var resultadosBusqueda = document.getElementById('resultadosBusqueda');
    var idUsuarioSeleccionado = resultadosBusqueda.value;
    var usuarioSeleccionado = resultadosBusqueda.options[resultadosBusqueda.selectedIndex].textContent;
    var tabla = document.querySelector('.usuarios-seleccionados-tabla tbody');

    if (usuarioSeleccionado !== usuarioCreador) {
        var fila = document.createElement('tr');
        fila.dataset.id = idUsuarioSeleccionado;

        var celdaId = document.createElement('td');
        celdaId.className = 'celdaId';
        celdaId.textContent = idUsuarioSeleccionado;
        fila.appendChild(celdaId);

        var celdaNombre = document.createElement('td');
        celdaNombre.className = 'celdaNombre';
        celdaNombre.textContent = usuarioSeleccionado;
        fila.appendChild(celdaNombre);

        var celdaEliminar = document.createElement('td');
        var botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.className = 'btn-eliminar-usuario';
        celdaEliminar.className = 'celdaEliminar';
        botonEliminar.addEventListener('click', function() {
            evento.preventDefault();
            fila.remove();

            var option = document.createElement('option');
            option.value = fila.dataset.id;
            option.textContent = usuarioSeleccionado;

            var opciones = Array.from(resultadosBusqueda.options);
            opciones.push(option);
            opciones.sort(function(a, b) {
                return a.textContent.localeCompare(b.textContent);
            });
            resultadosBusqueda.innerHTML = '';
            opciones.forEach(function(opcion) {
                resultadosBusqueda.appendChild(opcion);
            });
        });
        celdaEliminar.appendChild(botonEliminar);
        fila.appendChild(celdaEliminar);

        tabla.appendChild(fila);
    }

    resultadosBusqueda.remove(resultadosBusqueda.selectedIndex);
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
        botonEliminar.className = 'btn-eliminar-usuario';
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
    botonEliminar.className = 'btn-eliminar-usuario';
    celdaEliminar.className = 'celdaEliminar';
    botonEliminar.disabled = true;
    celdaEliminar.appendChild(botonEliminar);
    fila.appendChild(celdaEliminar);

    tabla.insertBefore(fila, tabla.firstChild);

    var todosUsuarios = document.querySelectorAll('#todosUsuarios .usuario');
    var resultadosBusqueda = document.getElementById('resultadosBusqueda');

    todosUsuarios.forEach(function(usuario) {
        var filas = document.querySelectorAll('.usuarios-seleccionados-tabla tbody tr');
        var usuarioYaEnTabla = Array.from(filas).some(function(fila) {
            return fila.dataset.id === usuario.dataset.id;
        });

        if (usuario.textContent !== usuarioCreador && !usuarioYaEnTabla) {
            var option = document.createElement('option');
            option.value = usuario.dataset.id;
            option.textContent = usuario.textContent;
            resultadosBusqueda.appendChild(option);
        }
    });
});

// Añade manejadores de eventos a los botones de eliminar existentes
document.querySelectorAll('.btn-eliminar-usuario').forEach(function(botonEliminar) {
    botonEliminar.addEventListener('click', function(evento) {
        evento.stopPropagation();
        evento.preventDefault();

        fila.remove();

        var option = document.createElement('option');
        option.value = fila.dataset.id;
        option.textContent = usuarioSeleccionado;

        var opciones = Array.from(resultadosBusqueda.options);
        opciones.push(option);
        opciones.sort(function(a, b) {
            return a.textContent.localeCompare(b.textContent);
        });

        resultadosBusqueda.innerHTML = '';
        opciones.forEach(function(opcion) {
            resultadosBusqueda.appendChild(opcion);
        });
    });
});

// Obtener el campo de fecha y hora
var campoFechaHoraInicio = document.getElementById("fecha-inicio");
var campoFechaHoraFin = document.getElementById("fecha-fin");

// Funcion para manejar el evento input
function handleInput(e) {
    if (!e.target.value) {
        e.target.value = getCurrentDateTime();
    }
}

// Listener para el evento input
campoFechaHoraInicio.addEventListener('input', handleInput);
campoFechaHoraFin.addEventListener('input', handleInput);

// Actualizar el mínimo valor de la fecha y hora de fin
campoFechaHoraInicio.addEventListener('input', () => {
    const fechaHoraInicio = new Date(campoFechaHoraInicio.value);
    const fechaHoraFin = new Date(campoFechaHoraFin.value);
    if (fechaHoraFin <= fechaHoraInicio) {
        campoFechaHoraFin.value = campoFechaHoraInicio.value;
    }
});

campoFechaHoraFin.addEventListener('input', () => {
    const fechaHoraInicio = new Date(campoFechaHoraInicio.value);
    const fechaHoraFin = new Date(campoFechaHoraFin.value);
    if (fechaHoraFin <= fechaHoraInicio) {
        campoFechaHoraFin.value = campoFechaHoraInicio.value;
    }
});

// Limitar la longitud del campo de título
const titulo = document.getElementById('titulo');
const maxLengthTitulo = 30;

titulo.addEventListener('input', () => {
    const text = titulo.value;
    if (text.length > maxLengthTitulo) {
        titulo.value = text.slice(0, maxLengthTitulo);
    }
});

// Textarea límite de texto
const textarea = document.querySelector('textarea');
const maxLengthTextArea = 150;

textarea.addEventListener('input', () => {
    const text = textarea.value;
    if (text.length > maxLengthTextArea) {
        textarea.value = text.slice(0, maxLengthTextArea);
    }
});
