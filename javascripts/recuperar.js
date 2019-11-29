$(function ()
{
	$('#usuario').click(ocultarMensaje); // SI HAY UN MENSAJE DE ERROR Y SE LE DA CLICK AL CAMPO, EL MENSAJE SE ESCONDE.
	$("#confirmar_usuario").click(function() // CUANDO LE DE CLICK AL BOTON BUSCAR USUARIO.
	{
		if ($("#usuario").val() != "") // SE VERIFICA QUE EL CAMPO NO ESTE VACIO.
		{
			// REALIZAMOS LA CONSULTA ATRAVES DE AJAX
			$.ajax({
				url: "controllers/c_restablecer.php",
				type: "POST",
				data:
				{
					opcion	: 'Confirmar usuario',
					usuario : $("#usuario").val()
				},
				// SI LA CONSULTA FUE EXISTOSA PROCEDE A MOSTRAR LOS DATOS.
				success: function (respuesta)
				{
					// SI NO EXISTE EL USUARIO SE MUESTRA UN MENSAJE DE ERROR.
					if (respuesta == 'No existe')
					{
						$('#caja_mensaje').show(100);
						$('#caja_mensaje p').html('<i class="fas fa-user-times mr-2"></i>El usuario no existe.');
					}
					else if (respuesta == 'Cancelada')
					{
						$('#caja_mensaje').show(100);
						$('#caja_mensaje p').html('<i class="fas fa-ban mr-2"></i>Su cuenta fue cancelada, hable con el administrador.');
					}
					// DE LO CONTRARIO BUSCAMOS MOSTRAR LA PREGUNTA DE SEGURIDAD.
					else
					{
						// USAMOS UN TRY CATCH POR SI HAY ERRORES A DECODIFICAR EL MENSAJE JSON.
						try
						{
							var datos = JSON.parse(respuesta);
							$('#caja_usuario').hide(500);
							$('#caja_respuesta').show(500);
							$('#pregunta').html(datos.pregunta_seguridad);
						}
						// SI HAY ALGUN ERROR, PROCEDEMOS A MOSTRAR EL ERROR.
						// POR LO GENERAL ES UN ERROR PROVENIENTE DE PHP CAUSADA EN LA CONSULTA.
						catch (error)
						{
							console.log(respuesta);
						}
					}
				},
				// SI HUBO UN ERROR, MOSTRAMOS UN MENSAJE DE ERROR.
				error: function ()
				{
					swal({
						title	: 'Error de conexión',
						text	: 'No se pudo hacer la consulta, revise su conexión he intente nuevamente.',
						icon	: 'error',
						timer	: 4000
					});
				},
				timeout: 15000
			});
		}
		// SI EL CAMPO ESTA VACIO SE LE MUESTRA UN MENSAJE DE ERROR.
		else
		{
			$('#caja_mensaje').show(100);
			$('#caja_mensaje p').html('<i class="fas fa-info mr-2"></i>Introduzca su usuario, el campo no puede estar vacío.');
		}
	});

	$('#respuesta').click(ocultarMensaje); // SI HAY UN MENSAJE DE ERROR Y SE LE DA CLICK AL CAMPO, EL MENSAJE SE ESCONDE.
	$("#comprobar_pregunta").click(function()
	{
		if ($("#respuesta").val() != "") // SE VERIFICA QUE EL CAMPO NO ESTE VACIO.
		{
			// REALIZAMOS LA CONSULTA ATRAVES DE AJAX
			$.ajax({
				url: "controllers/c_restablecer.php",
				type: "POST",
				data:
				{
					opcion		: 'Confirmar pregunta',
					respuesta	: $("#respuesta").val()
				},
				// SI LA CONSULTA FUE EXISTOSA PROCEDE A MOSTRAR LOS DATOS.
				success: function (respuesta)
				{
					if (respuesta == 'Consulte')
					{
						$('#caja_mensaje').show(100);
						$('#caja_mensaje p').html('<i class="fas fa-times mr-2"></i>Debe consultar primero su usuario.');
					}
					else if (respuesta)
					{
						$('#caja_respuesta').hide(500);
						$('#caja_contrasena').show(500);
					}
					// DE LO CONTRARIO BUSCAMOS MOSTRAR LA PREGUNTA DE SEGURIDAD.
					else
					{
						$('#caja_mensaje').show(100);
						$('#caja_mensaje p').html('<i class="fas fa-times mr-2"></i>Respuesta incorrecta.');
					}
				},
				// SI HUBO UN ERROR, MOSTRAMOS UN MENSAJE DE ERROR.
				error: function ()
				{
					swal({
						title	: 'Error de conexión',
						text	: 'No se pudo hacer la consulta, revise su conexión he intente nuevamente.',
						icon	: 'error',
						timer	: 4000
					});
				},
				timeout: 15000
			});
		}
		// SI EL CAMPO ESTA VACIO SE LE MUESTRA UN MENSAJE DE ERROR.
		else
		{
			$('#caja_mensaje').show(100);
			$('#caja_mensaje p').html('<i class="fas fa-info mr-2"></i>Introduzca la respuesta, el campo no puede estar vacío.');
		}
	});

	$('#contrasena1').click(ocultarMensaje); // SI HAY UN MENSAJE DE ERROR Y SE LE DA CLICK AL CAMPO, EL MENSAJE SE ESCONDE.
	$('#contrasena2').click(ocultarMensaje); // SI HAY UN MENSAJE DE ERROR Y SE LE DA CLICK AL CAMPO, EL MENSAJE SE ESCONDE.
	$("#guardar_contrasena").click(function()
	{
		if ($('#contrasena1').val().length > 5) // SE VERIFICA QUE EL CAMPO NO ESTE VACIO Y QUE TENGA AL MENOS 6 CARACTERES.
		{
			if ($('#contrasena1').val() == $('#contrasena2').val()) // SE VERIFICA QUE LAS CONTRASEÑAS DEBEN SER IGUALES.
			{
				// REALIZAMOS LA CONSULTA ATRAVES DE AJAX
				$.ajax({
					url: "controllers/c_restablecer.php",
					type: "POST",
					data:
					{
						opcion		: 'Nueva contrasena',
						contrasena	: $("#contrasena1").val()
					},
					// SI LA CONSULTA FUE EXISTOSA PROCEDE A MOSTRAR LOS DATOS.
					success: function (respuesta)
					{
						if (respuesta == 'Consulte')
						{
							$('#caja_mensaje').show(100);
							$('#caja_mensaje p').html('<i class="fas fa-times mr-2"></i>Debe consultar primero su usuario.');
						}
						else if (respuesta == 'Responda')
						{
							$('#caja_mensaje').show(100);
							$('#caja_mensaje p').html('<i class="fas fa-times mr-2"></i>Debe responder la pregunta de seguridad.');
						}
						else if (respuesta)
						{
							location.href = 'iniciar.php';
						}
						// DE LO CONTRARIO BUSCAMOS MOSTRAR LA PREGUNTA DE SEGURIDAD.
						else
						{
							$('#caja_mensaje').show(100);
							$('#caja_mensaje p').html('<i class="fas fa-times mr-2"></i>Hubo un error al actualizar, puede ser un error interno o su cuenta fue cancelada por el administrador.');

							setTimeout(() => {
								location.reload();
							}, 4000);
						}
					},
					// SI HUBO UN ERROR, MOSTRAMOS UN MENSAJE DE ERROR.
					error: function ()
					{
						swal({
							title	: 'Error de conexión',
							text	: 'No se pudo hacer la consulta, revise su conexión he intente nuevamente.',
							icon	: 'error',
							timer	: 4000
						});
					},
					timeout: 15000
				});
			}
			// SI EL CAMPO ESTA VACIO SE LE MUESTRA UN MENSAJE DE ERROR.
			else
			{
				$('#caja_mensaje').show(100);
				$('#caja_mensaje p').html('<i class="fas fa-info mr-2"></i>Repita la contraseña, debe confirmar la contraseña para evitar errores.');
			}
		}
		// SI EL CAMPO ESTA VACIO SE LE MUESTRA UN MENSAJE DE ERROR.
		else
		{
			$('#caja_mensaje').show(100);
			$('#caja_mensaje p').html('<i class="fas fa-info mr-2"></i>Introduzca la nueva contraseña, el campo no puede estar vacío y debe tener al menos 6 caracteres.');
		}
	});

	$('.close').click(ocultarMensaje);
	function ocultarMensaje ()
	{
		$('#caja_mensaje').hide(100);
		$('#caja_mensaje p').html('');
	}
});