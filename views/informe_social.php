<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>

        <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_aprendices" class="table table-borderless mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal px-1 py-2" width="100">N° informe</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha Nac.</th>
                    <th class="bg-info font-weight-normal px-1 py-2 text-center" width="45">Edad</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="150">Oficio</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="80">Turno</th>
                    <th class="bg-info rounded-right p-0 py-1" width="115"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
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

    <form action="<?php echo SERVERURL.'controllers/c_informe_social.php'; ?>" method="POST" name="formulario" id="formulario" class="formulario">
        <!-- INPUT DE FECHA DE REGISTRO -->
        <div class="form-row justify-content-end">
            <div class="col-sm-6 col-lg-3 col-xl-2 mb-2">
                <div class="form-group m-0">
                    <label for="fecha" class="small m-0">Fecha <span class="text-danger">*</span></label>
                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm localStorage"/>
                </div>
            </div>
        </div>
        <!-- FIN INPUT DE FECHA DE REGISTRO -->

        <!-- CONTENEDOR -->
        <div class="d-flex align-items-center position-relative rounded border p-2 my-2">
            <button type="button" id="retroceder_form" class="btn btn-info rounded-circle move-form" disabled="true">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div id="content_form" class="mx-2">
                <div id="datos_aprendiz">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Datos del ciudadano</h3>

                    <div class="form-row">
                        <!-- NACIONALIDAD -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="nacionalidad" class="small m-0">Nacionalidad <span class="text-danger">*</span></label>
                                <select name="nacionalidad" id="nacionalidad" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                    <option value="V">Venezolano</option>
                                    <option value="E">Extranjero</option>
                                </select>
                            </div>
                        </div>

                        <!-- CEDULA -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                                <input type="text" name="cedula" id="cedula" class="form-control form-control-sm localStorage" placeholder="Ingrese la cédula" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el nombre" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NOMBRE 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="nombre_2" class="small m-0">Segundo nombre</label>
                                <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el nombre" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el apellido" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- APELLIDO 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                                <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el apellido" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" id="sexo" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="I">Indefinido</option>
                                </select>
                            </div>
                        </div>

                        <!-- FECHA DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="fecha_n" class="small m-0">Fecha de nacimiento<span class="text-danger">*</span></label>
                                <input type="date" name="fecha_n" id="fecha_n" class="form-control form-control-sm localStorage"/>
                            </div>
                        </div>

                        <!-- LUGAR DE NACIMIENTO -->
                        <div class="col-sm-6 col-lg-4 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="lugar_n" class="small m-0">Lugar de nacimiento</label>
                                <input type="text" name="lugar_n" id="lugar_n" class="form-control form-control-sm localStorage" placeholder="Ingrese el lugar de nacimiento" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-sm-6 col-lg-2 col-xl-1">
                            <div class="form-group mb-2">
                                <label for="edad" class="small m-0">Edad</label>
                                <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center localStorage" value="0" readonly="true"/>
                            </div>
                        </div>

                        <!-- OCUPACION -->
                        <div class="col-sm-6 col-lg-6 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="ocupacion" class="small m-0">Ocupación <span class="text-danger">*</span></label>
                                <select name="ocupacion" id="ocupacion" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 my-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-bordered mb-0" style="min-width: 850px;">
                                    <tr>
                                        <td class="align-text-top w-25 p-2">
                                            <span class="d-inline-block small mb-2">Estado civil</span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_1" name="estado_civil" class="custom-control-input localStorage-radio" value="S">
                                                <label class="custom-control-label" for="estado_civil_1">Soltero</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_2" name="estado_civil" class="custom-control-input localStorage-radio" value="C">
                                                <label class="custom-control-label" for="estado_civil_2">Casado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_3" name="estado_civil" class="custom-control-input localStorage-radio" value="X">
                                                <label class="custom-control-label" for="estado_civil_3">Concubino</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_4" name="estado_civil" class="custom-control-input localStorage-radio" value="D">
                                                <label class="custom-control-label" for="estado_civil_4">Divorciado</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="estado_civil_5" name="estado_civil" class="custom-control-input localStorage-radio" value="V">
                                                <label class="custom-control-label" for="estado_civil_5">Viudo</label>
                                            </div>
                                        </td>

                                        <td class="align-text-top w-75 p-2">
                                            <span class="d-inline-block small mb-2">Grado de instrucción</span>
                                            
                                            <div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_1" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="BI">
                                                    <label class="custom-control-label" for="grado_instruccion_1">Educ. básica  incompleta</label>
                                                </div>

                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_2" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="BC">
                                                    <label class="custom-control-label" for="grado_instruccion_2">Educ. básica completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_3" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="MI">
                                                    <label class="custom-control-label" for="grado_instruccion_3">Educ. media diversificada  incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_4" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="MC">
                                                    <label class="custom-control-label" for="grado_instruccion_4">Educ. media diversificada  completa</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_5" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="SI">
                                                    <label class="custom-control-label" for="grado_instruccion_5">Educ. superior incompleta</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 5px);">
                                                    <input type="radio" id="grado_instruccion_6" name="grado_instruccion" class="custom-control-input localStorage-radio radio_educacion" value="SC">
                                                    <label class="custom-control-label" for="grado_instruccion_6">Educ. superior completa  </label>
                                                </div>

                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="titulo" class="col-sm-4 col-form-label py-0" style="font-size: 80%;">Título</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="titulo" id="titulo" class="form-control form-control-sm localStorage" disabled="true">
                                                    </div>
                                                </div>
                                                <div class="form-group form-row align-items-center mt-2 mb-0">
                                                    <label for="alguna_mision" class="col-sm-4 col-form-label py-0" style="font-size: 13px;">Ha participado en alguna  misión. Indique:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="alguna_mision" id="alguna_mision" class="form-control form-control-sm localStorage">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- TELEFONO 1 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="telefono_1" class="small m-0">Telefono 1 <span class="text-danger">*</span></label>
                                <input type="text" name="telefono_1" id="telefono_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el telefono" autocomplete="off"/>
                            </div>
                        </div>
                        
                        <!-- TELEFONO 2 -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="telefono_2" class="small m-0">Telefono 2 </label>
                                <input type="text" name="telefono_2" id="telefono_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el telefono" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="col-sm-6 col-lg-5 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="correo" class="small m-0">Correo</label>
                                <input type="email" name="correo" id="correo" class="form-control form-control-sm localStorage" placeholder="Ingrese el correo" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- SALIDA OCUPACIONAL (OFICIO O CARRERA) -->
                        <div class="col-sm-6 col-lg-5 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="oficio" class="small m-0">Salida ocupacional <span class="text-danger">*</span></label>
                                <select name="oficio" id="oficio" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- TURNO DE ESTUDIO -->
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="turno" class="small m-0">Turno<span class="text-danger">*</span></label>
                                <select name="turno" id="turno" class="custom-select custom-select-sm localStorage">
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
                        <div class="col-sm-6 col-lg-4 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="estado" class="small m-0">Estado <span class="text-danger">*</span></label>
                                <select name="estado" id="estado" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                </select>
                            </div>
                        </div>

                        <!-- CIUDAD -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="ciudad" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                                <select name="ciudad" id="ciudad" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- MUNICIPIO -->
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="municipio" class="small m-0">Municipio</label>
                                <select name="municipio" id="municipio" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija un estado</option>
                                </select>
                            </div>
                        </div>

                        <!-- PARROQUIA -->
                        <div class="col-sm-6 col-lg-4 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="parroquia" class="small m-0">Parroquia</label>
                                <select name="parroquia" id="parroquia" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija un municipio</option>
                                </select>
                            </div>
                        </div>

                        <!-- TIPO DE AREÁ -->
                        <div class="col-sm-6 col-lg-4 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="area" class="small m-0">Área <span class="text-danger">*</span></label>
                                <select name="area" id="area" class="custom-select custom-select-sm localStorage">
                                    <option value="">Elija una opción</option>
                                    <option value="R">Rural</option>
                                    <option value="U">Urbana</option>
                                </select>
                            </div>
                        </div>

                        <!-- DIRECCION -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="direccion" class="small m-0">Dirección <span class="text-danger">*</span></label>
                                <textarea name="direccion" id="direccion" class="form-control form-control-sm localStorage" placeholder="Ingrese la dirección del hogar"></textarea>
                            </div>
                        </div>

                        <!-- PUNTO DE REFERENCIA -->
                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group mb-2">
                                <label for="punto_referencia" class="small m-0">Punto de referencia <span class="text-danger">*</span></label>
                                <textarea name="punto_referencia" id="punto_referencia" class="form-control form-control-sm localStorage" placeholder="Ingrese punto de refencia"></textarea>
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
                                <table class="table table-bordered mb-0" style="min-width: 850px;">
                                    <tr>
                                        <td class="align-text-top p-2" rowspan="2" style="width: 20%;">
                                            <span class="d-inline-block small mb-2">Tipo de vivienda en la que habita  actualmente</span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_1" class="custom-control-input localStorage-radio" value="Q">
                                                <label class="custom-control-label" for="tipo_vivienda_1">Quinta</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_2" class="custom-control-input localStorage-radio" value="C">
                                                <label class="custom-control-label" for="tipo_vivienda_2">Casa</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_3" class="custom-control-input localStorage-radio" value="A">
                                                <label class="custom-control-label" for="tipo_vivienda_3">Apartamento</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_4" class="custom-control-input localStorage-radio" value="R">
                                                <label class="custom-control-label" for="tipo_vivienda_4">Rancho</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tipo_vivienda" id="tipo_vivienda_5" class="custom-control-input localStorage-radio" value="O">
                                                <label class="custom-control-label" for="tipo_vivienda_5">Otros</label>
                                            </div>
                                        </td>

                                        <td class="align-text-top p-2" rowspan="2" style="vertical-align: top !important; width: 20%;">
                                            <span class="d-inline-block small mb-2">Tenencia de la vivienda</span>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_1" class="custom-control-input localStorage-radio" value="P">
                                                <label class="custom-control-label" for="tenencia_vivienda_1">Propia</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_2" class="custom-control-input localStorage-radio" value="A">
                                                <label class="custom-control-label" for="tenencia_vivienda_2">Alquilada</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_3" class="custom-control-input localStorage-radio" value="E">
                                                <label class="custom-control-label" for="tenencia_vivienda_3">Prestada</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="tenencia_vivienda" id="tenencia_vivienda_4" class="custom-control-input localStorage-radio" value="I">
                                                <label class="custom-control-label" for="tenencia_vivienda_4">Invadida</label>
                                            </div>
                                        </td>

                                        <td class="align-text-top p-2" colspan="2" style="vertical-align: middle !important; width: 60%;">
                                            <span class="d-inline-block small w-100 mb-0">Tipo de vivienda en la que habita  actualmente:</span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="px-2 py-1">
                                            <span class="d-inline-block text-info w-100" style="font-size: 80%;"><b>Agua</b></span>

                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_agua" id="tipo_agua_1" class="custom-control-input localStorage-radio" value="A">
                                                <label class="custom-control-label" for="tipo_agua_1">Acueducto</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_agua" id="tipo_agua_2" class="custom-control-input localStorage-radio" value="C">
                                                <label class="custom-control-label" for="tipo_agua_2">Cisterna</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_agua" id="tipo_agua_3" class="custom-control-input localStorage-radio" value="P">
                                                <label class="custom-control-label" for="tipo_agua_3">Pozo</label>
                                            </div>

                                            <span class="d-inline-block text-info w-100 mt-1" style="font-size: 80%;"><b>Electricidad</b></span>
                                            
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_electricidad" id="tipo_electricidad_1" class="custom-control-input localStorage-radio" value="L">
                                                <label class="custom-control-label" for="tipo_electricidad_1">Legal</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_electricidad" id="tipo_electricidad_2" class="custom-control-input localStorage-radio" value="I">
                                                <label class="custom-control-label" for="tipo_electricidad_2">Ilegal</label>
                                            </div>
                                        </td>

                                        <td class="px-2 py-1">
                                            <span class="d-inline-block text-info w-100" style="font-size: 80%;"><b>Excretas</b></span>
                                            
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_excreta" id="tipo_excreta_1" class="custom-control-input localStorage-radio" value="A">
                                                <label class="custom-control-label" for="tipo_excreta_1">Cloacas</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_excreta" id="tipo_excreta_2" class="custom-control-input localStorage-radio" value="P">
                                                <label class="custom-control-label" for="tipo_excreta_2">Letrina</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: 100%">
                                                <input type="radio" name="tipo_excreta" id="tipo_excreta_3" class="custom-control-input localStorage-radio" value="C">
                                                <label class="custom-control-label" for="tipo_excreta_3">Pozo septico</label>
                                            </div>

                                            <span class="d-inline-block text-info w-100 mt-1" style="font-size: 80%;"><b>Basura</b></span>
                                        
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_basura" id="tipo_basura_1" class="custom-control-input localStorage-radio" value="U">
                                                <label class="custom-control-label" for="tipo_basura_1">Aseo úrbano</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block" style="width: calc(50% - 3px);">
                                                <input type="radio" name="tipo_basura" id="tipo_basura_2" class="custom-control-input localStorage-radio" value="Q">
                                                <label class="custom-control-label" for="tipo_basura_2">Quema</label>
                                            </div>

                                            <div class="form-group form-row align-items-center mt-2 mb-1">
                                                <label for="otros" class="col-sm-4 col-form-label py-0" style="font-size: 13px;">Otros:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="otros" id="otros" class="form-control form-control-sm localStorage">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <p class="small font-weight-bold text-info mb-0">Materiales de construcción predominantes:</p>
                    <div class="form-row">
                        <!-- MATERIALES DEL TECHO -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="techo" class="small m-0">Techo <span class="text-danger">*</span></label>
                                <input type="text" name="techo" id="techo" class="form-control form-control-sm localStorage" placeholder="Ingrese los materiales del techo" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- MATERIALES DE LA PARED -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="pared" class="small m-0">Pared <span class="text-danger">*</span></label>
                                <input type="text" name="pared" id="pared" class="form-control form-control-sm localStorage" placeholder="Ingrese los materiales de la pared" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- MATERIALES DEL PISO -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="piso" class="small m-0">Piso <span class="text-danger">*</span></label>
                                <input type="text" name="piso" id="piso" class="form-control form-control-sm localStorage" placeholder="Ingrese los materiales del piso" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- DESCRIPCION DE LA VIA DE ACCESO -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="form-group mb-2">
                                <label for="via_acceso" class="small m-0">Via de acceso <span class="text-danger">*</span></label>
                                <input type="text" name="via_acceso" id="via_acceso" class="form-control form-control-sm localStorage" placeholder="Descripción de la via de acceso" autocomplete="off"/>
                            </div>
                        </div>
                    </div>

                    <p class="small font-weight-bold text-info mb-0">Distribución de la vivienda (coloque el número de ambientes)</p>
                    <div class="form-row">
                        <!-- NUMERO DE SALAS -->
                        <div class="col-sm-4 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="sala" class="small m-0">Sala <span class="text-danger">*</span></label>
                                <input type="text" name="sala" id="sala" placeholder="N° de salas" class="form-control form-control-sm localStorage" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE COMEDORES -->
                        <div class="col-sm-4 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="comedor" class="small m-0">Comedor <span class="text-danger">*</span></label>
                                <input type="text" name="comedor" id="comedor" class="form-control form-control-sm localStorage" placeholder="N° de comedores" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE COCINAS -->
                        <div class="col-sm-4 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="cocina" class="small m-0">Cocina <span class="text-danger">*</span></label>
                                <input type="text" name="cocina" id="cocina" class="form-control form-control-sm localStorage" placeholder="N° de cocinas" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE BAÑOS -->
                        <div class="col-sm-4 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="bano" class="small m-0">Baños <span class="text-danger">*</span></label>
                                <input type="text" name="bano" id="bano" class="form-control form-control-sm localStorage" placeholder="N° de baños" autocomplete="off"/>
                            </div>
                        </div>

                        <!-- NUMERO DE DORMITORIOS -->
                        <div class="col-sm-4 col-lg-3 col-xl-2">
                            <div class="form-group mb-2">
                                <label for="dormitorio" class="small m-0">Domitorios <span class="text-danger">*</span></label>
                                <input type="text" name="dormitorio" id="dormitorio" class="form-control form-control-sm localStorage" placeholder="N° de dormitorios" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="datos_familiares" class="position-relative" style="display: none;">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Área socio familiar</h3>
                    <button type="button" id="agregar_familiar" class="btn btn-sm btn-info position-absolute" style="top: 25px; right: 0px;"><i class="fas fa-plus"></i></button>

                    <p class="small font-weight-bold text-secondary mb-0">Personas que habitan con el trabajador (a), iniciando desde el jefe del hogar.</p>
                    <div class="form-row">
                        <!-- ESTADO CIVIL Y GRADO DE INSTRUCCIÓN -->
                        <div class="col-sm-12 pb-2">
                            <div class="table-responsive pb-2">
                                <table id="tabla_datos_familiares" class="table table-borderless mb-0" style="min-width: 950px;">
                                    <thead class="text-light">
                                        <tr>
                                            <th class="bg-info rounded-left font-weight-normal text-center py-2 px-0" width="45">N°</th>
                                            <th class="bg-info font-weight-normal py-2 px-0">Apellido y nombre</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="132">Fecha de N.</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="60">Edad</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="60">Sexo</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="140">Parentesco</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="144">Ocupación</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="65">Trabaja</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="100">Ingresos</th>
                                            <th class="bg-info font-weight-normal py-2 px-0" width="40">Resp.</th>
                                            <th class="bg-info rounded-right" width="28"></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="datos_ingresos" style="display: none;">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Ingreso familiar</h3>
                
                    <div class="form-row">
                        <!-- TABLA DE INGRESOS DE TODA LA FAMILA EN GENERAL -->
                        <div class="col-sm-12 my-2 pb-2">
                            <div class="table-responsive pb-2">
                                <table class="table table-borderless mb-0" style="min-width: 700px;">
                                    <thead class="text-light">
                                        <tr>
                                            <th class="bg-info rounded-left font-weight-normal w-25 p-2">Ingreso</th>
                                            <th class="bg-info font-weight-normal w-25 p-2">Bolivares</th>
                                            <th class="bg-info font-weight-normal w-25 p-2">Egreso</th>
                                            <th class="bg-info rounded-right font-weight-normal w-25 p-2">Bolivares</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle px-1 pt-1 pb-0"><label for="ingreso_pension" class="small">Pensión</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_pension" id="ingreso_pension" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos localStorage" style="height: 50px;"></td>
                                            <td class="align-middle px-1 pt-1 pb-0"><label for="egreso_servicios" class="small">Gastos de servicios básicos (agua, luz, teléfono, etc.)</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_servicios" id="egreso_servicios" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos localStorage" style="height: 50px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="ingreso_seguro" class="small">Seguro social</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_seguro" id="ingreso_seguro" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos localStorage" style="height: 50px;"></td>
                                            <td class="align-middle px-1 py-0"><label for="egreso_alimentario" class="small">Alimentación</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_alimentario" id="egreso_alimentario" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos localStorage" style="height: 50px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="ingreso_pension_otras" class="small">Otras pensión</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_pension_otras" id="ingreso_pension_otras" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos localStorage" style="height: 50px;"></td>
                                            <td class="align-middle px-1 py-0"><label for="egreso_educacion" class="small">Educación</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_educacion" id="egreso_educacion" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos localStorage" style="height: 50px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="ingreso_sueldo" class="small">Sueldo  y/o salario</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="ingreso_sueldo" id="ingreso_sueldo" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos localStorage" style="height: 50px;"></td>
                                            <td class="align-middle px-1 py-0"><label for="egreso_vivienda" class="small">Vivienda(alquiler comdominio)</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="egreso_vivienda" id="egreso_vivienda" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos localStorage" style="height: 50px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle px-1 py-0"><label for="otros_ingresos" class="small">Otros ingresos</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="otros_ingresos" id="otros_ingresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_ingresos localStorage" style="height: 50px;"></td>
                                            <td class="align-middle px-1 py-0"><label for="otros_egresos" class="small">Otros egresos</label></td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="otros_egresos" id="otros_egresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos i_egresos localStorage" style="height: 50px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle small px-1 py-0">Total ingresos:</td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="total_ingresos" id="total_ingresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos_0 localStorage" style="height: 50px;" readonly="true"></td>
                                            <td class="align-middle small px-1 py-0">Total egresos:</td>
                                            <td class="px-1 pt-1 pb-0"><input type="text" name="total_egresos" id="total_egresos" class="form-control form-control-sm d-inline-block text-right campos_ingresos_0 localStorage" style="height: 50px;" readonly="true"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="datos_trabajadora_social" style="display: none;">
                    <h3 class="font-weight-normal text-secondary text-center text-uppercase">Para uso de la trabajadora social</h3>
                
                    <div class="form-row">
                        <!-- CONDICIONES DE LA VIVIENDA -->
                        <div class="col-lg-6 align-self-end">
                            <div class="form-group mb-2">
                                <label for="condicion_vivienda" class="small m-0">Condiciones generales de la vivienda <span class="text-danger">*</span></label>
                                <textarea name="condicion_vivienda" id="condicion_vivienda" class="form-control form-control-sm localStorage"></textarea>
                            </div>
                        </div>

                        <!-- RELACION FAMILIAR Y CONDICIONES SOCIOECONÓMICAS -->
                        <div class="col-lg-6">
                            <div class="form-group mb-2">
                                <label for="caracteristicas_generales" class="small m-0">Características generales de las relaciones familiares y sus condiciones socioeconómicas <span class="text-danger">*</span></label>
                                <textarea name="caracteristicas_generales" id="caracteristicas_generales" class="form-control form-control-sm localStorage"></textarea>
                            </div>
                        </div>

                        <!-- DIAGNOSTICO SOCIAL -->
                        <div class="col-lg-6">
                            <div class="form-group mb-2">
                                <label for="diagnostico_social" class="small m-0">Diagnostico social <span class="text-danger">*</span></label>
                                <textarea name="diagnostico_social" id="diagnostico_social" class="form-control form-control-sm localStorage"></textarea>
                            </div>
                        </div>

                        <!-- DIAGNOSTICO PRELIMINAR -->
                        <div class="col-lg-6">
                            <div class="form-group mb-2">
                                <label for="diagnostico_preliminar" class="small m-0">Diagnostico preliminar <span class="text-danger">*</span></label>
                                <textarea name="diagnostico_preliminar" id="diagnostico_preliminar" class="form-control form-control-sm localStorage"></textarea>
                            </div>
                        </div>

                        <!-- CONCLUSIONES Y RECOMENDACIONES -->
                        <div class="col-lg-6">
                            <div class="form-group mb-2">
                                <label for="conclusiones" class="small m-0">Conclusiones y recomendaciones <span class="text-danger">*</span></label>
                                <textarea name="conclusiones" id="conclusiones" class="form-control form-control-sm localStorage"></textarea>
                            </div>
                        </div>

                        <!-- ENFERMO EN EL GRUPO FAMILIAR -->
                        <div class="col-lg-6">
                            <p class="m-0 small">Ay algún enfermo en el grupo familiar <span class="text-danger">*</span></p>

                            <div class="custom-control custom-radio mr-2">
                                <input type="radio" name="enfermos" id="enfermo_si" class="custom-control-input localStorage-radio" value="S">
                                <label class="custom-control-label" for="enfermo_si">Si</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" name="enfermos" id="enfermo_no" class="custom-control-input localStorage-radio" value="N">
                                <label class="custom-control-label" for="enfermo_no">No</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="avanzar_form" class="btn btn-info rounded-circle move-form">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div id="carga_espera" class="position-absolute rounded w-100 h-100" style="top: 0px; left: 0px;display: none;">
                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                    <p class="h4 text-white m-0"><i class="fas fa-spinner fa-spin mr-3"></i><span>Cargando algunos datos...</span></p>
                </div>
            </div>
        </div>
        <!-- FIN CONTENEDOR -->

        <!-- BOTON GUARDAR DATOS -->
        <div class="pt-2 text-center">
            <button id="guardar_datos" type="button" class="btn btn-sm btn-info"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
        </div>
        <!-- FIN BOTON GUARDAR DATOS -->
    </form>
</div>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/informe_social.js"></script>