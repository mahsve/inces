<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_ocupacion.php');
    $objeto = new model_ocupacion;
    
    switch ($_POST['opcion']) {
        case 'Registrar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }

            $objeto->conectar();
            $resultado = $objeto->registrarOcupacion($data);
            if ($resultado) {
                echo 'Registro exitoso';
            } else {
                echo 'Registro fallido';
            }
            $objeto->desconectar();
        break;

        case 'Consultar':
            $resultados = [];
            $objeto->conectar();
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $datosLimpios = [];
            foreach ($_POST as $posicion => $valor) {
                $datosLimpios[$posicion] = htmlspecialchars($valor);
            }
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $datosLimpios['ordenar_tipo'] = 'ASC';
            if ($_POST['tipo_ord'] == 1)
                $datosLimpios['ordenar_tipo'] = 'ASC';
            else if ($_POST['tipo_ord'] == 2)
                $datosLimpios['ordenar_tipo'] = 'DESC';
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $datosLimpios['ordenar_por'] = 'codigo '.$datosLimpios['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $datosLimpios['ordenar_por'] = 'codigo '.$datosLimpios['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $datosLimpios['ordenar_por'] = 'nombre '.$datosLimpios['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados'] = $objeto->consultarOcupaciones($datosLimpios);
            $resultados['total']    = $objeto->consultarOcupacionesTotal($datosLimpios);
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
            $resultado = $objeto->modificarOcupacion($data);
            if ($resultado) {
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
            $resultado = $objeto->estatusOcupacion($data);
            if ($resultado) {
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