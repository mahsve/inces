<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_modulo_oficio.php');
    $objeto = new model_modulo_oficio;
    
    switch ($_POST['opcion']) {
        case 'Registrar';
            $datos = [
                'oficio'    => htmlspecialchars($_POST['oficio']),
                'nombre'    => htmlspecialchars($_POST['nombre'])
            ];

            $objeto->conectar();
            $resultado = $objeto->registrarModulo($datos);
            if ($resultado)
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'success';
                $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha registrado con exito.';
            }
            else
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al registrar.';
            }
            $objeto->desconectar();
            header('Location: ../intranet/gestion_modulo_oficio');
            break;

        case 'Modificar';
            $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo']),
                'oficio'    => htmlspecialchars($_POST['oficio']),
                'nombre'    => htmlspecialchars($_POST['nombre'])
            ];

            $objeto->conectar();
            $resultado = $objeto->modificarModulo($datos);
            if ($resultado)
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'success';
                $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha modificado con exito.';
            }
            else
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'info';
                $_SESSION['msj']['text'] = '<i class="fas fa-info mr-2"></i>Sin modificaciones.';
            }
            $objeto->desconectar();
            header('Location: ../intranet/modulo_oficio');
            break;

        case 'Estatus':
            if ($_POST['estatus'] == 'A')
                $estatus = 'I';
            else
                $estatus = 'A';

            $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo']),
                'estatus'   => htmlspecialchars($estatus)
            ];

            $objeto->conectar();
            $resultado = $objeto->estatusModulo($datos);
            if ($resultado)
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'success';
                $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Estatus actualizado.';
            }
            else
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Error al modificar el estatus.';
            }

            $objeto->desconectar();
            break;
    }
}
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
else
{
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}