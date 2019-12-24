<?php
include_once('models/m_empresa.php');
$objeto = new model_empresa();
$objeto->conectar();
$estados = $objeto->consultarEstados();
$objeto->desconectar();
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php if (!isset($consulta)) echo 'Registrar'; else echo 'Modificar'; ?></h4>

    <a href="<?php echo SERVERURL.'intranet/informe_social'; ?>" class="btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">volver</span></a>
</div>

<form class="formulario" action="<?php echo SERVERURL.'controllers/c_empresa.php'; ?>" method="POST" name="formulario">
    <?php if (!isset($consulta)) { ?>
        <input type="hidden" name="opcion" value="Registrar"/>
    <?php } else { ?> 
        <input type="hidden" name="opcion" value="Modificar"/>
        <input type="hidden" name="numero" value="<?php echo $consulta['numero']; ?>"/>
    <?php } ?>

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
        <button type="button" id="retroceder_form" class="btn btn-info rounded-circle move-form">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div id="formularios" class="mx-2">
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

    <div id="indicador_estado" class="text-center">
        <i class="fas fa-check"></i>
        <i class="fas fa-check"></i>
        <i class="fas fa-check"></i>
        <i class="fas fa-check"></i>
    </div>

    <style>
        #formularios {
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
  
    <?php /*
        <div class="form-row">
            <div class="col-sm-12">
                <p class="m-0">Materiales de construcción predominantes:</p>
            </div>
            

            <div class="col-sm-12 mt-2">
                <p class="m-0">Distribución de la vivienda (coloque el numero de ambientes):</p>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-2">
            <h5 class="font-weight-normal m-0">Área socio familiar</h5>
            <button id="agregar" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></button>
        </div>
        <p class="m-0 small">Personas que habitan con el trabajador(a), iniciando desde el jefe del hogar.</p>
        <table class="table table-bordered mb-2">
            <tr class="">
                <th class="p-1 align-middle font-weight-normal">N°</th>
                <th class="p-1 align-middle font-weight-normal">Nombre</th>
                <th class="p-1 align-middle font-weight-normal">Nombre</th>
                <th class="p-1 align-middle font-weight-normal">Apellido</th>
                <th class="p-1 align-middle font-weight-normal">Apellido</th>
                <th class="p-1 align-middle font-weight-normal">Fecha N.</th>
                <th class="p-1 align-middle font-weight-normal">Sexo</th>
                <th class="p-1 align-middle font-weight-normal">Parentesco</th>
                <th class="p-1 align-middle font-weight-normal">Representante</th>
                <th class="p-1 align-middle font-weight-normal">Ocupacion</th>
                <th class="p-1 align-middle font-weight-normal">Trabaja</th>
                <th class="p-1 align-middle font-weight-normal">Ingresos</th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <h5 class="font-weight-normal">Opinión del trabajador(a) social</h5>
        <div class="form-row">
            <div class="col-sm-6">
                <div class="form-group has-warning mb-2">
                    <label for="condicion_vivienda" class="small m-0">Condiciones generales de la vivienda: <span class="text-danger">*</span></label>
                    <textarea name="condicion_vivienda" id="condicion_vivienda" class="form-control form-control-sm"  maxlength="150" placeholder="" required></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-warning mb-2">
                    <label for="caracteristicas_generales" class="small m-0">Características generales de las relaciones familiares y sus condiciones socioeconómicas: <span class="text-danger">*</span></label>
                    <textarea name="caracteristicas_generales" id="caracteristicas_generales" class="form-control form-control-sm"  maxlength="150" placeholder="" required></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-warning mb-2">
                    <label for="diagnostico_social" class="small m-0">Diagnostico social: <span class="text-danger">*</span></label>
                    <textarea name="diagnostico_social" id="diagnostico_social" class="form-control form-control-sm"  maxlength="150" placeholder="" required></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-warning mb-2">
                    <label for="diagnostico_preliminar" class="small m-0">Diagnostico preliminar: <span class="text-danger">*</span></label>
                    <textarea name="diagnostico_preliminar" id="diagnostico_preliminar" class="form-control form-control-sm"  maxlength="150" placeholder="" required></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-warning mb-2">
                    <label for="conclusiones" class="small m-0">Conclusiones y recomendaciones: <span class="text-danger">*</span></label>
                    <textarea name="conclusiones" id="conclusiones" class="form-control form-control-sm"  maxlength="150" placeholder="" required></textarea>
                </div>
            </div>

            <div class="col-sm-6 align-self-center">
                <p class="m-0 small">Ay algún enfermo en el grupo familiar: </p>
                <div class="d-flex">
                    <div class="custom-control custom-radio mr-2">
                        <input type="radio" class="custom-control-input" id="enfermo_si" name="enfermos" value="N" required>
                        <label class="custom-control-label" for="enfermo_si">Si</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="enfermo_no" name="enfermos" value="S" required>
                        <label class="custom-control-label" for="enfermo_no">No</label>
                    </div>
                </div>
            </div>
        </div>
    */ ?>

    <div class="pt-2 text-center">
        <button class="btn btn-sm btn-info w-25"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
    </div>
