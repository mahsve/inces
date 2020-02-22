$(function () {
    /////////////////////////////////////////////////////////////////////
    let datosP  = [];   // VARIABLE PARA GUARDAR LOS RESULTADOS CONSULTADOS.
    let fecha   = '';   // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
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
        $('#edad').val(edad);
    });
    $('#estado').change(function () {
        if ($(this).val() != '') {
            $.ajax({
                url : url+'controllers/c_datos_personales.php',
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
                        if (window.agregarExtras == true) {
                            window.agregarExtras = false;
                            $('#ciudad').val(datosP.codigo_ciudad);
                            if (datosP.codigo_municipio != null) {
                                window.agregarExtras2 = true;
                                $('#municipio').val(datosP.codigo_municipio);
                                $('#municipio').trigger('change');
                            } else {
                                $('#carga_espera').hide(400);
                            }
                        }
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        } else {
            $('#ciudad').html('<option value="">Elija un estado</option>');
            $('#municipio').html('<option value="">Elija un estado</option>');
        }
        $('#parroquia').html('<option value="">Elija un municipio</option>');
    });
    $('#municipio').change(function () {
        if ($(this).val() != '') {
            $.ajax({
                url : url+'controllers/c_datos_personales.php',
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
                        if (window.agregarExtras2 == true) {
                            window.agregarExtras2 = false;
                            $('#parroquia').val(datosP.codigo_parroquia);
                            $('#carga_espera').hide(400);
                        }
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
    /////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    function definirDatos () {
        $('#nacionalidad').val(datosP.nacionalidad);
        $('#cedula').val(datosP.cedula);
        $('#nombre_1').val(datosP.nombre1);
        $('#nombre_2').val(datosP.nombre2);
        $('#apellido_1').val(datosP.apellido1);
        $('#apellido_2').val(datosP.apellido2);
        $('#sexo').val(datosP.sexo);
        $('#fecha_n').val(datosP.fecha_n);
        $('#fecha_n').trigger('change');
        $('#lugar_n').val(datosP.lugar_n);
        $('#ocupacion').val(datosP.codigo_ocupacion);
        $('#telefono_1').val(datosP.telefono1);
        $('#telefono_2').val(datosP.telefono2);
        $('#correo').val(datosP.correo);
        /////////////////////////////////////////////////////////////////
        window.agregarExtras = true;
        $('#estado').val(datosP.codigo_estado);
        $('#estado').trigger('change');
        $('#direccion').val(datosP.direccion);
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $('#guardar_datos').click(function (e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: 'opcion', value: 'Modificar' });
        
        $.ajax({
            url : url+'controllers/c_datos_personales.php',
            type: 'POST',
            data: data,
            success: function (resultados) {
                alert(resultados);
                if (resultados == 'Modificacion exitosa'){
                    location.reload();
                }
            },
            error: function () {
                console.log('error');
            }
        });
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos()
    {
        $.ajax({
            url : url+'controllers/c_datos_personales.php',
            type: 'POST',
            data: { opcion: 'Traer datos' },
            success: function (resultados){
                try {
                    let data = JSON.parse(resultados);
                    fecha = data.fecha;
                    datosP = data.datospersonales;

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
                    definirDatos();
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
    $('#fecha_n').datepicker();
});