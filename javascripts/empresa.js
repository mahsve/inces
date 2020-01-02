$(function () {
    let fecha = '';             // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let tipoEnvio = '';         // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).

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
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function (){
                alert('Hubo un error al conectar con el servidor y traer los datos.');
            }
        });
    }

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
                    } catch (error) { console.log(resultados); }
                },
                error: function () {
                    alert('Hubo un error al conectar con el servidor y traer los datos.');
                }
            });
        } else {
            $(nombreInput).append('<option value="">Elija un estado</option>');
        }
    }

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

    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: tipoEnvio });
        
        $.ajax({
            url : url+'controllers/c_empresa.php',
            type: 'POST',
            data: data,
            success: function (resultados) {
                alert(resultados);
                if (resultados == 'Registro exitoso' || resultados == 'Modificacion exitosa'){
                    // $('#show_table').trigger('click');
                    // buscar_listado();
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });
});