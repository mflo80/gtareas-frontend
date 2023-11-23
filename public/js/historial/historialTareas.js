$(document).ready(function() {
    $('#rowsPerPageHistorialTareas').change(function() {
        var tasksToShowHistorialTareas = $(this).val();
        var rowsToShowHistorialTareas = tasksToShowHistorialTareas * 2;
        $('table tbody tr').each(function(index) {
            $(this).toggle(index < rowsToShowHistorialTareas);
        });
    }).change();

    $('table tbody tr td').each(function() {
        if ($(this).text().trim() === '') {
            $(this).text('-');
        }
    });

    document.getElementById('rowsPerPageHistorialTareas').addEventListener('change', function() {
        window.location.href = window.routes.buscarHistorialTareas + '?filasPorPaginaHistorialTareas=' + this.value + '&pagina=1';
    });

    document.getElementById('ordenHistorialTareas').addEventListener('change', function() {
        window.location.href = window.routes.buscarHistorialTareas + '?ordenHistorialTareas=' + this.value;
    });
});
