$(document).ready(function() {
    $('#rowsPerPage').change(function() {
        var rowsToShow = $(this).val();
        $('table tbody tr').each(function(index) {
            $(this).toggle(index < rowsToShow);
        });
    }).change();

    document.getElementById('rowsPerPage').addEventListener('change', function() {
        window.location.href = window.routes.buscar + '?filasPorPagina=' + this.value + '&pagina=1';
    });

    document.getElementById('ordenTareas').addEventListener('change', function() {
        window.location.href = window.routes.buscar + '?ordenTareas=' + this.value;
    });
});
