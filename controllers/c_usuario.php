<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_usuario.php');
    $objeto = new model_usuario;
    
    switch ($_POST['opcion']) {
        // CONSULTAR DATOS
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['roles']   = $objeto->consultarRoles();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        // FIN CONSULTAR DATOS


        // OPERACIONES BASICAS
        case 'Registrar':
            // ESTABLECEMOS LA CONTRASEÑA QUE SERA LA MISMA CEDULA
            $_POST['contrasena'] = password_hash($_POST['cedula'], PASSWORD_DEFAULT);

            $objeto->conectar();
            if ($objeto->registrarUsuario($_POST)) {
                echo 'Registro exitoso';
            } else {
                echo 'Registro fallido';
            }
            $objeto->desconectar();
        break;

        case 'Modificar':
            $objeto->conectar();
            if ($objeto->modificarUsuario($_POST)) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Consultar':
            // OBTENEMOS LA CEDULA DEL USUARIO
            $_POST['cedula_usuario']   = $_SESSION['usuario']['nacionalidad'].'-'.$_SESSION['usuario']['cedula'];

            $resultados = [];
            $objeto->conectar();
            ////////////////////////////////////////////////////////////
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $campo_m_ordenar = 'ASC';
            if      ($_POST['campo_m_ordenar'] == 1) { $campo_m_ordenar = 'ASC'; }
            else if ($_POST['campo_m_ordenar'] == 2) { $campo_m_ordenar = 'DESC'; }
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $campo_ordenar = 't_datos_personales.cedula '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 't_datos_personales.cedula '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarUsuarios($_POST);
            $resultados['total']        = $objeto->consultarUsuariosTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($_POST['estatus'] == 'C') {
                $estatus = $objeto->cancelarUsuario($_POST);
            } else {
                // ESTABLECEMOS LA CONTRASEÑA QUE SERA LA MISMA CEDULA
                $_POST['contrasena'] = password_hash($_POST['cedula'], PASSWORD_DEFAULT);
                $estatus = $objeto->restablecerUsuario($_POST);
            }

            if ($estatus) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
        // FIN OPERACIONES BASICAS
    }
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}