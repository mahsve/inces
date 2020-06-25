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
                        <option value="3">Usuario</option>
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
                        <option value="B">Bloqueados</option>
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
                    <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="120">Usuario</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Personal</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="120">Rol</th>
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
        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-uppercase text-secondary font-weight-normal mb-0"></h4>
            <button type="button" id="show_table" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form name="formulario" id="formulario" class="formulario">
            <div class="form-row">
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
                        <input type="text" name="cedula" id="cedula" class="campos_formularios form-control form-control-sm" placeholder="Ingrese la cédula" maxlength="8" autocomplete="off"/>
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

                <!-- NACIONALIDAD -->
                <div class="col-sm-6 col-md-3 col-xl-2">
                    <div class="form-group has-warning mb-2">
                        <label for="tipo_persona" class="small m-0">Tipo personal <span class="text-danger">*</span></label>
                        <select name="tipo_persona" id="tipo_persona" class="custom-select custom-select-sm">
                            <option value="">Elija una opción</option>
                            <option value="A">Admistrador</option>
                            <option value="B">Admistrativo</option>
                            <option value="F">Facilitador</option>
                            <option value="E">Aprendiz</option>
                            <option value="C">Contacto empresa</option>
                            <option value="O">Otro</option>
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

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/usuario.js"></script>