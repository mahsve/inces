<div id="info_table">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-secondary text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <?php if ($permisos['registrar'] == 1){ ?>
            <button type="button" id="show_form" class="botones_formulario btn btn-sm btn-info" disabled="true"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
        <?php } ?>
    </div>

    <div class="row align-items-center justify-content-between">
        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-9">
            <div class="form-row">
                <div class="form-group col-sm-6 col-lg-3 col-xl-2 d-flex align-items-center text-info mb-2">
                    <label for="campo_cantidad" class="pr-2 m-0"><i class="fas fa-list-ul"></i></label>
                    <select id="campo_cantidad" class="campos_de_busqueda custom-select custom-select-sm">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="form-group col-sm-6 col-lg-3 col-xl-3 d-flex align-items-center text-info mb-2">
                    <label for="campo_ordenar" class="pr-2 m-0"><i class="fas fa-sort-alpha-down"></i></label>
                    <select id="campo_ordenar" class="campos_de_busqueda custom-select custom-select-sm">
                        <option value="1">Cédula</option>
                        <option value="2">Nombre</option>
                        <option value="3">Fecha Reg.</option>
                    </select>
                </div>

                <div class="form-group col-sm-6 col-lg-3 col-xl-3 d-flex align-items-center text-info mb-2">
                    <label for="campo_manera_ordenar" class="pr-2 m-0"><i class="fas fa-sort-numeric-down"></i></label>
                    <select id="campo_manera_ordenar" class="campos_de_busqueda custom-select custom-select-sm">
                        <option value="1">Ascendente</option>
                        <option value="2">Descendente</option>
                    </select>
                </div>

                <div class="form-group col-sm-6 col-lg-3 col-xl-2 d-flex align-items-center text-info mb-2">
                    <label for="campo_estatus" class="pr-2 m-0"><i class="fas fa-toggle-on"></i></label>
                    <select id="campo_estatus" class="campos_de_busqueda custom-select custom-select-sm">
                        <option value="">Todos</option>
                        <option value="A">Aceptados</option>
                        <option value="R">Rechazados</option>
                        <option value="E">En espera</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="form-group col-sm-12 col-xl-3 d-flex align-items-center text-info position-relative mb-2">
            <label for="campo_busqueda" class="position-absolute pr-4 m-0" style="right: 0px; cursor: pointer;"><i class="fas fa-search"></i></label>
            <input type="text" id="campo_busqueda" class="campos_de_busqueda form-control form-control-sm" style="padding-right: 30px;" placeholder="Buscar por cédula o por nombre" autocomplete="off"/>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="90">Fecha</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha Nac.</th>
                    <th class="bg-info font-weight-normal px-1 py-2 text-center" width="45">Edad</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="150">Oficio</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="85">Turno</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="92">Estatus</th>
                    <th class="bg-info rounded-right p-0 py-1" width="<?php if ($permisos['modificar'] == 1) echo 82; else echo 40; ?>"></th>
                </tr>
            </thead>
            
            <tbody>
                <!-- JAVASCRIPT -->
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 align-self-center">
            <p class="font-weight-bold text-secondary m-0" style="font-size: 13px;">Total registros <span id="total_registros">0</span> </p>
        </div>

        <div class="col-sm-12 col-md-6 align-self-center">
            <nav aria-label="Page navigation"><ul id="paginacion" class="pagination pagination-sm justify-content-end mb-0"></ul></nav>
        </div>
        
        <div id="contenedor-mensaje" class="col-sm-12"></div>
    </div>
</div>

