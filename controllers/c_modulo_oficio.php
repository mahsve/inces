<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_modulo_oficio.php');
    $objeto = new model_modulo_oficio;
    
    switch ($_POST['opcion']) {

         case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['oficios'] = $objeto->consultarOficios();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Registrar':       
          $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo']),
                'codigoModuloOficio'    => htmlspecialchars($_POST['codigoModuloOficio']),
                'oficio'    => htmlspecialchars($_POST['oficio']),
                'nombre'    => htmlspecialchars($_POST['nombre'])
            ];
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistencia($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarModulo($datos)) {
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
            $_POST['ordenar_por'] = 't_modulo.codigoModuloOficio '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $_POST['ordenar_por'] = 't_modulo.codigoModuloOficio '.$_POST['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $_POST['ordenar_por'] = 't_modulo.nombre '.$_POST['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados'] = $objeto->consultarModulos($_POST);
            $resultados['total']    = $objeto->consultarTotalPorModulo($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

          


        case 'Modificar';
           $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarModulo($_POST)){
                    $objeto->guardarTransaccion();
               
            } else {
                echo 'Modificación fallida: Datos personales';
                $objeto->calcelarTransaccion();
            }
            $objeto->desconectar();
      
            break;

         case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusModulo($_POST)) {
                echo 'Modificación exitosa';
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