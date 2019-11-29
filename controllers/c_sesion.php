<?php
session_start();

// VERIFICAMOS SI SE QUIERE INICIAR SESION.
if($_POST['entrar'])
{
	require_once('../models/m_sesion.php');
	$objeto = new model_sesion;

	// GUARDAMOS LOS DATOS EN UN ARREGLO.
	$datos = [
		'usuario'	=> htmlspecialchars($_POST['usuario']),
		'contrasena'=> htmlspecialchars($_POST['contrasena'])
	];

	if(!isset($_SESSION['intentos'])) // VERIFICAMOS SI HAY INTENTOS ANTERIORES Y SI NO, LAS CREA.
		$_SESSION['intentos'] = 0;

	$objeto->conectar(); // REALIZAMOS LA CONEXION A LA BASE DE DATOS.
	$consulta = $objeto->consultarUsuario($datos); // CONSULTAMOS EL USUARIO.
	if ($consulta) // VERIFICAMOS QUE RETORNE DATOS.
	{
		if ($consulta['estatus'] == 'A') // VERIFICAMOS QUE EL USUARIO ESTE ACTIVO.
		{
			if (password_verify($datos['contrasena'],$consulta['contrasena'])) // VERIFICAMOS SI LA CONTRASEÑA ES CORRECTA.
			{
				$objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.
				unset($_SESSION['intentos']); // SI TODO ESTA CORRECTO ELIMINAMOS LOS INTENTOS.

				// GUARDAMOS LOS DATOS DEL USUARIO.
				$_SESSION['sesion']	= true;
				$_SESSION['usuario'] = $consulta;

				header('Location: ../intranet');
			}
			else // SI NO ES CORRECTA.
			{
				if ($_SESSION['intentos'] < 2) // VERIFICAMOS SI NO SOBRE PASA LOS INTENTOS FALLIDOS.
				{
					$objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.
					$_SESSION['intentos']++; // AUMENTAMOS LOS INTENTOS FALLIDOS.

					// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
					$_SESSION['msj']['type'] = 'warning';
					$_SESSION['msj']['text'] = '<i class="fas fa-exclamation-triangle mr-2"></i>La contraseña que ingresó es incorrecta. Tiene '.(3 - $_SESSION['intentos']).' intento antes de ser bloqueada.';
					header('Location:../iniciar');
				}
				else // SI LOS INTENTOS NO HAN SOBREPASADO LOS INTENTOS.
				{
					unset($_SESSION['intentos']); // ELIMINAMOS LOS INTENTOS.

					$objeto->bloquearUsuario($datos); // SE BLOQUEA EL USUARIO POR INTENTOS FALLIDOS.
					$objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.
					
					// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
					$_SESSION['msj']['type'] = 'danger';
					$_SESSION['msj']['text'] = '<i class="fas fa-user-lock mr-2"></i>Ha excedido el número de intentos, su cuenta fue bloqueada.';
					header('Location: ../iniciar');
				}
			}
		}
		else if ($consulta['estatus'] == 'B') // USUARIO BLOQUEADO POR INTENTOS FALLIDOS.
		{
			$objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.

			// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
			$_SESSION['msj']['type'] = 'danger';
			$_SESSION['msj']['text'] = '<i class="fas fa-user-lock mr-2"></i>Su cuenta está bloqueada, hable con el administrador o desbloqueé restableciendo contraseña.';
			header('Location: ../iniciar');
		}
		else if ($consulta['estatus'] == 'I') // USUARIO DESACTIVADO POR EL ADMINISTRADOR.
		{
			$objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.

			// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
			$_SESSION['msj']['type'] = 'danger';
			$_SESSION['msj']['text'] = '<i class="fas fa-ban mr-2"></i>Su cuenta fue cancelada, hable con el administrador.';
			header('Location: ../iniciar');
		}
	}
	// SI NO TRAE DATOS, NO EXISTE EL USUARIO.
	else
	{
		$objeto->desconectar(); // ELIMINARMOS LA CONEXION A LA BASE DE DATOS.

		// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
		$_SESSION['msj']['type'] = 'danger';
		$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>El usuario y/o contraseña que ingresó son incorrectas.';
		header('Location: ../iniciar');
	}
}
// VERIFICAMOS SI SE QUIERE CERRAR SESION.
else if($_POST['salir'])
{
	session_destroy();
	session_start();

	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'success';
	$_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha cerrado sesión exitosamente.';
	header('Location: ../iniciar');
}
// SI INTENTA ENTRAR AL CONTROLADOR POR RAZONES AJENAS MARCA ERROR.
else
{
	// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
	$_SESSION['msj']['type'] = 'danger';
	$_SESSION['msj']['text'] = '<i class="fas fa-times mr-2"></i>Discúlpe ha habido un error.';
	header('Location: ../iniciar');
}