<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('d-m-Y', time());

if ($_POST['opcion']) {
    require_once('../models/m_modulo_curso.php');
    $objeto = new model_modulo_curso;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            $objeto->conectar();
            $resultados = [];
            $resultados['fecha']    = $date;
            $resultados['oficios']  = $objeto->consultarOficios();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer módulos':
            $data = [];
            $objeto->conectar();
            $data['modulos'] = $objeto->consultarModulos($_POST);
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

            $fecha_c = $_POST['fecha'];     $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));
            // VERIFICAMOS QUE NO EXISTA UN MODULO ACTIVO CON EL MISMO OFICIO Y MODULO.
            if ($objeto->confirmarExistenciaR($_POST) == 0) {
                // REGISTRA EL MODULO EN CURSO
                if ($numero = $objeto->registrarModuloCurso($_POST)) {
                    $errores = 0;
                    // PROCEDEMOS A REGISTRAR TODAS LAS ASIGNATURAS POR SECCION
                    for ($var1 = 1; $var1 <= intval($_POST['cant_seccion']); $var1++) {
                        // REGISTRA DOS VECES, TURNO MATUTINO Y VESPERTINO
                        for ($var2 = 0; $var2 < 2; $var2++) {
                            if ($var2 == 0) { $turno = 'M'; } // EL PRIMERO COMO MATUTINO
                            if ($var2 == 1) { $turno = 'V'; } // EL SEGUNDO COMO VESPERTINO

                            // RECORREMOS TODAS LAS ASIGNATURAS PRA EL REGISTRO
                            for ($var3 = 0; $var3 < count($_POST['codigo_registro']); $var3++) {
                                $datos_asignatura = [
                                    'codigo'    => $_POST['codigo_registro'][$var3],
                                    'modulo'    => $numero,
                                    'asignatura'=> $_POST['codigo_asignatura'][$var3],
                                    'horas'     => $_POST['horas_asignaturas'][$var3],
                                    'seccion'   => $var1,
                                    'turno'     => $turno,
                                ];
        
                                // SI NO SE REGISTRO SUMA UN ERROR COMETIDO.
                                if (!$objeto->registrarModuloCursoAsig($datos_asignatura)) { $errores++; }
                            }
                        }
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
            $campo_ordenar = 'fecha_inicio '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 'fecha_inicio '.$campo_m_ordenar; }
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