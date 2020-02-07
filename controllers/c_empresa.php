<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_empresa.php');
    $objeto = new model_empresa;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            ///////////////////// HACER CONSULTAS //////////////////////
            $data = [];
            $objeto->conectar();
            $data['actividades'] = $objeto->consultarActividades();
            $data['estados'] = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Traer ciudades':
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $dataBusqueda = [];
            foreach ($_POST AS $nombre => $valor) {
                $dataBusqueda[$nombre] = htmlspecialchars($valor);
            }
            ///////////////////// HACER CONSULTAS //////////////////////
            $data = [];
            $objeto->conectar();
            $data['ciudades'] = $objeto->consultarCiudades($dataBusqueda);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Verificar RIF':
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $rifLimpio = htmlspecialchars($_POST['rif']);
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            $data = $objeto->verificarRIF($rifLimpio);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Registrar':
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $dataInsercion = [];
            foreach ($_POST AS $nombre => $valor){
                if ($valor != '')
                    $dataInsercion[$nombre] = "'".htmlspecialchars($valor)."'";
                else
                    $dataInsercion[$nombre] = 'NULL';
            }
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->registrarPersonaContacto($dataInsercion)){
                if ($objeto->registrarEmpresa($dataInsercion)) {
                    echo 'Registro exitoso';
                    $objeto->guardarTransaccion();
                } else {
                    echo 'Registro fallido';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Registro fallido';
                $objeto->calcelarTransaccion();
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
            $datosLimpios['ordenar_por'] = 'rif '.$datosLimpios['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $datosLimpios['ordenar_por'] = 'rif '.$datosLimpios['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $datosLimpios['ordenar_por'] = 'nil '.$datosLimpios['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarEmpresas($datosLimpios);
            $resultados['total']        = $objeto->consultarEmpresasTotal($datosLimpios);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarPersonaContacto($data)){
                if ($objeto->modificarEmpresa($data)) {
                    echo 'Modificacion exitosa';
                    $objeto->guardarTransaccion();
                } else {
                    echo 'Modificación fallida';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Modificación fallida';
                $objeto->calcelarTransaccion();
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            if ($objeto->estatusEmpresa($data)) {
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
	header('Location: ../iniciar');
}