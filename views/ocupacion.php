<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <?php if ($permisos['registrar'] == 1){ ?>
            <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip" disabled="true"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
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
                        <option value="1">Codigo</option>
                        <option value="2">Nombre</option>
                        <option value="3">Formulario</option>
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
                <input type="text" id="campo_busqueda" class="form-control form-control-sm" style="padding-right: 30px;" placeholder="Buscar por nombre" autocomplete="off"/>
            </div>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal text-right py-2 pl-1 pr-2" width="80">#</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre de la ocupación</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Formulario</th>
                    <th class="bg-info font-weight-normal <?php if ($permisos['modificar'] != 1 AND $permisos['act_desc'] != 1) echo 'rounded-right'; ?> px-1 py-2" width="85">Estatus</th>
                    <?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) { ?>
                        <th class="bg-info rounded-right p-0 py-1" width="<?php if ($permisos['modificar'] == 1 AND $permisos['act_desc'] == 1) echo 76; else echo 40; ?>"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="<?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) echo 5; else echo 4; ?>" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 align-self-center">
            <p class="font-weight-bold text-secondary m-0" style="font-size: 13px;">Total registros
                <span id="total_registros">0</span>
            </p>
        </div>
        <div class="col-sm-12 col-md-6">
            <nav aria-label="Page navigation">
                <ul id="paginacion" class="pagination pagination-sm justify-content-end mb-0"></ul>
            </nav>
        </div>

        <div id="contenedor-mensaje" class="col-sm-12"></div>
    </div>
</div>

<?php if ($permisos['registrar'] == 1 OR $permisos['modificar']){ ?>
    <div id="gestion_form" style="display: none;">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-uppercase text-secondary font-weight-normal mb-0"></h4>
            <button type="button" id="show_table" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form name="formulario" id="formulario" class="formulario">
            <div class="form-row">
                <div class="col-sm-12 offset-md-3 col-md-6">
                    <div class="form-group has-warning mb-2">
                        <label for="nombre" class="d-inline-block w-100 position-relative small m-0">Nombre<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-sm" placeholder="Ingrese la ocupación" autocomplete="off"/>
                    </div>
                    <div class="form-group has-warning mb-2">
                        <label for="c_formulario" class="d-inline-block w-100 position-relative small m-0">Formulario<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <select name="c_formulario" id="c_formulario" class="custom-select custom-select-sm">
                            <option value="">Elija el formulario a mostrar</option>
                            <option value="A">Aprendiz</option>
                            <option value="B">Familia del aprendiz</option>
                            <option value="F">Facilitador</option>
                        </select>
                    </div>

                    <div id="contenedor-mensaje2"></div>
                </div>
            </div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar_datos" type="button" class="btn btn-sm btn-info px-4"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/ocupacion.js"></script>