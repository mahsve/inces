<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_ocupacion.php');
    $objeto = new model_ocupacion;
    
    switch ($_POST['opcion']) {
        case 'Registrar':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaR($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarOcupacion($_POST)) {
                    echo 'Registro exitoso';
                } else {
                    echo 'Registro fallido';
                }
            } else {
                echo 'Ya está registrado';
            }
            $objeto->desconectar();
        break;

        case 'Consultar':
            $resultados = [];
            $objeto->conectar();
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $_POST['ordenar_tipo'] = 'ASC';
            if ($_POST['tipo_ord'] == 1)
                $_POST['ordenar_tipo'] = 'ASC';
            else if ($_POST['tipo_ord'] == 2)
                $_POST['ordenar_tipo'] = 'DESC';
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $_POST['ordenar_por'] = 'codigo '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $_POST['ordenar_por'] = 'codigo '.$_POST['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $_POST['ordenar_por'] = 'nombre '.$_POST['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarOcupaciones($_POST);
            $resultados['total']        = $objeto->consultarOcupacionesTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Modificar':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaM($_POST) == 0) {
                // SE PROCEDE A MODIFICAR
                if ($objeto->modificarOcupacion($_POST)) {
                    echo 'Modificación exitosa';
                } else {
                    echo 'Modificación fallida';
                }
            } else {
                echo 'Ya está registrado';
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusOcupacion($_POST)) {
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
    }
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}