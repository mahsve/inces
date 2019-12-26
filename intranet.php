<?php
include_once 'config/config.php';               // INCLUIMOS EL ARCHIVO DE CONFIGURACION DE LA URL.
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.

session_start();                                // DECLARAMOS LA PALABRA RESERVADA SESSION_START PARA TRABAJAR CON SESIONES.
if (isset($_SESSION['sesion'])) {               // VERIFICAMOS SI HAY ALGUNA SESION ACTIVA.
    if (isset($_GET['data'])) {                 // VERIFICAMOS SI EXISTE LA VARIABLE VISTA.
        $dataGET = explode('/',$_GET['data']);  // SI EXISTE PROCESAMOS EL STRING DIVIDIENDOLO EN UN ARREGLO.

        $vista = $dataGET[0];                   // GUARDAMOS EL DATO EN LA POSICION UNO, YA QUE SERA EL NOMBRE DEL ARCHIVO.
        if (is_file('views/'.$vista.'.php')) {  // VERIFICAMOS SI EXISTE EL ARCHIVO.
            $q_prefijo = str_replace('gestion_','',$vista); // LE QUITAMOS EL PREFIJO "GESTION_" SI LO POSEE.
            $q_separado = str_replace('_',' ',$q_prefijo);  // REMPLAZAMOS EL SEPARADOR "_" POR UN ESPACIO " ".
            $titulo = ucfirst($q_separado);     // LO VOLVEMOS UN TITULO CON LA PRIMERA LETRA EN MAYUSCULA.
            $vista = 'views/'.$vista.'.php';    // SI EXISTE REESCRIBIMOS LA VARIABLE CON LA DIRECCION ENTERA.
        } else {                                // SI NO EXISTE DECIMOS QUE LA VISTA POR DEFAULT SERA EL DASHBOARD.
            $titulo = 'Dashboard';              // ESTABLECEMOS EL TITULO.
            $vista = 'views/dashboard.php';     // REESCRIBIMOS LA VARIABLE CON LA VISTA POR DEFAULT (DASHBOARD).
        }
    } else {                                    // SI NO EXISTE DECIMOS QUE LA VISTA POR DEFAULT SERA EL DASHBOARD.
        $titulo = 'Dashboard';                  // ESTABLECEMOS EL TITULO.
        $vista = 'views/dashboard.php';         // REESCRIBIMOS LA VARIABLE CON LA VISTA POR DEFAULT (DASHBOARD).
    }
    $arreglo = [ 'rol' => $_SESSION['usuario']['codigo_rol']]; // OBTENEMOS EL ROL DEL USUARIO Y LO GUARDAMOS EN UN ARREGLO.
    require_once 'models/m_usuario.php';        // INCLUIMOS EL MODELO DEL USUARIO PARA TRAER LOS MODULOS Y LOS SERVICIOS.
    $usuario = new model_usuario();             // CREAMOS UN NUEVO OBJETO.
    $modulos = $usuario->traerModulos($arreglo);// TRAEMOS LOS MODULOS PARA MOSTRARLO EN EL MENU.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo $titulo; ?> | INCES</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="<?php echo SERVERURL; ?>images/app/favicon.png" type="image/png">
    <!-- ARCHIVOS DE ESTILOS -->
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/fontawesome/css/all.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/datatable/datatables.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/datatable/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/app.css">
    <!-- ARCHIVOS JAVASCRIPTS -->
    <script src="<?php echo SERVERURL; ?>javascripts/jquery/jquery-3.4.1.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/popper/popper.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/datatable/datatables.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/generales.js"></script>
</head>

<body>
    <!-- BARRA SUPERIOR -->
    <header class="position-fixed border-info w-100">
        <div class="d-flex justify-content-between align-items-center bg-info">
            <!-- BOTON GESTION MENU -->
            <button id="menu-manager" class="btn btn-sm btn-light text-secondary ml-3"><i class="fas fa-bars"></i></button>

            <!-- TITULO LOGO -->
            <a href="<?php echo SERVERURL; ?>intranet" id="header-title" class="d-inline-block h-100 text-center px-3 py-1 m-0">
                <img src="<?php echo SERVERURL; ?>images/app/logo-invertido.svg" class="h-100">
            </a>
            <!-- FIN TITULO LOGO -->

            <!-- BOTON SALIR -->
            <a href="<?php echo SERVERURL; ?>controllers/c_salir.php" class="btn btn-sm btn-light text-danger hide-descrip mr-3"><i class="fas fa-sign-out-alt"></i><span class="ml-1">Salir</span></a>
        </div>
    </header>
    <!-- FIN MENU SUPERIOR -->

    <!-- CONTENEDOR PRINCIPAL -->
    <div id="container-main">
        <!-- SIDEBAR -->
        <div id="sidebar" class="bg-white px-0 m-0">
            <!-- INFORMACION DEL USUARIO -->
            <div id="info_user" class="d-flex border-bottom px-3 py-2">
                <img src="<?php echo SERVERURL; ?>images/app/man.png" class="rounded pt-2 px-1 mr-2">

                <div id="user-data" class="text-secondary text-uppercase">
                    <p class="m-0"><i class="fas fa-user text-center mr-2"></i><span><?php echo $_SESSION['usuario']['nombre1'].' '.$_SESSION['usuario']['apellido1']; ?></span></p>
                    <p class="m-0"><i class="fas fa-user-tag text-center mr-2"></i><span>C.I: <?php echo $_SESSION['usuario']['nacionalidad'].'-'.$_SESSION['usuario']['cedula']; ?></span></p>
                    <p class="m-0"><i class="fas fa-address-card text-center mr-2"></i><span><?php echo $_SESSION['usuario']['nombre']; ?></span></p>
                </div>
            </div>
            <!-- FIN INFORMACION DEL USUARIO -->

            <!-- COMPONENTE MENU -->
            <ul id="menu-list" class="mt-3 px-3">
                <li class="mb-1"><a href="<?php echo SERVERURL; ?>intranet" class="d-inline-block w-100 rounded px-3 py-2 <?php if ($titulo == 'Dashboard') { echo 'active'; } ?>"><i class="fas fa-home"></i><span class="ml-2">Inicio</span></a></li>
                
                <?php if ($modulos) { foreach ($modulos AS $datos) { ?>
                <li class="dropdown mb-1">
                    <?php $arreglo = [ 'rol' => $_SESSION['usuario']['codigo_rol'], 'modulo' => $datos['codigo']];
                        $vistas = $usuario->traerVistas($arreglo);
                        $pestana = false;
                        if ($vistas) {
                            foreach ($vistas AS $datos2) {
                                if ($titulo == ucfirst(str_replace('_',' ',$datos2['enlace'])))
                                    $pestana = true;
                            }
                        }
                    ?>

                    <a href="#" class="dropdown-toggle d-inline-block w-100 rounded px-3 py-2 <?php if ($pestana) echo 'active'; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="<?php echo $datos['icon']; ?>"></i><span class="ml-2"><?php echo $datos['nombre']; ?></span></a>
                    <ul class="dropdown-menu w-100">
                        <?php if ($vistas) { foreach ($vistas AS $datos2) { ?>
                        <li class="dropdown-item p-0"><a href="<?php echo SERVERURL.'intranet/'.$datos2['enlace']; ?>" class="d-inline-block w-100 px-2 py-1"><i class="text-center <?php echo $datos2['icon']; ?>" style="width:20px;"></i><span class="ml-2"><?php echo $datos2['nombre']; ?></span></a></li>
                        <?php } } ?>
                    </ul>
                </li>
                <?php } } ?>
            </ul>
            <!-- COMPONENTE MENU -->
        </div>
        <!-- FIN SIDEBAR -->

        <!-- CONTENIDO -->
        <div id="main-containt" class="p-3">
            <div class="row">
                <!-- CONTENEDOR FORMULARIOS Y DASHBOARD -->
                <div class="col-sm-12">
                    <div class="bg-white rounded p-3">
                        <?php include_once $vista;?>
                    </div>

                    <?php if (isset($_SESSION['msj'])) { ?>
                    <!-- MENSAJE ALERTA -->
                    <div class="alert alert-<?php echo $_SESSION['msj']['type']; ?> alert-dismissible fade show mt-3" role="alert">
                        <?php echo $_SESSION['msj']['text']; ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- FIN MENSAJE ALERTA -->
                    <?php unset($_SESSION['msj']); } ?>
                </div>
                <!-- FIN CONTENEDOR FORMULARIOS Y DASHBOARD -->
            </div>
        </div>
        <!-- FIN CONTENIDO -->
    </div>
    <!-- FIN CONTENDOR PRINCIPAL-->

    <!-- FOOTER -->
    <footer class="bg-info d-flex align-items-center text-white text-center m-0">
        <span class="small w-100">Instituto Nacional de Capacitación y Educación Socialista - INCES</span>
    </footer>
    <!-- FIN FOOTER -->
</body>
</html>
<?php
} else {    // SI NO HAY SESION INICIADA, ENTONCES REDIRECCIONA AL INICIO DE SESION CON UN MENSAJE.
    // DEFINIMOS EL MENSAJE QUE QUEREMOS MOSTRAR.
    $_SESSION['msj']['type'] = 'danger';
    $_SESSION['msj']['text'] = '<i class="fas fa-lock mr-2"></i>No tienes permiso, inicie sesión.';
    header('Location: '.SERVERURL.'iniciar');
}
?>