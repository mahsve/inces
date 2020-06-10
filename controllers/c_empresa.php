<?php 
session_start();
if ($_POST['opcion']) {
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
            $objeto->conectar();
            $data = $objeto->verificarRIF($_POST);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Verificar cedula':
            $objeto->conectar();
            $data = $objeto->verificarCedula($_POST);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Registrar': 
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            $respuestaRegistro = false;
            if      ($_POST['registrar_cont'] == 'si') { $respuestaRegistro = $objeto->registrarPersonaContacto($_POST); }
            else if ($_POST['registrar_cont'] == 'no') { $respuestaRegistro = true; }

            if ($respuestaRegistro) {
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
            ////////////////////////////////////////////////////////////
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $campo_m_ordenar = 'ASC';
            if      ($_POST['campo_m_ordenar'] == 1) { $campo_m_ordenar = 'ASC'; }
            else if ($_POST['campo_m_ordenar'] == 2) { $campo_m_ordenar = 'DESC'; }
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $campo_ordenar = 't_empresa.rif '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 't_empresa.rif '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 2) { $campo_ordenar = 't_empresa.razon_social '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 3) { $campo_ordenar = 't_actividad_economica.nombre '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

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
                    echo 'Modificación exitosa';
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
            $objeto->conectar();
            if ($objeto->estatusEmpresa($_POST)) {
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
	header('Location: ../iniciar');
}