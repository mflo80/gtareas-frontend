$(document).ready(function() {
    $('#rowsPerPageHistorialComentarios').change(function() {
        var tasksToShowHistorialComentarios = $(this).val();
        var rowsToShowHistorialComentarios = tasksToShowHistorialComentarios * 2;
        $('table tbody tr').each(function(index) {
            $(this).toggle(index < rowsToShowHistorialComentarios);
        });
    }).change();

    $('table tbody tr td').each(function() {
        if ($(this).text().trim() === '') {
            $(this).text('-');
        }
    });

    document.getElementById('rowsPerPageHistorialComentarios').addEventListener('change', function() {
        window.location.href = window.routes.buscarHistorialComentarios + '?filasPorPaginaHistorialComentarios=' + this.value + '&pagina=1';
    });

    document.getElementById('ordenHistorialComentarios').addEventListener('change', function() {
        window.location.href = window.routes.buscarHistorialComentarios + '?ordenHistorialComentarios=' + this.value;
    });
});
