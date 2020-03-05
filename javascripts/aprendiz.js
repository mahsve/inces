$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    let ER_caracteresConEspacios = /^([a-zA-Z,.\x7f-\xff](\s[a-zA-Z,.\x7f-\xff])*)+$/;
    let ER_alfaNumericoConEspacios=/^([a-zA-Z0-9,.\x7f-\xff](\s[a-zA-Z0-9,.\x7f-\xff])*)+$/;
    let ER_alfaNumericoCompleto=/^([a-zA-Z0-9,.#"\x7f-\xff](\s[a-zA-Z0-9,.#"\x7f-\xff])*)+$/;
    let ER_NumericoSinEspacios=/^([0-9])+$/;
    let ER_NumericoConComa=/^([0-9.])+$/;
    let ER_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    /////////////////////////////////////////////////////////////////////
    let pestania1, pestania2, pestania3, pestania4;
    /////////////////////////////////////////////////////////////////////
    let colorb = "#d4ffdc";
    let colorm = "#ffc6c6";
    let colorn = "#ffffff";

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // DATOS DE LA TABLA Y PAGINACION 
    let numeroDeLaPagina    = 1;
    $('#cantidad_a_buscar').change(restablecerN);
    $('#ordenar_por').change(restablecerN);
    $('#campo_ordenar').change(restablecerN);
    $('#campo_busqueda').keydown(function (e) {
        if (e.keyCode == 13) {
            numeroDeLaPagina = 1;
            buscar_listado();
            window.actualizar_busqueda = false;
        } else
            window.actualizar_busqueda = true;
    });
    $('#campo_busqueda').blur(function () {
        if (window.actualizar_busqueda)
            buscar_listado();
    });
    $('#buscar_estatus').change(restablecerN);
    /////////////////////////////////////////////////////////////////////
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR UNA FECHA TEMPORAL EN FAMILIAR.
    let dataOcupacion   = false;// VARIABLE PARA GUARDAR LAS OCUPACIONES Y AGREGARLAS A LA TABLA FAMILIA.
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARA GUARDAR LOS RESULTADOS CONSULTADOS.
    let dataListadoApre = [];   // VARIABLE PARA GUARDAR LOS RESULTADOS DEL APRENDIZ A INSCRIBIR
    let dataListadoEmp  = false;   // VARIABLE PARA GUARDAR LOS RESULTADOS
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado(){
        $('#listado_tabla tbody').html('<tr><td colspan="9" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>');
        $("#paginacion").html('<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>');
        $.ajax({
            url : url+'controllers/c_aprendiz.php',
            type: 'POST',
            data: {
                opcion  : 'Consultar',
                numero  : parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()),
                cantidad: parseInt($('#cantidad_a_buscar').val()),
                ordenar : parseInt($('#ordenar_por').val()),
                tipo_ord: parseInt($('#campo_ordenar').val()),
                campo   : $('#campo_busqueda').val(),
                estatus : $('#buscar_estatus').val()
            }, success: function (resultados){
                try {
                    $('#listado_tabla tbody').empty();
                    dataListado = JSON.parse(resultados);
                    if (dataListado.resultados) {
                        let cont = parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()) + 1;
                        for (var i in dataListado.resultados) {
                            /////////////// FECHA REGISTRO ///////////////
                            let yearR   = dataListado.resultados[i].fecha.substr(0,4);
                            let monthR  = dataListado.resultados[i].fecha.substr(5,2);
                            let dayR    = dataListado.resultados[i].fecha.substr(8,2);
                            let nombre_completo = dataListado.resultados[i].nombre1;
                            /////////////// NOMBRES ///////////////
                            if (dataListado.resultados[i].nombre2 != null)
                                nombre_completo += ' '+dataListado.resultados[i].nombre2.substr(0,1)+'.';
                            nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            if (dataListado.resultados[i].apellido2 != null)
                                nombre_completo += ' '+dataListado.resultados[i].apellido2.substr(0,1)+'.';
                            /////////////// FECHA DE NACIMIENTO ///////////////
                            let day     = dataListado.resultados[i].fecha_n.substr(8, 2);
                            let month   = dataListado.resultados[i].fecha_n.substr(5, 2);
                            let year    = dataListado.resultados[i].fecha_n.substr(0, 4);
                            let dayA    = fecha.substr(0,2);
                            let monthA  = fecha.substr(3,2);
                            let yearA   = fecha.substr(6,4);
                            /////////////// EDAD ///////////////
                            let edad = 0;
                            if (year != '' && year != undefined) {
                                if (year <= yearA) {
                                    edad = yearA - year;
                                    if (month > monthA) {
                                        if (edad != 0) 
                                            edad--;
                                    } else if (month == monthA) {
                                        if (day > dayA)
                                            if (edad != 0) 
                                                edad--;
                                    }
                                }
                            }
                            /////////////// TIPO ESTATUS ///////////////
                            // 'I' => 'INSCRIPTO'
                            // 'C' => 'CURSANDO'
                            // 'R' => 'RETIRADO'
                            /////////////// ESTATUS ///////////////
                            let estatus = '';
                            if (dataListado.resultados[i].estatus_informe == 'I') {
                                estatus = '<span class="badge badge-info"><i class="fas fa-user-plus mr-1"></i>Inscripto</span>';
                            } else if (dataListado.resultados[i].estatus_informe == 'C') {
                                estatus = '<span class="badge badge-success"><i class="fas fa-running mr-1"></i>Cursando</span>';
                            } else if (dataListado.resultados[i].estatus_informe == 'G') {
                                estatus = '<span class="badge badge-secondary"><i class="fas fa-user-check mr-1"></i>Culmidado</span>';
                            } else if (dataListado.resultados[i].estatus_informe == 'R') {
                                estatus = '<span class="badge badge-danger"><i class="fas fa-user-times mr-1"></i>Retirado</span>';
                            }
                            /////////////// TIPO REGISTRO ///////////////
                            let tipo_ficha = '';
                            if (dataListado.resultados[i].tipo_inscripcion == 'I')
                                tipo_ficha = 'Inscripción';
                            else
                                tipo_ficha = 'Re-inscripción';
                            /////////////// TIPO REGISTRO ///////////////
                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="text-right py-2 px-1">'+cont+'</td>';
                            contenido += '<td class="py-2 px-1">'+dayR+'-'+monthR+'-'+yearR+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nacionalidad+'-'+dataListado.resultados[i].cedula+'</td>';
                            contenido += '<td class="py-2 px-1">'+nombre_completo+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+day+'-'+month+'-'+year+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+edad+'</td>';
                            contenido += '<td class="py-2 px-1">'+tipo_ficha+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+estatus+'</td>';
                            contenido += '<td class="py-1 px-1">';
                            if (permisos.modificar == 1){
                                contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'"><i class="fas fa-pencil-alt"></i></button>';
                            }
                            contenido += '<div class="dropdown d-inline-block"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v px-1"></i></button>';
                            contenido += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                            if (permisos.act_desc == 1) {
                                if (dataListado.resultados[i].estatus_informe == 'I') {
                                    
                                } else if (dataListado.resultados[i].estatus_informe == 'R') {
                                    contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 reactivar_postulante" data-posicion="'+i+'"><i class="fas fa-redo text-center" style="width:20px;"></i><span class="ml-2">Re-ingresar</span></a></li>';
                                }
                            }
                            contenido += '<li class="dropdown-item p-0"><a href="'+url+'controllers/pdf/r_ficha_inscripcion?numero='+dataListado.resultados[i].numero+'" target="_blank" class="d-inline-block w-100 p-1"><i class="fas fa-print text-center" style="width:20px;"></i><span class="ml-2">Imprimir</span></a></li>';
                            contenido += '</div></div></td></tr>';
                            $('#listado_tabla tbody').append(contenido);
                            cont++;
                        }
                        $('.editar_registro').click(editarRegistro);
                        $('.cambiar_estatus').click(cambiarEstatus);
                    } else {
                        $('#listado_tabla tbody').append('<tr><td colspan="9" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay aprendices registrados</td></tr>');
                    }

                    $('#total_registros').html(dataListado.total);
                    establecer_tabla(numeroDeLaPagina, parseInt($('#cantidad_a_buscar').val()), dataListado.total);
                    $('.mover').click(cambiarPagina);
                } catch (error) {
                    console.log(resultados);
                }
                window.actualizar_busqueda = false;
            }, error: function (){
                console.log('error');
            }, timer: 15000
        });
    }
    function cambiarPagina(e) {
        e.preventDefault();
        let numero = $(this).attr('data-pagina');
        numeroDeLaPagina = parseInt(numero);
        buscar_listado();
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    ////////////////////////// VALIDACIONES //////////////////////////
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
        pestania1 = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD
        let tipo_ficha = $("#tipo_ficha").val();
        if(tipo_ficha != ''){
            $("#tipo_ficha").css("background-color", colorb);
        } else {
            $("#tipo_ficha").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let correlativo = $("#correlativo").val();
        if(correlativo != ''){
            if(correlativo.match(ER_NumericoSinEspacios)){
                $("#correlativo").css("background-color", colorb);
            }else{
                $("#correlativo").css("background-color", colorm);
                pestania1 = false;
            }
        }else{
            $("#correlativo").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let numero_orden = $("#numero_orden").val();
        if(numero_orden != ''){
            if(numero_orden.match(ER_NumericoSinEspacios)){
                $("#numero_orden").css("background-color", colorb);
            }else{
                $("#numero_orden").css("background-color", colorm);
                pestania1 = false;
            }
        }else{
            $("#numero_orden").css("background-color", colorm);
            pestania1 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania1) {
            $('#icon-ficha').hide();
        } else {
            $('#icon-ficha').show();
        }
    }
    function verificarParte2 () {
        pestania2 = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD
        let nacionalidad = $("#nacionalidad").val();
        if(nacionalidad != ''){
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            pestania2 = false;
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
                    pestania2 = false;
                }
            }else{
                $("#cedula").css("background-color", colorm);
                pestania2 = false;
            }
        }else{
            $("#cedula").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DEL PRIMER NOMBRE
        let nombre_1 = $("#nombre_1").val();
        if(nombre_1 != ''){
            if(nombre_1.match(ER_caracteresConEspacios)){
                $("#nombre_1").css("background-color", colorb);
            }else{
                $("#nombre_1").css("background-color", colorm);
                pestania2 = false;
            }
        }else{
            $("#nombre_1").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO NOMBRE
        let nombre_2 = $("#nombre_2").val();
        if(nombre_2 != ''){
            if(nombre_2.match(ER_caracteresConEspacios)){
                $("#nombre_2").css("background-color", colorb);
            }else{
                $("#nombre_2").css("background-color", colorm);
                pestania2 = false;
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
                pestania2 = false;
            }
        }else{
            $("#apellido_1").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO APELLIDO
        let apellido_2 = $("#apellido_2").val();
        if(apellido_2 != ''){
            if(apellido_2.match(ER_caracteresConEspacios)){
                $("#apellido_2").css("background-color", colorb);
            }else{
                $("#apellido_2").css("background-color", colorm);
                pestania2 = false;
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
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DE FECHA DE NACIMIENTO
        let fecha_n = $("#fecha_n").val();
        if(fecha_n != ''){
            let edad_cal = parseInt($('#edad').val());
            if (edad_cal >= 17 && edad_cal <= 19) {
                $("#fecha_n").css("background-color", colorb);
            } else {
                $("#fecha_n").css("background-color", colorm);
                pestania2 = false;
            }
        } else {
            $("#fecha_n").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DE LUGAR DE NACIMIENTO
        let lugar_n = $("#lugar_n").val();
        if(lugar_n != ''){
            if(lugar_n.match(ER_alfaNumericoCompleto)){
                $("#lugar_n").css("background-color", colorb);
            }else{
                $("#lugar_n").css("background-color", colorm);
                pestania2 = false;
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
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DE ESTADO CIVIL
        let estado_civil = document.formulario.estado_civil.value;
        $(".radio_estado_c_label").removeClass('inputMal');
        if(estado_civil == ''){
            $(".radio_estado_c_label").addClass('inputMal');
            pestania2 = false;
        } else {
            $(".radio_estado_c_label").addClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE GRADO DE INSTRUCCION
        let grado_instruccion = document.formulario.grado_instruccion.value;
        $(".radio_educacion_label").removeClass('inputMal');
        if(grado_instruccion == ''){
            $(".radio_educacion_label").addClass('inputMal');
            pestania2 = false;
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
                    pestania2 = false;
                }
            } else {
                pestania2 = false;
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
                pestania2 = false;
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
                    pestania2 = false;
                }
            }else{
                $("#telefono_1").css("background-color", colorm);
                pestania2 = false;
            }
        }else{
            $("#telefono_1").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL TELEFONO CELULAR
        let telefono_2 = $("#telefono_2").val();
        if(telefono_2 != ''){
            if(telefono_2.match(ER_NumericoSinEspacios)){
                if (telefono_2.length >= 10) {
                    $("#telefono_2").css("background-color", colorb);
                } else {
                    $("#telefono_2").css("background-color", colorm);
                    pestania2 = false;
                }
            }else{
                $("#telefono_2").css("background-color", colorm);
                pestania2 = false;
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
                pestania2 = false;
            }
        }else{
            $("#correo").css("background-color", colorn);
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania2) {
            $('#icon-ciudadano').hide();
        } else {
            $('#icon-ciudadano').show();
        }
    }
    function verificarParte3 () {
        pestania3 = true;
        // VERIFICAR EL CAMPO DE ESTADO
        let estado = $("#estado").val();
        if(estado != ''){
            $("#estado").css("background-color", colorb);
        } else {
            $("#estado").css("background-color", colorm);
            pestania3 = false;
        }
        // VERIFICAR EL CAMPO DE CIUDAD
        let ciudad = $("#ciudad").val();
        if(ciudad != ''){
            $("#ciudad").css("background-color", colorb);
        } else {
            $("#ciudad").css("background-color", colorm);
            pestania3 = false;
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
                pestania3 = false;
            }
        }else{
            $("#direccion").css("background-color", colorm);
            pestania3 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania3) {
            $('#icon-ubicacion').hide();
        } else {
            $('#icon-ubicacion').show();
        }
    }
    function verificarParte4 () {
        pestania4 = true;
        // VERIFICAR EL CAMPO DE 
        let rif = $("#rif").val();
        if(rif != ''){
            $(".data_empresa").css("background-color", '');
        }else{
            $(".data_empresa").css("background-color", colorm);
            pestania4 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania4) {
            $('#icon-empresa').hide();
        } else {
            $('#icon-empresa').show();
        }
    }
    //////////////////////// FUNCIONES MANTENER FECHA ////////////////////////
    $('.input_fecha').click(function () {
        fechaTemporal = $(this).val();
    });
    $('.input_fecha').blur(function () {
        $(this).val(fechaTemporal);
    });
    $('.input_fecha').change(function () {
        fechaTemporal = $(this).val();
    });
    //////////////////////// FUNCIONES CAMPOS ////////////////////////
    $('#fecha_n').change(function (){
        let day     = $(this).val().substr(0,2);
        let month   = $(this).val().substr(3,2);
        let year    = $(this).val().substr(6,4);
        let dayA    = fecha.substr(0,2);
        let monthA  = fecha.substr(3,2);
        let yearA   = fecha.substr(6,4);
            
        let edad = 0;
        if (year != '' && year != undefined) {
            if (year <= yearA) {
                edad = yearA - year;
                if (month > monthA) {
                    if (edad != 0) 
                        edad--;
                } else if (month == monthA) {
                    if (day > dayA)
                        if (edad != 0) 
                            edad--;
                }
            }
        }
        
        $('#edad').val(edad);
    });
    $('.radio_educacion').click(function () {
        if ($(this).val() == 'SI' || $(this).val() == 'SC')
            $('#titulo').attr('readonly', false);
        else {
            $('#titulo').attr('readonly', true);
            $('#titulo').val('');
        }
    });
    $('#estado').change(function () {
        if ($(this).val() != '') {
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
            window.actualizar = false;
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
    $('#show_form').click(function (){
        $('#form_title').html('Registrar');
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#carga_espera').hide(400);
        tipoEnvio = 'Registrar';
        /////////////////////
        limpiarFormulario();
    });
    $('#show_table').click(function (){
        $('#info_table').show(400);
        $('#gestion_form').hide(400);
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    // MODAL BUSCAR EMPRESA
    $('#btn-buscar-empresa').click(function () {
        limpiarModalBuscarEmpresa();
        $('#modal-buscar-empresa').modal();
    });
    $('#input-buscar-empresa').keydown(function (e) { if (e.keyCode == 13) e.preventDefault(); });
    $('#input-buscar-empresa').blur(function () {
        if (window.buscarBlur == true) {
            buscarEmpresa();
        }
    });
    $('#input-buscar-empresa').keydown(function (e) {
        window.buscarBlur = true;
        if (e.keyCode == 13) {
            buscarEmpresa();
            window.buscarBlur = false;
        }
    });
    function buscarEmpresa () {
        $('#resultados-buscar-empresa').empty();
        $('#resultados-buscar-empresa').hide();

        let valorBuscar = $('#input-buscar-empresa').val();
        if (valorBuscar != '') {
            $('#resultados-buscar-empresa').show();
            $('#resultados-buscar-empresa').html('<span class="d-inline-block text-center w-100 py-1 px-2"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando...</span>');
        
            $.ajax({
                url : url+'controllers/c_aprendiz.php',
                type: 'POST',
                data: {
                    opcion: 'Traer empresa',
                    buscar: valorBuscar
                },
                success: function (resultados){
                    try {
                        $('#resultados-buscar-empresa').empty();
                        dataListadoEmp = JSON.parse(resultados);
                        if (dataListadoEmp) {
                            for (let d_f in dataListadoEmp) {
                                $('#resultados-buscar-empresa').append('<p class="d-inline-block w-100 m-0 py-1 px-2 click-buscar-empresa" data-posicion="'+d_f+'"><i class="fas fa-industry mr-2"></i>'+dataListadoEmp[d_f].rif+' '+dataListadoEmp[d_f].razon_social+'</p>');
                            }
                        } else {
                            $('#resultados-buscar-empresa').html('<span class="d-inline-block text-center w-100 py-1 px-2"><i class="fas fa-user-times mr-2"></i>Sin resultados</span>');
                        }
                        $('.click-buscar-empresa').click(agregarEmpresa);
                    } catch (error) {
                        console.log(resultados);
                        console.log(error);
                    }
                },
                error: function (error){
                    console.log(error);
                }
            });
        }
    }
    function agregarEmpresa () {
        let posicion = $(this).attr('data-posicion');
        $('#rif').val(dataListadoEmp[posicion].rif);
        $('#nil').val(dataListadoEmp[posicion].nil);
        $('#razon_social').val(dataListadoEmp[posicion].razon_social);
        $('#actividad_economica').val(dataListadoEmp[posicion].actividad_economica);
        $('#codigo_aportante').val(dataListadoEmp[posicion].codigo_aportante);
        $('#telefono1_e').val(dataListadoEmp[posicion].telefono1);
        $('#telefono2_e').val(dataListadoEmp[posicion].telefono2);
        $('#estado_e').val(dataListadoEmp[posicion].estado);
        $('#ciudad_e').val(dataListadoEmp[posicion].ciudad);
        $('#direccion_e').val(dataListadoEmp[posicion].direccion);
        /////////////////////////////////////////////////////////////////
        $('#modal-buscar-empresa').modal('hide');
    }
    function limpiarModalBuscarEmpresa () {
        $('#resultados-buscar-empresa').empty();
        $('#resultados-buscar-empresa').hide();
        document.form_buscar_empresa.reset();
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
    // SI DESDE INFORME SOCIAL SE ACEPTO UN POSTULANTE PROCEDE A BUSCAR LOS DATOS PARA REGISTRAR, LLENAR EL FORM Y REGISTRAR.
    function consultarDatosAprendiz () {
        let numero_informe_Social = localStorage.getItem('numero_ficha');
        $('#show_form').trigger('click');
        $('#carga_espera').show();

        $.ajax({
            url : url+'controllers/c_aprendiz.php',
            type: 'POST',
            data: {
                opcion: 'Traer aprendiz por ficha',
                numero: numero_informe_Social
            },
            success: function (resultados){
                try {
                    localStorage.removeItem('numero_ficha');
                    //////////////////////////////////////////////////////
                    window.informe_social = numero_informe_Social;
                    dataListadoEmp = JSON.parse(resultados);
                    window.nacionalidad = dataListadoEmp.nacionalidad;
                    window.cedula = dataListadoEmp.cedula;
                    $('#nacionalidad').val(dataListadoEmp.nacionalidad);
                    $('#cedula').val(dataListadoEmp.cedula);
                    $('#cedula').trigger('blur');
                    $('#nombre_1').val(dataListadoEmp.nombre1);
                    $('#nombre_2').val(dataListadoEmp.nombre2);
                    $('#apellido_1').val(dataListadoEmp.apellido1);
                    $('#apellido_2').val(dataListadoEmp.apellido2);
                    $('#sexo').val(dataListadoEmp.sexo);
                    fecha_nu = dataListadoEmp.fecha_n;
                    $('#fecha_n').val(fecha_nu.substr(8, 2)+'-'+fecha_nu.substr(5, 2)+'-'+fecha_nu.substr(0, 4));
                    $('#fecha_n').trigger('change');
                    $('#lugar_n').val(dataListadoEmp.lugar_n);
                    $('#ocupacion').val(dataListadoEmp.codigo_ocupacion);
                    //////////////////////////////////////////////////////////
                    document.formulario.estado_civil.value = dataListadoEmp.estado_civil;
                    document.formulario.grado_instruccion.value = dataListadoEmp.nivel_instruccion;
                    if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC')
                        $('#titulo').attr('readonly', false);
                    $('#titulo').val(dataListadoEmp.titulo_acade);
                    $('#alguna_mision').val(dataListadoEmp.mision_participado);
                    $('#telefono_1').val(dataListadoEmp.telefono1);
                    $('#telefono_2').val(dataListadoEmp.telefono2);
                    $('#correo').val(dataListadoEmp.correo);
                    // SEGUNDA PARTE.
                    $('#estado').val(dataListadoEmp.codigo_estado);
                    window.buscarCiudadFicha = true;
                    $('#estado').trigger('change');
                    $('#direccion').val(dataListadoEmp.direccion);
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function (){
                console.log('error');
            }
        });
    }
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
        limpiarFormulario();
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
        if (pestania1 && pestania2 && pestania3 && pestania4) {
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
    // FUNCION PARA LIMPIAR EL FORMULARIO DE LOS DATOS ANTERIORES.
    function limpiarFormulario(){
        document.formulario.reset();
        $('#fecha').val(fecha);
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos()
    {
        $.ajax({
            url : url+'controllers/c_aprendiz.php',
            type: 'POST',
            data: { opcion: 'Traer datos' },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);
                    fecha = data.fecha;
                    fecha2= data.fecha;
                    if (data.ocupacion) {
                        for (let i in data.ocupacion) {
                            $('#ocupacion').append('<option value="'+data.ocupacion[i].codigo+'">'+data.ocupacion[i].nombre+'</option>');
                        }
                        dataOcupacion = data.ocupacion;
                    } else {
                        $('#ocupacion').html('<option value="">No hay ocupaciones</option>');
                    }

                    if (data.estado) {
                        for (let i in data.estado) {
                            $('#estado').append('<option value="'+data.estado[i].codigo+'">'+data.estado[i].nombre+'</option>');
                        }
                    } else {
                        $('#estado').html('<option value="">No hay estados</option>');
                    }
                    buscar_listado();

                    if (localStorage.getItem('numero_ficha'))
                        consultarDatosAprendiz();
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function (){
                console.log('error');
            }
        });
    }
    llamarDatos();
    $('.input_fecha').datepicker({ language: 'es' });
});