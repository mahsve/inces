<?php
session_start();

// VERIFICAMOS SI EXISTE LA VARIABLE QUE DETERMINE LA TAREA.
if ($_POST['opcion'])
{
    require_once('../models/m_recuperar.php');
	$objeto = new model_recuperar;

    switch ($_POST['opcion']) {
        case 'Confirmar usuario':
            // GUARDAMOS LOS DATOS EN UN ARREGLO.
            $datos = [
                'usuario' => htmlspecialchars($_POST['usuario'])
            ];

            $objeto->conectar(); // REALIZAMOS LA CONEXION A LA BASE DE DATOS.
            $consulta = $objeto->consultarPregunta($datos); // CONSULTAMOS EL USUARIO Y LA PREGUNTA DE SEGURIDAD.
            if ($consulta) // VERIFICAMOS QUE RETORNE DATOS.
            {
                // VERIFICAMOS QUE NO SEA UNA CUENTA CANCELADA POR EL ADMINISTRADOR.
                // ESTO PARA EVITAR QUE AL ACTUALIZAR CONTRASEÑA SE REACTIVE SIN CONSENTIMIENTO DEL ADMINISTRADOR
                // YA QUE TAMBIEN ACTUALIZAR EL ESTATUS A ACTIVO SI FUE BLOQUEADO POR INTENTOS FALLIDOS.
                if ($consulta['estatus'] == 'A' OR $consulta['estatus'] == 'B')
                {
                    $_SESSION['idusuario'] = $datos['usuario']; // GUARDAMOS EL USUARIO.
                    echo json_encode($consulta); // IMPRIMIMOS EN FORMATO JSON.
                }
                // SI LA CUENTA FUE BLOQUEADA POR EL ADMINISTRADOR ENVIA UN MENSAJE.
                else
                {
                    echo 'Cancelada'; // IMPRIMIMOS UN MENSAJE DE CUENTA CANCELADA.
                }
            }
            // SI NO TRAE DATOS, NO EXISTE EL USUARIO.
            else
            {
                echo 'No existe'; // IMPRIMIMOS UN MENSAJE DE ERROR.
            }
            $objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.
            break;

        case 'Confirmar pregunta':
            // VERIFICAMOS PRIMERO QUE HAYA CONSULTADOS SU USUARIO.
            // YA QUE SE GUARDA EN UNA VARIABLE DE SESION.
            if (isset($_SESSION['idusuario']))
            {
                // GUARDAMOS LOS DATOS EN UN ARREGLO PARA TRAER LA PREGUNTA DEL USUARIO CONSULTADO.
                $datos = [
                    'usuario'   => $_SESSION['idusuario'],
                    'respuesta' => $_POST['respuesta']
                ];

                $objeto->conectar(); // REALIZAMOS LA CONEXION A LA BASE DE DATOS.
                $consulta = $objeto->consultarRespuesta($datos); // CONSULTAMOS LA RESPUESTA DE SEGURIDAD.
                if (password_verify($datos['respuesta'], $consulta['respuesta_seguridad'])) // VERIFICAMOS QUE LA RESPUESTA SEA CORRECTA.
                {
                    $_SESSION['respuesta'] = true;
                    echo true; // MOSTRAMOS TRUE SI ES CORRECTA.
                }
                else
                {
                    echo false; // MOSTRAMOS FALSE SI ES INCORRECTA.
                }

                $objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.
            }
            // SI NO HA CONSULTADO SU USUARIO MANDA UN MENSAJE DE ERROR.
            else
            {
                echo 'Consulte';
            }
            break;
        
        case 'Nueva contrasena':
            // VERIFICAMOS PRIMERO QUE HAYA CONSULTADOS SU USUARIO.
            // YA QUE SE GUARDA EN UNA VARIABLE DE SESION.
            if (isset($_SESSION['idusuario']))
            {
                // VERIFICAMOS QUE SI HAYA RESPONDIDO LA PREGUNTA DE SEGURIDAD.
                if (isset($_SESSION['respuesta']))
                {
                    // GUARDAMOS LOS DATOS EN UN ARREGLO Y CAMBIAMOS LA NUEVA CONTRASEÑA DEL USUARIO CONSULTADO.
                    $datos = [
                        'usuario'       => $_SESSION['idusuario'],
                        'contrasena'    => password_hash($_POST['contrasena'], PASSWORD_DEFAULT)
                    ];
                    
                    $objeto->conectar(); // REALIZAMOS LA CONEXION A LA BASE DE DATOS.
                    $consulta = $objeto->guardarContrasena($datos); // PROCEDEMOS A ACTUALIZAR.
                    if ($consulta) // VERIFICAMOS QUE SI HAYA ACTUALIZADO.
                    {
                        // MANDAMOS UN MENSAJE
                        $_SESSION['msj']['type'] = 'success';
                        $_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Contraseña actualizada';
            
                        echo true; // MOSTRAMOS TRUE SI ES CORRECTA.
                    }
                    else
                    {
                        echo false; // MOSTRAMOS FALSE SI ES INCORRECTA.
                    }
                    $objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.
                }
                // SI NO HA RESPONDIDO LA PREGUNTA MANDA UN MENSAJE DE ERROR.
                else
                {
                    echo 'Responda';
                }
            }
            // SI NO HA CONSULTADO SU USUARIO MANDA UN MENSAJE DE ERROR.
            else
            {
                echo 'Consulte';
            }
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