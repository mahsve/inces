$(function () {
    /////////////////////////////////////////////////////////////////////
    // CARGAR DATOS DEL FORMULARIO
    function llamarDatos () {
        // let filas = $('#listado_tabla thead th').length;

        // // MOSTRAMOS MENSAJE DE "CARGANDO" EN LA TABLA
        // let contenido_tabla = '';
        // contenido_tabla += '<tr>';
        // contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
        // contenido_tabla += '<i class="fas fa-spinner fa-spin"></i> <span style="font-weight: 500;">Cargando...</span>';
        // contenido_tabla += '</td>';
        // contenido_tabla += '</tr>';
        // $('#listado_tabla tbody').html(contenido_tabla);

        // // MOSTRAMOS ICONO DE "CARGANDO" EN LA PAGINACIÓN.
        // let contenido_paginacion = '';
        // contenido_paginacion += '<li class="page-item">';
        // contenido_paginacion += '<a class="page-link text-info"><i class="fas fa-spinner fa-spin"></i></a>';
        // contenido_paginacion += '</li>';
        // $("#paginacion").html(contenido_paginacion);

        // // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
        // $('.campos_de_busqueda').attr('disabled', true);
        // $('.botones_formulario').attr('disabled', true);

        $.ajax({
            url: url + "controllers/c_dashboard.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                $('#grafica_1').html(resultados.aprendices_1);
                $('#grafica_2').html(resultados.aprendices_2);
                $('#grafica_3').html(resultados.oficios);
                $('#grafica_4').html(resultados.Facilitadores);
            },
            error: function (errorConsulta) {
                // MOSTRAMOS MENSAJE DE "ERROR" EN LA TABLA
                contenido_tabla = '';
                contenido_tabla += '<tr>';
                contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom text-danger p-2">';
                contenido_tabla += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                contenido_tabla += '<button type="button" id="btn-recargar-tabla" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                contenido_tabla += '</td>';
                contenido_tabla += '</tr>';
                $('#listado_tabla tbody').html(contenido_tabla);
                $('#btn-recargar-tabla').click(llamarDatos);

                // MOSTRAMOS ICONO DE "ERROR" EN LA PAGINACIÓN.
                contenido_paginacion = '';
                contenido_paginacion += '<li class="page-item">';
                contenido_paginacion += '<a class="page-link text-danger"><i class="fas fa-ethernet"></i></a>';
                contenido_paginacion += '</li>';
                $('#paginacion').html(contenido_paginacion);

                // MOSTRAR EL TOTAL DE REGISTROS ENCONTRADOS.
                $('#total_registros').html(0);

                // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                console.log(errorConsulta.responseText);
            }, timer: 15000
        });
    }
    llamarDatos();
    /////////////////////////////////////////////////////////////////////
});