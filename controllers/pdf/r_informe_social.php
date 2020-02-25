<?php 
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d H:m:s', time());
$_POST['numero'];
$data = '';
/////////////////////////////////////////////////
$data = '
    <h1>Hola</h1>
    <p>the first page</p>
    <p style="page-break-before: always;">the second page</p>
';
/////////////////////////////////////////////////
require_once '../../php/dompdf/autoload.inc.php';
$nombre_documento = "informe_social.pdf";
/////////////////////////////////////////////////
/////////////////////////////////////////////////
$html = '
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            body { font-family: "Helvetica", serif; margin: 8mm 8mm 2mm 8mm; }
            #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
            #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
            #footer .page:after { content: counter(page, upper-roman); }
        </style>
    </head>
    <body>
        <div id="header">
            header
        </div>
        <div id="footer">
            footer
        </div>
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