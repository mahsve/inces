$(function () {
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
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARA GUARDAR LOS RESULTADOS CONSULTADOS.
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado(){
        let filas = 0;
        if (permisos.modificar == 1 || permisos.act_desc == 1)
            filas = 7;
        else
            filas = 6;

        $('#listado_tabla tbody').html('<tr><td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>');
        $("#paginacion").html('<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>');
        $.ajax({
            url : url+'controllers/c_empresa.php',
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
                        for (var i in dataListado.resultados) {
                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="py-2 pl-1 pr-1">'+dataListado.resultados[i].rif+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nil+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].razon_social+'</td>';
                            contenido += '<td class="py-2 px-1" style="min-width: 200px;">'+dataListado.resultados[i].actividad_economica+'</td>';
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
                        }
                        $('.editar_registro').click(editarRegistro);
                        $('.cambiar_estatus').click(cambiarEstatus);
                    } else {
                        $('#listado_tabla tbody').append('<tr><td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay empresas registradas</td></tr>');
                    }
                    //////////////////////////////////////////////
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
    $('#rif').keyup(function () {
        let letraMayus = $('#rif').val().toUpperCase();
        $('#rif').val(letraMayus);
    });
    let validarRif = false;
    $('#rif').blur(function (){
        validarRif = false;
        $('#spinner-rif').hide();
        $('#spinner-rif-confirm').hide();
        $('#spinner-rif-confirm').removeClass('fa-check text-success fa-times text-danger');

        if ($('#rif').val() != '') {
            let parametrosRIF = new RegExp("^([VEJPG]{1})([-])([0-9]{9})$");
            if (parametrosRIF.test($("#rif").val())) {
                if (window.rif != $('#rif').val()) {
                    $.ajax({
                        url : url+'controllers/c_empresa.php',
                        type: 'POST',
                        data: {
                            opcion: 'Verificar RIF',
                            rif: $('#rif').val()
                        },
                        success: function (resultados) {
                            try {
                                $('#spinner-rif').hide();
                                //////////////////////////////////
                                let data = JSON.parse(resultados);
                                if (data == 0) {
                                    validarRif = true;
                                    $('#spinner-rif-confirm').addClass('fa-check text-success');
                                } else {
                                    validarRif = false;
                                    $('#spinner-rif-confirm').addClass('fa-times text-danger');
                                }
                                $('#spinner-rif-confirm').show(200);
                            } catch (error) {
                                console.log(resultados);
                            }
                        },
                        error: function () {
                            alert('Hubo un error al conectar con el servidor y traer los datos.');
                        }
                    });
                } else {
                    validarRif = true;
                }
            } else {
                alert('RIF incorrecto.');
            }
        }
    });
    let validarNacionalidad = false, validarCedula = false;
    $('#nacionalidad').change(function () {
        if ($('#nacionalidad').val() != '') {
            validarNacionalidad = true;
        } else {
            validarNacionalidad = false;
        }

        if (window.elegirNacionalidad == true) {
            window.elegirNacionalidad = false;
            $('#cedula').trigger('blur');
        }
    });
    $('#cedula').blur(function (){
        validarCedula = false;
        $('#spinner-cedula').hide();
        $('#spinner-cedula-confirm').hide();
        $('#spinner-cedula-confirm').removeClass('fa-check text-success fa-times text-danger');

        if($('#nacionalidad').val() != '') {
            if ($('#cedula').val() != '') {
                // let parametrosRIF = new RegExp("^([VEJPG]{1})([-])([0-9]{9})$");
                // if (parametrosRIF.test($("#rif").val())) {
                    if (window.cedula != $('#cedula').val()) {
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
                                        if (confirm('Esta persona ya esta registrada,\n¿Quiere agregarla como contacto de esta empresa?')) {
                                            window.nacionalidad = data[0].nacionalidad;
                                            window.cedula = data[0].cedula;
                                            window.registrar_cont = 'no';
                                            //////////////////////////////////////
                                            $('#nacionalidad').val(data[0].nacionalidad);
                                            $('#cedula').val(data[0].cedula);
                                            $('#nombre_1').val(data[0].nombre1);
                                            $('#nombre_2').val(data[0].nombre2);
                                            $('#apellido_1').val(data[0].apellido1);
                                            $('#apellido_2').val(data[0].apellido2);
                                            $('#sexo').val(data[0].sexo);
                                            $('#estado_c').val(data[0].codigo_estado);
                                            window.ciudad_c = data[0].codigo_ciudad;
                                            window.buscarCiudad_c2 = true;
                                            $('#estado_c').trigger('change');
                                            $('#telefono').val(data[0].telefono1);
                                            $('#correo').val(data[0].correo);
                                            //////////////////////////////////////
                                            $('#spinner-cedula-confirm').addClass('fa-check text-success');
                                            validarNacionalidad = true;
                                            validarCedula = true;
                                        } else {
                                            window.registrar_cont = 'no';
                                            $('#spinner-cedula-confirm').addClass('fa-times text-danger');
                                            validarNacionalidad = false;
                                            validarCedula = false;
                                        }
                                    } else {
                                        window.registrar_cont = 'si';
                                        $('#spinner-cedula-confirm').addClass('fa-check text-success');
                                        validarNacionalidad = true;
                                        validarCedula = true;
                                    }
                                    $('#spinner-cedula-confirm').show(200);
                                } catch (error) {
                                    console.log(resultados);
                                }
                            },
                            error: function () {
                                alert('Hubo un error al conectar con el servidor y traer los datos.');
                            }
                        });
                    } else {
                        validarNacionalidad = true;
                        validarCedula = true;
                    }
                // } else {
                //     alert('RIF incorrecto.');
                // }
            }
        } else {
            alert('Elija la nacionalidad');
            window.elegirNacionalidad = true;
        }
    });
    /////////////////////////////////////////////////////////////////////
    $('#estado').change(buscarCiudades);
    $('#estado_c').change(buscarCiudades);
    function buscarCiudades() {
        let nombreInput = '';
        if($(this).attr('name') == 'estado')
            nombreInput = '#ciudad';
        else
            nombreInput = '#ciudad_c';

        if ($(this).val() != '') {
            $.ajax({
                url : url+'controllers/c_empresa.php',
                type: 'POST',
                data: { opcion: 'Traer ciudades', estado: $(this).val() },
                success: function (resultados) {
                    try {
                        let data = JSON.parse(resultados);
                        $(nombreInput).empty();
                        if (data.ciudades) {
                            $(nombreInput).append('<option value="">Elija una opción</option>');

                            for (let i in data.ciudades) {
                                $(nombreInput).append('<option value="'+data.ciudades[i].codigo+'">'+data.ciudades[i].nombre+'</option>');
                            }
                        } else {
                            $(nombreInput).append('<option value="">No hay ciudades</option>');
                        }

                        if (window.buscarCiudad == true) {
                            $('#ciudad').val(dataListado.resultados[window.posicion].codigo_ciudad);
                            delete window.buscarCiudad;
                        }

                        if (window.buscarCiudad_c1 == true) {
                            $('#ciudad_c').val(dataListado.resultados[window.posicion].datos_personales.codigo_ciudad);
                            delete window.buscarCiudad_c1;
                        }

                        if (window.buscarCiudad_c == true) {
                            $('#estado_c').trigger('change');
                            delete window.buscarCiudad_c;
                            window.buscarCiudad_c1 = true;
                        }

                        if (window.buscarCiudad_c2 == true) {
                            $('#ciudad_c').val(window.ciudad_c);
                            delete window.buscarCiudad_c2;
                            delete window.ciudad_c;
                        }
                        $('#carga_espera').hide(400);
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function () {
                    alert('Hubo un error al conectar con el servidor y traer los datos.');
                }
            });
        } else {
            $(nombreInput).append('<option value="">Elija un estado</option>');
        }
    }
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
        $('#pills-datos-empresa-tab').tab('show');
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
        window.buscarCiudad = false;
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    llamarDatos();
    function llamarDatos()
    {
        $.ajax({
            url : url+'controllers/c_empresa.php',
            type: 'POST',
            data: { opcion: 'Traer datos' },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);
                    $('#actividad_economica').empty();
                    if (data.actividades) {
                        $('#actividad_economica').append('<option value="">Elija una opción</option>');
                        for (let i in data.actividades) {
                            $('#actividad_economica').append('<option value="'+data.actividades[i].codigo+'">'+data.actividades[i].nombre+'</option>');
                        }
                    } else {
                        $('#actividad_economica').append('<option value="">No hay actividades</option>');
                    }

                    $('#estado').empty();
                    $('#ciudad').empty();
                    ///////////////////////
                    $('#estado_c').empty();
                    $('#ciudad_c').empty();
                    if (data.estados) {
                        $('#estado').append('<option value="">Elija una opción</option>');
                        $('#estado_c').append('<option value="">Elija una opción</option>');
                        ///////////////////////
                        $('#ciudad').append('<option value="">Elija un estado</option>');
                        $('#ciudad_c').append('<option value="">Elija un estado</option>');
                        for (let i in data.estados) {
                            $('#estado').append('<option value="'+data.estados[i].codigo+'">'+data.estados[i].nombre+'</option>');
                            $('#estado_c').append('<option value="'+data.estados[i].codigo+'">'+data.estados[i].nombre+'</option>');
                        }
                    } else {
                        $('#estado').append('<option value="">No hay estados</option>');
                        $('#estado_c').append('<option value="">No hay estados</option>');
                    }

                    buscar_listado();
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function (){
                alert('Hubo un error al conectar con el servidor y traer los datos.');
            }
        });
    }
});