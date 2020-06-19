$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_rifEmpresa          = new RegExp("^([VEJPG]{1})([-])([0-9]{8,9})$");
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Ü.,-- ])+$/;
    let validar_caracteresEspeciales1=/^([a-zá-úä-üA-ZÁ-úÄ-Ü. ])+$/;
    let validar_caracteresEspeciales2=/^([a-zá-úä-üA-ZÁ-úÄ-Ü0-9.,--# ])+$/;
    let validar_soloNumeros         =/^([0-9])+$/;
    let validar_correoElectronico   =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1, tarjeta_2;
    let vd_actividad_economica, vd_cargo_contacto, vd_cargo_contacto_v, vd_cargo_contacto2;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // VARIABLES NECESARIAS PARA GUARDAR LOS DATOS CONSULTADOS
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFICACION).
    let dataListado     = [];   // VARIABLE PARA GUARDAR LOS RESULTADOS CONSULTADOS.
    let dataCargos      = [];   // VARIABLE PARA GUARDAR LOS CARGOS CONSULTADOS.
    let mensaje_contato = '<h6 class="text-center py-4 m-0 text-uppercase text-secondary">Presione el botón <button type="button" class="btn btn-sm btn-info" disabled="true" style="height: 22px; padding: 3px 5px; vertical-align: top; cursor: default;"><i class="fas fa-plus" style="font-size: 9px; vertical-align: top; padding-top: 3px;"></i></button> para agregar personas de contacto</h6>';
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
        if ($('#contenedor-personas-contacto').html() == mensaje_contato) {
            $('#contenedor-personas-contacto').css('background-color', colorm);
            tarjeta_2 = false;
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
    $('#nombre_actividad_economica').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');  // CHECK DE VALIDACION DE CEDULA Y RIF (ICONO)
        $('.ocultar-iconos').hide();  // ICONO DE CARGA DE CEDULA Y RIF (ICONO)
        $('.btn-recargar').hide(); // BOTON RECARGAR DE LAS CONSULTAS INDEPENDIENTES (CARGO, ACTIVIDAD ECONOMICA).
        $('.icon-alert').hide(); // ICONOS EN LAS PESTAÑAS DE LOS FORMULARIOS.
        $('#contenedor-personas-contacto').html(mensaje_contato);
        
        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#ciudad_c').html('<option value="">Elija un estado</option>');

        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.rif      = '';
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
        $('#pills-datos-empresa-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // AGREGAR INFORMACION AL FORMULARIO DINAMICAMENTE.
    // REGISTRAR NUEVAS ACTIVIDADES ECONOMICAS
    $('#btn-actividad-economica').click(function (e) {
        e.preventDefault();
        document.form_registrar_actividad_e.reset();
        $(".campos_formularios_actividad_economica").css('background-color', '');
        $('.botones_formulario_actividad_economica').attr('disabled', false);
        $('#btn-registrar-actividad-economica i.fa-save').removeClass('fa-spin');
        $('#btn-registrar-actividad-economica span').html('Guardar');
        $('#contenedor-mensaje-actividad-economica').empty();
        $('#modal-actividad-economica').modal();
    });
    function validar_actividad_economica () {
        vd_actividad_economica = true;
        let nueva_nombre_cupacion = $("#nombre_actividad_economica").val();
        if (nueva_nombre_cupacion != '') {
            if (nueva_nombre_cupacion.match(validar_caracteresEspeciales)) {
                $("#nombre_actividad_economica").css("background-color", colorb);
            } else {
                $("#nombre_actividad_economica").css("background-color", colorm);
                vd_actividad_economica = false;
            }
        } else {
            $("#nombre_actividad_economica").css("background-color", colorm);
            vd_actividad_economica = false;
        }
    }
    $('#btn-registrar-actividad-economica').click(function (e) {
        e.preventDefault();
        validar_actividad_economica();

        if (vd_actividad_economica) {
            let data = $("#form_registrar_actividad_e").serializeArray();
            data.push({ name: 'opcion', value: 'Registrar actividad economica' });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario_actividad_economica').attr('disabled', true);
            $('#btn-registrar-actividad-economica i.fa-save').addClass('fa-spin');
            $('#btn-registrar-actividad-economica span').html('Guardando...');
            $('#contenedor-mensaje-actividad-economica').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_empresa.php',
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
                            $("#actividad_economica").html('<option value="">Elija una opción</option>');
                            // CARGAMOS LAS ACTIVIDADES ECONOMICAS SI TODO CARGO CORRECTAMENTE.
                            let dataActividad = resultados.actividades;
                            if (dataActividad) {
                                for (let i in dataActividad) {
                                    $("#actividad_economica").append('<option value="'+dataActividad[i].codigo +'">'+dataActividad[i].nombre+"</option>");
                                }
                            } else {
                                $("#actividad_economica").html('<option value="">No hay actividades económicas</option>');
                            }
                            
                            // CERRAMOS LA VENTANA.
                            $('#modal-actividad-economica').modal('hide');
                        }

                        // CARGAMOS EL MENSAJE EN EL CONTENEDOR CORRESPONDIENTE.
                        if (resultados == 'Ya está registrado' || 'Registro fallido') {
                            // MENSAJE SOBRE EL ESTATUS DE LA CONSULTA.
                            let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                            let contenedor_mensaje = '';
                            contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3" role="alert">';
                            contenedor_mensaje += icono_alerta+' '+resultados;
                            contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                            contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                            contenedor_mensaje += '</button>';
                            contenedor_mensaje += '</div>';
                            $('#contenedor-mensaje-actividad-economica').html(contenedor_mensaje);

                            // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                            setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                        }
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_actividad_economica').attr('disabled', false);
                        $('#btn-registrar-actividad-economica i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-actividad-economica span').html('Guardar');
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
                        $('#contenedor-mensaje-actividad-economica').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_actividad_economica').attr('disabled', false);
                        $('#btn-registrar-actividad-economica i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-actividad-economica span').html('Guardar');

                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    // AGREGAR NUEVOS CONTACTOS.
    $('#btn-persona-contacto').click(function (e) {
        e.preventDefault();
        window.agregarContacto      = true;
        window.agregarDatosContacto = true;
        vd_cargo_contacto_v         = false;
        window.id_dinamico          = '';
        window.nacionalidad         = '';
        window.cedula               = '';

        document.form_agregar_contacto.reset();
        $(".campos_formularios_persona_contacto").css('background-color', '');
        $('.botones_formulario_persona_contacto').attr('disabled', false);
        $('#cedula').trigger('blur');
        $('#contenedor-mensaje-contacto').empty();
        $('#modal-registrar-contacto').modal();
        $('#carga_espera_2').hide();
    });
    function validar_persona_contacto () {
        vd_cargo_contacto = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD DEL CONTACTO
        let nacionalidad = $("#nacionalidad").val();
        if (nacionalidad != "") {
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DE CEDULA DEL CONTACTO
        let cedula = $("#cedula").val();
        if (cedula != "") {
            if (cedula.match(validar_soloNumeros) && cedula.length >= 7) {
                if (validarCedula) {
                    $("#cedula").css("background-color", colorb);
                } else {
                    $("#cedula").css("background-color", colorm);
                    vd_cargo_contacto = false;
                }
            } else {
                $("#cedula").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#cedula").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE 1 DEL CONTACTO
        let nombre_1 = $("#nombre_1").val();
        if (nombre_1 != "") {
            if (nombre_1.match(validar_caracteresEspeciales1)) {
                $("#nombre_1").css("background-color", colorb);
            } else {
                $("#nombre_1").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#nombre_1").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE 2 DEL CONTACTO
        let nombre_2 = $("#nombre_2").val();
        if (nombre_2 != "") {
            if (nombre_2.match(validar_caracteresEspeciales1)) {
                $("#nombre_2").css("background-color", colorb);
            } else {
                $("#nombre_2").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#nombre_2").css("background-color", colorm);
        }
        // VERIFICAR EL CAMPO DEL APELLIDO 1 DEL CONTACTO
        let apellido_1 = $("#apellido_1").val();
        if (apellido_1 != "") {
            if (apellido_1.match(validar_caracteresEspeciales1)) {
                $("#apellido_1").css("background-color", colorb);
            } else {
                $("#apellido_1").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#apellido_1").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DEL APELLIDO 2 DEL CONTACTO
        let apellido_2 = $("#apellido_2").val();
        if (apellido_2 != "") {
            if (apellido_2.match(validar_caracteresEspeciales1)) {
                $("#apellido_2").css("background-color", colorb);
            } else {
                $("#apellido_2").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#apellido_2").css("background-color", colorm);
        }
        // VERIFICAR EL CAMPO DE SEXO
        let cargo_contacto = $("#cargo_contacto").val();
        if (cargo_contacto != "") {
            $("#cargo_contacto").css("background-color", colorb);
        } else {
            $("#cargo_contacto").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DEL CONTACTO (TELEFONO 1)
        let telefono_1_c = $("#telefono_1_c").val();
        if (telefono_1_c != "") {
            if (telefono_1_c.match(validar_soloNumeros)) {
                $("#telefono_1_c").css("background-color", colorb);
            } else {
                $("#telefono_1_c").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#telefono_1_c").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DEL CONTACTO (TELEFONO 2, OPCIONAL)
        let telefono_2_c = $("#telefono_2_c").val();
        if (telefono_2_c != "") {
            if (telefono_2_c.match(validar_soloNumeros)) {
                $("#telefono_2_c").css("background-color", colorb);
            } else {
                $("#telefono_2_c").css("background-color", colorm);
                vd_cargo_contacto = false;
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
                vd_cargo_contacto = false;
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
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DE LA CIUDAD DEL CONTACTO
        let ciudad_c = $("#ciudad_c").val();
        if (ciudad_c != "") {
            $("#ciudad_c").css("background-color", colorb);
        } else {
            $("#ciudad_c").css("background-color", colorm);
            vd_cargo_contacto = false;
        }
        // VERIFICAR EL CAMPO DE DIRECCION DEL CONTACTO (OPCIONAL)
        let direccion_c = $("#direccion_c").val();
        if (direccion_c != "") {
            if (direccion_c.match(validar_caracteresEspeciales2)) {
                $("#direccion_c").css("background-color", colorb);
            } else {
                $("#direccion_c").css("background-color", colorm);
                vd_cargo_contacto = false;
            }
        } else {
            $("#direccion_c").css("background-color", colorn);
        }
    }
    $('#btn-agregar-contacto').click(function (e) {
        e.preventDefault();
        validar_persona_contacto();

        if (vd_cargo_contacto || vd_cargo_contacto_v) {
            if ($('#contenedor-personas-contacto').html() == mensaje_contato) { $('#contenedor-personas-contacto').empty(); }

            // FUNCION PARA AGREGAR UNA NUEVA PERSONA DE CONTACTO.
            if (window.agregarContacto) {
                window.id_dinamico = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                let contenido_contacto = '';
                contenido_contacto += '<div id="contacto-'+window.id_dinamico+'" class="border rounded my-2 p-3">';
                    contenido_contacto += '<div class="form-row">';
                        contenido_contacto += '<div class="col-lg-12 d-flex justify-content-between align-items-center mb-2">';
                            contenido_contacto += '<h4 class="font-weight-normal text-secondary text-center text-uppercase">Datos personales</h4>';
                            
                            contenido_contacto += '<div>';
                                contenido_contacto += '<button type="button" class="btn btn-sm btn-info editar-contacto mr-1" data-id-contacto="'+window.id_dinamico+'"><i class="fas fa-pencil-alt"></i></button>';
                                contenido_contacto += '<button type="button" class="btn btn-sm btn-danger eliminar-contacto" data-id-contacto="'+window.id_dinamico+'"><i class="fas fa-times px-1" style="font-size: 12px;"></i></button>';
                            contenido_contacto += '</div>';
                        contenido_contacto += '</div>';

                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center">';
                            contenido_contacto += '<span class="w-50 small mr-1"><b>Cédula:</b></span>';
                            contenido_contacto += '<input type="text" name="nacionalidad_contacto[]" class="form-control-plaintext p-0 nacionalidad_contacto" style="outline: none; width: 10px;" readonly>';
                            contenido_contacto += '<span>-</span>';
                            contenido_contacto += '<input type="text" name="cedula_contacto[]" class="form-control-plaintext p-0 cedula_contacto" style="outline: none; width: calc(50% - 15px);" data-id-contacto="'+window.id_dinamico+'" readonly>';
                        contenido_contacto += '</div>';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Primer nombre:</b></span><input type="text" name="nombre1_contacto[]" class="form-control-plaintext w-50 p-0 nombre1_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Segundo nombre:</b></span><input type="text" name="nombre2_contacto[]" class="form-control-plaintext w-50 p-0 nombre2_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Primer apellido:</b></span><input type="text" name="apellido1_contacto[]" class="form-control-plaintext w-50 p-0 apellido1_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Segundo apellido:</b></span><input type="text" name="apellido2_contacto[]" class="form-control-plaintext w-50 p-0 apellido2_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<input type="hidden" name="cargo_contacto[]" class="cargo_contacto">';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Cargo:</b></span><input type="text" name="n_cargo_contacto[]" class="form-control-plaintext w-50 p-0 n_cargo_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Tlf. de habitación:</b></span><input type="text" name="telefono1_contacto[]" class="form-control-plaintext w-50 p-0 telefono1_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<div class="col-lg-4 d-flex align-items-center"><span class="w-50 small mr-1"><b>Tlf. célular:</b></span><input type="text" name="telefono2_contacto[]" class="form-control-plaintext w-50 p-0 telefono2_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<div class="col-lg-6 d-flex align-items-center"><span class="w-25 small mr-1"><b>Correo:</b></span><input type="text" name="correo_contacto[]" class="form-control-plaintext w-50 p-0 correo_contacto" style="outline: none;" readonly></div>';
                        contenido_contacto += '<input type="hidden" name="estado_contacto[]" class="estado_contacto">';
                        contenido_contacto += '<input type="hidden" name="ciudad_contacto[]" class="ciudad_contacto">';
                        contenido_contacto += '<input type="hidden" name="direccion_contacto[]" class="direccion_contacto">';
                    contenido_contacto += '</div>';
                contenido_contacto += '</div>';
                $('#contenedor-personas-contacto').append(contenido_contacto);

                // FUNCION PARA EDITAR UNA PERSONA DE CONTACTO.
                $('#contacto-'+window.id_dinamico+' .editar-contacto').click(function (e) {
                    e.preventDefault();
                    window.agregarContacto      = false;
                    window.agregarDatosContacto = true;
                    vd_cargo_contacto_v         = false;
                    window.id_dinamico          = $(this).attr('data-id-contacto');
                    window.nacionalidad         = $('#contacto-'+window.id_dinamico+' .nacionalidad_contacto').val();
                    window.cedula               = $('#contacto-'+window.id_dinamico+' .cedula_contacto').val();

                    document.form_agregar_contacto.reset();
                    $(".campos_formularios_persona_contacto").css('background-color', '');
                    $('.botones_formulario_persona_contacto').attr('disabled', false);
                    $('#cedula').trigger('blur');
                    $('#contenedor-mensaje-contacto').empty();
                    $('#modal-registrar-contacto').modal();
                    $('#carga_espera_2').show();

                    $('#nacionalidad').val($('#contacto-'+window.id_dinamico+' .nacionalidad_contacto').val());
                    $('#cedula').val($('#contacto-'+window.id_dinamico+' .cedula_contacto').val());
                    $('#cedula').trigger('blur');
                    $('#nombre_1').val($('#contacto-'+window.id_dinamico+' .nombre1_contacto').val());
                    $('#nombre_2').val($('#contacto-'+window.id_dinamico+' .nombre2_contacto').val());
                    $('#apellido_1').val($('#contacto-'+window.id_dinamico+' .apellido1_contacto').val());
                    $('#apellido_2').val($('#contacto-'+window.id_dinamico+' .apellido2_contacto').val());
                    $('#cargo_contacto').val($('#contacto-'+window.id_dinamico+' .cargo_contacto').val());
                    $('#telefono_1_c').val($('#contacto-'+window.id_dinamico+' .telefono1_contacto').val());
                    $('#telefono_2_c').val($('#contacto-'+window.id_dinamico+' .telefono2_contacto').val());
                    $('#correo_c').val($('#contacto-'+window.id_dinamico+' .correo_contacto').val());
                    $('#estado_c').val($('#contacto-'+window.id_dinamico+' .estado_contacto').val());
                    window.valor_ciudad_c   = $('#contacto-'+window.id_dinamico+' .ciudad_contacto').val();
                    $('#estado_c').trigger('change');
                    $('#direccion_c').val($('#contacto-'+window.id_dinamico+' .direccion_contacto').val());
                });
            }

            // AGREGAMOS LOS DATOS DEL FORMULARIO A LAS TARJETAS DE CONTACTO
            if (window.agregarDatosContacto) {
                let nombre_cargo_content = '';
                for (let i in dataCargos) { if ($('#cargo_contacto').val() == dataCargos[i].codigo) { nombre_cargo_content = dataCargos[i].nombre; } }
    
                $('#contacto-'+window.id_dinamico+' .nacionalidad_contacto').val($('#nacionalidad').val());
                $('#contacto-'+window.id_dinamico+' .cedula_contacto').val($('#cedula').val());
                $('#contacto-'+window.id_dinamico+' .nombre1_contacto').val($('#nombre_1').val());
                $('#contacto-'+window.id_dinamico+' .nombre2_contacto').val($('#nombre_2').val());
                $('#contacto-'+window.id_dinamico+' .apellido1_contacto').val($('#apellido_1').val());
                $('#contacto-'+window.id_dinamico+' .apellido2_contacto').val($('#apellido_2').val());
                $('#contacto-'+window.id_dinamico+' .cargo_contacto').val($('#cargo_contacto').val());
                $('#contacto-'+window.id_dinamico+' .n_cargo_contacto').val(nombre_cargo_content);
                $('#contacto-'+window.id_dinamico+' .telefono1_contacto').val($('#telefono_1_c').val());
                $('#contacto-'+window.id_dinamico+' .telefono2_contacto').val($('#telefono_2_c').val());
                $('#contacto-'+window.id_dinamico+' .correo_contacto').val($('#correo_c').val());
                $('#contacto-'+window.id_dinamico+' .estado_contacto').val($('#estado_c').val());
                $('#contacto-'+window.id_dinamico+' .ciudad_contacto').val($('#ciudad_c').val());
                $('#contacto-'+window.id_dinamico+' .direccion_contacto').val($('#direccion_c').val());
                $('#modal-registrar-contacto').modal('hide');
            }
        }
    });
    // REGISTRAR NUEVAS ACTIVIDADES ECONOMICAS
    $('#btn-cargo').click(function (e) {
        e.preventDefault();
        document.form_registrar_cargo.reset();
        $(".campos_formularios_cargo").css('background-color', '');
        $('.botones_formulario_cargo').attr('disabled', false);
        $('#btn-registrar-cargo i.fa-save').removeClass('fa-spin');
        $('#btn-registrar-cargo span').html('Guardar');
        $('#contenedor-mensaje-cargo').empty();
        $('#modal-cargo').modal();
    });
    function validar_cargo_contacto () {
        vd_cargo_contacto2 = true;
        let nombre_cargo = $("#nombre_cargo").val();
        if (nombre_cargo != '') {
            if (nombre_cargo.match(validar_caracteresEspeciales)) {
                $("#nombre_cargo").css("background-color", colorb);
            } else {
                $("#nombre_cargo").css("background-color", colorm);
                vd_cargo_contacto2 = false;
            }
        } else {
            $("#nombre_cargo").css("background-color", colorm);
            vd_cargo_contacto2 = false;
        }
    }
    $('#btn-registrar-cargo').click(function (e) {
        e.preventDefault();
        validar_cargo_contacto();

        if (vd_cargo_contacto2) {
            let data = $("#form_registrar_cargo").serializeArray();
            data.push({ name: 'opcion', value: 'Registrar cargo' });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario_cargo').attr('disabled', true);
            $('#btn-registrar-cargo i.fa-save').addClass('fa-spin');
            $('#btn-registrar-cargo span').html('Guardando...');
            $('#contenedor-mensaje-cargo').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_empresa.php',
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
                            $("#cargo_contacto").html('<option value="">Elija una opción</option>');
                            // CARGAMOS LOS CARGOS SI TODO CARGO CORRECTAMENTE.
                            dataCargos = resultados.cargos;
                            if (dataCargos) {
                                for (let i in dataCargos) {
                                    $("#cargo_contacto").append('<option value="'+dataCargos[i].codigo +'">'+dataCargos[i].nombre+"</option>");
                                }
                            } else {
                                $("#cargo_contacto").html('<option value="">No hay cargos</option>');
                            }
                            
                            // CERRAMOS LA VENTANA.
                            $('#modal-cargo').modal('hide');
                        }

                        // CARGAMOS EL MENSAJE EN EL CONTENEDOR CORRESPONDIENTE.
                        if (resultados == 'Ya está registrado' || 'Registro fallido') {
                            // MENSAJE SOBRE EL ESTATUS DE LA CONSULTA.
                            let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                            let contenedor_mensaje = '';
                            contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3" role="alert">';
                            contenedor_mensaje += icono_alerta+' '+resultados;
                            contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                            contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                            contenedor_mensaje += '</button>';
                            contenedor_mensaje += '</div>';
                            $('#contenedor-mensaje-cargo').html(contenedor_mensaje);

                            // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                            setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                        }
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_cargo').attr('disabled', false);
                        $('#btn-registrar-cargo i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-cargo span').html('Guardar');
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
                        $('#contenedor-mensaje-cargo').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_cargo').attr('disabled', false);
                        $('#btn-registrar-cargo i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-cargo span').html('Guardar');

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
            if (validar_rifEmpresa.test($("#rif").val())) {
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
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger fa-exclamation-triangle text-warning');

        if($('#nacionalidad').val() != '') {
            if ($('#cedula').val() != '') {
                if ($('#cedula').val().match(validar_soloNumeros) && $('#cedula').val().length >= 7) {
                    let nacion_rep = false;
                    let cedula_rep = false;
                    let idcont_rep = '';

                    $('.nacionalidad_contacto').each(function () { if ($(this).val() == $('#nacionalidad').val()) { nacion_rep = true; } });
                    $('.cedula_contacto').each(function () { if ($(this).val() == $('#cedula').val()) { cedula_rep = true; idcont_rep = $(this).attr('data-id-contacto'); } });
                    
                    // SI ENCUENTRA LA MISMA CEDULA PERO CON OTRO ID (OTRO CONTACTO YA AGREGADO), MANDA ERROR.
                    if (nacion_rep && cedula_rep && idcont_rep != window.id_dinamico) {
                        // MOSTRAMOS ICONO DE ERROR
                        $('#spinner-cedula-confirm').show();
                        $('#spinner-cedula-confirm').addClass('fa-exclamation-triangle text-warning');

                        // MENSAJE DE ERROR, RIF INCORRECTO.
                        let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-warning mt-2 mb-0 py-2 px-3" role="alert">';
                        contenedor_mensaje += '<i class="fas fa-exclamation-triangle"></i> <span style="font-weight: 500;">Ya agregaste a esta persona como contacto de la empresa</span>';
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';
                        $('#contenedor-mensaje-contacto').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                    } else {
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

                                        window.dataConfirmar = resultados;
                                        if (window.dataConfirmar) {
                                            $('#modal-aceptar-contacto').modal({backdrop: 'static', keyboard: false})
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
                    $('#contenedor-mensaje-contacto').html(contenedor_mensaje);

                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                }
            }
        }
    });
    $('#btn-agregar-persona').click(function () {
        validarCedula = true;
        $('#spinner-cedula-confirm').addClass('fa-check text-success');

        window.nacionalidad = window.dataConfirmar.nacionalidad;
        window.cedula = window.dataConfirmar.cedula;
        $('#nacionalidad').val(window.dataConfirmar.nacionalidad);
        $('#cedula').val(window.dataConfirmar.cedula);
        $('#nombre_1').val(window.dataConfirmar.nombre1);
        $('#nombre_2').val(window.dataConfirmar.nombre2);
        $('#apellido_1').val(window.dataConfirmar.apellido1);
        $('#apellido_2').val(window.dataConfirmar.apellido2);
        $('#sexo').val(window.dataConfirmar.sexo);
        $('#estado_c').val(window.dataConfirmar.codigo_estado);
        window.valor_ciudad_c = window.dataConfirmar.codigo_ciudad;
        $('#estado_c').trigger('change');
        $('#telefono_1_c').val(window.dataConfirmar.telefono1);
        $('#telefono_2_c').val(window.dataConfirmar.telefono2);
        $('#correo_c').val(window.dataConfirmar.correo);
        $('#direccion_c').val(window.dataConfirmar.direccion);
    });
    $('#btn-rechazar-persona').click(function () {
        validarCedula = false;
        $('#spinner-cedula-confirm').addClass('fa-times text-danger');
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
                        if (window.valor_ciudad_c != undefined) {
                            $("#ciudad_c").val(window.valor_ciudad_c);
                            delete window.valor_ciudad_c;
                            validar_persona_contacto();

                            $('#carga_espera_2').hide(400);
                        }

                        // CIUDAD EMPRESA, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.valor_ciudad != undefined) {
                            $("#ciudad").val(window.valor_ciudad);
                            delete window.valor_ciudad;
                            verificarParte1();

                            $('#carga_espera').hide(400);
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
        $('#contenedor-personas-contacto').html(mensaje_contato);
        $('#carga_espera').show();
        
        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#ciudad_c').html('<option value="">Elija un estado</option>');

        document.formulario.reset();
        tipoEnvio       = 'Modificar';
        window.rif      = dataListado.resultados[posicion].rif;
        window.agregarContacto      = true;
        window.agregarDatosContacto = false;
        vd_cargo_contacto_v         = true;

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

        let arreglo_contactos = dataListado.resultados[posicion].contactos;
        for (let i = 0; i < arreglo_contactos.length; i++) {
            $('#btn-agregar-contacto').trigger('click');

            let nombre_cargo_content = '';
            for (let h in dataCargos) { if (arreglo_contactos[i].codigo_cargo == dataCargos[h].codigo) { nombre_cargo_content = dataCargos[h].nombre; } }

            $('#contacto-'+window.id_dinamico+' .nacionalidad_contacto').val(arreglo_contactos[i].nacionalidad);
            $('#contacto-'+window.id_dinamico+' .cedula_contacto').val(arreglo_contactos[i].cedula);
            $('#contacto-'+window.id_dinamico+' .nombre1_contacto').val(arreglo_contactos[i].nombre1);
            $('#contacto-'+window.id_dinamico+' .nombre2_contacto').val(arreglo_contactos[i].nombre2);
            $('#contacto-'+window.id_dinamico+' .apellido1_contacto').val(arreglo_contactos[i].apellido1);
            $('#contacto-'+window.id_dinamico+' .apellido2_contacto').val(arreglo_contactos[i].apellido2);
            $('#contacto-'+window.id_dinamico+' .cargo_contacto').val(arreglo_contactos[i].codigo_cargo);
            $('#contacto-'+window.id_dinamico+' .n_cargo_contacto').val(nombre_cargo_content);
            $('#contacto-'+window.id_dinamico+' .telefono1_contacto').val(arreglo_contactos[i].telefono1);
            $('#contacto-'+window.id_dinamico+' .telefono2_contacto').val(arreglo_contactos[i].telefono2);
            $('#contacto-'+window.id_dinamico+' .correo_contacto').val(arreglo_contactos[i].correo);
            $('#contacto-'+window.id_dinamico+' .estado_contacto').val(arreglo_contactos[i].codigo_estado);
            $('#contacto-'+window.id_dinamico+' .ciudad_contacto').val(arreglo_contactos[i].codigo_ciudad);
            $('#contacto-'+window.id_dinamico+' .direccion_contacto').val(arreglo_contactos[i].direccion);
        }
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();
        verificarParte2();

        // SE VERIFICA QUE TODOS LOS CAMPOS ESTEN DEFINIDOS CORRECTAMENTE.
        if (validarRif && validarCedula && tarjeta_1) {
            let data = $("#formulario").serializeArray();
            data.push({ name: 'opcion',         value: tipoEnvio });
            data.push({ name: 'rif2',           value: window.rif });

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
                        console.log(resultados);

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
                    $("#actividad_economica").html('<option value="">No hay actividades económica</option>');
                }

                // CARGAMOS LAS ACTIVIDADES ECONOMICAS.
                dataCargos = resultados.cargos;
                if (dataCargos) {
                    for (let i in dataCargos) {
                        $("#cargo_contacto").append('<option value="'+dataCargos[i].codigo +'">'+dataCargos[i].nombre+"</option>");
                    }
                } else {
                    $("#cargo_contacto").html('<option value="">No hay cargos</option>');
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