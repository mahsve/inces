<?php 
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d H:m:s', time());
/////////////////////////////////////////////////
$data = '
<p>Esto es una prueba</p>
<p>Numero: '.$_POST['numero'].'</p>
';

require_once '../../php/dompdf/autoload.inc.php';
$nombre_documento = "informe_social.pdf";
/////////////////////////////////////////////////
/////////////////////////////////////////////////
$html = '
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style></style>
    </head>
<body>
';
$html .= $data;
$html .= '
</body>
</html>
';
/////////////////////////////////////////////////
// A4, letter // TIPO DE PAGINA
// portrait (VERTICAL) / landscape (HORIZONTAL)
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->setPaper('letter', 'portrait'); 
$dompdf->loadHtml($html);
$dompdf->render();
$output = $dompdf->output();
file_put_contents("../../pdf/".$nombre_documento, $output);
echo $nombre_documento;
?>