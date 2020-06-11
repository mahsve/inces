<?php

// ESTABLECEMOS LA ZONA HORARIA.
date_default_timezone_set("America/Caracas");
$fechaHora = date('Y-m-d H:m:s', time());
$fechaSola = date('Y-m-d', time());
$fechaHorafooter = date('d-m-Y H:m:s', time());

///////////////////////////////////////////////////
require_once '../../models/m_aprendiz.php';
$objeto = new model_aprendiz;
$objeto->conectar();

// CONSULTA DEL POSTULANTE
$datosAprendizYEmpresa = $objeto->datosAprendizYEmpresaPDF($_GET['numero']);
$datosAprendiz = $objeto->datosAprendizPDF($_GET['numero']);

//////////////////////////////////
$edad   = 0;
$year = substr($datosAprendiz['fecha_n'], 0, 4); $month = substr($datosAprendiz['fecha_n'], 5, 2); $day = substr($datosAprendiz['fecha_n'], 8, 2);
$yearA = substr($fechaSola, 0, 4); $monthA = substr($fechaSola, 5, 2); $dayA = substr($fechaSola, 8, 2);
if ($year <= $yearA) {
    $edad = $yearA - $year;
    if ($month > $monthA) { 
        if ($edad != 0) { 
            $edad--; 
        } 
    }
    else if ($month == $monthA) {
     if ($day > $dayA) { 
        if ($edad != 0) {
         $edad--; 
     }       
        } 
    }
}

//////////////////////////////////////

// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require_once '../../library/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Instanciamos un objeto de la clase DOMPDF.
// $html = file_get_contents("../../plantillas/misDatosInscripcion.php");
$html='
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
  </head>

  <style type="text/css">
  
  td{
  padding: 5px;
  }
  .new-page {
  page-break-before: always;
  }
  #footer {
  position: fixed;
  bottom: 40px;
  text-align: center;
  font-size: 15px;
  }

  
  
  #footer .page:after {
  content: counter(page, decimal);
  }
  #footer .otro:after {
  content:  close-quote(page, decimal) ;
  }
  
  html {
  margin: 30pt 15pt;
  }
  body{
  font-size: 11px;
  }
  footer{
  position: fixed;
  left: 0;
  height: 150px;
  bottom: -180px;
  right: 0;
  display: block;
  bottom: 10px;
  text-align: center;
  }

  .spam{
   position: relative
  }
  .cabezera{
  height:73.5pt;
  width:570pt;
  }
  table{
  border-collapse: collapse;
  table-layout:fixed;
  }
  .title{
  background: #d6d4d4;
  font-weight: bold;
  text-align: center;
  padding: 10px;
  font-size: 16px;
  }
  </style>
  <body>   
    
    <table border="1" width="100%">
      <!-- Cabezera -->
      <tr>
        <td colspan="16" rowspan="2">
          <img src="../../images/reportes/headerInscripcion.png" width="100%">
          
        </td>
        <td colspan="5">
          GERENCIA REGIONAL: PORTUGUESA
        </td>
      </tr>
      <tr>
        <td colspan="5">FECHA DE INSCRIPCION: '.$datosAprendizYEmpresa['fechaInscripcion'].'</td>
      </tr>
      <!-- Title -->
      <tr height="35">
        <td  height="35" colspan="21" class="title" >
          INSCRIPCION DEL PROGRAMA NACIONAL DE APRENDIZAJE
        </td>
      </tr>
      <tr style=";">
        <td colspan="6" style="padding: 5px;">INSCRIPCION RE-INSCRIPCION: '.$datosAprendiz['correlativo'].'  </td>
        <td colspan="4" >CORRELATIVO DE INSCRIPCION: '.$datosAprendiz['correlativo'].'</td>
        <td colspan="2">N DE ORDEN:'.$datosAprendiz['numero_orden'].'</td>
        <td  colspan="9">RAZON SOCIAL DE LA EMPRESA SOCIAL ANTERIOR: </td>
      </tr>
      <tr style=";">
        <td colspan="16" style="padding: 5px">DIRECCION: Urb. Baraure sector 1, Araure </td>
        <td colspan="5">ESTADO: PORTUGUESA</td>
      </tr>
      <!-- Datos del aprendiz -->
      <tr>
        <td colspan="21" style="text-align: center; font-weight: bold;  font-size: 13px; background: #f1f1f1" >DATOS DEL APRENDIZ</td>
      </tr>
      <tr style="">
        <td colspan="5" height="20">1re APELLIDO:'.$datosAprendiz['apellido1'].' </td>
        <td colspan="5">2re APELLIDO:'.$datosAprendiz['apellido2'].'</td>
        <td colspan="5">1re NOMBRE:'.$datosAprendiz['nombre1'].'</td>
        <td colspan="6">2re NOMBRE:'.$datosAprendiz['nombre2'].'</td>
      </tr>
      <!--  -->
      <tr style="text-align: center;">
        <td colspan="4" > CEDULA </td>
        <td colspan="3">FECHA DE N. </td>
        <td>SEXO</td>
        <td colspan="9" rowspan="2">DIRECCION: CALLE 33 AV. 47 CASA 04 COMUNIDAD ANDRES
        ELOY BLANCO </td>
        <td colspan="4" rowspan="2">ESTADO: PORTUGUESA </td>
      </tr>
      <tr style="">
        <td colspan="4" rowspan="2" style="text-align:center">'.$datosAprendiz['nacionalidad_aprendiz'].' - '.$datosAprendiz['cedula_aprendiz'].'</td>
        <td colspan="3">'.$datosAprendiz['fecha_n'].'</td>
        <td>'.$datosAprendiz['sexo'].'</td>
      </tr>
      <tr style="text-align: center;">
        <td colspan="3">EDAD:'. $edad .' </td>
        <td>FX</td>
        <td colspan="3" rowspan="2">CIUDAD: '.$datosAprendiz['ciudadAprendiz'].'</td>
        <td colspan="3" rowspan="2">MUNICIPIO:'.$datosAprendiz['municipioAprendiz'].' </td>
        <td colspan="3" rowspan="2">PARROQUIA: '.$datosAprendiz['parroquiaAprendiz'].'</td>
        <td colspan="4" rowspan="2">TELEFONO: '.$datosAprendiz['telefono1'].'</td>
      </tr>
      <tr>
        <!-- cuadro de nivel de instruccion -->
        <td colspan="8" height="18" style="font-size: 8px; background-color: #dcd4d4; text-align: center; font-weight: bold;"> NIVEL DE INSTRUCCION</td>
      </tr>
      <tr>
        
        <td colspan="3" height="15" style="font-size: 8px; text-align: center;">EDUCACION BASICA</td>
        <td colspan="5" rowspan="2" style="font-size: 7.9px; text-align: center;"> EDUCACION MEDIA</td>
        <td colspan="10" rowspan="2" style="">DENOMINACION DEL OFICIO CALIFICADO:'.$datosAprendiz['oficio'].'
        </td>
        <td colspan="3" rowspan="2" style=""> CODIGO DE OFICIO: '.$datosAprendiz['codigo_oficio'].'</td>
      </tr>
      <tr style="text-align: center;">
        <td style="font-size: 7px">I ETAPA</td>
        <td style="font-size: 7px">II ETAPA</td>
        <td style="font-size: 7px">III ETAPA</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2">AÑO</td>
        <td colspan="3"></td>
        <td colspan="10" rowspan="3"></td>
        <td colspan="3" rowspan="3"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td rowspan="2">5to</td>
        <td rowspan="2"></td>
        <td rowspan="2"></td>
        <td rowspan="2"></td>
        <td rowspan="2"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      
      <!-- Datos de la empresa -->
      <tr>
        <td colspan="12" height="25">RAZON SOCIAL DE LA EMPRESA: '.$datosAprendizYEmpresa['razon_social'].'</td>
        <td colspan="9">ACTIVIDAD ECONOMICA DE LA EMPRESA: '.$datosAprendizYEmpresa['nombreActividad'].'</td>
      </tr>
      <tr>
        <td colspan="7" height="25">CODIGO APORTANTE: '.$datosAprendizYEmpresa['codigo_aportante'].'</td>
        <td colspan="5">R.I.F.  '.$datosAprendizYEmpresa['rifEmpresa'].' </td>
        <td colspan="5">N.I.L.:  '.$datosAprendizYEmpresa['nil'].'</td>
        <td colspan="4">TELEFONOS: '.$datosAprendizYEmpresa['telefono_empresa1'].'</td>
      </tr>
      <tr>
        <td colspan="14" height="25">DIRECCION: '.$datosAprendizYEmpresa['direccionEmpresa'].'</td>
        <td colspan="7">ESTADO: '.$datosAprendizYEmpresa['estadoEmpresa'].'</td>
      </tr>
      <tr>
        <td colspan="6" height="25">CIUDAD: '.$datosAprendizYEmpresa['ciudadEmpresa'].'</td>
        <td colspan="5">MUNICIPIO: '.$datosAprendizYEmpresa['municipioEmpresa'].'</td>
        <td colspan="5">PARROQUIA: '.$datosAprendizYEmpresa['parroquiaEmpresa'].'</td>
        <td colspan="5">PERSONA CONTACTO: '.$datosAprendizYEmpresa['personaContacto'].'</td>
      </tr>
      <tr>
        <td colspan="21" height="20" style="text-align: center; font-weight: bold;  font-size: 13px; background: #f1f1f1">INSCRIPCION AL PROGRAMA NACIONAL APRENDIZAJE</td>
      </tr>
      
      <!-- Pie del reporte para las firmas  -->
      <tr>
        <th colspan="7" style="padding:6px">INCES</th>
        <th colspan="7" >APRENDIZ</th>
        <th colspan="7">EMPRESA</th>
      </tr>
      <tr>
        <td height="25" colspan="7">NOMBRE Y APELLIDO: '.$datosAprendiz['f_nombre1'].' '.$datosAprendiz['f_apellido1'].'</td>
        <td  colspan="7">NOMBRE Y APELLIDO: '.$datosAprendiz['nombre1'].' '.$datosAprendiz['apellido1'].'</td>
        <td  colspan="7">NOMBRE Y APELLIDO: '.$datosAprendizYEmpresa['personaContacto'].'</td>
      </tr>
      <tr>
        <td colspan="7" height="27">CODIGO DEL EMPLEADO: '.$datosAprendiz['cedula_facilitador'].'</td>
        <td colspan="7">FIRMA</td>
        <td colspan="3">FIRMA</td>
        <td colspan="4">FIRMA / SELLO DE LA EMPRESA</td>
      </tr>
      <tr>
        <td colspan="14" height="25">FORMA PROVISIONAL (G.P.E.O YP.) (02-04)</td>
        <td colspan="7">
          ORIGINAL: UNIDAD DE PROGRAMA NACIONAL DE APRENDIZAJE
          DUPLICADO: EMPRESA
        </td>
      </tr>
    </table>
    
      
    <div id="footer">
      <p >Pagina 1 / 1</p>
      <spam class="spam">'. $fechaHorafooter .'</spam>
      
    </div>
    
    <script src="<?php echo SERVERURL; ?>javascripts/aprendiz.js"></script>
  </body>
</html>
';
$dompdf = new DOMPDF();
$dompdf->set_paper('A4', 'portrait');
// $dompdf->load_html(utf8_decode(file_get_contents("../../plantillas/misDatosInscripcion.php")));
$dompdf->load_html($html);
$output = $dompdf->output();
// $dompdf->stream("fichaDeInscripcion".Date('Y-m-d').".pdf");
$dompdf->render();
$dompdf->stream("FichaInscripcion.pdf", array("Attachment" => false));
exit(0);
?>