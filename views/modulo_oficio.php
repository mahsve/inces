<?php
include_once('models/m_modulo_oficio.php');
$objeto = new model_modulo_oficio();
$objeto->conectar();
$modulos = $objeto->consultarModulos();

$datos = ['vista' => $dataGET[0]];
$permisos = $usuario->permisos($datos);
$objeto->desconectar();
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>

    <?php if ($permisos['registrar']) { ?>
        <a href="gestion_modulo_oficio" class="btn btn-sm btn-info"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></a>
    <?php } ?>
</div>

<table id="listado" class="table table-bordered table-hover w-100">
    <thead class="">
        <tr class="text-capitalize">
            <th width="80px" class="font-weight-normal p-2">Código</th>
            <th class="font-weight-normal p-2">Nombre</th>
            <th width="80px" class="font-weight-normal p-2">Estatus</th>
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
            <td class="align-middle"><?php echo $datos['nombre'].' - '.$datos['noficio']; ?></td>
            <td class="align-middle text-center">
                <?php if ($datos['estatus'] == 'A') { ?>
                    <span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
                <?php } else { ?>
                    <span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
                <?php } ?>
            </td>
            
            <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
            <td class="align-middle p-2">
                <?php if ($permisos['modificar']) { ?>
                    <a href="<?php echo 'gestion_modulo_oficio/'.$datos['codigo']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
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

<script>
    $(function () {
        $('.cambiar_estatus').click(cambiarEstatus);
        function cambiarEstatus ()
        {
            var codigo = $(this).attr('data-codigo');
            var estatus = $(this).attr('data-estatus');
            
            $.ajax({
                url : '<?php echo SERVERURL.'controllers/c_modulo_oficio.php'; ?>',
                type: 'POST',
                data: {
                    opcion: 'Estatus',
                    codigo: codigo,
                    estatus: estatus
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