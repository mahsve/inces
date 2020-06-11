$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_rifEmpresa          = new RegExp("^([VEJPG]{1})([-])([0-9]{9})$");
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Ü.,-- ])+$/;
    let validar_caracteresEspeciales1=/^([a-zá-úä-üA-ZÁ-úÄ-Ü. ])+$/;
    let validar_caracteresEspeciales2=/^([a-zá-úä-üA-ZÁ-úÄ-Ü0-9.,--# ])+$/;
    let validar_soloNumeros         =/^([0-9])+$/;
    let validar_correoElectronico   =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
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
                url : url+'controllers/c_empresa.php',
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
                            contenido_tabla += '<td class="py-2 pl-1 pr-1">'+dataListado.resultados[i].rif+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nil+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].razon_social+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].actividad_economica+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].telefono1+'</td>';
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
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay empresas registradas.</span>';
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
        // VERIFICAR EL CAMPO DEL CODIGO DEL MODULO.
        let rif = $("#rif").val();
        if (rif != "") {
            if (validar_rifEmpresa.test($("#rif").val())) {
                if (validarRif) {
                    $("#rif").css("background-color", colorb);
                } else {
                    $("#rif").css("background-color", colorm);
                    tarjeta_1 = false;
                }
            } else {
                $("#rif").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#rif").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE NIL SOCIAL DE LA EMPRESA
        let nil = $("#nil").val();
        if (nil != "") {
            if (nil.match(validar_soloNumeros)) {
                $("#nil").css("background-color", colorb);
            } else {
                $("#nil").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#nil").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE LA RAZON SOCIAL DE LA EMPRESA
        let razon_social = $("#razon_social").val();
        if (razon_social != "") {
            if (razon_social.match(validar_caracteresEspeciales)) {
                $("#razon_social").css("background-color", colorb);
            } else {
                $("#razon_social").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#razon_social").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE LA ACTIVIDAD ECONOMICA DE LA EMPRESA
        let actividad_economica = $("#actividad_economica").val();
        if (actividad_economica != "") {
            $("#actividad_economica").css("background-color", colorb);
        } else {
            $("#actividad_economica").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DEL CODIGO APORTANTE DE LA EMPRESA
        let codigo_aportante = $("#codigo_aportante").val();
        if (codigo_aportante != "") {
            if (codigo_aportante.match(validar_soloNumeros)) {
                $("#codigo_aportante").css("background-color", colorb);
            } else {
                $("#codigo_aportante").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#codigo_aportante").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DE LA EMPRESA (TELEFONO 1)
        let telefono_1 = $("#telefono_1").val();
        if (telefono_1 != "") {
            if (telefono_1.match(validar_soloNumeros)) {
                $("#telefono_1").css("background-color", colorb);
            } else {
                $("#telefono_1").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#telefono_1").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DE LA EMPRESA (TELEFONO 2, OPCIONAL)
        let telefono_2 = $("#telefono_2").val();
        if (telefono_2 != "") {
            if (telefono_2.match(validar_soloNumeros)) {
                $("#telefono_2").css("background-color", colorb);
            } else {
                $("#telefono_2").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#telefono_2").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE CORREO DE LA EMPRESA (OPCIONAL)
        let correo = $("#correo").val();
        if (correo != "") {
            if (correo.match(validar_correoElectronico)) {
                $("#correo").css("background-color", colorb);
            } else {
                $("#correo").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#correo").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DEL ESTADO DE LA EMPRESA
        let estado = $("#estado").val();
        if (estado != "") {
            $("#estado").css("background-color", colorb);
        } else {
            $("#estado").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE LA CIUDAD DE LA EMPRESA
        let ciudad = $("#ciudad").val();
        if (ciudad != "") {
            $("#ciudad").css("background-color", colorb);
        } else {
            $("#ciudad").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE DIRECCION DE LA EMPRESA
        let direccion = $("#direccion").val();
        if (direccion != "") {
            if (direccion.match(validar_caracteresEspeciales2)) {
                $("#direccion").css("background-color", colorb);
            } else {
                $("#direccion").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#direccion").css("background-color", colorm);
            tarjeta_1 = false;
        }

        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_1) {
            $('#icon-empresa').hide();
        } else {
            $('#icon-empresa').show();
        }
    }
    function verificarParte2 () {
        tarjeta_2 = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD DEL CONTACTO
        let nacionalidad = $("#nacionalidad").val();
        if (nacionalidad != "") {
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE CEDULA DEL CONTACTO
        let cedula = $("#cedula").val();
        if (cedula != "") {
            if (cedula.match(validar_soloNumeros) && cedula.length >= 7) {
                if (validarCedula) {
                    $("#cedula").css("background-color", colorb);
                } else {
                    $("#cedula").css("background-color", colorm);
                    tarjeta_2 = false;
                }
            } else {
                $("#cedula").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#cedula").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE 1 DEL CONTACTO
        let nombre_1 = $("#nombre_1").val();
        if (nombre_1 != "") {
            if (nombre_1.match(validar_caracteresEspeciales1)) {
                $("#nombre_1").css("background-color", colorb);
            } else {
                $("#nombre_1").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#nombre_1").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE 2 DEL CONTACTO
        let nombre_2 = $("#nombre_2").val();
        if (nombre_2 != "") {
            if (nombre_2.match(validar_caracteresEspeciales1)) {
                $("#nombre_2").css("background-color", colorb);
            } else {
                $("#nombre_2").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#nombre_2").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL APELLIDO 1 DEL CONTACTO
        let apellido_1 = $("#apellido_1").val();
        if (apellido_1 != "") {
            if (apellido_1.match(validar_caracteresEspeciales1)) {
                $("#apellido_1").css("background-color", colorb);
            } else {
                $("#apellido_1").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#apellido_1").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL APELLIDO 2 DEL CONTACTO
        let apellido_2 = $("#apellido_2").val();
        if (apellido_2 != "") {
            if (apellido_2.match(validar_caracteresEspeciales1)) {
                $("#apellido_2").css("background-color", colorb);
            } else {
                $("#apellido_2").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#apellido_2").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE SEXO
        let sexo = $("#sexo").val();
        if (sexo != "") {
            $("#sexo").css("background-color", colorb);
        } else {
            $("#sexo").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DEL CONTACTO (TELEFONO 1)
        let telefono_1_c = $("#telefono_1_c").val();
        if (telefono_1_c != "") {
            if (telefono_1_c.match(validar_soloNumeros)) {
                $("#telefono_1_c").css("background-color", colorb);
            } else {
                $("#telefono_1_c").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#telefono_1_c").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DEL CONTACTO (TELEFONO 2, OPCIONAL)
        let telefono_2_c = $("#telefono_2_c").val();
        if (telefono_2_c != "") {
            if (telefono_2_c.match(validar_soloNumeros)) {
                $("#telefono_2_c").css("background-color", colorb);
            } else {
                $("#telefono_2_c").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#telefono_2_c").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE CORREO DEL CONTACTO (OPCIONAL)
        let correo_c = $("#correo_c").val();
        if (correo_c != "") {
            if (correo_c.match(validar_correoElectronico)) {
                $("#correo_c").css("background-color", colorb);
            } else {
                $("#correo_c").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#correo_c").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DEL ESTADO DEL CONTACTO
        let estado_c = $("#estado_c").val();
        if (estado_c != "") {
            $("#estado_c").css("background-color", colorb);
        } else {
            $("#estado_c").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE LA CIUDAD DEL CONTACTO
        let ciudad_c = $("#ciudad_c").val();
        if (ciudad_c != "") {
            $("#ciudad_c").css("background-color", colorb);
        } else {
            $("#ciudad_c").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE DIRECCION DEL CONTACTO (OPCIONAL)
        let direccion_c = $("#direccion_c").val();
        if (direccion_c != "") {
            if (direccion_c.match(validar_caracteresEspeciales2)) {
                $("#direccion_c").css("background-color", colorb);
            } else {
                $("#direccion_c").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#direccion_c").css("background-color", colorn);
        }

        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_2) {
            $('#icon-contacto').hide();
        } else {
            $('#icon-contacto').show();
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');
        $('.ocultar-iconos').hide();
        $('.btn-recargar').hide();
        $('.icon-alert').hide();
        
        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#ciudad_c').html('<option value="">Elija un estado</option>');

        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.rif      = '';
        window.nacionalidad = '';
        window.cedula   = '';
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
        $('#pills-datos-empresa-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // VALIDACIONES DE REGISTROS A BASE DE DATOS.
    // VERIFICAR RIF
    let validarRif = false;
    $('#rif').keyup(function () { let letraMayus = $('#rif').val().toUpperCase(); $('#rif').val(letraMayus); });
    $('#loader-rif-reload').click(function () { $('#rif').trigger('blur'); });
    $('#rif').blur(function () {
        validarRif = false;
        $('#spinner-rif').hide();
        $('#loader-rif-reload').hide();
        $('#spinner-rif-confirm').hide();
        $('#spinner-rif-confirm').removeClass('fa-check text-success fa-times text-danger');

        if ($('#rif').val() != '') {
            let parametrosRIF = new RegExp("^([VEJPG]{1})([-])([0-9]{9})$");
            if (parametrosRIF.test($("#rif").val())) {
                if (window.rif != $('#rif').val()) {
                    $('#spinner-rif').show();

                    setTimeout(() => {
                        $.ajax({
                            url : url+'controllers/c_empresa.php',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                opcion  : 'Verificar RIF',
                                rif     : $('#rif').val()
                            },
                            success: function (resultados) {
                                $('#spinner-rif').hide();
                                let dataConfirmar = resultados;
                                if      (dataConfirmar == 0) { validarRif = true; $('#spinner-rif-confirm').addClass('fa-check text-success'); }
                                else if (dataConfirmar != 0) {
                                    validarRif = false; $('#spinner-rif-confirm').addClass('fa-times text-danger');

                                    // MENSAJE DE ERROR DE CONEXION.
                                    let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                                    let contenedor_mensaje = '';
                                    contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                                    contenedor_mensaje += '<i class="fas fa-industry"></i> <span style="font-weight: 500;">Esta empresa ya está registrada.</span>';
                                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                                    contenedor_mensaje += '</button>';
                                    contenedor_mensaje += '</div>';
                                    $('#contenedor-mensaje2').html(contenedor_mensaje);
            
                                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                                    setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                                }
                                $('#spinner-rif-confirm').show();
                            },
                            error: function (errorConsulta) {
                                // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                                $('#spinner-rif').hide();
                                $('#loader-rif-reload').show();
        
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
                    validarRif = true;
                    $('#spinner-rif-confirm').addClass('fa-check text-success');
                    $('#spinner-rif-confirm').show();
                }
            } else {
                // MOSTRAMOS ICONO DE ERROR
                $('#spinner-rif-confirm').show();
                $('#spinner-rif-confirm').addClass('fa-times text-danger');

                // MENSAJE DE ERROR, RIF INCORRECTO.
                let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                let contenedor_mensaje = '';
                contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                contenedor_mensaje += '<i class="fas fa-times"></i> <span style="font-weight: 500;">Debe escribir el RIF correctamente, debe empezar por una letra (J), luego guión (-), seguido de nueve números. Ej: J-412488252</span>';
                contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                contenedor_mensaje += '</button>';
                contenedor_mensaje += '</div>';
                $('#contenedor-mensaje2').html(contenedor_mensaje);

                // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
            }
        }
    });
    // VERIFICAR CEDULA.
    let validarCedula = false;
    $('#nacionalidad').change(function () { $('#cedula').trigger('blur'); });
    $('#loader-cedula-reload').click(function () { $('#cedula').trigger('blur'); });
    $('#cedula').blur(function () {
        validarCedula = false;
        $('#spinner-cedula').hide();
        $('#loader-cedula-reload').hide();
        $('#spinner-cedula-confirm').hide();
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger');

        if($('#nacionalidad').val() != '') {
            if ($('#cedula').val() != '') {
                if ($('#cedula').val().match(validar_soloNumeros) && $('#cedula').val().length >= 7) {
                    if (window.nacionalidad != $('#nacionalidad').val() || window.cedula != $('#cedula').val()) {
                        $('#spinner-cedula').show();
                        
                        setTimeout(() => {
                            $.ajax({
                                url : url+'controllers/c_empresa.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    opcion      : 'Verificar cedula',
                                    nacionalidad: $('#nacionalidad').val(),
                                    cedula      : $('#cedula').val()
                                },
                                success: function (resultados) {
                                    $('#spinner-cedula').hide();

                                    let dataConfirmar = resultados;
                                    if (dataConfirmar) {
                                        swal({
                                            title: "Ya se encuetra registrada",
                                            text: "Esta persona ya está registrada,\n¿Quiere agregarla como contacto de está empresa?",
                                            icon: "info",
                                            buttons: true,
                                            dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                            if (willDelete) {
                                                validarCedula = true;
                                                window.registrar_cont = 'no';
                                                $('#spinner-cedula-confirm').addClass('fa-check text-success');
    
                                                window.nacionalidad = dataConfirmar.nacionalidad;
                                                window.cedula = dataConfirmar.cedula;
                                                $('#nacionalidad').val(dataConfirmar.nacionalidad);
                                                $('#cedula').val(dataConfirmar.cedula);
                                                $('#nombre_1').val(dataConfirmar.nombre1);
                                                $('#nombre_2').val(dataConfirmar.nombre2);
                                                $('#apellido_1').val(dataConfirmar.apellido1);
                                                $('#apellido_2').val(dataConfirmar.apellido2);
                                                $('#sexo').val(dataConfirmar.sexo);
                                                $('#estado_c').val(dataConfirmar.codigo_estado);
                                                window.valor_ciudad_c = dataConfirmar.codigo_ciudad;
                                                $('#estado_c').trigger('change');
                                                $('#telefono_1_c').val(dataConfirmar.telefono1);
                                                $('#telefono_2_c').val(dataConfirmar.telefono2);
                                                $('#correo_c').val(dataConfirmar.correo);
                                                $('#direccion_c').val(dataConfirmar.direccion);
    
                                                window.busquedad2 = true;
                                            } else {
                                                validarCedula = false;
                                                window.registrar_cont = 'no';
                                                $('#spinner-cedula-confirm').addClass('fa-times text-danger');
                                            }
                                        });
                                    } else {
                                        validarCedula = true;
                                        window.registrar_cont = 'si';
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
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // FUNCIONES EXTRAS DE LOS CAMPOS.
    $('.solo-numeros').keypress(function (e) { if (!(e.keyCode >= 48 && e.keyCode <= 57)) { e.preventDefault(); } });
    $('#estado').change(buscarCiudades);
    $('#loader-ciudad-reload').click(function () { $('#estado').trigger('change'); });
    $('#estado_c').change(buscarCiudades);
    $('#loader-ciudad_c-reload').click(function () { $('#estado_c').trigger('change'); });
    function buscarCiudades () {
        let campo_ciudad = '';
        if      ($(this).attr('name') == 'estado')      { campo_ciudad = 'ciudad'; }
        else if ($(this).attr('name') == 'estado_c')    { campo_ciudad = 'ciudad_c'; }

        if ($(this).val() != "") {
            $('#loader-'+campo_ciudad).show();
            $('#loader-'+campo_ciudad+'-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_empresa.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer ciudades",
                        estado: $(this).val()
                    },
                    success: function (resultados) {
                        $('#loader-'+campo_ciudad).hide();

                        let dataCiudades = resultados.ciudades;
                        if (dataCiudades) {
                            $("#"+campo_ciudad).html('<option value="">Elija una opción</option>');
                            for (let i in dataCiudades) {
                                $("#"+campo_ciudad).append('<option value="'+dataCiudades[i].codigo +'">'+dataCiudades[i].nombre+"</option>");
                            }
                        } else {
                            $("#"+campo_ciudad).html('<option value="">No hay ciudades</option>');
                        }

                        // CIUDAD CONTACTO, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.valor_ciudad_c != undefined && window.busquedad2) {
                            $("#ciudad_c").val(window.valor_ciudad_c);
                            delete window.valor_ciudad_c;
                            verificarParte2();

                            $('#carga_espera').hide(400);
                        }

                        // CIUDAD EMPRESA, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.valor_ciudad != undefined) {
                            $("#ciudad").val(window.valor_ciudad);
                            delete window.valor_ciudad;
                            verificarParte1();

                            if (window.valor_ciudad_c != undefined) {
                                window.busquedad2 = true;
                                $('#estado_c').trigger('change');
                            }
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS ICONO PARA REALIZAR NUEVAMENTE LA CONSULTA.
                        $('#loader-'+campo_ciudad).hide();
                        $('#loader-'+campo_ciudad+'-reload').show();

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
            $('#'+campo_ciudad).html('<option value="">Elija un estado</option>');
        }
    }
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
        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');
        $('.ocultar-iconos').hide();
        $('.btn-recargar').hide();
        $('.icon-alert').hide();
        $('#carga_espera').show();
        
        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#ciudad_c').html('<option value="">Elija un estado</option>');

        document.formulario.reset();
        tipoEnvio       = 'Modificar';
        window.rif      = dataListado.resultados[posicion].rif;
        $('#rif').val(dataListado.resultados[posicion].rif);
        $('#rif').trigger('blur');
        $('#nil').val(dataListado.resultados[posicion].nil);
        $('#razon_social').val(dataListado.resultados[posicion].razon_social);
        $('#actividad_economica').val(dataListado.resultados[posicion].codigo_actividad);
        $('#codigo_aportante').val(dataListado.resultados[posicion].codigo_aportante);
        $('#telefono_1').val(dataListado.resultados[posicion].telefono1);
        $('#telefono_2').val(dataListado.resultados[posicion].telefono2);
        $('#correo').val(dataListado.resultados[posicion].correo);
        $('#estado').val(dataListado.resultados[posicion].codigo_estado);
        window.valor_ciudad = dataListado.resultados[posicion].codigo_ciudad;
        $('#estado').trigger('change');
        $('#direccion').val(dataListado.resultados[posicion].direccion);

        /////////////////////////////////////////////////////////////////////
        window.nacionalidad = dataListado.resultados[posicion].datos_personales.nacionalidad;
        window.cedula       = dataListado.resultados[posicion].datos_personales.cedula;
        $('#nacionalidad').val(dataListado.resultados[posicion].datos_personales.nacionalidad);
        $('#cedula').val(dataListado.resultados[posicion].datos_personales.cedula);
        $('#cedula').trigger('blur');
        $('#nombre_1').val(dataListado.resultados[posicion].datos_personales.nombre1);
        $('#nombre_2').val(dataListado.resultados[posicion].datos_personales.nombre2);
        $('#apellido_1').val(dataListado.resultados[posicion].datos_personales.apellido1);
        $('#apellido_2').val(dataListado.resultados[posicion].datos_personales.apellido2);
        $('#sexo').val(dataListado.resultados[posicion].datos_personales.sexo);
        $('#telefono_1_c').val(dataListado.resultados[posicion].datos_personales.telefono1);
        $('#telefono_2_c').val(dataListado.resultados[posicion].datos_personales.telefono2);
        $('#correo_c').val(dataListado.resultados[posicion].datos_personales.correo);
        $('#estado_c').val(dataListado.resultados[posicion].datos_personales.codigo_estado);
        window.valor_ciudad_c   = dataListado.resultados[posicion].datos_personales.codigo_ciudad;
        window.busquedad2       = false;
        $('#direccion_c').val(dataListado.resultados[posicion].direccion);
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();
        verificarParte2();

        // SE VERIFICA QUE TODOS LOS CAMPOS ESTEN DEFINIDOS CORRECTAMENTE.
        if (validarRif && validarCedula && tarjeta_1 && tarjeta_2) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion',         value: tipoEnvio });
            data.push({ name: 'rif2',           value: window.rif });
            data.push({ name: 'nacionalidad2',  value: window.nacionalidad });
            data.push({ name: 'cedula2',        value: window.cedula });
            data.push({ name: 'registrar_cont', value: window.registrar_cont });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario').attr('disabled', true);
            $('#guardar-datos i.fa-save').addClass('fa-spin');
            $('#guardar-datos span').html('Guardando...');

            // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
            $('#contenedor-mensaje').empty();
            $('#contenedor-mensaje2').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_empresa.php',
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
        let rif         = dataListado.resultados[posicion].rif;

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
                url : url+'controllers/c_empresa.php',
                type: 'POST',
                data: {
                    opcion: 'Estatus',
                    rif: rif,
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
            url: url + "controllers/c_empresa.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                // CARGAMOS LAS ACTIVIDADES ECONOMICAS.
                let dataActividad = resultados.actividades;
                if (dataActividad) {
                    for (let i in dataActividad) {
                        $("#actividad_economica").append('<option value="'+dataActividad[i].codigo +'">'+dataActividad[i].nombre+"</option>");
                    }
                } else {
                    $("#actividad_economica").html('<option value="">No hay ocupaciones</option>');
                }

                // CARGAMOS LOS ESTADOS.
                let dataEstado = resultados.estados;
                if (dataEstado) {
                    for (let i in dataEstado) {
                        $("#estado").append('<option value="'+dataEstado[i].codigo +'">'+dataEstado[i].nombre+"</option>");
                        $("#estado_c").append('<option value="'+dataEstado[i].codigo +'">'+dataEstado[i].nombre+"</option>");
                    }
                } else {
                    $("#estado").html('<option value="">No hay estados</option>');
                    $("#estado_c").html('<option value="">No hay estados</option>');
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