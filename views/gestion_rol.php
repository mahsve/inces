<?php
include_once('models/m_rol.php'); // INCLUIMOS EL MODELO PARA CONSULTAR A LA BASE DE DATOS.
$objeto = new model_rol(); // CREAMOS UN NUEVO OBJETO PARA ACCEDER A LOS METODOS.
$objeto->conectar(); // CONECTAMOS A LA BASE DE DATOS.
$modulos = $objeto->consultarModulos(); // CONSULTAMOS LOS OFICIOS DISPONIBLES.
$objeto->desconectar(); // DESCONECTAMOS.

// VERIFICAMOS SI EXISTE LA VARIABLE QUE CONTIENE EL CODIGO O SI ESTA VACIO.
if (isset($dataGET[1]) AND !empty($dataGET[1]))
{
    $arreglo = ['codigo' => htmlspecialchars($dataGET[1])]; // GUARDAMOS LOS DATOS EN UN ARREGLO.

    $objeto->conectar(); // CONECTAMOS A LA BASE DE DATOS.
    $consulta = $objeto->consultarRol($arreglo); // CONSULTAMOS EL ROL CON SU CODIGO.
    
    if (!$consulta) // VERIFICAMOS QUE EXISTA EL ROL
    {
        $_SESSION['msj2']['type'] = 'danger';
        $_SESSION['msj2']['text'] = '<i class="fas fa-times"></i> El código es erróneo.';
        
        // REDIRECCIONAMOS A LA PAGINA DEL LISTADO.
        echo "<script>location.href = '../rol'</script>";
    } else {
        $modulosDelRol = $objeto->consultarModulosDelRol($arreglo);
        $vistasDelRol = $objeto->consultarVistasDelRol($arreglo);
    }
    $objeto->desconectar(); // DESCONECTAMOS.
}
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php if (!isset($consulta)) echo 'Registrar'; else echo 'Modificar'; ?></h4>

    <a href="<?php echo SERVERURL.'intranet/rol'; ?>" class="btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">volver</span></a>
</div>

<form class="formulario" action="<?php echo SERVERURL.'controllers/c_rol.php'; ?>" method="POST" name="formulario">
    <?php if (!isset($consulta)) { ?>
        <input type="hidden" name="opcion" value="Registrar"/>
    <?php } else { ?> 
        <input type="hidden" name="opcion" value="Modificar"/>
        <input type="hidden" name="codigo" value="<?php echo $consulta['codigo']; ?>"/>
    <?php } ?>

    <div class="form-row justify-content-center">
        <div class="col-sm-6">
            <div class="form-group has-warning mb-2">
                <label for="nombre" class="small m-0">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" maxlength="100" placeholder="Ingrese el rol a crear" value="<?php if (isset($consulta)) { echo $consulta['nombre']; } ?>" required/>
            </div>
        </div>

        <div class="col-sm-7">
            <h5 class="font-weight-normal mt-2">Módulos</h5>
            <?php
            if ($modulos) {
                foreach ($modulos AS $datos) {
                    $checked = false;
                    if (isset($modulosDelRol) AND !empty($modulosDelRol)) {
                        foreach ($modulosDelRol AS $datos2) {
                            if ($datos['codigo'] == $datos2['codigo_modulo']) {
                                $checked = true;
                            }
                        }
                    }
            ?>
                <div class="border rounded position-relative p-2 mb-1">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" name="modulos[]" class="custom-control-input" id="modulo_<?php echo $datos['codigo']; ?>" value="<?php echo $datos['codigo']; ?>" <?php if ($checked) { echo 'checked'; } ?>>
                        <label class="custom-control-label" for="modulo_<?php echo $datos['codigo']; ?>"><?php echo $datos['nombre']; ?></label>
                    </div>

                    <button class="btn btn-sm position-absolute btn-info mostrar_vistas" type="button" data-toggle="collapse" data-target="#vistas_<?php echo $datos['codigo']; ?>" aria-expanded="false" aria-controls="vistas_<?php echo $datos['codigo']; ?>" style="right: 5px; top: 5px;"><i class="fas fa-plus"></i></button>
                    
                    <div id="vistas_<?php echo $datos['codigo']; ?>" class="collapse">
                        <div class="border rounded mt-2 p-2">
                            <?php
                            $objeto->conectar();
                            $arreglo = ['codigo' => $datos['codigo']];
                            $vistas = $objeto->consultarVistas($arreglo);
                            $objeto->desconectar();

                            if ($vistas) {
                            ?>
                            <h5 class="font-weight-normal border-bottom pb-1 mb-2">Vistas</h5>
                            <?php
                            foreach ($vistas AS $datos2) {
                                $checked2   = false;
                                $registrar  = false;
                                $modificar  = false;
                                $estatus    = false;
                                if (isset($vistasDelRol) AND !empty($vistasDelRol)) {
                                    foreach ($vistasDelRol AS $datos3) {
                                        if ($datos2['codigo'] == $datos3['codigo_vista']) {
                                            $checked2 = true;
                                            $registrar  = $datos3['registrar'];
                                            $modificar  = $datos3['modificar'];
                                            $estatus    = $datos3['act_desc'];
                                        }
                                    }
                                }
                            ?>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" name="vistas[]" class="custom-control-input" id="vista_<?php echo $datos2['codigo']; ?>" value="<?php echo $datos2['codigo']; ?>" <?php if ($checked2) { echo 'checked'; } ?>>
                                    <label class="custom-control-label" for="vista_<?php echo $datos2['codigo']; ?>"><?php echo $datos2['nombre']; ?></label>
                                </div>

                                <div class="pl-4 ml-2 pt-2 pb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" name="registrar<?php echo $datos2['codigo']; ?>" class="custom-control-input" id="gestionr_<?php echo $datos2['codigo']; ?>" value="1" <?php if ($registrar) { echo 'checked'; } ?>>
                                        <label class="custom-control-label" for="gestionr_<?php echo $datos2['codigo']; ?>">Registrar</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" name="modificar<?php echo $datos2['codigo']; ?>" class="custom-control-input" id="gestionm_<?php echo $datos2['codigo']; ?>" value="1" <?php if ($modificar) { echo 'checked'; } ?>>
                                        <label class="custom-control-label" for="gestionm_<?php echo $datos2['codigo']; ?>">Modificar</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" name="estatus<?php echo $datos2['codigo']; ?>" class="custom-control-input" id="gestione_<?php echo $datos2['codigo']; ?>" value="1" <?php if ($estatus) { echo 'checked'; } ?>>
                                        <label class="custom-control-label" for="gestione_<?php echo $datos2['codigo']; ?>">Gestionar estatus</label>
                                    </div>
                                </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            <?php
                }
            } ?>
        </div>
    </div>

    <div class="pt-2 text-center">
        <button class="btn btn-sm btn-info w-25"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
    </div>
</form>

<script>
    $(function ()
    {
        $('.mostrar_vistas').click(mostrarVistas);
        function mostrarVistas ()
        {
            if ($(this).hasClass('girar'))
            {
                $(this).removeClass('girar');
            }
            else
            {
                $(this).addClass('girar');
            }
        }
    });
</script>