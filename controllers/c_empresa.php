<?php 
session_start();
if ($_POST['opcion'])
{
    require_once('../models/m_empresa.php');
    $objeto = new model_empresa;
    
    switch ($_POST['opcion']) {
        case 'Consultar ciudad':
            $datos = [
                'estado' => htmlspecialchars($_POST['estado'])
            ];

            $objeto->conectar();
            $ciudad = $objeto->consultarCiudades($datos);
            $objeto->desconectar();

            if ($ciudad)
            {
                echo json_encode($ciudad);
            }
            else
            {
                echo 'No hay';
            }
            break;
        
        case 'Registrar';
            $datos = [
                'rif'               => htmlspecialchars($_POST['rif']),
                'nil'               => htmlspecialchars($_POST['nil']),
                'razon_social'      => htmlspecialchars($_POST['razon_social']),
                'actividad_economica'=> htmlspecialchars($_POST['actividad_economica']),
                'codigo_aportante'  => htmlspecialchars($_POST['codigo_aportante']),
                'telefono_1'        => htmlspecialchars($_POST['telefono_1']),
                'telefono_2'        => htmlspecialchars($_POST['telefono_2']),
                'ciudad'            => htmlspecialchars($_POST['ciudad']),
                'direccion'         => htmlspecialchars($_POST['direccion']),
                'nacionalidad'      => htmlspecialchars($_POST['nacionalidad']),
                'cedula'            => htmlspecialchars($_POST['cedula']),
                'nombre_1'          => htmlspecialchars($_POST['nombre_1']),
                'nombre_2'          => htmlspecialchars($_POST['nombre_2']),
                'apellido_1'        => htmlspecialchars($_POST['apellido_1']),
                'apellido_2'        => htmlspecialchars($_POST['apellido_2']),
                'sexo'              => htmlspecialchars($_POST['sexo']),
                'ciudad_c'          => htmlspecialchars($_POST['ciudad_c']),
                'telefono'          => htmlspecialchars($_POST['telefono']),
                'correo'            => htmlspecialchars($_POST['correo'])
            ];

            $objeto->conectar();
            $objeto->nuevaTransaccion();
            $resultado = $objeto->registrarPersonaContacto($datos);
            if ($resultado)
            {
                $resultado = $objeto->registrarEmpresa($datos);
                if ($resultado)
                {
                    // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                    $_SESSION['msj']['type'] = 'success';
                    $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha registrado con exito.';

                    $objeto->guardarTransaccion();
                }
                else
                {
                    // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                    $_SESSION['msj']['type'] = 'danger';
                    $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe no se pudo registrar por un error interno.';

                    $objeto->calcelarTransaccion();
                }
            }
            else
            {
                // MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
                $_SESSION['msj']['type'] = 'danger';
                $_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe no se pudo registrar por un error interno.';

                $objeto->calcelarTransaccion();
            }
            $objeto->desconectar();
            header('Location: ../intranet/empresa');
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