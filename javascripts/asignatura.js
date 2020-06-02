$(function() {
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // PARAMETROS PARA VERIFICAR LOS CAMPOS CORRECTAMENTE.
    let ER_codigoFormulario = /^([0-9a-zA-Z-])+$/;
    let ER_alfaNumericoConEspacios = /^([a-zA-Z0-9,.\x7f-\xff](\s[a-zA-Z0-9,.\x7f-\xff])*)+$/;
    // VARIABLE QUE GUARDARA FALSE SI ALGUNOS DE LOS CAMPOS NO ESTA CORRECTAMENTE DEFINIDO
    let pestania1;
    // COLORES PARA VISUALMENTE MOSTRAR SI UN CAMPO CUMPLE LOS REQUISITOS
    let colorb = "#d4ffdc"; // COLOR DE EXITO, EL CAMPO CUMPLE LOS REQUISITOS.
    let colorm = "#ffc6c6"; // COLOR DE ERROR, EL CAMPO NO CUMPLE LOS REQUISITOS.
    let colorn = "#ffffff"; // COLOR BLANCO PARA MOSTRAR EL CAMPOS POR DEFECTO SIN ERRORES.
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // DATOS DE LA TABLA Y PAGINACION
    let numeroDeLaPagina = 1;
    $("#cantidad_a_buscar").change(restablecerN);
    $("#ordenar_por").change(restablecerN);
    $("#campo_ordenar").change(restablecerN);
    $("#campo_busqueda").keydown(function(e) {
        if (e.keyCode == 13) {
            numeroDeLaPagina = 1;
            buscar_listado();
        } else {
            window.actualizar_busqueda = true;
        }
    });
    $("#campo_busqueda").blur(function() {
        if (window.actualizar_busqueda) {
            buscar_listado();
        }
    });
    $("#buscar_estatus").change(restablecerN);
    /////////////////////////////////////////////////////////////////////
    // VARIABLES NECESARIAS PARA GUARDAR LOS DATOS CONSULTADOS
    let tipoEnvio = ""; // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado = []; // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA RESTABLECER LA PAGINACION A 1 SI CAMBIA ALGUNOS DE LOS PARAMETROS.
    function restablecerN() {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    // FUNCION PARA LLAMAR LO DATOS DE LA BASE DE DATOS Y MOSTRARLOS EN LA TABLA.
    buscar_listado();
    function buscar_listado() {
        let filas = 0;
        if (permisos.modificar == 1 || permisos.act_desc == 1) {
            filas = 4;
        } else {
            filas = 3;
        }

        let contenido_tabla = "";
        contenido_tabla += "<tr>";
        contenido_tabla +=
            '<td colspan="' +
            filas +
            '" class="text-center text-secondary border-bottom p-2">';
        contenido_tabla +=
            '<i class="fas fa-spinner fa-spin"></i> <span style="font-weight: 500;">Cargando...</span>';
        contenido_tabla += "</td>";
        contenido_tabla += "</tr>";
        $("#listado_tabla tbody").html(contenido_tabla);

        let contenido_paginacion = "";
        contenido_paginacion += '<li class="page-item">';
        contenido_paginacion += '<a class="page-link text-info">';
        contenido_paginacion += '<i class="fas fa-spinner fa-spin"></i>';
        contenido_paginacion += "</a>";
        contenido_paginacion += "</li>";
        $("#paginacion").html(contenido_paginacion);

        // DESABILITAMOS LOS BOTONES PARA EVITAR ACCIONES MIENTRAS SE EJECUTA LA CONSULTA
        $("#cantidad_a_buscar").attr("disabled", true);
        $("#ordenar_por").attr("disabled", true);
        $("#campo_ordenar").attr("disabled", true);
        $("#buscar_estatus").attr("disabled", true);
        $("#campo_busqueda").attr("disabled", true);

        // DESABILITAMOS LA OPCION DE AGREGAR NUEVOS DATOS HASTA QUE NO TERMINE LA CONSULTA.
        $("#show_form").attr("disabled", true);
        setTimeout(() => {
            $.ajax({
                url: url + "controllers/c_asignatura.php",
                type: "POST",
                dataType: "JSON",
                data: {
                    opcion: "Consultar",
                    numero:
                        parseInt(numeroDeLaPagina - 1) *
                        parseInt($("#cantidad_a_buscar").val()),
                    cantidad: parseInt($("#cantidad_a_buscar").val()),
                    ordenar: parseInt($("#ordenar_por").val()),
                    tipo_ord: parseInt($("#campo_ordenar").val()),
                    campo: $("#campo_busqueda").val(),
                    estatus: $("#buscar_estatus").val(),
                },
                success: function(resultados) {
                    $("#listado_tabla tbody").empty();

                    dataListado = resultados;
                    if (dataListado.resultados) {
                        for (var i in dataListado.resultados) {
                            let estatus_td = "";
                            if (dataListado.resultados[i].estatus == "A") {
                                estatus_td =
                                    '<span class="badge badge-success"><i class="fas fa-check"></i> <span style="font-weight: 500;">Activo</span></span>';
                            } else if (
                                dataListado.resultados[i].estatus == "I"
                            ) {
                                estatus_td =
                                    '<span class="badge badge-danger"><i class="fas fa-times"></i> <span style="font-weight: 500;">Inactivo</span></span>';
                            }

                            let contenido = "";
                            contenido +=
                                '<tr class="border-bottom text-secondary">';
                            contenido +=
                                '<td class="py-2 px-1">' +
                                dataListado.resultados[i].codigo +
                                "</td>";
                            contenido +=
                                '<td class="py-2 px-1">' +
                                dataListado.resultados[i].nombre +
                                "</td>";

                            contenido +=
                                '<td class="py-2 px-1">' +
                                dataListado.resultados[i].modulo +
                                "</td>";

                            contenido +=
                                '<td class="text-center py-2 px-1">' +
                                estatus_td +
                                "</td>";
                            ////////////////////////////////////////////////////////
                            if (
                                permisos.modificar == 1 ||
                                permisos.act_desc == 1
                            ) {
                                contenido += '<td class="py-1 px-1">';
                                if (permisos.modificar == 1) {
                                    contenido +=
                                        '<button type="button" class="btn btn-sm btn-info editar-registro" data-posicion="' +
                                        i +
                                        '" style="margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button>';
                                }
                                if (permisos.act_desc == 1) {
                                    if (
                                        dataListado.resultados[i].estatus == "A"
                                    ) {
                                        contenido +=
                                            '<button type="button" class="btn btn-sm btn-danger cambiar-estatus" data-posicion="' +
                                            i +
                                            '"><i class="fas fa-eye-slash" style="font-size: 12px;"></i></button>';
                                    } else {
                                        contenido +=
                                            '<button type="button" class="btn btn-sm btn-success cambiar-estatus" data-posicion="' +
                                            i +
                                            '"><i class="fas fa-eye"></i></button>';
                                    }
                                }
                                contenido += "</td>";
                            }
                            contenido += "</tr>";
                            $("#listado_tabla tbody").append(contenido);
                        }
                        $(".editar-registro").click(editarRegistro);
                        $(".cambiar-estatus").click(cambiarEstatus);
                    } else {
                        contenido_tabla = "";
                        contenido_tabla += "<tr>";
                        contenido_tabla +=
                            '<td colspan="' +
                            filas +
                            '" class="text-center text-secondary border-bottom p-2">';
                        contenido_tabla +=
                            '<i class="fas fa-file-alt"></i> <span style="font-weight: 500;"> No hay oficios registrados.</span>';
                        contenido_tabla += "</td>";
                        contenido_tabla += "</tr>";
                        $("#listado_tabla tbody").html(contenido_tabla);
                    }

                    // SE HABILITA LA FUNCION PARA QUE PUEDA REALIZAR BUSQUEDA AL TERMINAR LA ANTERIOR.
                    window.actualizar_busqueda = false;
                    // MOSTRAR EL TOTAL DE REGISTROS ENCONTRADOS.
                    $("#total_registros").html(dataListado.total);
                    // HABILITAR LA PAGINACION PARA MOSTRAR MAS DATOS.
                    establecer_tabla(
                        numeroDeLaPagina,
                        parseInt($("#cantidad_a_buscar").val()),
                        dataListado.total
                    );
                    // LE AGREGAMOS FUNCIONALIDAD A LOS BOTONES PARA CAMBIAR LA PAGINACION.
                    $(".mover").click(cambiarPagina);

                    $("#cantidad_a_buscar").attr("disabled", false);
                    $("#ordenar_por").attr("disabled", false);
                    $("#campo_ordenar").attr("disabled", false);
                    $("#buscar_estatus").attr("disabled", false);
                    $("#campo_busqueda").attr("disabled", false);
                    // HABILITAMOS EL BOTON PARA AGREGAR NUEVOS DATOS.
                    $("#show_form").attr("disabled", false);
                },
                error: function(msjError) {
                    contenido_tabla = "";
                    contenido_tabla += "<tr>";
                    contenido_tabla +=
                        '<td colspan="' +
                        filas +
                        '" class="text-center text-secondary border-bottom text-danger p-2">';
                    contenido_tabla +=
                        '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                    contenido_tabla +=
                        '<button type="button" id="btn-recargar-tabla" class="btn btn-sm btn-info ml-2"><i class="fas fa-sync-alt"></i></button>';
                    contenido_tabla += "</td>";
                    contenido_tabla += "</tr>";
                    $("#listado_tabla tbody").html(contenido_tabla);
                    $("#btn-recargar-tabla").click(buscar_listado);

                    contenido_paginacion = "";
                    contenido_paginacion += '<li class="page-item">';
                    contenido_paginacion += '<a class="page-link text-danger">';
                    contenido_paginacion += '<i class="fas fa-ethernet"></i>';
                    contenido_paginacion += "</a>";
                    contenido_paginacion += "</li>";
                    $("#paginacion").html(contenido_paginacion);

                    $("#cantidad_a_buscar").attr("disabled", false);
                    $("#ordenar_por").attr("disabled", false);
                    $("#campo_ordenar").attr("disabled", false);
                    $("#buscar_estatus").attr("disabled", false);
                    $("#campo_busqueda").attr("disabled", false);
                    // HABILITAMOS EL BOTON PARA AGREGAR NUEVOS DATOS.
                    $("#show_form").attr("disabled", false);
                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log(
                        "Error: " +
                            msjError.status +
                            " - " +
                            msjError.statusText
                    );
                    console.log(msjError.responseText);
                },
                timer: 15000,
            });
        }, 500);
    }
    // FUNCION PARA CAMBIAR LA PAGINACION.
    function cambiarPagina(e) {
        e.preventDefault();
        let numero = $(this).attr("data-pagina");
        numeroDeLaPagina = parseInt(numero);
        buscar_listado();
    }
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    ////////////////////////// VALIDACIONES /////////////////////////////
    function verificarParte1() {
        pestania1 = true;
        // VERIFICAR EL CAMPO DEL NOMBRE DE LA OCUPACION.
        let codigo = $("#codigo").val();
        if (codigo != "") {
            if (codigo.match(ER_codigoFormulario)) {
                $("#codigo").css("background-color", colorb);
            } else {
                $("#codigo").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#codigo").css("background-color", colorm);
            pestania1 = false;
        }
        // VERIFICAR EL CAMPO DEL NOMBRE DE LA OCUPACION.
        let nombre = $("#nombre").val();
        if (nombre != "") {
            if (nombre.match(ER_alfaNumericoConEspacios)) {
                $("#nombre").css("background-color", colorb);
            } else {
                $("#nombre").css("background-color", colorm);
                pestania1 = false;
            }
        } else {
            $("#nombre").css("background-color", colorm);
            pestania1 = false;
        }
    }
    /////////////////////////////////////////////////////////////////////
    // CLASES EXTRAS Y LIMITACIONES
    $("#nombre").keypress(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });
    /////////////////////////////////////////////////////////////////////
    $("#show_form").click(function() {
        $("#carga_espera").show();
        $("#info_table").hide(400);
        $("#gestion_form").show(400);
        $("#carga_espera").hide(400);
        $("#form_title").html("Registrar");
        $("form .form-control").css("background-color", colorn);
        $("form .custom-select").css("background-color", colorn);

        tipoEnvio = "Registrar";
        window.codigo = "";
        document.formulario.reset();
    });
    $("#show_table").click(function() {
        $("#info_table").show(400);
        $("#gestion_form").hide(400);
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    $("#oficio").change(buscarModulos);
    $("#oficio_c").change(buscarModulos);
    function buscarModulos() {
        let nombreInput = "";
        if ($(this).attr("name") == "oficio") nombreInput = "#modulo";
        else nombreInput = "#modulo_c";

        if ($(this).val() != "") {
            $.ajax({
                url: url + "controllers/c_asignatura.php",
                type: "POST",
                data: { opcion: "Traer modulo", oficio: $(this).val() },
                success: function(resultados) {
                    try {
                        let data = JSON.parse(resultados);
                        $(nombreInput).empty();
                        if (data.modulos) {
                            $(nombreInput).append(
                                '<option value="">Elija una opción</option>'
                            );

                            for (let i in data.modulos) {
                                $(nombreInput).append(
                                    '<option value="' +
                                        data.modulos[i].codigo +
                                        '">' +
                                        data.modulos[i].nombre +
                                        "</option>"
                                );
                            }
                        } else {
                            $(nombreInput).append(
                                '<option value="">No hay modulos</option>'
                            );
                        }

                        if (window.buscarModulo == true) {
                            $("#modulo").val(
                                dataListado.resultados[window.posicion]
                                    .codigo_modulo
                            );
                            delete window.buscarModulo;
                        }

                        if (window.buscarModulo_c1 == true) {
                            $("#modulo_c").val(
                                dataListado.resultados[window.posicion]
                                    .datos_personales.codigo_modulo
                            );
                            delete window.buscarModulo_c1;
                        }

                        if (window.buscarModulo_c == true) {
                            $("#oficio_c").trigger("change");
                            delete window.buscarModulo_c;
                            window.buscarModulo_c1 = true;
                        }

                        if (window.buscarMOdulo_c2 == true) {
                            $("#modulo_c").val(window.modulo_c);
                            delete window.buscarMOdulo_c2;
                            delete window.modulo_c;
                        }
                        $("#carga_espera").hide(400);
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function() {
                    alert(
                        "Hubo un error al conectar con el servidor y traer los datos."
                    );
                },
            });
        } else {
            $(nombreInput).append('<option value="">Elija un modulo</option>');
        }
    }
    /////////////////////////////////////////////////////////////////////
    $("#show_form").click(function() {
        $("#form_title").html("Registrar");
        $("#info_table").hide(400);
        $("#gestion_form").show(400);
        $("#carga_espera").hide(400);
        tipoEnvio = "Registrar";
        /////////////////////
        limpiarFormulario();
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarRegistro() {
        let posicion = $(this).attr("data-posicion");
        window.posicion = posicion;
        /////////////////////
        $("#info_table").hide(400);
        $("#gestion_form").show(400);
        $("#form_title").html("Modificar");
        $("#carga_espera").show();
        tipoEnvio = "Modificar";
        /////////////////////
        limpiarFormulario();
        /////////////////////
        // LLENADO DEL FORMULARIO CON LOS DATOS REGISTRADOS.
        window.codigo = dataListado.resultados[posicion].codigo;
        $("#codigo").val(dataListado.resultados[posicion].codigo);
        $("#nombre").val(dataListado.resultados[posicion].nombre);
        $("#modulo").val(dataListado.resultados[posicion].codigo_modulo);
        $("#oficio").val(dataListado.resultados[posicion].codigo_oficio);
        window.buscarModulo = true;
        $("#oficio").trigger("change");

        /////////////////////////////////////////////////////////////////////
    }
    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).

    $("#guardar_datos").click(function(e) {
        e.preventDefault();

        var data = $("#formulario").serializeArray();
        data.push({ name: "opcion", value: tipoEnvio });
        data.push({ name: "codigo2", value: window.codigo });

        $.ajax({
            url: url + "controllers/c_asignatura.php",
            type: "POST",
            data: data,
            success: function(resultados) {
                alert(resultados);
                if (
                    resultados == "Registro exitoso" ||
                    resultados == "Modificacion exitosa"
                ) {
                    $("#show_table").trigger("click");
                    buscar_listado();
                }
            },
            error: function() {
                console.log("error");
            },
        });
    });
    // FUNCION PARA CAMBIAR EL ESTATUS DEL REGISTRO (ACTIVAR / INACTIVAR).
    function cambiarEstatus(e) {
        e.preventDefault();
        let posicion = $(this).attr("data-posicion");
        let codigo = dataListado.resultados[posicion].codigo;

        ///////////////////
        $(".editar-registro").attr("disabled", true);
        $(".cambiar-estatus").attr("disabled", true);
        $("#show_form").attr("disabled", true);
        // LIMPIAMOS LOS CONTENEDORES DE LOS MENSAJES DE EXITO O DE ERROR DE LAS CONSULTAS ANTERIORES.
        $("#contenedor-mensaje").empty();
        $("#contenedor-mensaje2").empty();

        // DEFINIMOS EL ESTATUS POR EL QUE SE VA A ACTUALIZAR
        let estatus = "";
        if (dataListado.resultados[posicion].estatus == "A") {
            estatus = "I";
        } else {
            estatus = "A";
        }

        setTimeout(() => {
            $.ajax({
                url: url + "controllers/c_asignatura.php",
                type: "POST",
                data: {
                    opcion: "Estatus",
                    codigo: codigo,
                    estatus: estatus,
                },
                success: function(resultados) {
                    let color_alerta = "";
                    let icono_alerta = "";

                    if (resultados == "Modificación exitosa") {
                        buscar_listado();
                        color_alerta = "alert-success";
                        icono_alerta = '<i class="fas fa-check"></i>';
                    } else if (resultados == "Modificación fallida") {
                        color_alerta = "alert-danger";
                        icono_alerta = '<i class="fas fa-times"></i>';

                        // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                        $(".editar-registro").attr("disabled", false);
                        $(".cambiar-estatus").attr("disabled", false);
                        $("#show_form").attr("disabled", false);
                    }

                    let contenedor_mensaje = "";
                    contenedor_mensaje +=
                        '<div class="alert ' +
                        color_alerta +
                        ' mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                    contenedor_mensaje += icono_alerta + " " + resultados;
                    contenedor_mensaje +=
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje +=
                        '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += "</button>";
                    contenedor_mensaje += "</div>";
                    $("#contenedor-mensaje").html(contenedor_mensaje);
                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => {
                        $(".alerta-formulario").fadeOut(500);
                    }, 5000);
                },
                error: function(msjError) {
                    // HABILITAMOS NUEVAMENTE LOS BOTONES AL TERMINAR LA CONSULTA AJAX
                    $(".editar-registro").attr("disabled", false);
                    $(".cambiar-estatus").attr("disabled", false);
                    $("#show_form").attr("disabled", false);

                    // MENSAJE DE ERROR DE CONEXION.
                    let contenedor_mensaje = "";
                    contenedor_mensaje +=
                        '<div class="alert alert-danger mt-2 mb-0 py-2 px-3 alerta-formulario" role="alert">';
                    contenedor_mensaje +=
                        '<i class="fas fa-ethernet"></i> <span style="font-weight: 500;">[Error] No se pudo realizar la conexión.</span>';
                    contenedor_mensaje +=
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    contenedor_mensaje +=
                        '<span aria-hidden="true">&times;</span>';
                    contenedor_mensaje += "</button>";
                    contenedor_mensaje += "</div>";
                    $("#contenedor-mensaje").html(contenedor_mensaje);
                    // DESPUES DE 5 SEGUNDOS SE OCULTARA EL MENSAJE QUE HAYA DADO EL SERVIDOR
                    setTimeout(() => {
                        $(".alerta-formulario").fadeOut(500);
                    }, 5000);

                    // EN CASO DE ERROR MOSTRAMOS POR CONSOLA LA INFORMACION DE DICHO ERROR.
                    console.log(
                        "Error: " +
                            msjError.status +
                            " - " +
                            msjError.statusText
                    );
                    console.log(msjError.responseText);
                },
                timer: 15000,
            });
        }, 500);
    }

    // FUNCION PARA LIMPIAR EL FORMULARIO DE LOS DATOS ANTERIORES.
    function limpiarFormulario() {
        document.formulario.reset();
        $(".ocultar-iconos").hide();
        $(".limpiar-estatus").removeClass(
            "fa-check text-success fa-times text-danger"
        );
        /////////////////////////////////////////////////////////////////
        window.buscarModulo = false;
    }
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    llamarDatos();
    function llamarDatos() {
        $.ajax({
            url: url + "controllers/c_asignatura.php",
            type: "POST",
            data: { opcion: "Traer datos" },
            success: function(resultados) {
                try {
                    let data = JSON.parse(resultados);

                    $("#oficio").empty();
                    $("#modulo").empty();
                    $("#oficio_c").empty();
                    $("#modulo_c").empty();
                    ///////////////////////

                    if (data.oficios) {
                        $("#oficio").append(
                            '<option value="">Elija un oficio</option>'
                        );
                        $("#oficio_c").append(
                            '<option value="">Elija un oficio</option>'
                        );
                        ///////////////////////
                        $("#modulo").append(
                            '<option value="">Elija un modulo</option>'
                        );

                        $("#modulo_c").append(
                            '<option value="">Elija un modulo</option>'
                        );

                        for (let i in data.oficios) {
                            $("#oficio").append(
                                '<option value="' +
                                    data.oficios[i].codigo +
                                    '">' +
                                    data.oficios[i].nombre +
                                    "</option>"
                            );
                            $("#oficio_c").append(
                                '<option value="' +
                                    data.oficios[i].codigo +
                                    '">' +
                                    data.oficios[i].nombre +
                                    "</option>"
                            );
                        }
                    } else {
                        $("#oficio").append(
                            '<option value="">No hay oficios</option>'
                        );
                    }

                    buscar_listado();
                } catch (error) {
                    console.log(resultados);
                }
            },
            error: function() {
                alert(
                    "Hubo un error al conectar con el servidor y traer los datos."
                );
            },
        });
    }
});