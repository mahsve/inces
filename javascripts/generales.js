$(function () {
    // UNA VARIABLE JSON QUE TENDRA EL IDIOMA PARA LAS DATATABLE
    var idioma_es = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad"
        }
    };

    // FUNCION PARA MOSTRAR Y ESCONDER EL MENU EN PANTALLAS PEQUEÑAS.
    $('#menu-manager').click(gestionMenu);
    function gestionMenu ()
    {
        // SI TIENE LA CLASE MOSTRAR LA QUITA.
        if ($('#sidebar').hasClass('show'))
            $('#sidebar').removeClass('show');
        // SI NO LA TIENE LA COLOCA.
        else
            $('#sidebar').addClass('show');
    }

    // INICIALIZAMOS ALGUNAS FUNCIONES DE LOS FRAMEWORK Y LIBRERIAS.
    $('.dropdown-toggle').dropdown();
    $("[data-toggle='popover']").popover();
    $('#listado').dataTable({
        "language": idioma_es
    });
});