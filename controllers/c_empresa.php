<?php 
session_start();
if ($_POST['opcion']) {
    require_once('../models/m_empresa.php');
    $objeto = new model_empresa;
    
    switch ($_POST['opcion']) {
        case 'Traer datos':
            ///////////////////// HACER CONSULTAS //////////////////////
            $data = [];
            $objeto->conectar();
            $data['actividades']= $objeto->consultarActividades();
            $data['cargos']     = $objeto->consultarCargos();
            $data['estados']    = $objeto->consultarEstados();
            $objeto->desconectar();
            echo json_encode($data);
        break;

        case 'Traer ciudades':
            $data = [];
            $objeto->conectar();
            $data['ciudades'] = $objeto->consultarCiudades($_POST);
            $objeto->desconectar();
            echo json_encode($data);
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

        case 'Registrar': 
            $objeto->conectar();
            $objeto->nuevaTransaccion();

            if ($objeto->registrarEmpresa($_POST)) {
                $cant_errores = 0;
                for ($var = 0; $var < count($_POST['nacionalidad_contacto']); $var++) {
                    $dato_contacto  = [
                        'nacionalidad_contacto' => $_POST['nacionalidad_contacto'][$var],
                        'cedula_contacto'       => $_POST['cedula_contacto'][$var],
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
                    if ($objeto->consultarPersonaContacto($dato_contacto) == 0) {
                        $estatus_contacto = $objeto->registrarPersonaContacto($dato_contacto);
                    } else {
                        $estatus_contacto = true;
                    }

                    if ($estatus_contacto) {
                        $datos_conec    = [
                            'rif_empresa'           => $_POST['rif'],
                            'nacionalidad_contacto' => $_POST['nacionalidad_contacto'][$var],
                            'cedula_contacto'       => $_POST['cedula_contacto'][$var],
                            'cargo_contacto'        => $_POST['cargo_contacto'][$var],
                        ];

                        // SI RETORNA FALSE (Falso), HUBO ERROR AL REGISTRAR.
                        if (!$objeto->registrarRelacionEmpresaContacto($datos_conec)) { $cant_errores++; }
                    } else {
                        $cant_errores++;
                    }
                }

                if ($cant_errores == 0) {
                    echo 'Registro exitoso';
                    $objeto->guardarTransaccion();
                } else {
                    echo 'Registro fallido: Contactos de la empresa';
                    $objeto->calcelarTransaccion();
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
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            if ($objeto->modificarPersonaContacto($_POST)){
                if ($objeto->modificarEmpresa($_POST)) {
                    echo 'Modificación exitosa';
                    $objeto->guardarTransaccion();
                } else {
                    echo 'Modificación fallida: Datos de la empresa';
                    $objeto->calcelarTransaccion();
                }
            } else {
                echo 'Modificación fallida: Datos personales';
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
    }
}
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
else
{
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../iniciar');
}