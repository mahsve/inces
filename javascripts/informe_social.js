$(function () {
    function limpiarFormulario(){
        document.formulario.reset();
        $('#fecha').val(fecha);
        $('#tabla_datos_familiares tbody').html('');
    }

    // FUNCION PARA CALCULAR LA EDAD CUANDO SE INTRODUZCA LA FECHA DE NACIMIENTO DEL APRENDIZ.
    $('#fecha_n').change(calcularEdad);
    function calcularEdad (){
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
    }

    

    // MOSTRAR EL FORMULARIO PARA REGISTRAR UN NUEVO APRENDIZ.
    $('#show_form').click(function (){
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#form_title').html('Registrar');
        /////////////////////
        while (vista != 1)
            $('#retroceder_form').trigger('click');
        /////////////////////
        if (localStorage.getItem('confirm_data')){
            setTimeout(() => {
                if (confirm('Hay datos sin guardar, ¿Quieres seguir editandolos?')) {
                    $('.localStorage').each(function (){ $(this).val(localStorage.getItem($(this).attr('id'))); });
                    $('.localStorage-radio').each(function (){
                        if ($(this).val() == localStorage.getItem($(this).attr('name'))) {
                            $(this).prop('checked','checked');
                        }
                    });
                } else {
                    localStorage.removeItem('confirm_data');
                    $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
                    $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
                    limpiarFormulario();
                }
            }, 500);
        } else {
            limpiarFormulario();
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
            contenido += '</select></td>';
            
            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="parentesco_familiar" class="custom-select custom-select-sm" style="width: 136px;">';
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="ocupacion_familiar" class="custom-select custom-select-sm" style="width: 140px;">';
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><select name="trabaja_familiar" class="custom-select custom-select-sm" style="width: 61px;">';
            contenido += '</select></td>';

            contenido += '<td class="align-middle py-0 pr-1 pl-0"><input type="text" name="ingresos_familiar[]" class="form-control form-control-sm" style="width: 96px;"></td>';
            contenido += '<td class="align-middle py-0 px-0 text-center"><div class="custom-control custom-radio d-inline-block" style="width: 0px;"><input type="radio" class="custom-control-input" id="responsable_apre_'+cantidad+'" name="responsable_apre" value="'+cantidad+'"><label class="custom-control-label" for="responsable_apre_'+cantidad+'"></label></div></td>';
            contenido += '<td class="py-1 px-0"><button type="button" class="btn btn-sm btn-danger delete-row"><i class="fas fa-times"></i></button></td>';
            contenido += '</tr>';

            $('#tabla_datos_familiares tbody').append(contenido);
            $($('.delete-row')[$('.delete-row').length - 1]).click(eliminarFila);
            $($('.calcular_edad')[$('.calcular_edad').length - 1]).change(calcularEdadF);
        } else {
            alert('Solo es permitido un maximo de 10 filas.');
        }
    });

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

            cont++;
        });
    };

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

    $('.localStorage').keyup(guardarLocalStorage);
    $('.localStorage').change(guardarLocalStorage);
    $('.localStorage-radio').click(guardarLocalStorage);
    function guardarLocalStorage() {
        localStorage.setItem('confirm_data', true);
        localStorage.setItem($(this).attr('name'), $(this).val());
    }
});