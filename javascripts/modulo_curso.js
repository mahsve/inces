$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_soloNumeros         = /^([0-9])+$/;
    let validar_caracteresSimples   =/^([a-zá-úä-üA-ZÁ-úÄ-Üa. ])+$/;
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Üa.,-- ])+$/;
    let validar_caracteresEspeciales1=/^([a-zá-úä-üA-ZÁ-úÄ-Ü. ])+$/;
    let validar_caracteresEspeciales2=/^([a-zá-úä-üA-ZÁ-úÄ-Ü0-9.,--# ])+$/;
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
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL DE UN CAMPO CUANDO SEA CLIQUEADO.
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFICACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    let mensaje_asignaturas     = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary"><i class="fas fa-hand-pointer"></i> Seleccione un oficio y un módulo</h6>';
    let mensaje_asignaturas2    = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary"><i class="fas fa-file-alt"></i> No hay asignaturas registradas</h6>';
    let mensaje_secciones       = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary"><i class="fas fa-file-alt"></i> Elija el numero de secciones</h6>';
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
                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-info"><i class="fas fa-book-open"></i> <span>En curso</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'F') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Finalizado</span></span>'; }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="py-2 px-1 text-right">'+cont+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].modulo+' - '+dataListado.resultados[i].oficio+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].fecha_inicio.substr(8, 2)+'-'+dataListado.resultados[i].fecha_inicio.substr(5, 2)+'-'+dataListado.resultados[i].fecha_inicio.substr(0, 4)+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+dataListado.resultados[i].asignaturas.length+' Asg.</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+dataListado.resultados[i].horas+' Hrs.</td>';
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
                        $('td[data-toggle="tooltip"]').tooltip();
                        $('.editar-registro').click(editarRegistro);
                        $('.cambiar-estatus').click(cambiarEstatus);
                    } else {
                        // MOSTRAMOS MENSAJE "SIN RESULTADOS" EN LA TABLA
                        let contenido_tabla = '';
                        contenido_tabla += '<tr>';
                        contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay asignaturas registradas.</span>';
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
        // VERIFICAR EL CAMPOS DE SECCIONES (AL MENOS 1 SECCION)
        let cant_seccion = $("#cant_seccion").val();
        if (cant_seccion != "") {
            $("#cant_seccion").css("background-color", colorb);
        } else {
            $("#cant_seccion").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR QUE HAYA ASIGNATURAS REGISTRADAS
        if ($('.codigo_registro').length == 0) {
            tarjeta_1 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_1) {
            $('#icon-modulo').hide();
        } else {
            $('#icon-modulo').show();
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('.input_fecha').datepicker({ language: 'es' });
    $('.input_fecha').click(function () { fechaTemporal = $(this).val(); });
    $('.input_fecha').blur(function () { $(this).val(fechaTemporal); });
    $('.input_fecha').change(function () { fechaTemporal = $(this).val(); });
    $('#nombre').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        
        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';
        $('#fecha').val(fecha);
        $('#contenedor_asignaturas').html(mensaje_asignaturas);
        $('#lista_secciones').html(mensaje_secciones);
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // FUNCIONES EXTRAS SELECT.
    // CONSULTAR MODULOS
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
                        $('#loader-modulo').hide();

                        let dataModulos = resultados.modulos;
                        if (dataModulos) {
                            $("#modulo").html('<option value="">Elija una opción</option>');
                            for (let i in dataModulos) {
                                $("#modulo").append('<option value="'+dataModulos[i].codigo +'">'+dataModulos[i].nombre+"</option>");
                            }
                        } else {
                            $("#modulo").html('<option value="">No hay ciudades</option>');
                        }

                        // // CIUDAD EMPRESA, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        // if (window.valor_modulos != undefined) {
                        //     $("#modulo").val(window.valor_modulos);
                        //     delete window.valor_modulos;
                        //     // verificarParte1();

                        //     $('#carga_espera').hide(400);
                        // }
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
    // CONSULTAR ASIGNATURAS
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
                                window.id_asignatura = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                                let nombre_asignatura = dataAsignaturas[i].nombre;
            
                                // VERIFICAMOS QUE NO SOBREPASE LOS 35 CARACTERES Y SI ES ASI LO RECORTAMOS.
                                if (dataAsignaturas[i].nombre.length > 35) {
                                    nombre_asignatura = dataAsignaturas[i].nombre.substr(0, 35);
                                    if (nombre_asignatura[nombre_asignatura.length - 1] == ' ') { nombre_asignatura = dataAsignaturas[i].nombre.substr(0, 34); }
                                    nombre_asignatura += '...';
                                }

                                let contenedor_asignatura = '';
                                contenedor_asignatura += '<div id="asignatura-'+window.id_asignatura+'" class="bg-info text-white rounded d-flex align-items-center justify-content-between w-100 my-1 px-2 py-1">';
                                    contenedor_asignatura += '<input type="hidden" name="codigo_registro[]" class="codigo_registro" value="0">';
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
    // HABILITAR SECCIONES SELECCIONADAS
    $('#cant_seccion').change(function () {
        let secciones   = $('#cant_seccion').val();
        let secciones_h = $('.aprendices_seccion_m').length;
        if (secciones != '') {
            secciones   = parseInt($('#cant_seccion').val());

            // SI NO HAY NINGUNA SECCION AGREGADA LA ELIMINA EL CONTENIDO POR DEFAULT
            if ($('#lista_secciones').html() == mensaje_secciones) { $('#lista_secciones').empty(); }
            if (secciones < secciones_h) {
                // ELIMINAR LAS SECCIONES SOBRANTES
                for (let i = secciones; i < secciones_h; i++) {
                    $($('.aprendices_seccion_m')[$('.aprendices_seccion_m').length - 1]).remove();
                    $($('.aprendices_seccion_v')[$('.aprendices_seccion_v').length - 1]).remove();
                }
            } else if (secciones > secciones_h) {
                // AGREGAR NUEVAS SECCIONES AL MODULO
                for (let i = secciones_h; i < secciones; i++) {
                    let contenido_seccion = '';
                    // SECCION MATUTINA
                    contenido_seccion += '<div id="seccion_m'+(i + 1)+'" class="aprendices_seccion_m mb-2">';
                        contenido_seccion += '<h5 class="font-weight-normal text-secondary text-center text-uppercase">Sección '+(i + 1)+' - Turno: matutino</h5>';
                    
                        contenido_seccion += '<div class="border rounded bg-white overflow-auto p-3"  style="max-height: 300px;">';
                            contenido_seccion += '<table class="table table-borderless table-hover mb-0" style="min-width: 600px;">';
                                contenido_seccion += '<thead>';
                                    contenido_seccion += '<tr class="text-white">';
                                        contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="100">Cédula</th>';
                                        contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>';
                                        contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2 rounded-right">Empresa</th>';
                                    contenido_seccion += '</tr>';
                                contenido_seccion += '</thead>';
    
                                contenido_seccion += '<tbody>';
                                    contenido_seccion += '<tr class="border-bottom text-secondary"><td colspan="3" class="text-center p-2"><i class="fas fa-file-alt"></i><span style="font-weight: 500;"> Todavía no hay aprendices registrados.</span></td></tr>';
                                contenido_seccion += '</tbody>';
                            contenido_seccion += '</table>';
                        contenido_seccion += '</div>';
                    contenido_seccion += '</div>';
                    // FIN SECCION MATUTINA
    
                    // SECCION VESPERTINA
                    contenido_seccion += '<div id="seccion_v'+(i + 1)+'" class="aprendices_seccion_v mb-4">';
                        contenido_seccion += '<h5 class="font-weight-normal text-secondary text-center text-uppercase">Sección '+(i + 1)+' - Turno: vespertino</h5>';
                    
                        contenido_seccion += '<div class="border rounded bg-white overflow-auto p-3"  style="max-height: 300px;">';
                            contenido_seccion += '<table class="table table-borderless table-hover mb-0" style="min-width: 600px;">';
                                contenido_seccion += '<thead>';
                                    contenido_seccion += '<tr class="text-white">';
                                        contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="100">Cédula</th>';
                                        contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>';
                                        contenido_seccion += '<th class="bg-info font-weight-normal px-1 py-2 rounded-right">Empresa</th>';
                                    contenido_seccion += '</tr>';
                                contenido_seccion += '</thead>';
    
                                contenido_seccion += '<tbody>';
                                    contenido_seccion += '<tr class="border-bottom text-secondary"><td colspan="3" class="text-center p-2"><i class="fas fa-file-alt"></i><span style="font-weight: 500;"> Todavía no hay aprendices registrados.</span></td></tr>';
                                contenido_seccion += '</tbody>';
                            contenido_seccion += '</table>';
                        contenido_seccion += '</div>';
                    contenido_seccion += '</div>';
                    // FIN SECCION VESPERTINA
                    $('#lista_secciones').append(contenido_seccion);
                }
            }
        } else {
            $('#lista_secciones').html(mensaje_secciones);
        }
    });
    /////////////////////////////////////////////////////////////////////
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
        window.eliminarAsignaturas= [];
        $('#oficio').trigger('change');

        $('#descripcion').val(dataListado.resultados[posicion].descripcion);
        $('#anio_modulo').val(dataListado.resultados[posicion].anio_modulo);
        $('#p_anio_modulo').val(dataListado.resultados[posicion].parte_anio);
        $('#oficio').val(dataListado.resultados[posicion].codigo_oficio);
        $('#modulo').val(dataListado.resultados[posicion].codigo_modulo);
        $('#sesion').val(dataListado.resultados[posicion].codigo_seccion);
        window.asignaturas = dataListado.resultados[posicion].detalles_asignaturas;
        $('#oficio').trigger('change');

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
            data.push({ name: 'eliminar_asignaturas', value: JSON.stringify(window.eliminarAsignaturas) });

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
