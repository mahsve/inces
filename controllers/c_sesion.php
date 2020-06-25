<?php 
session_start();
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.

if ($_POST['entrar']) {
	require_once('../models/m_sesion.php');
	$objeto = new model_sesion;

	require_once('../library/tool.php');
	$objeto->conectar(); // REALIZAMOS LA CONEXION A LA BASE DE DATOS.
	$datos_usuario = $objeto->consultarUsuario($_POST); // CONSULTAMOS EL USUARIO.
	if ($datos_usuario) { // SI LO ENCUETRA PROCEDE A VERIFICAR SUS DATOS.
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		// DATOS PARA EL REGISTRO DE LA BITACORA
		$datos_bitacora = [
			'usuario'	=> $_POST['usuario'],
			'fecha'		=> date('Y-m-d', time()),
			'hora'		=> date('H:i:s', time()),
			'navegador'	=> tipoNavegador($user_agent),
			'operacion'	=> ''
		];

		// USUARIO ACTIVO O SIN ACTUALIZAR DATOS Y CONTRASEÑA
		if ($datos_usuario['estatus_usuario'] == 'A' OR $datos_usuario['estatus_usuario'] == 'E') {
			if (password_verify($_POST['contrasena'], $datos_usuario['contrasena'])) {
				$_POST['intentos'] = 0; // SUMAMOS UN INTENTO FALLIDO.
				$update = $objeto->actualizarIntentos($_POST);

				$datos_bitacora['operacion'] = 'Inicio de sesión exitoso.';
				$update = $objeto->registrarBitacora($datos_bitacora); // REGISTRAR EN LA BITACORA LA OPERACION
				while (!$update) { $update = $registrarBitacora->registrarBitacora($datos_bitacora); } // SI NO ACTUALIZO LO HACE HASTA QUE LO HAGA
				$objeto->desconectar(); // DECONECTAMOS DEL SERVIDOR

				// GUARDAMOS LOS DATOS DEL USUARIO.
				$_SESSION['usuario'] 	= $datos_usuario;
				$_SESSION['sesion']		= true;
				if 		($datos_usuario['estatus_usuario'] == 'E') { $_SESSION['actualizar_contrasena'] = true; }
				else if ($datos_usuario['estatus_usuario'] == 'A') { var_dump($datos_usuario['historial_contrasenas']); }
				header('Location: ../intranet');
			} else {
				$_POST['intentos'] = intval($datos_usuario['intentos']);
				if ($_POST['intentos'] < 2) {
					$_POST['intentos']++; // SUMAMOS UN INTENTO FALLIDO.
					$update = $objeto->actualizarIntentos($_POST);
					while (!$update) { $update = $objeto->actualizarIntentos($_POST); } // ACTUALIZAMOS EN LA BASE DE DATOS LOS INTENTOS FALLIDOS
					
					$datos_bitacora['operacion'] = 'Inicio de sesión fallido.';
					$update = $objeto->registrarBitacora($datos_bitacora); // REGISTRAR EN LA BITACORA LA OPERACION
					while (!$update) { $update = $registrarBitacora->registrarBitacora($datos_bitacora); } // SI NO ACTUALIZO LO HACE HASTA QUE LO HAGA
					$objeto->desconectar(); // DECONECTAMOS DEL SERVIDOR

					// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
					$_SESSION['msj']['type'] = 'warning';
					$_SESSION['msj']['text'] = '<i class="fas fa-exclamation-triangle mr-2"></i>La contraseña que ingresó es incorrecta. Tiene '.(3 - $_POST['intentos']).' intento antes de ser bloqueado.';
					header('Location:../iniciar');
				} else {
					$_POST['intentos'] = 0; // ESTABLECEMOS INTENTOS EN 0
					$update = $objeto->bloquearUsuario($_POST); // SE BLOQUEA EL USUARIO POR INTENTOS FALLIDOS.
					while (!$update) { $update = $objeto->bloquearUsuario($_POST); } // SI NO ACTUALIZO LO HACE HASTA QUE LO HAGA
					
					$datos_bitacora['operacion'] = 'Usuario bloqueado por intentos fallidos.';
					$update = $objeto->registrarBitacora($datos_bitacora); // REGISTRAR EN LA BITACORA LA OPERACION
					while (!$update) { $update = $objeto->registrarBitacora($datos_bitacora); } // SI NO ACTUALIZO LO HACE HASTA QUE LO HAGA
					$objeto->desconectar(); // DECONECTAMOS DEL SERVIDOR

					// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
					$_SESSION['msj']['type'] = 'danger';
					$_SESSION['msj']['text'] = '<i class="fas fa-user-lock mr-2"></i>Ha excedido el número de intentos, su cuenta fue bloqueada.';
					header('Location: ../iniciar');
				}
			}
		// USUARIO BLOQUEADO POR INTENTOS FALLIDOS.
		} else if ($datos_usuario['estatus_usuario'] == 'B')  {
			$datos_bitacora['operacion'] = 'Intento iniciar sesión con usuario bloqueado.';
			$update = $objeto->registrarBitacora($datos_bitacora); // REGISTRAR EN LA BITACORA LA OPERACION
			while (!$update) { $update = $registrarBitacora->registrarBitacora($datos_bitacora); } // SI NO ACTUALIZO LO HACE HASTA QUE LO HAGA
			$objeto->desconectar(); // DECONECTAMOS DEL SERVIDOR

			// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
			$_SESSION['msj']['type'] = 'danger';
			$_SESSION['msj']['text'] = '<i class="fas fa-user-lock mr-2"></i>Su cuenta está bloqueada, hable con el administrador o desbloqueé restableciendo contraseña.';
			header('Location: ../iniciar');
		// USUARIO DESACTIVADO POR EL ADMINISTRADOR.
		} else if ($datos_usuario['estatus_usuario'] == 'C') {
			$datos_bitacora['operacion'] = 'Intento iniciar sesión con usuario cancelado.';
			$update = $objeto->registrarBitacora($datos_bitacora); // REGISTRAR EN LA BITACORA LA OPERACION
			while (!$update) { $update = $registrarBitacora->registrarBitacora($datos_bitacora); } // SI NO ACTUALIZO LO HACE HASTA QUE LO HAGA
			$objeto->desconectar(); // DECONECTAMOS DEL SERVIDOR

			// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
			$_SESSION['msj']['type'] = 'danger';
			$_SESSION['msj']['text'] = '<i class="fas fa-ban mr-2"></i>Su cuenta fue cancelada, hable con el administrador.';
			header('Location: ../iniciar');
		}
	} else {
		$objeto->desconectar(); // DECONECTAMOS DEL SERVIDOR
		// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
		$_SESSION['msj']['type'] = 'danger';
		$_SESSION['msj']['text'] = '<i class="fas fa-times"></i> <span>El usuario y/o contraseña que ingresó son incorrectas.</span>';
		header('Location: ../iniciar');
	}
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
} else {
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times"></i> <span>Discúlpe ha habido un error.</span>';
	header('Location: ../iniciar');
}