<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <button type="button" id="show_form" class="btn btn-sm btn-info"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
    </div>
</div>

<div id="gestion_form" style="display: none;">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 id="form_title" class="text-uppercase font-weight-normal mb-0"></h4>

        <button type="button" id="show_table" class="btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
    </div>

    <form class="formulario" action="<?php echo SERVERURL.'controllers/c_empresa.php'; ?>" method="POST" name="formulario">
        <!-- INPUT DE FECHA DE REGISTRO -->
        <div class="form-row justify-content-end">
            <div class="col-sm-6 col-md-4 col-lg-2 mb-2">
                <div class="form-group has-warning m-0">
                    <label for="fecha" class="small m-0">Fecha <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" required/>
                </div>
            </div>
        </div>

        <!-- CONTENEDOR -->
        <div class="d-flex align-items-center rounded border p-2 my-2">
            <button type="button" id="retroceder_form" class="btn btn-info rounded-circle move-form" disabled="true">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div id="content_form" class="mx-2">
                <div id="datos_aprendiz">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos del ciudadano</h3>

                    <div class="form-row">
                        <!-- NACIONALIDAD -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="nacionalidad" class="small m-0">Nacionalidad <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="nacionalidad" id="nacionalidad" required>
                                    <option value="">Elija una opción</option>
                                    <option value="V">Venezolano</option>
                                    <option value="E">Extranjero</option>
                                </select>
                            </div>
                        </div>

                        <!-- CEDULA -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cedula" id="cedula" placeholder="Ingrese la cédula" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NOMBRE 1 -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="nombre_1" id="nombre_1" placeholder="Ingrese el nombre" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NOMBRE 2 -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="nombre_2" class="small m-0">Segundo nombre</label>
                                <input type="text" class="form-control form-control-sm" name="nombre_2" id="nombre_2" placeholder="Ingrese el nombre" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 1 -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="apellido_1" id="apellido_1" placeholder="Ingrese el apellido" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- APELLIDO 2 -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                                <input type="text" class="form-control form-control-sm" name="apellido_2" id="apellido_2" placeholder="Ingrese el apellido" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="sexo" id="sexo" required>
                                    <option value="">Elija una opción</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="I">Indefinido</option>
                                </select>
                            </div>
                        </div>

                        <!-- FECHA DE NACIMIENTO -->
                        <div class="col-sm-2 col-lg-2">
                            <div class="form-group has-warning mb-2">
                                <label for="fecha_n" class="small m-0">Fecha de nacimiento<span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" name="fecha_n" id="fecha_n" required/>
                            </div>
                        </div>

                        <!-- LUGAR DE NACIMIENTO -->
                        <div class="col-sm-3 col-lg-3">
                            <div class="form-group has-warning mb-2">
                                <label for="lugar_n" class="small m-0">Lugar de nacimiento</label>
                                <input type="text" class="form-control form-control-sm" name="lugar_n" id="lugar_n" placeholder="Ingrese el lugar de nacimiento" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- OCUPACION -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="ocupacion" class="small m-0">Ocupacion <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="ocupacion" id="ocupacion" required>
                                    <?php if ($estados) {?>
                                        <option value="">Elija una opción</option>
                                        <?php foreach ($estados as $datos) { ?>
                                            <option value="<?php echo $datos['codigo']; ?>"><?php echo $datos['nombre']; ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">No hay ocupaciones</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 my-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 700px;">
                                    <tr>
                                        <td class="align-text-top w-25 p-2">
                                            <span class="d-inline-block mb-2">Estado civil</span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_s" name="estado_civil" class="custom-control-input" value="S" required>
                                                <label class="custom-control-label" for="estado_civil_s">Soltero</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_c" name="estado_civil" class="custom-control-input" value="C" required>
                                                <label class="custom-control-label" for="estado_civil_c">Casado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_co" name="estado_civil" class="custom-control-input" value="X" required>
                                                <label class="custom-control-label" for="estado_civil_co">Concubino</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_d" name="estado_civil" class="custom-control-input" value="D" required>
                                                <label class="custom-control-label" for="estado_civil_d">Divorciado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_v" name="estado_civil" class="custom-control-input" value="V" required>
                                                <label class="custom-control-label" for="estado_civil_v">Viudo</label>
                                            </div>
                                        </td>

                                        <td class="align-text-top w-75 p-2">
                                            <span class="d-inline-block mb-2">Grado de instrucción</span>
                                            
                                            <div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_1" name="grado_instruccion" class="custom-control-input" value="BI" required>
                                                    <label class="custom-control-label" for="grado_instruccion_1">Educ. básica  incompleta</label>
                                                </div>

                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_2" name="grado_instruccion" class="custom-control-input" value="BC" required>
                                                    <label class="custom-control-label" for="grado_instruccion_2">Educ. básica completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_3" name="grado_instruccion" class="custom-control-input" value="MI" required>
                                                    <label class="custom-control-label" for="grado_instruccion_3">Educ. media diversificada  incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_4" name="grado_instruccion" class="custom-control-input" value="MC" required>
                                                    <label class="custom-control-label" for="grado_instruccion_4">Educ. media diversificada  completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_5" name="grado_instruccion" class="custom-control-input" value="SI" required>
                                                    <label class="custom-control-label" for="grado_instruccion_5">Educ. superior incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_6" name="grado_instruccion" class="custom-control-input" value="SC" required>
                                                    <label class="custom-control-label" for="grado_instruccion_6">Educ. superior completa  </label>
                                                </div>

                                                <div class="form-group form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="titulo" class="col-sm-4 col-form-label py-0">Título</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="titulo" name="titulo" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="alguna_mision" class="col-sm-4 col-form-label py-0" style="font-size: 13px;">Ha participado en alguna  misión. Indique:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="alguna_mision" name="alguna_mision">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- TELEFONO 1 -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="telefono_1" class="small m-0">Telefono 1 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="telefono_1" id="telefono_1" placeholder="Ingrese el telefono" autocomplete="off" required/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO 2 -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="telefono_2" class="small m-0">Telefono 2 </label>
                                <input type="text" class="form-control form-control-sm" name="telefono_2" id="telefono_2" placeholder="Ingrese el telefono" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="correo" class="small m-0">Correo</label>
                                <input type="email" class="form-control form-control-sm" name="correo" id="correo" placeholder="Ingrese el correo" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SALIDA OCUPACIONAL (OFICIO O CARRERA) -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="oficio" class="small m-0">Salida ocupacional <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="oficio" id="oficio" required>
                                    <?php if ($estados) {?>
                                        <option value="">Elija una opción</option>
                                        <?php foreach ($estados as $datos) { ?>
                                            <option value="<?php echo $datos['codigo']; ?>"><?php echo $datos['nombre']; ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">No hay ocupaciones</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- TURNO DE ESTUDIO -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="turno" class="small m-0">Turno<span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="turno" id="turno" required>
                                    <option value="">Elija una opción</option>
                                    <option value="M">Matutino</option>
                                    <option value="V">Vespertino</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="datos_extras" style="display: none;">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Ubicación geográfica de la vivienda</h3>
                
                    <div class="form-row">
                        <!-- ESTADO -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="estado" class="small m-0">Estado <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="estado" id="estado" required>
                                    <?php if ($estados) {?>
                                        <option value="">Elija una opción</option>
                                        <?php foreach ($estados as $datos) { ?>
                                            <option value="<?php echo $datos['codigo']; ?>"><?php echo $datos['nombre']; ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">No hay estados</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="ciudad" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="ciudad" id="ciudad" required>
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- MUNICIPIO -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="municipio" class="small m-0">Municipio</label>
                                <select class="custom-select custom-select-sm" name="municipio" id="municipio" required>
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- PARROQUIA -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="parroquia" class="small m-0">Parroquia</label>
                                <select class="custom-select custom-select-sm" name="parroquia" id="parroquia" required>
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- TIPO DE AREÁ -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="area" class="small m-0">Area <span class="text-danger">*</span></label>
                                <select class="custom-select custom-select-sm" name="area" id="area" required>
                                    <option value="">Elija una opción</option>
                                    <option value="R">Rural</option>
                                    <option value="U">Urbana</option>
                                </select>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-6">
                            <div class="form-group has-warning mb-2">
                                <label for="direccion" class="small m-0">Dirección <span class="text-danger">*</span></label>
                                <textarea name="direccion" id="direccion" class="form-control form-control-sm" placeholder="Ingrese la dirección del hogar" required></textarea>
                            </div>
                        </div>

                        <!-- PUNTO DE REFERENCIA -->
                        <div class="col-sm-6">
                            <div class="form-group has-warning mb-2">
                                <label for="punto_referencia" class="small m-0">Punto de referencia <span class="text-danger">*</span></label>
                                <textarea name="punto_referencia" id="punto_referencia" class="form-control form-control-sm" placeholder="Ingrese punto de refencia" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="datos_vivienda" style="display: none;">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Características de la vivienda</h3>

                    <div class="form-row">
                        <!-- TIPO DE VIVIENDA, TENENCIA Y SERVICIOS PUBLICOS. -->
                        <div class="col-sm-12 my-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 700px;">
                                    <tr>
                                        <td class="align-text-top w-25 p-2" rowspan="2">
                                            <span class="d-inline-block mb-2">Tipo de vivienda en la que habita  actualmente</span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tipo_vivienda_1" name="tipo_vivienda" value="Q" required>
                                                <label class="custom-control-label" for="tipo_vivienda_1">Quinta</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tipo_vivienda_2" name="tipo_vivienda" value="C" required>
                                                <label class="custom-control-label" for="tipo_vivienda_2">Casa</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tipo_vivienda_3" name="tipo_vivienda" value="A" required>
                                                <label class="custom-control-label" for="tipo_vivienda_3">Apartamento</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tipo_vivienda_4" name="tipo_vivienda" value="R" required>
                                                <label class="custom-control-label" for="tipo_vivienda_4">Rancho</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tipo_vivienda_5" name="tipo_vivienda" value="O" required>
                                                <label class="custom-control-label" for="tipo_vivienda_5">Otros</label>
                                            </div>
                                        </td>

                                        <td class="align-text-top w-25 p-2" rowspan="2" style="vertical-align: top !important;">
                                            <span class="d-inline-block mb-2">Tenencia de la vivienda</span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tenencia_vivienda_1" name="tenencia_vivienda" value="P" required>
                                                <label class="custom-control-label" for="tenencia_vivienda_1">Propia</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tenencia_vivienda_2" name="tenencia_vivienda" value="A" required>
                                                <label class="custom-control-label" for="tenencia_vivienda_2">Alquilada</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tenencia_vivienda_3" name="tenencia_vivienda" value="E" required>
                                                <label class="custom-control-label" for="tenencia_vivienda_3">Prestada</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="tenencia_vivienda_4" name="tenencia_vivienda" value="I" required>
                                                <label class="custom-control-label" for="tenencia_vivienda_4">Invadida</label>
                                            </div>
                                        </td>

                                        <td class="align-text-top w-50 p-2" colspan="2" style="vertical-align: middle !important;">
                                            <span class="d-inline-block w-100 mb-0">Tipo de vivienda en la que habita  actualmente:</span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="px-2 py-1">
                                            <span class="d-inline-block text-secondary w-100"><b>Agua</b></span>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="acueducto" name="tipo_agua" value="A" required>
                                                <label class="custom-control-label" for="acueducto">Acueducto</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="cisterna" name="tipo_agua" value="C" required>
                                                <label class="custom-control-label" for="cisterna">Cisterna</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="pozo" name="tipo_agua" value="P" required>
                                                <label class="custom-control-label" for="pozo">Pozo</label>
                                            </div>

                                            <span class="d-inline-block text-secondary w-100 mt-1"><b>Electricidad</b></span>
                                            
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="legal" name="tipo_electricidad" value="L" required>
                                                <label class="custom-control-label" for="legal">Legal</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="ilegal" name="tipo_electricidad" value="I" required>
                                                <label class="custom-control-label" for="ilegal">Ilegal</label>
                                            </div>
                                        </td>

                                        <td class="px-2 py-1">
                                            <span class="d-inline-block text-secondary w-100"><b>Excretas</b></span>
                                            
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="cloacas" name="tipo_excreta" value="A" required>
                                                <label class="custom-control-label" for="cloacas">Cloacas</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="letrina" name="tipo_excreta" value="P" required>
                                                <label class="custom-control-label" for="letrina">Letrina</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: 100%">
                                                <input type="radio" class="custom-control-input" id="pozo_septico" name="tipo_excreta" value="C" required>
                                                <label class="custom-control-label" for="pozo_septico">Pozo septico</label>
                                            </div>

                                            <span class="d-inline-block text-secondary w-100 mt-1"><b>Basura</b></span>
                                        
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="tipo_basura_1" name="tipo_basura" value="U" required>
                                                <label class="custom-control-label" for="tipo_basura_1">Aseo úrbano</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="tipo_basura_2" name="tipo_basura" value="Q" required>
                                                <label class="custom-control-label" for="tipo_basura_2">Quema</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" class="custom-control-input" id="tipo_basura_3" name="tipo_basura" value="O" required>
                                                <label class="custom-control-label" for="tipo_basura_3">Pozo</label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- MATERIALES DEL TECHO -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="techo" class="small m-0">Techo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="techo" id="techo" placeholder="Ingrese los materiales del techo" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- MATERIALES DE LA PARED -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="pared" class="small m-0">Pared <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="pared" id="pared" placeholder="Ingrese los materiales de la pared" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- MATERIALES DEL PISO -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="piso" class="small m-0">Piso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="piso" id="piso" placeholder="Ingrese los materiales del piso" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- DESCRIPCION DE LA VIA DE ACCESO -->
                        <div class="col-sm-3">
                            <div class="form-group has-warning mb-2">
                                <label for="via_acceso" class="small m-0">Via de acceso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="via_acceso" id="via_acceso" placeholder="Descripción de la via de acceso" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NUMERO DE SALAS -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="sala" class="small m-0">Sala <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="sala" id="sala" placeholder="N° de salas" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NUMERO DE COMEDORES -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="comedor" class="small m-0">Comedor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="comedor" id="comedor" placeholder="N° de comedores" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NUMERO DE COCINAS -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="cocina" class="small m-0">Cocina <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cocina" id="cocina" placeholder="N° de cocinas" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NUMERO DE BAÑOS -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="bano" class="small m-0">Baños <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="bano" id="bano" placeholder="N° de baños" autocomplete="off" required/>
                            </div>
                        </div>

                        <!-- NUMERO DE DORMITORIOS -->
                        <div class="col-sm-2">
                            <div class="form-group has-warning mb-2">
                                <label for="dormitorio" class="small m-0">Domitorios <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="dormitorio" id="dormitorio" placeholder="N° de dormitorios" autocomplete="off" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="datos_familiares" class="position-relative" style="display: none;">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">área socio familiar</h3>

                    <button type="button" id="agregar_familiar" class="btn btn-sm btn-info position-absolute" style="top: 25px; right: 0px;"><i class="fas fa-plus"></i></button>

                    <p class="small font-weight-bold text-secondary mb-0">Personas que habitan con el trabajador (a), iniciando desde el jefe del hogar.</p>
                    <div class="form-row">
                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 pb-2">
                            <div class="table-responsive pb-2">
                                <table id="tabla_datos_familiares" class="table mb-0" style="min-width: 700px;">
                                    <thead class="bg-info text-light">
                                        <tr>
                                            <th class="font-weight-normal p-2">N°</th>
                                            <th class="font-weight-normal p-2">Apellido y nombre</th>
                                            <th class="font-weight-normal p-2">Fecha de N.</th>
                                            <th class="font-weight-normal p-2">Edad</th>
                                            <th class="font-weight-normal p-2">Sexo</th>
                                            <th class="font-weight-normal p-2">Parentesco</th>
                                            <th class="font-weight-normal p-2">Ocupación</th>
                                            <th class="font-weight-normal p-2">Trabaja</th>
                                            <th class="font-weight-normal p-2">Ingresos</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="avanzar_form" class="btn btn-info rounded-circle move-form">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <!-- FIN CONTENEDOR -->
    </form>
</div>

<script src="<?php echo SERVERURL; ?>javascripts/informe_social.js"></script>
<style>
    #content_form {
        width: calc(100% - 70px);
        min-height: 400px;
    }
    .move-form {
        height: 30px;
        width: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .custom-radio label {
        cursor: pointer;
    }
</style>

<?php /* >
    <?php if ($permisos['registrar']) { ?>
            
        <?php } ?>
    <?php
    // include_once('models/m_empresa.php');
    // $objeto = new model_empresa();
    // $objeto->conectar();
    // $empresas = $objeto->consultarEmpresas();

    // $datos = ['vista' => $dataGET[0]];
    // $permisos = $usuario->permisos($datos);
    // $objeto->desconectar();
    ?>
    <table id="listado" class="table table-bordered table-hover w-100">
            <thead class="">
                <tr class="text-capitalize">
                    <th class="font-weight-normal">N° Informe</th>
                    <th class="font-weight-normal">Nombre</th>
                    <th class="font-weight-normal">estatus</th>
                    <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
                        <th width="75px" class="font-weight-normal p-2"></th>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($empresas) {
                    foreach ($empresas as $datos) {
                ?>
                <tr class="text-capitalize">
                    <td><?php echo $datos['rif']; ?></td>
                    <td><?php echo $datos['nil']; ?></td>
                    <td><?php echo $datos['razon_social']; ?></td>
                    <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
                    <td class="align-middle p-2">
                        <?php if ($permisos['modificar']) { ?>
                            <a href="<?php echo 'gestion_informe_social/'.$datos['rif']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                        <?php } ?>

                        <?php
                        if ($permisos['act_desc']) {
                            if ($datos['estatus'] == 'A') { ?>
                                <button class="btn btn-sm btn-danger cambiar_estatus" data-codigo="<?php echo $datos['codigo']; ?>" data-estatus="<?php echo $datos['estatus']; ?>"><i class="fas fa-retweet"></i></button>
                            <?php } else { ?>
                                <button class="btn btn-sm btn-success cambiar_estatus" data-codigo="<?php echo $datos['codigo']; ?>" data-estatus="<?php echo $datos['estatus']; ?>"><i class="fas fa-retweet "></i></button>
                            <?php } 
                        } ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>

*/ ?>