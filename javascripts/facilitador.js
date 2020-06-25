$(function() {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let ER_caracteresConEspacios = /^([a-zA-Z,.\x7f-\xff](\s[a-zA-Z,.\x7f-\xff])*)+$/;
    let ER_alfaNumericoConEspacios=/^([a-zA-Z0-9,.\x7f-\xff](\s[a-zA-Z0-9,.\x7f-\xff])*)+$/;
    let ER_NumericoSinEspacios=/^([0-9])+$/;
    let ER_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let pestania1, pestania2, pestania3;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////
    

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
        } else {
            window.actualizar_busqueda = true;
        }
    });
    $('#campo_busqueda').blur(function () {
        if (window.actualizar_busqueda) { buscar_listado(); }
    });
    $('#buscar_estatus').change(restablecerN);
    /////////////////////////////////////////////////////////////////////
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fechaTemporal   = '';   // VARIABLE PARA GUARDAR UNA FECHA TEMPORAL EN FAMILIAR.
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    let formatos_acce   = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx'];
    let mensaje_conte_arch = '<div class="col-sm-12"><h6 class="text-center m-0 text-uppercase text-secondary"><i class="fas fa-archive text-dark"></i> No hay archivos guardados</h6></div>';
    let mensaje_input_file = '<h6 class="text-center py-2 m-0 text-uppercase text-secondary">Presione el botón <button type="button" class="btn btn-sm btn-info" disabled="true" style="height: 22px; padding: 3px 5px; vertical-align: top; cursor: default;"><i class="fas fa-plus" style="font-size: 9px; vertical-align: top; padding-top: 3px;"></i></button> para agregar archivos</h6>';
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado(){
        let filas = 0;
        if (permisos.modificar == 1 || permisos.act_desc == 1) { filas = 8; }
        else { filas = 7; }

        let contenido_tabla = '';
        contenido_tabla += '<tr>';
        contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
        contenido_tabla += '<i class="fas fa-spinner fa-spin"></i> <span style="font-weight: 500;">Cargando...</span>';
        contenido_tabla += '</td>';
        contenido_tabla += '</tr>';
        $('#listado_tabla tbody').html(contenido_tabla);

        let contenido_paginacion = '';
        contenido_paginacion += '<li class="page-item">';
        contenido_paginacion += '<a class="page-link text-info">';
        contenido_paginacion += '<i class="fas fa-spinner fa-spin"></i>';
        contenido_paginacion += '</a>';
        contenido_paginacion += '</li>';
        $("#paginacion").html(contenido_paginacion);

        // DESABILITAMOS LOS BOTONES PARA EVITAR ACCIONES MIENTRAS SE EJECUTA LA CONSULTA
        $('#cantidad_a_buscar').attr('disabled', true);
        $('#ordenar_por').attr('disabled', true);
        $('#campo_ordenar').attr('disabled', true);
        $('#buscar_estatus').attr('disabled', true);
        $('#campo_busqueda').attr('disabled', true);

        // DESABILITAMOS LA OPCION DE AGREGAR NUEVOS DATOS HASTA QUE NO TERMINE LA CONSULTA.
        $('#show_form').attr('disabled', true);
        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_facilitador.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    opcion  : 'Consultar',
                    numero  : parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()),
                    cantidad: parseInt($('#cantidad_a_buscar').val()),
                    ordenar : parseInt($('#ordenar_por').val()),
                    tipo_ord: parseInt($('#campo_ordenar').val()),
                    campo   : $('#campo_busqueda').val(),
                    estatus : $('#buscar_estatus').val()
                },
                success: function (resultados){
                    $('#listado_tabla tbody').empty();

                    dataListado = resultados;
                    if (dataListado.resultados) {
                        for (var i in dataListado.resultados) {
                            let nombre_completo = dataListado.resultados[i].nombre1;
                            if (dataListado.resultados[i].nombre2 != '' && dataListado.resultados[i].nombre2 != null) {
                                nombre_completo += ' '+dataListado.resultados[i].nombre2;
                            }
                            nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            if (dataListado.resultados[i].apellido1 != '' && dataListado.resultados[i].apellido1 != null) {
                                nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            }

                            let day     = dataListado.resultados[i].fecha_n.substr(8, 2);
                            let month   = dataListado.resultados[i].fecha_n.substr(5, 2);
                            let year    = dataListado.resultados[i].fecha_n.substr(0, 4);
                            dataListado.resultados[i].fecha_n = day+'-'+month+'-'+year;
                            let edad = calcularEdad(fecha, dataListado.resultados[i].fecha_n);

                            let sexo = '';
                            if (dataListado.resultados[i].sexo == 'M') { sexo = 'Masculino'; }
                            else if (dataListado.resultados[i].sexo == 'F') { sexo = 'Femenino'; }

                            let estatus_td = '';
                            if      (dataListado.resultados[i].estatus == 'A') { estatus_td = '<span class="badge badge-success"><i class="fas fa-check"></i> <span style="font-weight: 500;">Activo</span></span>'; }
                            else if (dataListado.resultados[i].estatus == 'I') { estatus_td = '<span class="badge badge-danger"><i class="fas fa-times"></i> <span style="font-weight: 500;">Inactivo</span></span>'; }

                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nacionalidad+'-'+dataListado.resultados[i].cedula+'</td>';
                            contenido += '<td class="py-2 px-1">'+nombre_completo+'</td>';
                            contenido += '<td class="py-2 px-1">'+day+'-'+month+'-'+year+'</td>';
                            contenido += '<td class="py-2 text-center px-1">'+edad+'</td>';
                            contenido += '<td class="py-2 px-1">'+sexo+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].telefono1+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+estatus_td+'</td>';
                            if (permisos.modificar == 1 || permisos.act_desc == 1) {
                                contenido += '<td class="py-1 px-1">';
                                ////////////////////////////
                                if (permisos.modificar == 1) {
                                    contenido += '<button type="button" class="btn btn-sm btn-info editar-registro" data-posicion="'+i+'" style="margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button>';
                                }
                                if (permisos.act_desc == 1) {
                                    if (dataListado.resultados[i].estatus == 'A') { contenido += '<button type="button" class="btn btn-sm btn-danger cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye-slash" style="font-size: 12px;"></i></button>'; }
                                    else { contenido += '<button type="button" class="btn btn-sm btn-success cambiar-estatus" data-posicion="'+i+'"><i class="fas fa-eye"></i></button>'; }
                                }
                                ////////////////////////////
                                contenido += '</td>';
                            }
                            contenido += '</tr>';
                            $('#listado_tabla tbody').append(contenido);
                        }
                        $('.editar-registro').click(editarRegistro);
                        $('.cambiar-estatus').click(cambiarEstatus);
                    } else {
                        contenido_tabla = '';
                        contenido_tabla += '<tr>';
                        contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2">';
                        contenido_tabla += '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay facilitadores registrados.</span>';
                        contenido_tabla += '</td>';
                        contenido_tabla += '</tr>';
                        $('#listado_tabla tbody').html(contenido_tabla);
                    }

                    // SE HABILITA LA FUNCION PARA QUE PUEDA REALIZAR BUSQUEDA AL TERMINAR LA ANTERIOR.
                    window.actualizar_busqueda = false;
                    // MOSTRAR EL TOTAL DE REGISTROS ENCONTRADOS.
                    $('#total_registros').html(dataListado.total);
                    // HABILITAR LA PAGINACION PARA MOSTRAR MAS DATOS.
                    establecer_tabla(numeroDeLaPagina, parseInt($('#cantidad_a_buscar').val()), dataListado.total);
                    // LE AGREGAMOS FUNCIONALIDAD A LOS BOTONES PARA CAMBIAR LA PAGINACION.
                    $('.mover').click(cambiarPagina);

                    $('#cantidad_a_buscar').attr('disabled', false);
                    $('#ordenar_por').attr('disabled', false);
                    $('#campo_ordenar').attr('disabled', false);
                    $('#buscar_estatus').attr('disabled', false);
                    $('#campo_busqueda').attr('disabled', false);
                    // HABILITAMOS EL BOTON PARA AGREGAR NUEVOS DATOS.
                    $('#show_form').attr('disabled', false);
                },
                error: function (msjError) {
                    contenido_tabla = '';
                    contenido_tabla += '<tr>';
                    contenido_tabla += '<td colspan="'+filas+'" class="text-center text-secondary border-bottom text-danger p-2">';
                    contenido_tabla += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                    contenido_tabla += '<button type="button" id="btn-recargar-tabla" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                    contenido_tabla += '</td>';
                    contenido_tabla += '</tr>';
                    $('#listado_tabla tbody').html(contenido_tabla);
                    $('#btn-recargar-tabla').click(buscar_listado);

                    contenido_paginacion = '';
                    contenido_paginacion += '<li class="page-item">';
                    contenido_paginacion += '<a class="page-link text-danger">';
                    contenido_paginacion += '<i class="fas fa-ethernet"></i>';
                    contenido_paginacion += '</a>';
                    contenido_paginacion += '</li>';
                    $('#paginacion').html(contenido_paginacion);

                    $('#cantidad_a_buscar').attr('disabled', false);
                    $('#ordenar_por').attr('disabled', false);
                    $('#campo_ordenar').attr('disabled', false);
                    $('#buscar_estatus').attr('disabled', false);
                    $('#campo_busqueda').attr('disabled', false);
                    // HABILITAMOS EL BOTON PARA AGREGAR NUEVOS DATOS.
                    $('#show_form').attr('disabled', false);
                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+msjError.status+' - '+msjError.statusText);
                    console.log(msjError.responseText);
                }, timer: 15000
            });
        }, 500);
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

        // SE VERIFICA QUE NO ESTE VACIA LA NACIONALIDAD
        if($('#nacionalidad').val() != '') {
            // SE VERIFICA QUE NO ESTE VACIA LA CEDULA
            if ($('#cedula').val() != '') {
                // SE VERIFICA SI HA CAMBIADO PARA HACER LA CONSULTA
                if (window.nacionalidad != $('#nacionalidad').val() || window.cedula != $('#cedula').val()) {
                    // VERIFICAMOS QUE SEA MAYO DE 7 NUMEROS PARA PODER VERIFICAR
                    if ($('#cedula').val().length > 7) {
                        setTimeout(() => {
                            $.ajax({
                                url : url+'controllers/c_empresa.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    opcion: 'Verificar cedula',
                                    nacionalidad: $('#nacionalidad').val(),
                                    cedula: $('#cedula').val()
                                },
                                success: function (resultados) {
                                    $('#spinner-cedula').hide();
                                    // VERIFICAMOS SI YA ESTA REGISTRADA ESTA PERSONA.
                                    if (resultados) {
                                        $('#spinner-cedula-confirm').addClass('fa-times text-danger');
                                    } else {
                                        $('#spinner-cedula-confirm').addClass('fa-check text-success');
                                        validarCedula = true;
                                    }
                                    $('#spinner-cedula-confirm').show(200);
                                },
                                error: function () {
                                    
                                }
                            });
                        }, 500);
                    }
                // DECLARAMOS QUE ESTA BIEN DEFIFINA LOS CAMPOS DE IDENTIFICACION.
                } else {
                    validarCedula = true;
                }
            }
        }
    });
    function verificarParte1 () {
        pestania1 = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD
        let nacionalidad = $("#nacionalidad").val();
        if (nacionalidad != ''){
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DE CEDULA
        let cedula = $("#cedula").val();
        if (cedula != '') {
            if(cedula.match(ER_NumericoSinEspacios)){
                if (cedula.length > 7) {
                    if (validarCedula) {
                        $("#cedula").css("background-color", colorb);
                    } else {
                        $("#cedula").css("background-color", colorm);
                        $("#nacionalidad").css("background-color", colorm);
                        pestania1 = false;
                    }
                } else {
                    $("#cedula").css("background-color", colorm);
                    pestania1 = false;
                }
            }else{
                $("#cedula").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#cedula").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL PRIMER NOMBRE
        let nombre_1 = $("#nombre_1").val();
        if (nombre_1 != '') {
            if(nombre_1.match(ER_caracteresConEspacios)){
                $("#nombre_1").css("background-color", colorb);
            }else{
                $("#nombre_1").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#nombre_1").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO NOMBRE
        let nombre_2 = $("#nombre_2").val();
        if (nombre_2 != '') {
            if(nombre_2.match(ER_caracteresConEspacios)){
                $("#nombre_2").css("background-color", colorb);
            }else{
                $("#nombre_2").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#nombre_2").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DEL PRIMER APELLIDO
        let apellido_1 = $("#apellido_1").val();
        if (apellido_1 != '') {
            if(apellido_1.match(ER_caracteresConEspacios)){
                $("#apellido_1").css("background-color", colorb);
            }else{
                $("#apellido_1").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#apellido_1").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO APELLIDO
        let apellido_2 = $("#apellido_2").val();
        if (apellido_2 != '') {
            if(apellido_2.match(ER_caracteresConEspacios)){
                $("#apellido_2").css("background-color", colorb);
            }else{
                $("#apellido_2").css("background-color", colorm);
                pestania1 = false;
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
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DE FECHA DE NACIMIENTO
        let fecha_n = $("#fecha_n").val();
        if (fecha_n != '') {
            let edad_cal = parseInt($('#edad').val());
            if (edad_cal >= 20) {
                $("#fecha_n").css("background-color", colorb);
            } else {
                $("#fecha_n").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#fecha_n").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DE LUGAR DE NACIMIENTO
        let lugar_n = $("#lugar_n").val();
        if (lugar_n != '') {
            if(lugar_n.match(ER_alfaNumericoConEspacios)){
                $("#lugar_n").css("background-color", colorb);
            }else{
                $("#lugar_n").css("background-color", colorm);
                pestania1 = false;
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
            pestania1 = false;
        }
        // VERIFICAR EL TELEFONO DE CASA
        let telefono_1 = $("#telefono_1").val();
        if (telefono_1 != '') {
            if(telefono_1.match(ER_NumericoSinEspacios)){
                if (telefono_1.length >= 10) {
                    $("#telefono_1").css("background-color", colorb);
                } else {
                    $("#telefono_1").css("background-color", colorm);
                    pestania1 = false;
                }
            }else{
                $("#telefono_1").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#telefono_1").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL TELEFONO CELULAR
        let telefono_2 = $("#telefono_2").val();
        if(telefono_2 != ''){
            if(telefono_2.match(ER_NumericoSinEspacios)){
                if (telefono_2.length >= 10) {
                    $("#telefono_2").css("background-color", colorb);
                } else {
                    $("#telefono_2").css("background-color", colorm);
                    pestania1 = false;
                }
            }else{
                $("#telefono_2").css("background-color", colorm);
                pestania1 = false;
            }
        }else{
            $("#telefono_2").css("background-color", colorn);
        }
        // VERIFICAR EL CORREO ELECTRONICO
        let correo = $("#correo").val();
        if (correo != '') {
            if(correo.match(ER_email)){
                $("#correo").css("background-color", colorb);
            }else{
                $("#correo").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#correo").css("background-color", colorn);
        }
        // VERIFICAR EL CAMPO DE ESTADO CIVIL
        let estado_civil = document.formulario.estado_civil.value;
        $(".radio_estado_c_label").removeClass('inputMal');
        if (estado_civil == '') {
            $(".radio_estado_c_label").addClass('inputMal');
            pestania1 = false;
        } else {
            $(".radio_estado_c_label").addClass('inputBien');
        }
        // VERIFICAR EL CAMPO DE GRADO DE INSTRUCCION
        let grado_instruccion = document.formulario.grado_instruccion.value;
        $(".radio_educacion_label").removeClass('inputMal');
        if(grado_instruccion == ''){
            $(".radio_educacion_label").addClass('inputMal');
            pestania1 = false;
        } else {
            $(".radio_educacion_label").addClass('inputBien');
        }
        // SI EL CAMPO DE INSTRUCCION ES SUPERIOR, VERIFICAR QUE EL CAMPO TITULO NO ESTE VACIO.
        if (grado_instruccion == 'SI' || grado_instruccion == 'SC') {
            let titulo_edu = $("#titulo").val();
            if (titulo_edu != '') {
                if(titulo_edu.match(ER_caracteresConEspacios)){
                    $("#titulo").css("background-color", colorb);
                }else{
                    $("#titulo").css("background-color", colorm);
                    pestania1 = false;
                }
            } else {
                pestania1 = false;
                $("#titulo").css("background-color", colorm);
            }
        } else {
            $("#titulo").css("background-color", '');
        }
        // VERIFICAR EL CAMPO DE MISIONES REALZADAS (NO OBLIGATORIA)
        let alguna_mision = $("#alguna_mision").val();
        if(alguna_mision != ''){
            if(alguna_mision.match(ER_alfaNumericoConEspacios)){
                $("#alguna_mision").css("background-color", colorb);
            }else{
                $("#alguna_mision").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#alguna_mision").css("background-color", colorn);
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania1) {
            $('#icon-ciudadano').hide();
        } else {
            $('#icon-ciudadano').show();
        }
    }
    function verificarParte2 () {
        pestania2 = true;
        // VERIFICAR EL CAMPO DE ESTADO
        let estado = $("#estado").val();
        if (estado != '') {
            $("#estado").css("background-color", colorb);
        } else {
            $("#estado").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DE CIUDAD
        let ciudad = $("#ciudad").val();
        if (ciudad != '') {
            $("#ciudad").css("background-color", colorb);
        } else {
            $("#ciudad").css("background-color", colorm);
            pestania2 = false;
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
        // VERIFICAR EL CAMPO DE DIRECCION
        let direccion = $("#direccion").val();
        if (direccion != '') {
            if(direccion.match(ER_alfaNumericoConEspacios)){
                $("#direccion").css("background-color", colorb);
            }else{
                $("#direccion").css("background-color", colorm);
                pestania2 = false;
            }
        } else {
            $("#direccion").css("background-color", colorm);
            pestania2 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania2) {
            $('#icon-ubicacion').hide();
        } else {
            $('#icon-ubicacion').show();
        }
    }
    function verificarParte3 () {
        pestania3 = true;

        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania3) {
            $('#icon-archivos').hide();
        } else {
            $('#icon-archivos').show();
        }
    }

    
    //////////////////////// FUNCIONES MANTENER FECHA ////////////////////////
    $('.input_fecha').click(function () { fechaTemporal = $(this).val(); });
    $('.input_fecha').blur(function () { $(this).val(fechaTemporal); });
    $('.input_fecha').change(function () { fechaTemporal = $(this).val(); });
    //////////////////////// FUNCIONES CAMPOS ////////////////////////
    $('#fecha_n').change(function (){ let edad = calcularEdad(fecha, $(this).val()); $('#edad').val(edad); });
    $('#estado').change(function () {
        if ($(this).val() != '') {
            $.ajax({
                url : url+'controllers/c_facilitador.php',
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
                        if (window.cargarCiudad == true) {
                            window.cargarCiudad = false;
                            $('#ciudad').val(dataListado.resultados[window.posicion].codigo_ciudad);
                        }
                        if (data.municipio) {
                            $('#municipio').append('<option value="">Elija una opción</option>');
                            for(let i in data.municipio){
                                $('#municipio').append('<option value="'+data.municipio[i].codigo+'">'+data.municipio[i].nombre+'</option>');
                            }
                        } else {
                            $('#municipio').html('<option value="">No hay municipios</option>');
                        }
                        if (window.cargarParroquia == true) {
                            $('#municipio').val(dataListado.resultados[window.posicion].codigo_municipio);
                            $('#municipio').trigger('change');
                        } else {
                            $('#carga_espera').hide(400);
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
        if (window.actualizar2 !== true) {
            localStorage.removeItem('parroquia');
        }

        if ($(this).val() != '') {
            $.ajax({
                url : url+'controllers/c_facilitador.php',
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
                        if (window.cargarParroquia == true) {
                            window.cargarParroquia = false;
                            $('#parroquia').val(dataListado.resultados[window.posicion].codigo_parroquia);
                        }

                        $('#carga_espera').hide(400);
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
    function desabilitarEnter (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    }
    /////////////////////////////////////////////////////////////////////
    $('#show_form').click(function (){
        $('#form_title').html('Registrar');
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#carga_espera').hide(400);
        $('#archivos-guardados').html(mensaje_conte_arch);
        $('#contenedor-input-file').html(mensaje_input_file);
        tipoEnvio = 'Registrar';
        window.eliminarArchivos = [];

        // LIMPIAR EL FORMULARIO
        document.formulario.reset();
        $('form .form-control').css('background-color', colorn);
        $('form .custom-select').css('background-color', colorn);
        $('form .custom-control-label').removeClass('inputBien inputMal');
        $('#edad').css('background-color', '');
        $('.icon-alert').hide();
        $('.ocultar-iconos').hide();
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger');
    });
    $('#show_table').click(function (){
        $('#info_table').show(400);
        $('#gestion_form').hide(400);
        /////////////////////
        $('#pills-datos-ciudadano-tab').tab('show');
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA AGREGAR NUEVOS INPUTS PARA SUBIR NUEVOS ARCHIVOS.
    $('#agregar-input-file').click(function (e) {
        e.preventDefault();
        if ($('#contenedor-input-file').html() == mensaje_input_file) {
            $('#contenedor-input-file').empty();
        }
        
        let contenedor_file = '';
        let numero_input_file = $('.nuevo-input-file').length;
        contenedor_file += '<div class="nuevo-input-file p-1" style="min-width: 800px;">';
        contenedor_file += '<form class="archivo-formulario d-flex w-100 align-items-start" enctype="multipart/form-data">';
        contenedor_file += '<div class="form-row" style="width: calc(100% - 26px);">';
        
        // CAMPOS PARA SUBIR LAS IMAGENES
        contenedor_file += '<div class="col-sm-4">';
        contenedor_file += '<label class="d-inline-block w-100 position-relative small m-0">Archivo<i class="fas fa-asterisk text-danger position-absolute required"></i></label>';
        contenedor_file += '<div class="custom-file custom-file-sm">';
        contenedor_file += '<input type="file" name="archivo_input_file[]" class="archivo-input-file custom-file-input" accept=".jpeg, .jpg, .png, .pdf, .doc, .docx">';
        contenedor_file += '<label class="custom-file-label py-1 m-0">Seleccionar Archivo</label>';
        contenedor_file += '</div>';
        contenedor_file += '</div>';
        /////////////////////////////////////////////
        contenedor_file += '<div class="col-sm-6">';
        contenedor_file += '<div class="form-group m-0">';
        contenedor_file += '<label class="d-inline-block w-100 position-relative small m-0">Descripción<i class="fas fa-asterisk text-danger position-absolute required"></i></label>';
        contenedor_file += '<input type="text" name="descripcion_archivo[]" class="descripcion-archivo form-control form-control-sm"/>';
        contenedor_file += '</div>';
        contenedor_file += '</div>';
        /////////////////////////////////////////////
        contenedor_file += '<div class="col-sm-2 align-self-end">';
        contenedor_file += '<button type="button" class="btn btn-sm btn-info btn-block btn-enviar-archivo" data-posicion="'+numero_input_file+'" disabled="true"><i class="fas fa-upload"></i> Enviar</button>';
        contenedor_file += '</div>';
        // CAMPOS PARA SUBIR LAS IMAGENES

        contenedor_file += '</div>';
        contenedor_file += '<button type="button" class="btn btn-sm btn-danger rounded-circle ml-2 btn-eliminar-input-file" style="padding-top: 2px; padding-bottom: 2px;" data-posicion="'+numero_input_file+'"><i class="fas fa-times"></i></button>';
        contenedor_file += '</form>';

        contenedor_file += '<div class="progress barra-envio-archivo mt-2">';
        contenedor_file += '<div class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>';
        contenedor_file += '</div>';

        contenedor_file += '<p class="mensaje-error-archivo small text-danger mt-1 mb-0" style="display: none;"></p>';
        contenedor_file += '<hr class="my-1">';
        contenedor_file += '</div>';

        $('#contenedor-input-file').append(contenedor_file);
        $($('.btn-enviar-archivo')[numero_input_file]).click(enviarArchivo);
        $($('.btn-eliminar-input-file')[numero_input_file]).click(eliminarInputFile);

        $($('.archivo-input-file')[numero_input_file]).change(btnEnviarHabilitado);
        $($('.descripcion-archivo')[numero_input_file]).keyup(btnEnviarHabilitado);
        $($('.descripcion-archivo')[numero_input_file]).keypress(desabilitarEnter);
        function btnEnviarHabilitado () {
            let btnHabilitador  = true;
            let inputFileImage  = $($('.archivo-input-file')[numero_input_file])[0];
            if (inputFileImage.files[0] != undefined) {
                let fileName    = inputFileImage.files[0].name.split('.');
                let nfileName   = fileName.length - 1;
                $($('.custom-file-label')[numero_input_file]).html(inputFileImage.files[0].name);
                if (formatos_acce.indexOf(fileName[nfileName]) == -1) {
                    btnHabilitador = false;
                    $($('.mensaje-error-archivo')[numero_input_file]).show();
                    $($('.mensaje-error-archivo')[numero_input_file]).html("<b>[Error]: Unicos formatos (.jpeg, .jpg, .png, .pdf, .doc, docx)</b>");
                } else {
                    $($('.mensaje-error-archivo')[numero_input_file]).hide();
                }
            } else {
                btnHabilitador = false;
            }

            let descripcion_archivo = $($('.descripcion-archivo')[numero_input_file]).val();
            if (descripcion_archivo != '') {
                if(!descripcion_archivo.match(ER_alfaNumericoConEspacios)) {
                    btnHabilitador = false;
                }
            } else {
                btnHabilitador = false;
            }

            if (btnHabilitador) {
                $($('.btn-enviar-archivo')[numero_input_file]).attr('disabled', false);
            } else {
                $($('.btn-enviar-archivo')[numero_input_file]).attr('disabled', true);
            }
        }
    });
    function enviarArchivo () {
        let posicion_arch = $(this).attr('data-posicion');
        let inputFileImage = $($('.archivo-input-file')[posicion_arch])[0];

        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).addClass('bg-info');
        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).removeClass('bg-danger');
        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).css('width', '0%');
        $($('.mensaje-error-archivo')[posicion_arch]).hide();

        let fileName    = inputFileImage.files[0].name;
        let file        = inputFileImage.files[0];
        let data        = new FormData();
        data.append('archivo', file);

        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).animate({ width: "80%" }, 5000);
        $($('.btn-enviar-archivo')[posicion_arch]).attr('disabled', true);
        $.ajax({
            url : url+'controllers/c_imagenes_temporales.php',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            success: function(respuesta) {
                $($('.barra-envio-archivo .progress-bar')[posicion_arch]).animate({width: "100%"}, 2000, function () {
                    if (respuesta == 'Exitoso') {
                        mostrarImagen();
                        let getDescripcion = $($('.descripcion-archivo')[posicion_arch]).val();
                        let fileName2   = fileName.split('.');
                        let nfileName   = fileName2.length - 1;
                        let iconoArchivo = '';
                        if (fileName2[nfileName] == 'doc' || fileName2[nfileName] == 'docx') {
                            iconoArchivo = url+'images/app/icono-word.png';
                        } else if (fileName2[nfileName] == 'pdf') {
                            iconoArchivo = url+'images/app/icono-pdf.png';
                        } else {
                            iconoArchivo = url+'images/temp/'+fileName;
                        }
                        
                        let numero_img_temp = $('.archivo-temporal').length - 1;
                        $($('.nombre-archivo-temp')[numero_img_temp]).val(fileName);
                        $($('.descrip-archivo-temp')[numero_img_temp]).val(getDescripcion);
                        $($('.previzualizacion-archivo')[numero_img_temp]).attr('src', iconoArchivo);
                        $($('.p-descripcion-archivo')[numero_img_temp]).html(getDescripcion);
                        
                        $($('.btn-eliminar-input-file')[posicion_arch]).trigger('click');
                    } else if (respuesta == 'Error') {
                        $($('.mensaje-error-archivo')[posicion_arch]).show();
                        $($('.mensaje-error-archivo')[posicion_arch]).html('[Error] No se pude subir el archivo.');
                        $($('.btn-enviar-archivo')[posicion_arch]).attr('disabled', false);
                        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).stop();
                        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).removeClass('bg-info');
                        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).addClass('bg-danger');
                    } else if (respuesta == 'Vacio') {
                        $($('.mensaje-error-archivo')[posicion_arch]).show();
                        $($('.mensaje-error-archivo')[posicion_arch]).html('<b>[Error]: No ha seleccionado ningún archivo.</b>');
                        $($('.btn-enviar-archivo')[posicion_arch]).attr('disabled', false);
                        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).stop();
                        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).removeClass('bg-info');
                        $($('.barra-envio-archivo .progress-bar')[posicion_arch]).addClass('bg-danger');
                    }
                });
            }, error: function (msjError){
                $($('.mensaje-error-archivo')[posicion_arch]).show();
                $($('.mensaje-error-archivo')[posicion_arch]).html('<b>[Error]: Problemas de conexión.</b>');
                $($('.btn-enviar-archivo')[posicion_arch]).attr('disabled', false);
                $($('.barra-envio-archivo .progress-bar')[posicion_arch]).stop();
                $($('.barra-envio-archivo .progress-bar')[posicion_arch]).removeClass('bg-info');
                $($('.barra-envio-archivo .progress-bar')[posicion_arch]).addClass('bg-danger');
                console.log('Error: '+msjError.status+' - '+msjError.statusText);
            }
        });
    }
    function eliminarInputFile() {
        let posicion = $(this).attr('data-posicion');
        let nueva_posicion;
        $($('.nuevo-input-file')[posicion]).remove();
        /////////////////////////////////////////////
        nueva_posicion = 0;
        $('.btn-enviar-archivo').each(function () {
            $(this).attr('data-posicion', nueva_posicion);
            nueva_posicion++;
        });
        /////////////////////////////////////////////
        nueva_posicion = 0;
        $('.btn-eliminar-input-file').each(function () {
            $(this).attr('data-posicion', nueva_posicion);
            nueva_posicion++;
        });
        /////////////////////////////////////////////
        if ($('#contenedor-input-file').html() == '') {
            $('#contenedor-input-file').html(mensaje_input_file);
        }
    }
    function mostrarImagen () {
        if ($('#archivos-guardados').html() == mensaje_conte_arch) {
            $('#archivos-guardados').empty();
        }

        let contenido_archivo = '';
        let numero_archivo = $('.archivo-temporal').length;
        contenido_archivo += '<div class="col-sm-2 archivo-temporal position-relative" style="cursor: pointer;">';
        contenido_archivo += '<button type="button" class="btn btn-sm btn-danger rounded-circle btn-eliminar-arch position-absolute" style="padding-top: 2px; padding-bottom: 2px; top: -7px; right: -3px;" data-eliminar="0"><i class="fas fa-times"></i></button>';
        
        contenido_archivo += '<input type="hidden" name="id_archivo_temp[]" class="id-archivo-temp" value="0">';
        contenido_archivo += '<input type="hidden" name="nombre_archivo_temp[]" class="nombre-archivo-temp">';
        contenido_archivo += '<input type="hidden" name="descrip_archivo_temp[]" class="descrip-archivo-temp">';

        contenido_archivo += '<div class="bg-info p-2 rounded">';
        contenido_archivo += '<div><img class="previzualizacion-archivo bg-white border rounded w-100 mb-1 p-2"></div>';
        contenido_archivo += '<p class="p-descripcion-archivo text-white m-0 small text-capitalize"></p>';
        contenido_archivo += '</div>';

        contenido_archivo += '</div>';
        $('#archivos-guardados').append(contenido_archivo);

        $($('.previzualizacion-archivo')[numero_archivo]).bind("contextmenu", function(e){ return false; });
        $($('.archivo-temporal')[numero_archivo]).click(function () {
            $('#modal-detalles-archivo').modal();
            $('#detalles-archivo').empty();

            let nombre_archivo = $($('.nombre-archivo-temp')[numero_archivo]).val();
            let nombre_archivo_arr = nombre_archivo.split('.');
            let nombre_archivo_num = nombre_archivo_arr.length - 1;
            let descripcion_archivo = $($('.descrip-archivo-temp')[numero_archivo]).val();
            
            let contenido_detalles = '';
            if (nombre_archivo_arr[nombre_archivo_num] == 'doc' || nombre_archivo_arr[nombre_archivo_num] == 'docx') {
                contenido_detalles += '';
            } else if ( nombre_archivo_arr[nombre_archivo_num] == 'pdf') {
                contenido_detalles += '<embed class="w-100" src="'+url+'images/archivos/'+nombre_archivo+'" type="application/pdf" height="500px"/>';
            } else {
                contenido_detalles += '<img class="w-100 p-1 border" src="'+url+'images/archivos/'+nombre_archivo+'">';
            }
            contenido_detalles += '<h6 class="text-secondary mt-2 mb-1">Descripción: </h6>';
            contenido_detalles += '<p class="text-secondary m-0 small">'+descripcion_archivo+'</p>';
            $('#detalles-archivo').html(contenido_detalles);
        });
        $($('.btn-eliminar-arch')[numero_archivo]).click(function (e) {
            e.preventDefault();
            $(this).closest('div').find(".archivo-temporal")['prevObject'].remove();
            if ($(this).attr('data-eliminar') != 0) {
                window.eliminarArchivos.push($(this).attr('data-eliminar'));
            }
            if ($('#archivos-guardados').html() == '') {
                $('#archivos-guardados').html(mensaje_conte_arch);
            }
        });
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCIONES PARA MODALES (VENTANAS EMERGENTES).
    // MODAL REGISTRAR OCUPACION
    $('#btn-agregar-ocupacion').click(function (e) {
        e.preventDefault();
        document.form_registrar_ocupacion.reset();
        $("#nueva-nombre-ocupacion").css('background-color', colorn);
        $("#nueva-fomulario-ocupacion").css('background-color', colorn);
        $('#modal-registrar-ocupacion').modal();
        //////////////////////////////////////////////////////////
        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
        $('#nueva-nombre-ocupacion').attr('disabled', false);
        $('#nueva-fomulario-ocupacion').attr('disabled', false);
        $('#btn-registrar-ocupacion').attr('disabled', false);
        $('#btn-registrar-ocupacion span').html('Guardar');
    });
    // DESHABILITAR LA TECLA ENTER PARA EVITAR ENVIAR EL FORMULARIO POR ERROR.
    $('#nueva-nombre-ocupacion').keydown(function (e) {
        if (e.keyCode == 13) e.preventDefault();
    });
    // AL PRESIONAR EL BOTON REGISTRAR TRATAMOS DE ENVIAR LOS DATOS POR AJAX.
    $('#btn-registrar-ocupacion').click(function (e) {
        e.preventDefault();

        let formulario_ocupacion = true;
        // VERIFICAR EL CAMPO DEL NOMBRE DE LA OCUPACION.
        let nueva_nombre_cupacion = $("#nueva-nombre-ocupacion").val();
        if (nueva_nombre_cupacion != '') {
            if(nueva_nombre_cupacion.match(ER_alfaNumericoConEspacios)){
                $("#nueva-nombre-ocupacion").css("background-color", colorb);
            }else{
                $("#nueva-nombre-ocupacion").css("background-color", colorm);
                formulario_ocupacion = false;
            }
        } else {
            $("#nueva-nombre-ocupacion").css("background-color", colorm);
            formulario_ocupacion = false;
        }
        // VERIFICAR EL CAMPO DEL FORMULARIO ENFOCADOS.
        let nueva_fomulario_ocupacion = $("#nueva-fomulario-ocupacion").val();
        if (nueva_fomulario_ocupacion != '') {
            $("#nueva-fomulario-ocupacion").css("background-color", colorb);
        } else {
            $("#nueva-fomulario-ocupacion").css("background-color", colorm);
            formulario_ocupacion = false;
        }
        
        if (formulario_ocupacion) {
            let data = $("#form_registrar_ocupacion").serializeArray();
            data.push({ name: 'opcion', value: 'Registrar ocupacion' });
    
            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('#nueva-nombre-ocupacion').attr('disabled', true);
            $('#nueva-fomulario-ocupacion').attr('disabled', true);
            $('#btn-registrar-ocupacion').attr('disabled', true);
            $('#btn-registrar-ocupacion i.fa-save').addClass('fa-spin');
            $('#btn-registrar-ocupacion span').html('Guardando...');
            $('#btn-cancelar-ocupacion').attr('disabled', true);
            $('#btn-cancelar-ocupacion2').attr('disabled', true);
            $('#modal-registrar-ocupacion').modal({backdrop: 'static', keyboard: false});

            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_facilitador.php',
                    type: 'POST',
                    data: data,
                    success: function (resultados){
                        let color_alerta = '';
                        let icono_alerta = '';

                        if (resultados == 'Registro exitoso') {
                            $('#btn-buscar-ocupacion').trigger('click');
                            color_alerta = 'alert-success';
                            icono_alerta = '<i class="fas fa-check"></i>';
                            setTimeout(() => { $('#modal-registrar-ocupacion').modal('hide');  }, 3000);
                        }  else if (resultados == 'Ya está registrado') {
                            color_alerta = 'alert-warning';
                            icono_alerta = '<i class="fas fa-exclamation-circle"></i>';
                        } else if (resultados == 'Registro fallido') {
                            color_alerta = 'alert-danger';
                            icono_alerta = '<i class="fas fa-times"></i>';
                        }

                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('#btn-registrar-ocupacion i.fa-save').removeClass('fa-spin');
                        $('#btn-cancelar-ocupacion').attr('disabled', false);
                        $('#btn-cancelar-ocupacion2').attr('disabled', false);
                        $('#modal-registrar-ocupacion').modal({backdrop: 'static', keyboard: true});

                        if (resultados != 'Registro exitoso') {
                            $('#nueva-nombre-ocupacion').attr('disabled', false);
                            $('#nueva-fomulario-ocupacion').attr('disabled', false);
                            $('#btn-registrar-ocupacion').attr('disabled', false);
                            $('#btn-registrar-ocupacion span').html('Guardar');
                        } else {
                            $('#btn-registrar-ocupacion span').html('Guardado');
                        }

                        // MENSAJE DE ERROR DE CONEXION.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                        contenedor_mensaje += icono_alerta+' '+resultados;
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';
                        $('#contenedor-mensaje-ocupacion').html(contenedor_mensaje);
                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('.alerta-formulario').fadeOut(500); }, 3000);
                    },
                    error: function (msjError) {
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('#nueva-nombre-ocupacion').attr('disabled', false);
                        $('#nueva-fomulario-ocupacion').attr('disabled', false);
                        $('#btn-registrar-ocupacion').attr('disabled', false);
                        $('#btn-registrar-ocupacion i.fa-save').removeClass('fa-spin');
                        $('#btn-registrar-ocupacion span').html('Guardar');
                        $('#btn-cancelar-ocupacion').attr('disabled', false);
                        $('#btn-cancelar-ocupacion2').attr('disabled', false);
                        $('#modal-registrar-ocupacion').modal({backdrop: 'static', keyboard: true});
    
                        // MENSAJE DE ERROR DE CONEXION.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div class="alert alert-danger mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                        contenedor_mensaje += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';
                        $('#contenedor-mensaje-ocupacion').html(contenedor_mensaje);
                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('.alerta-formulario').fadeOut(500); }, 5000);
    
                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+msjError.status+' - '+msjError.statusText);
                        console.log(msjError.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    $('#btn-buscar-ocupacion').click(function (e) {
        e.preventDefault();
        $('#ocupacion').attr('disabled', true);
        $('#btn-agregar-ocupacion').hide();
        $('#btn-buscar-ocupacion').show();
        $('#btn-buscar-ocupacion').attr('disabled', true);
        $('#btn-buscar-ocupacion').addClass('btn-info');
        $('#btn-buscar-ocupacion').removeClass('btn-danger');
        $('#btn-buscar-ocupacion i.fa-sync-alt').addClass('fa-spin');
        
        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_facilitador.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    opcion: 'Traer ocupaciones actualizadas'
                },
                success: function (resultados){
                    $('#ocupacion').empty();
                    $('#ocupacion').attr('disabled', false);
                    $('#btn-agregar-ocupacion').show();
                    $('#btn-buscar-ocupacion').hide();
                    $('#btn-buscar-ocupacion').attr('disabled', false);
                    $('#btn-buscar-ocupacion').addClass('btn-info');
                    $('#btn-buscar-ocupacion').removeClass('btn-danger');
                    $('#btn-buscar-ocupacion i.fa-sync-alt').removeClass('fa-spin');
                    if (resultados.ocupacion) {
                        for (let i in resultados.ocupacion) {
                            if (resultados.ocupacion[i]['formulario'] == 'F' && $('#ocupacion').html() == '') {
                                $("#ocupacion").html('<option value="">Elija una opción</option>');
                            }
                        }

                        for (let i in resultados.ocupacion) {
                            if (resultados.ocupacion[i]['formulario'] == 'F') {
                                $("#ocupacion").append('<option value="' +resultados.ocupacion[i].codigo +'">' +resultados.ocupacion[i].nombre +"</option>");
                            }
                        }
                    }
                    if ($('#ocupacion').html() == '') {
                        $("#ocupacion").html('<option value="">No hay ocupaciones</option>');
                    }
                },
                error: function (msjError) {
                    $('#btn-buscar-ocupacion').attr('disabled', false);
                    $('#btn-buscar-ocupacion').removeClass('btn-info');
                    $('#btn-buscar-ocupacion').addClass('btn-danger');
                    $('#btn-buscar-ocupacion i.fa-sync-alt').removeClass('fa-spin');

                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+msjError.status+' - '+msjError.statusText);
                    console.log(msjError.responseText);
                }, timer: 15000
            });
        }, 500);
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro () {
        let posicion = $(this).attr('data-posicion');
        window.posicion = posicion;

        $('#form_title').html('Modificar');
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#carga_espera').show(400);
        $('#archivos-guardados').html(mensaje_conte_arch);
        $('#contenedor-input-file').html(mensaje_input_file);
        tipoEnvio = 'Modificar';
        window.eliminarArchivos = [];
        
        // LIMPIAR EL FORMULARIO
        document.formulario.reset();
        $('form .form-control').css('background-color', colorn);
        $('form .custom-select').css('background-color', colorn);
        $('form .custom-control-label').removeClass('inputBien inputMal');
        $('#edad').css('background-color', '');
        $('.icon-alert').hide();
        $('.ocultar-iconos').hide();
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger');
        
        /////////////////////
        // LLENADO DEL FORMULARIO CON LOS DATOS REGISTRADOS.
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula = dataListado.resultados[posicion].cedula;
        // PRIMERA PARTE.
        $('#fecha').val(dataListado.resultados[posicion].fecha);
        $('#nacionalidad').val(dataListado.resultados[posicion].nacionalidad);
        $('#cedula').val(dataListado.resultados[posicion].cedula);
        $('#cedula').trigger('blur');
        $('#nombre_1').val(dataListado.resultados[posicion].nombre1);
        $('#nombre_2').val(dataListado.resultados[posicion].nombre2);
        $('#apellido_1').val(dataListado.resultados[posicion].apellido1);
        $('#apellido_2').val(dataListado.resultados[posicion].apellido2);
        $('#sexo').val(dataListado.resultados[posicion].sexo);
        $('#fecha_n').val(dataListado.resultados[posicion].fecha_n);
        $('#fecha_n').trigger('change');
        $('#lugar_n').val(dataListado.resultados[posicion].lugar_n);
        $('#ocupacion').val(dataListado.resultados[posicion].codigo_ocupacion);
        document.formulario.estado_civil.value = dataListado.resultados[posicion].estado_civil;
        document.formulario.grado_instruccion.value = dataListado.resultados[posicion].nivel_instruccion;
        if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC') { $('#titulo').attr('readonly', false); }

        $('#titulo').val(dataListado.resultados[posicion].titulo_acade);
        $('#alguna_mision').val(dataListado.resultados[posicion].mision_participado);
        $('#telefono_1').val(dataListado.resultados[posicion].telefono1);
        $('#telefono_2').val(dataListado.resultados[posicion].telefono2);
        $('#correo').val(dataListado.resultados[posicion].correo);
        $('#direccion').val(dataListado.resultados[posicion].direccion);

        $('#estado').val(dataListado.resultados[posicion].codigo_estado);
        window.cargarCiudad = true;
        if (dataListado.resultados[posicion].codigo_parroquia != null) {
            window.cargarParroquia = true;
        }
        $('#estado').trigger('change');

        let detalles_archivos = dataListado.resultados[posicion].detalles_archivos;
        for (let index = 0; index < detalles_archivos.length; index++) {
            mostrarImagen();
            let iconoArchivo = '';
            if      (detalles_archivos[index].entension == 'doc' || detalles_archivos[index].entension == 'docx') { iconoArchivo = url+'images/app/icono-word.png'; }
            else if (detalles_archivos[index].entension == 'pdf') { iconoArchivo = url+'images/app/icono-pdf.png'; }
            else    { iconoArchivo = url+'images/archivos/'+detalles_archivos[index].numero_doc+'.'+detalles_archivos[index].entension; }

            $($('.id-archivo-temp')[index]).val(detalles_archivos[index].numero_doc);
            $($('.nombre-archivo-temp')[index]).val(detalles_archivos[index].numero_doc+'.'+detalles_archivos[index].entension);
            $($('.descrip-archivo-temp')[index]).val(detalles_archivos[index].descripcion);
            $($('.previzualizacion-archivo')[index]).attr('src', iconoArchivo);
            $($('.p-descripcion-archivo')[index]).html(detalles_archivos[index].descripcion);
            $($('.btn-eliminar-arch')[index]).attr('data-eliminar', detalles_archivos[index].numero_doc+'.'+detalles_archivos[index].entension);
        }

        setTimeout(() => {
            verificarParte1();
            verificarParte2();
            verificarParte3();
        }, 500);
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();
        verificarParte2();
        verificarParte3();
        if (pestania1 && pestania2 && pestania3) {
            enviarFormulario();
        }
    });
    // FUNCION PARA HACER LA CONSULTA AJAX.
    function enviarFormulario () {
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        data.push({ name: 'nacionalidad2', value: window.nacionalidad });
        data.push({ name: 'cedula2', value: window.cedula });
        data.push({ name: 'eliminare_archivos', value: JSON.stringify(window.eliminarArchivos) });

        // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
        $('#show_table').attr('disabled', true);
        $('#guardar-datos').attr('disabled', true);
        $('#guardar-datos i.fa-save').addClass('fa-spin');
        $('#guardar-datos span').html('Guardando...');
        // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
        $('#contenedor-mensaje').empty();
        $('#contenedor-mensaje2').empty();

        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_facilitador.php',
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
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
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
                    setTimeout(() => { $('.alerta-formulario').fadeOut(500); }, 5000);

                    // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                    $('#show_table').attr('disabled', false);
                    $('#guardar-datos').attr('disabled', false);
                    $('#guardar-datos i.fa-save').removeClass('fa-spin');
                    $('#guardar-datos span').html('Guardar');
                },
                error: function (msjError) {
                    // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                    $('#show_table').attr('disabled', false);
                    $('#guardar-datos').attr('disabled', false);
                    $('#guardar-datos i.fa-save').removeClass('fa-spin');
                    $('#guardar-datos span').html('Guardar');

                    // MENSAJE DE ERROR DE CONEXION.
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div class="alert alert-danger mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                    contenedor_mensaje += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += '</button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje2').html(contenedor_mensaje);
                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('.alerta-formulario').fadeOut(500); }, 5000);

                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+msjError.status+' - '+msjError.statusText);
                    console.log(msjError.responseText);
                }, timer: 15000
            });
        }, 500);
    }
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function cambiarEstatus (e) {
        e.preventDefault();
        let posicion = $(this).attr('data-posicion');
        let nacionalidad = dataListado.resultados[posicion].nacionalidad;
        let cedula = dataListado.resultados[posicion].cedula;

        ///////////////////
        $('.editar-registro').attr('disabled', true);
        $('.cambiar-estatus').attr('disabled', true);
        $('#show_form').attr('disabled', true);
        // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
        $('#contenedor-mensaje').empty();
        $('#contenedor-mensaje2').empty();

        // DEFINIMOS EL ESTATUS POR EL QUE SE VA A ACTUALIZAR
        let estatus = '';
        if (dataListado.resultados[posicion].estatus == 'A') { estatus = 'I'; }
        else { estatus = 'A'; }
        
        setTimeout(() => {
            $.ajax({
                url : url+'controllers/c_facilitador.php',
                type: 'POST',
                data: {
                    opcion: 'Estatus',
                    nacionalidad: nacionalidad,
                    cedula: cedula,
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

                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.editar-registro').attr('disabled', false);
                        $('.cambiar-estatus').attr('disabled', false);
                        $('#show_form').attr('disabled', false);
                    }

                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                    contenedor_mensaje += icono_alerta+' '+resultados;
                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += '</button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje').html(contenedor_mensaje);
                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('.alerta-formulario').fadeOut(500); }, 5000);
                },
                error: function (msjError) {
                    // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                    $('.editar-registro').attr('disabled', false);
                    $('.cambiar-estatus').attr('disabled', false);
                    $('#show_form').attr('disabled', false);

                    // MENSAJE DE ERROR DE CONEXION.
                    let contenedor_mensaje = '';
                    contenedor_mensaje += '<div class="alert alert-danger mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                    contenedor_mensaje += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                    contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += '</button>';
                    contenedor_mensaje += '</div>';
                    $('#contenedor-mensaje').html(contenedor_mensaje);
                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => { $('.alerta-formulario').fadeOut(500); }, 5000);

                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log('Error: '+msjError.status+' - '+msjError.statusText);
                    console.log(msjError.responseText);
                }, timer: 15000
            });
        }, 500);
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos() {
        $.ajax({
            url: url + "controllers/c_facilitador.php",
            type: "POST",
            data: { opcion: "Traer datos" },
            success: function(resultados) {
                try {
                    let data = JSON.parse(resultados);
                    fecha = data.fecha;
                    if (data.ocupacion) {
                        for (let i in data.ocupacion) {
                            $("#ocupacion").append('<option value="' +data.ocupacion[i].codigo +'">' +data.ocupacion[i].nombre +"</option>");
                        }
                        dataOcupacion = data.ocupacion;
                    } else {
                        $("#ocupacion").html(
                            '<option value="">No hay ocupaciones</option>'
                        );
                    }

                    if (data.estado) {
                        for (let i in data.estado) {
                            $("#estado").append(
                                '<option value="' +
                                    data.estado[i].codigo +
                                    '">' +
                                    data.estado[i].nombre +
                                    "</option>"
                            );
                        }
                    } else {
                        $("#estado").html(
                            '<option value="">No hay estados</option>'
                        );
                    }

                    buscar_listado();
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    }
    llamarDatos();
    $('#fecha_n').datepicker();
});
