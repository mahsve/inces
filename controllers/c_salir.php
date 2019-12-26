<?php
session_start();
/////////////////
session_destroy();
session_start();
/////////////////
// MANDAMOS UN MENSAJE Y REDIRECCIONAMOS A LA PAGINA DE INICAR SESION.
$_SESSION['msj']['type'] = 'success';
$_SESSION['msj']['text'] = '<i class="fas fa-check mr-2"></i>Se ha cerrado sesión exitosamente.';
header('Location: ../iniciar');
?>