<?php
session_start();

// SI SE HA CONSULTADO UN USUARIO ANTERIORMENTE LO ELIMNA AL RECARGAR LA PAGINA.
if (isset($_SESSION['idusuario']))
    unset($_SESSION['idusuario']);

// SI ALGUIEN RESPONDIO CORRECTAMENTE SU PREGUNTA DE SEGURIDAD LO ELIMINA.
if (isset($_SESSION['respuesta']))
    unset($_SESSION['respuesta']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Restablecer contraseña | INCES</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="images/app/favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="styles/fontawesome/css/all.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            background: #F1EBEB;
        }
    </style>
</head>

<body>
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-sm-12 col-md-7 col-lg-5 align-self-center">
                <div class="bg-white rounded p-3">
                    <div class="text-center mb-4">
                        <a href="index"><img src="images/app/logo.svg" class="w-50"></a>
                    </div>

                    <h3 class="text-center text-secondary">Recuperar contraseña</h3>

                    <form>
                        <div id="caja_usuario">
                            <div class="form-group text-secondary mb-3">
                                <label for="usuario" class="small m-0">Introduzca su cédula:</label>
                                <input class="form-control form-control-sm" type="text" name="usuario" id="usuario" placeholder="Cédula" required>
                            </div>
                            
                            <button type="button" class="btn btn-info btn-block" id="confirmar_usuario">Enviar</button>
                            <a href="iniciar" class="small text-secondary mt-3">Regresar a inicio de sesión</a>
                        </div>

                        <div id="caja_respuesta" style="display: none">
                            <div class="form-group text-secondary mb-3">
                                <label for="respuesta" class="small m-0" id="pregunta"></label>
                                <input class="form-control form-control-sm" type="text" name="respuesta" id='respuesta' placeholder='Respuesta'>
                            </div>

                            <button type='button' class="btn btn-info btn-block" id='comprobar_pregunta'>Comprobar</button>
                        </div>

                        <div id="caja_contrasena" style="display: none">
                            <div class="form-group text-secondary mb-3">
                                <label for="contrasena1" class="small m-0">Introduzca la contraseña:</label>
                                <input class="form-control form-control-sm" type="password" name="contrasena1" id="contrasena1" placeholder='Nueva Contraseña'>
                            </div>

                            <div class="form-group text-secondary mb-3">
                                <label for="contrasena2" class="small m-0">Repita la contraseña:</label>
                                <input class="form-control form-control-sm" type="password" name="contrasena2" id='contrasena2' placeholder='Repita la contraseña'>
                            </div>

                            <button type='button' class="btn btn-info btn-block" id='guardar_contrasena'>Guardar contraseña</button>
                        </div>
                    </form>
                </div>

                <div id="caja_mensaje" style="display: none;" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <p class="d-inline-block mb-0"></p>

                    <button type="button" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="javascripts/jquery/jquery-3.4.1.min.js"></script>
    <script src="javascripts/popper/popper.min.js"></script>
    <script src="javascripts/bootstrap/bootstrap.min.js"></script>
    <script src="javascripts/sweetalert/sweetalert.min.js"></script>
    <script src="javascripts/recuperar.js"></script>
</body>
</html>