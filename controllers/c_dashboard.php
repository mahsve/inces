<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_dashboard.php');
    $objeto = new model_dashboard;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            ///////////////////// HACER CONSULTAS //////////////////////
            $data = [];
            $objeto->conectar();
            $data['aprendices_1']   = $objeto->consultarActivos();
            $data['aprendices_2']   = $objeto->consultarTotal();
            $data['oficios']        = $objeto->consultarOficios();
            $data['Facilitadores']  = $objeto->consultarFacilitadores();
            $objeto->desconectar();
            echo json_encode($data);
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