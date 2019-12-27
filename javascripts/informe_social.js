$(function () {
    let fecha = '';
    let dataOcupacion = false;
    let dataParentesco = ['Padre','Madre','Hermano','Hermana','Abuelo','Abuela','Tío','Tía','Primo','Prima','Sobrino','Sobrina'];
    let tipoEnvio = '';

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
                if (data.oficio) {
                    for (let i in data.oficio) {
                        $('#oficio').append('<option value="'+data.oficio[i].codigo+'">'+data.oficio[i].nombre+'</option>');
                    }
                } else {
                    $('#oficio').html('<option value="">No hay oficios</option>');
                }
                if (data.estado) {
                    for (let i in data.estado) {
                        $('#estado').append('<option value="'+data.estado[i].codigo+'">'+data.estado[i].nombre+'</option>');
                    }
                } else {
                    $('#estado').html('<option value="">No hay estados</option>');
                }
            } catch (error) { }
        },
        error: function (){
            console.log('error');
        }
    });

    ////// FUNCIONES DE ALGUNOS CAMPOS.
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
        else
            $('#titulo').attr('disabled', true);
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
                success: function (respuesta) {
                    $('#ciudad').empty();
                    $('#municipio').empty();
                    try {
                        let data = JSON.parse(respuesta);
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

                        if (window.actualizar === true) {
                            window.actualizar = false;
                            window.actualizar2 = true;

                            $('#ciudad').val(localStorage.getItem('ciudad'));
                            $('#municipio').val(localStorage.getItem('municipio'));
                            $('#municipio').trigger('change');
                        }
                    } catch (error) { console.log(respuesta); }
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
                success: function (respuesta) {
                    $('#parroquia').empty();
                    try {
                        let data = JSON.parse(respuesta);
                        if (data.parroquia) {
                            $('#parroquia').append('<option value="">Elija una opción</option>');
                            for(let i in data.parroquia){
                                $('#parroquia').append('<option value="'+data.parroquia[i].codigo+'">'+data.parroquia[i].nombre+'</option>');
                            }
                        } else {
                            $('#parroquia').html('<option value="">No hay parroquias</option>');
                        }

                        if (window.actualizar2 === true) {
                            window.actualizar2 = false;
                            
                            $('#parroquia').val(localStorage.getItem('parroquia'));
                        }
                    } catch (error) { console.log(respuesta); }
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
        localStorage.setItem('total_ingresos', $('#total_ingresos').val());
    });
    $('.i_egresos').keyup(function () {
        let egreso_servicios = parseInt($('#egreso_servicios').val());
        let egreso_alimentario = parseInt($('#egreso_alimentario').val());
        let egreso_educacion = parseInt($('#egreso_educacion').val());
        let egreso_vivienda = parseInt($('#egreso_vivienda').val());
        let otros_egresos = parseInt($('#otros_egresos').val());

        $('#total_egresos').val(egreso_servicios + egreso_alimentario + egreso_educacion + egreso_vivienda + otros_egresos);
        localStorage.setItem('total_egresos', $('#total_egresos').val());
    });

    // MOSTRAR EL FORMULARIO PARA REGISTRAR UN NUEVO APRENDIZ.
    $('#show_form').click(function (){
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#form_title').html('Registrar');
        tipoEnvio = 'Registrar';
        /////////////////////
        while (vista != 1)
            $('#retroceder_form').trigger('click');
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

    // MOSTRAR LA TABLA CON TODA LA LISTA DE LOS APRENDICES REGISTRADOS.
    $('#show_table').click(function (){
        $('#info_table').show(400);
        $('#gestion_form').hide(400);
        /////////////////////
        localStorage.removeItem('confirm_data');
        $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
        $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
    });

    let vista = 1;
    // RETROCEDER LA VISTA DEL FORMULARIO PARA REGISTRAR NUEVO APRENDIZ.
    $('#retroceder_form').click(function (){
        $('#avanzar_form').attr('disabled',false);

        if (vista == 2){
            $('#datos_extras').hide(300);
            $('#datos_aprendiz').show(300);
        } else if (vista == 3) {
            $('#datos_vivienda').hide(300);
            $('#datos_extras').show(300);
        } else if (vista == 4) {
            $('#datos_familiares').hide(300);
            $('#datos_vivienda').show(300);
        } else if (vista == 5){
            $('#datos_familiares').show(300);
            $('#datos_ingresos').hide(300);
        } else if (vista == 6){
            $('#datos_ingresos').show(300);
            $('#datos_trabajadora_social').hide(300);
        }

        if (vista != 1)
            vista--;

        if (vista == 1)
            $('#retroceder_form').attr('disabled',true);
    });

    // AVANZAR LA VISTA DEL FORMULARIO PARA REGISTRAR NUEVO APRENDIZ.
    $('#avanzar_form').click(function avanzarForm (){
        $('#retroceder_form').attr('disabled', false);

        if (vista == 1){
            $('#datos_aprendiz').hide(300);
            $('#datos_extras').show(300);
        } else if (vista == 2){
            $('#datos_extras').hide(300);
            $('#datos_vivienda').show(300);
        } else if (vista == 3){
            $('#datos_vivienda').hide(300);
            $('#datos_familiares').show(300);
        } else if (vista == 4){
            $('#datos_familiares').hide(300);
            $('#datos_ingresos').show(300);
        } else if (vista == 5){
            $('#datos_ingresos').hide(300);
            $('#datos_trabajadora_social').show(300);
        }
        
        if (vista != 6)
            vista++;
        
        if (vista == 6)
            $('#avanzar_form').attr('disabled', true);
    });

    // FUNCION PARA AGREGAR NUEVA FILAS EN LA TABLA DE FAMILIARSE DEL APRENDIZ.
    $('#agregar_familiar').click(function (){
        let cantidad = $('#tabla_datos_familiares tbody tr').length + 1;
        if (cantidad <= 10) {
            let contenido = '<tr>';
            contenido += '<td class="py-2 px-0 text-center">'+cantidad+'</td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="nombre_familiar[]" class="form-control form-control-sm"></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="date" name="fecha_familiar[]" class="form-control form-control-sm calcular_edad" style="width: 128px;" data-posicion="'+cantidad+'"></td>';
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" id="edad_'+cantidad+'" class="form-control form-control-sm text-center" value="0" style="width: 56px;" readonly="true"></td>';
            
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="sexo_familiar[]" class="custom-select custom-select-sm" style="width: 56px;">';
            contenido += '<option value=""></option><option value="M">M</option><option value="F">F</option><option value="I">I</option>';
            contenido += '</select></td>';
            
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="parentesco_familiar" class="custom-select custom-select-sm" style="width: 136px;">';
            contenido += '<option value="">Elija una opción</option>';
            for (let i in dataParentesco) {
                contenido += '<option value="'+i+'">'+dataParentesco[i]+'</option>';
            }
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="ocupacion_familiar" class="custom-select custom-select-sm" style="width: 140px;">';
            if (dataOcupacion) {
                contenido += '<option value="">Elija una opción</option>';
                for (let i in dataOcupacion) {
                    contenido += '<option value="'+dataOcupacion[i].codigo+'">'+dataOcupacion[i].nombre+'</option>';
                }
            }
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="trabaja_familiar" class="custom-select custom-select-sm trabajando" style="width: 61px;" data-posicion="'+cantidad+'">';
            contenido += '<option value=""></option><option value="S">S</option><option value="N">N</option>';
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="ingresos_familiar[]" id="ingresos_'+cantidad+'" class="form-control form-control-sm text-right" style="width: 96px;" disabled="true"></td>';
            contenido += '<td class="align-middle py-0 px-0 text-center"><div class="custom-control custom-radio d-inline-block" style="width: 0px;"><input type="radio" class="custom-control-input" id="responsable_apre_'+cantidad+'" name="responsable_apre" value="'+cantidad+'"><label class="custom-control-label" for="responsable_apre_'+cantidad+'"></label></div></td>';
            contenido += '<td class="py-1 px-0"><button type="button" class="btn btn-sm btn-danger delete-row"><i class="fas fa-times"></i></button></td>';
            contenido += '</tr>';

            $('#tabla_datos_familiares tbody').append(contenido);
            $($('.calcular_edad')[$('.calcular_edad').length - 1]).change(calcularEdadF);
            $($('.trabajando')[$('.trabajando').length - 1]).change(habilitarIngresos);
            $($('.delete-row')[$('.delete-row').length - 1]).click(eliminarFila);
        } else {
            alert('Solo es permitido un maximo de 10 filas.');
        }
    });

    // FUNCION PARA CALCULAR LA EDAD CUANDO SE INTRODUZCA LA FECHA DE NACIMIENTO DE LOS FAMILIARES.
    function calcularEdadF(){
        let idEdad  = '#edad_'+$(this).attr('data-posicion');
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

    function habilitarIngresos(){
        let idIngresos = '#ingresos_' + $(this).attr('data-posicion');
        $(idIngresos).attr('disabled',true);

        if ($(this).val() == 'S') {
            $(idIngresos).attr('disabled',false);
        } else {
            $(idIngresos).val('');
        }
    }

    // FUNCION PARA ELIMINAR FILAS QUE YA NO SON NECESARIAS.
    function eliminarFila(){
        $(this).closest('tr').remove();

        let cont = 1;
        $('#tabla_datos_familiares tbody tr').each(function(){
            $($(this).children('td')[0]).html(cont);
            $($($(this).children('td')[2]).children('input')[0]).attr('data-posicion', cont);
            $($($(this).children('td')[3]).children('input')[0]).attr('id', 'edad_'+cont);

            $($($($(this).children('td')[9]).children('div')[0]).children('input')[0]).attr('id', 'responsable_apre_'+cont);
            $($($($(this).children('td')[9]).children('div')[0]).children('input')[0]).attr('value', cont);
            $($($($(this).children('td')[9]).children('div')[0]).children('label')[0]).attr('for', 'responsable_apre_'+cont);

            $($($(this).children('td')[7]).children('select')[0]).attr('data-posicion', cont);
            $($($(this).children('td')[8]).children('input')[0]).attr('id', 'ingresos_'+cont);

            cont++;
        });
    };

    function limpiarFormulario(){
        document.formulario.reset();
        $('#fecha').val(fecha);
        $('#tabla_datos_familiares tbody').html('');
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

        console.log(data);
        $.ajax({
            url : url+'controllers/c_informe_social.php',
            type: 'POST',
            data: data,
            success: function (respuesta) {
                alert(respuesta);
                // if (respuesta == 'Registro exitoso')
                //     $('#show_table').trigger('click');
            },
            error: function () {
                console.log('error');
            }
        });
    });

    $('.localStorage').keyup(guardarLocalStorage);
    $('.localStorage').change(guardarLocalStorage);
    $('.localStorage-radio').click(guardarLocalStorage);
    function guardarLocalStorage() {
        localStorage.setItem('confirm_data', true);
        localStorage.setItem($(this).attr('name'), $(this).val());
    }
});