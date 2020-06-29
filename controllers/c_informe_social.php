<?php
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('d-m-Y', time());

if ($_POST['opcion']){
    require_once('../models/m_informe_social.php');
    $objeto = new model_informeSocial;
    
    switch ($_POST['opcion']){
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['fecha']        = $date;
            $resultados['ocupaciones']  = $objeto->consultarOcupaciones();
            $resultados['oficios']      = $objeto->consultarOficios();
            $resultados['estados']      = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Traer divisiones':
            $resultados = [];
            $objeto->conectar();
            // $resultados['ciudades'] = $objeto->consultarCiudades($_POST);
            // $resultados['municipios'] = $objeto->consultarMunicipios($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer parroquias':
            $resultados = [];
            $objeto->conectar();
            $resultados['parroquias'] = $objeto->consultarParroquias($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Registrar ocupacion':
            $resultados = [];
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaR_O($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarOcupacion($_POST)) {
                    $resultados['ocupaciones']  = $objeto->consultarOcupaciones();
                    echo json_encode($resultados);
                } else {
                    echo json_encode('Registro fallido');
                }
            } else {
                echo json_encode('Ya está registrado');
            }
            $objeto->desconectar();
        break;

        case 'Registrar':
            $fecha_c = $_POST['fecha'];     $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));
            $fecha_c = $_POST['fecha_n'];   $_POST['fecha_n'] = date("Y-m-d", strtotime($fecha_c));

            // INPUT CHECK QUE NO HAYAN SIDO SELECCIONADOS.
            if (!isset($_POST['tipo_vivienda']))     { $_POST['tipo_vivienda']      = ''; }
            if (!isset($_POST['tenencia_vivienda'])) { $_POST['tenencia_vivienda']  = ''; }
            if (!isset($_POST['tipo_agua']))         { $_POST['tipo_agua']          = ''; }
            if (!isset($_POST['tipo_electricidad'])) { $_POST['tipo_electricidad']  = ''; }
            if (!isset($_POST['tipo_excreta']))      { $_POST['tipo_excreta']       = ''; }
            if (!isset($_POST['tipo_basura']))       { $_POST['tipo_basura']        = ''; }
            if (!isset($_POST['enfermos']))          { $_POST['enfermos']           = ''; }

            $_POST['f_nacionalidad']= $_SESSION['usuario']['nacionalidad'];
            $_POST['f_cedula']      = $_SESSION['usuario']['cedula'];

            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->registrarDatosPersonales($_POST)) {
                if ($objeto->registrarDatosVivienda($_POST)) {
                    if ($id = $objeto->registrarInformeSocial($_POST)) {
                        $errorI = 0;
                        for ($i2 = 0; $i2 < 10; $i2++) {
                            // DATOS DE LOS INGRESOS DE LA FAMILIA (NOMBRES DE LOS CAMPOS)
                            $listaDinero = [
                                'ingreso_pension',
                                'ingreso_seguro',
                                'ingreso_pension_otras',
                                'ingreso_sueldo',
                                'otros_ingresos',
                                'egreso_servicios',
                                'egreso_alimentario',
                                'egreso_educacion',
                                'egreso_vivienda',
                                'otros_egresos'
                            ];
                            
                            // LISTA DE ATRIBUTOS A REGISTRAR.
                            $arregloGestionDinero = [
                                'id_ficha'      => $id,
                                'descripcion'   => $listaDinero[$i2],
                                'cantidad'      => $_POST[$listaDinero[$i2]]
                            ];

                            if (!$objeto->registrarGestionDinero($arregloGestionDinero)) { $errorI++; }
                        }

                        if ($errorI == 0) {
                            $objeto->guardarTransaccion();
                            echo 'Registro exitoso';
                        } else {
                            $objeto->calcelarTransaccion();
                            echo 'Registro fallido: Ingresos familiares';
                        }
                    } else {
                        $objeto->calcelarTransaccion();
                        echo 'Registro fallido: Informe social';
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Registro fallido: Datos de la vivienda';
                }
            } else {
                $objeto->calcelarTransaccion();
                echo 'Registro fallido: Datos personales';
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
            $campo_ordenar = 't_datos_personales.cedula '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 't_datos_personales.cedula '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 2) { $campo_ordenar = 'concat (t_datos_personales.nombre1, t_datos_personales.nombre2, t_datos_personales.apellido1, t_datos_personales.apellido1) '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 3) { $campo_ordenar = 't_informe_social.fecha '.$datosLimpios['ordenar_tipo'];}
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarInformeSocial($_POST);
            $resultados['total']        = $objeto->consultarInformeSocialTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Consultar determinado':
            $resultados = [];
            $objeto->conectar();
            $resultados['vivienda']     = $objeto->consultarDatosVivienda($_POST);
            $resultados['familiares']   = $objeto->consultarFamiliares($_POST);
            $resultados['ingresos']     = $objeto->consultarDinero($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $fecha_c = $_POST['fecha'];     $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));
            $fecha_c = $_POST['fecha_n'];   $_POST['fecha_n'] = date("Y-m-d", strtotime($fecha_c));

            // INPUT CHECK QUE NO HAYAN SIDO SELECCIONADOS.
            if (isset($_POST['tipo_vivienda']))     { $_POST['tipo_vivienda'] = ''; }
            if (isset($_POST['tenencia_vivienda'])) { $_POST['tenencia_vivienda'] = ''; }
            if (isset($_POST['tipo_agua']))         { $_POST['tipo_agua'] = ''; }
            if (isset($_POST['tipo_electricidad'])) { $_POST['tipo_electricidad'] = ''; }
            if (isset($_POST['tipo_excreta']))      { $_POST['tipo_excreta'] = ''; }
            if (isset($_POST['tipo_basura']))       { $_POST['tipo_basura'] = ''; }
            if (isset($_POST['enfermos']))          { $_POST['enfermos'] = ''; }
                
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarDatosPersonales($_POST)){
                if ($objeto->modificarDatosVivienda($_POST)){
                    if ($objeto->modificarInformeSocial($_POST)) {
                        $objeto->eliminarGestionDinero($_POST['informe_social']);

                        $errorI = 0;
                        for ($i2 = 0; $i2 < 10; $i2++) {
                            // DATOS DE LOS INGRESOS DE LA FAMILIA (NOMBRES DE LOS CAMPOS)
                            $listaDinero = [
                                'ingreso_pension',
                                'ingreso_seguro',
                                'ingreso_pension_otras',
                                'ingreso_sueldo',
                                'otros_ingresos',
                                'egreso_servicios',
                                'egreso_alimentario',
                                'egreso_educacion',
                                'egreso_vivienda',
                                'otros_egresos'
                            ];
                            
                            // LISTA DE ATRIBUTOS A REGISTRAR.
                            $arregloGestionDinero = [
                                'id_ficha'      => $id,
                                'descripcion'   => $listaDinero[$i2],
                                'cantidad'      => $_POST[$listaDinero[$i2]]
                            ];

                            if (!$objeto->registrarGestionDinero($arregloGestionDinero)) { $errorI++; }
                        }

                        if ($errorI == 0) {
                            $objeto->guardarTransaccion();
                            echo 'Modificacion exitosa';
                        } else {
                            $objeto->calcelarTransaccion();
                            echo 'Modificación fallida: Ingresos familiares';
                        }
                    } else {
                        $objeto->calcelarTransaccion();
                        echo 'Modificación fallida: Informe social';
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Modificación fallida: Datos de la vivienda';
                }
            } else {
                $objeto->calcelarTransaccion();
                echo 'Modificación fallida: Datos personales';
            }
            $objeto->desconectar();
        break;

        case 'Rechazar':
            $objeto->conectar();
            if ($objeto->estatusAprendiz($_POST)) {
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;

        case 'Reactivar':
            $objeto->conectar();
            if ($objeto->estatusAprendiz($_POST)) {
                echo 'Modificacion exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
    }
} else { // SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet');
}