$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let ER_codigoFormulario = /^([0-9a-zA-Z-])+$/;
    let validar_caracteresEspeciales=/^([a-zá-úä-üA-ZÁ-úÄ-Üa.,-- ])+$/;
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
    let validarCedula = false;
    $('#nacionalidad').change(function () {
        $('#cedula').trigger('blur');
    });
    $('#cedula').blur(function (){
        validarCedula = false;
        $('#spinner-cedula').hide();
        $('#spinner-cedula-confirm').hide();
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger');

        if($('#nacionalidad').val() != '') {
            if ($('#cedula').val() != '') {
                if (window.nacionalidad != $('#nacionalidad').val() || window.cedula != $('#cedula').val()) {
                    if ($('#cedula').val().length > 7) {
                        $.ajax({
                            url : url+'controllers/c_empresa.php',
                            type: 'POST',
                            data: {
                                opcion: 'Verificar cedula',
                                nacionalidad: $('#nacionalidad').val(),
                                cedula: $('#cedula').val()
                            },
                            success: function (resultados) {
                                try {
                                    $('#spinner-cedula').hide();
                                    //////////////////////////////////
                                    let data = JSON.parse(resultados);
                                    if (data) {
                                        swal({
                                            title   : 'Aprendiz ya registrado',
                                            text    : 'Esta persona ya esta registrada en el sistema',
                                            icon    : 'error',
                                            buttons : false,
                                            timer   : 4000
                                        });
                                        $('#spinner-cedula-confirm').addClass('fa-times text-danger');
                                    } else {
                                        $('#spinner-cedula-confirm').addClass('fa-check text-success');
                                        validarCedula = true;
                                    }
                                    $('#spinner-cedula-confirm').show(200);
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
                            error: function () {
                                swal({
                                    title   : 'Error',
                                    text    : 'Hubo un error al conectar con el servidor y traer los datos.\nRevise su conexion de internet.',
                                    icon    : 'error',
                                    buttons : false,
                                    timer   : 4000
                                });
                            }
                        });
                    }
                } else {
                    validarCedula = true;
                }
            }
        }
    });
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
            if(correlativo.match(ER_NumericoSinEspacios)){
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
            if(numero_orden.match(ER_NumericoSinEspacios)){
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
        if(nacionalidad != ''){
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE CEDULA
        let cedula = $("#cedula").val();
        if(cedula != ''){
            if(cedula.match(ER_NumericoSinEspacios)){
                if (cedula.length > 7) {
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
        if(nombre_1 != ''){
            if(nombre_1.match(ER_caracteresConEspacios)){
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
        if(nombre_2 != ''){
            if(nombre_2.match(ER_caracteresConEspacios)){
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
        if(apellido_1 != ''){
            if(apellido_1.match(ER_caracteresConEspacios)){
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
        if(apellido_2 != ''){
            if(apellido_2.match(ER_caracteresConEspacios)){
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
        if(sexo != ''){
            $("#sexo").css("background-color", colorb);
        } else {
            $("#sexo").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL CAMPO DE FECHA DE NACIMIENTO
        let fecha_n = $("#fecha_n").val();
        if(fecha_n != ''){
            let edad_cal = parseInt($('#edad').val());
            if (edad_cal >= 17 && edad_cal <= 19) {
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
            if(lugar_n.match(ER_alfaNumericoCompleto)){
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
                if(titulo_edu.match(ER_caracteresConEspacios)){
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
            if(alguna_mision.match(ER_alfaNumericoConEspacios)){
                $("#alguna_mision").css("background-color", colorb);
            }else{
                $("#alguna_mision").css("background-color", colorm);
                tarjeta_2 = false;
            }
        } else {
            $("#alguna_mision").css("background-color", colorn);
        }
        // VERIFICAR EL TELEFONO DE CASA
        let telefono_1 = $("#telefono_1").val();
        if(telefono_1 != ''){
            if(telefono_1.match(ER_NumericoSinEspacios)){
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
        }else{
            $("#telefono_1").css("background-color", colorm);
            tarjeta_2 = false;
        }
        // VERIFICAR EL TELEFONO CELULAR
        let telefono_2 = $("#telefono_2").val();
        if(telefono_2 != ''){
            if(telefono_2.match(ER_NumericoSinEspacios)){
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
        }else{
            $("#telefono_2").css("background-color", colorn);
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let correo = $("#correo").val();
        if(correo != ''){
            if(correo.match(ER_email)){
                $("#correo").css("background-color", colorb);
            }else{
                $("#correo").css("background-color", colorm);
                tarjeta_2 = false;
            }
        }else{
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
            if(direccion.match(ER_alfaNumericoCompleto)){
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
        // VERIFICAR EL CAMPO DE 
        let rif = $("#rif").val();
        if(rif != ''){
            $(".data_empresa").css("background-color", '');
        }else{
            $(".data_empresa").css("background-color", colorm);
            tarjeta_4 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (tarjeta_4) {
            $('#icon-empresa').hide();
        } else {
            $('#icon-empresa').show();
        }
    }
    /////////////////////////////////////////////////////////////////////
    ///////////////////// FUNCIONES MANTENER FECHA //////////////////////
    $('.input_fecha').datepicker({ language: 'es' });
    $('.input_fecha').click(function () { fechaTemporal = $(this).val(); });
    $('.input_fecha').blur(function () { $(this).val(fechaTemporal); });
    $('.input_fecha').change(function () { fechaTemporal = $(this).val(); });
    $('#fecha_n').change(function () { $('#edad').val(calcularEdad(fecha, $('#fecha_n').val())); });
    /////////////////////////////////////////////////////////////////////
    $('#estado').change(function () {
        if ($('#estado').val() != '') {
            $.ajax({
                url : url+'controllers/c_informe_social.php',
                type: 'POST',
                data: { opcion: 'Traer divisiones', estado: $(this).val() },
                success: function (resultados) {
                    $('#ciudad').empty();
                    $('#municipio').empty();
                    try {
                        let data = JSON.parse(resultados);
                        if (data.ciudad) {
                            $('#ciudad').append('<option value="">Elija una opción</option>');
                            for(let i in data.ciudad){
                                $('#ciudad').append('<option value="'+data.ciudad[i].codigo+'">'+data.ciudad[i].nombre+'</option>');
                            }
                        } else {
                            $('#ciudad').html('<option value="">No hay ciudades</option>');
                        }
                        if (data.municipio) {
                            $('#municipio').append('<option value="">Elija una opción</option>');
                            for(let i in data.municipio){
                                $('#municipio').append('<option value="'+data.municipio[i].codigo+'">'+data.municipio[i].nombre+'</option>');
                            }
                        } else {
                            $('#municipio').html('<option value="">No hay municipios</option>');
                        }

                        if (window.buscarCiudadFicha == true) {
                            window.buscarCiudadFicha = false;
                            $('#ciudad').val(dataListadoEmp.codigo_ciudad);

                            if (dataListadoEmp.codigo_municipio != null) {
                                window.buscarParroquiaFicha = true;
                                $('#municipio').val(dataListadoEmp.codigo_municipio);
                                $('#municipio').trigger('change');
                            } else {
                                $('#carga_espera').hide(400);
                            }
                        }

                        if (window.selectCiudad === true) {
                            window.selectCiudad = false;

                            let ciudadValor = '';
                            let municipioValor = '';

                            ciudadValor = dataListado.resultados[window.posicion].codigo_ciudad;
                            municipioValor = dataListado.resultados[window.posicion].codigo_municipio;
                            window.selectMunicipio = true;

                            $('#ciudad').val(ciudadValor);
                            $('#municipio').val(municipioValor);
                            $('#municipio').trigger('change');
                            $('#carga_espera').hide(400);
                            
                            if (municipioValor == null) {
                                verificarParte1();
                                verificarParte2();
                                verificarParte3();
                                verificarParte4();
                            }
                        }
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        } else {
            $('#ciudad').html('<option value="">Elija un estado</option>');
            $('#municipio').html('<option value="">Elija un estado</option>');
        }
        $('#parroquia').html('<option value="">Elija un municipio</option>');
    });
    $('#municipio').change(function () {
        if ($(this).val() != '') {
            $.ajax({
                url : url+'controllers/c_informe_social.php',
                type: 'POST',
                data: { opcion: 'Traer parroquias', municipio: $(this).val() },
                success: function (resultados) {
                    $('#parroquia').empty();
                    try {
                        let data = JSON.parse(resultados);
                        if (data.parroquia) {
                            $('#parroquia').append('<option value="">Elija una opción</option>');
                            for(let i in data.parroquia){
                                $('#parroquia').append('<option value="'+data.parroquia[i].codigo+'">'+data.parroquia[i].nombre+'</option>');
                            }
                        } else {
                            $('#parroquia').html('<option value="">No hay parroquias</option>');
                        }
                        if (window.buscarParroquiaFicha == true) {
                            window.buscarCiudadFicha = false;
                            $('#parroquia').val(dataListadoEmp.codigo_parroquia);
                            $('#carga_espera').hide(400);
                        }

                        if (window.selectMunicipio === true) {
                            window.selectMunicipio = false;

                            let parroquiValor = '';
                            parroquiValor = dataListado.resultados[window.posicion].codigo_parroquia;
                            $('#parroquia').val(parroquiValor);

                            verificarParte1();
                            verificarParte2();
                            verificarParte3();
                            verificarParte4();
                        }
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        } else {
            $('#parroquia').html('<option value="">Elija un municipio</option>');
        }
    });
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $('.radio_educacion').click(function () {
        if ($(this).val() == 'SI' || $(this).val() == 'SC')
            $('#titulo').attr('readonly', false);
        else {
            $('#titulo').attr('readonly', true);
            $('#titulo').val('');
        }
    });
    $('#show_form').click(function () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        
        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';

        // MODAL BUSCAR ASPIRANTE.
        document.form_buscar_participante.reset();
        $('#btn-hide-modal-participante').hide();
        $('#resultados-buscar-participante').empty();
        $('#resultados-buscar-participante').hide();
        $('#modal-buscar-participante').modal({backdrop: 'static', keyboard: false});
    });
    $('#show_table').click(function () {
        $('#info_table').show(400); // MUESTRA TABLA DE RESULTADOS.
        $('#gestion_form').hide(400); // ESCONDE FORMULARIO
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    /////////////////////////////////////////////////////////////////////
    // MODAL BUSCAR PARTICIPANTE
    $('#btn-buscar-participante').click(function () {
        document.form_buscar_participante.reset();
        $('#btn-hide-modal-participante').show();
        $('#resultados-buscar-participante').empty();
        $('#resultados-buscar-participante').hide();
        $('#modal-buscar-participante').modal();
    });
    $('#input-buscar-participante').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input-buscar-participante').keyup(function () {
        window.buscarParticipante = true;
        if (window.buscarParticipante) {
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
                            contenido_div += '<button type="button" id="btn-recargar-contenedor" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                            contenido_div += '</span>';
                            $('#resultados-buscar-participante').html(contenido_div);
                            $('#btn-recargar-contenedor').click(function () { $('#input-buscar-participante').trigger('keyup'); });
                            
                            // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                            console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                            console.log(errorConsulta.responseText);
                        }, timer: 15000
                    });
                }, 500);
            }
        }
    });
    function consultarParticipante () {
        $('#info_table').hide(400); // ESCONDE TABLA DE RESULTADOS.
        $('#gestion_form').show(400); // MUESTRA FORMULARIO
        $('#form_title').html('Registrar');
        $('.campos_formularios').css('background-color', colorn);
        
        document.formulario.reset();
        tipoEnvio       = 'Registrar';
        window.codigo   = '';

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
                // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                console.log(errorConsulta.responseText);
            }, timer: 15000
        });
    }
    function agregarParticipante () {
        let posicion;
        if      (window.tipoAgregarParti == 1) { posicion = $(this).attr('data-posicion'); }
        else if (window.tipoAgregarParti == 2) { posicion = 0; }
        
        // GUARDAMOS EL ID DE LA FICHA Y LA CEDULA DEL APRENDIZ.
        window.informe_social   = 1;
        window.nacionalidad     = window.dataParticipante[posicion].nacionalidad;
        window.cedula           = window.dataParticipante[posicion].cedula;
        
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
        document.formulario.estado_civil.value = window.dataParticipante[posicion].estado_civil;
        document.formulario.grado_instruccion.value = window.dataParticipante[posicion].nivel_instruccion;
        if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC') { $('#titulo').attr('readonly', false); }
        $('#titulo').val(window.dataParticipante[posicion].titulo_acade);
        $('#alguna_mision').val(window.dataParticipante[posicion].mision_participado);
        $('#telefono_1').val(window.dataParticipante[posicion].telefono1);
        $('#telefono_2').val(window.dataParticipante[posicion].telefono2);
        $('#correo').val(window.dataParticipante[posicion].correo);
        // SEGUNDA PARTE.
        $('#estado').val(window.dataParticipante[posicion].codigo_estado);
        window.buscarCiudadFicha = true;
        $('#estado').trigger('change');
        $('#direccion').val(window.dataParticipante[posicion].direccion);

        $('#modal-buscar-participante').modal('hide');
        delete window.dataParticipante;
    }
    /////////////////////////////////////////////////////////////////////
    // MODAL BUSCAR EMPRESA
    $('#btn-buscar-empresa').click(function () {
        document.form_buscar_empresa.reset();
        $('#resultados-buscar-empresa').empty();
        $('#resultados-buscar-empresa').hide();
        $('#modal-buscar-empresa').modal();
    });
    $('#input-buscar-empresa').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input-buscar-empresa').keyup(function  () {
        window.buscarParticipante = true;
        if (window.buscarEmpresa) {
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
                                    contenido_div += '<i class="fas fa-user"></i> ';
                                    contenido_div += window.dataEmpresa[i].rif;
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
                            contenido_div += '<button type="button" id="btn-recargar-contenedor" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                            contenido_div += '</span>';
                            $('#resultados-buscar-participante').html(contenido_div);
                            $('#btn-recargar-contenedor').click(function () { $('#input-buscar-empresa').trigger('keyup'); });
                            
                            // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                            console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                            console.log(errorConsulta.responseText);
                        }, timer: 15000
                    });
                }, 500);
            }
        }
    });
    function agregarEmpresa () {
        let posicion = $(this).attr('data-posicion');
        $('#rif').val(window.dataEmpresa[posicion].rif);
        $('#nil').val(window.dataEmpresa[posicion].nil);
        $('#razon_social').val(window.dataEmpresa[posicion].razon_social);
        $('#actividad_economica').val(window.dataEmpresa[posicion].actividad_economica);
        $('#codigo_aportante').val(window.dataEmpresa[posicion].codigo_aportante);
        $('#telefono1_e').val(window.dataEmpresa[posicion].telefono1);
        $('#telefono2_e').val(window.dataEmpresa[posicion].telefono2);
        $('#estado_e').val(window.dataEmpresa[posicion].estado);
        $('#ciudad_e').val(window.dataEmpresa[posicion].ciudad);
        $('#direccion_e').val(window.dataEmpresa[posicion].direccion);
        /////////////////////////////////////////////////////////////////
        $('#modal-buscar-empresa').modal('hide');
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    // MODAL REGISTRAR OCUPACION
    $('#btn-agregar-ocupacion').click(function () {
        limpiarModalRegistrarOcupacion();
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
    function limpiarModalRegistrarOcupacion () {
        document.form_registrar_ocupacion.reset();
    }
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

                    window.ficha = dataListado.resultados[posicion].numero;
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
        if (tarjeta_1 && tarjeta_2 && tarjeta_3 && tarjeta_4) {
            enviarFormulario();
        }
    });
    function enviarFormulario () {
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        data.push({ name: 'estado_civil', value: document.formulario.estado_civil.value });
        data.push({ name: 'grado_instruccion', value: document.formulario.grado_instruccion.value });
        ///////////////////
        data.push({ name: 'informe_social', value: window.informe_social });
        data.push({ name: 'ficha_aprendiz', value: window.ficha });
        data.push({ name: 'nacionalidad_v', value: window.nacionalidad });
        data.push({ name: 'cedula_v', value: window.cedula });
        
        $.ajax({
            url : url+'controllers/c_aprendiz.php',
            type: 'POST',
            data: data,
            success: function (resultados) {
                if (resultados == 'Registro exitoso' || resultados == 'Modificacion exitosa'){
                    swal({
                        title   : resultados,
                        text    : 'La operación se ejecuto con exito.',
                        icon    : 'success',
                        buttons : false,
                        timer   : 4000
                    });

                    $('#show_table').trigger('click');
                    buscar_listado();
                } else {
                    swal({
                        title   : 'Información',
                        text    : resultados,
                        icon    : 'info',
                        buttons : false,
                        timer   : 4000
                    });
                }
            },
            error: function () {
                swal({
                    title   : 'Error',
                    text    : 'Hubo un error al conectar con el servidor y traer los datos.\nRevise su conexion de internet.',
                    icon    : 'error',
                    buttons : false,
                    timer   : 4000
                });
            }
        });
    }
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