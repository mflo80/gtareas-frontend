$(document).ready(function() {
    $('#rowsPerPage').change(function() {
        var rowsToShow = $(this).val();
        $('table tbody tr').each(function(index) {
            $(this).toggle(index < rowsToShow);
        });
    }).change();

    document.getElementById('rowsPerPage').addEventListener('change', function() {
        // Usa las variables globales
        window.location.href = window.routes.buscar + '?filasPorPagina=' + this.value + '&pagina=1';
    });
});
