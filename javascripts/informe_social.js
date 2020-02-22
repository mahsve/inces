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
    let dataOcupacion   = false;// VARIABLE PARA GUARDAR LAS OCUPACIONES Y AGREGARLAS A LA TABLA FAMILIA.
    let dataParentesco  = ['Padre','Madre','Hermano','Hermana','Abuelo','Abuela','Tío','Tía','Primo','Prima','Sobrino','Sobrina'];
    let dataTurno       = {'M': 'Matutino', 'V': 'Vespertino'};
    let tipoEnvio       = '';   // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let maxFamiliares   = 10;   // MAXIMO DE FAMILIARES EN LA TABLA DE FAMILIARES DEL APRENDIZ.
    let dataListado     = [];   // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    /////////////////////////////////////////////////////////////////////
    function restablecerN () {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado(){
        $('#listado_aprendices tbody').html('<tr><td colspan="10" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>');
        $("#paginacion").html('<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>');
        $.ajax({
            url : url+'controllers/c_informe_social.php',
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
                    $('#listado_aprendices tbody').empty();
                    dataListado = JSON.parse(resultados);
                    if (dataListado.resultados) {
                        for (var i in dataListado.resultados) {
                            let contenido = '';
                            contenido += '<tr class="border-bottom text-secondary">';
                            contenido += '<td class="text-right py-2 px-1">'+dataListado.resultados[i].numero+'</td>';

                            let yearR   = dataListado.resultados[i].fecha.substr(0,4);
                            let monthR  = dataListado.resultados[i].fecha.substr(5,2);
                            let dayR    = dataListado.resultados[i].fecha.substr(8,2);

                            contenido += '<td class="py-2 px-1">'+dayR+'-'+monthR+'-'+yearR+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].nacionalidad+'-'+dataListado.resultados[i].cedula+'</td>';
                            
                            let nombre_completo = dataListado.resultados[i].nombre1;
                            if (dataListado.resultados[i].nombre2 != null)
                                nombre_completo += ' '+dataListado.resultados[i].nombre2.substr(0,1)+'.';
                            nombre_completo += ' '+dataListado.resultados[i].apellido1;
                            if (dataListado.resultados[i].apellido2 != null)
                                nombre_completo += ' '+dataListado.resultados[i].apellido2.substr(0,1)+'.';
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

                            let estatus = '';
                            if (dataListado.resultados[i].estatus_informe == 'E') {
                                estatus = '<span class="badge badge-info"><i class="fas fa-clock mr-1"></i>En espera</span>';
                            } else if (dataListado.resultados[i].estatus_informe == 'A') {
                                estatus = '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aceptado</span>';
                            } else if (dataListado.resultados[i].estatus_informe == 'R') {
                                estatus = '<span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Rechazado</span>';
                            }

                            contenido += '<td class="text-center py-2 px-1">'+day+'-'+month+'-'+year+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+edad+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataListado.resultados[i].oficio+'</td>';
                            contenido += '<td class="py-2 px-1">'+dataTurno[dataListado.resultados[i].turno]+'</td>';
                            contenido += '<td class="text-center py-2 px-1">'+estatus+'</td>';
                            contenido += '<td class="py-1 px-1">';
                            if (permisos.modificar == 1)
                                contenido += '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="'+i+'"><i class="fas fa-pencil-alt"></i></button>';
                            contenido += '<div class="dropdown d-inline-block"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v px-1"></i></button>';
                            contenido += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                            if (permisos.act_desc == 1) {
                                if (dataListado.resultados[i].estatus_informe == 'E') {
                                    contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 aceptar_postulante" data-posicion="'+i+'"><i class="fas fa-check text-center" style="width:20px;"></i><span class="ml-2">Aceptar</span></a></li>';
                                    contenido += '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1 rechazar_postulante" data-posicion="'+i+'"><i class="fas fa-times text-center" style="width:20px;"></i><span class="ml-2">Rechazar</span></a></li>';
                                }
                            }
                            contenido += '<li class="dropdown-item p-0"><a href="'+url+'controllers/pdf/r_informe_social?numero='+dataListado.resultados[i].numero+'" target="_blank" class="d-inline-block w-100 p-1"><i class="fas fa-print text-center" style="width:20px;"></i><span class="ml-2">Imprimir</span></a></li>';
                            contenido += '</div></div></td></tr>';
                            $('#listado_aprendices tbody').append(contenido);
                        }
                        $('.editar_registro').click(editarInforme);

                        $('.aceptar_postulante').click(aceptarPostulante);
                        $('.rechazar_postulante').click(rechazarPostulante);
                    } else {
                        $('#listado_aprendices tbody').append('<tr><td colspan="10" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay informes registrados</td></tr>');
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

        if(window.editar !== true)
            localStorage.setItem('titulo', $('#titulo').val());
    });
    $('#estado').change(function () {
        if (window.actualizar !== true) {
            localStorage.removeItem('ciudad');
            localStorage.removeItem('municipio');
            localStorage.removeItem('parroquia');
        }

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
                    } catch (error) { console.log(resultados); }
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
        if (window.actualizar2 !== true) {
            localStorage.removeItem('parroquia');
        }

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
                    } catch (error) { console.log(resultados); }
                },
                error: function () {
                    console.log('error');
                }
            });
        } else {
            window.actualizar2 = false;
            $('#parroquia').html('<option value="">Elija un municipio</option>');
        }
    });
    $('.campos_ingresos').click(function () {
        if ($(this).val() == 0)
            $(this).val('');
    });
    $('.campos_ingresos').blur(function () {
        if ($(this).val() == '')
            $(this).val(0);
    });
    $('.i_ingresos').keyup(function () {
        let ingreso_pension = parseInt($('#ingreso_pension').val());
        let ingreso_seguro = parseInt($('#ingreso_seguro').val());
        let ingreso_pension_otras = parseInt($('#ingreso_pension_otras').val());
        let ingreso_sueldo = parseInt($('#ingreso_sueldo').val());
        let otros_ingresos = parseInt($('#otros_ingresos').val());

        $('#total_ingresos').val(ingreso_pension + ingreso_seguro + ingreso_pension_otras + ingreso_sueldo + otros_ingresos);
        if(window.editar !== true)
            localStorage.setItem('total_ingresos', $('#total_ingresos').val());
    });
    $('.i_egresos').keyup(function () {
        let egreso_servicios = parseInt($('#egreso_servicios').val());
        let egreso_alimentario = parseInt($('#egreso_alimentario').val());
        let egreso_educacion = parseInt($('#egreso_educacion').val());
        let egreso_vivienda = parseInt($('#egreso_vivienda').val());
        let otros_egresos = parseInt($('#otros_egresos').val());

        $('#total_egresos').val(egreso_servicios + egreso_alimentario + egreso_educacion + egreso_vivienda + otros_egresos);
        if(window.editar !== true)
            localStorage.setItem('total_egresos', $('#total_egresos').val());
    });
    /////////////////////////////////////////////////////////////////////
    $('#show_form').click(function (){
        $('#form_title').html('Registrar');
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#carga_espera').hide();
        tipoEnvio = 'Registrar';
        /////////////////////
        limpiarFormulario();
        if (localStorage.getItem('confirm_data')){
            setTimeout(() => {
                if (confirm('Hay datos sin guardar, ¿Quieres seguir editandolos?')) {
                    $('.localStorage').each(function (){
                        let valor = localStorage.getItem($(this).attr('id'));
                        if (valor != '' && valor != null && valor != undefined)
                            $(this).val(localStorage.getItem($(this).attr('id')));
                    });
                    $('.localStorage-radio').each(function (){
                        if ($(this).val() == localStorage.getItem($(this).attr('name')))
                            $(this).prop('checked','checked');
                    });
                    let grado_instruccion = localStorage.getItem('grado_instruccion');
                    if (grado_instruccion == 'SI' || grado_instruccion == 'SC')
                        $('#titulo').attr('disabled', false);

                    for (let index = 1; index <= maxFamiliares; index++) {
                        if (localStorage.getItem('filaFamilia'+index)) {
                            window.actualizar3 = true;
                            $('#agregar_familiar').trigger('click');

                            let arregloFamilia = JSON.parse(localStorage.getItem('filaFamilia'+index));
                            for(var posicion in arregloFamilia) {
                                if (arregloFamilia[posicion] != '')
                                    $('#'+posicion+index).val(arregloFamilia[posicion]);
                            }
                        }
                    }
                    $('.trabajando').trigger('change');
                    if (localStorage.getItem('responsable_apre')) 
                        document.formulario.responsable_apre.value = localStorage.getItem('responsable_apre');

                    window.actualizar3 = false;

                    window.actualizar = true;
                    $('#estado').trigger('change');
                    $('#fecha_n').trigger('change');
                } else {
                    localStorage.removeItem('confirm_data');
                    $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
                    $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
                }
            }, 500);
        }
    });
    $('#show_table').click(function (){
        $('#info_table').show(400);
        $('#gestion_form').hide(400);
        /////////////////////
        window.editar = false;
        /////////////////////
        $('#pills-datos-ciudadano-tab').tab('show');
        /////////////////////
        localStorage.removeItem('confirm_data');
        $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
        $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
        
        for (let index = 1; index <= maxFamiliares; index++) {
            localStorage.removeItem('filaFamilia'+index);
        }
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA AGREGAR NUEVA FILAS EN LA TABLA DE FAMILIARSE DEL APRENDIZ.
    $('#agregar_familiar').click(function (){
        if (window.tabla) {
            window.tabla = false;
            $('#tabla_datos_familiares tbody').empty();
        }

        let cantidad = $('#tabla_datos_familiares tbody tr').length + 1;
        if (cantidad <= 10) {
            let contenido = '';
            contenido += '<tr data-posicion="'+cantidad+'">';
            contenido += '<td class="py-2 px-0 text-center">'+cantidad+'</td>';
            contenido += '<input type="hidden" name="id_familiar[]" id="id_familiar'+cantidad+'">';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="nombre_familiar1[]" id="nombre_familiar1'+cantidad+'" class="form-control form-control-sm storageFamilia"></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="nombre_familiar2[]" id="nombre_familiar2'+cantidad+'" class="form-control form-control-sm storageFamilia"></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="apellido_familiar1[]" id="apellido_familiar1'+cantidad+'" class="form-control form-control-sm storageFamilia"></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="apellido_familiar2[]" id="apellido_familiar2'+cantidad+'" class="form-control form-control-sm storageFamilia"></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="fecha_familiar[]" id="fecha_familiar'+cantidad+'" class="form-control form-control-sm storageFamilia fecha_familiar calcular_edad" style="background: #ffffff;" data-date-format="yyyy-mm-dd" readonly></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="edad_familiar[]" id="edad_familiar'+cantidad+'" class="form-control form-control-sm text-center storageFamilia" value="0" style="width: 56px;" readonly="true"></td>';
            
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="sexo_familiar[]" id="sexo_familiar'+cantidad+'" class="custom-select custom-select-sm storageFamilia" style="width: 56px;">';
            contenido += '<option value=""></option><option value="M">M</option><option value="F">F</option><option value="I">I</option>';
            contenido += '</select></td>';
            
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="parentesco_familiar[]" id="parentesco_familiar'+cantidad+'" class="custom-select custom-select-sm storageFamilia">';
            contenido += '<option value="">Opción</option>';
            for (let i in dataParentesco) {
                contenido += '<option value="'+i+'">'+dataParentesco[i]+'</option>';
            }
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="ocupacion_familiar[]" id="ocupacion_familiar'+cantidad+'" class="custom-select custom-select-sm storageFamilia" style="width: 140px;">';
            if (dataOcupacion) {
                contenido += '<option value="">Opción</option>';
                for (let i in dataOcupacion) {
                    contenido += '<option value="'+dataOcupacion[i].codigo+'">'+dataOcupacion[i].nombre+'</option>';
                }
            }
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="trabaja_familiar[]" id="trabaja_familiar'+cantidad+'" class="custom-select custom-select-sm trabajando storageFamilia" style="width: 61px;">';
            contenido += '<option value=""></option><option value="S">S</option><option value="N">N</option>';
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="ingresos_familiar[]" id="ingresos_familiar'+cantidad+'" class="form-control form-control-sm text-right storageFamilia" readonly="true"></td>';
            contenido += '<td class="align-middle py-0 px-0 text-center"><div class="custom-control custom-radio d-inline-block" style="width: 0px;"><input type="radio" class="custom-control-input localStorage-radio" id="responsable_apre_'+cantidad+'" name="responsable_apre" value="'+cantidad+'"><label class="custom-control-label" for="responsable_apre_'+cantidad+'"></label></div></td>';
            contenido += '<td class="py-1 px-0"><button type="button" class="btn btn-sm btn-danger delete-row"><i class="fas fa-times"></i></button></td>';
            contenido += '</tr>';

            $('#tabla_datos_familiares tbody').append(contenido);
            $($('.calcular_edad')[$('.calcular_edad').length - 1]).change(calcularEdadF);
            $($('.trabajando')[$('.trabajando').length - 1]).change(habilitarIngresos);
            $($('.delete-row')[$('.delete-row').length - 1]).click(eliminarFila);
            $($('.fecha_familiar')[$('.fecha_familiar').length - 1]).datepicker();

            $('tr[data-posicion="'+cantidad+'"] .storageFamilia').change(localStorageFamiliares);
            $( $('tr[data-posicion="'+cantidad+'"] .localStorage-radio') ).click(guardarLocalStorage);

            if (window.actualizar3 !== true) {
                if(window.editar !== true){
                    let localFamilia = {nombre_familiar1: '', apellido_familiar2: '', apellido_familiar1: '', nombre_familiar2: '', fecha_familiar: '', edad_familiar: 0, sexo_familiar: '', parentesco_familiar: '', ocupacion_familiar: '', trabaja_familiar: '', ingresos_familiar: ''};
                    localStorage.setItem('filaFamilia'+cantidad, JSON.stringify(localFamilia));
                }
            }
        } else {
            alert('Solo es permitido un maximo de 10 filas.');
        }
    });
    // FUNCION PARA GUARDAR LOS DATOS DE LOS FAMILIARES EN EL LOCALSTORAGE
    function localStorageFamiliares() {
        if(window.editar !== true) {
            let nameInput = $(this).attr('name').replace('[]','');
            let position = $(this).closest('tr').attr('data-posicion');

            localStorage.setItem('confirm_data', true);
            let arregloFamilia = JSON.parse(localStorage.getItem('filaFamilia'+position));
            arregloFamilia[nameInput] = $(this).val();
            arregloFamilia['edad_familiar'] = parseInt($('#edad_familiar'+position).val());
            localStorage.setItem('filaFamilia'+position, JSON.stringify(arregloFamilia));
        }
    }
    // FUNCION PARA CALCULAR LA EDAD CUANDO SE INTRODUZCA LA FECHA DE NACIMIENTO DE LOS FAMILIARES.
    function calcularEdadF(){
        let idEdad  = '#edad_familiar' + $(this).closest('tr').attr('data-posicion');
        
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
        $(idEdad).val(edad);
    }
    // ACTIVAR EL CAMPO DE INGRESOS EN LA TABLA FAMILIAR SI EL MIEMBRO ESTA TRABAJANDO.
    function habilitarIngresos(){
        let posicion = $(this).closest('tr').attr('data-posicion');
        let idIngresos = '#ingresos_familiar' + posicion;
        $(idIngresos).attr('readonly',true);

        if ($(this).val() == 'S') {
            $(idIngresos).attr('readonly',false);
        } else {
            $(idIngresos).val('');

            ////////////////////
            if(window.editar !== true) {
                let arregloFamilia = JSON.parse(localStorage.getItem('filaFamilia'+posicion));
                arregloFamilia['ingresos_familiar'] = '';
                localStorage.setItem('filaFamilia'+posicion, JSON.stringify(arregloFamilia));
            }
        }
    }
    // FUNCION PARA ELIMINAR FILAS QUE YA NO SON NECESARIAS.
    function eliminarFila(){
        let position = $(this).closest('tr').attr('data-posicion');
        ////////////////////
        if(window.editar !== true)
            localStorage.removeItem('filaFamilia'+position);
        else {
            let valor_id = $('#id_familiar'+position).val();
            if (valor_id != '' && valor_id != undefined)
                window.eliminarFamiliar.push(valor_id);
        }
        ////////////////////
        $(this).closest('tr').remove();
        ////////////////////
        let arregloRespaldo = [];
        if(window.editar !== true){
            for (let index = 1; index <= maxFamiliares; index++) {
                if (localStorage.getItem('filaFamilia'+index)) {
                    arregloRespaldo.push(JSON.parse(localStorage.getItem('filaFamilia'+index)));
                    localStorage.removeItem('filaFamilia'+index);
                }
            }
        }
        ////////////////////
        let cont = 1;
        if ($('#tabla_datos_familiares tbody tr').length > 0) {
            $('#tabla_datos_familiares tbody tr').each(function(){
                $(this).attr('data-posicion', cont);
    
                $($(this).children('td')[0]).html(cont);
                $($($(this)).children('input')[0]).attr('id', 'id_familiar'+cont);
                $($($(this).children('td')[1]).children('input')[0]).attr('id', 'nombre_familiar1'+cont);
                $($($(this).children('td')[2]).children('input')[0]).attr('id', 'nombre_familiar2'+cont);
                $($($(this).children('td')[3]).children('input')[0]).attr('id', 'apellido_familiar1'+cont);
                $($($(this).children('td')[4]).children('input')[0]).attr('id', 'apellido_familiar2'+cont);

                $($($(this).children('td')[5]).children('input')[0]).attr('id', 'fecha_familiar'+cont);
                $($($(this).children('td')[6]).children('input')[0]).attr('id', 'edad_familiar'+cont);
                $($($(this).children('td')[7]).children('select')[0]).attr('id', 'sexo_familiar'+cont);
                $($($(this).children('td')[8]).children('select')[0]).attr('id', 'parentesco_familiar'+cont);
                $($($(this).children('td')[9]).children('select')[0]).attr('id', 'ocupacion_familiar'+cont);
                $($($(this).children('td')[10]).children('select')[0]).attr('id', 'trabaja_familiar'+cont);
                $($($(this).children('td')[11]).children('input')[0]).attr('id', 'ingresos_familiar'+cont);
    
                $($($($(this).children('td')[12]).children('div')[0]).children('input')[0]).attr('id', 'responsable_apre_'+cont);
                $($($($(this).children('td')[12]).children('div')[0]).children('input')[0]).attr('value', cont);
                $($($($(this).children('td')[12]).children('div')[0]).children('label')[0]).attr('for', 'responsable_apre_'+cont);
    
                if(window.editar !== true)
                    localStorage.setItem('filaFamilia'+cont, JSON.stringify(arregloRespaldo[cont-1]));
                cont++;
            });
        } else {
            window.tabla = true;
            $('#tabla_datos_familiares tbody').html('<tr><td colspan="14" class="text-secondary text-center border-bottom p-2">Sin familiares agregados<i class="fas fa-user-times ml-3"></i></td></tr>');
        }
    };
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarInforme() {
        let posicion = $(this).attr('data-posicion');
        window.posicion = posicion;
        if (localStorage.getItem('confirm_data')){
            if (confirm('Hay datos que no se han guardado, si prosigues se perderán. ¿Quieres proseguir?')) {
                editarF();
                /////////////////////
                localStorage.removeItem('confirm_data');
                $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
                $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
                
                for (let index = 1; index <= maxFamiliares; index++) {
                    localStorage.removeItem('filaFamilia'+index);
                }
            }
        } else {
            editarF();
        }

        function editarF() {
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
                url : url+'controllers/c_informe_social.php',
                type: 'POST',
                data: {
                    opcion: 'Consultar determinado',
                    informe: dataListado.resultados[posicion].numero,
                    nacionalidad: dataListado.resultados[posicion].nacionalidad,
                    cedula: dataListado.resultados[posicion].cedula
                },
                success: function (resultados){
                    try {
                        let data = JSON.parse(resultados);

                        window.informe_social = dataListado.resultados[posicion].numero;
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
                        $('#oficio').val(dataListado.resultados[posicion].codigo_oficio);
                        $('#turno').val(dataListado.resultados[posicion].turno);
                        // SEGUNDA PARTE.
                        $('#estado').val(dataListado.resultados[posicion].codigo_estado);
                        window.selectCiudad = true;
                        $('#estado').trigger('change');
                        $('#area').val(data.vivienda.tipo_area);
                        $('#direccion').val(dataListado.resultados[posicion].direccion);
                        $('#punto_referencia').val(data.vivienda.punto_referencia);
                        // TERCERA PARTE.
                        document.formulario.tipo_vivienda.value = data.vivienda.tipo_vivienda;
                        document.formulario.tenencia_vivienda.value = data.vivienda.tenencia_vivienda;
                        document.formulario.tipo_agua.value = data.vivienda.agua;
                        document.formulario.tipo_electricidad.value = data.vivienda.electricidad;
                        document.formulario.tipo_excreta.value = data.vivienda.excretas;
                        document.formulario.tipo_basura.value = data.vivienda.basura;
                        $('#otros').val(data.vivienda.otros);
                        /////////////////////
                        $('#techo').val(data.vivienda.techo);
                        $('#pared').val(data.vivienda.paredes);
                        $('#piso').val(data.vivienda.piso);
                        $('#via_acceso').val(data.vivienda.via_acceso);
                        $('#sala').val(data.vivienda.sala);
                        $('#comedor').val(data.vivienda.comedor);
                        $('#cocina').val(data.vivienda.cocina);
                        $('#bano').val(data.vivienda.banos);
                        $('#dormitorio').val(data.vivienda.n_dormitorios);
                        // CUARTA PARTE.
                        window.eliminarFamiliar = [];
                        dataFamiliares = data.familiares;
                        for (let index = 0; index < data.familiares.length; index++) {
                            $('#agregar_familiar').trigger('click');
                            $('#id_familiar'+(index+1)).val(data.familiares[index].id_familiar);
                            $('#nombre_familiar1'+(index+1)).val(data.familiares[index].nombre1);
                            $('#nombre_familiar2'+(index+1)).val(data.familiares[index].nombre2);
                            $('#apellido_familiar1'+(index+1)).val(data.familiares[index].apellido1);
                            $('#apellido_familiar2'+(index+1)).val(data.familiares[index].apellido2);
                            $('#fecha_familiar'+(index+1)).val(data.familiares[index].fecha_n);
                            $('#sexo_familiar'+(index+1)).val(data.familiares[index].sexo);
                            $('#parentesco_familiar'+(index+1)).val(data.familiares[index].parentesco);
                            $('#ocupacion_familiar'+(index+1)).val(data.familiares[index].codigo_ocupacion);
                            $('#trabaja_familiar'+(index+1)).val(data.familiares[index].trabaja);
                            $('#ingresos_familiar'+(index+1)).val(data.familiares[index].ingresos);
                        }
                        $('.localStorage-radio').each(function () {
                            if ($(this).val() == dataListado.resultados[posicion].representante) {
                                $(this).prop('checked', true);
                            }
                        });

                        $('.calcular_edad').trigger('change');
                        $('.trabajando').trigger('change');
                        // QUINTA PARTE.
                        for (let i_dine in data.ingresos) {
                            $('#'+data.ingresos[i_dine].descripcion).val(data.ingresos[i_dine].cantidad);
                        }
                        $('.i_ingresos').trigger('keyup');
                        $('.i_egresos').trigger('keyup');
                        // SEXTA PARTE.
                        $('#condicion_vivienda').val(dataListado.resultados[posicion].condicion_vivienda);
                        $('#caracteristicas_generales').val(dataListado.resultados[posicion].caracteristicas_generales);
                        $('#diagnostico_social').val(dataListado.resultados[posicion].diagnostico_social);
                        $('#diagnostico_preliminar').val(dataListado.resultados[posicion].diagnostico_preliminar);
                        $('#conclusiones').val(dataListado.resultados[posicion].conclusiones);
                        document.formulario.enfermos.value = dataListado.resultados[posicion].enfermos;

                        $('#carga_espera').hide(400);
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function (){
                    console.log('error');
                }
            });
        }
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        data.push({ name: 'estado_civil', value: document.formulario.estado_civil.value });
        data.push({ name: 'grado_instruccion', value: document.formulario.grado_instruccion.value });
        ///////////////////
        data.push({ name: 'tipo_vivienda', value: document.formulario.tipo_vivienda.value });
        data.push({ name: 'tenencia_vivienda', value: document.formulario.tenencia_vivienda.value });
        data.push({ name: 'tipo_agua', value: document.formulario.tipo_agua.value });
        data.push({ name: 'tipo_electricidad', value: document.formulario.tipo_electricidad.value });
        data.push({ name: 'tipo_excreta', value: document.formulario.tipo_excreta.value });
        data.push({ name: 'tipo_basura', value: document.formulario.tipo_basura.value });
        ///////////////////
        data.push({ name: 'enfermos', value: document.formulario.enfermos.value });
        ///////////////////
        data.push({ name: 'informe_social', value: window.informe_social });
        data.push({ name: 'nacionalidad_v', value: window.nacionalidad });
        data.push({ name: 'cedula_v', value: window.cedula });
        data.push({ name: 'eliminar_f', value: JSON.stringify(window.eliminarFamiliar) });

        $.ajax({
            url : url+'controllers/c_informe_social.php',
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
    function aceptarPostulante (e) {
        e.preventDefault();

        // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
        let posicion = $(this).attr('data-posicion');
        let numero = dataListado.resultados[posicion].numero;

        localStorage.setItem('numero_ficha', numero);
        location.href = url+'intranet/aprendiz'
    }
    function rechazarPostulante (e) {
        e.preventDefault();

        // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
        let posicion = $(this).attr('data-posicion');
        let numero = dataListado.resultados[posicion].numero;
        let estatus = 'R';
        
        $.ajax({
            url : url+'controllers/c_informe_social.php',
            type: 'POST',
            data: {
                opcion: 'Estatus',
                numero: numero,
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
    // FUNCION PARA GUARDAR LOS DATOS DEL APRENDIZ EN LOCALSTORAGE.
    $('.localStorage').keyup(guardarLocalStorage);
    $('.localStorage').change(guardarLocalStorage);
    $('.localStorage-radio').click(guardarLocalStorage);
    function guardarLocalStorage() {
        if(window.editar !== true){
            localStorage.setItem('confirm_data', true);
            localStorage.setItem($(this).attr('name'), $(this).val());
        }
    }
    // FUNCION PARA LIMPIAR EL FORMULARIO DE LOS DATOS ANTERIORES.
    function limpiarFormulario(){
        document.formulario.reset();
        $('#fecha').val(fecha);
        window.tabla = true;
        $('#tabla_datos_familiares tbody').html('<tr><td colspan="14" class="text-secondary text-center border-bottom p-2">Sin familiares agregados<i class="fas fa-user-times ml-3"></i></td></tr>');
        //////////
        $('#edad').val(0);
        $('#titulo').attr('disabled', true);
        //////////
        $('#ciudad').html('<option value="">Elija un estado</option>');
        $('#municipio').html('<option value="">Elija un estado</option>');
        $('#parroquia').html('<option value="">Elija un municipio</option>');
        //////////
        $('.campos_ingresos').val(0);
        $('.campos_ingresos_0').val(0);
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos()
    {
        $.ajax({
            url : url+'controllers/c_informe_social.php',
            type: 'POST',
            data: { opcion: 'Traer datos' },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);
                    fecha = data.fecha;
                    if (data.ocupacion) {
                        for (let i in data.ocupacion) {
                            $('#ocupacion').append('<option value="'+data.ocupacion[i].codigo+'">'+data.ocupacion[i].nombre+'</option>');
                        }
                        dataOcupacion = data.ocupacion;
                    } else {
                        $('#ocupacion').html('<option value="">No hay ocupaciones</option>');
                    }

                    $('#radios_oficios').empty();
                    if (data.oficio) {
                        let contenido_oficio = '';
                        contenido_oficio += '<div class="custom-control custom-radio">';
                        contenido_oficio += '<input type="radio" id="oficio_asd_0" name="buscar_oficio" class="custom-control-input" value="" checked>';
                        contenido_oficio += '<label class="custom-control-label" for="oficio_asd_0">Todos</label>';
                        contenido_oficio += '</div>';

                        $('#radios_oficios').append(contenido_oficio);
                        for (let i in data.oficio) {
                            $('#oficio').append('<option value="'+data.oficio[i].codigo+'">'+data.oficio[i].nombre+'</option>');
                        
                            let contenido_oficio = '';
                            contenido_oficio += '<div class="custom-control custom-radio">';
                            contenido_oficio += '<input type="radio" id="oficio_asd_'+data.oficio[i].codigo+'" name="buscar_oficio" class="custom-control-input" value="'+data.oficio[i].codigo+'">';
                            contenido_oficio += '<label class="custom-control-label" for="oficio_asd_'+data.oficio[i].codigo+'">'+data.oficio[i].nombre+'</label>';
                            contenido_oficio += '</div>';
                            $('#radios_oficios').append(contenido_oficio);
                        }
                    } else {
                        $('#oficio').html('<option value="">No hay oficios</option>');

                        let contenido_oficio = '';
                        contenido_oficio += '<div class="custom-control custom-radio">';
                        contenido_oficio += '<input type="radio" id="oficio_asd_0" name="buscar_oficio" class="custom-control-input" value="" checked>';
                        contenido_oficio += '<label class="custom-control-label" for="oficio_asd_0">Todos</label>';
                        contenido_oficio += '</div>';
                        $('#radios_oficios').append(contenido_oficio);
                    }
                    if (data.estado) {
                        for (let i in data.estado) {
                            $('#estado').append('<option value="'+data.estado[i].codigo+'">'+data.estado[i].nombre+'</option>');
                        }
                    } else {
                        $('#estado').html('<option value="">No hay estados</option>');
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

        $('#radios_turnos').empty();

        let contenido_oficio = '';
        contenido_oficio += '<div class="custom-control custom-radio">';
        contenido_oficio += '<input type="radio" id="turno_asd_0" name="buscar_turno" class="custom-control-input" value="" checked>';
        contenido_oficio += '<label class="custom-control-label" for="turno_asd_0">Todos</label>';
        contenido_oficio += '</div>';

        $('#radios_turnos').append(contenido_oficio);
        for (let i in dataTurno) {
            let contenido_oficio = '';
            contenido_oficio += '<div class="custom-control custom-radio">';
            contenido_oficio += '<input type="radio" id="turno_asd_'+i+'" name="buscar_turno" class="custom-control-input" value="">';
            contenido_oficio += '<label class="custom-control-label" for="turno_asd_'+i+'">'+dataTurno[i]+'</label>';
            contenido_oficio += '</div>';

            $('#radios_turnos').append(contenido_oficio);
        }
    }
    llamarDatos();
    $('#fecha_n').datepicker();
});