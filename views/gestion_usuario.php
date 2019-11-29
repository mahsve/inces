<?php
include_once('models/m_empresa.php');
$objeto = new model_empresa();
$objeto->conectar();
$estados = $objeto->consultarEstados();
$actividades = $objeto->consultarActividades();
$objeto->desconectar();
?>

<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
    <h4 class="text-uppercase font-weight-normal mb-0"><?php if (!isset($_GET['rif'])) { echo 'Registrar'; } ?></h4>

    <a href="empresa" class="btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">volver</span></a>
</div>

<form class="formulario" action="../controllers/c_empresa.php" method="POST" name="formulario">
    <input type="hidden" name="opcion" value="Registrar"/>

    <h5 class="font-weight-normal">Datos personales</h5>
    <div class="form-row">
        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="nacionalidad" class="small m-0">Nacionalidad <span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="nacionalidad" id="nacionalidad" required>
                    <option value="">Elija una opción</option>
                    <option value="V">Venezolano</option>
                    <option value="E">Extranjero</option>
                </select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="cedula" id="cedula" maxlength="11" placeholder="Ingrese la cédula" required/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="nombre_1" id="nombre_1" maxlength="11" placeholder="Ingrese el nombre" required/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="nombre_2" class="small m-0">Segundo nombre</label>
                <input type="text" class="form-control form-control-sm" name="nombre_2" id="nombre_2" maxlength="11" placeholder="Ingrese el nombre"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="apellido_1" id="apellido_1" maxlength="11" placeholder="Ingrese el apellido" required/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                <input type="text" class="form-control form-control-sm" name="apellido_2" id="apellido_2" maxlength="11" placeholder="Ingrese el apellido"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="sexo" id="sexo" required>
                    <option value="">Elija una opción</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="estado_c" class="small m-0">Estado <span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="estado_c" id="estado_c" required>
                    <?php if ($estados) {?>
                        <option value="">Elija una opción</option>
                        <?php foreach ($estados as $datos) { ?>
                            <option value="<?php echo $datos['codigo']; ?>"><?php echo $datos['nombre']; ?></option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="">No hay estados</option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group has-warning mb-2">
                <label for="ciudad_c" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                <select class="custom-select custom-select-sm" name="ciudad_c" id="ciudad_c" required>
                    <option value="">Elija un estado</option>
                </select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="telefono" class="small m-0">Telefono <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="telefono" id="telefono" maxlength="11" placeholder="Ingrese el telefono"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group has-warning mb-2">
                <label for="correo" class="small m-0">Correo</label>
                <input type="email" class="form-control form-control-sm" name="correo" id="correo" maxlength="60" placeholder="Ingrese el correo"/>
            </div>
        </div>
    </div>

    <div class="pt-2 text-center">
        <button class="btn btn-sm btn-info w-25"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
    </div>
</form>

<script>
    $(function () {
        $('#estado').change(buscarCiudades);
        $('#estado_c').change(buscarCiudades);
        function buscarCiudades()
        {
            var valor;
            var ciudad = '';
            if ($(this).attr('id') == 'estado')
            {
                valor = $('#estado').val();
                ciudad = '#ciudad';
            }
            else
            {
                valor = $('#estado_c').val();
                ciudad = '#ciudad_c';
            }

            if (valor != '')
            {
                // REALIZAMOS LA CONSULTA ATRAVES DE AJAX
				$.ajax({
					url: "../controllers/c_empresa.php",
					type: "POST",
					data:
					{
						opcion  : 'Consultar ciudad',
						estado  : valor
					},
					// SI LA CONSULTA FUE EXISTOSA PROCEDE A MOSTRAR LOS DATOS.
					success: function (respuesta)
					{
                        console.log(respuesta);
						if (respuesta == 'No hay')
						{
							$(ciudad).html('<option value="">No hay ciudades</option>');
						}
						else
						{
							// USAMOS UN TRY CATCH POR SI HAY ERRORES A DECODIFICAR EL MENSAJE JSON.
                            try
                            {
                                var datos = JSON.parse(respuesta);
                                var contenido = '<option value="">Elija una opción</option>';
                                for (var i in datos)
                                {
                                    contenido += '<option value="'+datos[i].codigo+'">'+datos[i].nombre+'</option>';
                                }
                                $(ciudad).html(contenido);
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
            else
            {
                $(ciudad).html('<option value="">Elija un estado</option>');
            }
        }
    });
</script>