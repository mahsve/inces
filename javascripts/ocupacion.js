$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Üa.,-- ])+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // VARIABLES NECESARIAS PARA GUARDAR LOS DATOS CONSULTADOS
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFICACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    let nombres_formularios = { 'A': 'Aprendiz', 'B': 'Familiar del aprendiz', 'F': 'Facilitador' };
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // DATOS DE LA TABLA Y PAGINACION 
    let numeroDeLaPagina    = 1;
    $('#campo_cantidad').change(restablecerN);
    $('#campo_ordenar').change(restablecerN);
    $('#campo_manera_ordenar').change(restablecerN);
    $('#campo_busqueda').keydown(function (e) { if (e.keyCode == 13) { restablecerN(); } else { window.actualizar_busqueda = true; } });
    $('#campo_busqueda').blur(function () { if (window.actualizar_busqueda) { buscar_listado(); } });
    $('#campo_estatus').change(restablecerN);
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA RESTABLECER LA PAGINACION A 1 SI CAMBIA ALGUNOS DE LOS PARAMETROS.
    function restablecerN () { numeroDeLaPagina = 1; buscar_listado(); }
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA LLAMAR LO DATOS DE LA BASE DE DATOS Y MOSTRARLOS EN LA TABLA.
    buscar_listado();
    function buscar_listado () {
        let filas = $('#listado_tabla thead th').length;

        // MOSTRAMOS MENSAJE DE "CARGANDO" EN LA TABLA
        let contenido_tabla = '';
        contenido_tabla += '<tr>';
        contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
        contenido_tabla += '<i class="fas fa-spinner fa-spin"></i> <span style="font-weight: 500;">Cargando...</span>';
        contenido_tabla += '</td>';
        contenido_tabla += '</tr>';
        $('#listado_tabla tbody').html(contenido_tabla);

        // MOSTRAMOS ICONO DE "CARGANDO" EN LA PAGINACIÓN.
        let contenido_paginacion = '';
        contenido_paginacion += '<li class="page-item">';
        contenido_paginacion += '<a class="page-link text-info"><i class="fas fa-spinner fa-spin"></i></a>';
        contenido_paginacion += '</li>';
        $("#paginacion").html(contenido_paginacion);

        // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
        $('.campos_de_busqueda').attr('disabled', true);
        $('.botones_formulario').attr('disabled', true);

        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_ocupacion.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    opcion          : 'Consultar',
                    campo_numero    : parseInt(numeroDeLaPagina-1) * parseInt($('#campo_cantidad').val()),
                    campo_cantidad  : parseInt($('#campo_cantidad').val()),
                    campo_ordenar   : parseInt($('#campo_ordenar').val()),
                    campo_m_ordenar : parseInt($('#campo_manera_ordenar').val()),
                    campo_estatus   : $('#campo_estatus').val(),
                    campo_busqueda  : $('#campo_busqueda').val(),
                },
                success: function (resultados){
                    dataListado = resultados;

                    $('#listado_tabla tbody').empty();
                    if (dataListado.resultados) {
                        let cont = parseInt(numeroDeLaPagina-1) * parseInt($('#campo_cantidad').val()) + 1;
                        for (var i in dataListado.resultados) {
                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Activo</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'I') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-times"></i> <span>Inactivo</span></span>'; }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="text-right py-2 px-1">'+cont+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+nombres_formularios[dataListado.resultados[i].formulario]+'</td>';
                            contenido_tabla += '<td class="text-center py-2 px-1">'+estatus_td+'</td>';
                            ////////////////////////////////////////////////////////
                            if (permisos.modificar == 1 || permisos.act_desc == 1) {
                                contenido_tabla += '<td class="py-1 px-1">';
                                if (permisos.modificar == 1) { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-info editar-registro" data-posicion="'+i+'" style="margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button>'; }
                                if (permisos.act_desc == 1) {
                                    if      (dataListado.resultados[i].estatus == 'A') { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-danger cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye-slash" style="font-size: 12px;"></i></button>'; }
                                    else if (dataListado.resultados[i].estatus == 'I') { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-success cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye"></i></button>'; }
                                }
                                contenido_tabla += '</td>';
                            }
                            ////////////////////////////////////////////////////////
                            contenido_tabla += '</tr>';
                            $('#listado_tabla tbody').append(contenido_tabla);
                            cont++;
                        }
                        $('.editar-registro').click(editarRegistro);
                        $('.cambiar-estatus').click(cambiarEstatus);
                    } else {
                        // MOSTRAMOS MENSAJE "SIN RESULTADOS" EN LA TABLA
                        let contenido_tabla = '';
                        contenido_tabla += '<tr>';
                        contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay ocupaciones registradas.</span>';
                        contenido_tabla += '</td>';
                        contenido_tabla += '</tr>';
                        $('#listado_tabla tbody').html(contenido_tabla);
                    }

                    // SE HABILITA LA FUNCION PARA QUE PUEDA REALIZAR BUSQUEDA AL TERMINAR LA ANTERIOR.
                    window.actualizar_busqueda = false;
                    // MOSTRAR EL TOTAL DE REGISTROS ENCONTRADOS.
                    $('#total_registros').html(dataListado.total);
                    // HABILITAR LA PAGINACION PARA MOSTRAR MAS DATOS.
                    establecer_tabla(numeroDeLaPagina, parseInt($('#campo_cantidad').val()), dataListado.total);
                    // LE AGREGAMOS FUNCIONALIDAD A LOS BOTONES PARA CAMBIAR LA PAGINACION.
                    $('.mover').click(cambiarPagina);

                    // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
                    $('.campos_de_busqueda').attr('disabled', false);
                    $('.botones_formulario').attr('disabled', false);
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
                    $('#btn-recargar-tabla').click(buscar_listado);

                    // MOSTRAMOS ICONO DE "ERROR" EN LA PAGINACIÓN.
                    contenido_paginacion = '';
                    contenido_paginacion += '<li class="page-item">';
                    contenido_paginacion += '<a class="page-link text-danger"><i class="fas fa-ethernet"></i></a>';
                    contenido_paginacion += '</li>';
                    $('#paginacion').html(contenido_paginacion);

                    // SE HABILITA LA FUNCION PARA QUE PUEDA REALIZAR BUSQUEDA AL TERMINAR LA ANTERIOR.
                    window.actualizar_busqueda = false;
                    // MOSTRAR EL TOTAL DE REGISTROS ENCONTRADOS.
                    $('#total_registros').html(0);

                    // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
                    $('.campos_de_busqueda').attr('disabled', false);
                    $('.botones_formulario').attr('disabled', false);
                    
                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                    console.log(errorConsulta.responseText);
                }, timer: 15000
            });
        }, 500);
    }
    // FUNCION PARA CAMBIAR LA PAGINACION.
    function cambiarPagina (e) {
        e.preventDefault();
        numeroDeLaPagina = parseInt($(this).attr('data-pagina'));
        buscar_listado();
    }
    /////////////////////////////////////////////////////////////////////
    
    /////////////////////////////////////////////////////////////////////
    ////////////////////////// VALIDACIONES /////////////////////////////
    function verificarParte1 () {
        tarjeta_1 = true; // CAMBIA A FALSO SI ALGUNO DE LOS CAMPOS ESTA MAL DEFINIDO.

        // VALIDAR NOMBRE DE LA OCUPACION
        let nombre = $("#nombre").val();
        if (nombre != '') {
            if (nombre.match(validar_caracteresEspeciales)) { $("#nombre").css("background-color", colorb); }
            else { $("#nombre").css("background-color", colorm); tarjeta_1 = false; }
        } else {
            $("#nombre").css("background-color", colorm); tarjeta_1 = false;
        }
        
        // VALIDAR FORMULARIO A DONDE VA DIRIGIDO
        let c_formulario = $("#c_formulario").val();
        if (c_formulario != '') {
            $("#c_formulario").css("background-color", colorb);
        } else {
            $("#c_formulario").css("background-color", colorm); tarjeta_1 = false;
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('#nombre').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        
        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro () {
        let posicion = $(this).attr('data-posicion');

        $('#info_table').hide(400);  // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Modificar');
        $('.campos_formularios').css('background-color', colorn);

        document.formulario.reset();
        tipoEnvio       = 'Modificar';
        window.codigo   = dataListado.resultados[posicion].codigo;
        $('#nombre').val(dataListado.resultados[posicion].nombre);
        $('#c_formulario').val(dataListado.resultados[posicion].formulario);

        verificarParte1();
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();

        // SE VERIFICA QUE TODOS LOS CAMPOS ESTEN DEFINIDOS CORRECTAMENTE.
        if (tarjeta_1) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion', value: tipoEnvio });
            data.push({ name: 'codigo', value: window.codigo });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario').attr('disabled', true);
            $('#guardar-datos i.fa-save').addClass('fa-spin');
            $('#guardar-datos span').html('Guardando...');

            // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
            $('#contenedor-mensaje').empty();
            $('#contenedor-mensaje2').empty();
            
            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_ocupacion.php',
                    type: 'POST',
                    data: data,
                    success: function (resultados) {
                        let color_alerta = '';
                        let icono_alerta = '';

                        if (resultados == 'Registro exitoso' || resultados == 'Modificación exitosa') {
                            $('#show_table').trigger('click');
                            buscar_listado();

                            color_alerta = 'alert-success';
                            icono_alerta = '<i class="fas fa-check"></i>';
                        }  else if (resultados == 'Ya está registrado') {
                            $('#show_table').trigger('click');
                            buscar_listado();
                            
                            color_alerta = 'alert-warning';
                            icono_alerta = '<i class="fas fa-exclamation-circle"></i>';
                        } else if (resultados == 'Registro fallido' || resultados == 'Modificación fallida') {
                            color_alerta = 'alert-danger';
                            icono_alerta = '<i class="fas fa-times"></i>';
                        }
    
                        // MENSAJE SOBRE EL ESTATUS DE LA CONSULTA.
                        let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3" role="alert">';
                        contenedor_mensaje += icono_alerta+' '+resultados;
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';

                        // CARGAMOS EL MENSAJE EN EL CONTENEDOR CORRESPONDIENTE.
                        if (resultados == 'Registro exitoso' || resultados == 'Modificación exitosa' || resultados == 'Ya está registrado') {
                            $('#contenedor-mensaje').html(contenedor_mensaje);
                        } else {
                            $('#contenedor-mensaje2').html(contenedor_mensaje);
                        }

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario').attr('disabled', false);
                        $('#guardar-datos i.fa-save').removeClass('fa-spin');
                        $('#guardar-datos span').html('Guardar');
                    },
                    error: function (errorConsulta) {
                        // MENSAJE DE ERROR DE CONEXION.
                        let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                        contenedor_mensaje += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';
                        $('#contenedor-mensaje2').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario').attr('disabled', false);
                        $('#guardar-datos i.fa-save').removeClass('fa-spin');
                        $('#guardar-datos span').html('Guardar');

                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function cambiarEstatus (e) {
        e.preventDefault();
        let posicion    = $(this).attr('data-posicion');
        let codigo      = dataListado.resultados[posicion].codigo;

        // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
        $('.campos_de_busqueda').attr('disabled', true);
        $('.botones_formulario').attr('disabled', true);

        // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
        $('#contenedor-mensaje').empty();
        $('#contenedor-mensaje2').empty();

        // DEFINIMOS EL ESTATUS POR EL QUE SE VA A ACTUALIZAR
        let estatus = '';
        if      (dataListado.resultados[posicion].estatus == 'A') { estatus = 'I'; }
        else if (dataListado.resultados[posicion].estatus == 'I') { estatus = 'A'; }
        
        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_ocupacion.php',
                type: 'POST',
                data: {
                    opcion: 'Estatus',
                    codigo: codigo,
                    estatus: estatus
                },
                success: function (resultados) {
                    let color_alerta = '';
                    let icono_alerta = '';

                    if (resultados == 'Modificación exitosa') {
                        buscar_listado();
                        
                        color_alerta = 'alert-success';
                        icono_alerta = '<i class="fas fa-check"></i>';
                    } else if (resultados == 'Modificación fallida') {
                        color_alerta = 'alert-danger';
                        icono_alerta = '<i class="fas fa-times"></i>';

                        // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
                        $('.campos_de_busqueda').attr('disabled', false);
                        $('.botones_formulario').attr('disabled', false);
                    }

                    // CARGAMOS MENSAJE.
                    let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3" role="alert">';
                    contenedor_mensaje += icono_alerta+' '+resultados;
                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += '</button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje').html(contenedor_mensaje);

                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                },
                error: function (errorConsulta) {
                    // MENSAJE DE ERROR DE CONEXION.
                    let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                    contenedor_mensaje += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += '</button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje').html(contenedor_mensaje);

                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);

                    // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
                    $('.campos_de_busqueda').attr('disabled', false);
                    $('.botones_formulario').attr('disabled', false);

                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                    console.log(errorConsulta.responseText);
                }, timer: 15000
            });
        }, 500);
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
});