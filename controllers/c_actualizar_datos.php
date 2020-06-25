<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.

if ($_POST['opcion']) {
    require_once('../models/m_actualizar_datos.php');
    $objeto = new model_actualizar_datos;
    
    require_once('../library/tool.php');
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // DATOS PARA EL REGISTRO DE LA BITACORA
    $datos_bitacora = [
        'usuario'	=> $_POST['usuario'],
        'fecha'		=> date('Y-m-d', time()),
        'hora'		=> date('H:i:s', time()),
        'navegador'	=> tipoNavegador($user_agent),
        'operacion'	=> ''
    ];

    switch ($_POST['opcion']) {
        case 'Actualizar contrasena':
            $objeto->conectar();
            $objeto->nuevaTransaccion();
            // OBTENEMOS EL NOMBRE DE USUARIO DE LA PERSONA PARA VERIFICAR SUS ULTIMAS CONTRASEÑAS.
            $_POST['usuario']   = $_SESSION['usuario']['usuario'];// GUARDAMOS LOS DATOS EN UN ARREGLO Y CAMBIAMOS LA NUEVA CONTRASEÑA DEL USUARIO CONSULTADO.
            $_POST['contrasena']= password_hash($_POST['contrasena_1'], PASSWORD_DEFAULT);
            $historial          = $objeto->consultarContrasenas($_POST); // CONSULTAMOS CONTRASEÑAS.

            // VERIFICAMOS QUE NO SE ENCUENTRE LA CONTRASEÑA ENTRE LAS ULTIMAS 10 CONTRASEÑAS INGRESADAS.
            $coincidencias = false;
            if ($historial) {
                for ($var = 0; $var < count($historial); $var++) {
                    if (password_verify($_POST['contrasena_1'], $historial[$var]['contrasena'])) { $coincidencias = true; }
                }
            }

            // SI NO ENCONTRO NINGUNA CONTRASEÑA IGUAL PROD
            if (!$coincidencias) {
                // if () {
                    
                // }
            } else {
                echo 'Modificación fallida: Por seguridad no puede usar la misma contraseña';
                $objeto->calcelarTransaccion();
            }
            $objeto->desconectar();
        break;
    }
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../intranet/dashboard');
}