<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_rol.php');
    $objeto = new model_rol;
    
    switch ($_POST['opcion']) {
        case 'Registrar';
            $datos = [
                'nombre'    => htmlspecialchars($_POST['nombre'])
            ];

            $objeto->conectar();
            $objeto->nuevaTransaccion(); // SE INICIA UNA TRANSACCION YA QUE SON VARIOS REGISTROS Y NINGUNOS DEBEN FALLAR.
            $resultado = $objeto->registrarRol($datos); // REGISTRAMOS ROL.
            if ($resultado) // VERIFICAMOS SI SE REGISTRO.
            {
                $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
                for ($i = 0; $i < count($_POST['modulos']); $i++) // RECORREMOS TODOS LOS MODULOS ASIGNADOS.
                {
                    // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                    $datos2 = [
                        'modulo' => $_POST['modulos'][$i],
                        'codigo' => $resultado
                    ];

                    // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                    $resultado2 = $objeto->registrarModulosDelRol($datos2);
                    if (!$resultado2) // SI ALGUNO FALLO GUARDA VERDADERO.
                    {
                        $errores = true;
                    }
                }

                if (!$errores) // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS.
                {
                    $objeto->guardarTransaccion(); // GUARDAMOS TODOS LOS REGISTROS.
                    // MANDAMOS UN MENSAJE
                    $_SESSION['msj']['type'] = 'success';
                    $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha registrado con exito.';
                }
                else
                {
                    $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                    // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS
                    $_SESSION['msj']['type'] = 'danger';
                    $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al registrar.';
                }
            }
            else
            {
                $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                // MANDAMOS UN MENSAJE
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al registrar.';
            }

            $objeto->desconectar();
            header('Location: ../intranet/gestion_rol');
            break;

        case 'Modificar':
            $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo']),
                'nombre'    => htmlspecialchars($_POST['nombre'])
            ];

            $objeto->conectar();
            ////////////////////////////
            $objeto->modificarRol($datos);
            $objeto->eliminarModulosDelRol($datos); // ELIMINAMOS TODOS LOS MODULOS PARA PROCEDER A REGISTRAR LOS QUE DEJO EL ADMINSITRADOR.
            $objeto->eliminarVistasDelRol($datos); // ELIMINAMOS TODAS LAS VISTAS PARA PROCEDER A REGISTRAR LOS QUE DEJO EL ADMINSITRADOR.

            $objeto->nuevaTransaccion(); // SE INICIA UNA TRANSACCION YA QUE SON VARIOS REGISTROS Y NINGUNOS DEBEN FALLAR.
            $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
            for ($i = 0; $i < count($_POST['modulos']); $i++) // RECORREMOS TODOS LOS MODULOS ASIGNADOS.
            {
                // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                $datos2 = [
                    'modulo' => $_POST['modulos'][$i],
                    'codigo' => $datos['codigo']
                ];

                // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                $resultado2 = $objeto->registrarModulosDelRol($datos2);
                if (!$resultado2) // SI ALGUNO FALLO GUARDA VERDADERO.
                {
                    $errores = true;
                }
            }

            if (!$errores) // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS AL REGISTRAR LOS MODULOS.
            {
                $errores = false; // GUARDAMOS UNA VARIABLE PARA GUARDAR SI OCURRIO UN ERROR.
                for ($i = 0; $i < count($_POST['vistas']); $i++) // RECORREMOS TODAS LAS VISTAS ASIGNADAS.
                {
                    $registrar = false;
                    if (isset($_POST['registrar'.($i+1)]))
                        $registrar = true;

                    $modificar = false;
                    if (isset($_POST['modificar'.($i+1)]))
                        $modificar = true;
                    
                    $estatus = false;
                    if (isset($_POST['estatus'.($i+1)]))
                        $estatus = true;

                    // GUARDAMOS LOS DATOS EN UNA VARIABLE.
                    $datos2 = [
                        'vista'     => $_POST['vistas'][$i],
                        'codigo'    => $datos['codigo'],
                        'registrar' => $registrar,
                        'modificar' => $modificar,
                        'act_desc'  => $estatus,
                        'eliminar'  => 1
                    ];

                    // REGISTRAMOS CADA UNO DE LOS MODULOS ASIGNADOS AL ROL.
                    $resultado2 = $objeto->registrarVistasDelRol($datos2);
                    if (!$resultado2) // SI ALGUNO FALLO GUARDA VERDADERO.
                    {
                        $errores = true;
                    }
                }

                if (!$errores) // VERIFICAMOS QUE ESTE EN FALSO, O SEA QUE NO HAYA ERRORES OCURRIDOS AL REGISTRAR TODAS LAS VISTAS.
                {
                    $objeto->guardarTransaccion(); // GUARDAMOS TODOS LOS REGISTROS.
                    $_SESSION['msj']['type'] = 'success';
                    $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se han guardado los cambios con exito.';
                }
                else
                {
                    $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                    // MANDAMOS UN MENSAJE 
                    $_SESSION['msj']['type'] = 'danger';
                    $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al modificar.';
                }
            }
            else
            {
                $objeto->calcelarTransaccion(); // DESHACEMOS TRANSACCION.
                // MANDAMOS UN MENSAJE 
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al modificar.';
            }
            
            $objeto->desconectar();
            header('Location: ../intranet/rol');
            break;

        case 'Eliminar':
            $datos = [
                'codigo'    => htmlspecialchars($_POST['codigo'])
            ];

            $objeto->conectar();
            $resultado = $objeto->eliminarRol($datos);
            if ($resultado)
            {
                // MANDAMOS UN MENSAJE
                $_SESSION['msj']['type'] = 'success';
                $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha eliminado con exito.';
            }
            else
            {
                // MANDAMOS UN MENSAJE
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe, hubo un error al eliminar.';
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