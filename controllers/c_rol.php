<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_rol.php');
    $objeto = new model_rol;
    
    switch ($_POST['opcion'])
    {
        case 'Traer datos':
            $resultados = [];
            $objeto->conectar();
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados = $objeto->consultarVistaFormulario();
            $objeto->desconectar();
            echo json_encode($resultados);
        break;
        
        case 'Registrar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if(!is_array($valor)) {
                    if ($valor != '')
                        $data[$indice] = "'".htmlspecialchars($valor)."'";
                    else
                        $data[$indice] = 'NULL';
                }
            }

            $objeto->conectar();
            $objeto->nuevaTransaccion(); // SE INICIA UNA TRANSACCION YA QUE SON VARIOS REGISTROS Y NINGUNOS DEBEN FALLAR.
            $resultado = $objeto->registrarRol($data); // REGISTRAMOS ROL.
            if ($resultado) { // VERIFICAMOS SI SE REGISTRO.
                $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
                if (isset($_POST['modulos'])) {
                    for ($i = 0; $i < count($_POST['modulos']); $i++) { // RECORREMOS TODOS LOS MODULOS ASIGNADOS.
                        // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                        $datos2 = [
                            'modulo' => "'".htmlspecialchars($_POST['modulos'][$i])."'",
                            'codigo' => "'".htmlspecialchars($resultado)."'"
                        ];
    
                        // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                        $resultado2 = $objeto->registrarModulosDelRol($datos2);
                        if (!$resultado2) { // SI ALGUNO FALLO GUARDA VERDADERO.
                            $errores = true;
                        }
                    }
                }

                if (!$errores) { // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS.
                    $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
                    if (isset($_POST['vistas'])) {
                        for ($i = 0; $i < count($_POST['vistas']); $i++) { // RECORREMOS TODAS LAS VISTAS ASIGNADAS.
                            $registrar = false;
                            if (isset($_POST[ 'registrar'.$_POST['vistas'][$i] ]))
                                $registrar = true;
    
                            $modificar = false;
                            if (isset($_POST['modificar'.$_POST['vistas'][$i]]))
                                $modificar = true;
                            
                            $estatus = false;
                            if (isset($_POST['estatus'.$_POST['vistas'][$i]]))
                                $estatus = true;
    
                            // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                            $datos2 = [
                                'vista'     => "'".htmlspecialchars($_POST['vistas'][$i])."'",
                                'codigo'    => "'".htmlspecialchars($resultado)."'",
                                'registrar' => "'".htmlspecialchars($registrar)."'",
                                'modificar' => "'".htmlspecialchars($modificar)."'",
                                'act_desc'  => "'".htmlspecialchars($estatus)."'",
                                'eliminar'  => 1
                            ];
    
                            // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                            $resultado2 = $objeto->registrarVistasDelRol($datos2);
                            if (!$resultado2) // SI ALGUNO FALLO GUARDA VERDADERO.
                            {
                                $errores = true;
                            }
                        }
                    }

                    if (!$errores) { // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS AL REGISTRAR TODAS LAS VISTAS.
                        $objeto->guardarTransaccion(); // GUARDAMOS TODOS LOS REGISTROS.
                        echo 'Registro exitoso';
                    } else {
                        $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                        echo 'Registro fallido';
                    }
                } else {
                    $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                    echo 'Registro fallido';
                }
            } else {
                $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                echo 'Registro fallido';
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
            $datosLimpios['ordenar_por'] = 'codigo '.$datosLimpios['ordenar_tipo'];
            if ($_POST['ordenar'] == 1)
                $datosLimpios['ordenar_por'] = 'codigo '.$datosLimpios['ordenar_tipo'];
            else if ($_POST['ordenar'] == 2)
                $datosLimpios['ordenar_por'] = 'nombre '.$datosLimpios['ordenar_tipo'];
            ///////////////////// HACER CONSULTAS //////////////////////
            $resultados['resultados'] = $objeto->consultarRoles($datosLimpios);
            $resultados['total']    = $objeto->consultarRolesTotal($datosLimpios);
            $objeto->desconectar();
            echo json_encode($resultados);
        break;

        case 'Modificar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if(!is_array($valor)) {
                    if ($valor != '')
                        $data[$indice] = "'".htmlspecialchars($valor)."'";
                    else
                        $data[$indice] = 'NULL';
                }
            }

            $objeto->conectar();
            $resultado = $objeto->modificarRol($data);
            $objeto->nuevaTransaccion(); // SE INICIA UNA TRANSACCION YA QUE SON VARIOS REGISTROS Y NINGUNOS DEBEN FALLAR.
            if ($resultado) {
                $objeto->eliminarModulosDelRol($data); // ELIMINAMOS TODOS LOS MODULOS PARA PROCEDER A REGISTRAR LOS QUE DEJO EL ADMINSITRADOR.
                $objeto->eliminarVistasDelRol($data); // ELIMINAMOS TODAS LAS VISTAS PARA PROCEDER A REGISTRAR LOS QUE DEJO EL ADMINSITRADOR.

                $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
                if (isset($_POST['modulos'])) {
                    for ($i = 0; $i < count($_POST['modulos']); $i++) { // RECORREMOS TODOS LOS MODULOS ASIGNADOS.
                        // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                        $datos2 = [
                            'modulo' => "'".htmlspecialchars($_POST['modulos'][$i])."'",
                            'codigo' => "'".htmlspecialchars($_POST['codigo'])."'"
                        ];

                        // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                        $resultado2 = $objeto->registrarModulosDelRol($datos2);
                        if (!$resultado2) { // SI ALGUNO FALLO GUARDA VERDADERO.
                            $errores = true;
                        }
                    }
                }

                if (!$errores) { // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS.
                    $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
                    if (isset($_POST['vistas'])) {
                        for ($i = 0; $i < count($_POST['vistas']); $i++) { // RECORREMOS TODAS LAS VISTAS ASIGNADAS.
                            $registrar = false;
                            if (isset($_POST[ 'registrar'.$_POST['vistas'][$i] ]))
                                $registrar = true;

                            $modificar = false;
                            if (isset($_POST['modificar'.$_POST['vistas'][$i]]))
                                $modificar = true;
                            
                            $estatus = false;
                            if (isset($_POST['estatus'.$_POST['vistas'][$i]]))
                                $estatus = true;

                            // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                            $datos2 = [
                                'vista'     => "'".htmlspecialchars($_POST['vistas'][$i])."'",
                                'codigo'    => "'".htmlspecialchars($_POST['codigo'])."'",
                                'registrar' => "'".htmlspecialchars($registrar)."'",
                                'modificar' => "'".htmlspecialchars($modificar)."'",
                                'act_desc'  => "'".htmlspecialchars($estatus)."'",
                                'eliminar'  => 1
                            ];

                            // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                            $resultado2 = $objeto->registrarVistasDelRol($datos2);
                            if (!$resultado2) // SI ALGUNO FALLO GUARDA VERDADERO.
                            {
                                $errores = true;
                            }
                        }
                    }

                    if (!$errores) { // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS AL REGISTRAR TODAS LAS VISTAS.
                        $objeto->guardarTransaccion(); // GUARDAMOS TODOS LOS REGISTROS.
                        echo 'Modificacion exitosa';
                    } else {
                        $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                        echo 'Modificacion fallida';
                    }
                } else {
                    $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                    echo 'Modificacion fallida';
                }
            }
            $objeto->desconectar();
        break;

        case 'Eliminar':
            $data = [];
            foreach ($_POST as $indice => $valor) {
                if ($valor != '')
                    $data[$indice] = "'".htmlspecialchars($valor)."'";
                else
                    $data[$indice] = 'NULL';
            }

            $objeto->conectar();
            $resultado = $objeto->eliminarRol($data);
            if ($resultado) {
                echo 'Modificacion exitosa';
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
	// MANDAMOS UN MENSAJE
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}