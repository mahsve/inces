$(function () {
    // MOSTRAR EL FORMULARIO PARA REGISTRAR UN NUEVO APRENDIZ.
    $('#show_form').click(function (){
        $('#info_table').hide(400);
        $('#gestion_form').show(400);
        $('#form_title').html('Registrar');
        $('#carga_espera').hide();
        tipoEnvio = 'Registrar';
        /////////////////////
        // limpiarFormulario();
        // if (localStorage.getItem('confirm_data')){
        //     setTimeout(() => {
        //         if (confirm('Hay datos sin guardar, ¿Quieres seguir editandolos?')) {
        //             $('.localStorage').each(function (){
        //                 let valor = localStorage.getItem($(this).attr('id'));
        //                 if (valor != '' && valor != null && valor != undefined)
        //                     $(this).val(localStorage.getItem($(this).attr('id')));
        //             });
        //             $('.localStorage-radio').each(function (){
        //                 if ($(this).val() == localStorage.getItem($(this).attr('name')))
        //                     $(this).prop('checked','checked');
        //             });
        //             let grado_instruccion = localStorage.getItem('grado_instruccion');
        //             if (grado_instruccion == 'SI' || grado_instruccion == 'SC')
        //                 $('#titulo').attr('disabled', false);

        //             for (let index = 1; index <= maxFamiliares; index++) {
        //                 if (localStorage.getItem('filaFamilia'+index)) {
        //                     window.actualizar3 = true;
        //                     $('#agregar_familiar').trigger('click');

        //                     let arregloFamilia = JSON.parse(localStorage.getItem('filaFamilia'+index));
        //                     for(var posicion in arregloFamilia) {
        //                         if (arregloFamilia[posicion] != '')
        //                             $('#'+posicion+index).val(arregloFamilia[posicion]);
        //                     }
        //                 }
        //             }
        //             $('.trabajando').trigger('change');
        //             if (localStorage.getItem('responsable_apre')) 
        //                 document.formulario.responsable_apre.value = localStorage.getItem('responsable_apre');

        //             window.actualizar3 = false;

        //             window.actualizar = true;
        //             $('#estado').trigger('change');
        //             $('#fecha_n').trigger('change');
        //         } else {
        //             localStorage.removeItem('confirm_data');
        //             $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
        //             $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
        //         }
        //     }, 500);
        // }
    });

    // MOSTRAR LA TABLA CON TODA LA LISTA DE LOS APRENDICES REGISTRADOS.
    $('#show_table').click(function (){
        $('#info_table').show(400);
        $('#gestion_form').hide(400);
        /////////////////////
        // window.editar = false;
        // /////////////////////
        // while (vista != 1)
        //     $('#retroceder_form').trigger('click');
        // /////////////////////
        // localStorage.removeItem('confirm_data');
        // $('.localStorage').each(function (){ localStorage.removeItem($(this).attr('name')); });
        // $('.localStorage-radio').each(function (){ localStorage.removeItem($(this).attr('name')); });
        
        // for (let index = 1; index <= maxFamiliares; index++) {
        //     localStorage.removeItem('filaFamilia'+index);
        // }
    });
});