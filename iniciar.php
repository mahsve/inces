<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Iniciar sesión | INCES</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="images/app/favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="styles/fontawesome/css/all.css">
    <style> html, body { height: 100%; } body { background: #F1EBEB; } </style>
</head>

<body>
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-sm-12 col-md-7 col-lg-5 align-self-center">
                <div class="bg-white rounded p-3">
                    <div class="text-center mb-4">
                        <a href="index"><img src="images/app/logo.svg" class="w-50"></a>
                    </div>

                    <h3 class="text-center text-secondary">Iniciar sesión</h3>
                    
                    <form action="controllers/c_sesion.php" method="POST">  
                        <input type="hidden" name="entrar" value="Entrar"/>

                        <div class="form-group text-secondary mb-2">
                            <label for="usuario" class="small m-0">Introduzca su cédula:</label>
                            <input class="form-control form-control-sm" type="text" name="usuario" id="usuario" placeholder="Cédula" required>
                        </div>

                        <div class="form-group text-secondary mb-3">
                            <label for="contrasena" class="small m-0">Introduzca su contraseña:</label>
                            <input class="form-control form-control-sm" type="password" name="contrasena" placeholder="Contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-info btn-block"><i class="fas fa-sign-in-alt mr-2"></i>Entrar</button>
                    </form>

                    <a href="restablecer" class="small text-secondary mt-3">¡Olvide mi contraseña!</a>
                </div>

                <?php if (isset($_SESSION['msj'])) { ?>
                    <div class="alert alert-<?php echo $_SESSION['msj']['type']; ?> alert-dismissible fade show mt-3" role="alert">
                        <?php echo $_SESSION['msj']['text']; ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php unset($_SESSION['msj']); } ?>
            </div>
        </div>
    </div>

    <script src="javascripts/jquery/jquery-3.4.1.min.js"></script>
    <script src="javascripts/popper/popper.min.js"></script>
    <script src="javascripts/bootstrap/bootstrap.min.js"></script>
    <script>setTimeout(() => { $('.alert').hide(100); }, 4500);</script>
</body>
</html>