<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d', time());

if ($_POST['opcion'])
{
    require_once('../models/m_aprendiz.php');
    $objeto = new model_aprendiz;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['fecha'] = $date;
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

        case 'Traer aprendiz por ficha':
            $resultados = [];
            $objeto->conectar();
            $resultados = $objeto->consultarAprendizPorFicha($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer empresa':
            $resultados = [];
            $objeto->conectar();
            $resultados = $objeto->consultarEmpresas($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Registrar':
            $objeto->conectar();
            if ($objeto->registrarOficio($_POST)) {
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
            $_POST['ordenar_por'] = 't_ficha_aprendiz.fecha '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $_POST['ordenar_por'] = 't_ficha_aprendiz.fecha '.$_POST['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $_POST['ordenar_por'] = 't_datos_personales.cedula '.$_POST['ordenar_tipo'];
            else if ($_POST['ordenar'] == 3)
                $_POST['ordenar_por'] = 't_datos_personales.nombre1, t_datos_personales.nombre2, t_datos_personales.apellido1, t_datos_personales.apellido2 '.$_POST['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarPlanilla($_POST);
            $resultados['total']        = $objeto->consultarPlanillaTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }

            $objeto->conectar();
            if ($objeto->modificarOficio($data)) {
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }

            $objeto->conectar();
            if ($objeto->estatusOficio($data)) {
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