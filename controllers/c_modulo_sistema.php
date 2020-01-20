<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_modulo_sistema.php');
    $objeto = new model_modulo_sistema;
    
    switch ($_POST['opcion']) {
        case 'Registrar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }
            ////////////////////
            $objeto->conectar();
            if ($objeto->registrarModulo($data)) {
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
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados'] = $objeto->consultarModulos($datosLimpios);
            $resultados['total']    = $objeto->consultarModulosTotal($datosLimpios);
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
            if ($objeto->modificarModulo($data)) {
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Modificar orden':
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            $errores = 0;
            for ($i=0; $i < count($_POST['codigo']); $i++) {
                $data = [
                    'codigo'    => htmlspecialchars($_POST['codigo'][$i]),
                    'posicion'  => htmlspecialchars($_POST['posicion'][$i])
                ];
                if (!$objeto->modificarOrdenModulo($data))
                    $errores++;
            }
            if ($errores == 0) {
                $objeto->guardarTransaccion();
                echo 'Modificacion exitosa';
            } else {
                $objeto->calcelarTransaccion();
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Eliminar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }
            ////////////////////
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->eliminarModulo($data)) {
                $todosLosModulos = $objeto->consultarModulosTodos();

                $errores = 0;
                for ($i=0; $i < count($todosLosModulos); $i++) {
                    $data = [
                        'codigo'    => htmlspecialchars($todosLosModulos[$i]['codigo']),
                        'posicion'  => $i + 1
                    ];
                    if (!$objeto->modificarOrdenModulo($data))
                        $errores++;
                }
                
                if ($errores == 0) {
                    $objeto->guardarTransaccion();
                    echo 'Modificacion exitosa';
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Modificación fallida';
                }
            } else {
                echo 'Modificación fallida';
                $objeto->calcelarTransaccion();
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