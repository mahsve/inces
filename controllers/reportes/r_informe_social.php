<?php 
// ESTABLECER ZONA HORARIA.
date_default_timezone_set("America/Caracas");
$fechaHora = date('Y-m-d H:m:s', time());
$fechaSola = date('Y-m-d', time());


///////////////////////////////////////////////////
require_once '../../models/m_informe_social.php';
$objeto = new model_informeSocial;
$objeto->conectar();

// CONSULTA DEL POSTULANTE
$datosAprendiz = $objeto->datosAprendizPDF($_GET['n']);
$arreglo_2 = ['nacionalidad' => $datosAprendiz['nacionalidad'], 'cedula' => $datosAprendiz['cedula']];
$arreglo_3 = ['informe' => $_GET['n']];

// CONSULTA DE LOS DETALLES
$datosVivienda = $objeto->consultarDatosVivienda($arreglo_2);
$datosFamiliares = $objeto->consultarFamiliares($arreglo_3);
$datosDinero = $objeto->consultarDinero($arreglo_3);
$objeto->desconectar();
///////////////////////////////////////////////////


///////////////////////////////////////////////////
// DATOS ARREGLOS
$estructuraHTML = '';
$arreglo_meses      = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$arreglo_ingresos   = ['PENSIÓN','SEGURO SOCIAL','OTRAS PENSION','SUELDO  Y/O SALARIO','OTROS INGRESOS'];
$arreglo_ingresos2  = ['ingreso_pension', 'ingreso_seguro', 'ingreso_pension_otras', 'ingreso_sueldo', 'otros_ingresos'];
$arreglo_egresos    = ['GASTOS DE SERVICIOS BASICOS  (AGUA, LUZ, TELEFONO, ETC.)', 'ALIMENTACION', 'EDUCACION', 'VIVIENDA (ALQUILER COMDOMINIO)', 'OTROS EGRESOS'];
$arreglo_egresos2   = ['egreso_servicios', 'egreso_alimentario', 'egreso_educacion', 'egreso_vivienda', 'otros_egresos'];
// $checked            = '<img src="../../images/pdf/check_1.png" style="width: 20px; margin-top: -5px; margin-left: 3px;">';
// $times              = '<img src="../../images/pdf/times_1.png" style="width: 20px; margin-top: -5px; margin-left: 3px;">';
$checked = 'V';
$times = 'X';
$dataParentescoF    = ['Madre', 'Hermana', 'Abuela', 'Tía', 'Prima', 'Sobrina'];
$dataParentescoM    = ['Padre', 'Hermano', 'Abuelo', 'Tío', 'Primo', 'Sobrino'];
///////////////////////////////////////////////////


/////////////////////////////////////////////////
// DEFINIR DATOS DEL APRENDIZ PARTE 1
if ($datosAprendiz['sexo'] == 'F') { $sexo = 'Femenino'; }
else { $sexo = 'Masculino'; }

$edad   = 0;
$year = substr($datosAprendiz['fecha_n'], 0, 4); $month = substr($datosAprendiz['fecha_n'], 5, 2); $day = substr($datosAprendiz['fecha_n'], 8, 2);
$yearA = substr($fechaSola, 0, 4); $monthA = substr($fechaSola, 5, 2); $dayA = substr($fechaSola, 8, 2);
if ($year <= $yearA) {
    $edad = $yearA - $year;
    if ($month > $monthA) { if ($edad != 0) { $edad--; } }
    else if ($month == $monthA) { if ($day > $dayA) { if ($edad != 0) { $edad--; } } }
}

$estado_civil = ['S' => '', 'C' => '', 'X' => '', 'D' => '', 'V' => ''];
$estado_civil[$datosAprendiz['estado_civil']] = $checked;

$grado_instruccion = ['BI' => '', 'BC' => '', 'MI' => '', 'MC' => '', 'SI' => '', 'SC' => ''];
$grado_instruccion[$datosAprendiz['nivel_instruccion']] = $checked;

if ($datosAprendiz['turno'] == 'V') { $turno = 'Vespertino'; }
else { $turno = 'Matutino'; }

$facilitador = $datosAprendiz['f_nombre1'];
if ($datosAprendiz['f_nombre2'] != null AND $datosAprendiz['f_nombre2'] != '') { $facilitador .= ' '.$datosAprendiz['f_nombre2']; }

$facilitador .= ' '.$datosAprendiz['f_apellido1'];
if ($datosAprendiz['apellido2'] != null AND $datosAprendiz['apellido2'] != '') { $facilitador .= ' '.$datosAprendiz['apellido2']; }
/////////////////////////////////////////////////