</form>

<script>
    $(function () {
        $('#estado').change(buscarCiudades);
        function buscarCiudades()
        {
            var valor;
            var ciudad = '';
            if ($(this).attr('id') == 'estado')
            {
                valor = $('#estado').val();
                ciudad = '#ciudad';
            }
            else
            {
                valor = $('#estado_c').val();
                ciudad = '#ciudad_c';
            }

            if (valor != '')
            {
                // REALIZAMOS LA CONSULTA ATRAVES DE AJAX
				$.ajax({
					url: "../controllers/c_empresa.php",
					type: "POST",
					data:
					{
						opcion  : 'Consultar ciudad',
						estado  : valor
					},
					// SI LA CONSULTA FUE EXISTOSA PROCEDE A MOSTRAR LOS DATOS.
					success: function (respuesta)
					{
                        console.log(respuesta);
						if (respuesta == 'No hay')
						{
							$(ciudad).html('<option value="">No hay ciudades</option>');
						}
						else
						{
							// USAMOS UN TRY CATCH POR SI HAY ERRORES A DECODIFICAR EL MENSAJE JSON.
                            try
                            {
                                var datos = JSON.parse(respuesta);
                                var contenido = '<option value="">Elija una opción</option>';
                                for (var i in datos)
                                {
                                    contenido += '<option value="'+datos[i].codigo+'">'+datos[i].nombre+'</option>';
                                }
                                $(ciudad).html(contenido);
                            }
                            // SI HAY ALGUN ERROR, PROCEDEMOS A MOSTRAR EL ERROR.
                            // POR LO GENERAL ES UN ERROR PROVENIENTE DE PHP CAUSADA EN LA CONSULTA.
                            catch (error)
                            {
                                console.log(respuesta);
                            }
						}
					},
					// SI HUBO UN ERROR, MOSTRAMOS UN MENSAJE DE ERROR.
					error: function ()
					{
						swal({
							title	: 'Error de conexión',
							text	: 'No se pudo hacer la consulta, revise su conexión he intente nuevamente.',
							icon	: 'error',
							timer	: 4000
						});
					},
					timeout: 15000
				});
            }
            else
            {
                $(ciudad).html('<option value="">Elija un estado</option>');
            }
        }

        let vista = 1;

        $('#retroceder_form').click(retrocederForm);
        function retrocederForm (e)
        {
            if (vista == 2)
            {
                $('#datos_extras').hide(300);
                $('#datos_aprendiz').show(300);
            }
            else if (vista == 3)
            {
                $('#datos_vivienda').hide(300);
                $('#datos_extras').show(300);
            }
            else if (vista == 4) {
                $('#datos_familiares').hide(300);
                $('#datos_vivienda').show(300);
            }

            if (vista != 1)
                vista--;
        }

        $('#avanzar_form').click(avanzarForm);
        function avanzarForm (e)
        {
            if (vista == 1)
            {
                $('#datos_aprendiz').hide(300);
                $('#datos_extras').show(300);
            }
            else if (vista == 2)
            {
                $('#datos_extras').hide(300);
                $('#datos_vivienda').show(300);
            }
            else if (vista == 3) {
                $('#datos_vivienda').hide(300);
                $('#datos_familiares').show(300);
            }
            
            if (vista != 4)
                vista++;
        }

        $('#agregar_familiar').click(agregarFila);
        function agregarFila ()
        {
            let cantidad = 1;
            // let cantidad = $('#tabla_datos_familiares tbody tr').length();
            let contenido = '<tr>';
            contenido += '<td class="py-2">'+cantidad+'</td>';
            contenido += '<td class="align-middle p-0"><input type="text" name="nombre_apellido[]" class="form-control form-control-sm"></td>';
            contenido += '<td class="align-middle p-0"><input type="date" name="fecha_nf[]" class="form-control form-control-sm"></td>';
            contenido += '<td class="align-middle p-0"><input type="date" name="fecha_nf[]" class="form-control form-control-sm"></td>';
            contenido += '</tr>';
            $('#tabla_datos_familiares tbody').append(contenido);
        }
    });
</script>

<script src="<?php echo SERVERURL; ?>javascripts\gestion_informe_social.js"></script>