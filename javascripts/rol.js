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
    /////////////////////////////////////////////////////////////////////
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado(){
        $('#listado_tabla tbody').html('<tr><td colspan="5" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>');
        $("#paginacion").html('<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>');
        $.ajax({
            url : url+'controllers/c_rol.php',
            type: 'POST',
            data: {
                opcion  : 'Consultar',
                numero  : parseInt(numeroDeLaPagina-1) * parseInt($('#cantidad_a_buscar').val()),
                cantidad: parseInt($('#cantidad_a_buscar').val()),
                ordenar : parseInt($('#ordenar_por').val()),
                tipo_ord: parseInt($('#campo_ordenar').val()),
                campo   : $('#campo_busqueda').val()
            }, success: function (resultados){
                try {
                    $('#listado_tabla tbody').empty();
                    dataListado = JSON.parse(resultados);
                    if (dataListado.resultados) {
                        for (var i in dataListado.resultados) {
                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="text-right py-2 px-1">'+dataListado.resultados[i].codigo+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nombre+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+dataListado.resultados[i].numero_m+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+dataListado.resultados[i].numero_v+'</td>';
                            contenido += '<td class="py-1 px-1">';
                            contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'"><i class="fas fa-pencil-alt"></i></button>';
                            if (xls != dataListado.resultados[i].codigo)
                                contenido += '<button type="button" class="btn btn-sm btn-danger eliminar_registro" data-posicion="'+i+'"><i class="fas fa-trash"></i></button>';
                            contenido += '</div></div></td></tr>';
                            $('#listado_tabla tbody').append(contenido);
                        }
                        $('.editar_registro').click(editarRegistro);
                        $('.eliminar_registro').click(eliminarRegistro);
                    } else {
                        $('#listado_tabla tbody').append('<tr><td colspan="5" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay roles registradas</td></tr>');
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
    //
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

        $('.check_modulos').each(function () {
            for (let i in dataListado.resultados[posicion].modulos) {
                if ($(this).val() == dataListado.resultados[posicion].modulos[i].codigo_modulo) {
                    $(this).prop('checked', true);
                }
            }
        });

        $('.check_vistas').each(function () {
            for (let i in dataListado.resultados[posicion].vistas) {
                if ($(this).val() == dataListado.resultados[posicion].vistas[i].codigo_vista) {
                    $(this).prop('checked', true);

                    if (dataListado.resultados[posicion].vistas[i].registrar == 1)
                        $('#registrar_'+dataListado.resultados[posicion].vistas[i].codigo_vista).prop('checked', true);

                    if (dataListado.resultados[posicion].vistas[i].modificar == 1)
                        $('#modificar_'+dataListado.resultados[posicion].vistas[i].codigo_vista).prop('checked', true);

                    if (dataListado.resultados[posicion].vistas[i].act_desc == 1)
                        $('#estatus_'+dataListado.resultados[posicion].vistas[i].codigo_vista).prop('checked', true);
                }
            }
        });
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        data.push({ name: 'codigo', value: window.codigo });
        
        $.ajax({
            url : url+'controllers/c_rol.php',
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
    });
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function eliminarRegistro () {
        let posicion = $(this).attr('data-posicion');
        let codigo = dataListado.resultados[posicion].codigo;
        
        $.ajax({
            url : url+'controllers/c_rol.php',
            type: 'POST',
            data: {
                opcion: 'Eliminar',
                codigo: codigo
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
        $('.collapse').collapse('hide');
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos() {
        $.ajax({
            url : url+'controllers/c_rol.php',
            type: 'POST',
            data: { opcion: 'Traer datos' },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);
                    for (let i in data) {
                        let contenedorModulo = '';
                        contenedorModulo += '<div class="border rounded position-relative p-2 mb-1">';
                        //////////////////////////////////////////////////////////////////////////////
                        contenedorModulo += '<div class="custom-control custom-checkbox mr-sm-2">';
                        contenedorModulo += '<input type="checkbox" name="modulos[]" class="custom-control-input check_modulos" id="codigo_modulo'+data[i].codigo+'" value="'+data[i].codigo+'">';
                        contenedorModulo += '<label class="custom-control-label" for="codigo_modulo'+data[i].codigo+'">'+data[i].nombre+'</label>';
                        contenedorModulo += '</div>';
                        //////////////////////////////////////////////////////////////////////////////
                        contenedorModulo += '<button class="btn btn-sm position-absolute btn-info" type="button" data-toggle="collapse" data-target="#contenedor_vistas'+data[i].codigo+'" aria-expanded="false" aria-controls="contenedor_vistas'+data[i].codigo+'" style="right: 5px; top: 5px;"><i class="fas fa-plus"></i></button>';
                        //////////////////////////////////////////////////////////////////////////////
                        contenedorModulo += '<div id="contenedor_vistas'+data[i].codigo+'" class="collapse">';
                        contenedorModulo += '<div class="border rounded mt-2 p-2">';
                        for (let j in data[i].vistas) {
                            contenedorModulo += '<div class="custom-control custom-checkbox mr-sm-2">';
                            contenedorModulo += '<input type="checkbox" name="vistas[]" class="custom-control-input check_vistas" id="codigo_vista'+data[i].vistas[j].codigo+'" value="'+data[i].vistas[j].codigo+'">';
                            contenedorModulo += '<label class="custom-control-label" for="codigo_vista'+data[i].vistas[j].codigo+'">'+data[i].vistas[j].nombre+'</label>';
                            contenedorModulo += '</div>';
                            //////////////////////////////////////////////////////////////////////////
                            contenedorModulo += '<div class="pl-4 ml-2 pt-2 pb-2">';
                            //////////////////////////////////////////////////////////////////////////
                            contenedorModulo += '<div class="custom-control custom-checkbox mr-sm-2">';
                            contenedorModulo += '<input type="checkbox" name="registrar'+data[i].vistas[j].codigo+'" class="custom-control-input" id="registrar_'+data[i].vistas[j].codigo+'" value="1">';
                            contenedorModulo += '<label class="custom-control-label" for="registrar_'+data[i].vistas[j].codigo+'">Registrar</label>';
                            contenedorModulo += '</div>';
                            contenedorModulo += '<div class="custom-control custom-checkbox mr-sm-2">';
                            contenedorModulo += '<input type="checkbox" name="modificar'+data[i].vistas[j].codigo+'" class="custom-control-input" id="modificar_'+data[i].vistas[j].codigo+'" value="1">';
                            contenedorModulo += '<label class="custom-control-label" for="modificar_'+data[i].vistas[j].codigo+'">Modificar</label>';
                            contenedorModulo += '</div>';
                            contenedorModulo += '<div class="custom-control custom-checkbox mr-sm-2">';
                            contenedorModulo += '<input type="checkbox" name="estatus'+data[i].vistas[j].codigo+'" class="custom-control-input" id="estatus_'+data[i].vistas[j].codigo+'" value="1">';
                            contenedorModulo += '<label class="custom-control-label" for="estatus_'+data[i].vistas[j].codigo+'">Gestionar estatus / Eliminar</label>';
                            contenedorModulo += '</div>';
                            //////////////////////////////////////////////////////////////////////////
                            contenedorModulo += '</div>';
                        }
                        contenedorModulo += '</div>';
                        contenedorModulo += '</div>';
                        //////////////////////////////////////////////////////////////////////////////
                        contenedorModulo += '</div>';    
                        $('#contenedor_modulos').append(contenedorModulo);
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