require_once '../../library/html2pdf/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
////////////////////////////////////////
/*       INICIALIZACIONDEL PDF        */
$html = '
<style>
<!--
    * {
        margin: 0mm;
        padding: 0mm;
        color: #515561;
        border-color: #515561;
        font-family: Helvetica;
    }
    #header, #footer { padding: 0mm 16mm; }
    table { border-collapse: collapse; }
    td { padding: 0mm; }
    .width-100 { width: 100%; }

    .font8  { font-size: 8px; }
    .font9  { font-size: 8px; }
    .font10 { font-size: 10px; }
    .font11 { font-size: 11px; }
    .font12 { font-size: 12px; }
    .font13 { font-size: 13px; }
    .font14 { font-size: 14px; }
    /* ================================== */
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-justify { text-align: justify; }
    /* ================================== */
    .d-block { display: block; }
    .d-inline { display: inline; }
    .d-inline-block { display: inline-block; }
    .d-flex { display: flex; }
    /* ================================== */
    .border { border: 1px solid #515561; }
    .border-top { border-top: 1px solid #515561; }
    .border-bottom { border-bottom: 1px solid #515561; }
    .border-left { border-left: 1px solid #515561; }
    .border-right { border-right: 1px solid #515561; }
    /* ================================== */
    .text-overline { text-decoration: overline; }
    .text-underline { text-decoration: underline; }
    .text-line-through { text-decoration: line-through; }

    .label {
        position: relative;
        padding-right: 20px;
        margin: 3px 0px;
    }
    .checkbox {
        display: inline-block;
        height: 15px;
        width: 15px;
        border: 2px solid #515561;
        border-radius: 3px;
        text-align: center;
        vertical-align: middle;
        position: absolute;
        top: -1.5px;
        right: 0px;
    }
-->
</style>

<page backtop="28mm" backbottom="10mm" backleft="16mm" backright="16mm">
    <page_header>
        <div id="header">
            <img src="./../../images/reportes/logo-inces.png" alt="Logo" width="120"/>
        </div>
    </page_header>

    <page_footer>
        <div id="footer" class="text-center">
            <b>[[page_cu]]/[[page_nb]]</b>
        </div>
    </page_footer>
    
    <h3 class="text-center" style="margin-bottom: 10px;"><span class="text-underline">INFORME SOCIAL</span></h3>
    <h4 class="text-right" style="margin-bottom: 10px;"><span>FECHA:</span><span>19</span>/<span>02</span>/<span>2020</span></h4>
    
    <p class="font12">I.- DATOS DEL CIUDADANO (A):</p>
    <table border="1">
        <tr>
            <td style="width: 35%; height: 50px;"><b>NOMBRES:</b><br> '.$datosAprendiz['nombre1'].' '.$datosAprendiz['nombre2'].'</td>
            <td style="width: 35%;"><b>APELLIDOS:</b><br>'.$datosAprendiz['apellido1'].' '.$datosAprendiz['apellido2'].'</td>
            <td style="width: 15%;"><b>C.I.:</b><br>'.$datosAprendiz['nacionalidad'].'-'.$datosAprendiz['cedula'].'</td>
            <td style="width: 15%;"><b>SEXO:</b><br>'.$sexo.'</td>
        </tr>
    </table>

    <table class="w-100" border="1">
        <tr>
            <td style="height: 50px;"><b>LUGAR Y FECHA  DE NACIMIENTO:</b><br>
                '.$day.' de '.$arreglo_meses[intval($month)].' de '.$year.'<br>
                '.$datosAprendiz['lugar_n'].'
            </td>
            <td style="width: 8%;"><b>EDAD:</b><br>'.$edad.' Años.</td>
            <td style="width: 20%;"><b>NACIONALIDAD:</b><br>Venezolana</td>
            <td style="width: 25%;"><b>OCUPACION:</b><br>'.$datosAprendiz['ocupacion'].'</td>
        </tr>
    </table>
    <table class="w-100" border="1">
        


    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-50" style="height: 50px;"><b>TELEFONO DE HABITACION:</b><br>'.$datosAprendiz['telefono1'].'</td>
            <td class="w-50"><b>TELEFONO CELULAR Y CORREO ELECTRÓNICO:</b><br>'.$datosAprendiz['telefono2'].'<br>'.$datosAprendiz['correo'].'</td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td style="width: 35%; height: 50px;"><b>SALIDA OCUPACIONAL:</b><br>'.$datosAprendiz['oficio'].'</td>
            <td><b>TURNO:</b><br>'.$turno.'</td>
            <td class="w-50"><b>NOMBRE DEL FACILITADOR:</b><br>'.$datosAprendiz['nacionalidad_fac'].'-'.$datosAprendiz['cedula_facilitador'].'<br>'.$facilitador.'</td>
        </tr>
    </table>
</page>
';
////////////////////////////////////////
////////////////////////////////////////
$html2pdf = new Html2Pdf('L', 'LETTER', 'es');
$html2pdf->setDefaultFont('Arial');
$html2pdf->writeHTML($html);
$html2pdf->output('informe_social.pdf');