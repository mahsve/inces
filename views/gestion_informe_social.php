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

    <div class="d-flex justify-content-between align-items-end rounded shadow-sm mb-3 p-2">
        <div>
            <i class="fas fa-plus btn btn-sm btn-primary"></i>
        </div>

        <div class="form-group has-warning m-0">
            <label for="fecha" class="small m-0">Fecha <span class="text-danger">*</span></label>
            <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" required/>
        </div>
    </div>

    <!-- CONTENEDOR -->
    <div class="position-relative rounded shadow-sm mb-3 p-2">
        <div id="datos_aprendiz" class="rounded">
            <h3 class="font-weight-light text-secondary text-center">Datos del aprendiz</h3>

            <div class="form-row px-2">
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

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="cedula" id="cedula" maxlength="11" placeholder="Ingrese la cédula" required/>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="nombre_1" id="nombre_1" maxlength="11" placeholder="Ingrese el nombre" required/>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="nombre_2" class="small m-0">Segundo nombre</label>
                        <input type="text" class="form-control form-control-sm" name="nombre_2" id="nombre_2" maxlength="11" placeholder="Ingrese el nombre"/>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="apellido_1" id="apellido_1" maxlength="11" placeholder="Ingrese el apellido" required/>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                        <input type="text" class="form-control form-control-sm" name="apellido_2" id="apellido_2" maxlength="11" placeholder="Ingrese el apellido"/>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="fecha_n" class="small m-0">Fecha de nacimiento<span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" name="fecha_n" id="fecha_n"/>
                    </div>
                </div>

                <div class="col-sm-3 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="lugar_n" class="small m-0">Lugar de nacimiento</label>
                        <input type="text" class="form-control form-control-sm" name="lugar_n" id="lugar_n" maxlength="100" placeholder="Ingrese el lugar de nacimiento"/>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="form-group has-warning mb-2">
                        <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                        <select class="custom-select custom-select-sm" name="sexo" id="sexo" required>
                            <option value="">Elija una opción</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="datos_hogar" class="rounded" style="display: none;">
            <h3 class="font-weight-light text-secondary text-center">Datos del hogar</h3>
        </div>

        <a href="#" id="retroceder_form" class="position-absolute" style="top:10px; left: 10px;"><i class="fas fa-chevron-left"></i></a>
        <a href="#" id="avanzar_form" class="position-absolute" style="top:10px; right: 10px;"><i class="fas fa-chevron-right"></i></a>
    </div>
    <!-- FIN CONTENEDOR -->
  
    <?php /*
    
    <div class="form-row">
        

        

        

        

        

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="estado_civil" class="small m-0">Estado civil <span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="estado_civil" id="estado_civil" required>
                    <option value="">Elija una opción</option>
                    <option value="S">Soltero</option>
                    <option value="C">Casado</option>
                    <option value="D">Divorciado</option>
                    <option value="V">Viudo</option>
                </select>
            </div>
        </div>

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

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="nivel_instruccion" class="small m-0">Nivel de instrucción<span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="nivel_instruccion" id="nivel_instruccion" required>
                    <option value="">Elija una opción</option>
                    <option value="BI">Educ. Basica incompleta</option>
                    <option value="BC">Educ. Basica completa</option>
                    <option value="MI">Educ. Media incompleta</option>
                    <option value="MC">Educ. Media completa</option>
                    <option value="SI">Educ. Superior incompleta</option>
                    <option value="SC">Educ. Superior completa</option>
                </select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="telefono_1" class="small m-0">Telefono 1 <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="telefono_1" id="telefono_1" maxlength="11" placeholder="Ingrese el telefono"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="telefono_2" class="small m-0">Telefono 2 </label>
                <input type="text" class="form-control form-control-sm" name="telefono_2" id="telefono_2" maxlength="11" placeholder="Ingrese el telefono"/>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="correo" class="small m-0">Correo</label>
                <input type="email" class="form-control form-control-sm" name="correo" id="correo" maxlength="60" placeholder="Ingrese el correo"/>
            </div>
        </div>

        <div class="col-sm-4">
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

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="facilitador" class="small m-0">Nombre del facilitador<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="facilitador" id="facilitador" maxlength="11" placeholder="Ingrese el nombre del facilitador"/>
            </div>
        </div>

        <div class="col-sm-3">
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

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="ciudad" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="ciudad" id="ciudad" required>
                    <option value="">Elija un estado</option>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="municipio" class="small m-0">Municipio</label>
                <select class="custom-select custom-select-sm" name="municipio" id="municipio" required>
                    <option value="">Elija un estado</option>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="parroquia" class="small m-0">Parroquia</label>
                <select class="custom-select custom-select-sm" name="parroquia" id="parroquia" required>
                    <option value="">Elija un estado</option>
                </select>
            </div>
        </div>

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

        <div class="col-sm-6">
            <div class="form-group has-warning mb-2">
                <label for="direccion" class="small m-0">Dirección <span class="text-danger">*</span></label>
                <textarea name="direccion" id="direccion" class="form-control form-control-sm"  maxlength="150" placeholder="Ingrese la dirección del hogar" required></textarea>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group has-warning mb-2">
                <label for="punto_referencia" class="small m-0">Punto de referencia <span class="text-danger">*</span></label>
                <textarea name="punto_referencia" id="punto_referencia" class="form-control form-control-sm"  maxlength="150" placeholder="Ingrese punto de refencia" required></textarea>
            </div>
        </div>
    </div>

    <h5 class="font-weight-normal mt-3">Características de la vivienda</h5>
    <table class="table table-bordered mb-2">
        <tr>
            <th class="w-25 py-0 align-middle">Tipo de vivienda en la que habita  actualmente:</th>
            <th class="w-25 py-0 align-middle">Tenencia de la vivienda:</th>
            <th class="w-50 py-0 align-middle" colspan="2">Servicios publicos disponibles:</th>
        </tr>

        <tr>
            <td rowspan="2" class="py-0">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="quinta" name="tipo_vivienda" value="Q" required>
                    <label class="custom-control-label" for="quinta">Quinta</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="casa" name="tipo_vivienda" value="C" required>
                    <label class="custom-control-label" for="casa">Casa</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="apartamento" name="tipo_vivienda" value="A" required>
                    <label class="custom-control-label" for="apartamento">Apartamento</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="rancho" name="tipo_vivienda" value="R" required>
                    <label class="custom-control-label" for="rancho">Rancho</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="otros" name="tipo_vivienda" value="O" required>
                    <label class="custom-control-label" for="otros">Otros</label>
                </div>
            </td>

            <td rowspan="2" class="py-0">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="propia" name="tendencia_vivienda" value="P" required>
                    <label class="custom-control-label" for="propia">Propia</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="alquilada" name="tendencia_vivienda" value="A" required>
                    <label class="custom-control-label" for="alquilada">Alquilada</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="prestada" name="tendencia_vivienda" value="E" required>
                    <label class="custom-control-label" for="prestada">Prestada</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="invadida" name="tendencia_vivienda" value="I" required>
                    <label class="custom-control-label" for="invadida">Invadida</label>
                </div>
            </td>

            <td class="py-0">
                <p class="m-0">Agua:</p>
                <div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="acueducto" name="tipo_agua" value="A" required>
                        <label class="custom-control-label" for="acueducto">Acueducto</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="cisterna" name="tipo_agua" value="C" required>
                        <label class="custom-control-label" for="cisterna">Cisterna</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="pozo" name="tipo_agua" value="P" required>
                        <label class="custom-control-label" for="pozo">Pozo</label>
                    </div>
                </div>
            </td>

            <td class="py-0">
                <p class="m-0">Excretas:</p>
                <div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="cloacas" name="tipo_excreta" value="A" required>
                        <label class="custom-control-label" for="cloacas">Cloacas</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="pozo_septico" name="tipo_excreta" value="C" required>
                        <label class="custom-control-label" for="pozo_septico">Pozo septico</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="letrina" name="tipo_excreta" value="P" required>
                        <label class="custom-control-label" for="letrina">Letrina</label>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td class="py-0">
                <p class="m-0">Electricidad:</p>
                <div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="legal" name="tipo_electricidad" value="L" required>
                        <label class="custom-control-label" for="legal">Legal</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="ilegal" name="tipo_electricidad" value="I" required>
                        <label class="custom-control-label" for="ilegal">Ilegal</label>
                    </div>
                </div>
            </td>

            <td class="py-0">
                <p class="m-0">Basura:</p>
                <div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="aseo_urbano" name="tipo_basura" value="U" required>
                        <label class="custom-control-label" for="aseo_urbano">Aseo úrbano</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="quema" name="tipo_basura" value="Q" required>
                        <label class="custom-control-label" for="quema">Quema</label>
                    </div>
                    <div class="custom-control custom-radio d-inline mr-1">
                        <input type="radio" class="custom-control-input" id="otros_basura" name="tipo_basura" value="O" required>
                        <label class="custom-control-label" for="otros_basura">Pozo</label>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div class="form-row">
        <div class="col-sm-12">
            <p class="m-0">Materiales de construcción predominantes:</p>
        </div>
        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="techo" class="small m-0">Techo <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="techo" id="techo" maxlength="50" placeholder="Ingrese los materiales del techo" required/>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="pared" class="small m-0">Pared <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="pared" id="pared" maxlength="50" placeholder="Ingrese los materiales de la apred" required/>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="piso" class="small m-0">Piso <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="piso" id="piso" maxlength="50" placeholder="Ingrese los materiales del piso" required/>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="via_acceso" class="small m-0">Via de acceso <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="via_acceso" id="via_acceso" maxlength="50" placeholder="Ingrese los materiales de la via de acceso" required/>
            </div>
        </div>

        <div class="col-sm-12 mt-2">
            <p class="m-0">Distribución de la vivienda (coloque el numero de ambientes):</p>
        </div>
        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="sala" class="small m-0">Sala <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="sala" id="sala" maxlength="2" placeholder="N° de salas" required/>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="comedor" class="small m-0">Comedor <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="comedor" id="comedor" maxlength="2" placeholder="N° de comedores" required/>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="cocina" class="small m-0">Cocina <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="cocina" id="cocina" maxlength="2" placeholder="N° de cocinas" required/>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="bano" class="small m-0">Baños <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="bano" id="bano" maxlength="2" placeholder="N° de baños" required/>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="dormitorio" class="small m-0">Domitorios <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="dormitorio" id="dormitorio" maxlength="2" placeholder="N° de dormitorios" required/>
            </div>
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
    });
</script>

<script src="<?php echo SERVERURL; ?>javascripts\gestion_informe_social.js"></script>