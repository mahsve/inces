<div id="info_table">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-secondary text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>
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
                        <option value="A">En curso</option>
                        <option value="E" selected>En espera</option>
                        <option value="F">Finalizado</option>
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
                    <th class="bg-info font-weight-normal px-1 py-2">Asignatura</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Descripción asignatura</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="70">Sección</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Turno</th>
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
                <!-- TITULO -->
                <div class="col-sm-12">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos de la asignatura</h3>    
                </div>

                <!-- FECHA DE INICIO -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group position-relative mb-2">
                        <label for="fecha_inicio" class="d-inline-block w-100 position-relative small m-0">Fecha de inicio<i class="fas fa-asterisk text-danger position-absolute required"></i></label>
                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="campos_formularios input_fecha form-control form-control-sm" style="background-color: white; padding-right: 30px;" data-date-format="dd-mm-yyyy" placeholder="dd-mm-aaaa" readonly="true"/>
                        <label for="fecha_inicio" class="position-absolute text-info m-0" style="bottom: 4px; right: 8px; cursor: pointer;"><i class="fas fa-calendar-day"></i></label>
                    </div>
                </div>

                <!-- HORAS (AUTOMATICO) -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="horas" class="d-inline-block w-100 position-relative small m-0">Horas <span class="small">(Automatico)</span></label>
                        <input type="text" name="horas" id="horas" class="form-control form-control-sm input_fecha" autocomplete="off"  readonly="true"/>
                    </div>
                </div>

                <!-- FECHA FIN -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="fecha_fin" class="d-inline-block w-100 position-relative small m-0">Finalización <span class="small">(Automatico)</span></label>
                        <input type="text" name="fecha_fin" id="fecha_fin" class="form-control form-control-sm input_fecha" placeholder="dd-mm-aaaa" readonly="true"/>
                    </div>
                </div>

                <!-- TITULO -->
                <div class="col-sm-12 mt-4">
                    <h4 class="font-weight-normal text-secondary text-center text-uppercase">Fechas no laborados</h4>    
                </div>
                
                <!-- DESCRIPCION Y BOTON -->
                <div class="col-sm-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="text-secondary small mb-0">Agregue las fechas que no se laboraron</p>
                        <button type="button" id="btn-agregar-fecha" class="btn btn-sm btn-info descripcion-tooltip" data-toggle="tooltip" data-placement="left" title="Agregar fecha no laboral"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- CONTENEDOR DE FECHAS NO LABORALES -->
                <div class="col-sm-12">
                    <div class="overflow-auto">
                        <div id="no_laborales" class="border rounded overflow-auto px-3 py-2 mb-2" style="min-width: 600px;">
                            <!-- JAVASCRIPT -->
                        </div>
                    </div>
                </div>

                <!-- TITULO -->
                <div class="col-sm-12 mt-4">
                    <h4 class="font-weight-normal text-secondary text-center text-uppercase">Datos del facilitador</h4>    
                    
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="small font-weight-bold text-secondary mb-0">Agregue al facilitador que Impartirá esta asignatura</p>
                        <button type="button" id="btn-buscar-facilitador" class="btn btn-sm btn-info descripcion-tooltip" data-toggle="tooltip" data-placement="left" title="Mostrar facilitadores disponibles"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- NACIONALIDAD -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="nacionalidad2" class="d-inline-block w-100 position-relative small m-0">Nacionalidad</label>
                        <input type="text" name="nacionalidad2" id="nacionalidad2" class="form-control form-control-sm" autocomplete="off" readonly="true"/>
                        <input type="hidden" name="nacionalidad" id="nacionalidad" value="0"/>
                    </div>
                </div>

                <!-- CEDULA -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="cedula" class="d-inline-block w-100 position-relative small m-0">Cédula</label>
                        <input type="text" name="cedula" id="cedula" class="form-control form-control-sm solo-numeros" autocomplete="off" readonly="true"/>
                    </div>
                </div>

                <!-- NOMBRE 1 -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="nombre_1" class="d-inline-block w-100 position-relative small m-0">Primer nombre</label>
                        <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm" autocomplete="off" readonly="true"/>
                    </div>
                </div>

                <!-- NOMBRE 2 -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="nombre_2" class="d-inline-block w-100 position-relative small m-0">Segundo nombre</label>
                        <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm" autocomplete="off" readonly="true"/>
                    </div>
                </div>

                <!-- APELLIDO 1 -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="apellido_1" class="d-inline-block w-100 position-relative small m-0">Primer Apellido</label>
                        <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm" autocomplete="off" readonly="true"/>
                    </div>
                </div>

                <!-- APELLIDO 2 -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                        <label for="apellido_2" class="d-inline-block w-100 position-relative small m-0">Segundo Apellido</label>
                        <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm" autocomplete="off" readonly="true"/>
                    </div>
                </div>

                <!-- OCUPACION -->
                <div class="col-sm-12 col-lg-6 col-xl-5">
                    <div class="form-group mb-2">
                        <label for="ocupacion" class="d-inline-block w-100 position-relative small m-0">Ocupación</label>
                        <input type="text" name="ocupacion" id="ocupacion" class="form-control form-control-sm" autocomplete="off" readonly="true"/>
                    </div>
                </div>

                <!-- MENSAJES PERSONALIZADOS -->
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

    <div id="modal-mostrar-facilitadores" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-secondary">Facilitadores disponibles</h5>
                    <button type="button" class="close botones_formulario_persona_contacto" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body py-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Cedula</th>
                                <th scope="col">Nombre completo</th>
                                <th scope="col">Sexo</th>
                                <th scope="col">Edad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mark</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <td>Jacob</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <td>Larry</td>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
            </div>
        </div>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/asignatura_curso.js"></script>
<!-- <script>
    $(function () {
        $('#btn-buscar-facilitador').click(function () {
            $('#modal-mostrar-facilitadores').modal();
        });
    });
</script> -->