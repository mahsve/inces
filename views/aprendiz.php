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
                        <option value="1">Fecha Reg.</option>
                        <option value="2">Cédula</option>
                        <option value="3">Nombre</option>
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
                        <option value="A">Activos</option>
                        <option value="I">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="form-group col-sm-12 col-xl-3 d-flex align-items-center text-info position-relative mb-2">
            <label for="campo_busqueda" class="position-absolute pr-4 m-0" style="right: 0px; cursor: pointer;"><i class="fas fa-search"></i></label>
            <input type="text" id="campo_busqueda" class="campos_de_busqueda form-control form-control-sm" style="padding-right: 30px;" placeholder="Buscar por nombre" autocomplete="off"/>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="80">N°</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha Nac.</th>
                    <th class="bg-info font-weight-normal px-1 py-2 text-center" width="45">Edad</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="150">Tipo de registro</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="105">Estatus</th>
                    <th class="bg-info font-weight-normal <?php if ($permisos['modificar'] != 1 AND $permisos['act_desc'] != 1) echo 'rounded-right'; ?> text-center px-1 py-2" width="85">Estatus</th>
                    
                    <?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) { ?>
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-right" width="<?php if ($permisos['modificar'] == 1 AND $permisos['act_desc'] == 1) echo 80; else echo 40; ?>"></th>
                    <?php } ?>
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

