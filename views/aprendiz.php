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
                        <option value="1">N° informe</option>
                        <option value="2">Cédula</option>
                        <option value="3">Fecha Reg.</option>
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
                        <option value="A">Aceptados</option>
                        <option value="R">Rechazados</option>
                        <option value="E">En espera</option>
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
        <table id="listado_aprendices" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal px-1 py-2" width="100">N° informe</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha Nac.</th>
                    <th class="bg-info font-weight-normal px-1 py-2 text-center" width="45">Edad</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="150">Oficio</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="85">Turno</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="92">Estatus</th>
                    <th class="bg-info rounded-right p-0 py-1" width="<?php if ($permisos['modificar'] == 1) echo 76; else echo 40; ?>"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
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
            <!-- INPUT DE FECHA DE REGISTRO -->
            <div class="form-row justify-content-end">
                <div class="col-sm-6 col-lg-3 col-xl-2 mb-2">
                    <div class="form-group m-0">
                        <label for="fecha" class="small m-0">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="fecha" id="fecha" class="form-control form-control-sm localStorage"/>
                    </div>
                </div>
            </div>
            <!-- FIN INPUT DE FECHA DE REGISTRO -->
        
            <ul class="nav nav-pills mb-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-datos-ciudadano-tab" data-toggle="pill" href="#pills-datos-ciudadano" role="tab" aria-controls="pills-datos-ciudadano" aria-selected="true">
                        <i class="fas fa-user-graduate"></i><span class="ml-1">Aprendiz</span><i class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-ciudadano2-tab" data-toggle="pill" href="#pills-datos-ciudadano2" role="tab" aria-controls="pills-datos-ciudadano2" aria-selected="false">
                        <i class="fas fa-file-signature"></i><span class="ml-1">Recomendación</span><i id="icon-ciudadano2" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
            </ul>

            <div class="tab-content border rounded position-relative">
                <div id="pills-datos-ciudadano" class="tab-pane fade px-3 py-2 show active" role="tabpanel" aria-labelledby="pills-datos-ciudadano-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos del ciudadano</h3>    
                        </div>

                        <!-- NACIONALIDAD -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="nacionalidad" class="small m-0">Nacionalidad <span class="text-danger">*</span></label>
                                <select name="nacionalidad" id="nacionalidad" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                    <option value="V">Venezolano</option>
                                    <option value="E">Extranjero</option>
                                </select>
                            </div>
                        </div>

                        <!-- CEDULA -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                                <input type="text" name="cedula" id="cedula" class="form-control form-control-sm localStorage" placeholder="Ingrese la cédula" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el nombre" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="nombre_2" class="small m-0">Segundo nombre</label>
                                <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el nombre" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el apellido" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                                <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el apellido" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" id="sexo" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="I">Indefinido</option>
                                </select>
                            </div>
                        </div>

                        <!-- FECHA DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="fecha_n" class="small m-0">Fecha de nacimiento <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_n" id="fecha_n" class="form-control form-control-sm localStorage"/>
                            </div>
                        </div>

                        <!-- LUGAR DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="lugar_n" class="small m-0">Lugar de nacimiento</label>
                                <input type="text" name="lugar_n" id="lugar_n" class="form-control form-control-sm localStorage" placeholder="Ingrese el lugar de nacimiento" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-sm-6 col-lg-2 col-xl-1">
                            <div class="form-group mb-2">
                                <label for="edad" class="small m-0">Edad</label>
                                <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center localStorage" value="0" readonly="true"/>
                            </div>
                        </div>

                        <!-- OCUPACION -->
                        <div class="col-sm-6 col-lg-6 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="ocupacion" class="small m-0">Ocupación <span class="text-danger">*</span></label>
                                <select name="ocupacion" id="ocupacion" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        
                    </div>
                </div>

                <div id="pills-datos-ciudadano2" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-ciudadano2-tab">
                    <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                    <div class="col-sm-12 my-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 850px;">
                                    <tr>
                                        <!-- ESTADO CIVIL -->
                                        <td class="align-text-top w-25 p-2">
                                            <span class="d-inline-block small mb-2">Estado civil <span class="text-danger">*</span></span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_1" name="estado_civil" class="custom-control-input localStorage-radio" value="S">
                                                <label class="custom-control-label" for="estado_civil_1">Soltero</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_2" name="estado_civil" class="custom-control-input localStorage-radio" value="C">
                                                <label class="custom-control-label" for="estado_civil_2">Casado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_3" name="estado_civil" class="custom-control-input localStorage-radio" value="X">
                                                <label class="custom-control-label" for="estado_civil_3">Concubino</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_4" name="estado_civil" class="custom-control-input localStorage-radio" value="D">
                                                <label class="custom-control-label" for="estado_civil_4">Divorciado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_5" name="estado_civil" class="custom-control-input localStorage-radio" value="V">
                                                <label class="custom-control-label" for="estado_civil_5">Viudo</label>
                                            </div>
                                        </td>

                                        <!-- GRADO DE INSTRUCCION -->
                                        <td class="align-text-top w-75 p-2">
                                            <span class="d-inline-block small mb-2">Grado de instrucción <span class="text-danger">*</span></span>
                                            
                                            <div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_1" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="BI">
                                                    <label class="custom-control-label" for="grado_instruccion_1">Educ. básica  incompleta</label>
                                                </div>

                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_2" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="BC">
                                                    <label class="custom-control-label" for="grado_instruccion_2">Educ. básica completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_3" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="MI">
                                                    <label class="custom-control-label" for="grado_instruccion_3">Educ. media diversificada  incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_4" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="MC">
                                                    <label class="custom-control-label" for="grado_instruccion_4">Educ. media diversificada  completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_5" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="SI">
                                                    <label class="custom-control-label" for="grado_instruccion_5">Educ. superior incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_6" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="SC">
                                                    <label class="custom-control-label" for="grado_instruccion_6">Educ. superior completa  </label>
                                                </div>

                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="titulo" class="col-sm-4 col-form-label py-0" style="font-size: 80%;">Título</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="titulo" id="titulo" class="form-control form-control-sm localStorage" disabled="true">
                                                    </div>
                                                </div>
                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="alguna_mision" class="col-sm-4 col-form-label py-0" style="font-size: 13px;">Ha participado en alguna  misión. Indique</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="alguna_mision" id="alguna_mision" class="form-control form-control-sm localStorage">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- TELEFONO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="small m-0">Teléfono 1 <span class="text-danger">*</span></label>
                                <input type="text" name="telefono_1" id="telefono_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el telefono" autocomplete="off"/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="telefono_2" class="small m-0">Teléfono 2 </label>
                                <input type="text" name="telefono_2" id="telefono_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el telefono" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-lg-5 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="correo" class="small m-0">Correo</label>
                                <input type="email" name="correo" id="correo" class="form-control form-control-sm localStorage" placeholder="Ingrese el correo" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SALIDA OCUPACIONAL (OFICIO O CARRERA) -->
                        <div class="col-sm-6 col-lg-5 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="oficio" class="small m-0">Salida ocupacional <span class="text-danger">*</span></label>
                                <select name="oficio" id="oficio" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- TURNO DE ESTUDIO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="turno" class="small m-0">Turno <span class="text-danger">*</span></label>
                                <select name="turno" id="turno" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                    <option value="M">Matutino</option>
                                    <option value="V">Vespertino</option>
                                </select>
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
                <button id="guardar_datos" type="button" class="btn btn-sm btn-info"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/aprendiz.js"></script>