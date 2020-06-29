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
                        <option value="1">Fecha</option>
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
                        <option value="A" selected>En curso</option>
                        <option value="F">Finalizados</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="form-group col-sm-12 col-xl-3 d-flex align-items-center text-info position-relative mb-2">
            <label for="campo_busqueda" class="position-absolute pr-4 m-0" style="right: 0px; cursor: pointer;"><i class="fas fa-search"></i></label>
            <input type="text" id="campo_busqueda" class="campos_de_busqueda form-control form-control-sm" style="padding-right: 30px;" placeholder="Buscar por descripción" autocomplete="off"/>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="80">N°</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Descripción modulo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="93">Asignaturas</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="80">Horas</th>
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

<?php if ($permisos['registrar'] == 1 OR $permisos['modificar']){ ?>
    <div id="gestion_form" style="display: none;">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-secondary text-uppercase font-weight-normal mb-0"></h4>
            <button type="button" id="show_table" class="botones_formulario btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form name="formulario" id="formulario" class="formulario">
            <ul class="nav nav-pills mb-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-modulo-tab" data-toggle="pill" href="#pills-modulo" role="tab" aria-controls="pills-modulo" aria-selected="true">
                        <i class="fas fa-chalkboard"></i><span class="ml-1">Módulo</span><i id="icon-modulo" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-secciones-tab" data-toggle="pill" href="#pills-secciones" role="tab" aria-controls="pills-secciones" aria-selected="false">
                        <i class="fas fa-list"></i><span class="ml-1">Lista por sección</span><i id="icon-secciones" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
            </ul>
        
            <div class="tab-content border rounded position-relative">
                <div id="pills-modulo" class="tab-pane fade px-3 py-2 show active" role="tabpanel" aria-labelledby="pills-modulo-tab">
                    <div class="form-row">
                        <!-- FECHA DE INICIO -->
                        <div class="col-sm-12 col-lg-6 col-xl-3">
                            <div class="form-group position-relative mb-2">
                                <label for="fecha" class="d-inline-block w-100 position-relative small m-0">Fecha de inicio<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="fecha" id="fecha" class="campos_formularios input_fecha form-control form-control-sm" style="background-color: white; padding-right: 30px;" data-date-format="dd-mm-yyyy" placeholder="dd-mm-aaaa" readonly="true"/>
                                <label for="fecha" class="position-absolute text-info m-0" style="bottom: 4px; right: 8px; cursor: pointer;"><i class="fas fa-calendar-day"></i></label>
                            </div>
                        </div>

                        <!-- OFICIO -->
                        <div class="col-sm-12 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">Oficio
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="loader-modulo" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-modulo-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <select name="oficio" id="oficio" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- MODULO -->
                        <div class="col-sm-12 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="modulo" class="d-inline-block w-100 position-relative small m-0">Módulo<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="modulo" id="modulo" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un oficio</option>
                                </select>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h5 class="font-weight-normal text-secondary text-center text-uppercase position-relative mt-4">Asignaturas
                                <i id="loader-asignaturas" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; top: 4px; right: 5px;"></i>
                                <i id="loader-asignaturas-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; top: 4px; right: 5px; cursor: pointer;"></i>  
                            </h5>
                        </div>

                        <!-- DESCRIPCION -->
                        <div class="col-sm-12">
                            <p class="text-secondary small mb-1">Asignaturas presentes en este módulo</p>
                        </div>

                        <!-- CONTENEDOR ASIGNATURAS -->
                        <div class="col-sm-12">
                            <div class="overflow-auto">
                                <div id="contenedor_asignaturas" class="border rounded overflow-auto px-3 py-2 mb-2" style="max-height: 300px; min-width: 600px;">
                                    <!-- JAVASCRIPT -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-secciones" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-secciones-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Secciones hábiles</h4>    
                        </div>

                        <!-- DESCRIPCION Y BOTON -->
                        <div class="col-sm-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="text-secondary small mb-0">Agrega las secciones hábiles para este módulo y el turno a cursar</p>
                                <button type="button" id="btn-agregar-seccion" class="btn btn-sm btn-info descripcion-tooltip" data-toggle="tooltip" data-placement="left" title="Agregar sección"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>

                        <!-- CONTENEDOR SECCIONES -->
                        <div class="col-sm-12">
                            <div class="overflow-auto">
                                <div id="lista_secciones" class="border rounded overflow-auto px-3 py-2 mb-2" style="max-height: 500px; min-width: 800px;">
                                    <!-- JAVASCRIPT -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENDOR DE CARGA -->
                <div id="carga_espera" class="position-absolute rounded w-100 h-100" style="top: 0px; left: 0px;display: none;">
                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                        <p class="h4 text-white m-0"><i class="fas fa-spinner fa-spin mr-3"></i><span>Cargando algunos datos...</span></p>
                    </div>
                </div>
            </div>

            <div id="contenedor-mensaje2"></div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar-datos" type="button" class="botones_formulario btn btn-sm btn-info px-4"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/modulo_curso.js"></script>