<?php if ($permisos['registrar'] == 1 OR $permisos['modificar']){ ?>
    <div id="gestion_form" style="display: none;">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-secondary text-uppercase font-weight-normal mb-0"></h4>
            <button type="button" id="show_table" class="botones_formulario btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form name="formulario" id="formulario" class="formulario">
            <!-- INPUT DE FECHA DE REGISTRO -->
            <div class="form-row justify-content-end">
                <div class="col-sm-6 col-lg-3 col-xl-2 mb-2">
                    <div class="form-group position-relative mb-2">
                        <label for="fecha" class="d-inline-block w-100 position-relative small m-0">Fecha<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <input type="text" name="fecha" id="fecha" class="campos_formularios input_fecha form-control form-control-sm" style="background-color: white; padding-right: 30px;" data-date-format="dd-mm-yyyy" placeholder="dd-mm-aaaa" readonly="true"/>
                        <label for="fecha" class="position-absolute text-info m-0" style="bottom: 4px; right: 8px; cursor: pointer;"><i class="fas fa-calendar-day"></i></label>
                    </div>
                </div>
            </div>
            <!-- FIN INPUT DE FECHA DE REGISTRO -->

            <!-- BOTONES PESTAÑAS -->
            <ul class="nav nav-pills mb-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-datos-ciudadano-tab" data-toggle="pill" href="#pills-datos-ciudadano" role="tab" aria-controls="pills-datos-ciudadano" aria-selected="true">
                        <i class="fas fa-user-graduate"></i><span class="ml-1">Aprendiz</span><i id="icon-ciudadano" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-ubicacion-tab" data-toggle="pill" href="#pills-datos-ubicacion" role="tab" aria-controls="pills-datos-ubicacion" aria-selected="false">
                        <i class="fas fa-map-marked-alt"></i><span class="ml-1">Contacto y ubicación</span><i id="icon-ubicacion" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-vivienda-tab" data-toggle="pill" href="#pills-datos-vivienda" role="tab" aria-controls="pills-datos-vivienda" aria-selected="false">
                        <i class="fas fa-hotel"></i><span class="ml-1">Vivienda</span><i id="icon-vivienda" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-familiares-tab" data-toggle="pill" href="#pills-datos-familiares" role="tab" aria-controls="pills-datos-familiares" aria-selected="false">
                        <i class="fas fa-users"></i><span class="ml-1">Familiares</span><i id="icon-familiares" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-ingresos-tab" data-toggle="pill" href="#pills-datos-ingresos" role="tab" aria-controls="pills-datos-ingresos" aria-selected="false">
                        <i class="fas fa-money-bill-alt"></i><span class="ml-1">Ingresos</span><i id="icon-ingresos" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-recomendaciones-tab" data-toggle="pill" href="#pills-datos-recomendaciones" role="tab" aria-controls="pills-datos-recomendaciones" aria-selected="false">
                        <i class="fas fa-file-signature"></i><span class="ml-1">Recomendación</span><i id="icon-recomendaciones" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
            </ul>
            <!-- FIN BOTONES PESTAÑAS -->

            <!-- CONTENEDORES TABLAS -->
            <div class="tab-content border rounded position-relative">
                <div id="pills-datos-ciudadano" class="tab-pane fade px-3 py-2 show active" role="tabpanel" aria-labelledby="pills-datos-ciudadano-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos personales</h3>
                        </div>

                        <!-- NACIONALIDAD -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nacionalidad" class="d-inline-block w-100 position-relative small m-0">Nacionalidad<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="nacionalidad" id="nacionalidad" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                    <option value="V">Venezolano</option>
                                    <option value="E">Extranjero</option>
                                </select>
                            </div>
                        </div>

                        <!-- CEDULA -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">Cédula
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="spinner-cedula" class="fas fa-spinner fa-spin position-absolute ocultar-iconos" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="spinner-cedula-confirm" class="fas position-absolute ocultar-iconos limpiar-estatus" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-cedula-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <input type="text" name="cedula" id="cedula" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="Ingrese la cédula" maxlength="8" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 1 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nombre_1" class="d-inline-block w-100 position-relative small m-0">Primer nombre<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="nombre_1" id="nombre_1" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el nombre" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 2 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nombre_2" class="d-inline-block w-100 position-relative small m-0">Segundo nombre</label>
                                <input type="text" name="nombre_2" id="nombre_2" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el nombre (Opcional)" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 1 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="apellido_1" class="d-inline-block w-100 position-relative small m-0">Primer Apellido<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="apellido_1" id="apellido_1" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el apellido" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 2 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="apellido_2" class="d-inline-block w-100 position-relative small m-0">Segundo Apellido</label>
                                <input type="text" name="apellido_2" id="apellido_2" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el apellido (Opcional)" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="sexo" class="d-inline-block w-100 position-relative small m-0">Sexo<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="sexo" id="sexo" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <!-- FECHA DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group position-relative mb-2">
                                <label for="fecha_n" class="d-inline-block w-100 position-relative small m-0">Fecha de nacimiento<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="fecha_n" id="fecha_n" class="campos_formularios input_fecha form-control form-control-sm" style="background-color: white; padding-right: 30px;" data-date-format="dd-mm-yyyy" placeholder="dd-mm-aaaa" readonly="true"/>
                                <label for="fecha_n" class="position-absolute text-info m-0" style="bottom: 4px; right: 8px; cursor: pointer;"><i class="fas fa-calendar-day"></i></label>
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-sm-2 col-lg-1 col-xl-1">
                            <div class="form-group mb-2">
                                <label for="edad" class="d-inline-block w-100 position-relative small m-0">Edad</label>
                                <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center" value="0" readonly="true"/>
                            </div>
                        </div>

                        <!-- ESTADO DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">Estado de nacimiento
                                    <i id="loader-ciudad_n" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-ciudad_n-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <select name="estado_n" id="estado_n" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- CIUDAD DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="ciudad_n" class="d-inline-block w-100 position-relative small m-0">Ciudad de nacimiento</label>
                                <select name="ciudad_n" id="ciudad_n" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- LUGAR DE NACIMIENTO -->
                        <div class="col-sm-10 col-lg-4">
                            <div class="form-group mb-2">
                                <label for="lugar_n" class="d-inline-block w-100 position-relative small m-0">Lugar de nacimiento</label>
                                <input type="text" name="lugar_n" id="lugar_n" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el lugar de nacimiento (Opcional)" maxlength="100" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- OCUPACION -->
                        <div class="col-sm-12 col-lg-6 col-xl-5">
                            <div class="form-group mb-2">
                                <label for="ocupacion" class="d-inline-block w-100 position-relative small m-0">Ocupación<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <div class="d-flex">
                                    <select name="ocupacion" id="ocupacion" class="campos_formularios custom-select custom-select-sm">
                                        <option value="">Elija una opción</option>
                                    </select>
                                    <button type="button" id="btn-ocupacion-aprendiz" class="btn btn-sm btn-info descripcion-tooltip ml-1" data-toggle="tooltip" data-placement="left" title="Registrar ocupación"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-4">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Estatus del aprendiz</h4>    
                        </div>

                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 mt-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 850px;">
                                    <tr>
                                        <!-- ESTADO CIVIL -->
                                        <td class="align-text-top w-25 p-2">
                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Estado civil<i class="fas fa-asterisk text-danger position-absolute required"></i></span>

                                            <!-- INPUT RADIOS -->
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_s" name="estado_civil" class="custom-control-input" value="S">
                                                <label class="custom-control-label radio_estado_civil_label" for="estado_civil_s">Soltero</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_c" name="estado_civil" class="custom-control-input" value="C">
                                                <label class="custom-control-label radio_estado_civil_label" for="estado_civil_c">Casado</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_x" name="estado_civil" class="custom-control-input" value="X">
                                                <label class="custom-control-label radio_estado_civil_label" for="estado_civil_x">Concubino</label>
                                            </div>
                                            
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_d" name="estado_civil" class="custom-control-input" value="D">
                                                <label class="custom-control-label radio_estado_civil_label" for="estado_civil_d">Divorciado</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_v" name="estado_civil" class="custom-control-input" value="V">
                                                <label class="custom-control-label radio_estado_civil_label" for="estado_civil_v">Viudo</label>
                                            </div>
                                            <!-- FIN INPUT RADIOS -->
                                        </td>

                                        <!-- GRADO DE INSTRUCCION -->
                                        <td class="align-text-top w-75 p-2">
                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Grado de instrucción<i class="fas fa-asterisk text-danger position-absolute required"></i></span>
                                            
                                            <!-- INPUT RADIOS -->
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                <input type="radio" id="grado_instruccion_bi" name="grado_instruccion" class="custom-control-input" value="BI">
                                                <label class="custom-control-label radio_educacion_label" for="grado_instruccion_bi">Educ. básica incompleta</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                <input type="radio" id="grado_instruccion_bc" name="grado_instruccion" class="custom-control-input" value="BC">
                                                <label class="custom-control-label radio_educacion_label" for="grado_instruccion_bc">Educ. básica completa</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                <input type="radio" id="grado_instruccion_mi" name="grado_instruccion" class="custom-control-input" value="MI">
                                                <label class="custom-control-label radio_educacion_label" for="grado_instruccion_mi">Educ. media diversificada incompleta</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                <input type="radio" id="grado_instruccion_mc" name="grado_instruccion" class="custom-control-input" value="MC">
                                                <label class="custom-control-label radio_educacion_label" for="grado_instruccion_mc">Educ. media diversificada completa</label>
                                            </div>
                                            <!-- FIN INPUT RADIOS -->

                                            <!-- DESCRIPCION TITULOS -->
                                            <div class="form-group form-row align-items-center mt-2 mb-0">
                                                <label for="titulo" class="d-inline-block col-sm-4 position-relative small m-0">Título</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="titulo" id="titulo" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el título académico (Opcional)" maxlength="100" autocomplete="off"/>
                                                </div>
                                            </div>

                                            <div class="form-group form-row align-items-center mt-2 mb-0">
                                                <label for="alguna_mision" class="d-inline-block col-sm-4 position-relative small m-0">Ha participado en alguna  misión. Indique</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="alguna_mision" id="alguna_mision" class="campos_formularios form-control form-control-sm" placeholder="Ingrese las misiones en las que participo (Opcional)" maxlength="100" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <!-- FIN DESCRIPCION TITULOS -->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-ubicacion" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-ubicacion-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos de contacto</h3>    
                        </div>

                        <!-- TELEFONO DE HABITACION -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="d-inline-block w-100 position-relative small m-0">Tlf. de habitación</label>
                                <input type="text" name="telefono_1" id="telefono_1" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="Ingrese el telefono (Opcional)" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO CELULAR -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_2" class="d-inline-block w-100 position-relative small m-0">Tlf. celular</label>
                                <input type="text" name="telefono_2" id="telefono_2" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="Ingrese el telefono (Opcional)" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="correo" class="d-inline-block w-100 position-relative small m-0">Correo</label>
                                <input type="text" name="correo" id="correo" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el correo (Opcional)" maxlength="80" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TURNO DE ESTUDIO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="turno" class="d-inline-block w-100 position-relative small m-0">Turno<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="turno" id="turno" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                    <option value="M">Matutino</option>
                                    <option value="V">Vespertino</option>
                                </select>
                            </div>
                        </div>

                        <!-- SALIDA OCUPACIONAL (OFICIO O CARRERA) -->
                        <div class="col-sm-12 col-lg-6 col-xl-5">
                            <div class="form-group mb-2">
                                <label for="oficio" class="d-inline-block w-100 position-relative small m-0">Salida ocupacional<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="oficio" id="oficio" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-4">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Ubicación geográfica de la vivienda</h4>    
                        </div>

                        <!-- ESTADO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">Estado
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="loader-ciudad" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-ciudad-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <select name="estado" id="estado" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="ciudad" class="d-inline-block w-100 position-relative small m-0">Ciudad<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="ciudad" id="ciudad" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- MUNICIPIO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">Municipio
                                    <i id="loader-parroquia" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-parroquia-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <select name="municipio" id="municipio" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- PARROQUIA -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="parroquia" class="d-inline-block w-100 position-relative small m-0">Parroquia</label>
                                <select name="parroquia" id="parroquia" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un municipio</option>
                                </select>
                            </div>
                        </div>

                        <!-- TIPO DE AREÁ -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="area" class="d-inline-block w-100 position-relative small m-0">Área<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="area" id="area" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                    <option value="R">Rural</option>
                                    <option value="U">Urbana</option>
                                </select>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12 col-xl-6">
                            <div class="form-group mb-2">
                                <label for="direccion" class="d-inline-block w-100 position-relative small m-0">Dirección<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <textarea type="text" name="direccion" id="direccion" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la dirección del hogar" autocomplete="off" maxlength="200" style="height: 100px;"></textarea>
                            </div>
                        </div>

                        <!-- PUNTO DE REFERENCIA -->
                        <div class="col-sm-12 col-xl-6">
                            <div class="form-group mb-2">
                                <label for="punto_referencia" class="d-inline-block w-100 position-relative small m-0">Punto de referencia<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <textarea type="text" name="punto_referencia" id="punto_referencia" class="campos_formularios form-control form-control-sm" placeholder="Ingrese punto de refencia" autocomplete="off" maxlength="200" style="height: 100px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-vivienda" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-vivienda-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Características de la vivienda</h3>
                        </div>

                        <!-- TIPO DE VIVIENDA, TENENCIA Y SERVICIOS PUBLICOS. -->
                        <div class="col-sm-12 mt-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 850px;">
                                    <tr>
                                        <!-- TIPO DE VIVIENDA -->
                                        <td class="align-text-top p-2" rowspan="2" style="width: 20%;">
                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Tipo de vivienda en la que habita  actualmente</span>

                                            <!-- INPUT RADIO -->
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_q" class="custom-control-input" value="Q">
                                                <label class="custom-control-label radio_tipo_vivienda_label" for="tipo_vivienda_q">Quinta</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_c" class="custom-control-input" value="C">
                                                <label class="custom-control-label radio_tipo_vivienda_label" for="tipo_vivienda_c">Casa</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_a" class="custom-control-input" value="A">
                                                <label class="custom-control-label radio_tipo_vivienda_label" for="tipo_vivienda_a">Apartamento</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_r" class="custom-control-input" value="R">
                                                <label class="custom-control-label radio_tipo_vivienda_label" for="tipo_vivienda_r">Rancho</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_o" class="custom-control-input" value="O">
                                                <label class="custom-control-label radio_tipo_vivienda_label" for="tipo_vivienda_o">Otros</label>
                                            </div>
                                            <!-- FIN INPUT RADIO -->
                                        </td>

                                        <!-- TENENCIA DE VIVIENDA -->
                                        <td class="align-text-top p-2" rowspan="2" style="vertical-align: top !important; width: 20%;">
                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Tenencia de la vivienda</span>

                                            <!-- INPUT RADIO -->
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_p" class="custom-control-input" value="P">
                                                <label class="custom-control-label radio_tenencia_vivienda_label" for="tenencia_vivienda_p">Propia</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_a" class="custom-control-input" value="A">
                                                <label class="custom-control-label radio_tenencia_vivienda_label" for="tenencia_vivienda_a">Alquilada</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_e" class="custom-control-input" value="E">
                                                <label class="custom-control-label radio_tenencia_vivienda_label" for="tenencia_vivienda_e">Prestada</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_i" class="custom-control-input" value="I">
                                                <label class="custom-control-label radio_tenencia_vivienda_label" for="tenencia_vivienda_i">Invadida</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_m" class="custom-control-input" value="M">
                                                <label class="custom-control-label radio_tenencia_vivienda_label" for="tenencia_vivienda_m">Arrimado</label>
                                            </div>
                                            <!-- FIN INPUT RADIO -->
                                        </td>

                                        <!-- SERVICIOS PUBLICOS -->
                                        <td class="align-text-top p-2" colspan="2" style="vertical-align: middle !important; width: 60%;">
                                            <span class="d-inline-block w-100 position-relative small mb-0">Tipo de vivienda en la que habita  actualmente</span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <!-- DETALLES 1 -->
                                        <td class="px-2 py-1">
                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Agua</span>

                                            <!-- INPUT RADIO -->
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_agua" id="tipo_agua_a" class="custom-control-input" value="A">
                                                <label class="custom-control-label radio_tipo_agua_label" for="tipo_agua_a">Acueducto</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_agua" id="tipo_agua_c" class="custom-control-input" value="C">
                                                <label class="custom-control-label radio_tipo_agua_label" for="tipo_agua_c">Cisterna</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_agua" id="tipo_agua_p" class="custom-control-input" value="P">
                                                <label class="custom-control-label radio_tipo_agua_label" for="tipo_agua_p">Pozo</label>
                                            </div>
                                            <!-- FIN INPUT RADIO -->

                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Electricidad</span>
                                            
                                            <!-- INPUT RADIO -->
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_electricidad" id="tipo_electricidad_l" class="custom-control-input" value="L">
                                                <label class="custom-control-label radio_tipo_electricidad_label" for="tipo_electricidad_l">Legal</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_electricidad" id="tipo_electricidad_i" class="custom-control-input" value="I">
                                                <label class="custom-control-label radio_tipo_electricidad_label" for="tipo_electricidad_i">Ilegal</label>
                                            </div>
                                            <!-- FIN INPUT RADIO -->
                                        </td>

                                        <!-- DETALLES 2 -->
                                        <td class="px-2 py-1">
                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Excretas</span>
                                            
                                            <!-- INPUT RADIO -->
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_excreta" id="tipo_excreta_c" class="custom-control-input" value="C">
                                                <label class="custom-control-label radio_tipo_excreta_label" for="tipo_excreta_c">Cloacas</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_excreta" id="tipo_excreta_l" class="custom-control-input" value="L">
                                                <label class="custom-control-label radio_tipo_excreta_label" for="tipo_excreta_l">Letrina</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: 100%">
                                                <input type="radio" name="tipo_excreta" id="tipo_excreta_p" class="custom-control-input" value="P">
                                                <label class="custom-control-label radio_tipo_excreta_label" for="tipo_excreta_p">Pozo septico</label>
                                            </div>
                                            <!-- FIN INPUT RADIO -->

                                            <span class="d-inline-block w-100 position-relative font-weight-bold text-info small mb-2">Basura</span>
                                        
                                            <!-- INPUT RADIO -->
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_basura" id="tipo_basura_a" class="custom-control-input" value="A">
                                                <label class="custom-control-label radio_tipo_basura_label" for="tipo_basura_a">Aseo úrbano</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_basura" id="tipo_basura_q" class="custom-control-input" value="Q">
                                                <label class="custom-control-label radio_tipo_basura_label" for="tipo_basura_q">Quema</label>
                                            </div>
                                            <!-- FIN INPUT RADIO -->

                                            <!-- OTRAS CARACTERISTICAS -->
                                            <div class="form-group form-row align-items-center mt-2 mb-0">
                                                <label for="otros" class="d-inline-block col-sm-4 position-relative small m-0">Otros</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="otros" id="otros" class="campos_formularios form-control form-control-sm" placeholder="(Opcional)" maxlength="100" autocomplete="off"/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-4">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Materiales de la vivienda</h4>    
                        </div>

                        <!-- DESCRIPCION MATERIAL VIVIENDA -->
                        <div class="col-sm-12">
                            <p class="small font-weight-bold text-info mb-0">Materiales de construcción predominantes:</p>
                        </div>

                        <!-- MATERIALES DEL TECHO -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="techo" class="d-inline-block w-100 position-relative small m-0">Techo</label>
                                <input type="text" name="techo" id="techo" class="campos_formularios form-control form-control-sm" placeholder="Ingrese los materiales del techo" maxlength="100" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- MATERIALES DE LA PARED -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="pared" class="d-inline-block w-100 position-relative small m-0">Pared</label>
                                <input type="text" name="pared" id="pared" class="campos_formularios form-control form-control-sm" placeholder="Ingrese los materiales de la pared" maxlength="100" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- MATERIALES DEL PISO -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="piso" class="d-inline-block w-100 position-relative small m-0">Piso</label>
                                <input type="text" name="piso" id="piso" class="campos_formularios form-control form-control-sm" placeholder="Ingrese los materiales del piso" maxlength="100" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- DESCRIPCION DE LA VIA DE ACCESO -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="via_acceso" class="d-inline-block w-100 position-relative small m-0">Piso</label>
                                <input type="text" name="via_acceso" id="via_acceso" class="campos_formularios form-control form-control-sm" placeholder="Descripción de la via de acceso" maxlength="100" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- DESCRIPCION NUMEROS AMBIENTES -->
                        <div class="col-sm-12">
                            <p class="small font-weight-bold text-info mb-0">Distribución de la vivienda (coloque el número de ambientes)</p>
                        </div>

                        <!-- NUMERO DE SALAS -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="sala" class="d-inline-block w-100 position-relative small m-0">Sala</label>
                                <input type="text" name="sala" id="sala" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="N° de salas" maxlength="2" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE COMEDORES -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="comedor" class="d-inline-block w-100 position-relative small m-0">Comedor</label>
                                <input type="text" name="comedor" id="comedor" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="N° de comedores" maxlength="2" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE COCINAS -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="cocina" class="d-inline-block w-100 position-relative small m-0">Cocina</label>
                                <input type="text" name="cocina" id="cocina" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="N° de cocinas" maxlength="2" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE BAÑOS -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="banio" class="d-inline-block w-100 position-relative small m-0">Baños</label>
                                <input type="text" name="banio" id="banio" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="N° de baños" maxlength="2" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE DORMITORIOS -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="dormitorio" class="d-inline-block w-100 position-relative small m-0">Domitorios</label>
                                <input type="text" name="dormitorio" id="dormitorio" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="N° de dormitorios" maxlength="2" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-familiares" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-familiares-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase mb-4">Área socio familiar</h3>
                        </div>

                        <!-- DESCRIPCION E INSTRUCCIONES -->
                        <div class="col-sm-12 d-flex justify-content-between align-items-center mb-1">
                            <p class="small font-weight-bold text-secondary mb-0">Personas que habitan con el trabajador (a), iniciando desde el jefe del hogar.</p>
                            <button type="button" id="agregar_familiar" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></button>
                        </div>

                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 pb-2">
                            <div class="table-responsive pb-2">
                                <table id="tabla_datos_familiares" class="table table-borderless mb-0" style="min-width: 1100px;">
                                    <thead class="text-light">
                                        <tr>
                                            <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="110">Cédula</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" colspan="4">Nombres y apellidos</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="100">Fecha de N.</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="60">Edad</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="50">Sexo</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="100">Parentesco</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="144">Ocupación</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="65">Trabaja</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="85">Ingresos</th>
                                            <th class="bg-info font-weight-normal px-1 py-2" width="40">Resp.</th>
                                            <th class="bg-info rounded-right" width="28"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- USO JAVASCRIPT -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-ingresos" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-ingresos-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Ingreso familiar</h3>
                        </div>

                        <!-- TABLA DE INGRESOS DE TODA LA FAMILA EN GENERAL -->
                        <div class="col-sm-12 my-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-borderless mb-0" style="min-width: 700px;">
                                    <thead class="text-light">
                                        <tr>
                                            <th class="bg-info rounded-left font-weight-normal w-25 p-2">Ingreso</th>
                                            <th class="bg-info font-weight-normal w-25 p-2">Bolívares</th>
                                            <th class="bg-info font-weight-normal w-25 p-2">Egreso</th>
                                            <th class="bg-info rounded-right font-weight-normal w-25 p-2">Bolívares</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle px-1 pt-1 pb-0"><label for="ingreso_pension" class="small">Pensión</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_pension" id="ingreso_pension" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                            <td class="align-middle px-1 pt-1 pb-0"><label for="egreso_servicios" class="small">Gastos de servicios básicos (agua, luz, teléfono, etc.)</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_servicios" id="egreso_servicios" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="ingreso_seguro" class="small">Seguro social</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_seguro" id="ingreso_seguro" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                            <td class="align-middle px-1 py-0"><label for="egreso_alimentario" class="small">Alimentación</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_alimentario" id="egreso_alimentario" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="ingreso_pension_otras" class="small">Otras pensión</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_pension_otras" id="ingreso_pension_otras" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                            <td class="align-middle px-1 py-0"><label for="egreso_educacion" class="small">Educación</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_educacion" id="egreso_educacion" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="ingreso_sueldo" class="small">Sueldo  y/o salario</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_sueldo" id="ingreso_sueldo" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                            <td class="align-middle px-1 py-0"><label for="egreso_vivienda" class="small">Vivienda (alquiler condominio)</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_vivienda" id="egreso_vivienda" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="otros_ingresos" class="small">Otros ingresos</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="otros_ingresos" id="otros_ingresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                            <td class="align-middle px-1 py-0"><label for="otros_egresos" class="small">Otros egresos</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="otros_egresos" id="otros_egresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos campo-montos" maxlength="9" style="height: 50px;"/></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle small px-1 py-0">Total ingresos</td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="total_ingresos" id="total_ingresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos_0" style="height: 50px;" readonly="true"/></td>
                                            <td class="align-middle small px-1 py-0">Total egresos</td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="total_egresos" id="total_egresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos_0" style="height: 50px;" readonly="true"/></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-recomendaciones" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-recomendaciones-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Para uso de la trabajadora social</h3>
                        </div>

                        <!-- CONDICIONES DE LA VIVIENDA -->
                        <div class="col-lg-12 col-xl-6 align-self-end">
                            <div class="form-group mb-2">
                                <label for="condicion_vivienda" class="d-inline-block w-100 position-relative small m-0">Condiciones generales de la vivienda</label>
                                <textarea name="condicion_vivienda" id="condicion_vivienda" class="campos_formularios form-control form-control-sm" maxlength="1000" placeholder="Describir" autocomplete="off" style="height: 100px;"></textarea>
                            </div>
                        </div>

                        <!-- RELACION FAMILIAR Y CONDICIONES SOCIOECONÓMICAS -->
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group mb-2">
                                <label for="caracteristicas_generales" class="d-inline-block w-100 position-relative small m-0">Características generales de las relaciones familiares y sus condiciones socioeconómicas</label>
                                <textarea name="caracteristicas_generales" id="caracteristicas_generales" class="campos_formularios form-control form-control-sm" maxlength="1000" placeholder="Describir" autocomplete="off" style="height: 100px;"></textarea>
                            </div>
                        </div>

                        <!-- DIAGNOSTICO SOCIAL -->
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group mb-2">
                                <label for="diagnostico_social" class="d-inline-block w-100 position-relative small m-0">Diagnóstico social</label>
                                <textarea name="diagnostico_social" id="diagnostico_social" class="campos_formularios form-control form-control-sm" maxlength="1000" placeholder="Describir" autocomplete="off" style="height: 100px;"></textarea>
                            </div>
                        </div>

                        <!-- DIAGNOSTICO PRELIMINAR -->
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group mb-2">
                                <label for="diagnostico_preliminar" class="d-inline-block w-100 position-relative small m-0">Diagnóstico preliminar</label>
                                <textarea name="diagnostico_preliminar" id="diagnostico_preliminar" class="campos_formularios form-control form-control-sm" maxlength="1000" placeholder="Describir" autocomplete="off" style="height: 100px;"></textarea>
                            </div>
                        </div>

                        <!-- CONCLUSIONES Y RECOMENDACIONES -->
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group mb-2">
                                <label for="conclusiones" class="d-inline-block w-100 position-relative small m-0">Conclusiones y recomendaciones</label>
                                <textarea name="conclusiones" id="conclusiones" class="campos_formularios form-control form-control-sm" maxlength="1000" placeholder="Describir" autocomplete="off" style="height: 100px;"></textarea>
                            </div>
                        </div>

                        <!-- ENFERMO EN EL GRUPO FAMILIAR -->
                        <div class="col-lg-12 col-xl-6">
                            <p class="m-0 small">Hay algún enfermo en el grupo familiar</p>

                            <div class="d-flex mt-2">
                                <!-- INPUT RADIOS -->
                                <div class="custom-control custom-radio mr-2">
                                    <input type="radio" name="enfermos" id="enfermo_si" class="custom-control-input" value="S">
                                    <label class="custom-control-label radio_enfermos_label" for="enfermo_si">Si</label>
                                </div>

                                <div class="custom-control custom-radio">
                                    <input type="radio" name="enfermos" id="enfermo_no" class="custom-control-input" value="N">
                                    <label class="custom-control-label radio_enfermos_label" for="enfermo_no">No</label>
                                </div>
                                <!-- FIN INPUT RADIOS -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="carga_espera" class="position-absolute rounded w-100 h-100" style="top: 0px; left: 0px;display: none;">
                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                        <p class="h4 text-white m-0"><i class="fas fa-spinner fa-spin mr-3"></i><span>Cargando algunos datos...</span></p>
                    </div>
                </div>
            </div>
            <!-- FIN CONTENEDORES TABLAS -->

            <div id="contenedor-mensaje2"></div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar-datos" type="button" class="btn btn-sm btn-info"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>

    <div id="modal-ocupacion" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Registrar ocupación</h5>
                    <button type="button" class="close botones_formulario_ocupacion" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body py-2">
                    <form name="form_registrar_ocupacion" id="form_registrar_ocupacion" class="form-row">
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="nombre_ocupacion" class="d-inline-block w-100 position-relative small m-0">Nombre<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="nombre_ocupacion" id="nombre_ocupacion" class="campos_formularios_ocupacion form-control form-control-sm" placeholder="Ingrese la ocupación" autocomplete="off"/>
                            </div>

                            <div id="contenedor-mensaje-ocupacion"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-sm btn-info botones_formulario_ocupacion" id="btn-registrar-ocupacion"><i class="fas fa-save"></i> <span></span></button>
                    <button type="button" class="btn btn-sm btn-secondary botones_formulario_ocupacion" data-dismiss="modal"><i class="fas fa-times"></i> <span>Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-aceptar-aprendiz" class="modal fade" tabindex="-1" role="dialog">
        <div class="position-fixed w-100 h-100" style="background: rgba(0,0,0,0.5);"></div>

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Persona ya registrada</h5>
                </div>

                <div class="modal-body py-2">
                    <h4 class="m-0 text-secondary text-center">Esta persona ya se encuentra registra como familiar de un aprendiz, ¿Desea agregarla como nuevo aprendiz?</h4>
                </div>

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-sm btn-info" id="btn-agregar-persona" data-dismiss="modal"><i class="fas fa-check"></i><span class="ml-2">Aceptar</span></button>
                    <button type="button" class="btn btn-sm btn-secondary"id="btn-rechazar-persona" data-dismiss="modal"><i class="fas fa-times"></i><span class="ml-2">Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/informe_social.js"></script>