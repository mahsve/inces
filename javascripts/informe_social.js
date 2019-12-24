$(function () {
    // MOSTRAR EL FORMULARIO PARA REGISTRAR UN NUEVO APRENDIZ.
    $('#show_form').click(function (){
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#form_title').html('Registrar');
        /////////////////////
        while (vista != 1) {
            $('#retroceder_form').trigger('click');
        }
    });

    // MOSTRAR LA TABLA CON TODA LA LISTA DE LOS APRENDICES REGISTRADOS.
    $('#show_table').click(function (){
        $('#info_table').show(400);
        $('#gestion_form').hide(400);
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
        }
        
        if (vista != 4)
            vista++;
        
        if (vista == 4)
            $('#avanzar_form').attr('disabled', true);
    });
});