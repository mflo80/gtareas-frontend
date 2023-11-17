$(document).ready(function() {
    $('#rowsPerPageHistorialTareas').change(function() {
        var rowsToShowHistorialTareas = $(this).val();
        $('table tbody tr').each(function(index) {
            $(this).toggle(index < rowsToShowHistorialTareas);
        });
    }).change();

    document.getElementById('rowsPerPageHistorialTareas').addEventListener('change', function() {
        // Usa las variables globales
        window.location.href = window.routes.buscarHistorialTareas + '?filasPorPaginaHistorialTareas=' + this.value + '&pagina=1';
    });
});
