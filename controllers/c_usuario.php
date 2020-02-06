<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_usuario.php');
    $objeto = new model_usuario;
    
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
            if ($objeto->registrarOficio($data)) {
                echo 'Registro exitoso';
            } else {
                echo 'Registro fallido';
            }
            $objeto->desconectar();
        break;

        case 'Consultar':
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
            $datosLimpios['ordenar_por'] = 'usuario '.$datosLimpios['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $datosLimpios['ordenar_por'] = 'usuario '.$datosLimpios['ordenar_tipo'];
            /////////////////
            $datosLimpios['usuario'] = $_SESSION['usuario']['usuario'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados = [];
            $objeto->conectar();
            $resultados['resultados']   = $objeto->consultarUsuarios($datosLimpios);
            $resultados['total']        = $objeto->consultarUsuariosTotal($datosLimpios);
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
    echo 'Error controlador';
}