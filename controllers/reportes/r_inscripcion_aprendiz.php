<?php 
require_once '../../library/html2pdf/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$dynamic_data = '';
////////////////////////////////////////
/*       INICIALIZACIONDEL PDF        */
$html = '
<page> 
    <page_header>
        <div style="padding: 0px 10px;">
            <img src="./../../images/reportes/logo-inscripcion.png" alt="Logo" width="120"/>
        <div>
    </page_header>
    <page_footer>

    </page_footer>
    '.$dynamic_data.'
</page> 
';
$html2pdf = new Html2Pdf('P', 'LETTER', 'es');
$html2pdf->setDefaultFont('Arial');
$html2pdf->writeHTML($html);
$html2pdf->output('inscripcion_aprendiz.pdf');