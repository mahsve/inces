<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_modulo_curso.php');
    $objeto = new model_modulo_curso;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $data = [];
            $objeto->conectar();
            $data['oficios'] = $objeto->consultarOficios();
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Traer asignaturas':
            $resultados = [];
            $objeto->conectar();
            $resultados['asignaturas'] = $objeto->consultarAsignaturas($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Registrar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // VERIFICAMOS QUE NO EXISTA UN REGISTRO SIMILAR
            if ($objeto->confirmarExistenciaR($_POST) == 0) {
                // REGISTRA EL MODULO EN CURSO
                if ($numero = $objeto->registrarModuloCurso($_POST)) {
                    // PROCEDEMOS A REGISTRAR LAS ASIGNATURAS
                    $errores = 0;
                    for ($var = 0; $var < count($_POST['campo_asignatura']); $var++) {
                        $arreglo_datos = [ 'asignatura' => $_POST['campo_asignatura'][$var], 'modulo' => $numero ];
                        // SI NO SE REGISTRO SUMA UN ERROR COMETIDO.
                        if (!$objeto->registrarModuloCursoAsig($arreglo_datos)) { $errores++; }
                    }

                    // SI LO ERRORES SE MANTUVIERON EN 0 TODO ESTA EXITOSO Y GUARDAMOS LAS TRANSACCIONES.
                    if ($errores == 0) {
                        $objeto->guardarTransaccion();
                        echo 'Registro exitoso';
                    } else {
                        $objeto->calcelarTransaccion();
                        echo 'Registro fallido: Asignaturas del módulo';
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Registro fallido: Datos del módulo en curso';
                }
            } else {
                $objeto->calcelarTransaccion();
                echo 'Ya está registrado';
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
            $campo_ordenar = 'td_modulo.anio_modulo, td_modulo.descripcion '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 'td_modulo.anio_modulo, td_modulo.descripcion '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 2) { $campo_ordenar = 'td_modulo.anio_modulo, t_oficio.nombre, td_modulo.codigo_modulo '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarModulosCursos($_POST);
            $resultados['total']        = $objeto->consultarModulosCursosTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // VERIFICAMOS QUE NO EXISTA UN REGISTRO SIMILAR
            if ($objeto->confirmarExistenciaM($_POST) == 0) {
                if ($objeto->modificarModuloCurso($_POST)) {
                    // PROCEDEMOS A REGISTRAR LAS ASIGNATURAS
                    $errores = 0;
                    for ($var = 0; $var < count($_POST['campo_asignatura']); $var++) {
                        if ($_POST['id_campo_asignatura'][$var] == 0) {
                            $arreglo_datos = [ 'asignatura' => $_POST['campo_asignatura'][$var], 'modulo' => $_POST['codigo'] ];
                            // SI NO SE REGISTRO SUMA UN ERROR COMETIDO.
                            if (!$objeto->registrarModuloCursoAsig($arreglo_datos)) { $errores++; }
                        }
                    }

                    // PROCEDEMOS A ELIMINAR LAS ASIGNATURAS
                    $erroresE = 0;
                    $eliminar_asignaturas = json_decode($_POST['eliminar_asignaturas']);
                    for ($var = 0; $var < count($eliminar_asignaturas); $var++) {
                        $arreglo_datos = [ 'codigo' => $eliminar_asignaturas[$var] ];
                        // SI NO SE REGISTRO SUMA UN ERROR COMETIDO.
                        if (!$objeto->eliminaModuloCursoAsig($arreglo_datos)) { $erroresE++; }
                    }

                    // SI LO ERRORES SE MANTUVIERON EN 0 TODO ESTA EXITOSO Y GUARDAMOS LAS TRANSACCIONES.
                    if ($errores == 0) {
                        if ($erroresE == 0) {
                            $objeto->guardarTransaccion();
                            echo 'Modificación exitosa';
                        } else {
                            $objeto->calcelarTransaccion();
                            echo 'Modificación fallida: Eliminar asignaturas del módulo';
                        }
                    } else {
                        $objeto->calcelarTransaccion();
                        echo 'Modificación fallida: Asignaturas del módulo';
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Modificación fallida: Datos del módulo en curso';
                }
            } else {
                $objeto->calcelarTransaccion();
                echo 'Ya está registrado';
            }
            $objeto->desconectar();
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusModuloCurso($_POST)) {
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