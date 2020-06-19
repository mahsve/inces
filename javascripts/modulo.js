$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Üa.,-- ])+$/;
    let validar_soloNumeros         =/^([0-9])+$/;
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
    let tipoEnvio           = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFICACION).
    let dataListado         = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    window.contenedor_asignaturas_vacio  = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary">Presione el botón <button type="button" class="btn btn-sm btn-info" disabled="true" style="height: 22px; padding: 3px 5px; vertical-align: top; cursor: default;"><i class="fas fa-search" style="font-size: 9px; vertical-align: top; padding-top: 3px;"></i></button> para buscar las asignaturas</h6>';
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
                url : url+'controllers/c_modulo.php',
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
                            let oficio_se  = dataListado.resultados[i].oficio;
                            if (dataListado.resultados[i].oficio == null) { oficio_se = 'Para todo los oficios.'}

                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Activo</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'I') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-times"></i> <span>Inactivo</span></span>'; }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="text-right py-2 px-1">'+cont+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+oficio_se+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+dataListado.resultados[i].asignaturas.length+' Asg.</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+dataListado.resultados[i].horas+' Hrs.</td>';
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
    function verificarParte1 () {
        tarjeta_1 = true; // CAMBIA A FALSO SI ALGUNO DE LOS CAMPOS ESTA MAL DEFINIDO.

        // VALIDAR NOMBRE DEL MODULO
        let nombre = $("#nombre").val();
        if (nombre != '') {
            if (nombre.match(validar_caracteresEspeciales)) {
                $("#nombre").css("background-color", colorb);
            } else {
                $("#nombre").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#nombre").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VALIDAR OFICIO, SI ES PARA SOLO UN OFICIO O PARA TODOS.
        if (!$("#repeticion_modulo").prop('checked')) {
            let oficio = $("#oficio").val();
            if (oficio != '') {
                $("#oficio").css("background-color", colorb);
            } else {
                $("#oficio").css("background-color", colorm);
                tarjeta_1 = false;
            }
        }
        // VALIDAR QUE HAYAN ASIGNATURAS AGREGADAS.
        if ($('#contenedor-asignatura').html() == window.contenedor_asignaturas_vacio) {
            $("#contenedor-asignatura").css("background-color", colorm);
            tarjeta_1 = false;
        } else {
            $("#contenedor-asignatura").css("background-color", '');

            // VALIDAMOS QUE NO ESTE VACIO, CONTENGA SOLO NUMEROS Y EL VALOR NO SEA CERO (0)
            // VALIDAR LAS HORAS DE CADA ASIGNATURA
            $('.horas_asignatura').each(function () {
                let horas_asignatura = $(this).val();
                if (horas_asignatura != '') {
                    if (horas_asignatura != 0) {
                        if (horas_asignatura.match(validar_soloNumeros)) {
                            $(this).css("background-color", colorb);
                        } else {
                            $(this).css("background-color", colorm);
                            tarjeta_1 = false;
                        }
                    } else {
                        $(this).css("background-color", colorm);
                        tarjeta_1 = false;
                    }
                } else {
                    $(this).css("background-color", colorm);
                    tarjeta_1 = false;
                }
            });
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $("#lista-modulos").sortable();
    // DESACTIVAR ENTER
    $('#nombre').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
    // SI SE REGISTRARA A TODOS LOS OFICIOS, SE DESHABILITA LA OPCION DE ELEGIR UN SOLO OFICIO.
    $('#repeticion_modulo').click(function () { if ($(this).prop('checked')) { $('#oficio').val(''); $('#oficio').attr('disabled', true); $('#oficio').css('background-color', ''); } else { $('#oficio').attr('disabled', false); } });
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        $('#contenedor-asignatura').html(window.contenedor_asignaturas_vacio);
        $('#oficio').attr('disabled', false);
        
        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';
        window.eliminar_asignaturas = [];
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    // MODAL BUSCAR LAS ASIGNATURAS
    $('#btn-buscar-asignatura').click(function () {
        document.form_buscar_asignatura.reset();
        $('#resultados-buscar-asignatura').empty();
        $('#resultados-buscar-asignatura').hide();
        $('#modal-buscar-asignaturas').modal();
        window.agregarNuevo = true;
    });
    $('#input-buscar-asignatura').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input-buscar-asignatura').keyup(function  () {
        // OCUTAMOS LA BARRA DE RESULTADOS.
        $('#resultados-buscar-asignatura').empty();
        $('#resultados-buscar-asignatura').hide();

        // SI HAY CONTENDIO EN EL INPUT SE VUELVE A MOSTRAR Y SE PROCEDE A BUSCAR
        if ($('#input-buscar-asignatura').val() != '') {
            $('#resultados-buscar-asignatura').show();
            $('#resultados-buscar-asignatura').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>');
        
            setTimeout(function () {
                $.ajax({
                    url : url+'controllers/c_modulo.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        opcion: 'Traer asignaturas',
                        buscar: $('#input-buscar-asignatura').val()
                    },
                    success: function (resultados) {
                        window.dataAsignaturas = resultados.asignaturas;
                        $('#resultados-buscar-asignatura').empty();
                        if (window.dataAsignaturas) {
                            for (let i in window.dataAsignaturas) {
                                // VERIFICAMOS QUE YA NO HAYA SIDO AGREGADO ANTERIORMENTE.
                                let agregar_asignatura = true;
                                $('.codigo_asignatura').each(function () { if ($(this).val() == window.dataAsignaturas[i].codigo) { agregar_asignatura = false; } });

                                // SI NO HA SIDO AGREGADO PROCEDE A AGREGARLO.
                                if (agregar_asignatura) {
                                    let contenido_div = '';
                                    contenido_div += '<p class="d-inline-block w-100 m-0 py-1 px-2 agregar-asignatura" data-posicion="'+i+'">'+window.dataAsignaturas[i].nombre+'</p>';
                                    $('#resultados-buscar-asignatura').append(contenido_div);
                                }
                            }
                            $('.agregar-asignatura').click(agregarAsignatura);

                            // SI NO SE AGREGO NINGUNO POR QUE YA ESTA AÑADIDO Y LA BARRA QUEDO VACIA SE PRECARGA EL MENSAJE: 'Sin resultados'
                            if ($('#resultados-buscar-asignatura').html() == '') {
                                $('#resultados-buscar-asignatura').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-times"></i> Sin resultados</span>');
                            }
                        } else {
                            $('#resultados-buscar-asignatura').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-times"></i> Sin resultados</span>');
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS MENSAJE DE "ERROR" EN EL CONTENEDOR.
                        let contenido_div = '';
                        contenido_div += '<span class="d-inline-block w-100 text-center text-danger py-1 px-2">';
                        contenido_div += '<i class="fas fa-ethernet"></i> [Error] No se pudo realizar la conexión.';
                        contenido_div += '<button type="button" id="btn-recargar-asignaturas" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                        contenido_div += '</span>';
                        $('#resultados-buscar-asignatura').html(contenido_div);
                        $('#btn-recargar-asignaturas').click(function () { $('#input-buscar-asignatura').trigger('keyup'); });
                        
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    function agregarAsignatura () {
        let posicion;
        if (window.agregarNuevo) {
            posicion = $(this).attr('data-posicion');
            $(this).remove();

            // SI NO SE AGREGO NINGUNO POR QUE YA ESTA AÑADIDO Y LA BARRA QUEDO VACIA SE PRECARGA EL MENSAJE: 'Sin resultados'
            if ($('#resultados-buscar-asignatura').html() == '') {
                $('#resultados-buscar-asignatura').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-times"></i> Sin resultados</span>');
            }
        }

        window.id_asignatura = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
        if ($('#contenedor-asignatura').html() == window.contenedor_asignaturas_vacio) { $('#contenedor-asignatura').empty(); }

        let contenedor_asignatura = '';
        contenedor_asignatura += '<div id="asignatura-'+window.id_asignatura+'" class="bg-info text-white rounded d-flex align-items-center justify-content-between w-100 my-1 px-2 py-1">';
            contenedor_asignatura += '<input type="hidden" name="codigo_registro[]" class="codigo_registro" value="0">';
            contenedor_asignatura += '<input type="hidden" name="codigo_asignatura[]" class="codigo_asignatura">';
            contenedor_asignatura += '<span class="nombre_asignatura" data-toggle="tooltip" data-placement="right" title=""></span>';

            contenedor_asignatura += '<div>';
                contenedor_asignatura += '<input type="text" name="horas_asignatura[]" class="horas_asignatura form-control form-control-sm text-right" placeholder="Horas" style="width: 70px; display: initial;" maxlength="4" autocomplete="off"/>';
                contenedor_asignatura += '<i class="fas fa-times eliminar-asignatura mr-1 ml-2" data-id-asignatura="'+window.id_asignatura+'" style="cursor: pointer;"></i>';
            contenedor_asignatura += '</div>';
        contenedor_asignatura += '</div>';
        $('#contenedor-asignatura').append(contenedor_asignatura);

        // LE AGREGAMOS FUNCIONALIDAD A LOS CAMPOS DE LA ASIGNATURA.
        $('#asignatura-'+window.id_asignatura+' .horas_asignatura').keypress(function (e) { if (!(e.keyCode >= 48 && e.keyCode <= 57)) { e.preventDefault(); } });
        $('#asignatura-'+window.id_asignatura+' .eliminar-asignatura').click(function () {
            let id_asignatura_s = $(this).attr('data-id-asignatura');

            // SI TIENE ALGUN CODIGO DE REGISTRO SE PROCEDE A GUARDAR EN UN ARREGLO PARA ELIMINARLO DEFINITIVO DE LA BD.
            if ($('#asignatura-'+id_asignatura_s+' .codigo_registro').val() != 0) { window.eliminar_asignaturas.push($('#asignatura-'+id_asignatura_s+' .codigo_registro').val()); }
            // SE REMUEVE EL COMPONENTE CON LOS DATOS DE LA ASIGNATURA DE LA VISTA.
            $('#asignatura-'+id_asignatura_s).remove();

            // SI EL CONTENEDOR QUEDA EN BLANDO VUELVA A MOSTRAR EL MENSAJE POR DEFECTO.
            if ($('#contenedor-asignatura').html() == '') { $('#contenedor-asignatura').html(window.contenedor_asignaturas_vacio); }
        });

        // SOLO SI ES UNA ASIGNATURA NUEVA SE LE AGREGARA ESTOS DATOS.
        if (window.agregarNuevo) {
            let nombre_asignatura = window.dataAsignaturas[posicion].nombre;
            
            // VERIFICAMOS QUE NO SOBREPASE LOS 35 CARACTERES Y SI ES ASI LO RECORTAMOS.
            if (window.dataAsignaturas[posicion].nombre.length > 35) {
                nombre_asignatura = window.dataAsignaturas[posicion].nombre.substr(0, 35);
                if (nombre_asignatura[nombre_asignatura.length - 1] == ' ') { nombre_asignatura = window.dataAsignaturas[posicion].nombre.substr(0, 34); }
                nombre_asignatura += '...';
            }

            $('#asignatura-'+window.id_asignatura+' .codigo_asignatura').val(window.dataAsignaturas[posicion].codigo);
            $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').html(nombre_asignatura);
            $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').attr('title', window.dataAsignaturas[posicion].nombre);
            $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').tooltip();
        }
    }
    // MODAL PARA ORDENAR LOS MODULOS EN LA FORMA CORRESPONDIENTE
    $('#btn-ordenar-modulos').click(function (e) {
        document.form_ordenar_modulos.reset();
        $('#lista-modulos').empty();
        $('#modal-ordenar-modulos').modal();
    });
    $('#select-modulos').change(function () {
        if ($('#select-modulos').val() != '') {
            $('#lista-modulos').html('<li class="text-secondary text-center border rounded p-2"><i class="fas fa-spinner fa-spin"></i>Cargando...</li>');

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_modulo.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        opcion: 'Traer modulos',
                        oficio: $('#select-modulos').val()
                    },
                    success: function (resultados){
                        $('#lista-modulos').empty();
                        
                        // CARGAMOS LOS MODULOS DEL OFICIO
                        let dataModulos = resultados.modulos;
                        if (dataModulos) {
                            for (var i = 0; i < dataModulos.length; i++) {
                                let contenido2 = '';
                                contenido2 += '<li class="nav-item d-flex align-items-center border rounded text-secondary bg-white mouse-move p-2 my-1">';
                                contenido2 += '<span class="numero-orden mr-1">'+(i + 1)+'.-</span><span>'+dataModulos[i].nombre+'</span>';
                                // contenido2 += '<i class="'+data[i].icono+' pr-2"></i>';
                                contenido2 += '<input type="hidden" name="codigo[]" value="'+dataModulos[i].codigo+'">';
                                contenido2 += '<input type="hidden" name="posicion[]" class="ordenar" value="'+dataModulos[i].orden+'">';
                                contenido2 += '</li>';
                                $('#lista-modulos').append(contenido2);
                            }
                        } else {
                            $('#lista-modulos').html('<li class="text-secondary text-center border rounded p-2"><i class="fas fa-times"></i> Sin resultados</li>');
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS MENSAJE DE "ERROR" EN EL CONTENEDOR.
                        let contenido_div = '';
                        contenido_div += '<span class="d-inline-block w-100 text-center text-danger py-1 px-2">';
                        contenido_div += '<i class="fas fa-ethernet"></i> [Error] No se pudo realizar la conexión.';
                        contenido_div += '<button type="button" id="btn-recargar-modulos" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                        contenido_div += '</span>';
                        $('#lista-modulos').html(contenido_div);
                        $('#btn-recargar-modulos').click(function () { $('#select-modulos').trigger('change'); });
                        
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        } else {
            $('#lista-modulos').empty();
        }
    });
    empezarOrdernarPosicion();
    function empezarOrdernarPosicion () {
        setInterval(() => {
            let cont = 1;
            $('.ordenar').each(function () {
                $($('.numero-orden')[cont - 1]).html(cont+'.-');
                $(this).val(cont); cont++;
            });
        }, 400);
    }
    $('#btn-guardar-orden').click(function (e) {
        e.preventDefault();
        if ($('#select-modulos').val() != '') {
            let data = $("#form_ordenar_modulos").serializeArray();
            data.push({ name: 'opcion', value: 'Guardar orden modulos' });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario_orden_modulos').attr('disabled', true);
            $('#btn-guardar-orden i.fa-save').addClass('fa-spin');
            $('#btn-guardar-orden span').html('Guardando...');
            $('#contenedor-mensaje-modulos').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_modulo.php',
                    type: 'POST',
                    data: data,
                    success: function (resultados) {
                        let color_alerta = '';
                        let icono_alerta = '';

                        if (resultados == 'Modificado exitosamente') {
                            $('#modal-ordenar-modulos').modal('hide');

                            // MENSAJE AL USUARIO SI SE MODIFICO CORRECTAMENTE.
                            color_alerta = 'alert-success';
                            icono_alerta = '<i class="fas fa-check"></i>';
                        } else if (resultados == 'Error al modificar') {
                            // MENSAJE AL USUARIO SI HUBO ALGUN ERROR
                            color_alerta = 'alert-danger';
                            icono_alerta = '<i class="fas fa-times"></i>';
                        }

                        // CARGAMOS EL MENSAJE EN EL CONTENEDOR CORRESPONDIENTE.
                        let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3" role="alert">';
                        contenedor_mensaje += icono_alerta+' '+resultados;
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';

                        if (resultados == 'Modificado exitosamente') {
                            $('#contenedor-mensaje').html(contenedor_mensaje);
                        } else {
                            $('#contenedor-mensaje-modulos').html(contenedor_mensaje);
                        }
                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_orden_modulos').attr('disabled', false);
                        $('#btn-guardar-orden i.fa-save').removeClass('fa-spin');
                        $('#btn-guardar-orden span').html('Guardar');
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
                        $('#contenedor-mensaje-modulos').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_orden_modulos').attr('disabled', false);
                        $('#btn-guardar-orden i.fa-save').removeClass('fa-spin');
                        $('#btn-guardar-orden span').html('Guardar');

                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro () {
        let posicion = $(this).attr('data-posicion');

        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Modificar');
        $('.campos_formularios').css('background-color', colorn);
        $('#contenedor-asignatura').html(window.contenedor_asignaturas_vacio);
        $('#oficio').attr('disabled', false);

        document.formulario.reset();
        tipoEnvio       = 'Modificar';
        window.codigo   = dataListado.resultados[posicion].codigo;
        window.agregarNuevo = false;
        window.eliminar_asignaturas = [];
        $('#nombre').val(dataListado.resultados[posicion].nombre);
        if (dataListado.resultados[posicion].codigo_oficio == null) { $('#repeticion_modulo').trigger('click'); }
        else { $('#oficio').val(dataListado.resultados[posicion].codigo_oficio); }

        let arreglo_asig = dataListado.resultados[posicion].asignaturas;
        for (let i = 0; i < arreglo_asig.length; i++) {
            // LLAMAMOS LA FUNCION PARA AGREGAR LAS ASIGNATURAS REGISTRADAS
            agregarAsignatura();

            let nombre_asignatura = arreglo_asig[i].nombre;
            // VERIFICAMOS QUE NO SOBREPASE LOS 35 CARACTERES Y SI ES ASI LO RECORTAMOS.
            if (arreglo_asig[i].nombre.length > 35) {
                nombre_asignatura = arreglo_asig[i].nombre.substr(0, 35);
                if (nombre_asignatura[nombre_asignatura.length - 1] == ' ') { nombre_asignatura = arreglo_asig[i].nombre.substr(0, 34); }
                nombre_asignatura += '...';
            }

            $('#asignatura-'+window.id_asignatura+' .codigo_registro').val(arreglo_asig[i].codigo);
            $('#asignatura-'+window.id_asignatura+' .codigo_asignatura').val(arreglo_asig[i].codigo_asignatura);
            $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').html(nombre_asignatura);
            $('#asignatura-'+window.id_asignatura+' .horas_asignatura').val(arreglo_asig[i].horas);
            $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').attr('title', arreglo_asig[i].nombre);
            $('#asignatura-'+window.id_asignatura+' .nombre_asignatura').tooltip();
        }
        verificarParte1();
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();

        // SE VERIFICA QUE TODOS LOS CAMPOS ESTEN DEFINIDOS CORRECTAMENTE.
        if (tarjeta_1) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion',         value: tipoEnvio });
            data.push({ name: 'codigo',         value: window.codigo });
            data.push({ name: 'eliminar_asign', value: JSON.stringify(window.eliminar_asignaturas) });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario').attr('disabled', true);
            $('#guardar-datos i.fa-save').addClass('fa-spin');
            $('#guardar-datos span').html('Guardando...');

            // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
            $('#contenedor-mensaje').empty();
            $('#contenedor-mensaje2').empty();
            
            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_modulo.php',
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
                url : url+'controllers/c_modulo.php',
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
            url: url + "controllers/c_modulo.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                // CARGAMOS LAS OCUPACIONES
                let dataOficios = resultados.oficio;
                if (dataOficios) {
                    for (let i in dataOficios) {
                        $("#oficio").append('<option value="'+dataOficios[i].codigo +'">'+dataOficios[i].nombre+"</option>");
                        $("#select-modulos").append('<option value="'+dataOficios[i].codigo +'">'+dataOficios[i].nombre+"</option>");
                    }
                } else {
                    $("#oficio").html('<option value="">No hay oficios</option>');
                    $("#select-modulos").html('<option value="">No hay oficios</option>');
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