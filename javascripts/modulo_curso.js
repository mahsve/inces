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
    let listaPAnio   = ['', '1° semestre del año', '2° semestre del año'];
    let listaModulos = ['', 'Módulo 1', 'Módulo 2', 'Módulo 3', 'Módulo 4'];
    let listaSesiones = ['', 'Sesión A', 'Sesión B', 'Sesión C', 'Sesión D'];
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // VARIABLES NECESARIAS PARA GUARDAR LOS DATOS CONSULTADOS
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL DE UN CAMPO CUANDO SEA CLIQUEADO.
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFICACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
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
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Activo</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'I') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-times"></i> <span>Inactivo</span></span>'; }

                            // RECORTES NOMBRES
                            let nombreDescripcion = '';
                            if      (dataListado.resultados[i].descripcion.length <= 20) { nombreDescripcion = dataListado.resultados[i].descripcion; }
                            else if (dataListado.resultados[i].descripcion.length > 20) {
                                nombreDescripcion = dataListado.resultados[i].descripcion.substr(0, 20);
                                if (nombreDescripcion[nombreDescripcion.length - 1] == ' ') { nombreDescripcion = nombreDescripcion.substr(0, 19) }
                                nombreDescripcion += '...';
                            }
                            //////////////////////////////////////////////////////
                            let nombreOficio = '';
                            if      (dataListado.resultados[i].oficio.length <= 20) { nombreOficio = dataListado.resultados[i].oficio; }
                            else if (dataListado.resultados[i].oficio.length > 20) {
                                nombreOficio = dataListado.resultados[i].oficio.substr(0, 20);
                                if (nombreOficio[nombreOficio.length - 1] == ' ') { nombreOficio = nombreOficio.substr(0, 19) }
                                nombreOficio += '...';
                            }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="py-2 px-1 text-right">'+cont+'</td>';
                            contenido_tabla += '<td class="py-2 px-1" data-toggle="tooltip" data-placement="top" title="'+dataListado.resultados[i].descripcion+'">'+nombreDescripcion+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+dataListado.resultados[i].anio_modulo+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+listaPAnio[dataListado.resultados[i].parte_anio]+'</td>';
                            contenido_tabla += '<td class="py-2 px-1" data-toggle="tooltip" data-placement="top" title="'+dataListado.resultados[i].oficio+'">'+nombreOficio+' - '+listaModulos[dataListado.resultados[i].codigo_modulo]+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+listaSesiones[dataListado.resultados[i].codigo_seccion]+'</td>';
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

        // VERIFICAR EL CAMPO DEL NOMBRE DEL MODULO.
        let descripcion = $("#descripcion").val();
        if (descripcion != "") {
            if (descripcion.match(validar_caracteresEspeciales2)) {
                $("#descripcion").css("background-color", colorb);
            } else {
                $("#descripcion").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#descripcion").css("background-color", colorm);
            tarjeta_1 = false;
        }

        // VERIFICAR EL CAMPO OFICIO AL QUE PERTENECE EL MODULO.
        let anio_modulo = $("#anio_modulo").val();
        if (anio_modulo != "") {
            $("#anio_modulo").css("background-color", colorb);
        } else {
            $("#anio_modulo").css("background-color", colorm);
            tarjeta_1 = false;
        }

        // VERIFICAR EL CAMPO OFICIO AL QUE PERTENECE EL MODULO.
        let p_anio_modulo = $("#p_anio_modulo").val();
        if (p_anio_modulo != "") {
            $("#p_anio_modulo").css("background-color", colorb);
        } else {
            $("#p_anio_modulo").css("background-color", colorm);
            tarjeta_1 = false;
        }

        // VERIFICAR EL CAMPO OFICIO AL QUE PERTENECE EL MODULO.
        let oficio = $("#oficio").val();
        if (oficio != "") {
            $("#oficio").css("background-color", colorb);
        } else {
            $("#oficio").css("background-color", colorm);
            tarjeta_1 = false;
        }

        // VERIFICAR EL CAMPO OFICIO AL QUE PERTENECE EL MODULO.
        let modulo = $("#modulo").val();
        if (modulo != "") {
            $("#modulo").css("background-color", colorb);
        } else {
            $("#modulo").css("background-color", colorm);
            tarjeta_1 = false;
        }

        // VERIFICAR EL CAMPO OFICIO AL QUE PERTENECE EL MODULO.
        let sesion = $("#sesion").val();
        if (sesion != "") {
            $("#sesion").css("background-color", colorb);
        } else {
            $("#sesion").css("background-color", colorm);
            tarjeta_1 = false;
        }
        
        // VERIFICAMOS QUE HAYAS ASIGNATURAS CARGADAS.
        if ($('.campo_asignatura').length > 0) {
            // REALIZAMOS UN CONTADOR PARA VERIFICAR CUANTAS ESTAN MARCADAS.
            let total_checked = 0;
            // RECORREMOS CADA UNOS DE LOS CHECKBOX.
            $('.campo_asignatura').each(function () { if ($(this).prop('checked')) { total_checked++ } });
            // SI NO HAY NINGUNO SELECCIONADO NO PERMITE GUARDAR.
            if (total_checked == 0) { tarjeta_1 = false;}
        } else {
            tarjeta_1 = false;
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
        window.eliminarAsignaturas= [];
        $('#oficio').trigger('change');
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // FUNCIONES EXTRAS SELECT.
    $('#oficio').change(buscarAsignaturas);
    $('#modulo').change(function () { $('#oficio').trigger('change'); });
    $('#loader-asignaturas-reload').click(function () { $('#oficio').trigger('change'); });
    function buscarAsignaturas () {
        if ($('#oficio').val() != "" && $('#modulo').val() != '') {
            $('#loader-asignaturas').show();
            $('#loader-asignaturas-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_modulo_curso.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer asignaturas",
                        oficio: $('#oficio').val(),
                        modulo: $('#modulo').val()
                    },
                    success: function (resultados) {
                        $('#loader-asignaturas').hide();

                        // CARGAMOS LAS CIUDADES DEL ESTADO SELECCIONADO
                        let dataAsignaturas = resultados.asignaturas;
                        if (dataAsignaturas) {
                            $('#contenedor_asignaturas').empty();

                            for (let i in dataAsignaturas) {
                                let contenido_asig = '';
                                contenido_asig += '<div class="d-flex align-items-center rounded w-100 p-1 mb-1 text-secondary font-weight-bold contenedor_select_asignatura">';
                                    contenido_asig += '<div style="width: 25px;" class="custom-control custom-checkbox mr-sm-2">';
                                        contenido_asig += '<input type="hidden" name="id_campo_asignatura[]" id="id-'+dataAsignaturas[i].codigo+'" class="custom-control-input id_campo_asignatura" value="0">';
                                        contenido_asig += '<input type="checkbox" name="campo_asignatura[]" id="'+dataAsignaturas[i].codigo+'" class="custom-control-input campo_asignatura" value="'+dataAsignaturas[i].codigo+'" data-id="'+dataAsignaturas[i].codigo+'">';
                                        contenido_asig += '<label class="custom-control-label" for="'+dataAsignaturas[i].codigo+'" data-id="'+dataAsignaturas[i].codigo+'"></label>';
                                    contenido_asig += '</div>';
                                    
                                    contenido_asig += '<div style="width: calc(100% - 25px);" class="d-flex align-items-center seleccionar_asignatura" data-id="'+dataAsignaturas[i].codigo+'">';
                                        contenido_asig += '<span style="width: 120px;">'+dataAsignaturas[i].codigo+'</span>';
                                        contenido_asig += '<span style="width: calc(100% - 120px);">'+dataAsignaturas[i].nombre+'</span>';
                                    contenido_asig += '</div>';
                                contenido_asig += '</div>';
                                $('#contenedor_asignaturas').append(contenido_asig);
                            }

                            // CUANDO DESMARQUE ALGUNA ASIGNATURA, TOMARA EL ID DE REGISTRO Y LO GUARDARA EN UN ARREGLO PARA SU POSTERIOR ELIMINACION
                            $('.campo_asignatura').click(function () {
                                let data_id = $(this).attr('data-id');
                                if ($('#id-'+data_id).val() != 0) {
                                    var i = window.eliminarAsignaturas.indexOf($('#id-'+data_id).val());
                                    if      (i !== -1) { window.eliminarAsignaturas.splice(i, 1); }
                                    else if (i === -1) { window.eliminarAsignaturas.push($('#id-'+data_id).val()); }
                                }
                            });

                            $('.seleccionar_asignatura').click(function () {
                                let data_id = $(this).attr('data-id');
                                if      ($('#'+data_id).prop('checked')) { $('#'+data_id).prop('checked', false); }
                                else    { $('#'+data_id).prop('checked', true); }

                                // CUANDO DESMARQUE ALGUNA ASIGNATURA, TOMARA EL ID DE REGISTRO Y LO GUARDARA EN UN ARREGLO PARA SU POSTERIOR ELIMINACION
                                if ($('#id-'+data_id).val() != 0) {
                                    var i = window.eliminarAsignaturas.indexOf($('#id-'+data_id).val());
                                    if      (i !== -1) { window.eliminarAsignaturas.splice(i, 1); }
                                    else if (i === -1) { window.eliminarAsignaturas.push($('#id-'+data_id).val()); }
                                }
                            });
                        } else {
                            let contenido_asig = '';
                            contenido_asig += '<div class="d-flex justify-content-center align-items-center h-100">';
                            contenido_asig += '<h5 class="font-weight-normal text-secondary text-center text-uppercase"><i class="fas fa-file-alt"></i> No hay asignaturas registradas</h5>';
                            contenido_asig += '</div>';
                            $('#contenedor_asignaturas').html(contenido_asig);
                        }

                        // CIUDAD, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.asignaturas != undefined) {
                            for (let i in window.asignaturas) {
                                $('#id-'+window.asignaturas[i].codigo_asignatura).val(window.asignaturas[i].codigo);
                                $('#'+window.asignaturas[i].codigo_asignatura).prop('checked', true);
                            }
                            delete window.asignaturas;
                            verificarParte1();
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

            let contenido_asig = '';
            contenido_asig += '<div class="d-flex justify-content-center align-items-center h-100">';
            contenido_asig += '<h5 class="font-weight-normal text-secondary text-center text-uppercase"><i class="fas fa-hand-pointer"></i> Seleccione un oficio y un módulo</h5>';
            contenido_asig += '</div>';
            $('#contenedor_asignaturas').html(contenido_asig);
        }
    }
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
