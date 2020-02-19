$(function() {
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
            window.actualizar_busqueda = false;
        } else window.actualizar_busqueda = true;
    });
    $("#campo_busqueda").blur(function() {
        if (window.actualizar_busqueda) buscar_listado();
    });
    $("#buscar_estatus").change(restablecerN);
    /////////////////////////////////////////////////////////////////////
    let fecha = ""; // VARIABLE PARA GUARDAR LA FECHA ACTUAL.
    let dataOcupacion = false; // VARIABLE PARA GUARDAR LAS OCUPACIONES Y AGREGARLAS A LA TABLA FAMILIA.

    let tipoEnvio = ""; // VARIABLE PARA ENVIAR EL TIPO DE GUARDADO DE DATOS (REGISTRO / MODIFIACION).
    let dataListado = []; // VARIABLE PARAGUARDAR LOS RESULTADOS CONSULTADOS.
    /////////////////////////////////////////////////////////////////////
    function restablecerN() {
        numeroDeLaPagina = 1;
        buscar_listado();
    }
    function buscar_listado() {
        $("#listado_aprendices tbody").html(
            '<tr><td colspan="10" class="text-center text-secondary border-bottom p-2"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</td></tr>'
        );
        $("#paginacion").html(
            '<li class="page-item"><a class="page-link text-info"><i class="fas fa-spinner fa-spin mr-3"></i>Cargando</a></li>'
        );
        $.ajax({
            url: url + "controllers/c_informe_social.php",
            type: "POST",
            data: {
                opcion: "Consultar",
                numero:
                    parseInt(numeroDeLaPagina - 1) *
                    parseInt($("#cantidad_a_buscar").val()),
                cantidad: parseInt($("#cantidad_a_buscar").val()),
                ordenar: parseInt($("#ordenar_por").val()),
                tipo_ord: parseInt($("#campo_ordenar").val()),
                campo: $("#campo_busqueda").val(),
                estatus: $("#buscar_estatus").val()
            },
            success: function(resultados) {
                try {
                    $("#listado_aprendices tbody").empty();
                    dataListado = JSON.parse(resultados);
                    if (dataListado.resultados) {
                        for (var i in dataListado.resultados) {
                            let contenido = "";
                            contenido +=
                                '<tr class="border-bottom text-secondary">';
                            contenido +=
                                '<td class="text-right py-2 px-1">' +
                                dataListado.resultados[i].numero +
                                "</td>";

                            let yearR = dataListado.resultados[i].fecha.substr(
                                0,
                                4
                            );
                            let monthR = dataListado.resultados[i].fecha.substr(
                                5,
                                2
                            );
                            let dayR = dataListado.resultados[i].fecha.substr(
                                8,
                                2
                            );

                            contenido +=
                                '<td class="py-2 px-1">' +
                                dayR +
                                "-" +
                                monthR +
                                "-" +
                                yearR +
                                "</td>";
                            contenido +=
                                '<td class="py-2 px-1">' +
                                dataListado.resultados[i].nacionalidad +
                                "-" +
                                dataListado.resultados[i].cedula +
                                "</td>";

                            let nombre_completo =
                                dataListado.resultados[i].nombre1;
                            if (dataListado.resultados[i].nombre2 != null)
                                nombre_completo +=
                                    " " +
                                    dataListado.resultados[i].nombre2.substr(
                                        0,
                                        1
                                    ) +
                                    ".";
                            nombre_completo +=
                                " " + dataListado.resultados[i].apellido1;
                            if (dataListado.resultados[i].apellido2 != null)
                                nombre_completo +=
                                    " " +
                                    dataListado.resultados[i].apellido2.substr(
                                        0,
                                        1
                                    ) +
                                    ".";
                            contenido +=
                                '<td class="py-2 px-1">' +
                                nombre_completo +
                                "</td>";

                            let year = dataListado.resultados[i].fecha_n.substr(
                                0,
                                4
                            );
                            let month = dataListado.resultados[
                                i
                            ].fecha_n.substr(5, 2);
                            let day = dataListado.resultados[i].fecha_n.substr(
                                8,
                                2
                            );
                            let yearA = fecha.substr(0, 4);
                            let monthA = fecha.substr(5, 2);
                            let dayA = fecha.substr(8, 2);

                            let edad = 0;
                            if (year != "" && year != undefined) {
                                if (year <= yearA) {
                                    edad = yearA - year;
                                    if (month > monthA) {
                                        if (edad != 0) edad--;
                                    } else if (month == monthA) {
                                        if (day > dayA) if (edad != 0) edad--;
                                    }
                                }
                            }

                            contenido +=
                                '<td class="text-center py-2 px-1">' +
                                day +
                                "-" +
                                month +
                                "-" +
                                year +
                                "</td>";
                            contenido +=
                                '<td class="text-center py-2 px-1">' +
                                edad +
                                "</td>";
                            contenido +=
                                '<td class="py-2 px-1">' +
                                dataListado.resultados[i].oficio +
                                "</td>";
                            contenido +=
                                '<td class="py-2 px-1">' +
                                dataTurno[dataListado.resultados[i].turno] +
                                "</td>";
                            contenido +=
                                '<td class="text-center py-2 px-1">' +
                                estatus +
                                "</td>";
                            contenido += '<td class="py-1 px-1">';
                            if (permisos.modificar == 1)
                                contenido +=
                                    '<button type="button" class="btn btn-sm btn-info editar_registro mr-1" data-posicion="' +
                                    i +
                                    '"><i class="fas fa-pencil-alt"></i></button>';
                            contenido +=
                                '<div class="dropdown d-inline-block"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v px-1"></i></button>';
                            contenido +=
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                            if (permisos.act_desc == 1) {
                                contenido +=
                                    '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1"><i class="fas fa-check text-center" style="width:20px;"></i><span class="ml-2">Aceptar</span></a></li>';
                                contenido +=
                                    '<li class="dropdown-item p-0"><a href="#" class="d-inline-block w-100 p-1"><i class="fas fa-times text-center" style="width:20px;"></i><span class="ml-2">Rechazar</span></a></li>';
                            }
                            contenido +=
                                '<li class="dropdown-item p-0"><a href="' +
                                url +
                                "controllers/r_informe_social?numero=" +
                                dataListado.resultados[i].numero +
                                '" target="_blank" class="d-inline-block w-100 p-1"><i class="fas fa-print text-center" style="width:20px;"></i><span class="ml-2">Imprimir</span></a></li>';
                            contenido += "</div></div></td></tr>";
                            $("#listado_aprendices tbody").append(contenido);
                        }
                        $(".editar_registro").click(editarInforme);
                    } else {
                        $("#listado_aprendices tbody").append(
                            '<tr><td colspan="10" class="text-center text-secondary border-bottom p-2"><i class="fas fa-file-alt mr-3"></i>No hay informes registrados</td></tr>'
                        );
                    }

                    $("#total_registros").html(dataListado.total);
                    establecer_tabla(
                        numeroDeLaPagina,
                        parseInt($("#cantidad_a_buscar").val()),
                        dataListado.total
                    );
                    $(".mover").click(cambiarPagina);
                } catch (error) {
                    console.log(resultados);
                }
                window.actualizar_busqueda = false;
            },
            error: function() {
                console.log("error");
            },
            timer: 15000
        });
    }
    function cambiarPagina(e) {
        e.preventDefault();
        let numero = $(this).attr("data-pagina");
        numeroDeLaPagina = parseInt(numero);
        buscar_listado();
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    $("#fecha_n").change(function() {
        let year = $(this)
            .val()
            .substr(0, 4);
        let month = $(this)
            .val()
            .substr(5, 2);
        let day = $(this)
            .val()
            .substr(8, 2);
        let yearA = fecha.substr(0, 4);
        let monthA = fecha.substr(5, 2);
        let dayA = fecha.substr(8, 2);

        let edad = 0;
        if (year != "" && year != undefined) {
            if (year <= yearA) {
                edad = yearA - year;
                if (month > monthA) {
                    if (edad != 0) edad--;
                } else if (month == monthA) {
                    if (day > dayA) if (edad != 0) edad--;
                }
            }
        }

        // if (edad > 16 && edad < 19) {
        //     alert8
        // }
        $("#edad").val(edad);
    });

    ///////////////////////////////////////// ESTADO

    $("#estado").change(function() {
        if (window.actualizar !== true) {
            localStorage.removeItem("ciudad");
            localStorage.removeItem("municipio");
            localStorage.removeItem("parroquia");
        }

        if ($(this).val() != "") {
            $.ajax({
                url: url + "controllers/c_informe_social.php",
                type: "POST",
                data: { opcion: "Traer divisiones", estado: $(this).val() },
                success: function(resultados) {
                    $("#ciudad").empty();
                    $("#municipio").empty();
                    try {
                        let data = JSON.parse(resultados);
                        if (data.ciudad) {
                            $("#ciudad").append(
                                '<option value="">Elija una opción</option>'
                            );
                            for (let i in data.ciudad) {
                                $("#ciudad").append(
                                    '<option value="' +
                                        data.ciudad[i].codigo +
                                        '">' +
                                        data.ciudad[i].nombre +
                                        "</option>"
                                );
                            }
                        } else {
                            $("#ciudad").html(
                                '<option value="">No hay ciudades</option>'
                            );
                        }
                        if (data.municipio) {
                            $("#municipio").append(
                                '<option value="">Elija una opción</option>'
                            );
                            for (let i in data.municipio) {
                                $("#municipio").append(
                                    '<option value="' +
                                        data.municipio[i].codigo +
                                        '">' +
                                        data.municipio[i].nombre +
                                        "</option>"
                                );
                            }
                        } else {
                            $("#municipio").html(
                                '<option value="">No hay municipios</option>'
                            );
                        }

                        if (
                            window.actualizar === true ||
                            window.selectCiudad === true
                        ) {
                            window.actualizar = false;
                            window.selectCiudad = false;

                            let ciudadValor = "";
                            let municipioValor = "";
                            if (window.editar !== true) {
                                ciudadValor = localStorage.getItem("ciudad");
                                municipioValor = localStorage.getItem(
                                    "municipio"
                                );
                                window.actualizar2 = true;
                            } else {
                                ciudadValor =
                                    dataListado.resultados[window.posicion]
                                        .codigo_ciudad;
                                municipioValor =
                                    dataListado.resultados[window.posicion]
                                        .codigo_municipio;
                                window.selectMunicipio = true;
                            }

                            $("#ciudad").val(ciudadValor);
                            $("#municipio").val(municipioValor);
                            $("#municipio").trigger("change");
                        }
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function() {
                    console.log("error");
                }
            });
        } else {
            window.actualizar = false;
            $("#ciudad").html('<option value="">Elija un estado</option>');
            $("#municipio").html('<option value="">Elija un estado</option>');
        }
        $("#parroquia").html('<option value="">Elija un municipio</option>');
    });

    //////////////////////////////////////////MUNICIPIO
    $("#municipio").change(function() {
        if (window.actualizar2 !== true) {
            localStorage.removeItem("parroquia");
        }

        if ($(this).val() != "") {
            $.ajax({
                url: url + "controllers/c_informe_social.php",
                type: "POST",
                data: { opcion: "Traer parroquias", municipio: $(this).val() },
                success: function(resultados) {
                    $("#parroquia").empty();
                    try {
                        let data = JSON.parse(resultados);
                        if (data.parroquia) {
                            $("#parroquia").append(
                                '<option value="">Elija una opción</option>'
                            );
                            for (let i in data.parroquia) {
                                $("#parroquia").append(
                                    '<option value="' +
                                        data.parroquia[i].codigo +
                                        '">' +
                                        data.parroquia[i].nombre +
                                        "</option>"
                                );
                            }
                        } else {
                            $("#parroquia").html(
                                '<option value="">No hay parroquias</option>'
                            );
                        }

                        if (
                            window.actualizar2 === true ||
                            window.selectMunicipio === true
                        ) {
                            window.actualizar2 = false;
                            window.selectMunicipio = false;

                            let parroquiValor = "";
                            if (window.editar !== true) {
                                parroquiValor = localStorage.getItem(
                                    "parroquia"
                                );
                            } else {
                                parroquiValor =
                                    dataListado.resultados[window.posicion]
                                        .codigo_parroquia;
                            }
                            $("#parroquia").val(parroquiValor);
                        }
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function() {
                    console.log("error");
                }
            });
        } else {
            window.actualizar2 = false;
            $("#parroquia").html(
                '<option value="">Elija un municipio</option>'
            );
        }
    });

    /////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    $("#show_form").click(function() {
        $("#info_table").hide(400);
        $("#gestion_form").show(400);
        $("#form_title").html("Registrar");
        $("#carga_espera").hide();
        tipoEnvio = "Registrar";
        /////////////////////
        limpiarFormulario();
        if (localStorage.getItem("confirm_data")) {
            setTimeout(() => {
                if (
                    confirm(
                        "Hay datos sin guardar, ¿Quieres seguir editandolos?"
                    )
                ) {
                    $(".localStorage").each(function() {
                        let valor = localStorage.getItem($(this).attr("id"));
                        if (valor != "" && valor != null && valor != undefined)
                            $(this).val(
                                localStorage.getItem($(this).attr("id"))
                            );
                    });
                    $(".localStorage-radio").each(function() {
                        if (
                            $(this).val() ==
                            localStorage.getItem($(this).attr("name"))
                        )
                            $(this).prop("checked", "checked");
                    });

                    $(".trabajando").trigger("change");
                    if (localStorage.getItem("responsable_apre"))
                        document.formulario.responsable_apre.value = localStorage.getItem(
                            "responsable_apre"
                        );

                    window.actualizar3 = false;

                    window.actualizar = true;
                    $("#estado").trigger("change");
                    $("#fecha_n").trigger("change");
                } else {
                    localStorage.removeItem("confirm_data");
                    $(".localStorage").each(function() {
                        localStorage.removeItem($(this).attr("name"));
                    });
                    $(".localStorage-radio").each(function() {
                        localStorage.removeItem($(this).attr("name"));
                    });
                }
            }, 500);
        }
    });
    $("#show_table").click(function() {
        $("#info_table").show(400);
        $("#gestion_form").hide(400);
        /////////////////////
        window.editar = false;
        /////////////////////
        $("#pills-datos-ciudadano-tab").tab("show");
        /////////////////////
        localStorage.removeItem("confirm_data");
        $(".localStorage").each(function() {
            localStorage.removeItem($(this).attr("name"));
        });
        $(".localStorage-radio").each(function() {
            localStorage.removeItem($(this).attr("name"));
        });
    });
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // FUNCION PARA ABRIR EL FORMULARIO Y PODER EDITAR LA INFORMACION.
    function editarInforme() {
        let posicion = $(this).attr("data-posicion");
        window.posicion = posicion;
        if (localStorage.getItem("confirm_data")) {
            if (
                confirm(
                    "Hay datos que no se han guardado, si prosigues se perderán. ¿Quieres proseguir?"
                )
            ) {
                editarF();
                /////////////////////
                localStorage.removeItem("confirm_data");
                $(".localStorage").each(function() {
                    localStorage.removeItem($(this).attr("name"));
                });
                $(".localStorage-radio").each(function() {
                    localStorage.removeItem($(this).attr("name"));
                });

                for (let index = 1; index <= maxFamiliares; index++) {
                    localStorage.removeItem("filaFamilia" + index);
                }
            }
        } else {
            editarF();
        }

        function editarF() {
            window.editar = true;
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
            $.ajax({
                url: url + "controllers/c_informe_social.php",
                type: "POST",
                data: {
                    opcion: "Consultar determinado",
                    informe: dataListado.resultados[posicion].numero,
                    nacionalidad: dataListado.resultados[posicion].nacionalidad,
                    cedula: dataListado.resultados[posicion].cedula
                },
                success: function(resultados) {
                    try {
                        let data = JSON.parse(resultados);

                        window.informe_social =
                            dataListado.resultados[posicion].numero;
                        window.nacionalidad =
                            dataListado.resultados[posicion].nacionalidad;
                        window.cedula = dataListado.resultados[posicion].cedula;
                        // PRIMERA PARTE.
                        $("#fecha").val(dataListado.resultados[posicion].fecha);
                        $("#nacionalidad").val(
                            dataListado.resultados[posicion].nacionalidad
                        );
                        $("#cedula").val(
                            dataListado.resultados[posicion].cedula
                        );
                        $("#nombre_1").val(
                            dataListado.resultados[posicion].nombre1
                        );
                        $("#nombre_2").val(
                            dataListado.resultados[posicion].nombre2
                        );
                        $("#apellido_1").val(
                            dataListado.resultados[posicion].apellido1
                        );
                        $("#apellido_2").val(
                            dataListado.resultados[posicion].apellido2
                        );
                        $("#sexo").val(dataListado.resultados[posicion].sexo);
                        $("#fecha_n").val(
                            dataListado.resultados[posicion].fecha_n
                        );
                        $("#fecha_n").trigger("change");
                        $("#lugar_n").val(
                            dataListado.resultados[posicion].lugar_n
                        );
                        $("#ocupacion").val(
                            dataListado.resultados[posicion].codigo_ocupacion
                        );
                        document.formulario.estado_civil.value =
                            dataListado.resultados[posicion].estado_civil;

                        document.formulario.grado_instruccion.value =
                            dataListado.resultados[posicion].nivel_instruccion;

                        $("#alguna_mision").val(
                            dataListado.resultados[posicion].mision_participado
                        );
                        $("#telefono_1").val(
                            dataListado.resultados[posicion].telefono1
                        );

                        $("#correo").val(
                            dataListado.resultados[posicion].correo
                        );
                        $("#oficio").val(
                            dataListado.resultados[posicion].codigo_oficio
                        );

                        // SEGUNDA PARTE.
                        $("#estado").val(
                            dataListado.resultados[posicion].codigo_estado
                        );
                        window.selectCiudad = true;
                        $("#estado").trigger("change");
                        $("#area").val(data.vivienda.tipo_area);
                        $("#direccion").val(
                            dataListado.resultados[posicion].direccion
                        );
                        $("#punto_referencia").val(
                            data.vivienda.punto_referencia
                        );

                        $("#carga_espera").hide(400);
                    } catch (error) {
                        console.log(resultados);
                    }
                },
                error: function() {
                    console.log("error");
                }
            });
        }
    }

    // FUNCION PARA GUARDAR LOS DATOS (REGISTRAR / MODIFICAR).
    $("#guardar_datos").click(function(e) {
        e.preventDefault();
        var data = $("#formulario").serializeArray();
        data.push({ name: "opcion", value: tipoEnvio });
        data.push({
            name: "estado_civil",
            value: document.formulario.estado_civil.value
        });

        ///////////////////
        data.push({ name: "informe_social", value: window.informe_social });
        data.push({ name: "nacionalidad_v", value: window.nacionalidad });
        data.push({ name: "cedula_v", value: window.cedula });
        data.push({
            name: "eliminar_f",
            value: JSON.stringify(window.eliminarFamiliar)
        });

        $.ajax({
            url: url + "controllers/c_informe_social.php",
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
            }
        });
    });
    // FUNCION PARA GUARDAR LOS DATOS DEL APRENDIZ EN LOCALSTORAGE.
    $(".localStorage").keyup(guardarLocalStorage);
    $(".localStorage").change(guardarLocalStorage);
    $(".localStorage-radio").click(guardarLocalStorage);
    function guardarLocalStorage() {
        if (window.editar !== true) {
            localStorage.setItem("confirm_data", true);
            localStorage.setItem($(this).attr("name"), $(this).val());
        }
    }
    // FUNCION PARA LIMPIAR EL FORMULARIO DE LOS DATOS ANTERIORES.
    function limpiarFormulario() {
        document.formulario.reset();
        $("#fecha").val(fecha);
        window.tabla = true;
        $("#tabla_datos_familiares tbody").html(
            '<tr><td colspan="11" class="text-secondary text-center border-bottom p-2">Sin familiares agregados<i class="fas fa-user-times ml-3"></i></td></tr>'
        );
        //////////
        $("#edad").val(0);
        //////////
        $("#ciudad").html('<option value="">Elija un estado</option>');
        $("#municipio").html('<option value="">Elija un estado</option>');
        $("#parroquia").html('<option value="">Elija un municipio</option>');
        //////////
    }
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    // AUTOLLAMADOS.
    function llamarDatos() {
        $.ajax({
            url: url + "controllers/c_informe_social.php",
            type: "POST",
            data: { opcion: "Traer datos" },
            success: function(resultados) {
                try {
                    let data = JSON.parse(resultados);
                    fecha = data.fecha;
                    if (data.ocupacion) {
                        for (let i in data.ocupacion) {
                            $("#ocupacion").append(
                                '<option value="' +
                                    data.ocupacion[i].codigo +
                                    '">' +
                                    data.ocupacion[i].nombre +
                                    "</option>"
                            );
                        }
                        dataOcupacion = data.ocupacion;
                    } else {
                        $("#ocupacion").html(
                            '<option value="">No hay ocupaciones</option>'
                        );
                    }

                    $("#radios_oficios").empty();
                    if (data.oficio) {
                        let contenido_oficio = "";
                        contenido_oficio +=
                            '<div class="custom-control custom-radio">';
                        contenido_oficio +=
                            '<input type="radio" id="oficio_asd_0" name="buscar_oficio" class="custom-control-input" value="" checked>';
                        contenido_oficio +=
                            '<label class="custom-control-label" for="oficio_asd_0">Todos</label>';
                        contenido_oficio += "</div>";

                        $("#radios_oficios").append(contenido_oficio);
                        for (let i in data.oficio) {
                            $("#oficio").append(
                                '<option value="' +
                                    data.oficio[i].codigo +
                                    '">' +
                                    data.oficio[i].nombre +
                                    "</option>"
                            );

                            let contenido_oficio = "";
                            contenido_oficio +=
                                '<div class="custom-control custom-radio">';
                            contenido_oficio +=
                                '<input type="radio" id="oficio_asd_' +
                                data.oficio[i].codigo +
                                '" name="buscar_oficio" class="custom-control-input" value="' +
                                data.oficio[i].codigo +
                                '">';
                            contenido_oficio +=
                                '<label class="custom-control-label" for="oficio_asd_' +
                                data.oficio[i].codigo +
                                '">' +
                                data.oficio[i].nombre +
                                "</label>";
                            contenido_oficio += "</div>";
                            $("#radios_oficios").append(contenido_oficio);
                        }
                    } else {
                        $("#oficio").html(
                            '<option value="">No hay oficios</option>'
                        );

                        let contenido_oficio = "";
                        contenido_oficio +=
                            '<div class="custom-control custom-radio">';
                        contenido_oficio +=
                            '<input type="radio" id="oficio_asd_0" name="buscar_oficio" class="custom-control-input" value="" checked>';
                        contenido_oficio +=
                            '<label class="custom-control-label" for="oficio_asd_0">Todos</label>';
                        contenido_oficio += "</div>";
                        $("#radios_oficios").append(contenido_oficio);
                    }
                    if (data.estado) {
                        for (let i in data.estado) {
                            $("#estado").append(
                                '<option value="' +
                                    data.estado[i].codigo +
                                    '">' +
                                    data.estado[i].nombre +
                                    "</option>"
                            );
                        }
                    } else {
                        $("#estado").html(
                            '<option value="">No hay estados</option>'
                        );
                    }

                    buscar_listado();
                } catch (error) {}
            },
            error: function() {
                console.log("error");
            }
        });

        $("#radios_turnos").empty();

        let contenido_oficio = "";
        contenido_oficio += '<div class="custom-control custom-radio">';
        contenido_oficio +=
            '<input type="radio" id="turno_asd_0" name="buscar_turno" class="custom-control-input" value="" checked>';
        contenido_oficio +=
            '<label class="custom-control-label" for="turno_asd_0">Todos</label>';
        contenido_oficio += "</div>";

        $("#radios_turnos").append(contenido_oficio);
        for (let i in dataTurno) {
            let contenido_oficio = "";
            contenido_oficio += '<div class="custom-control custom-radio">';
            contenido_oficio +=
                '<input type="radio" id="turno_asd_' +
                i +
                '" name="buscar_turno" class="custom-control-input" value="">';
            contenido_oficio +=
                '<label class="custom-control-label" for="turno_asd_' +
                i +
                '">' +
                dataTurno[i] +
                "</label>";
            contenido_oficio += "</div>";

            $("#radios_turnos").append(contenido_oficio);
        }
    }
    llamarDatos();
});
