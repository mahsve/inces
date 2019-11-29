<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_modulo_sistema.php');
    $objeto = new model_modulo_sistema;
    
    switch ($_POST['opcion']) {
        case 'Registrar';
            $datos = [
                'nombre'    => htmlspecialchars($_POST['nombre']),
                'posicion'  => htmlspecialchars($_POST['posicion']),
                'icon'      => htmlspecialchars($_POST['icon'])
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
            header('Location: ../intranet/gestion_modulo_sistema');
            break;

        case 'Modificar';
            $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo']),
                'nombre'    => htmlspecialchars($_POST['nombre']),
                'posicion'  => htmlspecialchars($_POST['posicion']),
                'icon'      => htmlspecialchars($_POST['icon'])
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
            header('Location: ../intranet/modulo_sistema');
            break;

        case 'Eliminar':
            $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo'])
            ];

            $objeto->conectar();
            $resultado = $objeto->eliminarModulo($datos);
            if ($resultado)
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'success';
                $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha eliminado con exito.';
            }
            else
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al eliminar.';
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