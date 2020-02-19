<?php
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d', time());

if ($_POST['opcion']){
    require_once('../models/m_informe_social.php');
    $objeto = new model_informeSocial;
    
    switch ($_POST['opcion']){
        case 'Traer datos':
            $resultados = [];
            $resultados['fecha'] = $date;
            $objeto->conectar();
            $resultados['ocupacion'] = $objeto->consultarOcupaciones();
            $resultados['oficio'] = $objeto->consultarOficios();
            $resultados['estado'] = $objeto->consultarEstados();
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

        case 'Registrar':
            $_POST['nacionalidad_fac'] = $_SESSION['usuario']['nacionalidad'];
            $_POST['cedula_facilitador'] = $_SESSION['usuario']['cedula'];

            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->registrarDatosPersonales($_POST)) {
                if ($objeto->registrarDatosVivienda($_POST)) {
                    if ($id = $objeto->registrarInformeSocial($_POST)) {
                        $errorF = 0;
                        if(isset($_POST['nombre_familiar1'])){
                            for ($i=0; $i < count($_POST['nombre_familiar1']); $i++) {
                                $ingresos = 0;
                                if (isset($_POST['ingresos_familiar'][$i]))
                                    $ingresos = $_POST['ingresos_familiar'][$i];

                                $responsable = 0;
                                if ($_POST['responsable_apre'] == ($i+1))
                                    $responsable = $_POST['responsable_apre'];
                                
                                $arreglo_familia = [
                                    'id_ficha'              => $id,
                                    'nombre_familiar1'      => $_POST['nombre_familiar1'][$i],
                                    'nombre_familiar2'      => $_POST['nombre_familiar2'][$i],
                                    'apellido_familiar1'    => $_POST['apellido_familiar1'][$i],
                                    'apellido_familiar2'    => $_POST['apellido_familiar2'][$i],
                                    'fecha_familiar'        => $_POST['fecha_familiar'][$i],
                                    'sexo_familiar'         => $_POST['sexo_familiar'][$i],
                                    'parentesco_familiar'   => $_POST['parentesco_familiar'][$i],
                                    'ocupacion_familiar'    => $_POST['ocupacion_familiar'][$i],
                                    'trabaja_familiar'      => $_POST['trabaja_familiar'][$i],
                                    'ingresos_familiar'     => $ingresos
                                ];

                                if (!$objeto->registrarFamilares($arreglo_familia))
                                    $errorF++;
                            }
                        }

                        $errorI = 0;
                        for ($i2 = 0; $i2 < 10; $i2++) {
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
                            
                            $arregloGestionDinero = [
                                'id_ficha'      => $id,
                                'descripcion'   => $listaDinero[$i2],
                                'cantidad'      => $_POST[$listaDinero[$i2]]
                            ];

                            if (!$objeto->registrarGestionDinero($arregloGestionDinero))
                                $errorI++;
                        }

                        if ($errorF == 0 AND $errorI == 0) {
                            $objeto->guardarTransaccion();
                            echo 'Registro exitoso';
                        } else {
                            $localidadError = '';
                            if ($errorF > 0) {
                                $localidadError = 'Datos familiares';
                            } else if ($errorI > 0) {
                                $localidadError = 'Ingresos familiares';
                            } else {
                                $localidadError = 'Datos e ingresos familiares';
                            }

                            $objeto->calcelarTransaccion();
                            echo 'Registro fallido: '.$localidadError;
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
            ////////////////////// LIMPIAR DATOS ///////////////////////
            $datosLimpios = [];
            foreach ($_POST as $posicion => $valor) {
                $datosLimpios[$posicion] = htmlspecialchars($valor);
            }
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $datosLimpios['ordenar_tipo'] = 'ASC';
            if ($_POST['tipo_ord'] == 1)
                $datosLimpios['ordenar_tipo'] = 'ASC';
            else if ($_POST['tipo_ord'] == 2)
                $datosLimpios['ordenar_tipo'] = 'DESC';
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $datosLimpios['ordenar_por'] = 't_informe_social.numero '.$datosLimpios['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $datosLimpios['ordenar_por'] = 't_informe_social.numero '.$datosLimpios['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $datosLimpios['ordenar_por'] = 't_datos_personales.cedula '.$datosLimpios['ordenar_tipo'];
            else if ($_POST['ordenar'] == 3)
                $datosLimpios['ordenar_por'] = 't_informe_social.fecha '.$datosLimpios['ordenar_tipo'];
            else if ($_POST['ordenar'] == 4)
                $datosLimpios['ordenar_por'] = 'concat (t_datos_personales.nombre1, t_datos_personales.nombre2, t_datos_personales.apellido1, t_datos_personales.apellido1) '.$datosLimpios['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados'] = $objeto->consultarInformeSocial($datosLimpios);
            $resultados['total']    = $objeto->consultarInformeSocialTotal($datosLimpios);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Consultar determinado':
            $data = [];
            foreach ($_POST as $key => $value) {
                if(!is_array($value)) {
                    if ($value != '')
                        $data[$key] = "'".htmlspecialchars($value)."'";
                    else
                        $data[$key] = 'NULL';

                    if (!isset($_POST['titulo']))
                        $data['titulo'] = 'NULL';
                }
            }

            $resultados = [];
            $objeto->conectar();
            $resultados['vivienda']     = $objeto->consultarDatosVivienda($data);
            $resultados['familiares']   = $objeto->consultarFamiliares($data);
            $resultados['ingresos']     = $objeto->consultarDinero($data);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $data = [];
            foreach ($_POST as $key => $value) {
                if(!is_array($value)) {
                    if ($value != '')
                        $data[$key] = "'".htmlspecialchars($value)."'";
                    else
                        $data[$key] = 'NULL';

                    if (!isset($_POST['titulo']))
                        $data['titulo'] = 'NULL';
                }
            }

            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarDatosPersonales($data)){
                if ($objeto->modificarDatosVivienda($data)){
                    if ($objeto->modificarInformeSocial($data)){
                        $errorE = 0;
                        if(isset($_POST['eliminar_f'])){
                            $_POST['eliminar_f'] = json_decode($_POST['eliminar_f']);
                            for ($i=0; $i < count($_POST['eliminar_f']); $i++) {
                                if (!$objeto->eliminarFamilia($_POST['eliminar_f'][$i]))
                                    $errorE++;
                            }
                        }

                        $errorF = 0;
                        if(isset($_POST['nombre_familiar'])){
                            for ($i=0; $i < count($_POST['nombre_familiar']); $i++) {
                                $ingresos = 0;
                                if (isset($_POST['ingresos_familiar'][$i]))
                                    $ingresos = "'".htmlspecialchars($_POST['ingresos_familiar'][$i])."'";
                                    
                                $data2 = [
                                    'id_ficha'              => $data['informe_social'],
                                    'id_familiar'           => "'".htmlspecialchars($_POST['id_familiar'][$i])."'",
                                    'nombre_familiar'       => "'".htmlspecialchars($_POST['nombre_familiar'][$i])."'",
                                    'fecha_familiar'        => "'".htmlspecialchars($_POST['fecha_familiar'][$i])."'",
                                    'sexo_familiar'         => "'".htmlspecialchars($_POST['sexo_familiar'][$i])."'",
                                    'parentesco_familiar'   => "'".htmlspecialchars($_POST['parentesco_familiar'][$i])."'",
                                    'ocupacion_familiar'    => "'".htmlspecialchars($_POST['ocupacion_familiar'][$i])."'",
                                    'trabaja_familiar'      => "'".htmlspecialchars($_POST['trabaja_familiar'][$i])."'",
                                    'ingresos_familiar'     => htmlspecialchars($ingresos)
                                ];

                                if ($_POST['id_familiar'][$i] != '') {
                                    if (!$objeto->modificarFamiliaresAprendiz($data2))
                                        $errorF++;
                                } else {
                                    if (!$objeto->registrarFamilares($data2))
                                        $errorF++;
                                }
                            }
                        }

                        $objeto->eliminarGestionDinero($data);
                        $errorI = 0;
                        for ($i2 = 0; $i2 < 10; $i2++) {
                            $listaDinero = ['ingreso_pension', 'ingreso_seguro', 'ingreso_pension_otras', 'ingreso_sueldo', 'otros_ingresos',
                            'egreso_servicios', 'egreso_alimentario', 'egreso_educacion', 'egreso_vivienda', 'otros_egresos'];
                            
                            $data3 = [
                                'id_ficha'      => $data['informe_social'],
                                'descripcion'   => "'".$listaDinero[$i2]."'",
                                'cantidad'      => $data[$listaDinero[$i2]]
                            ];

                            if (!$objeto->registrarGestionDinero($data3))
                                $errorI++;
                        }

                        if ($errorE == 0 AND $errorF == 0 AND $errorI == 0) {
                            $objeto->guardarTransaccion();
                            echo 'Modificacion exitosa';
                        } else {
                            $objeto->calcelarTransaccion();
                            echo 'Modificación fallida';
                        }
                    } else {
                        $objeto->calcelarTransaccion();
                        echo 'Modificación fallida';
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Modificación fallida';
                }
            } else {
                $objeto->calcelarTransaccion();
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