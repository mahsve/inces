<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>
        <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
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
                        <option value="1">Usuario</option>
                        <option value="2">Nombre</option>
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
                        <option value="B">Bloqueados</option>
                        <option value="I">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-3 mb-2">
            <div class="form-group d-flex align-items-center text-info position-relative mb-0">
                <label for="campo_busqueda" class="position-absolute pr-2 m-0" style="right: 2px;"><i class="fas fa-search"></i></label>
                <input type="text" id="campo_busqueda" class="form-control form-control-sm" style="padding-right:30px;" placeholder="Buscar por código o nombre" autocomplete="off"/>
            </div>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal px-1 py-2" width="150">Usuario</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="170">T. personal</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="120">Rol</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Estatus</th>
                    <th class="bg-info rounded-right p-0 py-1" width="80"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
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

<div id="gestion_form" style="display: none;">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 id="form_title" class="text-uppercase text-secondary font-weight-normal mb-0"></h4>
        <button type="button" id="show_table" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
    </div>

    <form name="formulario" id="formulario" class="formulario">
        <div class="form-row">
            <!-- NACIONALIDAD -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="nacionalidad" class="small m-0">Nacionalidad <span class="text-danger">*</span></label>
                    <select name="nacionalidad" id="nacionalidad" class="custom-select custom-select-sm">
                        <option value="">Elija una opción</option>
                        <option value="V">Venezolano</option>
                        <option value="E">Extranjero</option>
                    </select>
                </div>
            </div>

            <!-- CEDULA -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                    <input type="text" name="cedula" id="cedula" class="form-control form-control-sm" placeholder="Ej: 20158789" maxlength="8"/>
                </div>
            </div>

            <!-- NOMBRE 1 -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm" placeholder="Ej: Juan"/>
                </div>
            </div>

            <!-- NOMBRE 2 -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="nombre_2" class="small m-0">Segundo nombre</label>
                    <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm" placeholder="Ej: Luis"/>
                </div>
            </div>

            <!-- APELLIDO 1 -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm" placeholder="Ej: López"/>
                </div>
            </div>

            <!-- APELLIDO 2 -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                    <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm" placeholder="Ej: Pérez"/>
                </div>
            </div>

            <!-- SEXO -->
            <div class="col-sm-6 col-md-3 col-xl-2">
                <div class="form-group has-warning mb-2">
                    <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                    <select name="sexo" id="sexo" class="custom-select custom-select-sm">
                        <option value="">Elija una opción</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
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

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/usuario.js"></script>