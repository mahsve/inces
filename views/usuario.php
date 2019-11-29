<?php
include_once('models/m_usuario.php');
$objeto = new model_usuario();
$objeto->conectar();
$usuarios = $objeto->consultarUsuario();

$datos = ['vista' => $dataGET[0]];
$permisos = $usuario->permisos($datos);
$objeto->desconectar();
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>

    <?php if ($permisos['registrar']) { ?>
        <a href="gestion_usuario" class="btn btn-sm btn-info"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></a>
    <?php } ?>
</div>

<table id="listado" class="table table-striped table-hover w-100">
    <thead class="">
        <tr class="text-capitalize">
            <th width="100px" class="font-weight-normal pl-2">Usuario</th>
            <th width="80px" class="font-weight-normal pl-2">Cedula</th>
            <th class="font-weight-normal pl-2">Nombre completo</th>
            <th class="font-weight-normal pl-2">Sexo</th>
            <th class="font-weight-normal pl-2">Rol</th>
            <th class="font-weight-normal pl-2">Estatus</th>
            <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
                <th width="75px" class="font-weight-normal p-2"></th>
            <?php } ?>
        </tr>
    </thead>

    <tbody>
        <?php
        if ($usuarios) {
            foreach ($usuarios as $datos) {
                if ($datos['usuario'] != $_SESSION['usuario']['usuario']) {
        ?>
        <tr class="text-capitalize">
            <td><?php echo $datos['usuario']; ?></td>
            <td><?php echo $datos['nacionalidad'].'-'.$datos['cedula']; ?></td>
            <td><?php
                echo $datos['nombre1'];
                if ($datos['nombre2'] != NULL AND $datos['nombre2'] != '')
                    echo ' '.$datos['nombre2'];
                echo ' '.$datos['apellido1']; 
                if ($datos['apellido2'] != NULL AND $datos['apellido2'] != '')
                    echo ' '.$datos['apellido2'];
            ?></td>
            <td><?php
                if ($datos['sexo'] == 'M')
                    echo 'Masculino';
                else if ($datos['sexo'] == 'F')
                    echo 'Femenino';
                else
                    echo 'Indefinido';
            ?></td>
            <td><?php echo $datos['nombre']; ?></td>
            <td><?php
                if ($datos['estatus'] == 'A') // ACTIVO Y FUNCIONANDO.
                    echo 'Activo';
                else if ($datos['estatus'] == 'C') // CANCELADO POR EL ADMINISTRADOR.
                    echo 'Cancelado';
                else if ($datos['estatus'] == 'B') // BLOQUEADO POR INTENTOS FALLIDOS AL INICIAR SESION.
                    echo 'Bloqueado';
            ?></td>

            <?php if ($permisos['modificar'] OR $permisos['act_desc'] OR $permisos['eliminar']) { ?>
            <td class="align-middle p-2">
                <?php if ($permisos['modificar']) { ?>
                    <a href="<?php echo 'gestion_usuario/'.$datos['codigo']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
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
        }
        ?>
    </tbody>
</table>