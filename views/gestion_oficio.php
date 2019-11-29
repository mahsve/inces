<?php
    // VERIFICAMOS SI EXISTE LA VARIABLE QUE CONTIENE EL CODIGO O SI ESTA VACIO.
    if (isset($dataGET[1]) AND !empty($dataGET[1]))
    {
        include_once('models/m_oficio.php'); // INCLUIMOS EL MODELO PARA CONSULTAR A LA BASE DE DATOS.
        $objeto = new model_oficio(); // CREAMOS UN NUEVO OBJETO PARA ACCEDER A LOS METODOS.

        $arreglo = ['codigo' => htmlspecialchars($dataGET[1])]; // GUARDAMOS LOS DATOS EN UN ARREGLO.

        $objeto->conectar(); // CONECTAMOS A LA BASE DE DATOS.
        $consulta = $objeto->consultarOficio($arreglo); // CONSULTAMOS EL ROL CON SU CODIGO.
        $objeto->desconectar(); // DESCONECTAMOS.

        if (!$consulta) // VERIFICAMOS QUE EXISTA EL ROL
        {
            $_SESSION['msj2']['type'] = 'danger';
            $_SESSION['msj2']['text'] = '<i class="fas fa-times"></i> El código es erróneo.';
            
            // REDIRECCIONAMOS A LA PAGINA DEL LISTADO.
            echo "<script>location.href = '../rol'</script>";
        }
    }
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php if (!isset($consulta)) echo 'Registrar'; else echo 'Modificar'; ?></h4>

    <a href="<?php echo SERVERURL.'intranet/oficio'; ?>" class="btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">volver</span></a>
</div>

<form class="formulario" action="<?php echo SERVERURL.'controllers/c_oficio.php'; ?>" method="POST" name="formulario">
    <?php if (!isset($consulta)) { ?>
        <input type="hidden" name="opcion" value="Registrar"/>
    <?php } else { ?> 
        <input type="hidden" name="opcion" value="Modificar"/>
        <input type="hidden" name="codigo2" value="<?php echo $consulta['codigo']; ?>"/>
    <?php } ?>
  
    <div class="form-row">
        <div class="offset-sm-3 col-sm-6">
            <div class="form-group has-warning mb-2">
                <label for="codigo" class="small m-0">Código <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="codigo" id="codigo" maxlength="12" placeholder="Ingrese el código" value="<?php if (isset($consulta)) { echo $consulta['codigo']; } ?>" required/>
            </div>
        </div>

        <div class="offset-sm-3 col-sm-6">
            <div class="form-group has-warning mb-2">
                <label for="nombre" class="small m-0">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" maxlength="100" placeholder="Ingrese el nombre" value="<?php if (isset($consulta)) { echo $consulta['nombre']; } ?>" required/>
            </div>
        </div>
    </div>

    <div class="pt-2 text-center">
        <button class="btn btn-sm btn-info w-25"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
    </div>
</form>