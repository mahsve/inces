<?php
if ($_FILES['archivo']['name'] == '') { 
    $respuesta = 'Vacio';
} else {
    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], '../images/temp/'.$_FILES['archivo']['name'])) {
        $respuesta = 'Error';
    } else {
        $respuesta = 'Exitoso';
    }
}
echo $respuesta;
?>