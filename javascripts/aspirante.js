$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Ü.,-- ])+$/;
    let validar_caracteresEspeciales1=/^([a-zá-úä-üA-ZÁ-úÄ-Ü. ])+$/;
    let validar_caracteresEspeciales2=/^([a-zá-úä-üA-ZÁ-úÄ-Ü0-9.,--# ])+$/;
    let validar_soloNumeros         =/^([0-9])+$/;
    let validar_correoElectronico   =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1, tarjeta_2;
    let vd_ocupacion;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // VARIABLES NECESARIAS PARA GUARDAR LOS DATOS CONSULTADOS
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR UNA FECHA TEMPORAL EN FAMILIAR.
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
                url : url+'controllers/c_oficio.php',
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
                            let horas = 0;
                            if (dataListado.resultados[i].horas != null) { horas = dataListado.resultados[i].horas; }

                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Activo</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'I') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-times"></i> <span>Inactivo</span></span>'; }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="py-2 px-1 text-right">'+dataListado.resultados[i].codigo+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+dataListado.resultados[i].asignaturas+' Asg.</td>';
                            contenido_tabla += '<td class="py-2 px-1 text-center">'+horas+' Hrs.</td>';
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
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay oficios registrados.</span>';
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
    // FUNCION PARA VERIFICAR LA CEDULA Y QUE NO ESTE REGISTRADO
    let validarCedula = false;
    $('#nacionalidad').change(function () { $('#cedula').trigger('blur'); });
    $('#loader-cedula-reload').click(function () { $('#cedula').trigger('blur'); });
    $('#cedula').blur(function () {
        validarCedula = false;
        $('#spinner-cedula').hide();
        $('#loader-cedula-reload').hide();
        $('#spinner-cedula-confirm').hide();
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger fa-exclamation-triangle text-warning');

        if($('#nacionalidad').val() != '') {
            if ($('#cedula').val() != '') {
                if ($('#cedula').val().match(validar_soloNumeros) && $('#cedula').val().length >= 7) {
                    if (window.nacionalidad != $('#nacionalidad').val() || window.cedula != $('#cedula').val()) {
                        $('#spinner-cedula').show();
                        
                        setTimeout(() => {
                            $.ajax({
                                url : url+'controllers/c_aspirante.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    opcion      : 'Verificar cedula',
                                    nacionalidad: $('#nacionalidad').val(),
                                    cedula      : $('#cedula').val()
                                },
                                success: function (resultados) {
                                    $('#spinner-cedula').hide();
                                    if (!resultados) {
                                        $('#spinner-cedula-confirm').addClass('fa-times text-danger');
                                    } else {
                                        validarCedula = true;
                                        $('#spinner-cedula-confirm').addClass('fa-check text-success');
                                    }
                                    $('#spinner-cedula-confirm').show(200);
                                },
                                error: function (errorConsulta) {
                                    // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                                    $('#spinner-cedula').hide();
                                    $('#loader-cedula-reload').show();
            
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
                        validarCedula = true;
                        $('#spinner-cedula-confirm').addClass('fa-check text-success');
                        $('#spinner-cedula-confirm').show();
                    }
                } else {
                    // MOSTRAMOS ICONO DE ERROR
                    $('#spinner-cedula-confirm').show();
                    $('#spinner-cedula-confirm').addClass('fa-times text-danger');

                    // MENSAJE DE ERROR, RIF INCORRECTO.
                    let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                    contenedor_mensaje += '<i class="fas fa-times"></i> <span style="font-weight: 500;">Debe escribir la cédula correctamente, debe tener al menos 7 números.</span>';
                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += '</button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje2').html(contenedor_mensaje);

                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                }
            }
        }
    });
    // VERIFICAR LOS CAMPOS DEL FORMULARIO
    function verificarParte1 () {
        tarjeta_1 = true;
        // VERIFICAR EL CAMPO DEL NOMBRE DE LA OCUPACION.
        let nombre = $("#nombre").val();
        if (nombre != '') {
            if(nombre.match(validar_caracteresEspeciales)){
                $("#nombre").css("background-color", colorb);
            }else{
                $("#nombre").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#nombre").css("background-color", colorm);
            tarjeta_1 = false;
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
    $('#fecha').change(function () { obtenerEdad(); });
    $('#fecha_n').change(function (){ obtenerEdad(); });
    function obtenerEdad () { $('#edad').val(calcularEdad($('#fecha').val(), $('#fecha_n').val())); }
    $('.solo-numeros').keypress(function (e) { if (!(e.keyCode >= 48 && e.keyCode <= 57)) { e.preventDefault(); } });
    $('#nombre_ocupacion').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
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
        tipoEnvio           = 'Registrar';
        window.nacionalidad = '';
        window.cedula       = '';

        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#municipio').html('<option value="">Elija un estado</option>');
        $('#parroquia').html('<option value="">Elija un municipio</option>');
        $('#fecha').val(fecha);
        $('#edad').val(0);
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
        $('#pills-datos-ciudadano-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AGREGAR INFORMACION AL FORMULARIO DINAMICAMENTE.
    // REGISTRAR NUEVA OCUPACION
    $('#btn-ocupacion-aprendiz').click(function (e) {
        e.preventDefault();
        modalOcupacion();
        window.formulario_ocupacion = 'B'; // PARA EL FORMULARIO DE APRENDIZ
    });
    function modalOcupacion () {
        document.form_registrar_ocupacion.reset();
        $(".campos_formularios_ocupacion").css('background-color', '');
        $('.botones_formulario_ocupacion').attr('disabled', false);
        $('#btn-registrar-ocupacion i.fa-save').removeClass('fa-spin');
        $('#btn-registrar-ocupacion span').html('Guardar');
        $('#contenedor-mensaje-ocupacion').empty();
        $('#modal-ocupacion').modal();
    }
    function validar_ocupacion () {
        vd_ocupacion = true;
        let nombre_ocupacion = $("#nombre_ocupacion").val();
        if (nombre_ocupacion != '') {
            if (nombre_ocupacion.match(validar_caracteresEspeciales)) {
                $("#nombre_ocupacion").css("background-color", colorb);
            } else {
                $("#nombre_ocupacion").css("background-color", colorm);
                vd_ocupacion = false;
            }
        } else {
            $("#nombre_ocupacion").css("background-color", colorm);
            vd_ocupacion = false;
        }
    }
    $('#btn-registrar-ocupacion').click(function (e) {
        e.preventDefault();
        validar_ocupacion();

        if (vd_ocupacion) {
            let data = $("#form_registrar_ocupacion").serializeArray();
            data.push({ name: 'opcion', value: 'Registrar ocupacion' });
            data.push({ name: 'formulario_ocupacion', value: window.formulario_ocupacion });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario_ocupacion').attr('disabled', true);
            $('#btn-registrar-ocupacion i.fa-save').addClass('fa-spin');
            $('#btn-registrar-ocupacion span').html('Guardando...');
            $('#contenedor-mensaje-ocupacion').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_aspirante.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: data,
                    success: function (resultados) {
                        let color_alerta = '';
                        let icono_alerta = '';

                        if (resultados == 'Ya está registrado') {
                            // MENSAJE AL USUARIO SI YA ESTA REGISTRADO.
                            color_alerta = 'alert-warning';
                            icono_alerta = '<i class="fas fa-exclamation-circle"></i>';
                        } else if (resultados == 'Registro fallido') {
                            // MENSAJE AL USUARIO SI HUBO ALGUN ERROR
                            color_alerta = 'alert-danger';
                            icono_alerta = '<i class="fas fa-times"></i>';
                        } else {
                            // MENSAJE AL USUARIO SI HUBO ALGUN ERROR
                            color_alerta = 'alert-success';
                            icono_alerta = '<i class="fas fa-check"></i>';

                            // CARGAR LAS OCUPACIONES DEL APRENDIS
                            let valor_anterior = $("#ocupacion").val();
                            $("#ocupacion").html('<option value="">Elija una opción</option>');
                            let dataOcupaciones = resultados.ocupaciones;
                            if (dataOcupaciones) {
                                for (let i in dataOcupaciones) {
                                    $("#ocupacion").append('<option value="'+dataOcupaciones[i].codigo +'">'+dataOcupaciones[i].nombre+"</option>");
                                }
                            } else {
                                $("#ocupacion").html('<option value="">No hay ocupaciones</option>');
                            }
                            $('#ocupacion').val(valor_anterior);
                            
                            // CERRAMOS LA VENTANA.
                            $('#modal-ocupacion').modal('hide');
                            resultados = 'Registro exitoso';
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
                        if (resultados == 'Ya está registrado' || resultados == 'Registro fallido') { $('#contenedor-mensaje-ocupacion').html(contenedor_mensaje); }
                        else { $('#contenedor-mensaje2').html(contenedor_mensaje); }

                        // OCULTAMOS EL MENSAJE DESPUES DE 5 SEGUNDOS.
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_ocupacion').attr('disabled', false);
                        $('#btn-registrar-ocupacion i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-ocupacion span').html('Guardar');
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
                        $('#contenedor-mensaje-ocupacion').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_ocupacion').attr('disabled', false);
                        $('#btn-registrar-ocupacion i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-ocupacion span').html('Guardar');

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
    // FUNCIONES EXTRAS DE LOS CAMPOS.
    // CONSULTAR LAS CIUDADES Y MUNICIPIOS DEL ESTADO
    $('#loader-ciudad-reload').click(function () { $('#estado').trigger('change'); });
    $('#estado').change(function () {
        if ($(this).val() != "") {
            $('#loader-ciudad').show();
            $('#loader-ciudad-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_aspirante.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer divisiones",
                        estado: $(this).val()
                    },
                    success: function (resultados) {
                        // OCULTAR ICONO DE CARGA
                        $('#loader-ciudad').hide();

                        // CARGAMOS LA CUIDADES
                        let dataCiudades = resultados.ciudades;
                        if (dataCiudades) {
                            $("#ciudad").html('<option value="">Elija una opción</option>');
                            for (let i in dataCiudades) {
                                $("#ciudad").append('<option value="'+dataCiudades[i].codigo +'">'+dataCiudades[i].nombre+"</option>");
                            }
                        } else {
                            $("#ciudad").html('<option value="">No hay ciudades</option>');
                        }

                        // CARGAMOS LOS MUNICIPIOS
                        let dataMunicipios = resultados.municipios;
                        if (dataMunicipios) {
                            $("#municipio").html('<option value="">Elija una opción</option>');
                            for (let i in dataMunicipios) {
                                $("#municipio").append('<option value="'+dataMunicipios[i].codigo +'">'+dataMunicipios[i].nombre+"</option>");
                            }
                        } else {
                            $("#municipio").html('<option value="">No hay municipios</option>');
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                        $('#loader-ciudad').hide();
                        $('#loader-ciudad-reload').show();

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
            $('#ciudad').html('<option value="">Elija un estado</option>');
            $('#municipio').html('<option value="">Elija un estado</option>');
            $('#parroquia').html('<option value="">Elija un municipio</option>');
        }
    });
    // CONSUTAR LAS PARROQUIAS DEL MUNICIPIO
    $('#loader-parroquia-reload').click(function () { $('#municipio').trigger('change'); });
    $('#municipio').change(function () {
        if ($(this).val() != '') {
            $('#loader-parroquia').show();
            $('#loader-parroquia-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_aspirante.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        opcion: 'Traer parroquias',
                        municipio: $('#municipio').val()
                    },
                    success: function (resultados) {
                        // OCULTAR ICONO DE CARGA
                        $('#loader-parroquia').hide();

                        // CARGAR LAS OCUPACIONES DEL APRENDIS
                        let dataParroquia = resultados.parroquias;
                        if (dataParroquia) {
                            $('#parroquia').html('<option value="">Elija una opción</option>');
                            for (let i in dataParroquia) {
                                $("#parroquia").append('<option value="'+dataParroquia[i].codigo +'">'+dataParroquia[i].nombre+"</option>");
                            }
                        } else {
                            $('#parroquia').html('<option value="">No hay parroquias</option>');
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                        $('#loader-parroquia').hide();
                        $('#loader-parroquia-reload').show();

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
            $('#parroquia').html('<option value="">Elija un municipio</option>');
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

        document.formulario.reset();
        tipoEnvio       = 'Modificar';
        window.codigo   = dataListado.resultados[posicion].codigo;
        $('#nombre').val(dataListado.resultados[posicion].nombre);

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
                    url : url+'controllers/c_oficio.php',
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
                url : url+'controllers/c_oficio.php',
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
            url: url + "controllers/c_aspirante.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                // CARGAMOS LA FECHA ACTUAL EN UNA VARIABLE.
                fecha = resultados.fecha;

                // CARGAMOS LAS OCUPACIONES
                let dataOcupaciones = resultados.ocupaciones;
                if (dataOcupaciones) {
                    for (let i in dataOcupaciones) {
                        $("#ocupacion").append('<option value="'+dataOcupaciones[i].codigo +'">'+dataOcupaciones[i].nombre+"</option>");
                    }
                } else {
                    $("#ocupacion").html('<option value="">No hay ocupaciones</option>');
                }

                // CARGAMOS LOS OFICIOS
                let dataOficios = resultados.oficios;
                if (dataOficios) {
                    for (let i in dataOficios) {
                        $("#oficio").append('<option value="'+dataOficios[i].codigo +'">'+dataOficios[i].nombre+"</option>");
                    }
                } else {
                    $("#oficio").html('<option value="">No hay oficios</option>');
                }

                // CARGAMOS LOS ESTADOS DEL PAIS
                let dataEstados = resultados.estados;
                if (dataEstados) {
                    for (let i in dataEstados) {
                        $("#estado").append('<option value="'+dataEstados[i].codigo +'">'+dataEstados[i].nombre+"</option>");
                    }
                } else {
                    $("#estado").html('<option value="">No hay estados</option>');
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