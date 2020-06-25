$(function () {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let tarjeta_1;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    ////////////////////////// VALIDACIONES /////////////////////////////
    function verificarParte1 () {
        tarjeta_1 = true; // CAMBIA A FALSO SI ALGUNO DE LOS CAMPOS ESTA MAL DEFINIDO.
        // VALIDAR EL PRIMER CAMPO DE LA CONTRASEÑA
        let contrasena_1 = $("#contrasena_1").val();
        if (contrasena_1 != '') {
            if (contrasena_1.length >= 8) {
                $("#contrasena_1").css("background-color", colorb);
            } else {
                // MENSAJE DE CONTRASEÑA ERRONEA
                let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                let contenedor_mensaje = '';
                contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                contenedor_mensaje += '<i class="fas fa-times"></i> <span style="font-weight: 500;">La contraseña debe tener al menos 8 caracteres.</span>';
                contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                contenedor_mensaje += '</button>';
                contenedor_mensaje += '</div>';
                $('#contenedor-mensaje').html(contenedor_mensaje);

                // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);

                $("#contrasena_1").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#contrasena_1").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VALIDAR EL SEGUNDO CAMPO DE LA CONTRASEÑA
        let contrasena_2 = $("#contrasena_2").val();
        if (contrasena_2 != '') {
            if (contrasena_1 == contrasena_2) {
                $("#contrasena_2").css("background-color", colorb);
            } else {
                // MENSAJE DE CONTRASEÑA ERRONEA
                let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                let contenedor_mensaje = '';
                contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                contenedor_mensaje += '<i class="fas fa-times"></i> <span style="font-weight: 500;">Las contraseñas no coinciden.</span>';
                contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                contenedor_mensaje += '</button>';
                contenedor_mensaje += '</div>';
                $('#contenedor-mensaje').append(contenedor_mensaje);

                // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);

                $("#contrasena_2").css("background-color", colorm);
                tarjeta_1 = false;
            }
        } else {
            $("#contrasena_2").css("background-color", colorm);
            tarjeta_1 = false;
        }
        // VALIDAR EL SEGUNDO CAMPO DE LA CONTRASEÑA
        let tiempo = $("#tiempo").val();
        if (tiempo != '') {
            $("#tiempo").css("background-color", colorb);
        } else {
            $("#tiempo").css("background-color", colorm);
            tarjeta_1 = false;
        }
    }
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar-datos').click(function (e) {
        e.preventDefault();
        verificarParte1();

        // SE VERIFICA QUE TODOS LOS CAMPOS ESTEN DEFINIDOS CORRECTAMENTE.
        if (tarjeta_1) {
            var data = $("#formulario").serializeArray();
            data.push({ name: 'opcion', value: 'Actualizar contrasena' });

            // DESHABILITAMOS LOS BOTONES PARA EVITAR QUE CLIQUEE DOS VECES REPITIENDO LA CONSULTA O QUE SALGA DEL FORMULARIO SIN TERMINAR
            $('.botones_formulario').attr('disabled', true);
            $('#guardar-datos i.fa-save').addClass('fa-spin');
            $('#guardar-datos span').html('Guardando...');

            // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
            $('#contenedor-mensaje').empty();
            
            setTimeout(() => {
                $.ajax({
                    url : url+'controllers/c_actualizar_datos.php',
                    type: 'POST',
                    data: data,
                    success: function (resultados) {
                        let color_alerta = '';
                        let icono_alerta = '';

                        if (resultados == 'Modificación exitosa') {
                            color_alerta = 'alert-success';
                            icono_alerta = '<i class="fas fa-check"></i>';
                        } else if (resultados.indexOf('Modificación fallida') == -1) {
                            color_alerta = 'alert-danger';
                            icono_alerta = '<i class="fas fa-times"></i>';
                        }
    
                        // MENSAJE SOBRE EL ESTATUS DE LA CONSULTA.
                        let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert '+color_alerta+' mt-2 mb-0 py-2 px-3" role="alert">';
                        contenedor_mensaje += icono_alerta+' '+resultados;
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';

                        // CARGAMOS EL MENSAJE EN EL CONTENEDOR CORRESPONDIENTE.
                        $('#contenedor-mensaje').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => {
                            $('#alerta-'+idAlerta).fadeOut(500);
                            if (resultados == 'Modificación exitosa') {
                                location.reload();
                            } else {
                                // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                                $('.botones_formulario').attr('disabled', false);
                                $('#guardar-datos i.fa-save').removeClass('fa-spin');
                                $('#guardar-datos span').html('Guardar');
                            }
                        }, 5000);
                    },
                    error: function (errorConsulta) {
                        // MENSAJE DE ERROR DE CONEXION.
                        let idAlerta = Math.random().toString().replace('.', '-'); // GENERA UN ID ALEATORIO.
                        let contenedor_mensaje = '';
                        contenedor_mensaje += '<div id="alerta-'+idAlerta+'" class="alert alert-danger mt-2 mb-0 py-2 px-3" role="alert">';
                        contenedor_mensaje += '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                        contenedor_mensaje += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        contenedor_mensaje += '<span aria-hidden="true">&times;</span>';
                        contenedor_mensaje += '</button>';
                        contenedor_mensaje += '</div>';
                        $('#contenedor-mensaje').html(contenedor_mensaje);

                        // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                        setTimeout(() => { $('#alerta-'+idAlerta).fadeOut(500); }, 5000);
    
                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $('.botones_formulario').attr('disabled', false);
                        $('#guardar-datos i.fa-save').removeClass('fa-spin');
                        $('#guardar-datos span').html('Guardar');

                        // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                        console.log('Error: '+errorConsulta.status+' - '+errorConsulta.statusText);
                        console.log(errorConsulta.responseText);
                    }, timer: 15000
                });
            }, 500);
        }
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
});