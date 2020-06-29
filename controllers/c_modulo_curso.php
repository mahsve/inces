<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('d-m-Y', time());

if ($_POST['opcion']) {
    require_once('../models/m_modulo_curso.php');
    $objeto = new model_modulo_curso;
    
    switch ($_POST['opcion']) {
        // CONSULTAR DATOS
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
        // FIN CONSULTAR DATOS


        // OPERACIONES BASICAS
        case 'Registrar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // REORDENAMOS LA FECHA
            $fecha_c = $_POST['fecha'];     $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));

            // VERIFICAMOS QUE NO EXISTA UN MODULO ACTIVO CON EL MISMO OFICIO Y MODULO.
            if ($objeto->confirmarExistenciaR($_POST) == 0) {
                // REGISTRA EL MODULO EN CURSO
                if ($codigo_modulo = $objeto->registrarModuloCurso($_POST)) {
                    $errores_s = 0;
                    // PROCEDEMOS A REGISTRAR LAS SECCION
                    for ($var1 = 0; $var1 < count($_POST['registro_seccion']); $var1++) {
                        // DATOS PARA REGISTRAR SECCION.
                        $datos_seccion = [
                            'codigo'    => $_POST['registro_seccion'][$var1],
                            'seccion'   => $_POST['seccion'][$var1],
                            'turno'     => $_POST['turno'][$var1],
                            'modulo'    => $codigo_modulo,
                        ];

                        // SI NO SE REGISTRO SUMA UN ERROR COMETIDO.
                        if ($codigo_seccion = $objeto->registrarModuloSeccion($datos_seccion)) {
                            $errores_a = 0;

                            // RECORREMOS TODAS LAS ASIGNATURAS PRA EL REGISTRO
                            for ($var2 = 0; $var2 < count($_POST['codigo_asignatura']); $var2++) {
                                $datos_asignatura = [
                                    'asignatura'=> $_POST['codigo_asignatura'][$var2],
                                    'horas'     => $_POST['horas_asignaturas'][$var2],
                                    'seccion'   => $codigo_seccion
                                ];
        
                                // SI NO SE REGISTRO SUMA UN ERROR COMETIDO.
                                if (!$objeto->registrarModuloCursoAsig($datos_asignatura)) { $errores_a++; }
                            }
                        } else {
                            $errores_s++;
                        }
                    }

                    // SI LO ERRORES SE MANTUVIERON EN 0 TODO ESTA EXITOSO Y GUARDAMOS LAS TRANSACCIONES.
                    if ($errores_s == 0 AND $errores_a == 0) {
                        $objeto->guardarTransaccion();
                        echo 'Registro exitoso';
                    } else {
                        $objeto->calcelarTransaccion();
                        if ($errores_s > 0 AND $errores_a == 0) {
                            echo 'Registro fallido: Secciones del módulo';
                        } else if ($errores_s == 0 AND $errores_a > 0) {
                            echo 'Registro fallido: Asignaturas del módulo';
                        } else {
                            echo 'Registro fallido: Secciones y asignaturas del módulo';
                        }
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
            $campo_ordenar = 'td_modulo.fecha_inicio '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 'td_modulo.fecha_inicio '.$campo_m_ordenar; }
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
        // FIN OPERACIONES BASICAS
    }
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}