<?php 
session_start();
date_default_timezone_set("America/Caracas");
$fecha_actual = date('d-m-Y', time());

if ($_POST['opcion']) {
    require_once('../models/m_administrativo.php');
    $objeto = new model_administrativo;
    
    switch ($_POST['opcion']) {
        // CONSULTAR DATOS
        case 'Traer datos':
            $objeto->conectar();
            $resultados = [];
            $resultados['fecha']        = $fecha_actual;
            $resultados['ocupaciones']  = $objeto->consultarOcupaciones();
            $resultados['oficios']      = $objeto->consultarOficios();
            $resultados['estados']      = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer divisiones':
            $resultados = [];
            $objeto->conectar();
            $resultados['ciudades'] = $objeto->consultarCiudades($_POST);
            $resultados['municipios'] = $objeto->consultarMunicipios($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer parroquias':
            $resultados = [];
            $objeto->conectar();
            $resultados['parroquias'] = $objeto->consultarParroquias($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Verificar cedula':
            $objeto->conectar();
            $resultados = $objeto->verificarCedula($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        // FIN CONSULTAR DATOS
        

        // REGISTROS RAPIDOS
        case 'Registrar ocupacion':
            $resultados = [];
            $objeto->conectar();
            if ($objeto->confirmarExistenciaR_O($_POST) == 0) {
                if ($objeto->registrarOcupacion($_POST)) {
                    $resultados['ocupaciones']  = $objeto->consultarOcupaciones();
                    echo json_encode($resultados);
                } else {
                    echo json_encode('Registro fallido');
                }
            } else {
                echo json_encode('Ya está registrado');
            }
            $objeto->desconectar();
        break;
        // FIN REGISTROS RAPIDOS


        // OPERACIONES BASICAS
        case 'Registrar':
            // REORDENAMOS LA FECHA
            $fecha_c = $_POST['fecha_n'];   $_POST['fecha_n'] = date("Y-m-d", strtotime($fecha_c));

            $objeto->conectar();
            if ($objeto->registrarAdministrativo($_POST)) {
                echo 'Registro exitoso';
            } else {
                echo 'Registro fallido';
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
            $campo_ordenar = 'cedula '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 'cedula '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 2) { $campo_ordenar = 'CONCAT(nombre1, nombre2, apellido1, apellido2) '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarDatosPersonales($_POST);
            $resultados['total']        = $objeto->consultarDatosPersonalesTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            // REORDENAMOS LA FECHA
            $fecha_c = $_POST['fecha_n'];   $_POST['fecha_n'] = date("Y-m-d", strtotime($fecha_c));

            $objeto->conectar();
            if ($objeto->modificarAdministrativo($_POST)) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusAdministrador($_POST)) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
        // FIN OPERACIONES BASICAS
    }
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../iniciar');
}