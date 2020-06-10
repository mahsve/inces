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
                        <option value="1">RIF</option>
                        <option value="1">Razón social</option>
                        <option value="1">Act. económica</option>
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
            <input type="text" id="campo_busqueda" class="campos_de_busqueda form-control form-control-sm" style="padding-right: 30px;" placeholder="Buscar por RIF, NIL o razón social" autocomplete="off"/>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="110">RIF</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">NIL</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Razón social</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="200">Act. económica</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Teléfono</th>
                    <th class="bg-info font-weight-normal <?php if ($permisos['modificar'] != 1 AND $permisos['act_desc'] != 1) echo 'rounded-right'; ?> text-center px-1 py-2" width="85">Estatus</th>
                    
                    <?php if ($permisos['modificar'] == 1 OR $permisos['act_desc'] == 1) { ?>
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-right" width="<?php if ($permisos['modificar'] == 1 AND $permisos['act_desc'] == 1) echo 76; else echo 40; ?>"></th>
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
                    <a class="nav-link active" id="pills-datos-empresa-tab" data-toggle="pill" href="#pills-datos-empresa" role="tab" aria-controls="pills-datos-empresa" aria-selected="true">
                        <i class="fas fa-industry"></i><span class="mx-1">Empresa</span><i id="icon-empresa" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-datos-contacto-tab" data-toggle="pill" href="#pills-datos-contacto" role="tab" aria-controls="pills-datos-contacto" aria-selected="false">
                        <i class="fas fa-user-friends"></i><span class="mx-1">Contacto</span><i id="icon-contacto" class="fas fa-exclamation-triangle icon-alert ml-2" style="display: none;"></i>
                    </a>
                </li>
            </ul>

            <div class="tab-content border rounded position-relative mb-2">
                <div id="pills-datos-empresa" class="tab-pane fade px-3 py-2 show active" role="tabpanel" aria-labelledby="pills-datos-empresa-tab">
                    <div class="form-row">
                        <!-- TITULO -->
                        <div class="col-sm-12">
                            <h3 class="font-weight-normal text-secondary text-center text-uppercase">INFORMACIÓN DE LA EMPRESA</h3>    
                        </div>

                        <!-- RIF -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label class="d-inline-block w-100 position-relative small m-0">RIF
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="spinner-rif" class="fas fa-spinner fa-spin position-absolute ocultar-iconos" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="spinner-rif-confirm" class="fas position-absolute ocultar-iconos limpiar-estatus" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-rif-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <input type="text" name="rif" id="rif" class="campos_formularios form-control form-control-sm" placeholder="J-12345678-9" maxlength="12" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NIL -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="nil" class="d-inline-block w-100 position-relative small m-0">NIL<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="nil" id="nil" class="campos_formularios form-control form-control-sm" placeholder="12345" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- RAZON SOCIAL -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="razon_social" class="d-inline-block w-100 position-relative small m-0">Razón social<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="razon_social" id="razon_social" class="campos_formularios form-control form-control-sm" placeholder="Importadora Hernandez" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- ACTIVIDAD ECONOMICA -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="actividad_economica" class="d-inline-block w-100 position-relative small m-0">Actividad económica<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="actividad_economica" id="actividad_economica" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opcion</option>
                                </select>
                            </div>
                        </div>

                        <!-- CODIGO APORTANTE -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="codigo_aportante" class="d-inline-block w-100 position-relative small m-0">Código aportante<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="codigo_aportante" id="codigo_aportante" class="campos_formularios form-control form-control-sm" placeholder="1180123585" maxlength="10" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TELEFONO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="d-inline-block w-100 position-relative small m-0">Teléfono 1<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="telefono_1" id="telefono_1" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el teléfono" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TELEFONO 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="d-inline-block w-100 position-relative small m-0">Teléfono 2</label>
                                <input type="text" name="telefono_2" id="telefono_2" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el teléfono (Opcional)" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="correo" class="d-inline-block w-100 position-relative small m-0">Correo</label>
                                <input type="email" name="correo" id="correo" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el correo (Opcional)" maxlength="80" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Ubicación de la empresa</h4>
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

                        <!-- DIRECCION -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="direccion" class="d-inline-block w-100 position-relative small m-0">Dirección<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <textarea name="direccion" id="direccion" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la dirección de la empresa" maxlength="200" style="height: 100px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pills-datos-contacto" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-contacto-tab">
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

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">Datos de contacto</h4>    
                        </div>

                        <!-- TELEFONO DE HABITACION -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_1_c" class="d-inline-block w-100 position-relative small m-0">Tlf. de habitación<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <input type="text" name="telefono_1_c" id="telefono_1_c" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el teléfono" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO CELULAR -->
                        <div class="col-sm-6 col-lg-3 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="telefono_2_c" class="d-inline-block w-100 position-relative small m-0">Tlf. celular</label>
                                <input type="text" name="telefono_2_c" id="telefono_2_c" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el teléfono (Opcional)" maxlength="11" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="correo_c" class="d-inline-block w-100 position-relative small m-0">Correo</label>
                                <input type="email" name="correo_c" id="correo_c" class="campos_formularios form-control form-control-sm" placeholder="Ingrese el correo (Opcional)" maxlength="80" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- TITULO -->
                        <div class="col-sm-12 mt-3">
                            <h4 class="font-weight-normal text-secondary text-center text-uppercase">DIRECCIÓN DEL CONTACTO</h4>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="estado_c" class="d-inline-block w-100 position-relative small m-0">Estado
                                    <i class="fas fa-asterisk text-danger position-absolute required"></i>
                                    <i id="loader-ciudad_c" class="fas fa-spinner fa-spin position-absolute" style="display: none; font-size: 16px; right: 5px;"></i>
                                    <i id="loader-ciudad_c-reload" class="fas fa-sync-alt text-danger position-absolute btn-recargar" title="Recargar" style="display: none; font-size: 16px; right: 5px; cursor: pointer;"></i>
                                </label>
                                <select name="estado_c" id="estado_c" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija una opcion</option>
                                </select>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-6 col-lg-6 col-xl-4">
                            <div class="form-group mb-2">
                                <label for="ciudad_c" class="d-inline-block w-100 position-relative small m-0">Ciudad<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                                <select name="ciudad_c" id="ciudad_c" class="campos_formularios custom-select custom-select-sm">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2">
                                <label for="direccion_c" class="d-inline-block w-100 position-relative small m-0">Dirección</label>
                                <textarea name="direccion_c" id="direccion_c" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la dirección del contacto (Opcional)" maxlength="200" style="height: 100px;"></textarea>
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

            <div id="contenedor-mensaje2"></div>

            <div class="pt-2 text-center">
                <button id="guardar-datos" type="button" class="botones_formulario btn btn-sm btn-info px-4"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
        </form>
    </div>

    <div id="modal-aceptar-contacto" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Persona ya registrada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body py-2">
                    <h1>Persona ya encontrada</h1>
                </div>

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-sm btn-info" id="btn-agregar-persona"><i class="fas fa-plus"></i><span class="ml-2">Aceptar</span></button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i><span class="ml-2">Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/empresa.js"></script>