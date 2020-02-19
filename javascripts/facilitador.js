$(function() {
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
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
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
                            contenido += '<td class="text-right py-2 px-1">'+cont+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';

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
                        if (data.municipio) {
                            $('#municipio').append('<option value="">Elija una opción</option>');
                            for(let i in data.municipio){
                                $('#municipio').append('<option value="'+data.municipio[i].codigo+'">'+data.municipio[i].nombre+'</option>');
                            }
                        } else {
                            $('#municipio').html('<option value="">No hay municipios</option>');
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
        /////////////////////
        // $('#pills-datos-empresa-tab').tab('show');
    });
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
        tipoEnvio = 'Modificar';
        /////////////////////
        limpiarFormulario();
        /////////////////////
        // LLENADO DEL FORMULARIO CON LOS DATOS REGISTRADOS.
        window.rif = dataListado.resultados[posicion].rif;
        $('#rif').val(dataListado.resultados[posicion].rif);
        $('#nil').val(dataListado.resultados[posicion].nil);
        $('#razon_social').val(dataListado.resultados[posicion].razon_social);
        $('#actividad_economica').val(dataListado.resultados[posicion].codigo_actividad_e);
        $('#codigo_aportante').val(dataListado.resultados[posicion].codigo_aportante);
        $('#telefono_1').val(dataListado.resultados[posicion].telefono1);
        $('#telefono_2').val(dataListado.resultados[posicion].telefono2);
        $('#estado').val(dataListado.resultados[posicion].codigo_estado);
        window.buscarCiudad = true;
        $('#estado').trigger('change');
        $('#direccion').val(dataListado.resultados[posicion].direccion);
        /////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////
        window.nacionalidad = dataListado.resultados[posicion].datos_personales.nacionalidad;
        window.cedula = dataListado.resultados[posicion].datos_personales.cedula;
        $('#nacionalidad').val(dataListado.resultados[posicion].datos_personales.nacionalidad);
        $('#cedula').val(dataListado.resultados[posicion].datos_personales.cedula);
        $('#nombre_1').val(dataListado.resultados[posicion].datos_personales.nombre1);
        $('#nombre_2').val(dataListado.resultados[posicion].datos_personales.nombre2);
        $('#apellido_1').val(dataListado.resultados[posicion].datos_personales.apellido1);
        $('#apellido_2').val(dataListado.resultados[posicion].datos_personales.apellido2);
        $('#sexo').val(dataListado.resultados[posicion].datos_personales.sexo);
        $('#estado_c').val(dataListado.resultados[posicion].datos_personales.codigo_estado);
        window.buscarCiudad_c = true;
        $('#telefono').val(dataListado.resultados[posicion].datos_personales.telefono1);
        $('#correo').val(dataListado.resultados[posicion].datos_personales.correo);
        /////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////
        validarRif = true;
        validarNacionalidad = true;
        validarCedula = true;
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        if (validarRif && validarNacionalidad && validarCedula) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion', value: tipoEnvio });
            data.push({ name: 'rif2', value: window.rif });
            data.push({ name: 'nacionalidad2', value: window.nacionalidad });
            data.push({ name: 'cedula2', value: window.cedula });
            data.push({ name: 'registrar_cont', value: window.registrar_cont });

            $.ajax({
                url : url+'controllers/c_empresa.php',
                type: 'POST',
                data: data,
                success: function (resultados) {
                    alert(resultados);
                    if (resultados == 'Registro exitoso' || resultados == 'Modificacion exitosa'){
                        $('#show_table').trigger('click');
                        buscar_listado();
                    }
                    // delete  window.posicion,
                    //         window.rif,
                    //         window.nacionalidad,
                    //         window.cedula;
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
        let rif = dataListado.resultados[posicion].rif;
        let estatus = '';
        if (dataListado.resultados[posicion].estatus == 'A')
            estatus = 'I';
        else
            estatus = 'A';
        
        $.ajax({
            url : url+'controllers/c_empresa.php',
            type: 'POST',
            data: {
                opcion: 'Estatus',
                rif: rif,
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
