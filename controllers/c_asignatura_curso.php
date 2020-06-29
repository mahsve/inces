<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_asignatura_curso.php');
    $objeto = new model_asignatura_curso;
    
    switch ($_POST['opcion']) {
        case 'Calcular fecha fin':
            $objeto->conectar();
            $diasFeriados =[ '01-01', '24-02', '25-02', '09-04', '10-04', '19-04', '01-05', '24-06', '05-07', '24-07', '12-10', '24-12', '25-12', '31-12', ];
        
            if ($vasdas) {

            }
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
            $campo_ordenar = 't_asignatura.nombre '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 't_asignatura.nombre '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarAsignaturas($_POST);
            $resultados['total']        = $objeto->consultarAsignaturasTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Modificar':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaM($_POST) == 0) {
                // SE PROCEDE A MODIFICAR
                if ($objeto->modificarCargoContacto($_POST)) {
                    echo 'Modificación exitosa';
                } else {
                    echo 'Modificación fallida';
                }
            } else {
                echo 'Ya está registrado';
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusCargoContacto($_POST)) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
    }
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}