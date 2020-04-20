<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('d-m-Y', time());

if ($_POST['opcion']) {
    require_once('../models/m_facilitador.php');
    $objeto = new model_facilitador;
    
    switch ($_POST['opcion']) {
        ////////////////////////////////////////////////////////////////
        // OPERACIONES PARA CARGAR EL FORMULARIO A TRAVES DE AJAX
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            $resultados['fecha']    = $date;
            $resultados['ocupacion']= $objeto->consultarOcupaciones();
            $resultados['oficio']   = $objeto->consultarOficios();
            $resultados['estado']   = $objeto->consultarEstados();
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

        case 'Registrar ocupacion':
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaOcupacionR($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarOcupacion($_POST)) {
                    echo 'Registro exitoso';
                } else {
                    echo 'Registro fallido';
                }
            } else {
                echo 'Ya está registrado';
            }
            $objeto->desconectar();
        break;

        case 'Traer ocupaciones actualizadas':
            $resultados = [];
            $objeto->conectar();
            $resultados['ocupacion'] = $objeto->consultarOcupaciones();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        ////////////////////////////////////////////////////////////////
        // OPERACIONES PRINCIPAL DEL MODULO
        case 'Registrar':
            $fecha_c = $_POST['fecha_n'];
            $_POST['fecha_n'] = date("Y-m-d", strtotime($fecha_c));

            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->registrarFacilitador($_POST)) {
                $error_arch = 0;
                $nombres_ar = [];
                if (isset($_POST['nombre_archivo_temp'])) {
                    for ($var = 0; $var < count($_POST['nombre_archivo_temp']); $var++) {
                        $extension  = explode(".", $_POST['nombre_archivo_temp'][$var]);
                        $ultma      = count($extension);
                        $extension  = $extension[$ultma - 1];
                        ////////////////////////

                        $arreglo_archivo = [
                            'nacionalidad'  => $_POST['nacionalidad'],
                            'cedula'        => $_POST['cedula'],
                            'extension'     => $extension,
                            'descripcion'   => $_POST['descrip_archivo_temp'][$var]
                        ];
                        $id_archivo = $objeto->registrarArchivo($arreglo_archivo);
                        $nombres_ar[$var] = $id_archivo.'.'.$extension;
                        // SI HUBO ALGUN ERROR (id_archivo ES IGUAL A FALSE), ENTONCES SUBE EL CONTADOR DE ERRORES +1
                        if (!$id_archivo) {
                            $error_arch++;
                        }
                    }
                }

                // COMPROBAMOS QUE EL NUMERO DE ERRORES ES 0
                if ($error_arch == 0) {
                    // SI EL REGISTRO DE LOS ARCHIVO FUE EXITOSO; SE PROCEDE A MOVER DE LA CARPETA TEMPORAL A LA CARPETA DE ARCHIVOS.
                    if (isset($_POST['nombre_archivo_temp'])) {
                        for ($var = 0; $var < count($_POST['nombre_archivo_temp']); $var++) {
                            copy    ('../images/temp/'.$_POST['nombre_archivo_temp'][$var], '../images/archivos/'.$nombres_ar[$var]);
                            unlink  ('../images/temp/'.$_POST['nombre_archivo_temp'][$var]);
                        }
                    }

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
            $objeto->desconectar();
        break;

        case 'Consultar':
            $resultados = [];
            $objeto->conectar();
            /////////////////// ESTABLECER ORDER BY ////////////////////
            $_POST['ordenar_tipo'] = 'ASC';
            if ($_POST['tipo_ord'] == 1)
                $_POST['ordenar_tipo'] = 'ASC';
            else if ($_POST['tipo_ord'] == 2)
                $_POST['ordenar_tipo'] = 'DESC';
            ///////////////// ESTABLECER TIPO DE ORDEN /////////////////
            $_POST['ordenar_por'] = 't_datos_personales.cedula '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $_POST['ordenar_por'] = 't_datos_personales.cedula '.$_POST['ordenar_tipo'];
            if ($_POST['ordenar'] == 2)
                $_POST['ordenar_por'] = 'CONCAT(t_datos_personales.nombre1, t_datos_personales.nombre2, t_datos_personales.apellido1, t_datos_personales.apellido2) '.$_POST['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarDatosPersonales($_POST);
            $resultados['total']        = $objeto->consultarDatosPersonalesTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $fecha_c = $_POST['fecha_n'];
            $_POST['fecha_n'] = date("Y-m-d", strtotime($fecha_c));

            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarFacilitador($_POST)) {
                $error_arch = 0;
                $nombres_ar = [];
                if (isset($_POST['nombre_archivo_temp'])) {
                    for ($var = 0; $var < count($_POST['nombre_archivo_temp']); $var++) {
                        $extension  = explode(".", $_POST['nombre_archivo_temp'][$var]);
                        $ultma      = count($extension);
                        $extension  = $extension[$ultma - 1];
                        ////////////////////////

                        $arreglo_archivo = [
                            'id_archivo'    => $_POST['id_archivo_temp'][$var],
                            'nacionalidad'  => $_POST['nacionalidad'],
                            'cedula'        => $_POST['cedula'],
                            'extension'     => $extension,
                            'descripcion'   => $_POST['descrip_archivo_temp'][$var]
                        ];

                        // SI EL ID ES IGUAL A 0 ENTONCES LO REGISTRAMOS, DE LO CONTRARIO LO MODIFICAMOS.
                        if ($_POST['id_archivo_temp'][$var] == '0') {
                            $id_archivo = $objeto->registrarArchivo($arreglo_archivo);
                            $nombres_ar[$var] = $id_archivo.'.'.$extension;
                        } else {
                            $id_archivo = $objeto->modificarArchivo($arreglo_archivo);
                        }
                        // SI HUBO ALGUN ERROR (id_archivo ES IGUAL A FALSE), ENTONCES SUBE EL CONTADOR DE ERRORES +1
                        if (!$id_archivo) {
                            $error_arch++;
                        }
                    }
                }

                // COMPROBAMOS SI HAY ARCHIVOS POR ELIMINAR DE LA BASE DE DATOS
                $arreglo_eliminar = json_decode($_POST['eliminare_archivos']);
                for ($var = 0; $var < count($arreglo_eliminar); $var++) {
                    $id_archivo  = explode(".", $arreglo_eliminar[$var]);
                    $id_archivo  = $id_archivo[0];
                    if (!$objeto->eliminarArchivo($id_archivo)) {
                        $error_arch++;
                    }
                }

                // COMPROBAMOS QUE EL NUMERO DE ERRORES ES 0
                if ($error_arch == 0) {
                    // SI EL REGISTRO DE LOS ARCHIVO FUE EXITOSO; SE PROCEDE A MOVER DE LA CARPETA TEMPORAL A LA CARPETA DE ARCHIVOS.
                    if (isset($_POST['nombre_archivo_temp'])) {
                        for ($var = 0; $var < count($_POST['nombre_archivo_temp']); $var++) {
                            if (isset($nombres_ar[$var])) {
                                copy    ('../images/temp/'.$_POST['nombre_archivo_temp'][$var], '../images/archivos/'.$nombres_ar[$var]);
                                unlink  ('../images/temp/'.$_POST['nombre_archivo_temp'][$var]);
                            }
                        }
                    }

                    // LOS ARCHIVOS ELIMINADOS DE LA BASE DE DATOS SON ELIMINADOS DEL SERVIDOR TAMBIEN.
                    for ($var = 0; $var < count($arreglo_eliminar); $var++) {
                        unlink('../images/archivos/'.$arreglo_eliminar[$var]);
                    }

                    $objeto->guardarTransaccion();
                    echo 'Modificación exitosa';
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

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusFacilitador($_POST)) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
    }
}
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
else
{
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}