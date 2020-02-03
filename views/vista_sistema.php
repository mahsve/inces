<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <?php if ($permisos['registrar'] == 1){ ?>
            <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
        <?php } ?>
    </div>

    <?php if ($permisos['modificar'] == 1){ ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
            Usted puede modificar el ordén de las vistas de como se mostraran en el menú por módulo.

            <button type="button" id="mostrar_modal_ordenar" class="btn btn-sm bg-white">Modificar orden<i class="fas fa-sort ml-2"></i></button>
        </div>
    <?php } ?>

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
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-3 mb-2">
            <div class="form-group d-flex align-items-center text-info position-relative mb-0">
                <label for="campo_busqueda" class="position-absolute pr-2 m-0" style="right: 2px;"><i class="fas fa-search"></i></label>
                <input type="text" id="campo_busqueda" class="form-control form-control-sm" style="padding-right:30px;" placeholder="Buscar por vista o por módulo" autocomplete="off"/>
            </div>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal text-right py-2 pl-1 pr-2" width="80">#</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre de la vista</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Módulo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="70">Posición</th>
                    <th class="bg-info font-weight-normal <?php if ($permisos['modificar'] != 1 AND $permisos['act_desc'] != 1) echo 'rounded-right'; ?> px-1 py-2" width="50">Icono</th>
                    <?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) { ?>
                        <th class="bg-info rounded-right p-0 py-1" width="<?php if ($permisos['modificar'] == 1 AND $permisos['act_desc'] == 1) echo 76; else echo 40; ?>"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="<?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) echo 6; else echo 5; ?>" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
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

        <div class="alert alert-info" role="alert">
            <ul class="m-0">
                <li>Este sistema utiliza los iconos de <a href="https://fontawesome.com/icons" target="_blank" class="text-dark"><i class="fab fa-font-awesome"></i> Font Awesome</a> | Click para ver el catálogo de iconos</li>
                <li>Tiene un recuadro para ver una vista previa del nombre con el icono, asi se apreciara en el menú.</li>
                <li>El sistema utiliza la versión gratuita de Font Awesome, por lo que varios iconos no estarán disponibles, ni se mostrarán en el menú.</li>
                <li>Para agregar un icono solo hace falta agregar las clases 'fas' + la clase del icono, ejemplo: 'fa-user'</li>
            </ul>
        </div>

        <form name="formulario" id="formulario" class="formulario">
            <div class="form-row">
                <div class="col-sm-12 offset-md-3 col-md-6">
                    <div>
                        <h5 class="text-secondary">Vista previa</h5>
                        <p class="text-secondary py-2 px-3 border rounded"><i id="icono_prueba"></i><span id="titulo_prueba" class="ml-2"></span></p>
                    </div>

                    <div class="form-group has-warning mb-2">
                        <label for="nombre" class="small m-0">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-sm" placeholder="Ingrese el nombre del módulo" autocomplete="off"/>
                    </div>
                    <label for="enlace" class="small m-0">Enlace <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo SERVERURL; ?>intranet/</span>
                        </div>
                        <input type="text" name="enlace" id="enlace" class="form-control" placeholder="archivo_registro"  autocomplete="off">
                    </div>
                    <div class="form-group has-warning mb-2">
                        <label for="icono" class="small m-0">Icono <span class="text-danger">*</span></label>
                        <input type="text" name="icono" id="icono" class="form-control form-control-sm" placeholder="Ej: fas fa-user" autocomplete="off"/>
                    </div>
                    <div class="form-group has-warning mb-2">
                        <label for="modulo" class="small m-0">Módulo <span class="text-danger">*</span></label>
                        <select name="modulo" id="modulo" class="custom-select custom-select-sm">
                            <option value="">Elija una opción</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar_datos" type="button" class="btn btn-sm btn-info px-4"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>
<?php } ?>

<?php if ($permisos['modificar']){ ?>
    <div class="modal fade" id="modar_cambiar_orden" tabindex="-1" role="dialog" aria-labelledby="modar_cambiar_orden" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title font-weight-normal text-secondary">Modificar orden</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form name="formulario2" id="formulario2" class="formulario">
                        <div class="form-group has-warning mb-2">
                            <label for="modulo2" class="small m-0">Seleccione el módulo <span class="text-danger">*</span></label>
                            <select name="modulo2" id="modulo2" class="custom-select custom-select-sm">
                                <option value="">Elija una opción</option>
                            </select>
                        </div>

                        <ul id="lista-modulos" class="nav flex-column"></ul>
                    </form>
                </div>

                <div class="modal-footer py-2 d-flex justify-content-between">
                    <button type="button" id="guardar_lista_ordenada" class="btn btn-sm btn-info"><i class="fas fa-save"></i><span class="ml-2">Guardar orden</span></button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i><span class="ml-2">Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/jquery/jquery-ui.min.js"></script>
<script src="<?php echo SERVERURL; ?>javascripts/vista_sistema.js"></script>