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
            if ($objeto->registrarDatosPersonales($data)) {
                if ($objeto->registrarDatosVivienda($data)) {
                    if ($id = $objeto->registrarFichaAprendiz($data)) {
                        $error = 0;
                        if(isset($_POST['nombre_familiar'])){
                            for ($i=0; $i < count($_POST['nombre_familiar']); $i++) {
                                $ingresos = 0;
                                if (isset($_POST['ingresos_familiar'][$i]))
                                    $ingresos = "'".htmlspecialchars($_POST['ingresos_familiar'][$i])."'";
                                
                                $data2 = [
                                    'id_ficha'              => $id,
                                    'nombre_familiar'       => "'".htmlspecialchars($_POST['nombre_familiar'][$i])."'",
                                    'fecha_familiar'        => "'".htmlspecialchars($_POST['fecha_familiar'][$i])."'",
                                    'sexo_familiar'         => "'".htmlspecialchars($_POST['sexo_familiar'][$i])."'",
                                    'parentesco_familiar'   => "'".htmlspecialchars($_POST['parentesco_familiar'][$i])."'",
                                    'ocupacion_familiar'    => "'".htmlspecialchars($_POST['ocupacion_familiar'][$i])."'",
                                    'trabaja_familiar'      => "'".htmlspecialchars($_POST['trabaja_familiar'][$i])."'",
                                    'ingresos_familiar'     => $ingresos,
                                    'responsable'           => 0
                                ];

                                if (!$objeto->registrarFamilares($data2))
                                    $error++;
                            }
                        }

                        if ($error == 0) {
                            $objeto->guardarTransaccion();
                            echo 'Registro exitoso';
                        } else {
                            $objeto->calcelarTransaccion();
                            echo 'Registro fallido';
                        }
                    } else {
                        $objeto->calcelarTransaccion();
                        echo 'Registro fallido';
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    echo 'Registro fallido';
                }
            } else {
                $objeto->calcelarTransaccion();
                echo 'Registro fallido';
            }
            $objeto->desconectar();
            break;

        case 'Consultar':
            $resultados = [];
            $objeto->conectar();
            $resultados['informes'] = $objeto->consultarInformeSocial();
            $objeto->desconectar();
            echo json_encode($resultados);
            break;

        case 'Traer datos 2':
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
            $resultados['vivienda'] = $objeto->consultarDatosVivienda($data);
            $resultados['familiares'] = $objeto->consultarFamiliares($data);
            $objeto->desconectar();
            echo json_encode($resultados);
            break;
    }
} else { // SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet');
}