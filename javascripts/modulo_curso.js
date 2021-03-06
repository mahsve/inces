$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_caracteresSimples   =/^([a-zA-Z0-9])+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1, tarjeta_2;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // VARIABLES NECESARIAS PARA GUARDAR LOS DATOS CONSULTADOS
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL DE UN CAMPO CUANDO SEA CLIQUEADO.
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFICACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    let dataTurno       = {'': 'Elija una opción', 'M': 'Matutino', 'V': 'Vespertino'};
    let mensaje_asignaturas     = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary"><i class="fas fa-hand-pointer"></i> Seleccione un oficio y un módulo</h6>';
    let mensaje_asignaturas2    = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary"><i class="fas fa-file-alt"></i> No hay asignaturas registradas</h6>';
    let mensaje_secciones       = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary"><i class="fas fa-file-alt"></i> Agregue al menos una sección.</h6>';
    let mensaje_table_seccion   = '<tr class="border-bottom text-secondary"><td colspan="3" class="text-center p-2"><i class="fas fa-file-alt"></i><span style="font-weight: 500;"> Todavía no hay aprendices registrados.</span></td></tr>';
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
                url : url+'controllers/c_modulo_curso.php',
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
                            let nombre_modulo = abreviarDescripcion(dataListado.resultados[i].modulo+' - '+dataListado.resultados[i].oficio, 45);

                            let total_horas         = 0;
                            let total_asignaturas   = 0;

                            let dataAsignaturas = dataListado.resultados[i].secciones[0].asignaturas;
                            for (let ix = 0; ix < dataAsignaturas.length; ix++) {
                                // SOLO TOMARAN EN CUENTA LA DE LA PRIMERA SECCION
                                total_horas += parseInt(dataAsignaturas[ix].horas);
                                total_asignaturas++;
                            }

                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-info"><i class="fas fa-book-open"></i> <span>En curso</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'F') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Finalizado</span></span>'; }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="py-2 px-1 text-right">'+cont+'</td>';
                            contenido_tabla += '<td class="py-2 px-1"><span class="tooltip-table" data-toggle="tooltip" data-placement="right" title="'+dataListado.resultados[i].modulo+' - '+dataListado.resultados[i].oficio+'">'+nombre_modulo+'</span></td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].fecha_inicio.substr(8, 2)+'-'+dataListado.resultados[i].fecha_inicio.substr(5, 2)+'-'+dataListado.resultados[i].fecha_inicio.substr(0, 4)+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+total_asignaturas+' Asg.</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+total_horas+' Hrs.</td>';
                            contenido_tabla += '<td class="text-center py-2 px-1">'+estatus_td+'</td>';
                            
                            ////////////////////////////////////////////////////////
                            if (permisos.modificar == 1 || permisos.act_desc == 1) {
                                contenido_tabla += '<td class="py-1 px-1">';
                                    if (permisos.modificar == 1) { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-info editar-registro" data-posicion="'+i+'" style="margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button>'; }
                                    // if (permisos.act_desc == 1) {
                                    //     if      (dataListado.resultados[i].estatus == 'A') { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-danger cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye-slash" style="font-size: 12px;"></i></button>'; }
                                    //     else if (dataListado.resultados[i].estatus == 'I') { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-success cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye"></i></button>'; }
                                    // }

                                    // MAS OPCIONES
                                    contenido_tabla += '<div class="dropdown d-inline-block">';
                                        contenido_tabla += '<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                            contenido_tabla += '<i class="fas fa-ellipsis-v px-1"></i>';
                                        contenido_tabla += '</button>';

                                        if (permisos.act_desc == 1) {
                                            contenido_tabla += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                            
                                            if (dataListado.resultados[i].estatus == 'A') {
                                                contenido_tabla += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 aceptar_postulante" data-posicion="'+i+'"><i class="fas fa-check text-center" style="width:20px;"></i><span class="ml-2">Finalizar y registrar nuevo</span></a></li>';
                                                contenido_tabla += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 rechazar_postulante" data-posicion="'+i+'"><i class="fas fa-times text-center" style="width:20px;"></i><span class="ml-2">Eliminar curso</span></a></li>';
                                            }
                                            contenido_tabla += '</div>';
                                        }
                                    contenido_tabla += '</div>';
                                contenido_tabla += '</td>';
                            }
                            ////////////////////////////////////////////////////////
                            contenido_tabla += '</tr>';
                            $('#listado_tabla tbody').append(contenido_tabla);
                            cont++;
                        }
                        $('.tooltip-table').tooltip();
                        $('.editar-registro').click(editarRegistro);
                        $('.cambiar-estatus').click(cambiarEstatus);
                    } else {
                        // MOSTRAMOS MENSAJE "SIN RESULTADOS" EN LA TABLA
                        let contenido_tabla = '';
                        contenido_tabla += '<tr>';
                        contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay módulos registrados.</span>';
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
    // VERIFICAR LOS CAMPOS DEL FORMULARIO
    function verificarParte1 () {
        tarjeta_1 = true;
        // VERIFICAR EL CAMPO LA FECHA DE REGISTRO
        let fecha = $("#fecha").val();
        if (fecha != "") {
            $("#fecha").css("background-color", colorb);
        } else {
            $("#fecha").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE OFICIO
        let oficio = $("#oficio").val();
        if (oficio != "") {
            $("#oficio").css("background-color", colorb);
        } else {
            $("#oficio").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE MODULO PERTENECIENTE AL OFICIO
        let modulo = $("#modulo").val();
        if (modulo != "") {
            $("#modulo").css("background-color", colorb);
        } else {
            $("#modulo").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR QUE HAYA ASIGNATURAS REGISTRADAS
        if ($('.codigo_asignatura').length == 0) {
            tarjeta_1 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_1) {
            $('#icon-modulo').hide();
        } else {
            $('#icon-modulo').show();
        }
    }
    function verificarParte2 () {
        tarjeta_2 = true;

        if ($('.aprendices_seccion').length > 0) {
            $('#lista_secciones').css('background-color', '');

            $('.campo_seccion').each(function () {
                let campo_seccion = $(this).val();
                if (campo_seccion != "") {
                    if (campo_seccion.match(validar_caracteresSimples)) {
                        $(this).css("background-color", colorb);
                    } else {
                        $(this).css("background-color", colorm);
                        tarjeta_2 = false;
                    }
                } else {
                    $(this).css("background-color", colorm);
                    tarjeta_2 = false;
                }
            });

            $('.campo_turno').each(function () {
                let campo_turno = $(this).val();
                if (campo_turno != "") {
                    $(this).css("background-color", colorb);
                } else {
                    $(this).css("background-color", colorm);
                    tarjeta_2 = false;
                }
            });
        } else {
            $('#lista_secciones').css('background-color', colorm);
            tarjeta_2 = false;
        }

        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_2) {
            $('#icon-secciones').hide();
        } else {
            $('#icon-secciones').show();
        }
    }
    //////////////////////// FIN VALIDACIONES ///////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('.input_fecha').datepicker({ language: 'es' });
    $('.input_fecha').click(function () { fechaTemporal = $(this).val(); });
    $('.input_fecha').blur(function () { $(this).val(fechaTemporal); });
    $('.input_fecha').change(function () { fechaTemporal = $(this).val(); });
    $('#nombre').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        
        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');  // CHECK DE VALIDACION DE CEDULA Y RIF (ICONO)
        $('.ocultar-iconos').hide();  // ICONO DE CARGA DE CEDULA Y RIF (ICONO)
        $('.btn-recargar').hide(); // BOTON RECARGAR DE LAS CONSULTAS INDEPENDIENTES (CARGO, ACTIVIDAD ECONOMICA).
        $('.icon-alert').hide(); // ICONOS EN LAS PESTAÑAS DE LOS FORMULARIOS.

        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';
        window.eliminarSeccion = [];

        $('#fecha').val(fecha);
        $('#modulo').html('<option value="">Elija un oficio</option>');
        $('#contenedor_asignaturas').html(mensaje_asignaturas);
        $('#lista_secciones').html(mensaje_secciones);
        $('#lista_secciones').css('background-color', '');
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
        $('#pills-modulo-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // AGREGAR INFORMACION AL FORMULARIO DINAMICAMENTE.
    // AGREGAR SECCION DINAMICAMENTE Y ELEGIR TURNO
    $('#btn-agregar-seccion').click(function () {
        // ELIMINAMOS MENSAJE POR DEFECTO DEL CONTENEDOR SI LO POSEE
        if ($('#lista_secciones').html() == mensaje_secciones) { $('#lista_secciones').empty(); }

        // ID DINAMICO
        window.id_seccion = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.

        // NUEVA SECCION
        let contenido_seccion = '';
        contenido_seccion += '<div id="seccion_'+window.id_seccion+'" class="aprendices_seccion position-relative border rounded bg-white my-2 mb-2 p-3">';
            contenido_seccion += '<button type="button" class="btn btn-sm btn-danger position-absolute rounded-circle eliminar-seccion p-0" data-id-seccion="'+window.id_seccion+'" style="width: 25px; height: 25px; right: 1rem;"><i class="fas fa-times px-1" style="font-size: 12px;"></i></button>';
        
            contenido_seccion += '<input type="hidden" name="registro_seccion[]" class="registro_seccion" value="0">'
            contenido_seccion += '<h5 class="font-weight-normal text-secondary text-center text-uppercase">';
                contenido_seccion += 'Sección'
                contenido_seccion += '<input type="text" name="seccion[]" class="campo_seccion form-control form-control-sm d-inline-block position-relative text-center ml-1" style="width: 50px; top: -1px;"/>';
                contenido_seccion += ' - Turno: ';
                contenido_seccion += '<select name="turno[]" class="campo_turno custom-select custom-select-sm position-relative" style="width: auto; top: -2px;">';
                    for (let i in dataTurno) { contenido_seccion += '<option value="'+i+'">'+dataTurno[i]+'</option>'; }
                contenido_seccion += '</select>';
            contenido_seccion += '</h5>';
        
            contenido_seccion += '<div class="pb-1" style="max-height: 250px;">';
                contenido_seccion += '<table class="table table-borderless table-hover mb-0">';
                    contenido_seccion += '<thead>';
                        contenido_seccion += '<tr class="text-white">';
                            contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="100">Cédula</th>';
                            contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>';
                            contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2 rounded-right">Empresa</th>';
                        contenido_seccion += '</tr>';
                    contenido_seccion += '</thead>';

                    contenido_seccion += '<tbody>';
                        contenido_seccion += mensaje_table_seccion;
                    contenido_seccion += '</tbody>';
                contenido_seccion += '</table>';
            contenido_seccion += '</div>';
        contenido_seccion += '</div>';
        // FIN NUEVA SECCION

        $('#lista_secciones').append(contenido_seccion);
        $('#seccion_'+window.id_seccion+' .eliminar-seccion').click(eliminarSeccion);
    });
    function eliminarSeccion (e) {
        e.preventDefault();

        // OBTENEMOS EL ID DEL CONTENEDOR
        let id_seccion = $(this).attr('data-id-seccion');
        // PARA ELIMINAR UNA SECCION NO DEBE TENER APRENDICES
        if ($('#seccion_'+window.id_seccion+' table tbody').html() == mensaje_table_seccion) {
            // SI YA FUE REGISTRADO EN LA BASE DE DATOS, PROCEDEMOS A GUARDAR EL ID EN UN ARREGLO PARA ELIMINARLO COMPLETAMENTE.
            if ($('#seccion_'+window.id_seccion+' .registro_seccion').val() != 0) {
                window.eliminarSeccion.push($('#seccion_'+window.id_seccion+' .registro_seccion').val());
            }

            // ELIMINAMOS EL ELEMENTO DEL DOM
            $('#seccion_'+window.id_seccion).remove();
        }

        // SI QUEDA VACIO LE AGREGAMOS EL MENSAJE POR DEFECTO NUEVAMENTE
        if ($('#lista_secciones').html() == '') {
            $('#lista_secciones').html(mensaje_secciones);
        }
    }
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES EXTRAS DE LOS CAMPOS.
    // CONSULTAR LOS MODULOS DEL OFICIO
    $('#oficio').change(buscarModulos);
    $('#loader-modulo-reload').click(function () { $('#oficio').trigger('change'); });
    function buscarModulos () {
        if ($('#oficio').val() != "") {
            $('#loader-modulo').show();
            $('#loader-modulo-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_modulo_curso.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer módulos",
                        oficio: $('#oficio').val()
                    },
                    success: function (resultados) {
                        // OCULTAR ICONO DE CARGA
                        $('#loader-modulo').hide();

                        // CARGAMOS LOS MODULOS
                        let dataModulos = resultados.modulos;
                        if (dataModulos) {
                            $("#modulo").html('<option value="">Elija una opción</option>');
                            for (let i in dataModulos) {
                                $("#modulo").append('<option value="'+dataModulos[i].codigo +'">'+dataModulos[i].nombre+"</option>");
                            }
                        } else {
                            $("#modulo").html('<option value="">No hay ciudades</option>');
                        }

                        // CARGAMOS LOS MODULOS TRAIDOS DESDE LA BASE DE DATOS AL EDITAR.
                        if (window.valor_modulos != undefined) {
                            $("#modulo").val(window.valor_modulos);
                            delete window.valor_modulos;

                            verificarParte1();
                            $('#carga_espera').hide(400);
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                        $('#loader-modulo').hide();
                        $('#loader-modulo-reload').show();

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
                        
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        } else {
            $('#modulo').html('<option value="">Elija un oficio</option>');
            $('#contenedor_asignaturas').html(mensaje_asignaturas);
        }
    }
    // CONSULTAR ASIGNATURAS DEL OFICIO SEGUN EL MODULO
    $('#modulo').change(buscarAsignaturas);
    $('#loader-asignaturas-reload').click(function () { $('#modulo').trigger('change'); });
    function buscarAsignaturas () {
        if ($('#modulo').val() != '') {
            $('#loader-asignaturas').show();
            $('#loader-asignaturas-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_modulo_curso.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer asignaturas",
                        modulo: $('#modulo').val()
                    },
                    success: function (resultados) {
                        $('#loader-asignaturas').hide();

                        // CARGAMOS LAS CIUDADES DEL ESTADO SELECCIONADO
                        let dataAsignaturas = resultados.asignaturas;
                        if (dataAsignaturas) {
                            $('#contenedor_asignaturas').empty();
                            for (let i in dataAsignaturas) {
                                // GENERA UN ID ALEATORIO.
                                window.id_asignatura = Math.random().toString().replace('.', '-');
                                // RECORAMOS LA DESCRIPCION.
                                let nombre_asignatura = abreviarDescripcion(dataAsignaturas[i].nombre, 30);
            
                                let contenedor_asignatura = '';
                                contenedor_asignatura += '<div id="asignatura-'+window.id_asignatura+'" class="bg-info text-white rounded d-flex align-items-center justify-content-between w-100 my-1 px-2 py-1">';
                                    contenedor_asignatura += '<input type="hidden" name="codigo_asignatura[]" class="codigo_asignatura" value="'+dataAsignaturas[i].codigo+'">';
                                    contenedor_asignatura += '<input type="hidden" name="horas_asignaturas[]" class="horas_asignaturas" value="'+dataAsignaturas[i].horas+'">';
                                    contenedor_asignatura += '<span class="nombre_asignatura" data-toggle="tooltip" data-placement="right" title="'+dataAsignaturas[i].nombre+'">'+nombre_asignatura+' | Horas: '+dataAsignaturas[i].horas+'</span>';

                                    contenedor_asignatura += '<div></div>';
                                contenedor_asignatura += '</div>';
                                $('#contenedor_asignaturas').append(contenedor_asignatura);
                                $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').tooltip();
                            }
                        } else {
                            $('#contenedor_asignaturas').html(mensaje_asignaturas2);
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                        $('#loader-asignaturas').hide();
                        $('#loader-asignaturas-reload').show();
                        $('#contenedor_asignaturas').empty();

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
                        
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        } else {
            $('#loader-asignaturas').hide();
            $('#loader-asignaturas-reload').hide();
            $('#contenedor_asignaturas').html(mensaje_asignaturas);
        }
    }
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
        $('#carga_espera').show();

        document.formulario.reset();
        tipoEnvio       = 'Modificar';
        window.codigo   = dataListado.resultados[posicion].codigo;

        let fecha_nu = dataListado.resultados[posicion].fecha_inicio;
        $('#fecha').val(fecha_nu.substr(8, 2)+'-'+fecha_nu.substr(5, 2)+'-'+fecha_nu.substr(0, 4));
        $('#oficio').val(dataListado.resultados[posicion].codigo_oficio);
        $('#modulo').html('<option value="">Elija un oficio</option>');

        let dataAsignaturas = dataListado.resultados[posicion].secciones[0].asignaturas;
        if (dataAsignaturas) {
            $('#contenedor_asignaturas').empty();
            for (let i = 0; i < dataAsignaturas.length; i++) {
                // GENERA UN ID ALEATORIO.
                window.id_asignatura = Math.random().toString().replace('.', '-');
                // RECORAMOS LA DESCRIPCION.
                let nombre_asignatura = abreviarDescripcion(dataAsignaturas[i].nombre, 30);

                let contenedor_asignatura = '';
                contenedor_asignatura += '<div id="asignatura-'+window.id_asignatura+'" class="bg-info text-white rounded d-flex align-items-center justify-content-between w-100 my-1 px-2 py-1">';
                    contenedor_asignatura += '<input type="hidden" name="codigo_asignatura[]" class="codigo_asignatura" value="'+dataAsignaturas[i].codigo_asignatura+'">';
                    contenedor_asignatura += '<input type="hidden" name="horas_asignaturas[]" class="horas_asignaturas" value="'+dataAsignaturas[i].horas+'">';
                    contenedor_asignatura += '<span class="nombre_asignatura" data-toggle="tooltip" data-placement="right" title="'+dataAsignaturas[i].nombre+'">'+nombre_asignatura+' | Horas: '+dataAsignaturas[i].horas+'</span>';

                    contenedor_asignatura += '<div></div>';
                contenedor_asignatura += '</div>';
                $('#contenedor_asignaturas').append(contenedor_asignatura);
                $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').tooltip();
            }
        } else {
            $('#contenedor_asignaturas').html(mensaje_asignaturas2);
        }

        $('#lista_secciones').empty();
        let dataSecciones = dataListado.resultados[posicion].secciones;
        for (let ix = 0; ix < dataSecciones.length; ix++) {
            $('#btn-agregar-seccion').trigger('click');

            $('#seccion_'+window.id_seccion+' .registro_seccion').val(dataSecciones[ix].codigo);
            $('#seccion_'+window.id_seccion+' .campo_seccion').val(dataSecciones[ix].seccion);
            $('#seccion_'+window.id_seccion+' .campo_turno').val(dataSecciones[ix].turno);
        }
        verificarParte2();

        window.valor_modulos= dataListado.resultados[posicion].codigo_modulo;
        $('#oficio').trigger('change');
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();
        verificarParte2();

        // SE VERIFICA QUE TODOS LOS CAMPOS ESTEN DEFINIDOS CORRECTAMENTE.
        if (tarjeta_1 && tarjeta_2) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion', value: tipoEnvio });
            data.push({ name: 'codigo', value: window.codigo });
            data.push({ name: 'eliminar_seccion', value: JSON.stringify(window.eliminarSeccion) });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario').attr('disabled', true);
            $('#guardar-datos i.fa-save').addClass('fa-spin');
            $('#guardar-datos span').html('Guardando...');

            // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
            $('#contenedor-mensaje').empty();
            $('#contenedor-mensaje2').empty();
            
            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_modulo_curso.php',
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
                        } else if (resultados.indexOf('Registro fallido') == -1  || resultados.indexOf('Modificación fallida') == -1) {
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
                url : url+'controllers/c_modulo_curso.php',
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

    /////////////////////////////////////////////////////////////////////
    // CARGAR DATOS DEL FORMULARIO
    function llamarDatos () {
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

        $.ajax({
            url: url + "controllers/c_modulo_curso.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                fecha = resultados.fecha;

                let dataOficios = resultados.oficios;
                if (dataOficios) {
                    for (let i in dataOficios) {
                        $("#oficio").append('<option value="'+dataOficios[i].codigo +'">'+dataOficios[i].nombre+"</option>");
                    }
                } else {
                    $("#oficio").html('<option value="">No hay oficios</option>');
                }
                buscar_listado();
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
