<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d', time());

if ($_POST['opcion'])
{
    require_once('../models/m_datos_personales.php');
    $objeto = new model_datos_personales;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $resultados = [];
            $resultados['fecha'] = $date;
            $objeto->conectar();
            $arreglo_datos = ['nacionalidad' => $_SESSION['usuario']['nacionalidad'], 'cedula' => $_SESSION['usuario']['cedula']];
            $resultados['datospersonales'] = $objeto->consultarMisDatos($arreglo_datos);
            $resultados['ocupacion'] = $objeto->consultarOcupaciones();
            $resultados['estado'] = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer divisiones':
            $resultados = [];
            $objeto->conectar();
            $resultados['ciudad'] = $objeto->consultarCiudades($_POST);
            $resultados['municipio'] = $objeto->consultarMunicipios($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer parroquias':
            $resultados = [];
            $objeto->conectar();
            $resultados['parroquia'] = $objeto->consultarParroquias($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $_POST['nacionalidad2'] = $_SESSION['usuario']['nacionalidad'];
            $_POST['cedula2'] = $_SESSION['usuario']['cedula'];

            $objeto->conectar();
            if ($objeto->modificarMisDatos($_POST)) {
                $_SESSION['usuario'] = $objeto->consultarDatosActualizados($_SESSION['usuario']['usuario']);
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
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