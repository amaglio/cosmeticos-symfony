$(document).ready(function() {
    $('#productos').DataTable({
        language: {
            "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "<i data-toggle='tooltip' data-placement='top' title='Buscar productos' class='fas fa-search'></i>",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "  <i class='fas fa-1x fa-angle-right'></i>",
                    "sPrevious": "  <i class='fas fa-1x fa-angle-left'></i>"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
        }
        
    });
    
    $( "select[name='productos_length']").addClass('form-control" btn btn-info');

    $( "a.paginate_button").addClass('btn btn-info');
    $( "a.paginate_button").removeClass('paginate_button`');
} );