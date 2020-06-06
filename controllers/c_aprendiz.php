<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('d-m-Y', time());

if ($_POST['opcion']) {
    require_once('../models/m_aprendiz.php');
    $objeto = new model_aprendiz;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['fecha']        = $date;
            $resultados['ocupacion']    = $objeto->consultarOcupaciones();
            $resultados['estado']       = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Traer divisiones':
            $resultados = [];
            $objeto->conectar();
            $resultados['ciudad'] = $objeto->consultarCiudades($_POST);
            $resultados['municipio'] = $objeto->consultarMunicipios($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer parroquias':
            $resultados = [];
            $objeto->conectar();
            $resultados['parroquia'] = $objeto->consultarParroquias($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer participante':
            $resultados = [];
            $objeto->conectar();
            $resultados['participantes'] = $objeto->consultarParticipante($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer empresa':
            $resultados = [];
            $objeto->conectar();
            $resultados['empresas'] = $objeto->consultarEmpresas($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Registrar ocupacion':
            $resultados = [];
            $objeto->conectar();
            if ($objeto->verificarOcupacion($_POST) == 0) {
                if ($objeto->registrarOcupacion($_POST) > 0) {
                    $resultados['ocupacion'] = $objeto->consultarOcupaciones();
                    echo json_encode($resultados);
                } else {
                    echo 'Error al registrar';
                }
            } else {
                echo 'Ya registrado';
            }
            $objeto->desconectar();
        break;

        case 'Registrar':
            $fecha_c = $_POST['fecha'];
            $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));
            ////////////////////////////////////////////
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->registrarAprendiz($_POST)) {
                if ($objeto->estatusAprendiz($_POST)) {
                    $objeto->guardarTransaccion();
                    echo 'Registro exitoso';
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Registro fallido: Cambiar estatus a Inscripto';
                }
            } else {
                $objeto->calcelarTransaccion();
                echo 'Registro fallido: Registrar ficha al PNA';
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
            $campo_ordenar = 't_ficha_aprendiz.fecha '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 't_ficha_aprendiz.fecha '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 2) { $campo_ordenar = 't_datos_personales.cedula '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 3) { $campo_ordenar = 'concat (t_datos_personales.nombre1, t_datos_personales.nombre2, t_datos_personales.apellido1, t_datos_personales.apellido1) '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarPlanilla($_POST);
            $resultados['total']        = $objeto->consultarPlanillaTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Consultar determinado':
            $resultados = [];
            $objeto->conectar();
            $resultados['empresa']      = $objeto->consultarDatosEmpresa($_POST);
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
            $objeto->conectar();
            if ($objeto->estatusOcupacion($_POST)) {
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