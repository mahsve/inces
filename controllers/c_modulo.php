<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_modulo.php');
    $objeto = new model_modulo;

    switch ($_POST['opcion']) {
        // CONSULTAR DATOS
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['oficio']   = $objeto->consultarOficios();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer asignaturas':
            $resultados = [];
            $objeto->conectar();
            $resultados['asignaturas'] = $objeto->consultarAsignaturas($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer modulos':
            $resultados = [];
            $objeto->conectar();
            $resultados['modulos'] = $objeto->consultarModulosOrden($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        // FIN CONSULTAR DATOS


        // MODIFICACIONES RAPIDAS
        case 'Guardar orden modulos':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // PROCEDEMOS A ACTUALIZAR EL ORDEN DE CADA MODULO
            $errores = 0;
            for ($var = 0; $var < count($_POST['codigo']); $var++) {
                $datos_orden = [
                    'codigo_modulo' => $_POST['codigo'][$var],
                    'posicion_modulo' => $_POST['posicion'][$var]
                ];

                // ACTUALIZAMOS Y VERIFICAMOS QUE NO HAYA ERRORES.
                if (!$objeto->modificarOrdenModulos($datos_orden)) { $errores++; }
            }

            // SI NO HUBO ERRORES GUARDAMOS LOS CAMBIOS
            if ($errores == 0) {
                echo 'Modificado exitosamente';
                $objeto->guardarTransaccion();
            } else {
                echo 'Error al modificar';
                $objeto->calcelarTransaccion();
            }
            $objeto->desconectar();
        break;
        // FIN MODIFICACIONES RAPIDAS


        // OPERACIONES BASICAS
        case 'Registrar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // VERIFICAMOS QUE SI YA ESTA REGISTRADO ESTE MODULO CON SUS CARACTERISTICAS
            $verificar_modulo = false;
            if (isset($_POST['repeticion_modulo'])) {
                $verificar_modulo = $objeto->confirmarExistenciaSinOficio($_POST);
            } else {
                $verificar_modulo = $objeto->confirmarExistenciaConOficio($_POST);
            }

            if ($verificar_modulo == 0) {
                // REGISTRAR MODULO
                $registro_modulo = false;
                if (isset($_POST['repeticion_modulo'])) {
                    $registro_modulo = $objeto->registrarModuloSinOficio($_POST);
                } else {
                    $registro_modulo = $objeto->registrarModuloConOficio($_POST);
                }

                // SI SE REGISTRO PROCEDE A REGISTRAR LAS ASIGNATURAS.
                if ($registro_modulo) {
                    // REGISTRAR TODAS ASIGNATURAS AGREGADAS.
                    $errores_asig   = 0;
                    for ($var = 0; $var < count($_POST['codigo_asignatura']); $var++) {
                        $datos_asignaturas = [
                            'codigo_modulo'     => $registro_modulo,
                            'codigo_asignatura' => $_POST['codigo_asignatura'][$var],
                            'cantidad_horas'    => $_POST['horas_asignatura'][$var]
                        ];

                        // REGISTRAMOS Y VERIFICAMOS QUE NO HAYA RETORNADO ERROR
                        if (!$objeto->registrarAsignatura($datos_asignaturas)) { $errores_asig++; }
                    }

                    if ($errores_asig == 0) {
                        // VERIFICAMOS SI DEBE REGISTRAR A TODOS LOS OFICIOS.
                        if (isset($_POST['repeticion_modulo'])) {
                            // REGISTRAR EL MODULO A TODO LOS OFICIOS
                            $errores_mod = 0;
                            $oficios = $objeto->consultarOficiosTodos();
                            for ($var_ofi = 0; $var_ofi < count($oficios); $var_ofi++) {
                                $datos_oficios = [
                                    'codigo_oficio' => $oficios[$var_ofi]['codigo'],
                                    'codigo_modulo' => $registro_modulo
                                ];

                                // REGISTRAMOS Y VERIFICAMOS QUE NO HAYA RETORNADO ERROR
                                if (!$objeto->registrarModuloTodosLosOficios($datos_oficios)) { $errores_mod++; }
                            }

                            // SI EL NUMERO DE ERRORES ES CERO ENVIAMOS MENSAJE DE EXITO
                            if ($errores_mod == 0) {
                                echo 'Registro exitoso';
                                $objeto->guardarTransaccion();
                            } else {
                                echo 'Registro fallido: Módulos a los oficios';
                                $objeto->calcelarTransaccion();
                            }
                        // DE LO CONTRARIO MANDAMOS MENSAJE DE EXITO
                        } else {
                            echo 'Registro exitoso';
                            $objeto->guardarTransaccion();
                        }
                    } else {
                        echo 'Registro fallido: Asignaturas';
                        $objeto->calcelarTransaccion();
                    }
                } else {
                    echo 'Registro fallido: Módulo';
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
            $resultados['resultados']   = $objeto->consultarModulos($_POST);
            $resultados['total']        = $objeto->consultarModulosTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // VERIFICAMOS QUE SI YA ESTA REGISTRADO ESTE MODULO CON SUS CARACTERISTICAS
            $verificar_modulo = false;
            if (isset($_POST['repeticion_modulo'])) {
                $verificar_modulo = $objeto->confirmarExistenciaMSinOficio($_POST);
            } else {
                $verificar_modulo = $objeto->confirmarExistenciaMConOficio($_POST);
            }

            if ($verificar_modulo == 0) {
                // REGISTRAR MODULO
                $modificar_modulo = false;
                if (isset($_POST['repeticion_modulo'])) {
                    $modificar_modulo = $objeto->modificarModuloSinOficio($_POST);
                } else {
                    $modificar_modulo = $objeto->modificarModuloConOficio($_POST);
                }

                if ($modificar_modulo) {
                    // REGISTRAR NUEVAS ASIGNATURAS Y MODIFICAR LAS EXISTENTES.
                    $errores_asig   = 0;
                    for ($var = 0; $var < count($_POST['codigo_asignatura']); $var++) {
                        $datos_asignaturas = [
                            'codigo_modulo'     => $_POST['codigo'],
                            'codigo_asignatura' => $_POST['codigo_asignatura'][$var],
                            'codigo_registro'   => $_POST['codigo_registro'][$var],
                            'cantidad_horas'    => $_POST['horas_asignatura'][$var]
                        ];

                        // SI EL CODIGO DE REGISTRO ES 0, ENTONCES NO ESTA REGISTRADO Y SE PROCEDE A REGISTRAR
                        if ($_POST['codigo_registro'][$var] == 0) {
                            // REGISTRAMOS Y VERIFICAMOS QUE NO HAYA RETORNADO ERROR
                            if (!$objeto->registrarAsignatura($datos_asignaturas)) { $errores_asig++; }
                        } else {
                            // MODIFICAMOS Y VERIFICAMOS QUE NO HAYA RETORNADO ERROR
                            if (!$objeto->modificarAsignatura($datos_asignaturas)) { $errores_asig++; }
                        }
                    }

                    if ($errores_asig == 0) {
                        // ELIMINAR ASIGNATURAS SELECCIONADAS POR EL USUARIO
                        $errores_asig   = 0;
                        $eliminar_asign = json_decode($_POST['eliminar_asign']);
                        for ($var = 0; $var < count($eliminar_asign); $var++) {
                            if (!$objeto->eliminarAsignatura($eliminar_asign[$var])) { $errores_asig++; }
                        }

                        if ($errores_asig == 0) {
                            if ($objeto->eliminarDetallesOficioModulos($_POST)) {
                                // VERIFICAMOS SI DEBE REGISTRAR A TODOS LOS OFICIOS.
                                if (isset($_POST['repeticion_modulo'])) {
                                    // REGISTRAR EL MODULO A TODO LOS OFICIOS
                                    $errores_mod = 0;
                                    $oficios = $objeto->consultarOficiosTodos();
                                    for ($var_ofi = 0; $var_ofi < count($oficios); $var_ofi++) {
                                        $datos_oficios = [
                                            'codigo_oficio' => $oficios[$var_ofi]['codigo'],
                                            'codigo_modulo' => $_POST['codigo']
                                        ];
        
                                        // REGISTRAMOS Y VERIFICAMOS QUE NO HAYA RETORNADO ERROR
                                        if (!$objeto->registrarModuloTodosLosOficios($datos_oficios)) { $errores_mod++; }
                                    }
        
                                    // SI EL NUMERO DE ERRORES ES CERO ENVIAMOS MENSAJE DE EXITO
                                    if ($errores_mod == 0) {
                                        echo 'Modificación exitosa';
                                        $objeto->guardarTransaccion();
                                    } else {
                                        echo 'Modificación fallida: Módulos a los oficios';
                                        $objeto->calcelarTransaccion();
                                    }
                                // DE LO CONTRARIO MANDAMOS MENSAJE DE EXITO
                                } else {
                                    echo 'Modificación exitosa';
                                    $objeto->guardarTransaccion();
                                }
                            } else {
                                echo 'Modificación fallida: Actualizar detalles';
                                $objeto->calcelarTransaccion();
                            }
                        } else {
                            echo 'Modificación fallida: Eliminar asignaturas';
                            $objeto->calcelarTransaccion();
                        }
                    } else {
                        echo 'Modificación fallida: Asignaturas';
                        $objeto->calcelarTransaccion();
                    }
                } else {
                    echo 'Modificación fallida: Módulo';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Ya está registrado';
                $objeto->calcelarTransaccion();
            }
            $objeto->desconectar();
        break;
        
        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusModulo($_POST)) {
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