$(function() {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    let ER_caracteresConEspacios = /^([a-zA-Z,.\x7f-\xff](\s[a-zA-Z,.\x7f-\xff])*)+$/;
    let ER_alfaNumericoConEspacios=/^([a-zA-Z0-9,.\x7f-\xff](\s[a-zA-Z0-9,.\x7f-\xff])*)+$/;
    let ER_NumericoSinEspacios=/^([0-9])+$/;
    let ER_NumericoConComa=/^([0-9.])+$/;
    let ER_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    /////////////////////////////////////////////////////////////////////
    let pestania1, pestania2, pestania3;
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
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    let formatos_acce   = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx'];
    let mensaje_conte_arch = '<div class="col-sm-12"><h6 class="text-center m-0 text-uppercase text-secondary"><i class="fas fa-archive text-dark"></i> No hay archivos guardados</h6></div>';
    let mensaje_input_file = '<h6 class="text-center py-2 m-0 text-uppercase text-secondary">Presione el botón <button type="button" class="btn btn-sm btn-info" disabled="true" style="height: 22px; padding: 3px 5px; vertical-align: top;"><i class="fas fa-plus" style="font-size: 9px; vertical-align: top; padding-top: 3px;"></i></button> para agregar archivos</h6>';
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado(){
        let filas = 0;
        if (permisos.modificar == 1 || permisos.act_desc == 1)
            filas = 8;
        else
            filas = 7;

        $('#listado_tabla tbody').html('<tr><td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>');
        $("#paginacion").html('<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>');
        $.ajax({
            url : url+'controllers/c_facilitador.php',
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
                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nacionalidad+'-'+dataListado.resultados[i].cedula+'</td>';
                            
                            let nombre_completo = dataListado.resultados[i].nombre1;
                            if (dataListado.resultados[i].nombre2 != '' && dataListado.resultados[i].nombre2 != null) {
                                nombre_completo += ' '+dataListado.resultados[i].nombre2;
                            }
                            nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            if (dataListado.resultados[i].apellido1 != '' && dataListado.resultados[i].apellido1 != null) {
                                nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            }
                            contenido += '<td class="py-2 px-1">'+nombre_completo+'</td>';

                            let year    = dataListado.resultados[i].fecha_n.substr(0,4);
                            let month   = dataListado.resultados[i].fecha_n.substr(5,2);
                            let day     = dataListado.resultados[i].fecha_n.substr(8,2);
                            let yearA   = fecha.substr(0,4);
                            let monthA  = fecha.substr(5,2);
                            let dayA    = fecha.substr(8,2);
                            
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
                                
                            contenido += '<td class="py-2 px-1">'+day+'-'+month+'-'+year+'</td>';
                            contenido += '<td class="py-2 text-center px-1">'+edad+'</td>';
    
                            let sexo = '';
                            if (dataListado.resultados[i].sexo == 'M')
                                sexo = 'Masculino';
                            else if (dataListado.resultados[i].sexo == 'F')
                                sexo = 'Femenino';
                            else
                                sexo = 'Indefinido';

                            contenido += '<td class="py-2 px-1">'+sexo+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].telefono1+'</td>';

                            if (dataListado.resultados[i].estatus == 'A')
                                contenido += '<td class="text-center py-2 px-1"><span class="badge badge-success"><i class="fas fa-check mr-1"></i>Activo</span></td>';
                            else if (dataListado.resultados[i].estatus == 'I')
                                contenido += '<td class="text-center py-2 px-1"><span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Inactivo</span></td>';

                            if (permisos.modificar == 1 || permisos.act_desc == 1) {
                                contenido += '<td class="py-1 px-1">';
                                ////////////////////////////
                                if (permisos.modificar == 1)
                                    contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'"><i class="fas fa-pencil-alt"></i></button>';
                                    
                                if (permisos.act_desc == 1) {
                                    if (dataListado.resultados[i].estatus == 'A')
                                        contenido += '<button type="button" class="btn btn-sm btn-danger cambiar_estatus" data-posicion="'+i+'"><i class="fas fa-times" style="padding: 0px 2px;"></i></button>';
                                    else
                                        contenido += '<button type="button" class="btn btn-sm btn-success cambiar_estatus" data-posicion="'+i+'"><i class="fas fa-check"></i></button>';
                                }
                                ////////////////////////////
                                contenido += '</td>';
                            }
                            contenido += '</tr>';
                            $('#listado_tabla tbody').append(contenido);
                            cont++;
                        }
                        $('.editar_registro').click(editarRegistro);
                        $('.cambiar_estatus').click(cambiarEstatus);
                    } else {
                        $('#listado_tabla tbody').append('<tr><td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay facilitadores registrados</td></tr>');
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
    function verificarParte1 () {
        pestania1 = true;
        // VERIFICAR EL CAMPO DE NACIONALIDAD
        let nacionalidad = $("#nacionalidad").val();
        if(nacionalidad != ''){
            $("#nacionalidad").css("background-color", colorb);
        } else {
            $("#nacionalidad").css("background-color", colorm);
            pestania1 = false;
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
                    pestania1 = false;
                }
            }else{
                $("#cedula").css("background-color", colorm);
                pestania1 = false;
            }
        }else{
            $("#cedula").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL PRIMER NOMBRE
        let nombre_1 = $("#nombre_1").val();
        if(nombre_1 != ''){
            if(nombre_1.match(ER_caracteresConEspacios)){
                $("#nombre_1").css("background-color", colorb);
            }else{
                $("#nombre_1").css("background-color", colorm);
                pestania1 = false;
            }
        }else{
            $("#nombre_1").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO NOMBRE
        let nombre_2 = $("#nombre_2").val();
        if(nombre_2 != ''){
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
        if(apellido_1 != ''){
            if(apellido_1.match(ER_caracteresConEspacios)){
                $("#apellido_1").css("background-color", colorb);
            }else{
                $("#apellido_1").css("background-color", colorm);
                pestania1 = false;
            }
        }else{
            $("#apellido_1").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL SEGUNDO APELLIDO
        let apellido_2 = $("#apellido_2").val();
        if(apellido_2 != ''){
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
        if(sexo != ''){
            $("#sexo").css("background-color", colorb);
        } else {
            $("#sexo").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DE FECHA DE NACIMIENTO
        let fecha_n = $("#fecha_n").val();
        if(fecha_n != ''){
            let edad_cal = parseInt($('#edad').val());
            if (edad_cal >= 17 && edad_cal <= 19) {
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
        if(lugar_n != ''){
            if(lugar_n.match(ER_alfaNumericoCompleto)){
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
        if(ocupacion != ''){
            $("#ocupacion").css("background-color", colorb);
        } else {
            $("#ocupacion").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DE ESTADO CIVIL
        let estado_civil = document.formulario.estado_civil.value;
        $(".radio_estado_c_label").removeClass('inputMal');
        if(estado_civil == ''){
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
            if(titulo_edu != ''){
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
        // VERIFICAR EL CAMPO DE TURNO
        let turno = $("#turno").val();
        if(turno != ''){
            $("#turno").css("background-color", colorb);
        } else {
            $("#turno").css("background-color", colorm);
            pestania2 = false;
        }
        // VERIFICAR EL CAMPO DE SALIDA OCUPACIONAL
        let oficio = $("#oficio").val();
        if(oficio != ''){
            $("#oficio").css("background-color", colorb);
        } else {
            $("#oficio").css("background-color", colorm);
            pestania2 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania2) {
            $('#icon-contacto').hide();
        } else {
            $('#icon-contacto').show();
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
        // VERIFICAR EL CAMPO DE AREA
        let area = $("#area").val();
        if(area != ''){
            $("#area").css("background-color", colorb);
        } else {
            $("#area").css("background-color", colorm);
            pestania3 = false;
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
        // VERIFICAR EL CAMPO DE PUNTO DE REFERENCIA
        let punto_referencia = $("#punto_referencia").val();
        if(punto_referencia != ''){
            if(punto_referencia.match(ER_alfaNumericoCompleto)){
                $("#punto_referencia").css("background-color", colorb);
            }else{
                $("#punto_referencia").css("background-color", colorm);
                pestania3 = false;
            }
        }else{
            $("#punto_referencia").css("background-color", colorm);
            pestania3 = false;
        }
        // SI ALGUNO NO CUMPLE LOS CAMPOS SE MUESTRA UN ICONO Y NO SE DEJA ENVIAR EL FORMULARIO.
        if (pestania3) {
            $('#icon-ubicacion').hide();
        } else {
            $('#icon-ubicacion').show();
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
        let year    = $(this).val().substr(0,4);
        let month   = $(this).val().substr(5,2);
        let day     = $(this).val().substr(8,2);
        let yearA   = fecha.substr(0,4);
        let monthA  = fecha.substr(5,2);
        let dayA    = fecha.substr(8,2);
            
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
        
        // if (edad > 16 && edad < 19) {
        //     alert8
        // }
        $('#edad').val(edad);
    });
    $('.radio_educacion').click(function () {
        if ($(this).val() == 'SI' || $(this).val() == 'SC')
            $('#titulo').attr('disabled', false);
        else {
            $('#titulo').attr('disabled', true);
            $('#titulo').val('');
        }
    });
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
    /////////////////////////////////////////////////////////////////////
    $('#show_form').click(function (){
        $('#form_title').html('Registrar');
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#carga_espera').hide(400);
        $('#archivos-guardados').html(mensaje_conte_arch);
        $('#contenedor-input-file').html(mensaje_input_file);
        tipoEnvio = 'Registrar';
        /////////////////////
        window.eliminarArchivos = [];
        /////////////////////
        limpiarFormulario();
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
        contenedor_file += '<label class="small m-0">Archivo <span class="text-danger">*</span></label>';
        contenedor_file += '<div class="custom-file custom-file-sm">';
        contenedor_file += '<input type="file" name="archivo_input_file[]" class="archivo-input-file custom-file-input" accept=".jpeg, .jpg, .png, .pdf, .doc, .docx">';
        contenedor_file += '<label class="custom-file-label py-1 m-0">Seleccionar Archivo</label>';
        contenedor_file += '</div>';
        contenedor_file += '</div>';
        /////////////////////////////////////////////
        contenedor_file += '<div class="col-sm-6">';
        contenedor_file += '<div class="form-group m-0">';
        contenedor_file += '<label class="small m-0">Descripción <span class="text-danger">*</span></label>';
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
        contenido_archivo += '<div class="col-sm-2 archivo-temporal position-relative">';
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
    function desabilitarEnter (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro() {
        let posicion = $(this).attr('data-posicion');
        window.posicion = posicion;
        /////////////////////
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#form_title').html('Modificar');
        $('#carga_espera').show();
        $('#contenedor-archivos').html(mensaje_input_file);
        tipoEnvio = 'Modificar';
        /////////////////////
        limpiarFormulario();
        /////////////////////
        // LLENADO DEL FORMULARIO CON LOS DATOS REGISTRADOS.
        window.nacionalidad = dataListado.resultados[posicion].nacionalidad;
        window.cedula = dataListado.resultados[posicion].cedula;
        // PRIMERA PARTE.
        $('#fecha').val(dataListado.resultados[posicion].fecha);
        $('#nacionalidad').val(dataListado.resultados[posicion].nacionalidad);
        $('#cedula').val(dataListado.resultados[posicion].cedula);
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
        if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC')
            $('#titulo').attr('disabled', false);

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
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        if (true) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion', value: tipoEnvio });
            data.push({ name: 'nacionalidad2', value: window.nacionalidad });
            data.push({ name: 'cedula2', value: window.cedula });

            $.ajax({
                url : url+'controllers/c_facilitador.php',
                type: 'POST',
                data: data,
                success: function (resultados) {
                    alert(resultados);
                    if (resultados == 'Registro exitoso' || resultados == 'Modificacion exitosa'){
                        $('#show_table').trigger('click');
                        buscar_listado();
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        }
    });
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function cambiarEstatus () {
        let posicion = $(this).attr('data-posicion');
        let nacionalidad = dataListado.resultados[posicion].nacionalidad;
        let cedula = dataListado.resultados[posicion].cedula;
        let estatus = '';
        if (dataListado.resultados[posicion].estatus == 'A')
            estatus = 'I';
        else
            estatus = 'A';
        
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
        $('.ocultar-iconos').hide();
        $('.limpiar-estatus').removeClass('fa-check text-success fa-times text-danger');
        /////////////////////////////////////////////////////////////////
        // window.buscarCiudad = false;
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
                            $("#ocupacion").append(
                                '<option value="' +
                                    data.ocupacion[i].codigo +
                                    '">' +
                                    data.ocupacion[i].nombre +
                                    "</option>"
                            );
                        }
                        dataOcupacion = data.ocupacion;
                    } else {
                        $("#ocupacion").html(
                            '<option value="">No hay ocupaciones</option>'
                        );
                    }

                    $("#radios_oficios").empty();
                    if (data.oficio) {
                        let contenido_oficio = "";
                        contenido_oficio +=
                            '<div class="custom-control custom-radio">';
                        contenido_oficio +=
                            '<input type="radio" id="oficio_asd_0" name="buscar_oficio" class="custom-control-input" value="" checked>';
                        contenido_oficio +=
                            '<label class="custom-control-label" for="oficio_asd_0">Todos</label>';
                        contenido_oficio += "</div>";

                        $("#radios_oficios").append(contenido_oficio);
                        for (let i in data.oficio) {
                            $("#oficio").append(
                                '<option value="' +
                                    data.oficio[i].codigo +
                                    '">' +
                                    data.oficio[i].nombre +
                                    "</option>"
                            );

                            let contenido_oficio = "";
                            contenido_oficio +=
                                '<div class="custom-control custom-radio">';
                            contenido_oficio +=
                                '<input type="radio" id="oficio_asd_' +
                                data.oficio[i].codigo +
                                '" name="buscar_oficio" class="custom-control-input" value="' +
                                data.oficio[i].codigo +
                                '">';
                            contenido_oficio +=
                                '<label class="custom-control-label" for="oficio_asd_' +
                                data.oficio[i].codigo +
                                '">' +
                                data.oficio[i].nombre +
                                "</label>";
                            contenido_oficio += "</div>";
                            $("#radios_oficios").append(contenido_oficio);
                        }
                    } else {
                        $("#oficio").html(
                            '<option value="">No hay oficios</option>'
                        );

                        let contenido_oficio = "";
                        contenido_oficio +=
                            '<div class="custom-control custom-radio">';
                        contenido_oficio +=
                            '<input type="radio" id="oficio_asd_0" name="buscar_oficio" class="custom-control-input" value="" checked>';
                        contenido_oficio +=
                            '<label class="custom-control-label" for="oficio_asd_0">Todos</label>';
                        contenido_oficio += "</div>";
                        $("#radios_oficios").append(contenido_oficio);
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
