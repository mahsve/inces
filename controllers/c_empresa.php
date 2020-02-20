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
            $data = [];
            $objeto->conectar();
            $data['ciudades'] = $objeto->consultarCiudades($_POST);
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

        case 'Verificar cedula':
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $cedulaLimpia['nacionalidad'] = "'".htmlspecialchars($_POST['nacionalidad'])."'";
            $cedulaLimpia['cedula'] = "'".htmlspecialchars($_POST['cedula'])."'";
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            $data = $objeto->verificarCedula($cedulaLimpia);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Registrar':
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($_POST['registrar_cont'] == 'si') {
                $respuestaRegistro = $objeto->registrarPersonaContacto($_POST);
            } else {
                $respuestaRegistro = true;
            }
            ////////////////////////////////////////////////////////////
            if ($respuestaRegistro){
                if ($objeto->registrarEmpresa($_POST)) {
                    echo 'Registro exitoso';
                    $objeto->guardarTransaccion();
                } else {
                    echo 'Registro fallido: Datos de la empresa';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Registro fallido: Datos personales';
                $objeto->calcelarTransaccion();
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
            $_POST['ordenar_por'] = 'rif '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $_POST['ordenar_por'] = 'rif '.$_POST['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $_POST['ordenar_por'] = 'nil '.$_POST['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarEmpresas($_POST);
            $resultados['total']        = $objeto->consultarEmpresasTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarPersonaContacto($_POST)){
                if ($objeto->modificarEmpresa($_POST)) {
                    echo 'Modificacion exitosa';
                    $objeto->guardarTransaccion();
                } else {
                    echo 'Modificación fallida: Datos de la empresa';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Modificación fallida: Datos personales';
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