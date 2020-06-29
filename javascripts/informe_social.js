$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Ü.,-- ])+$/;
    let validar_caracteresEspeciales1=/^([a-zá-úä-üA-ZÁ-úÄ-Ü. ])+$/;
    let validar_caracteresEspeciales2=/^([a-zá-úä-üA-ZÁ-úÄ-Ü0-9.,--# ])+$/;
    let validar_soloNumeros         =/^([0-9])+$/;
    let validar_soloNumerosComa     =/^([0-9.])+$/;
    let validar_correoElectronico   =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1, tarjeta_2, tarjeta_3, tarjeta_4, tarjeta_5, tarjeta_6;
    let vd_ocupacion;
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
    let mensaje_familia = '<tr><td colspan="'+($('#tabla_datos_familiares thead th').length + 4)+'"><h6 class="text-center py-4 m-0 text-uppercase text-secondary">Presione el botón <button type="button" class="btn btn-sm btn-info" disabled="true" style="height: 22px; padding: 3px 5px; vertical-align: top; cursor: default;"><i class="fas fa-plus" style="font-size: 9px; vertical-align: top; padding-top: 3px;"></i></button> para agregar familiares</h6></td></tr>';

    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR UNA FECHA TEMPORAL EN FAMILIAR.
    let dataOcupacion   = false;// VARIABLE PARA GUARDAR LAS OCUPACIONES Y AGREGARLAS A LA TABLA FAMILIA.
    let dataParentescoF = ['Madre', 'Hermana', 'Abuela', 'Tía', 'Prima', 'Sobrina'];
    let dataParentescoM = ['Padre', 'Hermano', 'Abuelo', 'Tío', 'Primo', 'Sobrino'];
    let dataTurno       = {'M': 'Matutino', 'V': 'Vespertino'};
    let maxFamiliares   = 10;   // MAXIMO DE FAMILIARES EN LA TABLA DE FAMILIARES DEL APRENDIZ.
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
                url : url+'controllers/c_informe_social.php',
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
                success: function (resultados) {
                    dataListado = resultados;

                    // $('#listado_aprendices tbody').empty();
                    // if (dataListado.resultados) {
                    //     let cont = parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()) + 1;
                    //     for (var i in dataListado.resultados) {
                    //         let contenido = '';
                    //         contenido += '<tr class="border-bottom text-secondary">';
                    //         contenido += '<td class="text-right py-2 px-1">'+cont+'</td>';
                    //         contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].fecha.substr(8,2)+'-'+dataListado.resultados[i].fecha.substr(5,2)+'-'+dataListado.resultados[i].fecha.substr(0,4)+'</td>';
                    //         contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nacionalidad+'-'+dataListado.resultados[i].cedula+'</td>';
                            
                    //         let nombre_completo = dataListado.resultados[i].nombre1;
                    //         if (dataListado.resultados[i].nombre2 != null) nombre_completo += ' '+dataListado.resultados[i].nombre2.substr(0,1)+'.';
                    //         nombre_completo += ' '+dataListado.resultados[i].apellido1;
                    //         if (dataListado.resultados[i].apellido2 != null) nombre_completo += ' '+dataListado.resultados[i].apellido2.substr(0,1)+'.';
                    //         contenido += '<td class="py-2 px-1">'+nombre_completo+'</td>';
                    //         let edad = calcularEdad(fecha, dataListado);
                
                    //         let estatus = '';
                    //         if (dataListado.resultados[i].estatus_informe == 'E') {
                    //             estatus = '<span class="badge badge-info"><i class="fas fa-clock mr-1"></i>En espera</span>';
                    //         } else if (dataListado.resultados[i].estatus_informe == 'A') {
                    //             estatus = '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aceptado</span>';
                    //         } else if (dataListado.resultados[i].estatus_informe == 'R') {
                    //             estatus = '<span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Rechazado</span>';
                    //         }
                
                    //         contenido += '<td class="text-center py-2 px-1">'+day+'-'+month+'-'+year+'</td>';
                    //         contenido += '<td class="text-center py-2 px-1">'+edad+'</td>';
                    //         contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].oficio+'</td>';
                    //         contenido += '<td class="py-2 px-1">'+dataTurno[dataListado.resultados[i].turno]+'</td>';
                    //         contenido += '<td class="text-center py-2 px-1">'+estatus+'</td>';
                    //         contenido += '<td class="py-1 px-1">';
                    //         if (permisos.modificar == 1){
                    //             if (dataListado.resultados[i].estatus_informe != 'E') contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'" disabled="true"><i class="fas fa-pencil-alt"></i></button>';
                    //             else contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'"><i class="fas fa-pencil-alt"></i></button>';
                    //         }
                            
                    //         contenido += '<div class="dropdown d-inline-block"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v px-1"></i></button>';
                    //         contenido += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    //         if (permisos.act_desc == 1) {
                    //             if (dataListado.resultados[i].estatus_informe == 'E') {
                    //                 contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 aceptar_postulante" data-posicion="'+i+'"><i class="fas fa-check text-center" style="width:20px;"></i><span class="ml-2">Aceptar</span></a></li>';
                    //                 contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 rechazar_postulante" data-posicion="'+i+'"><i class="fas fa-times text-center" style="width:20px;"></i><span class="ml-2">Rechazar</span></a></li>';
                    //             } else if (dataListado.resultados[i].estatus_informe == 'R') {
                    //                 contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 reactivar_postulante" data-posicion="'+i+'"><i class="fas fa-redo text-center" style="width:20px;"></i><span class="ml-2">Reactivar</span></a></li>';
                    //             }
                    //         }
                    //         contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 imprimir_informe" data-posicion="'+i+'"><i class="fas fa-print text-center" style="width:20px;"></i><span class="ml-2">Imprimir</span></a></li>';
                    //         contenido += '</div></div></td></tr>';
                    //         $('#listado_aprendices tbody').append(contenido);
                    //         cont++;
                    //     }

                    //     $('.editar_registro').click(editarInforme);
                    //     $('.aceptar_postulante').click(aceptarPostulante);
                    //     $('.rechazar_postulante').click(rechazarPostulante);
                    //     $('.reactivar_postulante').click(reactivarPostulante);
                    //     $('.imprimir_informe').click(imprimirInforme);
                    // } else {
                    //     // MOSTRAMOS MENSAJE "SIN RESULTADOS" EN LA TABLA
                    //     let contenido_tabla = '';
                    //     contenido_tabla += '<tr>';
                    //     contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
                    //     contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay informes registrados.</span>';
                    //     contenido_tabla += '</td>';
                    //     contenido_tabla += '</tr>';
                    //     $('#listado_tabla tbody').html(contenido_tabla);
                    // }

                    // $('#listado_tabla tbody').empty();
                    // if (dataListado.resultados) {
                    //     let cont = parseInt(numeroDeLaPagina-1) * parseInt($('#campo_cantidad').val()) + 1;
                    //     for (var i in dataListado.resultados) {
                    //         let estatus_td = '';
                    //         if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span>Activo</span></span>'; }
                    //         else if (dataListado.resultados[i].estatus == 'I') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-times"></i> <span>Inactivo</span></span>'; }

                    //         let contenido_tabla = '';
                    //         contenido_tabla += '<tr class="border-bottom text-secondary">';
                    //         contenido_tabla += '<td class="text-right py-2 px-1">'+cont+'</td>';
                    //         contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
                    //         contenido_tabla += '<td class="py-2 px-1">'+nombres_formularios[dataListado.resultados[i].formulario]+'</td>';
                    //         contenido_tabla += '<td class="text-center py-2 px-1">'+estatus_td+'</td>';
                    //         ////////////////////////////////////////////////////////
                    //         if (permisos.modificar == 1 || permisos.act_desc == 1) {
                    //             contenido_tabla += '<td class="py-1 px-1">';
                    //             if (permisos.modificar == 1) { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-info editar-registro" data-posicion="'+i+'" style="margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button>'; }
                    //             if (permisos.act_desc == 1) {
                    //                 if      (dataListado.resultados[i].estatus == 'A') { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-danger cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye-slash" style="font-size: 12px;"></i></button>'; }
                    //                 else if (dataListado.resultados[i].estatus == 'I') { contenido_tabla += '<button type="button" class="botones_formulario btn btn-sm btn-success cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye"></i></button>'; }
                    //             }
                    //             contenido_tabla += '</td>';
                    //         }
                    //         ////////////////////////////////////////////////////////
                    //         contenido_tabla += '</tr>';
                    //         $('#listado_tabla tbody').append(contenido_tabla);
                    //         cont++;
                    //     }
                    //     $('.editar-registro').click(editarRegistro);
                    //     $('.cambiar-estatus').click(cambiarEstatus);
                    // }

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
        // VERIFICAR EL CAMPO DE NACIONALIDAD DEL CONTACTO
        let nacionalidad = $("#nacionalidad").val();
        if (nacionalidad != "") {
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE CEDULA DEL CONTACTO
        let cedula = $("#cedula").val();
        if (cedula != "") {
            if (cedula.match(validar_soloNumeros) && cedula.length >= 7) {
                if (validarCedula) {
                    $("#cedula").css("background-color", colorb);
                } else {
                    $("#cedula").css("background-color", colorm);
                    tarjeta_1 = false;
                }
            } else {
                $("#cedula").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#cedula").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE 1 DEL CONTACTO
        let nombre_1 = $("#nombre_1").val();
        if (nombre_1 != "") {
            if (nombre_1.match(validar_caracteresEspeciales1)) {
                $("#nombre_1").css("background-color", colorb);
            } else {
                $("#nombre_1").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#nombre_1").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE 2 DEL CONTACTO
        let nombre_2 = $("#nombre_2").val();
        if (nombre_2 != "") {
            if (nombre_2.match(validar_caracteresEspeciales1)) {
                $("#nombre_2").css("background-color", colorb);
            } else {
                $("#nombre_2").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#nombre_2").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DEL APELLIDO 1 DEL CONTACTO
        let apellido_1 = $("#apellido_1").val();
        if (apellido_1 != "") {
            if (apellido_1.match(validar_caracteresEspeciales1)) {
                $("#apellido_1").css("background-color", colorb);
            } else {
                $("#apellido_1").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#apellido_1").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DEL APELLIDO 2 DEL CONTACTO
        let apellido_2 = $("#apellido_2").val();
        if (apellido_2 != "") {
            if (apellido_2.match(validar_caracteresEspeciales1)) {
                $("#apellido_2").css("background-color", colorb);
            } else {
                $("#apellido_2").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#apellido_2").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE SEXO
        let sexo = $("#sexo").val();
        if (sexo != '') {
            $("#sexo").css("background-color", colorb);
        } else {
            $("#sexo").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE FECHA DE NACIMIENTO
        let fecha_n = $("#fecha_n").val();
        if (fecha_n != '') {
            let edad_cal = parseInt($('#edad').val());
            if (edad_cal >= 14 && edad_cal <= 19) {
                $("#fecha_n").css("background-color", colorb);
            } else {
                $("#fecha_n").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#fecha_n").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE ESTADO
        let estado_n = $("#estado_n").val();
        if(estado_n != ''){
            $("#estado_n").css("background-color", colorb);
        } else {
            $("#estado_n").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE CIUDAD
        let ciudad_n = $("#ciudad_n").val();
        if (ciudad_n != '') {
            $("#ciudad_n").css("background-color", colorb);
        } else {
            $("#ciudad_n").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE LUGAR DE NACIMIENTO
        let lugar_n = $("#lugar_n").val();
        if (lugar_n != '') {
            if(lugar_n.match(validar_caracteresEspeciales2)){
                $("#lugar_n").css("background-color", colorb);
            }else{
                $("#lugar_n").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#lugar_n").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE OCUPACION
        let ocupacion = $("#ocupacion").val();
        if (ocupacion != '') {
            $("#ocupacion").css("background-color", colorb);
        } else {
            $("#ocupacion").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CAMPO DE ESTADO CIVIL
        let estado_civil = document.formulario.estado_civil.value;
        $(".radio_estado_civil_label").removeClass('inputMal');
        if (estado_civil == '') {
            $(".radio_estado_civil_label").addClass('inputMal');
            tarjeta_1 = false;
        } else {
            $(".radio_estado_civil_label").addClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE GRADO DE INSTRUCCION
        let grado_instruccion = document.formulario.grado_instruccion.value;
        $(".radio_educacion_label").removeClass('inputMal');
        if (grado_instruccion == '') {
            $(".radio_educacion_label").addClass('inputMal');
            tarjeta_1 = false;
        } else {
            $(".radio_educacion_label").addClass('inputBien');
        }
        // SI EL CAMPO DE INSTRUCCION ES SUPERIOR, VERIFICAR QUE EL CAMPO TITULO NO ESTE VACIO.
        let titulo_educacion = $("#titulo").val();
        if (titulo_educacion != '') {
            if(titulo_educacion.match(validar_caracteresEspeciales)){
                $("#titulo").css("background-color", colorb);
            }else{
                $("#titulo").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#titulo").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE MISIONES REALZADAS (NO OBLIGATORIA)
        let alguna_mision = $("#alguna_mision").val();
        if (alguna_mision != '') {
            if(alguna_mision.match(validar_caracteresEspeciales)){
                $("#alguna_mision").css("background-color", colorb);
            }else{
                $("#alguna_mision").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#alguna_mision").css("background-color", colorn);
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_1) {
            $('#icon-ciudadano').hide();
        } else {
            $('#icon-ciudadano').show();
        }
    }
    function verificarParte2 () {
        tarjeta_2 = true;
        // VERIFICAR EL TELEFONO DE CASA
        let telefono_1 = $("#telefono_1").val();
        if (telefono_1 != '') {
            if(telefono_1.match(validar_soloNumeros)){
                if (telefono_1.length == 7 || telefono_1.length >= 10) {
                    $("#telefono_1").css("background-color", colorb);
                } else {
                    $("#telefono_1").css("background-color", colorm);
                    tarjeta_2 = false;
                }
            }else{
                $("#telefono_1").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#telefono_1").css("background-color", colorn);
        }
        // VERIFICAR EL TELEFONO CELULAR
        let telefono_2 = $("#telefono_2").val();
        if (telefono_2 != '') {
            if(telefono_2.match(validar_soloNumeros)){
                if (telefono_2.length >= 10) {
                    $("#telefono_2").css("background-color", colorb);
                } else {
                    $("#telefono_2").css("background-color", colorm);
                    tarjeta_2 = false;
                }
            }else{
                $("#telefono_2").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#telefono_2").css("background-color", colorn);
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let correo = $("#correo").val();
        if (correo != '') {
            if(correo.match(validar_correoElectronico)){
                $("#correo").css("background-color", colorb);
            }else{
                $("#correo").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#correo").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE TURNO
        let turno = $("#turno").val();
        if (turno != '') {
            $("#turno").css("background-color", colorb);
        } else {
            $("#turno").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE SALIDA OCUPACIONAL
        let oficio = $("#oficio").val();
        if (oficio != '') {
            $("#oficio").css("background-color", colorb);
        } else {
            $("#oficio").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE ESTADO
        let estado = $("#estado").val();
        if (estado != '') {
            $("#estado").css("background-color", colorb);
        } else {
            $("#estado").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE CIUDAD
        let ciudad = $("#ciudad").val();
        if (ciudad != '') {
            $("#ciudad").css("background-color", colorb);
        } else {
            $("#ciudad").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE MUNICIPIO
        let municipio = $("#municipio").val();
        if (municipio != '') {
            $("#municipio").css("background-color", colorb);
        } else {
            $("#municipio").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE PARROQUIA
        let parroquia = $("#parroquia").val();
        if (parroquia != '') {
            $("#parroquia").css("background-color", colorb);
        } else {
            $("#parroquia").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE AREA
        let area = $("#area").val();
        if (area != '') {
            $("#area").css("background-color", colorb);
        } else {
            $("#area").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE DIRECCION
        let direccion = $("#direccion").val();
        if (direccion != '') {
            if(direccion.match(validar_caracteresEspeciales2)){
                $("#direccion").css("background-color", colorb);
            }else{
                $("#direccion").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#direccion").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE PUNTO DE REFERENCIA
        let punto_referencia = $("#punto_referencia").val();
        if (punto_referencia != '') {
            if(punto_referencia.match(validar_caracteresEspeciales2)){
                $("#punto_referencia").css("background-color", colorb);
            }else{
                $("#punto_referencia").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#punto_referencia").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_2) {
            $('#icon-ubicacion').hide();
        } else {
            $('#icon-ubicacion').show();
        }
    }
    function verificarParte3 () {
        tarjeta_3 = true;
        // VERIFICAR EL CAMPO DEL TIPO DE VIVIENDA
        let tipo_vivienda = document.formulario.tipo_vivienda.value;
        if (tipo_vivienda != '') {
            $(".radio_tipo_vivienda_label").addClass('inputBien');
        } else {
            $(".radio_tipo_vivienda_label").removeClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE TENENCIA DE LA VIVIENDA
        let tenencia_vivienda = document.formulario.tenencia_vivienda.value;
        if (tenencia_vivienda != '') {
            $(".radio_tenencia_vivienda_label").addClass('inputBien');
        } else {
            $(".radio_tenencia_vivienda_label").removeClass('inputBien');
        }
        // VERIFICAR EL CAMPO DEL TIPO DE AGUA
        let tipo_agua = document.formulario.tipo_agua.value;
        if (tipo_agua != '') {
            $(".radio_tipo_agua_label").addClass('inputBien');
        } else {
            $(".radio_tipo_agua_label").removeClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE ELECTRICIDAD
        let tipo_electricidad = document.formulario.tipo_electricidad.value;
        if (tipo_electricidad != '') {
            $(".radio_tipo_electricidad_label").addClass('inputBien');
        } else {
            $(".radio_tipo_electricidad_label").removeClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE EXCRETAS
        let tipo_excreta = document.formulario.tipo_excreta.value;
        if (tipo_excreta != '') {
            $(".radio_tipo_excreta_label").addClass('inputBien');
        } else {
            $(".radio_tipo_excreta_label").removeClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE LA BASURA
        let tipo_basura = document.formulario.tipo_basura.value;
        if (tipo_basura != '') {
            $(".radio_tipo_basura_label").addClass('inputBien');
        } else {
            $(".radio_tipo_basura_label").removeClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE OTROS EN LAS CARACTERISTICAS DE VIVIENDA
        let otros = $("#otros").val();
        if (otros != '') {
            if(otros.match(validar_caracteresEspeciales2)){
                $("#otros").css("background-color", colorb);
            }else{
                $("#otros").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#otros").css("background-color", colorn);
        }
        /////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////
        // VERIFICAR EL CAMPO DE MATERIALES DEL TECHO
        let techo = $("#techo").val();
        if (techo != '') {
            if(techo.match(validar_caracteresEspeciales2)){
                $("#techo").css("background-color", colorb);
            }else{
                $("#techo").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#techo").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE MATERIALES DEL PARED
        let pared = $("#pared").val();
        if (pared != '') {
            if(pared.match(validar_caracteresEspeciales2)){
                $("#pared").css("background-color", colorb);
            }else{
                $("#pared").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#pared").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE MATERIALES DEL PISO
        let piso = $("#piso").val();
        if (piso != '') {
            if (piso.match(validar_caracteresEspeciales2)) {
                $("#piso").css("background-color", colorb);
            } else {
                $("#piso").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#piso").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE DESCRIPCION DE VIA DE ACCESO
        let via_acceso = $("#via_acceso").val();
        if (via_acceso != '') {
            if (via_acceso.match(validar_caracteresEspeciales2)) {
                $("#via_acceso").css("background-color", colorb);
            }else{
                $("#via_acceso").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#via_acceso").css("background-color", colorn);
        }
        /////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////
        // VERIFICAR EL CAMPO DE 
        let sala = $("#sala").val();
        if (sala != '') {
            if(sala.match(validar_soloNumeros)){
                $("#sala").css("background-color", colorb);
            }else{
                $("#sala").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#sala").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE 
        let comedor = $("#comedor").val();
        if (comedor != '') {
            if(comedor.match(validar_soloNumeros)){
                $("#comedor").css("background-color", colorb);
            }else{
                $("#comedor").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#comedor").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE 
        let cocina = $("#cocina").val();
        if (cocina != '') {
            if(cocina.match(validar_soloNumeros)){
                $("#cocina").css("background-color", colorb);
            }else{
                $("#cocina").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#cocina").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE 
        let banio = $("#banio").val();
        if (banio != '') {
            if(banio.match(validar_soloNumeros)){
                $("#banio").css("background-color", colorb);
            }else{
                $("#banio").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#banio").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE 
        let dormitorio = $("#dormitorio").val();
        if (dormitorio != '') {
            if(dormitorio.match(validar_soloNumeros)){
                $("#dormitorio").css("background-color", colorb);
            }else{
                $("#dormitorio").css("background-color", colorm);
                tarjeta_3 = false;
            }
        } else {
            $("#dormitorio").css("background-color", colorn);
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_3) {
            $('#icon-vivienda').hide();
        } else {
            $('#icon-vivienda').show();
        }
    }
    function verificarParte4 () {
        tarjeta_4 = true;
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_4) {
            $('#icon-familiares').hide();
        } else {
            $('#icon-familiares').show();
        }
    }
    function verificarParte5 () {
        tarjeta_5 = true;
        $('.i_ingresos').each(function () {
            let valor_dinero = $(this).val();
            if(valor_dinero != ''){
                if(valor_dinero.match(validar_soloNumerosComa)){
                    $(this).css("background-color", colorb);
                }else{
                    $(this).css("background-color", colorm);
                    tarjeta_5 = false;
                }
            }else{
                $(this).css("background-color", colorm);
                tarjeta_5 = false;
            }
        });
        $('.i_egresos').each(function () {
            let valor_dinero = $(this).val();
            if(valor_dinero != ''){
                if(valor_dinero.match(validar_soloNumerosComa)){
                    $(this).css("background-color", colorb);
                }else{
                    $(this).css("background-color", colorm);
                    tarjeta_5 = false;
                }
            }else{
                $(this).css("background-color", colorm);
                tarjeta_5 = false;
            }
        });
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_5) {
            $('#icon-ingresos').hide();
        } else {
            $('#icon-ingresos').show();
        }
    }
    function verificarParte6 () {
        tarjeta_6 = true;
        // VERIFICAR EL CAMPO DE CONDICION DE LA VIVIENDA
        let condicion_vivienda = $("#condicion_vivienda").val();
        if (condicion_vivienda != '') {
            if(condicion_vivienda.match(validar_caracteresEspeciales)){
                $("#condicion_vivienda").css("background-color", colorb);
            }else{
                $("#condicion_vivienda").css("background-color", colorm);
                tarjeta_6 = false;
            }
        } else {
            $("#condicion_vivienda").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE CARACTERISTICAS GENERALES DE LA FAMILIA
        let caracteristicas_generales = $("#caracteristicas_generales").val();
        if (caracteristicas_generales != '') {
            if(caracteristicas_generales.match(validar_caracteresEspeciales)){
                $("#caracteristicas_generales").css("background-color", colorb);
            }else{
                $("#caracteristicas_generales").css("background-color", colorm);
                tarjeta_6 = false;
            }
        } else {
            $("#caracteristicas_generales").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE DIAGNOSTICO SOCIAL
        let diagnostico_social = $("#diagnostico_social").val();
        if (diagnostico_social != '') {
            if(diagnostico_social.match(validar_caracteresEspeciales)){
                $("#diagnostico_social").css("background-color", colorb);
            }else{
                $("#diagnostico_social").css("background-color", colorm);
                tarjeta_6 = false;
            }
        } else {
            $("#diagnostico_social").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE DIAGNOSTICO PRELIMINAR
        let diagnostico_preliminar = $("#diagnostico_preliminar").val();
        if (diagnostico_preliminar != '') {
            if(diagnostico_preliminar.match(validar_caracteresEspeciales)){
                $("#diagnostico_preliminar").css("background-color", colorb);
            }else{
                $("#diagnostico_preliminar").css("background-color", colorm);
                tarjeta_6 = false;
            }
        } else {
            $("#diagnostico_preliminar").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE CONCLUSION Y RECOMENDACIONES
        let conclusiones = $("#conclusiones").val();
        if (conclusiones != '') {
            if(conclusiones.match(validar_caracteresEspeciales)){
                $("#conclusiones").css("background-color", colorb);
            }else{
                $("#conclusiones").css("background-color", colorm);
                tarjeta_6 = false;
            }
        } else {
            $("#conclusiones").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE ENFERMOS EN LA FAMILIA
        let enfermos = document.formulario.enfermos.value;
        if (enfermos != '') {
            $(".radio_enfermos_label").addClass('inputBien');
        } else {
            $(".radio_enfermos_label").removeClass('inputBien');
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_6) {
            $('#icon-recomendaciones').hide();
        } else {
            $('#icon-recomendaciones').show();
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('.input_fecha').datepicker({ language: 'es' });
    $('.input_fecha').click(function () { fechaTemporal = $(this).val(); });
    $('.input_fecha').blur(function () { $(this).val(fechaTemporal); });
    $('.input_fecha').change(function () { fechaTemporal = $(this).val(); });
    $('#fecha_n').change(function (){ let edad = calcularEdad(fecha, $(this).val()); $('#edad').val(edad); });
    $('.solo-numeros').keypress(function (e) { if (!(e.keyCode >= 48 && e.keyCode <= 57)) { e.preventDefault(); } });
    $('#nombre_ocupacion').keypress(function (e){ if (e.keyCode == 13) { e.preventDefault(); } });
    /////////////////////////////////////////////////////////////////////
    $('#show_form').click(function (){
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);

        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');  // CHECK DE VALIDACION DE CEDULA Y RIF (ICONO)
        $('.ocultar-iconos').hide();  // ICONO DE CARGA DE CEDULA Y RIF (ICONO)
        $('.btn-recargar').hide(); // BOTON RECARGAR DE LAS CONSULTAS INDEPENDIENTES (CARGO, ACTIVIDAD ECONOMICA).
        $('.icon-alert').hide(); // ICONOS EN LAS PESTAÑAS DE LOS FORMULARIOS.

        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#municipio').html('<option value="">Elija un estado</option>');
        $('#parroquia').html('<option value="">Elija un municipio</option>');
        $('#ciudad_n').html('<option value="">Elija un estado</option>');

        document.formulario.reset();
        tipoEnvio               = 'Registrar';
        window.informe_social   = '';
        window.nacionalidad     = '';
        window.cedula           = '';
        window.eliminarFamiliar = [];
        $('#fecha').val(fecha);
        $('#edad').val(0);
        $('.campos_ingresos').val(0);
        $('.campos_ingresos').trigger('keyup');
        $('#tabla_datos_familiares tbody').html(mensaje_familia);
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
        $('#pills-datos-ciudadano-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // AGREGAR INFORMACION AL FORMULARIO DINAMICAMENTE.
    // REGISTRAR NUEVA OCUPACION
    $('#btn-ocupacion-aprendiz').click(function (e) {
        e.preventDefault();
        modalOcupacion();
        window.formulario_ocupacion = 'B'; // PARA EL FORMULARIO DE APRENDIZ
    });
    $('#btn-ocupacion-familiar').click(function (e) {
        e.preventDefault();
        modalOcupacion();
        window.formulario_ocupacion = ''; // PARA EL FORMULARIO DE APRENDIZ
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
            data.push({ name: 'formulario', value: window.formulario_ocupacion });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario_ocupacion').attr('disabled', true);
            $('#btn-registrar-ocupacion i.fa-save').addClass('fa-spin');
            $('#btn-registrar-ocupacion span').html('Guardando...');
            $('#contenedor-mensaje-ocupacion').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_informe_social.php',
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
                            if (window.formulario_ocupacion = 'A') {
                                // GUARDAMOS EL VALOR ANTERIOR SI HAY ALGUNA SELECCIONADA
                                let valor_anterior = $("#ocupacion").val();
                                $("#ocupacion").html('<option value="">Elija una opción</option>');
    
                                // CARGAR LAS OCUPACIONES DEL APRENDIS
                                dataOcupacion = resultados.ocupaciones;
                                if (dataOcupacion) {
                                    for (let i in dataOcupacion) {
                                        if (dataOcupacion[i].formulario == 'B') { $("#ocupacion").append('<option value="'+dataOcupacion[i].codigo +'">'+dataOcupacion[i].nombre+"</option>"); }
                                    }
                                    if ($("#ocupacion").html() == '<option value="">Elija una opción</option>') { $('#ocupacion').html('<option value="">No hay ocupaciones</option>'); }
                                } else {
                                    $('#ocupacion').html('<option value="">No hay ocupaciones</option>');
                                }

                                // SI HUBO UN DATOS SE VUELVE A ACOLOCAR PARA EVITAR SELECCIONARLA NUEVAMENTE
                                $('#ocupacion').val(valor_anterior);
                            }
                            
                            // CERRAMOS LA VENTANA.
                            $('#modal-ocupacion').modal('hide');
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
                            $('#contenedor-mensaje-ocupacion').html(contenedor_mensaje);

                            // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                            setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
                        }
    
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
    // FUNCIONES EXTRAS DE LOS CAMPOS.
    $('#estado').change(buscarCiudades);
    $('#loader-ciudad-reload').click(function () { $('#estado').trigger('change'); });
    $('#estado_n').change(buscarCiudades);
    $('#loader-ciudad_n-reload').click(function () { $('#estado_n').trigger('change'); });
    function buscarCiudades () {
        let campo_ciudad = '';
        if      ($(this).attr('name') == 'estado')      { campo_ciudad = 'ciudad'; }
        else if ($(this).attr('name') == 'estado_n')    { campo_ciudad = 'ciudad_n'; }

        if ($(this).val() != "") {
            $('#loader-'+campo_ciudad).show();
            $('#loader-'+campo_ciudad+'-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_informe_social.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer divisiones",
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

                        if (campo_ciudad == 'ciudad') {
                            let dataMunicipios = resultados.municipios;
                            if (dataMunicipios) {
                                $("#municipio").html('<option value="">Elija una opción</option>');
                                for (let i in dataMunicipios) {
                                    $("#municipio").append('<option value="'+dataMunicipios[i].codigo +'">'+dataMunicipios[i].nombre+"</option>");
                                }
                            } else {
                                $("#municipio").html('<option value="">No hay municipios</option>');
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
            if (campo_ciudad == 'ciudad') {
                $('#municipio').html('<option value="">Elija un estado</option>');
                $('#parroquia').html('<option value="">Elija un municipio</option>');
            }
        }
    }
    /////////////////////////////////////////////////////////////////////
    $('#loader-parroquia-reload').click(function () { $('#municipio').trigger('change'); });
    $('#municipio').change(function () {
        if ($(this).val() != '') {
            $('#loader-parroquia').show();
            $('#loader-parroquia-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_informe_social.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        opcion: 'Traer parroquias',
                        municipio: $('#municipio').val()
                    },
                    success: function (resultados) {
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


    
    $('.campos_ingresos').click(function () { if ($(this).val() == 0) { $(this).val(''); } });
    $('.campos_ingresos').blur(function () { if ($(this).val() == '') { $(this).val(0); } });
    $('.i_ingresos').keyup(function () {
        let ingreso_pension         = 0;
        let ingreso_seguro          = 0;
        let ingreso_pension_otras   = 0;
        let ingreso_sueldo          = 0;
        let otros_ingresos          = 0;

        if ($('#ingreso_pension').val() != '' && $('#ingreso_pension').val() != 0) { ingreso_pension = parseFloat($('#ingreso_pension').val()); }
        if ($('#ingreso_seguro').val() != '' && $('#ingreso_seguro').val() != 0) { ingreso_seguro = parseFloat($('#ingreso_seguro').val()); }
        if ($('#ingreso_pension_otras').val() != '' && $('#ingreso_pension_otras').val() != 0) { ingreso_pension_otras = parseFloat($('#ingreso_pension_otras').val()); }
        if ($('#ingreso_sueldo').val() != '' && $('#ingreso_sueldo').val() != 0) { ingreso_sueldo = parseFloat($('#ingreso_sueldo').val()); }
        if ($('#otros_ingresos').val() != '' && $('#otros_ingresos').val() != 0) { otros_ingresos = parseFloat($('#otros_ingresos').val()); }
        $('#total_ingresos').val(ingreso_pension + ingreso_seguro + ingreso_pension_otras + ingreso_sueldo + otros_ingresos);
    });
    $('.i_egresos').keyup(function () {
        let egreso_servicios        = 0;
        let egreso_alimentario      = 0;
        let egreso_educacion        = 0;
        let egreso_vivienda         = 0;
        let otros_egresos           = 0;

        if ($('#egreso_servicios').val() != '' && $('#egreso_servicios').val() != 0) { egreso_servicios = parseFloat($('#egreso_servicios').val()); }
        if ($('#egreso_alimentario').val() != '' && $('#egreso_alimentario').val() != 0) { egreso_alimentario = parseFloat($('#egreso_alimentario').val()); }
        if ($('#egreso_educacion').val() != '' && $('#egreso_educacion').val() != 0) { egreso_educacion = parseFloat($('#egreso_educacion').val()); }
        if ($('#egreso_vivienda').val() != '' && $('#egreso_vivienda').val() != 0) { egreso_vivienda = parseFloat($('#egreso_vivienda').val()); }
        if ($('#otros_egresos').val() != '' && $('#otros_egresos').val() != 0) { otros_egresos = parseFloat($('#otros_egresos').val()); }
        $('#total_egresos').val(egreso_servicios + egreso_alimentario + egreso_educacion + egreso_vivienda + otros_egresos);
    });
    $('.campo-montos').keypress(Montos);
    function Montos (e) { if (!(e.keyCode >= 48 && e.keyCode <= 57) && e.keyCode != 46) { e.preventDefault(); } }
    /////////////////////////////////////////////////////////////////////
    

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
                                        validarCedula = false;
                                        $('#spinner-cedula-confirm').addClass('fa-times text-danger');
                                        // $('#modal-aceptar-contacto').modal({backdrop: 'static', keyboard: false})
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

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA AGREGAR NUEVA FILAS EN LA TABLA DE FAMILIARSE DEL APRENDIZ.
    // $('#agregar_familiar').click(function () {
    //     if ($('#tabla_datos_familiares tbody').html() == mensaje_familia) { $('#tabla_datos_familiares tbody').empty(); }

    //     let cantidad = $('#tabla_datos_familiares tbody tr').length + 1;
    //     window.id_dinamico = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
    //     if (cantidad <= 10) {
    //         let contenido = '';
    //         contenido += '<tr id="familiar-'+window.id_dinamico+'">';
    //             contenido += '<input type="hidden" name="id_familiar[]" class="id_familiar">';

    //             // CEDULA DEL FAMILIAR
    //             contenido += '<td class="align-middle d-flex py-1 px-0">';
    //                 contenido += '<select name="nacionalidad_familiar[]" class="campos_formularios nacionalidad_familiar custom-select custom-select-sm" style="width: 40px; padding-right: 8px;">';
    //                     contenido += '<option value=""></option>';
    //                     contenido += '<option value="V">V</option>';
    //                     contenido += '<option value="E">E</option>';
    //                 contenido += '</select>';
    //                 contenido += '<input type="text" name="cedula_familiar[]" class="campos_formularios cedula_familiar form-control form-control-sm solo-numeros" placeholder="Ingrese la cédula" maxlength="8" autocomplete="off"/>';
    //             contenido += '</td>';

    //             // INFORMACION PERSONAL
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="nombre_familiar1[]" class="campos_formularios nombre_familiar1 form-control form-control-sm" placeholder="Nombre 1"></td>';
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="nombre_familiar2[]" class="campos_formularios nombre_familiar2 form-control form-control-sm" placeholder="Nombre 2"></td>';
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="apellido_familiar1[]" class="campos_formularios apellido_familiar1 form-control form-control-sm" placeholder="Apellido 1"></td>';
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="apellido_familiar2[]" class="campos_formularios apellido_familiar2 form-control form-control-sm" placeholder="Apellido 2"></td>';
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="fecha_familiar[]" class="campos_formularios fecha_familiar form-control form-control-sm fecha_familiar calcular_edad" style="background-color: #ffffff;" data-date-format="dd-mm-yyyy" placeholder="aaaa-mm-dd" readonly="true"></td>';
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="edad_familiar[]" class="campos_formularios edad_familiar form-control form-control-sm text-center" value="0" style="width: 56px;" readonly="true"></td>';
            
    //             // SEXO DEL FAMILIAR
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0">';
    //                 contenido += '<select name="sexo_familiar[]" class="sexo_familiar custom-select custom-select-sm agregar_parentezco" style="width: 46px; padding-right: 9px;" id-data-familiar="'+window.id_dinamico+'">';
    //                     contenido += '<option value=""></option>';
    //                     contenido += '<option value="M">M</option>';
    //                     contenido += '<option value="F">F</option>';
    //                 contenido += '</select>';
    //             contenido += '</td>';
            
    //             // PARENTESCO
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0">';
    //                 contenido += '<select name="parentesco_familiar[]" class="campos_formularios parentesco_familiar custom-select custom-select-sm">';
    //                     contenido += '<option value="">Opción</option>';
    //                 contenido += '</select>';
    //             contenido += '</td>';

    //             // OCUPACION FAMILIAR
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0">';
    //                 contenido += '<select name="ocupacion_familiar[]" class="campos_formularios ocupacion_familiar custom-select custom-select-sm" style="width: 120px;">';
    //                 if (dataOcupacion) {
    //                     contenido += '<option value="">Opción</option>';
    //                     for (let i in dataOcupacion) {
    //                         contenido += '<option value="'+dataOcupacion[i].codigo+'">'+dataOcupacion[i].nombre+'</option>';
    //                     }
    //                 } else {
    //                     contenido += '<option value="">No hay ocupaciones</option>';
    //                 }
    //                 contenido += '</select>';
    //             contenido += '</td>';

    //             // VERIFICAR SI TRABAJA
    //             contenido += '<td class="align-middle py-0 pr-1 pl-0">';
    //                 contenido += '<select name="trabaja_familiar[]" class="custom-select custom-select-sm trabajando storageFamilia" style="width: 46px; padding-right: 9px;" id-data-familiar="'+window.id_dinamico+'">';
    //                     contenido += '<option value=""></option>';
    //                     contenido += '<option value="S">S</option>';
    //                     contenido += '<option value="N">N</option>';
    //                 contenido += '</select>';
    //             contenido += '</td>';

    //             contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="ingresos_familiar[]" id="ingresos_familiar'+cantidad+'" class="form-control form-control-sm text-right monto-familiar" readonly="true"></td>';
    //             contenido += '<td class="align-middle py-0 px-0 text-center"><div class="custom-control custom-radio d-inline-block" style="width: 0px;"><input type="radio" class="custom-control-input" name="responsable_apre" value="'+cantidad+'"><label class="custom-control-label radio_responsable_apre_label" for="responsable_apre_'+cantidad+'"></label></div></td>';
    //             contenido += '<td class="py-1 px-0"><button type="button" class="btn btn-sm btn-danger delete-row"><i class="fas fa-times"></i></button></td>';
    //             contenido += '</tr>';
    //             $('#tabla_datos_familiares tbody').append(contenido);

    //         // $($('.fecha_familiar')[$('.fecha_familiar').length - 1]).datepicker({ language: 'es' });
    //         // // $($('.calcular_edad')[$('.calcular_edad').length - 1]).change(ca);
    //         // $($('.calcular_edad')[$('.calcular_edad').length - 1]).click(function () { fechaTemporal = $(this).val(); });
    //         // $($('.calcular_edad')[$('.calcular_edad').length - 1]).blur(function (){
    //         //     if ($(this).val() == '') {
    //         //         $(this).val(fechaTemporal);
    //         //     }
    //         // });
    //         // $($('.agregar_parentezco')[$('.agregar_parentezco').length - 1]).change(definirParentezco);
    //         // $($('.trabajando')[$('.trabajando').length - 1]).change(habilitarIngresos);
    //         // $($('.monto-familiar')[$('.monto-familiar').length - 1]).keypress(Montos);
    //         // $($('.delete-row')[$('.delete-row').length - 1]).click(eliminarFila);

    //         // $('tr[data-posicion="'+cantidad+'"] .storageFamilia').change(localStorageFamiliares);
    //         // $( $('tr[data-posicion="'+cantidad+'"] .localStorage-radio') ).click(guardarLocalStorage);

    //         // if (window.actualizar3 !== true) {
    //         //     if(window.editar !== true){
    //         //         let localFamilia = {nombre_familiar1: '', apellido_familiar2: '', apellido_familiar1: '', nombre_familiar2: '', fecha_familiar: '', edad_familiar: 0, sexo_familiar: '', parentesco_familiar: '', ocupacion_familiar: '', trabaja_familiar: '', ingresos_familiar: ''};
    //         //         localStorage.setItem('filaFamilia'+cantidad, JSON.stringify(localFamilia));
    //         //     }
    //         // }
    //     } else {
    //         // MENSAJE DE ERROR DE CONEXION.
    //         let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
    //         let contenedor_mensaje = '';
    //         contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
    //         contenedor_mensaje += '<i class="fas fa-times"></i> <span style="font-weight: 500;">Solo es permitido un maximo de '+maxFamiliares+' filas.</span>';
    //         contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    //         contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
    //         contenedor_mensaje += '</button>';
    //         contenedor_mensaje += '</div>';
    //         $('#contenedor-mensaje2').html(contenedor_mensaje);

    //         // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
    //         setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    //     }
    // });
    // // FUNCION PARA AGREGAR EL PARENTEZCO SEGUN EL SEXO DEL FAMILIAR.
    // function definirParentezco () {
    //     let idParentezco  = '#parentesco_familiar' + $(this).closest('tr').attr('data-posicion');
    //     let valorSexo = '';
    //     let valorParentezco = $(idParentezco).val();
    //     if ($(this).val() != '') { valorSexo = $(this).val(); }
    //     /////////////////////////////////////////////////////////////////
    //     $(idParentezco).empty();
    //     let arregloParentezco = [];
    //     if      (valorSexo == 'M') { arregloParentezco = dataParentescoM; }
    //     else if (valorSexo == 'F') { arregloParentezco = dataParentescoF; }

    //     $(idParentezco).append('<option value="">Opción</option>');
    //     for (let iPat in arregloParentezco) {
    //         $(idParentezco).append('<option value="'+iPat+'">'+arregloParentezco[iPat]+'</option>');
    //     }
    //     $(idParentezco).val(valorParentezco);
    // }
    // // ACTIVAR EL CAMPO DE INGRESOS EN LA TABLA FAMILIAR SI EL MIEMBRO ESTA TRABAJANDO.
    // function habilitarIngresos () {
    //     let posicion = $(this).closest('tr').attr('data-posicion');
    //     let idIngresos = '#ingresos_familiar' + posicion;
    //     $(idIngresos).attr('readonly',true);

    //     if ($(this).val() == 'S') {
    //         $(idIngresos).attr('readonly',false);
    //     } else {
    //         $(idIngresos).val('');

    //         ////////////////////
    //         if(window.editar !== true) {
    //             let arregloFamilia = JSON.parse(localStorage.getItem('filaFamilia'+posicion));
    //             arregloFamilia['ingresos_familiar'] = '';
    //             localStorage.setItem('filaFamilia'+posicion, JSON.stringify(arregloFamilia));
    //         }
    //     }
    // }
    // // FUNCION PARA ELIMINAR FILAS QUE YA NO SON NECESARIAS.
    // function eliminarFila () {
    //     let position = $(this).closest('tr').attr('data-posicion');
    //     ////////////////////
    //     if(window.editar !== true)
    //         localStorage.removeItem('filaFamilia'+position);
    //     else {
    //         let valor_id = $('#id_familiar'+position).val();
    //         if (valor_id != '' && valor_id != undefined)
    //             window.eliminarFamiliar.push(valor_id);
    //     }
    //     ////////////////////
    //     $(this).closest('tr').remove();
    //     ////////////////////
    //     let arregloRespaldo = [];
    //     if(window.editar !== true){
    //         for (let index = 1; index <= maxFamiliares; index++) {
    //             if (localStorage.getItem('filaFamilia'+index)) {
    //                 arregloRespaldo.push(JSON.parse(localStorage.getItem('filaFamilia'+index)));
    //                 localStorage.removeItem('filaFamilia'+index);
    //             }
    //         }
    //     }
    //     ////////////////////
    //     let cont = 1;
    //     if ($('#tabla_datos_familiares tbody tr').length > 0) {
    //         $('#tabla_datos_familiares tbody tr').each(function(){
    //             $(this).attr('data-posicion', cont);
    
    //             $($(this).children('td')[0]).html(cont);
    //             $($($(this)).children('input')[0]).attr('id', 'id_familiar'+cont);
    //             $($($(this).children('td')[1]).children('input')[0]).attr('id', 'nombre_familiar1'+cont);
    //             $($($(this).children('td')[2]).children('input')[0]).attr('id', 'nombre_familiar2'+cont);
    //             $($($(this).children('td')[3]).children('input')[0]).attr('id', 'apellido_familiar1'+cont);
    //             $($($(this).children('td')[4]).children('input')[0]).attr('id', 'apellido_familiar2'+cont);

    //             $($($(this).children('td')[5]).children('input')[0]).attr('id', 'fecha_familiar'+cont);
    //             $($($(this).children('td')[6]).children('input')[0]).attr('id', 'edad_familiar'+cont);
    //             $($($(this).children('td')[7]).children('select')[0]).attr('id', 'sexo_familiar'+cont);
    //             $($($(this).children('td')[8]).children('select')[0]).attr('id', 'parentesco_familiar'+cont);
    //             $($($(this).children('td')[9]).children('select')[0]).attr('id', 'ocupacion_familiar'+cont);
    //             $($($(this).children('td')[10]).children('select')[0]).attr('id', 'trabaja_familiar'+cont);
    //             $($($(this).children('td')[11]).children('input')[0]).attr('id', 'ingresos_familiar'+cont);
    
    //             $($($($(this).children('td')[12]).children('div')[0]).children('input')[0]).attr('id', 'responsable_apre_'+cont);
    //             $($($($(this).children('td')[12]).children('div')[0]).children('input')[0]).attr('value', cont);
    //             $($($($(this).children('td')[12]).children('div')[0]).children('label')[0]).attr('for', 'responsable_apre_'+cont);
    
    //             if(window.editar !== true)
    //                 localStorage.setItem('filaFamilia'+cont, JSON.stringify(arregloRespaldo[cont-1]));
    //             cont++;
    //         });
    //     } else {
    //         $('#tabla_datos_familiares tbody').html('<tr><td colspan="14" class="text-secondary text-center border-bottom p-2">Sin familiares agregados<i class="fas fa-user-times ml-3"></i></td></tr>');
    //     }
    // };
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarInforme() {
        let posicion = $(this).attr('data-posicion');
        if (dataListado.resultados[posicion].estatus_informe == 'E') {
            window.editar = true;
            /////////////////////
            $('#info_table').hide(400);
            $('#gestion_form').show(400);
            $('#form_title').html('Modificar');
            $('#carga_espera').show();
            tipoEnvio = 'Modificar';
            // LLENADO DEL FORMULARIO CON LOS DATOS REGISTRADOS.
            $.ajax({
                url : url+'controllers/c_informe_social.php',
                type: 'POST',
                data: {
                    opcion: 'Consultar determinado',
                    informe: dataListado.resultados[posicion].numero,
                    nacionalidad: dataListado.resultados[posicion].nacionalidad,
                    cedula: dataListado.resultados[posicion].cedula
                },
                success: function (resultados){
                    try {
                        let data = JSON.parse(resultados);

                        window.informe_social = dataListado.resultados[posicion].numero;
                        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
                        window.cedula = dataListado.resultados[posicion].cedula;
                        validarCedula = true;
                        // PRIMERA PARTE.
                        let fecha_nu = dataListado.resultados[posicion].fecha;
                        $('#fecha').val(fecha_nu.substr(8, 2)+'-'+fecha_nu.substr(5, 2)+'-'+fecha_nu.substr(0, 4));
                        $('#nacionalidad').val(dataListado.resultados[posicion].nacionalidad);
                        $('#cedula').val(dataListado.resultados[posicion].cedula);
                        $('#nombre_1').val(dataListado.resultados[posicion].nombre1);
                        $('#nombre_2').val(dataListado.resultados[posicion].nombre2);
                        $('#apellido_1').val(dataListado.resultados[posicion].apellido1);
                        $('#apellido_2').val(dataListado.resultados[posicion].apellido2);
                        $('#sexo').val(dataListado.resultados[posicion].sexo);
                        window.fecha_nacimiento = dataListado.resultados[posicion].fecha_n;
                        
                        fecha_nu = dataListado.resultados[posicion].fecha_n;
                        $('#fecha_n').val(fecha_nu.substr(8, 2)+'-'+fecha_nu.substr(5, 2)+'-'+fecha_nu.substr(0, 4));
                        $('#fecha_n').trigger('change');
                        $('#lugar_n').val(dataListado.resultados[posicion].lugar_n);
                        $('#ocupacion').val(dataListado.resultados[posicion].codigo_ocupacion);
                        document.formulario.estado_civil.value = dataListado.resultados[posicion].estado_civil;
                        document.formulario.grado_instruccion.value = dataListado.resultados[posicion].nivel_instruccion;
                        if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC')
                            $('#titulo').attr('readonly', false);

                        $('#titulo').val(dataListado.resultados[posicion].titulo_acade);
                        $('#alguna_mision').val(dataListado.resultados[posicion].mision_participado);
                        $('#telefono_1').val(dataListado.resultados[posicion].telefono1);
                        $('#telefono_2').val(dataListado.resultados[posicion].telefono2);
                        $('#correo').val(dataListado.resultados[posicion].correo);
                        $('#oficio').val(dataListado.resultados[posicion].codigo_oficio);
                        $('#turno').val(dataListado.resultados[posicion].turno);
                        // SEGUNDA PARTE.
                        $('#estado').val(dataListado.resultados[posicion].codigo_estado);
                        window.selectCiudad = true;
                        $('#estado').trigger('change');
                        $('#area').val(data.vivienda.tipo_area);
                        $('#direccion').val(dataListado.resultados[posicion].direccion);
                        $('#punto_referencia').val(data.vivienda.punto_referencia);
                        /////////////////////
                        let nacionalidad_fac = 'Venezolano';
                        if (dataListado.resultados[posicion].nacionalidad_fac != 'V') {
                            nacionalidad_fac = 'Extranjero';
                        }
                        let nombre_completo = dataListado.resultados[posicion].f_nombre1;
                        if (dataListado.resultados[posicion].f_nombre2 != null && dataListado.resultados[posicion].f_nombre2 != '') {
                            nombre_completo += ' '+dataListado.resultados[posicion].f_nombre2;
                        }
                        nombre_completo += ' '+dataListado.resultados[posicion].f_apellido1;
                        if (dataListado.resultados[posicion].f_apellido2 != null && dataListado.resultados[posicion].f_apellido2 != '') {
                            nombre_completo += ' '+dataListado.resultados[posicion].f_apellido2;
                        }
                        $('#f_nacionalidad').val(nacionalidad_fac);
                        $('#f_cedula').val(dataListado.resultados[posicion].cedula_facilitador);
                        $('#nombre_completo_f').val(nombre_completo);
                        // TERCERA PARTE.
                        document.formulario.tipo_vivienda.value = data.vivienda.tipo_vivienda;
                        document.formulario.tenencia_vivienda.value = data.vivienda.tenencia_vivienda;
                        document.formulario.tipo_agua.value = data.vivienda.agua;
                        document.formulario.tipo_electricidad.value = data.vivienda.electricidad;
                        document.formulario.tipo_excreta.value = data.vivienda.excretas;
                        document.formulario.tipo_basura.value = data.vivienda.basura;
                        $('#otros').val(data.vivienda.otros);
                        /////////////////////
                        $('#techo').val(data.vivienda.techo);
                        $('#pared').val(data.vivienda.paredes);
                        $('#piso').val(data.vivienda.piso);
                        $('#via_acceso').val(data.vivienda.via_acceso);
                        $('#sala').val(data.vivienda.sala);
                        $('#comedor').val(data.vivienda.comedor);
                        $('#cocina').val(data.vivienda.cocina);
                        $('#bano').val(data.vivienda.banos);
                        $('#dormitorio').val(data.vivienda.n_dormitorios);
                        // CUARTA PARTE.
                        window.eliminarFamiliar = [];
                        dataFamiliares = data.familiares;
                        for (let index = 0; index < data.familiares.length; index++) {
                            $('#agregar_familiar').trigger('click');
                            $('#id_familiar'+(index+1)).val(data.familiares[index].id_familiar);
                            $('#nombre_familiar1'+(index+1)).val(data.familiares[index].nombre1);
                            $('#nombre_familiar2'+(index+1)).val(data.familiares[index].nombre2);
                            $('#apellido_familiar1'+(index+1)).val(data.familiares[index].apellido1);
                            $('#apellido_familiar2'+(index+1)).val(data.familiares[index].apellido2);

                            fecha_nu = data.familiares[index].fecha_n;
                            $('#fecha_familiar'+(index+1)).val(fecha_nu.substr(8, 2)+'-'+fecha_nu.substr(5, 2)+'-'+fecha_nu.substr(0, 4));
                            $('#sexo_familiar'+(index+1)).val(data.familiares[index].sexo);
                            $('.agregar_parentezco').trigger('change');

                            $('#parentesco_familiar'+(index+1)).val(data.familiares[index].parentesco);
                            $('#ocupacion_familiar'+(index+1)).val(data.familiares[index].codigo_ocupacion);
                            $('#trabaja_familiar'+(index+1)).val(data.familiares[index].trabaja);
                            $('#ingresos_familiar'+(index+1)).val(data.familiares[index].ingresos);
                        }
                        $('.localStorage-radio').each(function () {
                            if ($(this).val() == dataListado.resultados[posicion].representante) {
                                $(this).prop('checked', true);
                            }
                        });

                        $('.calcular_edad').trigger('change');
                        $('.trabajando').trigger('change');
                        // QUINTA PARTE.
                        for (let i_dine in data.ingresos) {
                            $('#'+data.ingresos[i_dine].descripcion).val(data.ingresos[i_dine].cantidad);
                        }
                        $('.i_ingresos').trigger('keyup');
                        $('.i_egresos').trigger('keyup');
                        // SEXTA PARTE.
                        $('#condicion_vivienda').val(dataListado.resultados[posicion].condicion_vivienda);
                        $('#caracteristicas_generales').val(dataListado.resultados[posicion].caracteristicas_generales);
                        $('#diagnostico_social').val(dataListado.resultados[posicion].diagnostico_social);
                        $('#diagnostico_preliminar').val(dataListado.resultados[posicion].diagnostico_preliminar);
                        $('#conclusiones').val(dataListado.resultados[posicion].conclusiones);
                        document.formulario.enfermos.value = dataListado.resultados[posicion].enfermos;

                        setTimeout(() => {
                            verificarParte1();
                        verificarParte2();
                        verificarParte3();
                        verificarParte4();
                        verificarParte5();
                        verificarParte6();
                        }, 500);
                        $('#carga_espera').hide(400);
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function (){
                    console.log('error');
                }
            });
        } else {
            alert('Informe social inactivo, no puede editarlo');
        }
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();
        verificarParte2();
        verificarParte3();
        verificarParte4();
        verificarParte5();
        verificarParte6();

        if (tarjeta_1 && tarjeta_2 && tarjeta_3 && tarjeta_4 && tarjeta_5 && tarjeta_6) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion',         value: tipoEnvio });
            data.push({ name: 'informe_social', value: window.informe_social });
            data.push({ name: 'nacionalidad2',  value: window.nacionalidad });
            data.push({ name: 'cedula2',        value: window.cedula });
            data.push({ name: 'eliminar_fam',   value: JSON.stringify(window.eliminarFamiliar) });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario').attr('disabled', true);
            $('#guardar-datos i.fa-save').addClass('fa-spin');
            $('#guardar-datos span').html('Guardando...');

            // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
            $('#contenedor-mensaje').empty();
            $('#contenedor-mensaje2').empty();

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_informe_social.php',
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
    function aceptarPostulante (e) {
        e.preventDefault();
        // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
        let posicion = $(this).attr('data-posicion');
        let numero = dataListado.resultados[posicion].numero;
        ///////////////////////////////////////////////////////
        localStorage.setItem('numero_ficha', numero);
        location.href = url+'intranet/aprendiz'
    }
    function rechazarPostulante (e) {
        e.preventDefault();
        // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
        let posicion = $(this).attr('data-posicion');
        let numero = dataListado.resultados[posicion].numero;
        let estatus = 'R';
        ///////////////////////////////////////////////////////
        $.ajax({
            url : url+'controllers/c_informe_social.php',
            type: 'POST',
            data: {
                opcion: 'Rechazar',
                numero: numero,
                estatus: estatus
            },
            success: function (resultados) {
                if (resultados == 'Modificacion exitosa'){
                    
                    buscar_listado();
                } else {

                }
            },
            error: function () {
                
            }
        });
    }
    function reactivarPostulante (e) {
        e.preventDefault();
        // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
        let posicion = $(this).attr('data-posicion');
        let numero = dataListado.resultados[posicion].numero;
        let estatus = 'E';
        ///////////////////////////////////////////////////////
        $.ajax({
            url : url+'controllers/c_informe_social.php',
            type: 'POST',
            data: {
                opcion: 'Reactivar',
                numero: numero,
                estatus: estatus
            },
            success: function (resultados) {
                if (resultados == 'Modificacion exitosa'){
                    buscar_listado();
                } else {
                    
                }
            },
            error: function () {
            }
        });
    }
    function imprimirInforme (e) {
        e.preventDefault();

        let posicion = $(this).attr('data-posicion');
        let numero = dataListado.resultados[posicion].numero;
        window.open(url+'controllers/reportes/r_informe_social?n='+numero, '_blank');
    }
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
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
            url: url + "controllers/c_informe_social.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                // OBTENEMOS LA FECHA ACTUAL.
                fecha = resultados.fecha;

                // CARGAR LAS OCUPACIONES DEL APRENDIS
                dataOcupacion = resultados.ocupaciones;
                if (dataOcupacion) {
                    for (let i in dataOcupacion) {
                        if (dataOcupacion[i].formulario == 'B') { $("#ocupacion").append('<option value="'+dataOcupacion[i].codigo +'">'+dataOcupacion[i].nombre+"</option>"); }
                    }
                    if ($("#ocupacion").html() == '<option value="">Elija una opción</option>') { $('#ocupacion').html('<option value="">No hay ocupaciones</option>'); }
                } else {
                    $('#ocupacion').html('<option value="">No hay ocupaciones</option>');
                }

                // CARGAR LOS OFICIOS DEL APRENDIS
                dataOficios = resultados.oficios;
                if (dataOficios) {
                    for (let i in dataOficios) {
                        $("#oficio").append('<option value="'+dataOficios[i].codigo +'">'+dataOficios[i].nombre+"</option>");
                    }
                } else {
                    $('#oficio').html('<option value="">No hay oficios</option>');
                }

                // CARGAMOS LOS ESTADOS.
                let dataEstado = resultados.estados;
                if (dataEstado) {
                    for (let i in dataEstado) {
                        $("#estado").append('<option value="'+dataEstado[i].codigo +'">'+dataEstado[i].nombre+"</option>");
                        $("#estado_n").append('<option value="'+dataEstado[i].codigo +'">'+dataEstado[i].nombre+"</option>");
                    }
                } else {
                    $("#estado").html('<option value="">No hay estados</option>');
                    $("#estado_n").html('<option value="">No hay estados</option>');
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
});