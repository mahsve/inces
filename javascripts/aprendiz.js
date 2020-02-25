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
    let fecha           = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let fecha2          = '';   // VARIABLE PARA GUARDAR LA FECHA DE LA FICHA.
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
                            let year    = dataListado.resultados[i].fecha_n.substr(0,4);
                            let month   = dataListado.resultados[i].fecha_n.substr(5,2);
                            let day     = dataListado.resultados[i].fecha_n.substr(8,2);
                            let yearA   = fecha.substr(0,4);
                            let monthA  = fecha.substr(5,2);
                            let dayA    = fecha.substr(8,2);
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
                                    contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 aceptar_postulante" data-posicion="'+i+'"><i class="fas fa-check text-center" style="width:20px;"></i><span class="ml-2">Aceptar</span></a></li>';
                                    contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 rechazar_postulante" data-posicion="'+i+'"><i class="fas fa-times text-center" style="width:20px;"></i><span class="ml-2">Rechazar</span></a></li>';
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
    // VALIDACIONES
    $('#fecha').blur(function (){
        if ($(this).val() == '') {
            $(this).val(fecha2);
        }
    });
    $('#fecha_n').blur(function (){
        if ($(this).val() == '') {
            $(this).val(window.fecha_nacimiento);
        }
        $('#fecha_n').trigger('change');
    });
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

                        /*
                        if (window.actualizar === true || window.selectCiudad === true) {
                            window.actualizar = false;
                            window.selectCiudad = false;

                            let ciudadValor = '';
                            let municipioValor = '';
                            if(window.editar !== true) {
                                ciudadValor = localStorage.getItem('ciudad');
                                municipioValor = localStorage.getItem('municipio');
                                window.actualizar2 = true;
                            } else {
                                ciudadValor = dataListado.resultados[window.posicion].codigo_ciudad;
                                municipioValor = dataListado.resultados[window.posicion].codigo_municipio;
                                window.selectMunicipio = true;
                            }

                            $('#ciudad').val(ciudadValor);
                            $('#municipio').val(municipioValor);
                            $('#municipio').trigger('change');
                        }
                        */
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

                        /*
                        if (window.actualizar2 === true || window.selectMunicipio === true) {
                            window.actualizar2 = false;
                            window.selectMunicipio = false;

                            let parroquiValor = '';
                            if(window.editar !== true) {
                                parroquiValor = localStorage.getItem('parroquia')
                            } else {
                                parroquiValor = dataListado.resultados[window.posicion].codigo_parroquia;
                            }
                            $('#parroquia').val(parroquiValor);
                        }
                        */
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
                    dataListadoEmp = JSON.parse(resultados);
                    $('#nacionalidad').val(dataListadoEmp.nacionalidad);
                    $('#cedula').val(dataListadoEmp.cedula);
                    $('#nombre_1').val(dataListadoEmp.nombre1);
                    $('#nombre_2').val(dataListadoEmp.nombre2);
                    $('#apellido_1').val(dataListadoEmp.apellido1);
                    $('#apellido_2').val(dataListadoEmp.apellido2);
                    $('#sexo').val(dataListadoEmp.sexo);
                    $('#fecha_n').val(dataListadoEmp.fecha_n);
                    window.fecha_nacimiento = dataListadoEmp.fecha_n;
                    $('#fecha_n').trigger('change');
                    $('#lugar_n').val(dataListadoEmp.lugar_n);
                    $('#ocupacion').val(dataListadoEmp.codigo_ocupacion);
                    //////////////////////////////////////////////////////////
                    document.formulario.estado_civil.value = dataListadoEmp.estado_civil;
                    document.formulario.grado_instruccion.value = dataListadoEmp.nivel_instruccion;
                    if (document.formulario.grado_instruccion.value == 'SI' || document.formulario.grado_instruccion.value == 'SC')
                        $('#titulo').attr('disabled', false);
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
        $('#codigo').val(dataListado.resultados[posicion].codigo);
        $('#nombre').val(dataListado.resultados[posicion].nombre);
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        data.push({ name: 'codigo2', value: window.codigo });
        
        $.ajax({
            url : url+'controllers/c_oficio.php',
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
    $('.input-fechas').datepicker({ language: 'es' });
});