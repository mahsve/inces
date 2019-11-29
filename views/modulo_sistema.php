<?php
include_once('models/m_modulo_sistema.php');
$objeto = new model_modulo_sistema();
$objeto->conectar();
$modulos = $objeto->consultarModulos();

$datos = ['vista' => $dataGET[0]];
$permisos = $usuario->permisos($datos);
$objeto->desconectar();
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>

    <?php if ($permisos['registrar']) { ?>
        <a href="gestion_modulo_sistema" class="btn btn-sm btn-info"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></a>
    <?php } ?>
</div>

<table id="listado" class="table table-bordered table-hover w-100">
    <thead class="">
        <tr class="text-capitalize">
            <th width="80px" class="font-weight-normal p-2">Código</th>
            <th class="font-weight-normal p-2">Nombre</th>
            <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
                <th width="75px" class="font-weight-normal p-2"></th>
            <?php } ?>
        </tr>
    </thead>

    <tbody>
        <?php
        if ($modulos) {
            foreach ($modulos AS $datos) {
        ?>
        <tr class="text-capitalize">
            <td class="align-middle text-right"><?php echo $datos['codigo']; ?></td>
            <td class="align-middle"><?php echo $datos['nombre']; ?></td>

            <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
            <td class="align-middle p-2">
                <?php if ($permisos['modificar']) { ?>
                    <a href="<?php echo 'gestion_modulo_sistema/'.$datos['codigo']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                <?php } ?>

                <?php
                if ($permisos['eliminar']) { ?>
                    <button class="btn btn-sm btn-danger eliminar" data-codigo="<?php echo $datos['codigo']; ?>"><i class="fas fa-trash"></i></button>
                <?php } ?>
            </td>
            <?php } ?>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<script>
    $(function () {
        $('.eliminar').click(eliminar);
        function eliminar ()
        {
            var codigo = $(this).attr('data-codigo');
            $.ajax({
                url : '<?php echo SERVERURL.'controllers/c_modulo_sistema.php'; ?>',
                type: 'POST',
                data: {
                    opcion: 'Eliminar',
                    codigo: codigo
                },
                success: function () {
                    location.reload();
                },
                error: function () {
                    swal({
                        text: 'Ocurrio un error, recargue e intente nuevamente',
                        icon: 'info',
                        timer: 5000,
                        buttons: false
                    });
                }
            });
        }
    });
</script>

<?php if (isset($_SESSION['msj2'])) {
    $_SESSION['msj'] = $_SESSION['msj2'];
    unset($_SESSION['msj2']);
} ?>