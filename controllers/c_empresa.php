<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_empresa.php');
    $objeto = new model_empresa;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $data = [];
            $objeto->conectar();
            $data['actividades'] = $objeto->consultarActividades();
            $data['estados'] = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Traer ciudades':
            $dataBusqueda = [];
            foreach ($_POST AS $nombre => $valor) {
                $dataBusqueda[$nombre] = htmlspecialchars($valor);
            }
            
            $data = [];
            $objeto->conectar();
            $data['ciudades'] = $objeto->consultarCiudades($dataBusqueda);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Registrar':
            $dataInsercion = [];
            foreach ($_POST AS $nombre => $valor){
                if ($valor != '')
                    $dataInsercion[$nombre] = "'".htmlspecialchars($valor)."'";
                else
                    $dataInsercion[$nombre] = 'NULL';
            }

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