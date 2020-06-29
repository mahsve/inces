$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Üa.,-- ])+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let vd_usuario;
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
    let dataPersonal        = {'A': 'Administrativo', 'C': 'Contacto empresa', 'F': 'Facilitador', 'B': 'Aprendiz'};
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
                url : url+'controllers/c_usuario.php',
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
                            // ORDENAR NOMBRE
                            let nombre_completo = dataListado.resultados[i].nombre1;
                            let nombre_completo2= dataListado.resultados[i].nombre1;
                            if (dataListado.resultados[i].nombre2 != null && dataListado.resultados[i].nombre2 != '') {
                                nombre_completo += ' '+dataListado.resultados[i].nombre2.substr(0, 1)+'.';
                                nombre_completo2+= ' '+dataListado.resultados[i].nombre2;
                            }
                            nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            nombre_completo2+= ' '+dataListado.resultados[i].apellido1;
                            if (dataListado.resultados[i].apellido2 != null && dataListado.resultados[i].apellido2 != '') {
                                nombre_completo += ' '+dataListado.resultados[i].apellido2.substr(0, 1)+'.';
                                nombre_completo2+= ' '+dataListado.resultados[i].apellido2;
                            }
                            //////////////////////////////////////////////////////////
                            nombre_completo = abreviarDescripcion(nombre_completo, 20);

                            let nombre_usuario = '';
                            if (dataListado.resultados[i].usuario != null) {
                                nombre_usuario = dataListado.resultados[i].usuario;
                            } else {
                                nombre_usuario = 'No regisrado.';
                            }

                            let rol_usuario = '';
                            if (dataListado.resultados[i].rol != null) {
                                rol_usuario = dataListado.resultados[i].rol;
                            } else {
                                rol_usuario = 'No regisrado.';
                            }

                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Activo</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'E') { estatus_td = '<span class="badge badge-info"><i class="fas fa-clock"></i> <span>Sin Entrar</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'B') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-user-lock"></i> <span>Bloqueado</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'C') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-ban"></i> <span>Cancelado</span></span>'; }
                            else if (dataListado.resultados[i].estatus == null) { estatus_td = '<span class="badge badge-secondary"><i class="fas fa-times"></i> <span>Sin estatus</span></span>'; }

                            let contenido_tabla = '';
                            contenido_tabla += '<tr class="border-bottom text-secondary">';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nacionalidad+'-'+dataListado.resultados[i].cedula+'</td>';
                            contenido_tabla += '<td class="py-2 px-1"><span class="tooltip-table" data-toggle="tooltip" data-placement="right" title="'+nombre_completo2+'">'+nombre_completo+'</span></td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataPersonal[dataListado.resultados[i].tipo_persona]+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+nombre_usuario+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+rol_usuario+'</td>';
                            contenido_tabla += '<td class="text-center py-2 px-1">'+estatus_td+'</td>';
                            ////////////////////////////////////////////////////////
                            if (permisos.modificar == 1 || permisos.act_desc == 1) {
                                contenido_tabla += '<td class="py-1 px-1">';
                                if (dataListado.resultados[i].usuario == null) {
                                    if (permisos.registrar == 1) { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-info agregar-registro" data-posicion="'+i+'" style="margin-right: 2px;"><i class="fas fa-user-plus"></i></button>'; }
                                } else {
                                    if (permisos.modificar == 1) { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-info editar-registro" data-posicion="'+i+'" style="margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button>'; }
                                }
                                // MODIFICAR
                                if (permisos.act_desc == 1) {
                                    if (dataListado.resultados[i].estatus != null) {
                                        contenido_tabla += '<div class="dropdown d-inline-block">';
                                            contenido_tabla += '<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                                contenido_tabla += '<i class="fas fa-ellipsis-v px-1"></i>';
                                            contenido_tabla += '</button>';

                                            contenido_tabla += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                                contenido_tabla += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 restablecer-usuario" data-posicion="'+i+'"><i class="fas fa-sync text-center" style="width:20px;"></i><span class="ml-2">Restablecer</span></a></li>';

                                                if      (dataListado.resultados[i].estatus == 'C') { contenido_tabla += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 activar-usuario" data-posicion="'+i+'"><i class="fas fa-redo text-center" style="width:20px;"></i><span class="ml-2">Reactivar</span></a></li>'; }
                                                else if (dataListado.resultados[i].estatus == 'A' || dataListado.resultados[i].estatus == 'E') { contenido_tabla += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 cancelar-usuario" data-posicion="'+i+'"><i class="fas fa-ban text-center" style="width:20px;"></i><span class="ml-2">Cancelar</span></a></li>'; }
                                            contenido_tabla += '</div>';
                                        contenido_tabla += '</div>';
                                    }
                                }
                                contenido_tabla += '</td>';
                            }
                            ////////////////////////////////////////////////////////
                            contenido_tabla += '</tr>';
                            $('#listado_tabla tbody').append(contenido_tabla);
                            cont++;
                        }
                        $('.tooltip-table').tooltip();
                        $('.agregar-registro').click(agregarRegistro);
                        $('.editar-registro').click(editarRegistro);

                        // FUNCIONES DE ESTATUS
                        $('.restablecer-usuario').click(restablecerUsuario);
                        $('.activar-usuario').click(activarUsuario);
                        $('.cancelar-usuario').click(cancelarUsuario);
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
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR MODAL Y REGISTAR UN NUEVO USUARIO (SOLO A PERSONAS YA REGISTRADAS)
    function agregarRegistro () {
        tipoEnvio = 'Registrar';
        abrirModalUsuario();

        let posicion = $(this).attr('data-posicion');
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula = dataListado.resultados[posicion].cedula;
    }
    // FUNCION PARA ABRIR MODAL Y MODIFICAR UN NUEVO USUARIO (SOLO A PERSONAS YA REGISTRADAS)
    function editarRegistro () {
        tipoEnvio = 'Modificar';
        abrirModalUsuario();

        let posicion = $(this).attr('data-posicion');
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula = dataListado.resultados[posicion].cedula;
        $('#rol_usuario').val(dataListado.resultados[posicion].codigo_rol);
    }
    // FUNCION PARA ABRIR LA MODAL Y RESETEAR FORMULARIO
    function abrirModalUsuario () {
        document.form_registrar_usuario.reset();
        $('#titulo-gestionar-usuario').html('REGISTRAR');
        $(".campos_formularios_usuario").css('background-color', '');
        $('.botones_formulario_usuario').attr('disabled', false);
        $('#btn-guardar-usuario i.fa-save').removeClass('fa-spin');
        $('#btn-guardar-usuario span').html('Guardar');
        $('#contenedor-mensaje-usuario').empty();
        $('#modal-usuario').modal();
    }
    // VALIDAR FORMULARIO
    function validar_usuario () {
        vd_usuario = true;
        let rol_usuario = $("#rol_usuario").val();
        if (rol_usuario != '') {
            $("#rol_usuario").css("background-color", colorb);
        } else {
            $("#rol_usuario").css("background-color", colorm);
            vd_usuario = false;
        }
    }
    // ENVIAR DATOS AL CONTROLADOR
    $('#btn-guardar-usuario').click(function (e) {
        e.preventDefault();
        validar_usuario();

        if (vd_usuario) {
            let data = $("#form_registrar_usuario").serializeArray();
            data.push({ name: 'opcion', value: tipoEnvio });
            data.push({ name: 'nacionalidad', value: window.nacionalidad });
            data.push({ name: 'cedula', value: window.cedula });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario_usuario').attr('disabled', true);
            $('#btn-guardar-usuario i.fa-save').addClass('fa-spin');
            $('#btn-guardar-usuario span').html('Guardando...');
            $('#contenedor-mensaje-usuario').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_usuario.php',
                    type: 'POST',
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
                            $('#modal-usuario').modal('hide');
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
                        if (resultados == 'Ya está registrado' || resultados == 'Registro fallido') { $('#contenedor-mensaje-usuario').html(contenedor_mensaje); }
                        else { $('#contenedor-mensaje').html(contenedor_mensaje); buscar_listado(); }

                        // OCULTAMOS EL MENSAJE DESPUES DE 5 SEGUNDOS.
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_usuario').attr('disabled', false);
                        $('#btn-guardar-usuario i.fa-save').removeClass('fa-spin');
                        $('#btn-guardar-usuario span').html('Guardar');
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
                        $('#contenedor-mensaje-usuario').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario_usuario').attr('disabled', false);
                        $('#btn-guardar-usuario i.fa-save').removeClass('fa-spin');
                        $('#btn-guardar-usuario span').html('Guardar');

                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function restablecerUsuario (e) {
        e.preventDefault();

        let posicion        = $(this).attr('data-posicion');
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula       = dataListado.resultados[posicion].cedula;
        window.estatus      = 'E';
        cambiarEstatus();
    }
    function activarUsuario (e) {
        e.preventDefault();

        let posicion        = $(this).attr('data-posicion');
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula       = dataListado.resultados[posicion].cedula;
        window.estatus      = 'E';
        cambiarEstatus();
    }
    function cancelarUsuario (e) {
        e.preventDefault();

        let posicion        = $(this).attr('data-posicion');
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula       = dataListado.resultados[posicion].cedula;
        tipoEnvio           = 'Cancelar usuario';
        window.estatus      = 'C';
        cambiarEstatus();
    }
    // CAMBIAR EL ESTATUS DEL USUARIO
    function cambiarEstatus () {
        // DESABILITAMOS TODO LO QUE PUEDA GENERAR NUEVAS CONSULTAS MIENTRAS SE ESTA REALIZANDO ALGUNA INTERNAMENTE.
        $('.campos_de_busqueda').attr('disabled', true);
        $('.botones_formulario').attr('disabled', true);

        // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
        $('#contenedor-mensaje').empty();
        
        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_usuario.php',
                type: 'POST',
                data: {
                    opcion      : 'Estatus',
                    nacionalidad: window.nacionalidad,
                    cedula      : window.cedula,
                    estatus     : window.estatus
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
            url: url + "controllers/c_usuario.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                // CARGAMOS LOS ROLES
                let dataRoles = resultados.roles;
                if (dataRoles) {
                    for (let i in dataRoles) {
                        $("#rol_usuario").append('<option value="'+dataRoles[i].codigo +'">'+dataRoles[i].nombre+"</option>");
                    }
                } else {
                    $("#rol_usuario").html('<option value="">No hay roles</option>');
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