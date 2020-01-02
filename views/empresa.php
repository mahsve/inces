<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_aprendices" class="table table-borderless mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal px-1 py-2" width="100">RIF</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">NIL</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Razón social</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Estatus</th>
                    <th class="bg-info rounded-right p-0 py-1" width="115"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="gestion_form" style="display: none;">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 id="form_title" class="text-uppercase text-secondary font-weight-normal mb-0"></h4>

        <button type="button" id="show_table" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
    </div>

    <form name="formulario" id="formulario" class="formulario">
        <ul class="nav nav-pills mb-2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-datos-empresa-tab" data-toggle="pill" href="#pills-datos-empresa" role="tab" aria-controls="pills-datos-empresa" aria-selected="true">
                    <i class="fas fa-industry"></i><span class="mx-1">Empresa</span><i class="fas fa-times icon-tab text-danger"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-datos-contacto-tab" data-toggle="pill" href="#pills-datos-contacto" role="tab" aria-controls="pills-datos-contacto" aria-selected="false">
                    <i class="fas fa-user-friends"></i><span class="mx-1">Contacto</span><i class="fas fa-times icon-tab text-danger"></i>
                </a>
            </li>
        </ul>

        <div class="tab-content border rounded">
            <div id="pills-datos-empresa" class="tab-pane fade px-3 py-2 show active" role="tabpanel" aria-labelledby="pills-datos-empresa-tab">
                <div class="form-row">
                    <!-- RIF -->
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <div class="form-group has-warning mb-2">
                            <label for="rif" class="small m-0">RIF <span class="text-danger">*</span></label>
                            <input type="text" name="rif" id="rif" class="form-control form-control-sm" placeholder="Ej: J-12345678-9" maxlength="12"/>
                        </div>
                    </div>

                    <!-- NIL -->
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <div class="form-group has-warning mb-2">
                            <label for="nil" class="small m-0">NIL <span class="text-danger">*</span></label>
                            <input type="text" name="nil" id="nil" class="form-control form-control-sm" placeholder="Ej: 123456-7" maxlength="8"/>
                        </div>
                    </div>

                    <!-- RAZON SOCIAL -->
                    <div class="col-sm-8 col-md-6 col-xl-5">
                        <div class="form-group has-warning mb-2">
                            <label for="razon_social" class="small m-0">Razón social <span class="text-danger">*</span></label>
                            <input type="text" name="razon_social" id="razon_social" class="form-control form-control-sm" placeholder="Ej: Grupo González S.A."/>
                        </div>
                    </div>

                    <!-- ACTIVIDAD ECONOMICA -->
                    <div class="col-sm-4 col-md-6 col-xl-3">
                        <div class="form-group has-warning mb-2">
                            <label for="actividad_economica" class="small m-0">Actividad económica <span class="text-danger">*</span></label>
                            <select name="actividad_economica" id="actividad_economica" class="custom-select custom-select-sm"></select>
                        </div>
                    </div>

                    <!-- CODIGO APORTANTE -->
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <div class="form-group has-warning mb-2">
                            <label for="codigo_aportante" class="small m-0">Código aportante <span class="text-danger">*</span></label>
                            <input type="text" name="codigo_aportante" id="codigo_aportante" class="form-control form-control-sm" placeholder="Ej: "/>
                        </div>
                    </div>

                    <!-- TELEFONO 1 -->
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <div class="form-group has-warning mb-2">
                            <label for="telefono_1" class="small m-0">Teléfono 1 <span class="text-danger">*</span></label>
                            <input type="text" name="telefono_1" id="telefono_1" class="form-control form-control-sm" placeholder="Ej: 02556630108" maxlength="11"/>
                        </div>
                    </div>

                    <!-- TELEFONO 2 -->
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <div class="form-group has-warning mb-2">
                            <label for="telefono_2" class="small m-0">Teléfono 2</label>
                            <input type="text" name="telefono_2" id="telefono_2" class="form-control form-control-sm" placeholder="Ej: 02556630102" maxlength="11"/>
                        </div>
                    </div>

                    <!-- ESTADO -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="form-group has-warning mb-2">
                            <label for="estado" class="small m-0">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="custom-select custom-select-sm"></select>
                        </div>
                    </div>

                    <!-- CIUDAD -->
                    <div class="col-sm-6 col-md-5 col-xl-3">
                        <div class="form-group has-warning mb-2">
                            <label for="ciudad" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                            <select name="ciudad" id="ciudad" class="custom-select custom-select-sm"></select>
                        </div>
                    </div>

                    <!-- DIRECCION -->
                    <div class="col-sm-12">
                        <div class="form-group has-warning mb-2">
                            <label for="direccion" class="small m-0">Dirección <span class="text-danger">*</span></label>
                            <textarea name="direccion" id="direccion" class="form-control form-control-sm" placeholder="Ej: Av. Los Agricultores, Edificio Profinca, Acarigua, Edo. Portuguesa."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pills-datos-contacto" class="tab-pane fade px-3 py-2" role="tabpanel" aria-labelledby="pills-datos-contacto-tab">
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

                    <!-- ESTADO -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="form-group has-warning mb-2">
                            <label for="estado_c" class="small m-0">Estado <span class="text-danger">*</span></label>
                            <select name="estado_c" id="estado_c" class="custom-select custom-select-sm"></select>
                        </div>
                    </div>

                    <!-- CIUDAD -->
                    <div class="col-sm-6 col-md-5 col-xl-3">
                        <div class="form-group has-warning mb-2">
                            <label for="ciudad_c" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                            <select name="ciudad_c" id="ciudad_c" class="custom-select custom-select-sm"></select>
                        </div>
                    </div>

                    <!-- TELEFONO -->
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <div class="form-group has-warning mb-2">
                            <label for="telefono" class="small m-0">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" placeholder="Ej: 04128975882" maxlength="11"/>
                        </div>
                    </div>

                    <!-- CORREO -->
                    <div class="col-sm-6 col-md-5 col-xl-3">
                        <div class="form-group has-warning mb-2">
                            <label for="correo" class="small m-0">Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control form-control-sm" placeholder="Ej: juanlopez123@email.com" maxlength="60"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <button id="guardar_datos" type="button" class="btn btn-sm btn-info"><i class="fas fa-save"></i><span class="ml-2">Guardar</span></button>
        </div>
    </form>
</div>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/empresa.js"></script>