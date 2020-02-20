<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d', time());

if ($_POST['opcion'])
{
    require_once('../models/m_facilitador.php');
    $objeto = new model_facilitador;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['fecha']    = $date;
            $resultados['ocupacion']= $objeto->consultarOcupaciones();
            $resultados['oficio']   = $objeto->consultarOficios();
            $resultados['estado']   = $objeto->consultarEstados();
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

        case 'Registrar':
            $objeto->conectar();
            if ($objeto->registrarFacilitador($_POST)) {
                echo 'Registro exitoso';
            } else {
                echo 'Registro fallido';
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
            $_POST['ordenar_por'] = 't_datos_personales.cedula '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $_POST['ordenar_por'] = 't_datos_personales.cedula '.$_POST['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarDatosPersonales($_POST);
            $resultados['total']        = $objeto->consultarDatosPersonalesTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $objeto->conectar();
            if ($objeto->modificarFacilitador($_POST)) {
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusFacilitador($_POST)) {
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