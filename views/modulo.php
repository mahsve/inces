<div id="info_table">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-secondary text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <?php if ($permisos['registrar'] == 1){ ?>
            <button type="button" id="show_form" class="botones_formulario btn btn-sm btn-info" disabled="true"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
        <?php } ?>
    </div>

    <?php if ($permisos['modificar'] == 1){ ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
            <span>Debe ordenar los módulos en un orden cronológico.</span>
            <button type="button" id="btn-ordenar-modulos" class="btn btn-sm bg-white">Modificar orden<i class="fas fa-sort ml-2"></i></button>
        </div>
    <?php } ?>

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
                        <option value="1">Nombre</option>
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
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre del módulo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="250">Oficio añadido</th>
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
            <div class="form-row">
                <!-- NOMBRE DEL OFICIO -->
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group mb-2">
                        <label for="nombre" class="d-inline-block w-100 position-relative small m-0">Nombre<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <input type="text" name="nombre" id="nombre" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el módulo o unidad" autocomplete="off"/>
                    </div>
                </div>

                <!-- OFICIO AL QUE PERTENECE EL MODULO (SI NO ES COMPARTIDO) -->
                <div class="col-sm-12 col-lg-6">
                    <div class="form-group mb-2">
                        <label for="oficio" class="d-inline-block w-100 position-relative small m-0">Oficio<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <select name="oficio" id="oficio" class="campos_formularios custom-select custom-select-sm">
                            <option value="">Elija una opcion</option>
                        </select>
                    </div>
                </div>

                <!-- MODULO COMPARTIDO PARA TODO LOS MODULOS (DESACTIVA EL SELECT OFICIO SI ES ASI) -->
                <div class="col-sm-12">
                    <div class="custom-control custom-checkbox mb-2" mr-sm-2">
                        <input type="checkbox" class="custom-control-input" name="repeticion_modulo" id="repeticion_modulo" value="S">
                        <label class="custom-control-label small" for="repeticion_modulo">Repetir en todos <span class="text-secondary">(Módulo repetido en todos los oficios)</span></label>
                    </div>
                </div>

                <!-- TITULO -->
                <div class="col-sm-12">
                    <h5 class="font-weight-normal text-secondary text-center text-uppercase mt-4">Asignaturas</h5>
                </div>

                <!-- LISTA ASIGNATURAS DEL MODULO -->
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-secondary mb-0 small">Agregue las asignaturas presentes en este módulo</p>
                        <button id="btn-buscar-asignatura" type="button" class="btn btn-sm btn-info descripcion-tooltip" data-toggle="tooltip" data-placement="left" title="Buscar las asignaturas"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <!-- CONTENEDOR ASIGNATURAS -->
                <div class="col-sm-12">
                    <div class="overflow-auto">
                        <div id="contenedor-asignatura" class="border rounded overflow-auto px-3 py-2 mb-2" style="max-height: 300px; min-width: 600px;">
                            <!-- JAVASCRIPT -->
                        </div>
                    </div>
                </div>

                <!-- CONTENEDOR PARA MENSAJES PERSONALIZADOS -->
                <div class="col-sm-12">
                    <div id="contenedor-mensaje2"></div>
                </div>
            </div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar-datos" type="button" class="botones_formulario btn btn-sm btn-info px-4"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>

    <div id="modal-buscar-asignaturas" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Buscar asignaturas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body py-2">
                    <form name="form_buscar_asignatura" class="form-row">
                        <div class="col-sm-12">
                            <div class="form-group position-relative mb-2">
                                <label for="input-buscar-asignatura" class="d-inline-block w-100 position-relative small m-0">Buscar asignatura</label>
                                <input type="text" name="input-buscar-asignatura" id="input-buscar-asignatura" class="form-control form-control-sm" placeholder="Ingrese la asignatura" autocomplete="off"/>
                                
                                <div id="resultados-buscar-asignatura" class="caja-resultados-busqueda position-absolute bg-white text-secondary border mt-1 rounded w-100" style="display: none;"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-ordenar-modulos" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Ordenar módulos</h5>
                    <button type="button" class="botones_formulario_orden_modulos close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body py-2">
                    <form name="form_ordenar_modulos" id="form_ordenar_modulos" class="form-row">
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="select-modulos" class="d-inline-block w-100 position-relative small m-0">Seleccione el oficio</label>
                                <select name="select-modulos" id="select-modulos" class="custom-select custom-select-sm">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>

                            <ul id="lista-modulos" class="nav flex-column"></ul>

                            <div id="contenedor-mensaje-modulos" style="mt-2"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer py-2 d-flex justify-content-between">
                    <button type="button" id="btn-guardar-orden" class="botones_formulario_orden_modulos btn btn-sm btn-info"><i class="fas fa-save"></i> <span>Guardar</span></button>
                    <button type="button" class="botones_formulario_orden_modulos btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <span>Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/jquery/jquery-ui.min.js"></script>
<script src="<?php echo SERVERURL; ?>javascripts/modulo.js"></script>