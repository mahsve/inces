<?php
include_once 'config/config.php';               // INCLUIMOS EL ARCHIVO DE CONFIGURACION DE LA URL.
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
////////////////////////////////////////////////////////////////////////////////

session_start();
if (isset($_SESSION['sesion'])) {
    require_once 'models/m_sesion.php';
    $sesion = new model_sesion();
    $sesion->conectar();
    $data = [ 'codigo_rol'    => $_SESSION['usuario']['codigo_rol'] ];
    ///////////////////////////////////////////////////////////////////////////
    $files = glob('images/temp/*');
    foreach($files as $file){ if(is_file($file)) { unlink($file); } }
    ///////////////////////////////////////////////////////////////////////////
    if (isset($_GET['data'])) {
        $dataGET = explode('/',$_GET['data']);
        $vista = $dataGET[0];
        if (is_file('views/'.$vista.'.php')) {
            $data['text_vista'] = htmlspecialchars($vista);
            $permisos = $sesion->consultarPermisos($data);
            if ($permisos) {
                $titulo = $permisos['nombre'];
                $vista = 'views/'.$vista.'.php';
            } else {
                header('Location: '.SERVERURL.'intranet');
            }
        } else {
            header('Location: '.SERVERURL.'intranet');
        }
    } else {
        $titulo = 'Dashboard';
        $vista  = 'views/dashboard.php';
        $permisos = [];
    }
    ////////////////////////////////////////////////////////////////////////////
    $menu = $sesion->consultarMenu($data);
    $sesion->desconectar();
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
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/bootstrap-datepicker/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/fontawesome/css/all.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/design.css">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>styles/app.css">
    <!-- ARCHIVOS JAVASCRIPTS -->
    <script src="<?php echo SERVERURL; ?>javascripts/jquery/jquery-3.4.1.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/popper/popper.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/definir_paginacion.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/generales.js"></script>
    <script src="<?php echo SERVERURL; ?>javascripts/tool.js"></script>
    <script>let permisos = <?php echo json_encode($permisos); ?>;</script>
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
        <?php // if (!$_SESSION['actualizar_contrasena']) { ?>
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
                    <li class="mb-1"><a href="<?php echo SERVERURL; ?>intranet" class="d-inline-block w-100 rounded px-3 py-2 <?php if ($titulo == 'Dashboard' || $titulo == 'Sin acceso') { echo 'active'; } ?>"><i class="fas fa-home"></i><span class="ml-2">Inicio</span></a></li>
                    
                    <?php if ($menu) {
                    foreach ($menu AS $modulos) { ?>
                        <li class="dropdown mb-1">
                            <?php
                            $active = '';
                            foreach ($modulos['vistas'] AS $vistas) {
                                if ($titulo == $vistas['nombre'])
                                    $active = 'active';
                            } ?>

                            <a href="#" class="dropdown-toggle d-inline-block w-100 rounded px-3 py-2 <?php echo $active; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-center <?php echo $modulos['icono']; ?>" style="width:16px;"></i><span class="ml-2"><?php echo $modulos['nombre']; ?></span></a>
                    
                            <ul class="dropdown-menu w-100">
                                <?php foreach ($modulos['vistas'] AS $vistas) { ?>
                                    <li class="dropdown-item p-0"><a href="<?php echo SERVERURL.'intranet/'.$vistas['enlace']; ?>" class="d-inline-block w-100 px-2 py-1"><i class="text-center <?php echo $vistas['icono']; ?>" style="width:16px;"></i><span class="ml-2"><?php echo $vistas['nombre']; ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                    } ?>
                </ul>
                <!-- COMPONENTE MENU -->
            </div>
            <!-- FIN SIDEBAR -->

            <!-- CONTENIDO -->
            <div id="main-containt" class="p-3">
                <div class="row">
                    <!-- CONTENEDOR FORMULARIOS Y DASHBOARD -->
                    <div class="col-sm-12">
                        <div class="bg-white rounded p-3 shadow-sm">
                            <?php include_once $vista; ?>
                        </div>
                    </div>
                    <!-- FIN CONTENEDOR FORMULARIOS Y DASHBOARD -->
                </div>
            </div>
            <!-- FIN CONTENIDO -->
        <?php /* } else {
            include_once 'views/actualizar_contrasena.php';
        } */ ?>
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