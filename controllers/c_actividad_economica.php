<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_actividad_economica.php');
    $objeto = new model_actividad_economica;
    
    switch ($_POST['opcion']) {
        case 'Registrar':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaR($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarActividadEconomica($_POST)) {
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
            ////////////////////////////////////////////////////////////
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $campo_m_ordenar = 'ASC';
            if      ($_POST['campo_m_ordenar'] == 1) { $campo_m_ordenar = 'ASC'; }
            else if ($_POST['campo_m_ordenar'] == 2) { $campo_m_ordenar = 'DESC'; }
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $campo_ordenar = 'nombre '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 'nombre '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarActividadesEconomicas($_POST);
            $resultados['total']        = $objeto->consultarActividadesEconomicasTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Modificar':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaM($_POST) == 0) {
                // SE PROCEDE A MODIFICAR
                if ($objeto->modificarActividadEconomica($_POST)) {
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
            if ($objeto->estatusActividadEconomica($_POST)) {
                echo 'Modificación exitosa';
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