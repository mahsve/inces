<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <?php if ($permisos['registrar'] == 1){ ?>
            <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
        <?php } ?>
    </div>

    <div class="row justify-content-between">
        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-9">
            <div class="form-row">
                <div class="col-sm-6 col-lg-3 col-xl-2 form-group d-flex align-items-center text-info mb-2">
                    <label for="cantidad_a_buscar" class="pr-2 m-0"><i class="fas fa-list-ul"></i></label>
                    <select id="cantidad_a_buscar" class="custom-select custom-select-sm">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3 col-xl-3 form-group d-flex align-items-center text-info mb-2">
                    <label for="ordenar_por" class="pr-2 m-0"><i class="fas fa-sort-alpha-down"></i></label>
                    <select id="ordenar_por" class="custom-select custom-select-sm">
                        <option value="2">Cédula</option>
                        <option value="4">Nombre</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3 col-xl-2 form-group d-flex align-items-center text-info mb-2">
                    <label for="campo_ordenar" class="pr-2 m-0"><i class="fas fa-sort-numeric-down"></i></label>
                    <select id="campo_ordenar" class="custom-select custom-select-sm">
                        <option value="1">ASC</option>
                        <option value="2">DESC</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3 col-xl-3 form-group d-flex align-items-center text-info mb-2">
                    <label for="buscar_estatus" class="pr-2 m-0"><i class="fas fa-toggle-on"></i></label>
                    <select id="buscar_estatus" class="custom-select custom-select-sm">
                        <option value="">Todos</option>
                        <option value="A">Activos</option>
                        <option value="I">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-3 mb-2">
            <div class="form-group d-flex align-items-center text-info position-relative mb-0">
                <label for="campo_busqueda" class="position-absolute pr-2 m-0" style="right: 2px;"><i class="fas fa-search"></i></label>
                <input type="text" id="campo_busqueda" class="form-control form-control-sm" style="padding-right:30px;" placeholder="Buscar por cédula o nombre" autocomplete="off"/>
            </div>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 1000px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha Nac.</th>
                    <th class="bg-info font-weight-normal px-1 py-2 text-center" width="45">Edad</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Sexo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Teléfono</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="92">Estatus</th>
                    <?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) { ?>
                        <th class="bg-info rounded-right p-0 py-1" width="<?php if ($permisos['modificar'] == 1 AND $permisos['act_desc'] == 1) echo 76; else echo 40; ?>"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="<?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) echo 8; else echo 7; ?>" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <p class="font-weight-bold text-secondary" style="font-size: 13px;">Total registros
                <span id="total_registros">0</span>
            </p>
        </div>
        <div class="col-sm-12 col-md-6">
            <nav aria-label="Page navigation">
                <ul id="paginacion" class="pagination pagination-sm justify-content-end mb-0"></ul>
            </nav>
        </div>
    </div>
</div>

<?php if ($permisos['registrar'] == 1 OR $permisos['modificar']){ ?>
    <div id="gestion_form" style="display: none;">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-uppercase text-secondary font-weight-normal mb-0"></h4>

            <button type="button" id="show_table" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form action="<?php echo SERVERURL.'controllers/c_informe_social.php'; ?>" method="POST" name="formulario" id="formulario" class="formulario">
            <ul class="nav nav-pills mb-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-datos-ciudadano-tab" data-toggle="pill" href="#pills-datos-ciudadano" role="tab" aria-controls="pills-datos-ciudadano" aria-selected="true">
                        <i class="fas fa-chalkboard-teacher"></i><span class="ml-1">Facilitador</span><i id="icon-ciudadano" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-ubicacion-tab" data-toggle="pill" href="#pills-datos-ubicacion" role="tab" aria-controls="pills-datos-ubicacion" aria-selected="false">
                        <i class="fas fa-map-marked-alt"></i><span class="ml-1">Ubicación</span><i id="icon-ubicacion" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-archivos-tab" data-toggle="pill" href="#pills-datos-archivos" role="tab" aria-controls="pills-datos-archivos" aria-selected="false">
                        <i class="fas fa-file-word"></i><span class="ml-1">Archivos</span><i id="icon-archivos" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
            </ul>

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
                                <select name="nacionalidad" id="nacionalidad" class="custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                    <option value="V">Venezolano</option>
                                    <option value="E">Extranjero</option>
                                </select>
                            </div>
                        </div>

                        <!-- CEDULA -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="cedula" class="d-inline-block w-100 position-relative small m-0">Cédula<i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="spinner-cedula" class="fas fa-spinner fa-spin position-absolute ocultar-iconos" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="spinner-cedula-confirm" class="fas position-absolute ocultar-iconos limpiar-estatus" style="display: none; font-size: 16px; right: 5px;"></i>
                                </label>
                                <input type="text" name="cedula" id="cedula" class="form-control form-control-sm localStorage solo-numeros" placeholder="Ingrese la cédula" maxlength="8" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 1 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nombre_1" class="d-inline-block w-100 position-relative small m-0">Primer nombre<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm" placeholder="Ingrese el nombre" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 2 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nombre_2" class="d-inline-block w-100 position-relative small m-0">Segundo nombre</label>
                                <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm" placeholder="Ingrese el nombre (Opcional)" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 1 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="apellido_1" class="d-inline-block w-100 position-relative small m-0">Primer Apellido<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm" placeholder="Ingrese el apellido" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 2 -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="apellido_2" class="d-inline-block w-100 position-relative small m-0">Segundo Apellido</label>
                                <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm" placeholder="Ingrese el apellido (Opcional)" maxlength="25" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="sexo" class="d-inline-block w-100 position-relative small m-0">Sexo<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="sexo" id="sexo" class="custom-select custom-select-sm">
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
                                <input type="text" name="fecha_n" id="fecha_n" class="form-control form-control-sm input_fecha" style="background-color: white;" data-date-format="dd-mm-yyyy" placeholder="aaaa-mm-dd" autocomplete="off" readonly="true"/>
                                <label for="fecha_n" class="text-info position-absolute m-0" style="bottom: 4px; right: 8px;"><i class="fas fa-calendar-day"></i></label>
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-sm-2 col-lg-1 col-xl-1">
                            <div class="form-group mb-2">
                                <label for="edad" class="d-inline-block w-100 position-relative small m-0">Edad</label>
                                <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center" value="0" readonly="true"/>
                            </div>
                        </div>

                        <!-- LUGAR DE NACIMIENTO -->
                        <div class="col-sm-10 col-lg-4 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="lugar_n" class="d-inline-block w-100 position-relative small m-0">Lugar de nacimiento</label>
                                <input type="text" name="lugar_n" id="lugar_n" class="form-control form-control-sm" placeholder="Ingrese el lugar de nacimiento (Opcional)" maxlength="100" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- OCUPACION -->
                        <div class="col-sm-12 col-lg-6 col-xl-5">
                            <div class="form-group mb-2">
                                <label for="ocupacion" class="d-inline-block w-100 position-relative small m-0">Ocupación<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <div class="d-flex">
                                    <select name="ocupacion" id="ocupacion" class="custom-select custom-select-sm">
                                        <option value="">Elija una opción</option>
                                    </select>
                                    <button type="button" id="btn-agregar-ocupacion" class="btn btn-sm btn-info ml-2"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- TELEFONO DE HABITACION -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="d-inline-block w-100 position-relative small m-0">Tlf. de habitación<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="telefono_1" id="telefono_1" class="form-control form-control-sm solo-numeros" placeholder="Ingrese el telefono" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO CELULAR -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_2" class="d-inline-block w-100 position-relative small m-0">Tlf. celular</label>
                                <input type="text" name="telefono_2" id="telefono_2" class="form-control form-control-sm solo-numeros" placeholder="Ingrese el telefono (Opcional)" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="correo" class="d-inline-block w-100 position-relative small m-0">Correo</label>
                                <input type="email" name="correo" id="correo" class="form-control form-control-sm" placeholder="Ingrese el correo (Opcional)" maxlength="80" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Estatus de la persona</h3>    
                        </div>

                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 mt-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 850px;">
                                    <tr>
                                        <!-- ESTADO CIVIL -->
                                        <td class="align-text-top w-25 p-2">
                                            <span class="d-inline-block w-100 position-relative small mb-2">Estado civil<i class="fas fa-asterisk text-danger position-absolute required"></i></span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_1" name="estado_civil" class="custom-control-input radio_estado_c" value="S">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_1">Soltero</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_2" name="estado_civil" class="custom-control-input radio_estado_c" value="C">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_2">Casado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_3" name="estado_civil" class="custom-control-input radio_estado_c" value="X">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_3">Concubino</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_4" name="estado_civil" class="custom-control-input radio_estado_c" value="D">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_4">Divorciado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_5" name="estado_civil" class="custom-control-input radio_estado_c" value="V">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_5">Viudo</label>
                                            </div>
                                        </td>

                                        <!-- GRADO DE INSTRUCCION -->
                                        <td class="align-text-top w-75 p-2">
                                            <span class="d-inline-block w-100 position-relative small mb-2">Grado de instrucción<i class="fas fa-asterisk text-danger position-absolute required"></i></span>
                                            
                                            <div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_1" name="grado_instruccion" class="custom-control-input radio_educacion" value="BI">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_1">Educ. básica incompleta</label>
                                                </div>

                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_2" name="grado_instruccion" class="custom-control-input radio_educacion" value="BC">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_2">Educ. básica completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_3" name="grado_instruccion" class="custom-control-input radio_educacion" value="MI">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_3">Educ. media diversificada incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_4" name="grado_instruccion" class="custom-control-input radio_educacion" value="MC">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_4">Educ. media diversificada completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_5" name="grado_instruccion" class="custom-control-input radio_educacion" value="SI">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_5">Educ. superior incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_6" name="grado_instruccion" class="custom-control-input radio_educacion" value="SC">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_6">Educ. superior completa</label>
                                                </div>

                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="titulo" class="col-sm-4 col-form-label py-0" style="font-size: 80%;">Título</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="titulo" id="titulo" class="form-control form-control-sm" placeholder="Ingrese el título académico" maxlength="100" autocomplete="off" readonly="true"/>
                                                    </div>
                                                </div>
                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="alguna_mision" class="col-sm-4 col-form-label py-0" style="font-size: 13px;">Ha participado en alguna  misión. Indique</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="alguna_mision" id="alguna_mision" class="form-control form-control-sm" placeholder="(Opcional)" maxlength="150" autocomplete="off"/>
                                                    </div>
                                                </div>
                                            </div>
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
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Ubicación geográfica de la vivienda</h3>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="estado" class="d-inline-block w-100 position-relative small m-0">Estado <i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="estado" id="estado" class="custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="ciudad" class="d-inline-block w-100 position-relative small m-0">Ciudad <i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="ciudad" id="ciudad" class="custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- MUNICIPIO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="municipio" class="d-inline-block w-100 position-relative small m-0">Municipio</label>
                                <select name="municipio" id="municipio" class="custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- PARROQUIA -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="parroquia" class="d-inline-block w-100 position-relative small m-0">Parroquia</label>
                                <select name="parroquia" id="parroquia" class="custom-select custom-select-sm">
                                    <option value="">Elija un municipio</option>
                                </select>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12 col-xl-12">
                            <div class="form-group mb-2">
                                <label for="direccion" class="d-inline-block w-100 position-relative small m-0">Dirección <i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <textarea name="direccion" id="direccion" class="form-control form-control-sm" placeholder="Ingrese la dirección del hogar" autocomplete="off" maxlength="200" style="height: 100px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-archivos" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-archivos-tab">
                    <div class="form-row">
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Subir Archivos</h3>

                            <div class="border mt-3 mb-4 p-2">
                                <h5 class="font-weight-normal text-secondary text-center text-uppercase mb-2">Archivos subidos</h5>
                                <div class="border">
                                    <div id="archivos-guardados" class="form-row overflow-auto p-3" style="max-height: 350px;"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="text-secondary mb-0 small">Puede subir todos los archivos que quiera relacionados al facilitador, PDF, documentos Word, Imagenes (PNG, JPG, JPEG).</p>
                                <button id="agregar-input-file" type="button" class="btn btn-sm btn-info" style="top: 5px;"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="col-sm-12 overflow-scroll">
                            <div id="contenedor-input-file" class="border overflow-auto mb-2 py-1 px-2" style="max-height: 350px;"></div>
                        </div>
                    </div>
                </div>

                <div id="carga_espera" class="position-absolute rounded w-100 h-100" style="top: 0px; left: 0px;display: none;">
                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                        <p class="h4 text-white m-0"><i class="fas fa-spinner fa-spin mr-3"></i><span>Cargando algunos datos...</span></p>
                    </div>
                </div>
            </div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar_datos" type="button" class="btn btn-sm btn-info"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/facilitador.js"></script>