$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let validar_soloNumeros         = /^([0-9])+$/;
    let validar_caracteresSimples   =/^([a-zá-úä-üA-ZÁ-úÄ-Üa. ])+$/;
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Üa.,-- ])+$/;
    let validar_caracteresEspeciales1=/^([a-zá-úä-üA-ZÁ-úÄ-Ü. ])+$/;
    let validar_caracteresEspeciales2=/^([a-zá-úä-üA-ZÁ-úÄ-Ü0-9.,--# ])+$/;
    let validar_correoElectronico   =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1, tarjeta_2, tarjeta_3, tarjeta_4, tarjeta_5;
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
                url : url+'controllers/c_aprendiz.php',
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
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].codigo+'</td>';
                            contenido_tabla += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
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
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay aprendices registrados.</span>';
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
        // VERIFICAR EL CAMPO DE NACIONALIDAD
        let tipo_ficha = $("#tipo_ficha").val();
        if(tipo_ficha != ''){
            $("#tipo_ficha").css("background-color", colorb);
        } else {
            $("#tipo_ficha").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let correlativo = $("#correlativo").val();
        if(correlativo != ''){
            if(correlativo.match(validar_soloNumeros)){
                $("#correlativo").css("background-color", colorb);
            }else{
                $("#correlativo").css("background-color", colorm);
                tarjeta_1 = false;
            }
        }else{
            $("#correlativo").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let numero_orden = $("#numero_orden").val();
        if(numero_orden != ''){
            if(numero_orden.match(validar_soloNumeros)){
                $("#numero_orden").css("background-color", colorb);
            }else{
                $("#numero_orden").css("background-color", colorm);
                tarjeta_1 = false;
            }
        }else{
            $("#numero_orden").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_1) {
            $('#icon-ficha').hide();
        } else {
            $('#icon-ficha').show();
        }
    }
    function verificarParte2 () {
        tarjeta_2 = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD
        let nacionalidad = $("#nacionalidad").val();
        if (nacionalidad != '') {
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE CEDULA
        let cedula = $("#cedula").val();
        if (cedula != '') {
            if (cedula.match(validar_soloNumeros)) {
                if (cedula.length >= 7) {
                    if (validarCedula) {
                        $("#cedula").css("background-color", colorb);
                    } else {
                        $("#cedula").css("background-color", colorm);
                        $("#nacionalidad").css("background-color", colorm);
                        estania1 = false;
                    }
                } else {
                    $("#cedula").css("background-color", colorm);
                    tarjeta_2 = false;
                }
            }else{
                $("#cedula").css("background-color", colorm);
                tarjeta_2 = false;
            }
        }else{
            $("#cedula").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL PRIMER NOMBRE
        let nombre_1 = $("#nombre_1").val();
        if (nombre_1 != '') {
            if(nombre_1.match(validar_caracteresSimples)){
                $("#nombre_1").css("background-color", colorb);
            }else{
                $("#nombre_1").css("background-color", colorm);
                tarjeta_2 = false;
            }
        }else{
            $("#nombre_1").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO NOMBRE
        let nombre_2 = $("#nombre_2").val();
        if (nombre_2 != '') {
            if(nombre_2.match(validar_caracteresSimples)){
                $("#nombre_2").css("background-color", colorb);
            }else{
                $("#nombre_2").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#nombre_2").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DEL PRIMER APELLIDO
        let apellido_1 = $("#apellido_1").val();
        if (apellido_1 != '') {
            if(apellido_1.match(validar_caracteresSimples)){
                $("#apellido_1").css("background-color", colorb);
            }else{
                $("#apellido_1").css("background-color", colorm);
                tarjeta_2 = false;
            }
        }else{
            $("#apellido_1").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO APELLIDO
        let apellido_2 = $("#apellido_2").val();
        if (apellido_2 != '') {
            if(apellido_2.match(validar_caracteresSimples)){
                $("#apellido_2").css("background-color", colorb);
            }else{
                $("#apellido_2").css("background-color", colorm);
                tarjeta_2 = false;
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
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE FECHA DE NACIMIENTO
        let fecha_n = $("#fecha_n").val();
        if (fecha_n != '') {
            let edad_cal = parseInt($('#edad').val());
            if (edad_cal >= 14 && edad_cal <= 19) {
                $("#fecha_n").css("background-color", colorb);
            } else {
                $("#fecha_n").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#fecha_n").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE LUGAR DE NACIMIENTO
        let lugar_n = $("#lugar_n").val();
        if(lugar_n != ''){
            if(lugar_n.match(validar_caracteresEspeciales)){
                $("#lugar_n").css("background-color", colorb);
            }else{
                $("#lugar_n").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#lugar_n").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE OCUPACION
        let ocupacion = $("#ocupacion").val();
        if(ocupacion != ''){
            $("#ocupacion").css("background-color", colorb);
        } else {
            $("#ocupacion").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE ESTADO CIVIL
        let estado_civil = document.formulario.estado_civil.value;
        $(".radio_estado_c_label").removeClass('inputMal');
        if(estado_civil == ''){
            $(".radio_estado_c_label").addClass('inputMal');
            tarjeta_2 = false;
        } else {
            $(".radio_estado_c_label").addClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE GRADO DE INSTRUCCION
        let grado_instruccion = document.formulario.grado_instruccion.value;
        $(".radio_educacion_label").removeClass('inputMal');
        if(grado_instruccion == ''){
            $(".radio_educacion_label").addClass('inputMal');
            tarjeta_2 = false;
        } else {
            $(".radio_educacion_label").addClass('inputBien');
        }
        // SI EL CAMPO DE INSTRUCCION ES SUPERIOR, VERIFICAR QUE EL CAMPO TITULO NO ESTE VACIO.
        if (grado_instruccion == 'SI' || grado_instruccion == 'SC') {
            let titulo_edu = $("#titulo").val();
            if(titulo_edu != ''){
                if(titulo_edu.match(validar_caracteresEspeciales)){
                    $("#titulo").css("background-color", colorb);
                }else{
                    $("#titulo").css("background-color", colorm);
                    tarjeta_2 = false;
                }
            } else {
                tarjeta_2 = false;
                $("#titulo").css("background-color", colorm);
            }
        }
        // VERIFICAR EL CAMPO DE MISIONES REALZADAS (NO OBLIGATORIA)
        let alguna_mision = $("#alguna_mision").val();
        if(alguna_mision != ''){
            if(alguna_mision.match(validar_caracteresEspeciales)){
                $("#alguna_mision").css("background-color", colorb);
            }else{
                $("#alguna_mision").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#alguna_mision").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE TELEFONO DEL CONTACTO (TELEFONO 1)
        let telefono_1 = $("#telefono_1").val();
        if (telefono_1 != "") {
            if (telefono_1.match(validar_soloNumeros)) {
                $("#telefono_1").css("background-color", colorb);
            } else {
                $("#telefono_1").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#telefono_1").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE TELEFONO DEL CONTACTO (TELEFONO 2, OPCIONAL)
        let telefono_2 = $("#telefono_2").val();
        if (telefono_2 != "") {
            if (telefono_2.match(validar_soloNumeros)) {
                $("#telefono_2").css("background-color", colorb);
            } else {
                $("#telefono_2").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#telefono_2").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE CORREO DEL CONTACTO (OPCIONAL)
        let correo = $("#correo").val();
        if (correo != "") {
            if (correo.match(validar_correoElectronico)) {
                $("#correo").css("background-color", colorb);
            } else {
                $("#correo").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#correo").css("background-color", colorn);
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_2) {
            $('#icon-ciudadano').hide();
        } else {
            $('#icon-ciudadano').show();
        }
    }
    function verificarParte3 () {
        tarjeta_3 = true;
        // VERIFICAR EL CAMPO DE ESTADO
        let estado = $("#estado").val();
        if(estado != ''){
            $("#estado").css("background-color", colorb);
        } else {
            $("#estado").css("background-color", colorm);
            tarjeta_3 = false;
        }
        // VERIFICAR EL CAMPO DE CIUDAD
        let ciudad = $("#ciudad").val();
        if(ciudad != ''){
            $("#ciudad").css("background-color", colorb);
        } else {
            $("#ciudad").css("background-color", colorm);
            tarjeta_3 = false;
        }
        // VERIFICAR EL CAMPO DE MUNICIPIO
        let municipio = $("#municipio").val();
        if(municipio != ''){
            $("#municipio").css("background-color", colorb);
        } else {
            $("#municipio").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE PARROQUIA
        let parroquia = $("#parroquia").val();
        if(parroquia != ''){
            $("#parroquia").css("background-color", colorb);
        } else {
            $("#parroquia").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE DIRECCION
        let direccion = $("#direccion").val();
        if(direccion != ''){
            if(direccion.match(validar_caracteresEspeciales2)){
                $("#direccion").css("background-color", colorb);
            }else{
                $("#direccion").css("background-color", colorm);
                tarjeta_3 = false;
            }
        }else{
            $("#direccion").css("background-color", colorm);
            tarjeta_3 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_3) {
            $('#icon-ubicacion').hide();
        } else {
            $('#icon-ubicacion').show();
        }
    }
    function verificarParte4 () {
        tarjeta_4 = true;
        // VERIFICAR EL CAMPO DE RIF DE EMPRESA
        let rif = $("#rif").val();
        if(rif != '') {
            $(".campos_formularios2").css("background-color", '');
        } else {
            $(".campos_formularios2").css("background-color", colorm);
            tarjeta_4 = false;
        }
        
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_4) {
            $('#icon-empresa').hide();
        } else {
            $('#icon-empresa').show();
        }
    }
    function verificarParte5 () {
        tarjeta_5 = true;

        // VERIFICAMOS QUE HAYAS ASIGNATURAS CARGADAS.
        if ($('.campo_asignatura').length > 0) {
            // REALIZAMOS UN CONTADOR PARA VERIFICAR CUANTAS ESTAN MARCADAS.
            let total_checked = 0;
            // RECORREMOS CADA UNOS DE LOS CHECKBOX.
            $('.campo_asignatura').each(function () { if ($(this).prop('checked')) { total_checked++ } });
            // SI NO HAY NINGUNO SELECCIONADO NO PERMITE GUARDAR.
            if (total_checked == 0) { tarjeta_5 = false;}
        } else {
            tarjeta_5 = false;
        }

        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_5) {
            $('#icon-asignatura').hide();
        } else {
            $('#icon-asignatura').show();
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        $('.campos_formularios2').css('background-color', '');
        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');
        $('.ocultar-iconos').hide();
        $('.btn-recargar').hide();
        $('.icon-alert').hide();
        
        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#municipio').html('<option value="">Elija un estado</option>');
        $('#parroquia').html('<option value="">Elija un municipio</option>');

        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';
        $('#fecha').val(fecha);

        // MODAL BUSCAR ASPIRANTE.
        window.deshacerBusqueda = true;
        document.form_buscar_participante.reset();
        $('#resultados-buscar-participante').empty();
        $('#resultados-buscar-participante').hide();
        $('#modal-buscar-participante').modal();
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
        $('#pills-datos-ficha-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    // VALIDACIONES DE REGISTROS A BASE DE DATOS.
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
    ///////////////////// FUNCIONES MANTENER FECHA //////////////////////
    $("#modal-buscar-participante").on("hidden.bs.modal", function () { if (window.deshacerBusqueda) { $('#show_table').trigger('click'); } });
    $('.solo-numeros').keypress(function (e) { if (!(e.keyCode >= 48 && e.keyCode <= 57)) { e.preventDefault(); } });
    $('.input_fecha').datepicker({ language: 'es' });
    $('.input_fecha').click(function () { fechaTemporal = $(this).val(); });
    $('.input_fecha').blur(function () { $(this).val(fechaTemporal); });
    $('.input_fecha').change(function () { fechaTemporal = $(this).val(); });
    $('#fecha_n').change(function () { $('#edad').val(calcularEdad(fecha, $('#fecha_n').val())); });
    /////////////////////////////////////////////////////////////////////
    $('#estado').change(buscarCiudades);
    $('#loader-ciudad-reload').click(function () { $('#estado').trigger('change'); });
    function buscarCiudades () {
        if ($(this).val() != "") {
            $('#loader-ciudad').show();
            $('#loader-ciudad-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_aprendiz.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer divisiones",
                        estado: $(this).val()
                    },
                    success: function (resultados) {
                        $('#loader-ciudad').hide();

                        // CARGAMOS LAS CIUDADES DEL ESTADO SELECCIONADO
                        let dataCiudades = resultados.ciudad;
                        if (dataCiudades) {
                            $("#ciudad").html('<option value="">Elija una opción</option>');
                            for (let i in dataCiudades) {
                                $("#ciudad").append('<option value="'+dataCiudades[i].codigo +'">'+dataCiudades[i].nombre+"</option>");
                            }
                        } else {
                            $("#ciudad").html('<option value="">No hay ciudades</option>');
                        }

                        // CARGAMOS LOS MUNICIPIOS DEL ESTADO SELECCIONADO
                        let dataMunicipios = resultados.municipio;
                        if (dataMunicipios) {
                            $("#municipio").html('<option value="">Elija una opción</option>');
                            for (let i in dataMunicipios) {
                                $("#municipio").append('<option value="'+dataMunicipios[i].codigo +'">'+dataMunicipios[i].nombre+"</option>");
                            }
                        } else {
                            $("#municipio").html('<option value="">No hay ciudades</option>');
                        }

                        // CIUDAD, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.valor_ciudad != undefined) {
                            $("#ciudad").val(window.valor_ciudad);
                            delete window.valor_ciudad;

                            if (window.valor_municipio == undefined) {
                                $('#carga_espera').hide(400);
                                verificarParte3();
                            }
                        }

                        // MUNICIPIO, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.valor_municipio != undefined) {
                            $("#municipio").val(window.valor_municipio);
                            $('#municipio').trigger('change');
                            delete window.valor_municipio;

                            if (window.valor_parroquia == undefined) {
                                $('#carga_espera').hide(400);
                                verificarParte3();
                            }
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
        }
    }
    $('#municipio').change(buscarMunicipios);
    $('#loader-parroquia-reload').click(function () { $('#municipio').trigger('change'); });
    function buscarMunicipios () {
        if ($(this).val() != "") {
            $('#loader-parroquia').show();
            $('#loader-parroquia-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_aprendiz.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion      : "Traer parroquias",
                        municipio   : $(this).val()
                    },
                    success: function (resultados) {
                        $('#loader-parroquia').hide();

                        // CARGAMOS LAS PARROQUIAS DEL MUNICIPIO SELECCIONADO
                        let dataParroquias = resultados.parroquia;
                        if (dataParroquias) {
                            $("#parroquia").html('<option value="">Elija una opción</option>');
                            for (let i in dataParroquias) {
                                $("#parroquia").append('<option value="'+dataParroquias[i].codigo +'">'+dataParroquias[i].nombre+"</option>");
                            }
                        } else {
                            $("#parroquia").html('<option value="">No hay parroquias</option>');
                        }

                        // PARROQUIA, SI EXISTE UN VALOR GUARDADO SE AGREGA AL CAMPO Y SE ELIMINA LA VARIABLE
                        if (window.valor_parroquia != undefined) {
                            $("#parroquia").val(window.valor_parroquia);
                            delete window.valor_parroquia;

                            $('#carga_espera').hide(400);
                            buscarAsignaturas();
                            verificarParte3();
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
    }
    $('#loader-asignaturas-reload').click(function () { buscarAsignaturas(); });
    function buscarAsignaturas () {
        if ($('#oficio').val() != "" && $('#modulo').val() != '') {
            $('#loader-asignaturas').show();
            $('#loader-asignaturas-reload').hide();

            setTimeout(() => {
                $.ajax({
                    url: url + "controllers/c_aprendiz.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        opcion: "Traer asignaturas",
                        oficio: window.oficio_aprendiz
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
    // CLASES EXTRAS Y LIMITACIONES
    $('.radio_educacion').click(function () {
        if ($(this).val() == 'SI' || $(this).val() == 'SC') { $('#titulo').attr('readonly', false); }
        else { $('#titulo').attr('readonly', true); $('#titulo').val(''); }
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    // MODAL BUSCAR PARTICIPANTE
    $('#btn-buscar-participante').click(function () {
        document.form_buscar_participante.reset();
        $('#resultados-buscar-participante').empty();
        $('#resultados-buscar-participante').hide();
        $('#modal-buscar-participante').modal();
    });
    $('#input-buscar-participante').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input-buscar-participante').keyup(function () {
        $('#resultados-buscar-participante').empty();
        $('#resultados-buscar-participante').hide();

        if ($('#input-buscar-participante').val() != '') {
            $('#resultados-buscar-participante').show();
            $('#resultados-buscar-participante').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>');
        
            setTimeout(function () {
                $.ajax({
                    url : url+'controllers/c_aprendiz.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        campo_busqueda  : $('#input-buscar-participante').val(),
                        opcion          : 'Traer participante',
                    },
                    success: function (resultados) {
                        window.dataParticipante = resultados.participantes;
                        window.tipoAgregarParti = 1;

                        $('#resultados-buscar-participante').empty();
                        if (window.dataParticipante) {
                            for (let i in window.dataParticipante) {
                                let contenido_div = '';
                                contenido_div += '<p class="d-inline-block w-100 m-0 py-1 px-2 agregar-participante" data-posicion="'+i+'">'
                                contenido_div += '<i class="fas fa-user"></i> ';
                                contenido_div += window.dataParticipante[i].nacionalidad+'-'+window.dataParticipante[i].cedula;
                                
                                contenido_div += ' '+window.dataParticipante[i].nombre1;
                                if (window.dataParticipante[i].nombre2 != '' && window.dataParticipante[i].nombre2 != null) { contenido_div += ' '+window.dataParticipante[i].nombre2; }
                                
                                contenido_div += ' '+window.dataParticipante[i].apellido1;
                                if (window.dataParticipante[i].apellido2 != '' && window.dataParticipante[i].apellido2 != null) { contenido_div += ' '+window.dataParticipante[i].apellido2; }
                                
                                contenido_div += '</p>';
                                $('#resultados-buscar-participante').append(contenido_div);
                            }
                            $('.agregar-participante').click(agregarParticipante);
                        } else {
                            $('#resultados-buscar-participante').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-times"></i> Sin resultados</span>');
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS MENSAJE DE "ERROR" EN EL CONTENEDOR.
                        let contenido_div = '';
                        contenido_div += '<span class="d-inline-block w-100 text-center text-danger py-1 px-2">';
                        contenido_div += '<i class="fas fa-ethernet"></i> [Error] No se pudo realizar la conexión.';
                        contenido_div += '<button type="button" id="btn-recargar-participante" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                        contenido_div += '</span>';
                        $('#resultados-buscar-participante').html(contenido_div);
                        $('#btn-recargar-participante').click(function () { $('#input-buscar-participante').trigger('keyup'); });
                        
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    function consultarParticipante () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        $('.campos_formularios').attr('disabled', true);
        $('.campos_formularios2').css('background-color', '');
        
        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';
        $('#fecha').val(fecha);

        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_aprendiz.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    opcion      : 'Traer participante',
                    campo_numero: localStorage.getItem('numero_ficha')
                },
                success: function (resultados) {
                    window.dataParticipante = resultados.participantes;
                    window.tipoAgregarParti = 2;
                    localStorage.removeItem('numero_ficha');
                    agregarParticipante();
                },
                error: function (errorConsulta) {
                    // MENSAJE DE ERROR DE CONEXION.
                    let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                    contenedor_mensaje += '<i class="fas fa-ethernet"></i> [Error] No se pudo realizar la conexión.';
                    contenedor_mensaje += '<button type="button" id="btn-recargar-participante2" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje2').html(contenedor_mensaje);
                    $('#btn-recargar-participante2').click(function () { consultarParticipante(); $('#alerta-'+idAlerta).alert('close') });

                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                    console.log(errorConsulta.responseText);
                }, timer: 15000
            });
        }, 500);
    }
    function agregarParticipante () {
        let posicion;
        if      (window.tipoAgregarParti == 1) { posicion = $(this).attr('data-posicion'); }
        else if (window.tipoAgregarParti == 2) { posicion = 0; }
        
        // GUARDAMOS EL ID DE LA FICHA Y LA CEDULA DEL APRENDIZ.
        window.informe_social   = window.dataParticipante[posicion].t_informe_social;
        window.nacionalidad     = window.dataParticipante[posicion].nacionalidad;
        window.cedula           = window.dataParticipante[posicion].cedula;

        if (window.dataParticipante[posicion].ficha_anterior != null) {
            $('#tipo_ficha').val('R');
            $('#rif_a').val();
            $('#nil_a').val();
            $('#razon_social_a').val();
            $('#actividad_economica_a').val();
            $('#telefono_1_ea').val();
            $('#estado_ea').val();
            $('#ciudad_ea').val();
            $('#direccion_ea').val();
        } else { $('#tipo_ficha').val('I'); }
        $("#tipo_ficha").css("background-color", colorb);

        // DATOS PERSONALES
        $('#nacionalidad').val(window.dataParticipante[posicion].nacionalidad);
        $('#cedula').val(window.dataParticipante[posicion].cedula);
        $('#cedula').trigger('blur');
        $('#nombre_1').val(window.dataParticipante[posicion].nombre1);
        $('#nombre_2').val(window.dataParticipante[posicion].nombre2);
        $('#apellido_1').val(window.dataParticipante[posicion].apellido1);
        $('#apellido_2').val(window.dataParticipante[posicion].apellido2);
        $('#sexo').val(window.dataParticipante[posicion].sexo);
        fecha_nu = window.dataParticipante[posicion].fecha_n;
        $('#fecha_n').val(fecha_nu.substr(8, 2)+'-'+fecha_nu.substr(5, 2)+'-'+fecha_nu.substr(0, 4));
        $('#fecha_n').trigger('change');
        $('#lugar_n').val(window.dataParticipante[posicion].lugar_n);
        $('#ocupacion').val(window.dataParticipante[posicion].codigo_ocupacion);
        window.oficio_aprendiz = window.dataParticipante[posicion].codigo_oficio;

        // ESTATUS DE LA PERSONA
        document.formulario.estado_civil.value = window.dataParticipante[posicion].estado_civil;
        document.formulario.grado_instruccion.value = window.dataParticipante[posicion].nivel_instruccion;
        if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC') { $('#titulo').attr('readonly', false); }
        $('#titulo').val(window.dataParticipante[posicion].titulo_acade);
        $('#alguna_mision').val(window.dataParticipante[posicion].mision_participado);
        
        // DATOS DE CONTACTO
        $('#telefono_1').val(window.dataParticipante[posicion].telefono1);
        $('#telefono_2').val(window.dataParticipante[posicion].telefono2);
        $('#correo').val(window.dataParticipante[posicion].correo);
        
        // UBICACION DE LA PERSONA
        $('#estado').val(window.dataParticipante[posicion].codigo_estado);

        // GUARDAMOS LOS DATOS DE CUIDAD, MUNICIPIO Y PARROQUIA.
        window.valor_ciudad = window.dataParticipante[posicion].codigo_ciudad;
        if (window.dataParticipante[posicion].codigo_municipio != null) { window.valor_municipio = window.dataParticipante[posicion].codigo_municipio; }
        if (window.dataParticipante[posicion].codigo_parroquia != null) { window.valor_parroquia = window.dataParticipante[posicion].codigo_parroquia; }
        
        $('#estado').trigger('change');
        $('#direccion').val(window.dataParticipante[posicion].direccion);

        window.deshacerBusqueda = false;
        $('#modal-buscar-participante').modal('hide');
        delete window.dataParticipante;

        $('#carga_espera').show();
        verificarParte2();
    }
    // MODAL BUSCAR EMPRESA
    $('#btn-buscar-empresa').click(function () {
        document.form_buscar_empresa.reset();
        $('#resultados-buscar-empresa').empty();
        $('#resultados-buscar-empresa').hide();
        $('#modal-buscar-empresa').modal();
    });
    $('#input-buscar-empresa').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input-buscar-empresa').keyup(function  () {
        $('#resultados-buscar-empresa').empty();
        $('#resultados-buscar-empresa').hide();

        if ($('#input-buscar-empresa').val() != '') {
            $('#resultados-buscar-empresa').show();
            $('#resultados-buscar-empresa').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>');
        
            setTimeout(function () {
                $.ajax({
                    url : url+'controllers/c_aprendiz.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        opcion: 'Traer empresa',
                        buscar: $('#input-buscar-empresa').val()
                    },
                    success: function (resultados) {
                        window.dataEmpresa = resultados.empresas;

                        $('#resultados-buscar-empresa').empty();
                        if (window.dataEmpresa) {
                            for (let i in window.dataEmpresa) {
                                let contenido_div = '';
                                contenido_div += '<p class="d-inline-block w-100 m-0 py-1 px-2 agregar-empresa" data-posicion="'+i+'">'
                                contenido_div += '<i class="fas fa-industry"></i> ';
                                contenido_div += window.dataEmpresa[i].rif;
                                contenido_div += ' '+window.dataEmpresa[i].razon_social;
                                contenido_div += '</p>';
                                $('#resultados-buscar-empresa').append(contenido_div);
                            }
                            $('.agregar-empresa').click(agregarEmpresa);
                        } else {
                            $('#resultados-buscar-empresa').html('<span class="d-inline-block w-100 text-center py-1 px-2"><i class="fas fa-times"></i> Sin resultados</span>');
                        }
                    },
                    error: function (errorConsulta) {
                        // MOSTRAMOS MENSAJE DE "ERROR" EN EL CONTENEDOR.
                        let contenido_div = '';
                        contenido_div += '<span class="d-inline-block w-100 text-center text-danger py-1 px-2">';
                        contenido_div += '<i class="fas fa-ethernet"></i> [Error] No se pudo realizar la conexión.';
                        contenido_div += '<button type="button" id="btn-recargar-empresas" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                        contenido_div += '</span>';
                        $('#resultados-buscar-empresa').html(contenido_div);
                        $('#btn-recargar-empresas').click(function () { $('#input-buscar-empresa').trigger('keyup'); });
                        
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    function agregarEmpresa () {
        let posicion = $(this).attr('data-posicion');

        $('#rif').val(window.dataEmpresa[posicion].rif);
        $('#nil').val(window.dataEmpresa[posicion].nil);
        $('#razon_social').val(window.dataEmpresa[posicion].razon_social);
        $('#actividad_economica').val(window.dataEmpresa[posicion].actividad_economica);
        $('#codigo_aportante').val(window.dataEmpresa[posicion].codigo_aportante);
        $('#telefono_1_e').val(window.dataEmpresa[posicion].telefono1);
        $('#telefono_2_e').val(window.dataEmpresa[posicion].telefono2);
        $('#correo_e').val(window.dataEmpresa[posicion].correo);
        $('#estado_e').val(window.dataEmpresa[posicion].estado);
        $('#ciudad_e').val(window.dataEmpresa[posicion].ciudad);
        $('#direccion_e').val(window.dataEmpresa[posicion].direccion);
        /////////////////////////////////////////////////////////////////
        $('#modal-buscar-empresa').modal('hide');
        verificarParte4();
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    // MODAL REGISTRAR OCUPACION
    $('#btn-agregar-ocupacion').click(function () {
        document.form_registrar_ocupacion.reset();
        $('#modal-registrar-ocupacion').modal();
    });
    $('#input_registrar_ocupacion').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input_registrar_ocupacion').keydown(function (e) { if (e.keyCode == 13) { $('#btn-registrar-ocupacion').trigger('click'); } });
    $('#btn-registrar-ocupacion').click(function (e) {
        e.preventDefault();
        /////////////////////////////////////////////////
        if ($('#input_registrar_ocupacion').val() != '') {
            let data = $("#form_registrar_ocupacion").serializeArray();
            data.push({ name: 'opcion', value: 'Registrar ocupacion' });

            $.ajax({
                url : url+'controllers/c_aprendiz.php',
                type: 'POST',
                data: data,
                success: function (resultados){
                    try {
                        if (resultados == 'Ya registrado') {
                            swal({
                                title   : 'No se registro',
                                text    : 'La ocupación ya esta registrada y para evitar valores repetidos no se realizo el registro.',
                                icon    : 'info',
                                buttons : false,
                                timer   : 4000
                            });
                        } else if (resultados == 'Error al registrar') {
                            swal({
                                title   : 'Error',
                                text    : 'Al parecer hubo un error al intentar registrar la ocupación, vuelva a intentarlo',
                                icon    : 'error',
                                buttons : false,
                                timer   : 4000
                            });
                        } else {
                            let data = JSON.parse(resultados);
                            let este_valor_o1 = $('#ocupacion').val();
                            $('#ocupacion').empty();
                            $('#ocupacion').append('<option value="">Elija una opción</option>');
                            if (data.ocupacion) {
                                for (let i in data.ocupacion) {
                                    $('#ocupacion').append('<option value="'+data.ocupacion[i].codigo+'">'+data.ocupacion[i].nombre+'</option>');
                                }
                                dataOcupacion = data.ocupacion;
                            } else {
                                $('#ocupacion').html('<option value="">No hay ocupaciones</option>');
                            }
                            $('#ocupacion').val(este_valor_o1);

                            swal({
                                title   : 'Exito',
                                text    : 'La ocupacion se registro con exito',
                                icon    : 'success',
                                buttons : false,
                                timer   : 4000
                            });
                        }
                        $('#modal-registrar-ocupacion').modal('hide');
                    } catch (error) {
                        swal({
                            title   : 'Error',
                            text    : 'Hubo un error al procesar los datos, revise la consola para mas información.',
                            icon    : 'error',
                            buttons : false,
                            timer   : 4000
                        });
                        console.log(resultados);
                    }
                },
                error: function (){
                    swal({
                        title   : 'Error',
                        text    : 'Hubo un error al conectar con el servidor y traer los datos.\nRevise su conexion de internet.',
                        icon    : 'error',
                        buttons : false,
                        timer   : 4000
                    });
                }
            });
        } else {
            swal({
                title   : 'Atención',
                text    : 'Para proceder con el registro el campo no puede estar vacío.',
                icon    : 'error',
                buttons : false,
                timer   : 4000
            });
        }
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro() {
        let posicion = $(this).attr('data-posicion');
        window.posicion = posicion;
        window.editar = true;
        /////////////////////
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#form_title').html('Modificar');
        $('#carga_espera').show();
        tipoEnvio = 'Modificar';
        /////////////////////
        // LLENADO DEL FORMULARIO CON LOS DATOS REGISTRADOS.
        $.ajax({
            url : url+'controllers/c_aprendiz.php',
            type: 'POST',
            data: {
                opcion: 'Consultar determinado',
                rif: dataListado.resultados[posicion].empresa_actual
            },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);

                    window.idficha = dataListado.resultados[posicion].numero;
                    window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
                    window.cedula = dataListado.resultados[posicion].cedula;
                    validarCedula = true;
                    // DATOS FICHAS
                    $('#tipo_ficha').val(dataListado.resultados[posicion].tipo_inscripcion);
                    $('#correlativo').val(dataListado.resultados[posicion].correlativo);
                    $('#numero_orden').val(dataListado.resultados[posicion].numero_orden);
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
                    // SEGUNDA PARTE.
                    $('#estado').val(dataListado.resultados[posicion].codigo_estado);
                    window.selectCiudad = true;
                    $('#estado').trigger('change');
                    $('#direccion').val(dataListado.resultados[posicion].direccion);
                    // TERCERA PARTE.
                    $('#rif').val(data.empresa.rif);
                    $('#nil').val(data.empresa.nil);
                    $('#razon_social').val(data.empresa.razon_social);
                    $('#actividad_economica').val(data.empresa.actividad_economica);
                    $('#codigo_aportante').val(data.empresa.codigo_aportante);
                    $('#telefono1_e').val(data.empresa.telefono1);
                    $('#telefono2_e').val(data.empresa.telefono2);
                    $('#estado_e').val(data.empresa.estado);
                    $('#ciudad_e').val(data.empresa.ciudad);
                    $('#direccion_e').val(data.empresa.direccion);
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function (){
                console.log('error');
            }
        });
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        verificarParte1();
        verificarParte2();
        verificarParte3();
        verificarParte4();
        verificarParte5();

        if (tarjeta_1 && tarjeta_2 && tarjeta_3 && tarjeta_4 && tarjeta_4) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion', value: tipoEnvio });

            // ENVIAMOS LOS DATOS GUARDADO EN LAS VARIABLES.
            data.push({ name: 'ficha_aprendiz', value: window.idficha });
            data.push({ name: 'informe_social', value: window.informe_social });
            data.push({ name: 'nacionalidad2',  value: window.nacionalidad });
            data.push({ name: 'cedula2',        value: window.cedula });
            
            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_aprendiz.php',
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
    function cambiarEstatus () {
        let posicion = $(this).attr('data-posicion');
        let codigo = dataListado.resultados[posicion].codigo;
        let estatus = '';
        if (dataListado.resultados[posicion].estatus == 'A')
            estatus = 'I';
        else
            estatus = 'A';
        
        $.ajax({
            url : url+'controllers/c_oficio.php',
            type: 'POST',
            data: {
                opcion: 'Estatus',
                codigo: codigo,
                estatus: estatus
            },
            success: function (resultados) {
                alert(resultados);
                if (resultados == 'Modificacion exitosa')
                    buscar_listado();
            },
            error: function () {
                console.log('error');
            }
        });
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
            url: url + "controllers/c_aprendiz.php",
            type: "POST",
            dataType: 'JSON',
            data: { opcion: "Traer datos" },
            success: function (resultados) {
                // ESTABLECEMOS CUAL ES LA FECHA ACTUAL.
                fecha = resultados.fecha;

                // CARGAMOS LAS OCUPACIONES
                let dataOcupacion = resultados.ocupacion;
                if (dataOcupacion) {
                    for (let i in dataOcupacion) {
                        $("#ocupacion").append('<option value="'+dataOcupacion[i].codigo +'">'+dataOcupacion[i].nombre+"</option>");
                    }
                } else {
                    $("#ocupacion").html('<option value="">No hay ocupaciones</option>');
                }

                // CARGAMOS LOS ESTADOS.
                let dataEstado = resultados.estado;
                if (dataEstado) {
                    for (let i in dataEstado) {
                        $("#estado").append('<option value="'+dataEstado[i].codigo +'">'+dataEstado[i].nombre+"</option>");
                    }
                } else {
                    $("#estado").html('<option value="">No hay estados</option>');
                }
                buscar_listado();

                if (localStorage.getItem('numero_ficha')) { consultarParticipante(); } 
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