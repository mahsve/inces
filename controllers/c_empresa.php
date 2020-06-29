<?php 
session_start();
date_default_timezone_set("America/Caracas");
$fecha_actual = date('d-m-Y', time());

if ($_POST['opcion']) {
    require_once('../models/m_empresa.php');
    $objeto = new model_empresa;
    
    switch ($_POST['opcion']) {
        // CONSULTAR DATOS
        case 'Traer datos':
            ///////////////////// HACER CONSULTAS //////////////////////
            $objeto->conectar();
            $resultados = [];
            $resultados['fecha']        = $fecha_actual;
            $resultados['actividades']  = $objeto->consultarActividades();
            $resultados['cargos']       = $objeto->consultarCargos();
            $resultados['estados']      = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer divisiones':
            $objeto->conectar();
            $resultados = [];
            $resultados['ciudades']     = $objeto->consultarCiudades($_POST);
            $resultados['municipios']   = $objeto->consultarMunicipios($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Traer parroquias':
            $objeto->conectar();
            $resultados = [];
            $resultados['parroquias']   = $objeto->consultarParroquias($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Verificar RIF':
            $objeto->conectar();
            $data = $objeto->verificarRIF($_POST);
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Verificar cedula':
            $objeto->conectar();
            $data = $objeto->verificarCedula($_POST);
            $objeto->desconectar();
            echo json_encode($data);
        break;
        // FIN CONSULTAR DATOS


        // REGISTROS RAPIDOS
        case 'Registrar actividad economica':
            $data = [];
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaR_AE($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarActividadEconomica($_POST)) {
                    $data['actividades'] = $objeto->consultarActividades();
                    echo json_encode($data);
                } else {
                    echo json_encode('Registro fallido');
                }
            } else {
                echo json_encode('Ya está registrado');
            }
            $objeto->desconectar();
        break;

        case 'Registrar cargo':
            $data = [];
            $objeto->conectar();
            // SE CONFIRMA QUE NO ESTE REGISTRADO
            if ($objeto->confirmarExistenciaR_CC($_POST) == 0) {
                // SE PROCEDE A REGISTRAR
                if ($objeto->registrarCargoContacto($_POST)) {
                    $data['cargos'] = $objeto->consultarCargos();
                    echo json_encode($data);
                } else {
                    echo json_encode('Registro fallido');
                }
            } else {
                echo json_encode('Ya está registrado');
            }
            $objeto->desconectar();
        break;
        // REGISTROS RAPIDOS

        
        // OPERACIONES BASICAS
        case 'Registrar': 
            // REORDENAMOS LA FECHA
            $fecha_c = $_POST['fecha'];   $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));

            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // SE PROCEDE A REGISTRAR LA EMPRESA Y VERIFICAR QUE NO HAYA ERRORES.
            if ($objeto->registrarEmpresa($_POST)) {
                // PROCEDEMOS A REGISTRAR A LOS CONTACTOS DE LA EMPRESA
                $cant_errores = 0; $cant_errores2 = 0;
                for ($var = 0; $var < count($_POST['codigo_registro']); $var++) {
                    // ARREGLO CON TODOS LOS DATOS DEL CONTACTO.
                    $dato_contacto  = [
                        'nacionalidad_contacto' => $_POST['nacionalidad_contacto'][$var],
                        'nacionalidad_contacto2'=> $_POST['nacionalidad_contacto2'][$var],
                        'cedula_contacto'       => $_POST['cedula_contacto'][$var],
                        'cedula_contacto2'      => $_POST['cedula_contacto2'][$var],

                        'nombre1_contacto'      => $_POST['nombre1_contacto'][$var],
                        'nombre2_contacto'      => $_POST['nombre2_contacto'][$var],
                        'apellido1_contacto'    => $_POST['apellido1_contacto'][$var],
                        'apellido2_contacto'    => $_POST['apellido2_contacto'][$var],
                        'ciudad_contacto'       => $_POST['ciudad_contacto'][$var],
                        'direccion_contacto'    => $_POST['direccion_contacto'][$var],
                        'telefono1_contacto'    => $_POST['telefono1_contacto'][$var],
                        'telefono2_contacto'    => $_POST['telefono2_contacto'][$var],
                        'correo_contacto'       => $_POST['correo_contacto'][$var],
                    ];

                    // CREAMOS UNA VARIABLE PARA VERFICAR SI YA EXISTE Y OMITIR EL REGISTRO O EN TODO CASO
                    // VERIFICAR QUE SE HAYA REGISTRADO CORRECTAMENTE.
                    $estatus_contacto = false;
                    if ($_POST['nacionalidad_contacto2'][$var] == 0 AND $_POST['cedula_contacto2'][$var] == 0) {
                        $estatus_contacto = $objeto->registrarPersonaContacto($dato_contacto);
                    } else {
                        $estatus_contacto = $objeto->modificarPersonaContacto($dato_contacto);
                    }

                    // VERIFICAMOS QUE SE HAYA REGISTRADO O MODIFICADO CON EXISTO SEGUN EL CASO.
                    if ($estatus_contacto) {
                        $datos_conec    = [
                            'rif_empresa'           => $_POST['rif'],
                            'numero_contacto'       => $_POST['codigo_registro'][$var],
                            'nacionalidad_contacto' => $_POST['nacionalidad_contacto'][$var],
                            'cedula_contacto'       => $_POST['cedula_contacto'][$var],
                            'cargo_contacto'        => $_POST['cargo_contacto'][$var],
                        ];

                        // SI RETORNA FALSE (Falso), HUBO ERROR AL REGISTRAR.
                        if (!$objeto->registrarRelacionEmpresaContacto($datos_conec)) { $cant_errores2++; }
                    } else {
                        $cant_errores++;
                    }
                }

                // VERIFICAMOS QUE NO HAYAN ERRORES Y GUARDAMOS LOS CAMBIOS.
                if ($cant_errores == 0 AND $cant_errores2 == 0) {
                    echo 'Registro exitoso';
                    $objeto->guardarTransaccion();
                } else {
                    $objeto->calcelarTransaccion();
                    if      ($cant_errores > 0 AND $cant_errores2 == 0) { echo 'Registro fallido: Registrar/Modificar contactos'; }
                    else if ($cant_errores2 > 0 AND $cant_errores == 0) { echo 'Registro fallido: Registrar relación contacto-empresa'; }
                    else    { echo 'Registro fallido: Registrar contacto y relación'; }
                }
            } else {
                echo 'Registro fallido: Datos de la empresa';
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
            $campo_ordenar = 't_empresa.rif '.$campo_m_ordenar;
            if      ($_POST['campo_ordenar'] == 1) { $campo_ordenar = 't_empresa.rif '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 2) { $campo_ordenar = 't_empresa.razon_social '.$campo_m_ordenar; }
            else if ($_POST['campo_ordenar'] == 3) { $campo_ordenar = 't_actividad_economica.nombre '.$campo_m_ordenar; }
            $_POST['campo_ordenar'] = $campo_ordenar;
            ////////////////////////////////////////////////////////////

            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados']   = $objeto->consultarEmpresas($_POST);
            $resultados['total']        = $objeto->consultarEmpresasTotal($_POST);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            // REORDENAMOS LA FECHA
            $fecha_c = $_POST['fecha'];   $_POST['fecha'] = date("Y-m-d", strtotime($fecha_c));

            $objeto->conectar();
            $objeto->nuevaTransaccion();

            // MODIFICAMOS LOS DATOS DE LA EMPRESA Y VERIFICAMOS QUE NO HAYA ERRORES.
            if ($objeto->modificarEmpresa($_POST)) {
                // RECORREMOS LAS PERSONAS DE CONTACTO AGREGADOS A LA EMPRESA
                $cant_errores = 0; $cant_errores2 = 0;
                for ($var = 0; $var < count($_POST['codigo_registro']); $var++) {
                    $dato_contacto  = [
                        'nacionalidad_contacto' => $_POST['nacionalidad_contacto'][$var],
                        'nacionalidad_contacto2'=> $_POST['nacionalidad_contacto2'][$var],
                        'cedula_contacto'       => $_POST['cedula_contacto'][$var],
                        'cedula_contacto2'      => $_POST['cedula_contacto2'][$var],

                        'nombre1_contacto'      => $_POST['nombre1_contacto'][$var],
                        'nombre2_contacto'      => $_POST['nombre2_contacto'][$var],
                        'apellido1_contacto'    => $_POST['apellido1_contacto'][$var],
                        'apellido2_contacto'    => $_POST['apellido2_contacto'][$var],
                        'ciudad_contacto'       => $_POST['ciudad_contacto'][$var],
                        'direccion_contacto'    => $_POST['direccion_contacto'][$var],
                        'telefono1_contacto'    => $_POST['telefono1_contacto'][$var],
                        'telefono2_contacto'    => $_POST['telefono2_contacto'][$var],
                        'correo_contacto'       => $_POST['correo_contacto'][$var],
                    ];

                    // CREAMOS UNA VARIABLE PARA VERFICAR SI YA EXISTE Y OMITIR EL REGISTRO O EN TODO CASO
                    // VERIFICAR QUE SE HAYA REGISTRADO CORRECTAMENTE.
                    $estatus_contacto = false;
                    if ($_POST['nacionalidad_contacto2'][$var] == 0 AND $_POST['cedula_contacto2'][$var] == 0) {
                        $estatus_contacto = $objeto->registrarPersonaContacto($dato_contacto);
                    } else {
                        $estatus_contacto = $objeto->modificarPersonaContacto($dato_contacto);
                    }

                    // VERIFICAMOS QUE SE HAYA REGISTRADO O MODIFICADO CON EXISTO SEGUN EL CASO.
                    if ($estatus_contacto) {
                        $datos_conec    = [
                            'rif_empresa'           => $_POST['rif'],
                            'numero_contacto'       => $_POST['codigo_registro'][$var],
                            'nacionalidad_contacto' => $_POST['nacionalidad_contacto'][$var],
                            'cedula_contacto'       => $_POST['cedula_contacto'][$var],
                            'cargo_contacto'        => $_POST['cargo_contacto'][$var],
                        ];

                        // SE PROCEDE A REGISTRAR LA RELACION SI NO HAY NUMERO DE REGISTRO
                        if ($_POST['codigo_registro'][$var] == 0) {
                            // SI RETORNA FALSE (Falso), HUBO ERROR AL REGISTRAR.
                            if (!$objeto->registrarRelacionEmpresaContacto($datos_conec)) { $cant_errores2++; }
                        } else {
                            // SI RETORNA FALSE (Falso), HUBO ERROR AL MODIFICAR.
                            if (!$objeto->modificarRelacionEmpresaContacto($datos_conec)) { $cant_errores2++; }
                        }
                    } else {
                        $cant_errores++;
                    }
                }

                if ($cant_errores == 0 AND $cant_errores2 == 0) {
                    // ELIMINAR ASIGNATURAS SELECCIONADAS POR EL USUARIO
                    $errores_eliminar   = 0; $errores_eliminar2   = 0;
                    $eliminar_contactos = json_decode($_POST['eliminar_contactos']);
                    for ($var = 0; $var < count($eliminar_contactos); $var++) {
                        // CONSULTAMOS SUS DATOS PERSONALES Y SI ES SOLAMENTE UN CONTACTO DE LA EMPRESA.
                        $datos_contacto = $objeto->consultarContactoEmpresa($eliminar_contactos[$var]);
                        if (!$objeto->eliminarRelacionEmpresaContacto($eliminar_contactos[$var])) { $errores_eliminar++; }
                        
                        // SI SOLO ES UN CONTACTO DE LA EMPRESA Y NO CUMPLE OTRA FUNCION DENTRO DEL SISTEMAS SE PROCEDE A ELIMINAR
                        if ($datos_contacto['tipo_persona'] == 'C' AND $datos_contacto['relaciones'] == 1) {
                            $datosContacto = [
                                'nacionalidad'  => $datos_contacto['nacionalidad'],
                                'cedula'        => $datos_contacto['cedula']
                            ];

                            // VERIFICAMOS QUE SE HAYA ELIMINADO CORRECTAMENTE
                            if (!$objeto->eliminarDatosContacto($datosContacto)) { $errores_eliminar2++; }
                        }
                    }

                    // VERIFICAMOS QUE SE HAYAN ELIMINADOS LOS DATOS CORRESPONDIENTES SIN ERRORES.
                    if ($errores_eliminar == 0 AND $errores_eliminar2 == 0) {
                        echo 'Modificación exitosa';
                        $objeto->guardarTransaccion();
                    } else {
                        $objeto->calcelarTransaccion();
                        if      ($errores_eliminar > 0 AND $errores_eliminar2 == 0) { echo 'Modificación fallida: Eliminar contactos de la empresa'; }
                        else if ($errores_eliminar2 > 0 AND $errores_eliminar == 0) { echo 'Modificación fallida: Eliminar contactos del sistema'; }
                        else { echo 'Modificación fallida: Eliminar contactos del sistema y de la empresa'; }
                    }
                } else {
                    $objeto->calcelarTransaccion();
                    if      ($cant_errores > 0 AND $cant_errores2 == 0) { echo 'Modificación fallida: Registrar/Modificar contactos'; }
                    else if ($cant_errores2 > 0 AND $cant_errores == 0) { echo 'Modificación fallida: Registrar/Modificar relación contacto-empresa'; }
                    else    { echo 'Modificación fallida: Registrar contacto y relación'; }
                }
            } else {
                echo 'Modificación fallida: Datos de la empresa';
                $objeto->calcelarTransaccion();
            }

            $objeto->desconectar();
        break;

        case 'Estatus':
            $objeto->conectar();
            if ($objeto->estatusEmpresa($_POST)) {
                echo 'Modificación exitosa';
            } else {
                echo 'Modificación fallida';
            }
            $objeto->desconectar();
        break;
        // OPERACIONES BASICAS
    }
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
    // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
    $_SESSION['msj']['type'] = 'danger';
    $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
    header('Location: ../intranet/dashboard');
}