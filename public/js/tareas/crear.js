
// Obtener el campo de fecha y hora
var campoFechaHoraInicio = document.getElementById("fecha-inicio");
var campoFechaHoraFin = document.getElementById("fecha-fin");

// Obtener la fecha y hora actual
function getCurrentDateTime() {
    var fechaHoraActual = new Date();
    fechaHoraActual.setHours(fechaHoraActual.getHours() - 3);
    return fechaHoraActual.toISOString().slice(0, 16);
}

// Inicializar el campo de fecha y hora
campoFechaHoraInicio.value = getCurrentDateTime();
campoFechaHoraFin.value = getCurrentDateTime();

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
