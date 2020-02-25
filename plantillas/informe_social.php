<?php 
date_default_timezone_set("America/Caracas");   // ESTABLECEMOS LA ZONA HORARIA.
$date = date('Y-m-d H:m:s', time());
$_POST['numero'];
$arreglo_ingresos = ['PENSIÓN','SEGURO SOCIAL','OTRAS PENSION','SUELDO  Y/O SALARIO','OTROS INGRESOS','TOTAL INGRESOS:'];
$arreglo_egresos = ['GASTOS DE SERVICIOS BASICOS  (AGUA, LUZ, TELEFONO, ETC.)', 'ALIMENTACION', 'EDUCACION', 'VIVIENDA (ALQUILER COMDOMINIO)', 'OTROS EGRESOS', 'TOTAL EGRESOS:'];

$data = '';
/////////////////////////////////////////////////
$data = '
    <h3 class="text-center" style="margin-bottom: 10px;"><span class="border-bottom">INFORME SOCIAL</span></h3>
    <h4 class="text-right" style="margin-bottom: 10px;">FECHA: <span class="d-inline-block" style="width: 25px;"></span>/<span class="d-inline-block" style="width: 25px;"></span>/<span class="d-inline-block" style="width: 50px;"></span></h4>
    <p class="font12">I.- DATOS DEL CIUDADANO (A):</p>
    <table class="w-100" border="1">
        <tr>
            <td style="width: 35%; height: 50px;">
                <b>NOMBRES:</b><br>
            </td>
            <td style="width: 35%;">
                <b>APELLIDOS:</b><br>
            </td>
            <td style="width: 15%;">
                <b>C.I.:</b><br>
            </td>
            <td style="width: 15%;">
                <b>SEXO:</b><br>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td style="height: 50px;">
                <b>LUGAR Y FECHA  DE NACIMIENTO:</b><br>
            </td>
            <td style="width: 8%;">
                <b>EDAD:</b><br>
            </td>
            <td style="width: 20%;">
                <b>NACIONALIDAD:</b><br>
            </td>
            <td style="width: 25%;">
                <b>OCUPACION:</b><br>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-25">
                <p style="margin-bottom: 10px;"><b>ESTADO CIVIL:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative">SOLTERO (A) <span class="input-check" style="margin-left: 100px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">CASADO (A) <span class="input-check"  style="margin-left: 107px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">CONCUBINO (A) <span class="input-check" style="margin-left: 84.5px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">DIVORCIADO (A) <span class="input-check" style="margin-left: 82px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">VIUDO (A) <span class="input-check" style="margin-left: 120px;"></span></div>
            </td>
            <td class="w-75">
                <p style="margin-bottom: 5px;"><b>GRADO DE INSTRUCCIÓN:</b></p>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. BASICA INCOMPLETA: <span class="input-check" style="margin-left: 100px;"></span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. MEDIA DIVERSIFICADA INCOMPLETA: <span class="input-check" style="margin-left: 37px;"></span></div><br>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. BASICA COMPLETA: <span class="input-check" style="margin-left: 112.5px;"></span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. MEDIA DIVERSIFICADA COMPLETA: <span class="input-check" style="margin-left: 49px;"></span></div><br>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. SUPERIOR INCOMPLETA: <span class="input-check" style="margin-left: 82px;"></span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. SUPERIOR COMPLETA: <span class="input-check" style="margin-left: 120px;"></span></div>

                <p style="margin-top: 5px;"><b>TITULO:</b></p>
                <p class="border-bottom" style="height: 20px;"></p>
                <p style="margin-top: 5px;"><b>HA PARTICIPADO EN ALGUNA MISION. INDIQUE:</b></p>
                <p class="border-bottom" style="height: 20px;"></p>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-50" style="height: 50px;">
                <b>TELEFONO DE HABITACION:</b><br>
            </td>
            <td class="w-50">
                <b>TELEFONO CELULAR y correo electrónico:</b><br>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td style="width: 35%; height: 50px;">
                <b>SALIDA OCUPACIONAL:</b><br>
            </td>
            <td>
                <b>TURNO:</b><br>
            </td>
            <td class="w-50">
                <b>NOMBRE DEL FACILITADOR:</b><br>
            </td>
        </tr>
    </table>
    <div style="page-break-before: always;"></div>

    <p class="font12">II.- UBICACIÓN GEOGRAFICA DE LA VIVIENDA:</p>
    <table class="w-100" border="1">
        <tr>
            <td class="w-50" style="height: 50px;">
                <b>DIRECCION EXACTA:</b><br>
            </td>
            <td class="w-50">
                <b>PUNTO DE REFERENCIA:</b><br>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-25" style="height: 50px;">
                <b>LOCALIDAD:</b><br>
            </td>
            <td class="w-25">
                <b>MUNICIPIO:</b><br>
            </td>
            <td class="w-25">
                <b>ESTADO:</b><br>
            </td>
            <td class="w-25">
                <p style="margin-bottom: 5px;"><b>AREA:</b></p>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">RURAL <span class="input-check"></span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">URBANA <span class="input-check"></span></div>
            </td>
        </tr>
    </table>

    <p class="font12" style="margin-top: 20px;">III.- CARACTERISTICAS DE LA VIVIENDA</p>
    <table class="w-100" border="1">
        <tr>
            <td style="width: 20%;">
                <b>TIPO DE VIVIENDA EN LA QUE HABITA  ACTUALMENTE::</b>
            </td>
            <td style="width: 20%;">
                <b>TENENCIA DE LA VIVIENDA:</b>
            </td>
            <td colspan="2">
                <b>SERVICIOS PUBLICOS DISPONIBLES:</b>
            </td>
        </tr>
        <tr>
            <td style="height: 50px; padding-top: 10px;">
                <div style="margin-bottom: 9px;" class="position-relative">QUINTA <span class="input-check" style="margin-left: 90px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">CASA <span class="input-check" style="margin-left: 102.5px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">APARTAMENTO <span class="input-check" style="margin-left: 44.3px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">RANCHO <span class="input-check" style="margin-left: 84px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">OTRO <span class="input-check" style="margin-left: 101.5px;"></span></div>
            </td>
            <td style="padding-top: 10px;">
                <div style="margin-bottom: 9px;" class="position-relative">PROPIA <span class="input-check" style="margin-left: 90px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">ALQUILADA <span class="input-check" style="margin-left: 68px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">PRESTADA <span class="input-check" style="margin-left: 70.5px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative">INVADIDA <span class="input-check" style="margin-left: 79px;"></span></div>
            </td>
            <td style="padding-top: 10px;">
                <p style="margin-bottom: 5px;"><b>GRADO DE INSTRUCCIÓN:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">ACUEDUCTO <span class="input-check" style="margin-left: 30px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">CISTERNA <span class="input-check" style="margin-left: 30px;"></span></div><br>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">POZOS <span class="input-check" style="margin-left: 64.5px;"></span></div>
            
                <p style="margin-bottom: 5px; margin-top: 10px;"><b>ELECTRICIDAD:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">LEGAL <span class="input-check" style="margin-left: 68px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">ILEGAL <span class="input-check" style="margin-left: 49px;"></span></div>
            </td>
            <td style="padding-top: 10px;">
                <p style="margin-bottom: 5px;"><b>EXCRETAS:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">CLOACAS <span class="input-check" style="margin-left: 38px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">POZO SEPTICO <span class="input-check" style="margin-left: 7px;"></span></div><br>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">LETRINA <span class="input-check" style="margin-left: 45px;"></span></div>
            
                <p style="margin-bottom: 5px; margin-top: 10px;"><b>BASURA:</b></p>
                <div style="margin-bottom: 9px; width: 60%;" class="position-relative d-inline-block">ASEO URBANO <span class="input-check" style="margin-left: 9px;"></span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block">QUEMA <span class="input-check" style="margin-left: 27px;"></span></div>
                
                <p style="margin-top: 5px;"><b>OTROS:</b></p>
                <p class="border-bottom" style="height: 20px;"></p>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td colspan="4">
                <p><b>MATERIALES DE CONSTRUCCION PREDOMINANTES:</b></p>
            </td>
        </tr>
        <tr>
            <td class="w-25" style="height: 50px;">
                <b>TECHO:</b><br>
            </td>
            <td class="w-25">
                <b>PAREDES:</b><br>
            </td>
            <td class="w-25">
                <b>PISO:</b><br>
            </td>
            <td class="w-25">
                <b>VIA DE ACCESO:</b><br>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td colspan="4">
                <p><b>DISTRIBUCION DE LA VIVIENDA (COLOQUE EL NUMERO DE AMBIENTES):</b></p>
            </td>
        </tr>
        <tr>
            <td class="w-20" style="height: 50px;">
                <b>SALA:</b><br>
            </td>
            <td class="w-20">
                <b>COMEDOR:</b><br>
            </td>
            <td class="w-20">
                <b>COCINA:</b><br>
            </td>
            <td class="w-20>
                <b>BAÑOS:</b><br>
            </td>
            <td class="w-20">
                <b>Nº DE DORMITORIOS:</b><br>
            </td>
        </tr>
    </table>
    <div style="page-break-before: always;"></div>

    <p class="font12">IV.- AREA SOCIO FAMILIAR</p>
    <p class="font10">PERSONAS QUE HABITAN CON EL TRABAJADOR (A), INICIANDO DESDE EL JEFE DEL HOGAR</p>
    <table class="w-100 medio" border="1">
        <tr>
            <td style="width: 20px; height: 20px;" class="text-center">
                <b>Nº</b>
            </td>
            <td>
                <b>APELLIDO Y NOMBRE</b>
            </td>
            <td style="width: 20px;">
                <b>EDAD</b>
            </td>
            <td style="width: 20px;">
                <b>SEXO</b>
            </td>
            <td style="width: 100px;">
                <b>PARENTESCO</b>
            </td>
            <td style="width: 100px;">
                <b>OCUPACION</b>
            </td>
            <td style="width: 100px;">
                <b>TRABAJA</b>
            </td>
            <td style="width: 100px;">
                <b>INGRESO (Bs.S)</b>
            </td>
            <td style="width: 20px;">
                <b>RESPON.</b>
            </td>
        </tr>
    ';
    for ($i = 0; $i < 10; $i++) {
        $data .= '
        <tr>
            <td style="width: 20px; height: 30px;" class="text-center">
                '.($i + 1).'
            </td>
            <td>

            </td>
            <td style="width: 20px;">

            </td>
            <td style="width: 20px;">
            
            </td>
            <td style="width: 100px;">
            
            </td>
            <td style="width: 100px;">
            
            </td>
            <td style="width: 100px;">
            
            </td>
            <td style="width: 100px;">
            
            </td>
            <td style="width: 20px;">
            
            </td>
        </tr>';
    }
    $data .= '</table>
    <div style="page-break-before: always;"></div>

    <p class="font12">V.- INGRESO FAMILIAR</p>
    <table class="w-100 medio" border="1">
        <tr>
            <td style="height: 20px;">
                <b>INGRESO FAMILIAR</b>
            </td>
            <td class="w-20">
                <b>BOLIVARES</b>
            </td>
            <td>
                <b>EGRESO FAMILIAR</b>
            </td>
            <td class="w-20">
                <b>BOLIVARES</b>
            </td>
        </tr>
    ';
    for ($i = 0; $i < count($arreglo_ingresos); $i++) {
        $data .= '
        <tr>
            <td style="height: 40px;">
                <b>'.$arreglo_ingresos[$i].'</b>
            </td>
            <td class="w-20">
                <b></b>
            </td>
            <td>
                <b>'.$arreglo_egresos[$i].'</b>
            </td>
            <td class="w-20">
                <b></b>
            </td>
        </tr>';
    }
    $data .= '</table>

    <p class="font12" style="margin-top: 20px;">VI.- SOLO PARA USO DE LA TRABAJADORA SOCIAL</p>
    <div class="descripcion-facilitador">
        <p class="font11"><b>CONDICIONES GENERALES DE LA VIVIENDA:</b></p>
    </div>

    <div class="descripcion-facilitador">
        <p class="font11"><b>CARACTERISTICAS GENERALES DE LAS RELACIONES FAMILIARES Y SUS CONDICIONES SOCIOECONOMICAS:</b></p>
    </div>

    <div class="descripcion-facilitador">
        <p class="font11"><b>DIAGNOSTICO SOCIAL:</b></p>
    </div>

    <div class="descripcion-facilitador">
        <p class="font11"><b>DIAGNOSTICO PRELIMINAR:</b></p>
    </div>

    <div class="descripcion-facilitador">
        <p class="font11"><b>CONCLUSIONES Y RECOMENDACIONES:</b></p>
    </div>

    <b class="font11">AY ALGUN ENFERMO EN EL GRUPO FAMILIAR:</b>
    <div style="margin-bottom: 9px; width: 60px;" class="font11 d-inline-block position-relative">SI <span class="input-check" style="margin-left: 5px; margin-right: 7px;"></span></div>
    <div style="margin-bottom: 9px; width: 60px;" class="font11 d-inline-block position-relative">NO <span class="input-check" style="margin-left: 5px; margin-right: 7px;"></span></div>
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
            body { font-family: "Helvetica", serif; margin: 20mm 8mm 2mm 8mm; color: #4D5056; }
            h1, h2, h3, h4, h5, h6, p { margin: 0px;}
            table { border-spacing : 0 0; border-collapse : collapse; margin-bottom: -1.5px; }
            table td { vertical-align:top; font-size: 12px; padding: 3px;}
            table.medio td { vertical-align:middle;}
            #header { position: fixed; left: 0px; top: -50px; right: 0px; height: 110px; margin: 0mm 8mm 0mm 8mm; }
            #footer { position: fixed; left: 0px; bottom: -55px; right: 0px; height: 80px; margin: 0mm 8mm 0mm 8mm; }
            #footer .page:after { content: counter(page, upper-roman); }
            
            .font12 { font-size: 12px; }
            .font11 { font-size: 11px; }
            .font10 { font-size: 10px; }
            .w-100 { width: 100%; }
            .w-50 { width: 50%; }
            .w-25 { width: 25%; }
            .w-20 { width: 20%; }
            .text-center { text-align: center; }
            .text-left { text-align: left; }
            .text-right { text-align: right; }
            .d-inline-block { display: inline-block; }
            .d-inline { display: inline; }
            .d-block { display: block; }
            .d-flex { display: flex; }
            .position-relative { position: relative; }
            .position-absolute { position: absolute; }
            .border { border: 1px solid; }
            .border-top { border-top: 1px solid; }
            .border-right { border-right: 1px solid; }
            .border-bottom { border-bottom: 1px solid; }
            .border-left { border-left: 1px solid; }

            .input-check {
                display: inline-block;
                width: 15px;
                height: 15px;
                border: 2px solid #4D5056;
                border-radius: 4px 4px 4px 4px;
                margin-top: -2px;
                position: absolute;
            }
            .descripcion-facilitador {
                width: 100%;
                height: 200px;
                margin-bottom: 10px;
                border: 2px solid #4D5056;
                border-radius: 4px 4px 4px 4px;
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <div id="header">
            <img src="../../images/pdf/logo-inces.jpg" style="width: 170px; height: 90px; margin-top: 15px;">
        </div>
        <div id="footer"></div>
    ';

    $html .= $data;

    $html .= '
    </body>
</html>
';
/////////////////////////////////////////////////
// A4, letter, legal  // TIPO DE PAGINA
// portrait (VERTICAL) / landscape (HORIZONTAL)
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->setPaper('letter', 'landscape'); 
$dompdf->loadHtml($html);
$dompdf->render();
$output = $dompdf->output();
file_put_contents("../../pdf/".$nombre_documento, $output);
echo $nombre_documento;
?>