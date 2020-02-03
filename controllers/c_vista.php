<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_vistas.php');
    $objeto = new modelo_vistas;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['modulos'] = $objeto->consultarModulos();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Registrar';
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }
            ////////////////////
            $objeto->conectar();
            $num_modulos = $objeto->consultarTotalVistasPorModulo($data);
            $data['posicion'] = $num_modulos + 1;
            $resultado = $objeto->registrarVista($data);
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
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados'] = $objeto->consultarVistas($datosLimpios);
            $resultados['total']    = $objeto->consultarVistasTotal($datosLimpios);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar';
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }

            $objeto->conectar();
            if ($objeto->modificarVista($data)) {
                $vistas = $objeto->consultarVistasModulosTodos();

                $cont = 0; $mod_ = 0;
                for ($i = 0; $i < count($vistas); $i++) {
                    if ($mod_ != $vistas[$i]['codigo_modulo']) {
                        $mod_ = $vistas[$i]['codigo_modulo'];
                        $cont = 1;
                    }

                    $data2 = [
                        'codigo' => $vistas[$i]['codigo'],
                        'posicion' => $cont
                    ];
                    $objeto->modificarOrdenVista($data2);
                    $cont++;
                }
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Traer vistas':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }
            ////////////////////
            $resultados = [];
            $objeto->conectar();
            $resultados = $objeto->consultarVistasModulosTodos2($data);
            $objeto->desconectar();
            echo json_encode($resultados);
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
                if (!$objeto->modificarOrdenVista($data))
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
            if ($objeto->eliminarVista($data)) {
                $todasLasVistas = $objeto->consultarVistasModulosTodos2($data);

                $errores = 0;
                for ($i=0; $i < count($todasLasVistas); $i++) {
                    $data = [
                        'codigo'    => htmlspecialchars($todasLasVistas[$i]['codigo']),
                        'posicion'  => $i + 1
                    ];
                    if (!$objeto->modificarOrdenVista($data))
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