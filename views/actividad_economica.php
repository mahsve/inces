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
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre de la actividad económica</th>
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
                <div class="col-sm-12 offset-md-3 col-md-6">
                    <div class="form-group mb-2">
                        <label for="nombre" class="d-inline-block w-100 position-relative small m-0">Nombre<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <input type="text" name="nombre" id="nombre" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la actividad económica" autocomplete="off"/>
                    </div>

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
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/actividad_economica.js"></script>