<?php if ($permisos['registrar'] == 1 OR $permisos['modificar']) { ?>
    <div id="gestion_form" style="display: none;">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-uppercase text-secondary font-weight-normal mb-0"></h4>

            <button type="button" id="show_table" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form action="<?php echo SERVERURL.'controllers/c_informe_social.php'; ?>" method="POST" name="formulario" id="formulario" class="formulario">
            <!-- INPUT DE FECHA DE REGISTRO -->
            <div class="form-row justify-content-end">
                <div class="col-sm-6 col-lg-3 col-xl-2 mb-2">
                    <div class="form-group m-0">
                        <label for="fecha" class="small m-0">Fecha <span class="text-danger">*</span></label>
                        <input type="text" name="fecha" id="fecha" class="form-control form-control-sm bg-white input_fecha" data-date-format="dd-mm-yyyy" placeholder="aaaa-mm-dd" readonly="true"/>
                    </div>
                </div>
            </div>
            <!-- FIN INPUT DE FECHA DE REGISTRO -->
        
            <ul class="nav nav-pills mb-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-datos-ficha-tab" data-toggle="pill" href="#pills-datos-ficha" role="tab" aria-controls="pills-datos-ficha" aria-selected="true">
                        <i class="fas fa-file-invoice"></i><span class="ml-1">Ficha del PNA</span><i id="icon-ficha" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-ciudadano-tab" data-toggle="pill" href="#pills-datos-ciudadano" role="tab" aria-controls="pills-datos-ciudadano" aria-selected="false">
                        <i class="fas fa-user-graduate"></i><span class="ml-1">Aprendiz</span><i id="icon-ciudadano" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-ubicacion-tab" data-toggle="pill" href="#pills-datos-ubicacion" role="tab" aria-controls="pills-datos-ubicacion" aria-selected="false">
                        <i class="fas fa-map-marked-alt"></i><span class="ml-1">Ubicación</span><i id="icon-ubicacion" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-empresa-tab" data-toggle="pill" href="#pills-datos-empresa" role="tab" aria-controls="pills-datos-empresa" aria-selected="false">
                        <i class="fas fa-industry"></i><span class="mx-1">Empresa</span><i id="icon-empresa" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-asignatura-tab" data-toggle="pill" href="#pills-datos-asignatura" role="tab" aria-controls="pills-datos-asignatura" aria-selected="false">
                        <i class="fas fa-book"></i><span class="mx-1">Asignaturas</span><i id="icon-asignatura" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
            </ul>

            <div class="tab-content border rounded position-relative">
                <div id="pills-datos-ficha" class="tab-pane fade px-3 py-2 show active" role="tabpanel" aria-labelledby="pills-datos-ficha-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">PROGRAMA NACIONAL DE APRENDIZAJE</h3>    
                        </div>

                        <!-- TIPO REGISTRO (INSCRIPCION/RE-INSCRIPCION) -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <input type="hidden" name="ficha_anterior" id="ficha_anterior">
                                <label for="tipo_ficha" class="small m-0">Tipo registro <span class="text-danger">*</span></label>
                                <select name="tipo_ficha" id="tipo_ficha" class="custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                    <option value="I">Inscripción</option>
                                    <option value="R">Re-inscripción</option>
                                </select>
                            </div>
                        </div>

                        <!-- CORRELATIVO DE INSCRIPCION -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="correlativo" class="small m-0">Correlativo de Insc.</label>
                                <input type="text" name="correlativo" id="correlativo" class="form-control form-control-sm" placeholder="Correlativo de Inscripción" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE ORDEN -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="numero_orden" class="small m-0">N° de orden</label>
                                <input type="text" name="numero_orden" id="numero_orden" class="form-control form-control-sm" placeholder="Número de orden" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-4">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">EMPRESA ANTERIOR</h4>    
                        </div>

                        <!-- RIF -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="rif_a" class="d-inline-block w-100 position-relative small m-0">RIF</label>
                                <input type="text" name="rif_a" id="rif_a" class="campos_formularios3 form-control form-control-sm" placeholder="RIF de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- NIL -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nil_a" class="d-inline-block w-100 position-relative small m-0">NIL</label>
                                <input type="text" name="nil_a" id="nil_a" class="campos_formularios3 form-control form-control-sm" placeholder="NIL de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- RAZON SOCIAL -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="razon_social_a" class="d-inline-block w-100 position-relative small m-0">Razón social</label>
                                <input type="text" name="razon_social_a" id="razon_social_a" class="campos_formularios3 form-control form-control-sm" placeholder="Razón social de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- ACTIVIDAD ECONOMICA -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="actividad_economica_a" class="d-inline-block w-100 position-relative small m-0">Actividad económica</label>
                                <input type="text" name="actividad_economica_a" id="actividad_economica_a" class="campos_formularios3 form-control form-control-sm" placeholder="Actividad economica de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- TELEFONO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1_ea" class="d-inline-block w-100 position-relative small m-0">Teléfono 1</label>
                                <input type="text" name="telefono_1_ea" id="telefono_1_ea" class="campos_formularios3 form-control form-control-sm solo-numeros" placeholder="Teléfono de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Ubicación de la empresa</h4>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="estado_ea" class="d-inline-block w-100 position-relative small m-0">Estado</label>
                                <input type="text" name="estado_ea" id="estado_ea" class="campos_formularios3 form-control form-control-sm" placeholder="Estado de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="ciudad_ea" class="d-inline-block w-100 position-relative small m-0">Ciudad</label>
                                <input type="text" name="ciudad_ea" id="ciudad_ea" class="campos_formularios3 form-control form-control-sm" placeholder="Ciudad de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="direccion_ea" class="d-inline-block w-100 position-relative small m-0">Dirección</label>
                                <textarea name="direccion_ea" id="direccion_ea" class="campos_formularios3 form-control form-control-sm" placeholder="Dirección de la empresa" style="height: 100px;" readonly="true"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-ciudadano" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-ciudadano-tab">
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
                                <input type="text" name="cedula" id="cedula" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la cédula" autocomplete="off"/>
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
                                <input type="text" name="fecha_n" id="fecha_n" class="campos_formularios form-control form-control-sm input_fecha" style="background-color: white;" data-date-format="dd-mm-yyyy" placeholder="aaaa-mm-dd" autocomplete="off" readonly="true"/>
                                <label for="fecha_n" class="text-info position-absolute m-0" style="bottom: 4px; right: 8px;"><i class="fas fa-calendar-day"></i></label>
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-sm-2 col-lg-1 col-xl-1">
                            <div class="form-group mb-2">
                                <label for="edad" class="d-inline-block w-100 position-relative small m-0">Edad</label>
                                <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center"  value="0" readonly="true"/>
                            </div>
                        </div>

                        <!-- LUGAR DE NACIMIENTO -->
                        <div class="col-sm-10 col-lg-4 col-xl-4">
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
                                    <button type="button" id="btn-agregar-ocupacion" class="btn btn-sm btn-info ml-2"><i class="fas fa-plus"></i></button>
                                </div>
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
                                            <span class="d-inline-block w-100 position-relative small mb-2">Estado civil <i class="fas fa-asterisk text-danger position-absolute required"></i></span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_1" name="estado_civil" class="custom-control-input localStorage-radio" value="S">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_1">Soltero</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_2" name="estado_civil" class="custom-control-input localStorage-radio" value="C">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_2">Casado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_3" name="estado_civil" class="custom-control-input localStorage-radio" value="X">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_3">Concubino</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_4" name="estado_civil" class="custom-control-input localStorage-radio" value="D">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_4">Divorciado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_5" name="estado_civil" class="custom-control-input localStorage-radio" value="V">
                                                <label class="custom-control-label radio_estado_c_label v_radio" for="estado_civil_5">Viudo</label>
                                            </div>
                                        </td>

                                        <!-- GRADO DE INSTRUCCION -->
                                        <td class="align-text-top w-75 p-2">
                                            <span class="d-inline-block w-100 position-relative small mb-2">Grado de instrucción <i class="fas fa-asterisk text-danger position-absolute required"></i></span>
                                            
                                            <div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_1" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="BI">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_1">Educ. básica incompleta</label>
                                                </div>

                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_2" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="BC">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_2">Educ. básica completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_3" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="MI">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_3">Educ. media diversificada incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_4" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="MC">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_4">Educ. media diversificada completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_5" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="SI">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_5">Educ. superior incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_6" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="SC">
                                                    <label class="custom-control-label radio_educacion_label v_radio" for="grado_instruccion_6">Educ. superior completa</label>
                                                </div>

                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="titulo" class="col-sm-4 col-form-label py-0" style="font-size: 80%;">Título</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="titulo" id="titulo" class="form-control form-control-sm localStorage" placeholder="Ingrese el título académico" maxlength="100" autocomplete="off" readonly="true"/>
                                                    </div>
                                                </div>
                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="alguna_mision" class="col-sm-4 col-form-label py-0" style="font-size: 13px;">Ha participado en alguna  misión. Indique</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="alguna_mision" id="alguna_mision" class="form-control form-control-sm localStorage" placeholder="(Opcional)" maxlength="150" autocomplete="off"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos de contacto</h3>    
                        </div>

                        <!-- TELEFONO DE HABITACION -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="d-inline-block w-100 position-relative small m-0">Tlf. de habitación<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="telefono_1" id="telefono_1" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="Ingrese el teléfono" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO CELULAR -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_2" class="d-inline-block w-100 position-relative small m-0">Tlf. célular</label>
                                <input type="text" name="telefono_2" id="telefono_2" class="campos_formularios form-control form-control-sm solo-numeros" placeholder="Ingrese el teléfono (Opcional)" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="correo" class="d-inline-block w-100 position-relative small m-0">Correo</label>
                                <input type="email" name="correo" id="correo" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el correo (Opcional)" maxlength="80" autocomplete="off"/>
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
                                <label class="d-inline-block w-100 position-relative small m-0">Estado
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="loader-ciudad" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-ciudad-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <select name="estado" id="estado" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opcion</option>
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
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
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
                                <label for="parroquia" class="d-inline-block w-100 position-relative small m-0">Parroquia<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="parroquia" id="parroquia" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="direccion" class="d-inline-block w-100 position-relative small m-0">Dirección<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <textarea name="direccion" id="direccion" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la dirección de la empresa" maxlength="200" style="height: 100px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-empresa" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-empresa-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12 position-relative">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Asignar empresa</h3>    
                            <button type="button" id="btn-buscar-empresa" class="btn btn-sm btn-info position-absolute ml-2" style="top: 0px; right: 5px;"><i class="fas fa-search"></i><span class="ml-2">Buscar empresa</span></button>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">INFORMACIÓN DE LA EMPRESA</h4>
                        </div>

                        <!-- RIF -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">RIF<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="rif" id="rif" class="campos_formularios2 form-control form-control-sm" placeholder="RIF de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- NIL -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nil" class="d-inline-block w-100 position-relative small m-0">NIL<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="nil" id="nil" class="campos_formularios2 form-control form-control-sm" placeholder="NIL de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- RAZON SOCIAL -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="razon_social" class="d-inline-block w-100 position-relative small m-0">Razón social<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="razon_social" id="razon_social" class="campos_formularios2 form-control form-control-sm" placeholder="Razón social de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- ACTIVIDAD ECONOMICA -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="actividad_economica" class="d-inline-block w-100 position-relative small m-0">Actividad económica<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="actividad_economica" id="actividad_economica" class="campos_formularios2 form-control form-control-sm" placeholder="Actividad economica de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- CODIGO APORTANTE -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="codigo_aportante" class="d-inline-block w-100 position-relative small m-0">Código aportante<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="codigo_aportante" id="codigo_aportante" class="campos_formularios2 form-control form-control-sm" placeholder="Código aportante" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- TELEFONO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1_e" class="d-inline-block w-100 position-relative small m-0">Teléfono 1<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="telefono_1_e" id="telefono_1_e" class="campos_formularios2 form-control form-control-sm solo-numeros" placeholder="Teléfono de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- TELEFONO 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_2_e" class="d-inline-block w-100 position-relative small m-0">Teléfono 2</label>
                                <input type="text" name="telefono_2_e" id="telefono_2_e" class="campos_formularios2 form-control form-control-sm solo-numeros" placeholder="Segundo teléfono de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="correo_e" class="d-inline-block w-100 position-relative small m-0">Correo</label>
                                <input type="email" name="correo_e" id="correo_e" class="campos_formularios2 form-control form-control-sm" placeholder="Correo de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Ubicación de la empresa</h4>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="estado_e" class="d-inline-block w-100 position-relative small m-0">Estado<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="estado_e" id="estado_e" class="campos_formularios2 form-control form-control-sm" placeholder="Estado de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="ciudad_e" class="d-inline-block w-100 position-relative small m-0">Ciudad<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="ciudad_e" id="ciudad_e" class="campos_formularios2 form-control form-control-sm" placeholder="Ciudad de la empresa" autocomplete="off" readonly="true"/>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="direccion_e" class="d-inline-block w-100 position-relative small m-0">Dirección<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <textarea name="direccion_e" id="direccion_e" class="campos_formularios2 form-control form-control-sm" placeholder="Dirección de la empresa" style="height: 100px;" readonly="true"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-asignatura" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-asignatura-tab">
                    <div class="form-row">
                        <div class="col-sm-12 offset-md-2 col-md-8 mb-2">
                            <h6 class="font-weight-normal text-secondary text-center text-uppercase position-relative">Asignaturas
                                <i id="loader-asignaturas" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; top: 4px; right: 5px;"></i>
                                <i id="loader-asignaturas-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; top: 4px; right: 5px; cursor: pointer;"></i>  
                            </h6>
                            <i class="d-inline-block w-100 text-center text-secondary">Selecciones las asignaturas correspondientes</i>

                            <div id="contenedor_asignaturas" class="border rounded bg-white overflow-auto p-3"  style="height: calc(100% - 60px); min-height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div id="carga_espera" class="position-absolute rounded w-100 h-100" style="top: 0px; left: 0px;display: none;">
                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                        <p class="h4 text-white m-0"><i class="fas fa-spinner fa-spin mr-3"></i><span>Cargando algunos datos...</span></p>
                    </div>
                </div>
            </div>

            <div id="contenedor-mensaje2"></div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar_datos" type="button" class="btn btn-sm btn-info"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>

    <div id="modal-buscar-participante" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Buscar participante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body py-2">
                    <form name="form_buscar_participante" class="form-row">
                        <!-- CEDULA -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2 position-relative">
                                <label for="input-buscar-participante" class="small m-0">Buscar participante <span class="text-danger">*</span></label>
                                <input type="text" name="input-buscar-participante" id="input-buscar-participante" class="form-control form-control-sm" placeholder="Buscar participante por Cédula o Nombre" autocomplete="off"/>
                                <div id="resultados-buscar-participante" class="caja-resultados-busqueda position-absolute bg-white text-secondary border mt-1 rounded w-100" style="display: none;"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-buscar-empresa" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Buscar empresa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body py-2">
                    <form name="form_buscar_empresa" class="form-row">
                        <!-- CEDULA -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2 position-relative">
                                <label for="input-buscar-empresa" class="small m-0">Buscar empresa <span class="text-danger">*</span></label>
                                <input type="text" name="input-buscar-empresa" id="input-buscar-empresa" class="form-control form-control-sm" placeholder="Buscar empresa por RIF o por razón social" autocomplete="off"/>
                                <div id="resultados-buscar-empresa" class="caja-resultados-busqueda position-absolute bg-white text-secondary border mt-1 rounded w-100" style="display: none;"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-registrar-ocupacion" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Registrar ocupación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body py-2">
                    <form name="form_registrar_ocupacion" id="form_registrar_ocupacion" class="form-row">
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="input_registrar_ocupacion" class="d-inline-block w-100 position-relative small m-0">Ocupación <i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="input_registrar_ocupacion" id="input_registrar_ocupacion" class="form-control form-control-sm" placeholder="Ocupación (Mecanico, Estudiante,...)" autocomplete="off"/>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-sm btn-info" id="btn-registrar-ocupacion"><i class="fas fa-plus"></i><span class="ml-2">Registrar</span></button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i><span class="ml-2">Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/aprendiz.js"></script>