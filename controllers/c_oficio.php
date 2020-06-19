<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_oficio.php');
    $objeto = new model_oficio;
    
    switch ($_POST['opcion']) {
        case 'Registrar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaR($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($n_oficio = $objeto->registrarOficio($_POST)) {
                    // CONSULTAMOS LOS MODULO QUE SE REPITEN EN TODO LOS OFICIOS
                    $errores = 0;
                    $modulosG = $objeto->consultarModulosGenerales();
                    for ($var = 0; $var < count($modulosG); $var++) {
                        $datosModulos = [
                            'codigo_oficio' => $n_oficio,
                            'codigo_modulo' => $modulosG[$var]['codigo']
                        ];

                        // REGISTRAMOS Y VEREFICAMOS QUE NO HAYA ERRORES.
                        if (!$objeto->registrarModuloTodosLosOficios($datosModulos)) { $errores++; }
                    }

                    if ($errores == 0) {
                        echo 'Registro exitoso';
                        $objeto->guardarTransaccion();
                    } else {
                        echo 'Registro fallido: Módulos generales';
                        $objeto->calcelarTransaccion();
                    }
                } else {
                    echo 'Registro fallido: Oficios';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Ya está registrado';
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
            $campo_ordenar = 'nombre '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 'nombre '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarOficios($_POST);
            $resultados['total']        = $objeto->consultarOficiosTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaM($_POST) == 0) {
                // SE PROCEDE A MODIFICAR
                if ($objeto->modificarOficio($_POST)) {
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
            if ($objeto->estatusOficio($_POST)) {
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