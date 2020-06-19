$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // DATOS DE LA TABLA Y PAGINACION 
    let numeroDeLaPagina    = 1;
    $('#cantidad_a_buscar').change(restablecerN);
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
    /////////////////////////////////////////////////////////////////////
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARA GUARDAR LOS RESULTADOS CONSULTADOS.
    let dataModulos     = [];   // VARIABLE PARA GUARDAR LOS MODULOS Y AGREGARLOS A LOS SELECTS.
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado () {
        let filas = 0;
        if (permisos.modificar == 1 || permisos.act_desc == 1)
            filas = 6;
        else
            filas = 5;

        $('#listado_tabla tbody').html('<tr><td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>');
        $("#paginacion").html('<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>');
        $.ajax({
            url : url+'controllers/c_vista.php',
            type: 'POST',
            data: {
                opcion  : 'Consultar',
                numero  : parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()),
                cantidad: parseInt($('#cantidad_a_buscar').val()),
                campo   : $('#campo_busqueda').val(),
            }, success: function (resultados){
                try {
                    $('#listado_tabla tbody').empty();
                    dataListado = JSON.parse(resultados);
                    if (dataListado.resultados) {
                        if (permisos.modificar == 1)
                            empezarOrdernarPosicion();

                        let cont = parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()) + 1;
                        for (var i in dataListado.resultados) {
                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="text-right py-2 pl-1 pr-1">'+cont+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].modulo+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+dataListado.resultados[i].posicion+'</td>';
                            contenido += '<td class="text-center py-2 px-1 text-info"><i class="'+dataListado.resultados[i].icono+'"></i></td>';
                            if (permisos.modificar == 1 || permisos.act_desc == 1) {
                                contenido += '<td class="py-1 px-1">';
                                ////////////////////////////
                                if (permisos.modificar == 1)
                                    contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'"><i class="fas fa-pencil-alt"></i></button>';
                                if (permisos.act_desc == 1)
                                    contenido += '<button type="button" class="btn btn-sm btn-danger eliminar_registro" data-posicion="'+i+'"><i class="fas fa-trash"></i></button>';
                                ////////////////////////////
                                contenido += '</td>';
                            }
                            contenido += '</tr>';
                            $('#listado_tabla tbody').append(contenido);
                            cont++;
                        }
                        $('.editar_registro').click(editarRegistro);
                        $('.eliminar_registro').click(eliminarRegistro);
                    } else {
                        $('#listado_tabla tbody').append('<tr><td colspan="'+filas+'" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay módulos de sistema registrados</td></tr>');
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
    $('#mostrar_modal_ordenar').click(function (e) {
        if ($('#campo_busqueda').val() == '') {
            $('#modulo2').val('');
            $('#lista-modulos').empty();
            $('#modar_cambiar_orden').modal();
        } else {
            alert('No debe haber nada en el campo de búsqueda');
        }
    });
    $('#modulo2').change(function () {
        if ($('#modulo2').val() != '') {
            $('#lista-modulos').html('<li class="text-secondary text-center border rounded p-2"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando...</li>');

            $.ajax({
                url : url+'controllers/c_vista.php',
                type: 'POST',
                data: {
                    opcion: 'Traer vistas',
                    modulo: $('#modulo2').val()
                },
                success: function (resultados){
                    $('#lista-modulos').empty();
                    try {
                        let data = JSON.parse(resultados);
                        for (var i in data) {
                            let contenido2 = '';
                            contenido2 += '<li class="nav-item d-flex justify-content-between align-items-center border rounded text-secondary bg-white mouse-move p-2 my-1">';
                            contenido2 += '<span>'+data[i].nombre+'</span>';
                            contenido2 += '<i class="'+data[i].icono+' pr-2"></i>';
                            contenido2 += '<input type="hidden" name="codigo[]" value="'+data[i].codigo+'">';
                            contenido2 += '<input type="hidden" name="posicion[]" class="ordenar" value="'+data[i].posicion+'">';
                            contenido2 += '</li>';
                            $('#lista-modulos').append(contenido2);
                        }
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function (){
                    console.log('error');
                }
            });
        } else {
            $('#lista-modulos').empty();
        }
    });
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
    $('#nombre').keyup(function () {
        $('#titulo_prueba').empty();
        $('#titulo_prueba').html($(this).val());
    });
    $('#icono').keyup(function () {
        $('#icono_prueba').removeAttr('class');
        $('#icono_prueba').addClass($(this).val());
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
    // FUNCION PARA ACTUALIZAR LAS POSICIONES DE LOS MODULOS SI ESTE CAMBIA PARA LUEGO SER GUARDADOS EN LA BD.
    function empezarOrdernarPosicion () {
        setInterval(() => {
            let cont = parseInt(numeroDeLaPagina - 1) * parseInt($('#cantidad_a_buscar').val());
            $('.ordenar').each(function () {
                $(this).val(cont + 1);
                cont++;
            });
        }, 400);
    }
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro() {
        let posicion = $(this).attr('data-posicion');
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
        window.codigo = dataListado.resultados[posicion].codigo;
        $('#nombre').val(dataListado.resultados[posicion].nombre);
        $('#nombre').trigger('keyup');
        $('#icono').val(dataListado.resultados[posicion].icono);
        $('#icono').trigger('keyup');
        /////////////////////
        $('#modulo').val(dataListado.resultados[posicion].codigo_modulo);
        $('#enlace').val(dataListado.resultados[posicion].enlace);
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        data.push({ name: 'codigo', value: window.codigo });
        
        $.ajax({
            url : url+'controllers/c_vista.php',
            type: 'POST',
            data: data,
            success: function (resultados) {
                alert(resultados);
                if (resultados == 'Registro exitoso') {
                    $('#show_table').trigger('click');
                    buscar_listado();
                } else if (resultados == 'Modificacion exitosa')
                    location.reload();
            },
            error: function () {
                console.log('error');
            }
        });
    });
    // FUNCION PARA GUARDAR LAS NUEVAS POSICIONES ASIGNADAS A LAS VISTAS.
    $('#guardar_lista_ordenada').click(function () {
        if ($('#modulo2').val() != '') {
            var data = $("#formulario2").serializeArray();
            data.push({ name: 'opcion', value: 'Modificar orden' });
            
            $.ajax({
                url : url+'controllers/c_vista.php',
                type: 'POST',
                data: data,
                success: function (resultados) {
                    alert(resultados);
                    if (resultados == 'Modificacion exitosa')
                        location.reload();
                },
                error: function () {
                    console.log('error');
                }
            });
        } else {
            alert('Seleccione un módulo');
        }
    });
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function eliminarRegistro () {
        let posicion = $(this).attr('data-posicion');
        let codigo = dataListado.resultados[posicion].codigo;
        let modulo = dataListado.resultados[posicion].codigo_modulo;
        
        $.ajax({
            url : url+'controllers/c_vista.php',
            type: 'POST',
            data: {
                opcion: 'Eliminar',
                codigo: codigo,
                modulo: modulo
            },
            success: function (resultados) {
                alert(resultados);
                if (resultados == 'Modificacion exitosa')
                    location.reload();
            },
            error: function () {
                console.log('error');
            }
        });
    }
    // FUNCION PARA LIMPIAR EL FORMULARIO DE LOS DATOS ANTERIORES.
    function limpiarFormulario(){
        document.formulario.reset();
        $('#icono_prueba').removeAttr('class');
        $('#titulo_prueba').empty();
    }
    /////////////////////////////////////////////////////////////////////
    $("#lista-modulos").sortable();
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos() {
        $.ajax({
            url : url+'controllers/c_vista.php',
            type: 'POST',
            data: { opcion: 'Traer datos' },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);
                    if (data.modulos) {
                        for (let i in data.modulos) {
                            $('#modulo').append('<option value="'+data.modulos[i].codigo+'">'+data.modulos[i].nombre+'</option>');
                            $('#modulo2').append('<option value="'+data.modulos[i].codigo+'">'+data.modulos[i].nombre+'</option>');
                        }
                        dataModulos = data.modulos;
                    } else {
                        $('#modulo').html('<option value="">No hay módulos</option>');
                        $('#modulo2').html('<option value="">No hay módulos</option>');
                    }

                    buscar_listado();
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
});