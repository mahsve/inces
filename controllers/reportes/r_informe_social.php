<?php 
// ESTABLECEMOS LA ZONA HORARIA.
date_default_timezone_set("America/Caracas");
$fechaHora = date('Y-m-d H:m:s', time());
$fechaSola = date('Y-m-d', time());
///////////////////////////////////////////////////
///////////////////////////////////////////////////
require_once '../../models/m_informe_social.php';
$objeto = new model_informeSocial;
$objeto->conectar();
$datosAprendiz = $objeto->datosAprendizPDF($_GET['n']);
/////////////////////////////////////////////////////////////
$arreglo_2 = ['nacionalidad' => $datosAprendiz['nacionalidad'], 'cedula' => $datosAprendiz['cedula']];
$arreglo_3 = ['informe' => $_GET['n']];
/////////////////////////////////////////////////////////////
$datosVivienda = $objeto->consultarDatosVivienda($arreglo_2);
$datosFamiliares = $objeto->consultarFamiliares($arreglo_3);
$datosDinero = $objeto->consultarDinero($arreglo_3);
$objeto->desconectar();
///////////////////////////////////////////////////
$data = '';
$arreglo_meses      = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$arreglo_ingresos   = ['PENSIÓN','SEGURO SOCIAL','OTRAS PENSION','SUELDO  Y/O SALARIO','OTROS INGRESOS'];
$arreglo_ingresos2  = ['ingreso_pension', 'ingreso_seguro', 'ingreso_pension_otras', 'ingreso_sueldo', 'otros_ingresos'];
$arreglo_egresos    = ['GASTOS DE SERVICIOS BASICOS  (AGUA, LUZ, TELEFONO, ETC.)', 'ALIMENTACION', 'EDUCACION', 'VIVIENDA (ALQUILER COMDOMINIO)', 'OTROS EGRESOS'];
$arreglo_egresos2   = ['egreso_servicios', 'egreso_alimentario', 'egreso_educacion', 'egreso_vivienda', 'otros_egresos'];
$checked            = '<img src="../../images/reportes/check.png" style="width: 20px; margin-top: -5px; margin-left: 3px;">';
$times              = '<img src="../../images/reportes/times.png" style="width: 20px; margin-top: -5px; margin-left: 3px;">';
$dataParentescoF    = ['Madre', 'Hermana', 'Abuela', 'Tía', 'Prima', 'Sobrina'];
$dataParentescoM    = ['Padre', 'Hermano', 'Abuelo', 'Tío', 'Primo', 'Sobrino'];
/////////////////////////////////////////////////
// DEFINIR SEXO
$sexo = 'Masculino';
if ($datosAprendiz['nacionalidad'] == 'F')
    $sexo = 'Femenino';

// DEFINIR EDAD
$edad   = 0;
$year    = substr($datosAprendiz['fecha_n'], 0, 4);
$month   = substr($datosAprendiz['fecha_n'], 5, 2);
$day     = substr($datosAprendiz['fecha_n'], 8, 2);
$yearA   = substr($fechaSola, 0, 4);
$monthA  = substr($fechaSola, 5, 2);
$dayA    = substr($fechaSola, 8, 2);
if ($year <= $yearA) {
    $edad = $yearA - $year;
    if ($month > $monthA) {
        if ($edad != 0) 
            $edad--;
    } else if ($month == $monthA) {
        if ($day > $dayA)
            if ($edad != 0) 
                $edad--;
    }
}

// DEFINIR ESTADO CIVIL
$estado_civil = ['S' => '', 'C' => '', 'X' => '', 'D' => '', 'V' => ''];
$estado_civil[$datosAprendiz['estado_civil']] = $checked;

// DEFINIR GRADO DE INSTRUCCION
$grado_instruccion = ['BI' => '', 'BC' => '', 'MI' => '', 'MC' => '', 'SI' => '', 'SC' => ''];
$grado_instruccion[$datosAprendiz['nivel_instruccion']] = $checked;

// DEFINIR TURNO
$turno = 'Matutino';
if ($datosAprendiz['turno'] == 'V')
    $turno = 'Vespertino';

// DEFINIR NOMBRE FACILITADOR
$facilitador = $datosAprendiz['f_nombre1'];
if ($datosAprendiz['f_nombre2'] != null AND $datosAprendiz['f_nombre2'] != '')
    $facilitador .= ' '.$datosAprendiz['f_nombre2'];
$facilitador .= ' '.$datosAprendiz['f_apellido1'];
if ($datosAprendiz['apellido2'] != null AND $datosAprendiz['apellido2'] != '')
    $facilitador .= ' '.$datosAprendiz['apellido2'];

// DEFINIR AREA DE LA VIVIENDA
$tipo_area = ['U' => '', 'R' => ''];
$tipo_area[$datosVivienda['tipo_area']] = $checked;

// DEFINIR EL TIPO DE VIVIENDA
$tipo_vivienda = ['Q' => '', 'C' => '', 'A' => '', 'R' => '', 'O' => ''];
$tipo_vivienda[$datosVivienda['tipo_vivienda']] = $checked;

// DEFINIR EL ESTATUS DE PROPIEDAD DE LA VIVIENDA
$tenencia = ['P' => '', 'A' => '', 'E' => '', 'I' => ''];
$tenencia[$datosVivienda['tenencia_vivienda']] = $checked;

// DEFINIR EL ESTATUS DE PROPIEDAD DE LA VIVIENDA
$agua = ['A' => '', 'C' => '', 'P' => ''];
$agua[$datosVivienda['agua']] = $checked;

// DEFINIR EL ESTATUS DE PROPIEDAD DE LA VIVIENDA
$electricidad = ['L' => '', 'I' => ''];
$electricidad[$datosVivienda['electricidad']] = $checked;

// DEFINIR EL ESTATUS DE PROPIEDAD DE LA VIVIENDA
$excretas = ['C' => '', 'P' => '', 'L' => ''];
$excretas[$datosVivienda['excretas']] = $checked;

// DEFINIR EL ESTATUS DE PROPIEDAD DE LA VIVIENDA
$basura = ['A' => '', 'Q' => ''];
$basura[$datosVivienda['basura']] = $checked;

// OBTENER LOS INGRESOS Y LOS EGRESOS DEL DINERO DE LA FAMILIA
$arreglo_ingresos3  = [];
$total_ingresos     = 0;
$arreglo_egresos3   = [];
$total_egresos      = 0;
for ($i = 0; $i < count($datosDinero); $i++) {
    for ($j = 0; $j < count($arreglo_ingresos2); $j++) {
        if ($arreglo_ingresos2[$j] == $datosDinero[$i]['descripcion']) {
            $arreglo_ingresos3[] = number_format($datosDinero[$i]['cantidad'], 2).' Bs.S';
            $total_ingresos += $datosDinero[$i]['cantidad'];
        }
        if ($arreglo_egresos2[$j] == $datosDinero[$i]['descripcion']) {
            $arreglo_egresos3[] = number_format($datosDinero[$i]['cantidad'], 2).' Bs.S';
            $total_egresos += $datosDinero[$i]['cantidad'];
        }
    }
}

// DEFINIR SI HAY ENFERMOS EN LA FAMILIA.
$enfermos = ['S' => '', 'N' => ''];
$enfermos[$datosAprendiz['enfermos']] = $checked;
/////////////////////////////////////////////////
$data = '
    <h3 class="text-center" style="margin-bottom: 10px;"><span class="border-bottom">INFORME SOCIAL</span></h3>
    <h4 class="text-right" style="margin-bottom: 10px;">FECHA: '.substr($datosAprendiz['fecha'], 8, 2).' / '.substr($datosAprendiz['fecha'], 5, 2).' / '.substr($datosAprendiz['fecha'], 0, 4).'</h4>
    <p class="font12">I.- DATOS DEL CIUDADANO (A):</p>
    <table class="w-100" border="1">
        <tr>
            <td style="width: 35%; height: 50px;">
                <b>NOMBRES:</b><br>
                '.$datosAprendiz['nombre1'].' '.$datosAprendiz['nombre2'].'
            </td>
            <td style="width: 35%;">
                <b>APELLIDOS:</b><br>
                '.$datosAprendiz['apellido1'].' '.$datosAprendiz['apellido2'].'
            </td>
            <td style="width: 15%;">
                <b>C.I.:</b><br>
                '.$datosAprendiz['nacionalidad'].'-'.$datosAprendiz['cedula'].'
            </td>
            <td style="width: 15%;">
                <b>SEXO:</b><br>
                '.$sexo.'
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td style="height: 50px;">
                <b>LUGAR Y FECHA  DE NACIMIENTO:</b><br>
                '.$day.' de '.$arreglo_meses[intval($month)].' de '.$year.'<br>
                '.$datosAprendiz['lugar_n'].'
            </td>
            <td style="width: 8%;">
                <b>EDAD:</b><br>
                '.$edad.' Años.
            </td>
            <td style="width: 20%;">
                <b>NACIONALIDAD:</b><br>
                Venezolana
            </td>
            <td style="width: 25%;">
                <b>OCUPACION:</b><br>
                '.$datosAprendiz['ocupacion'].'
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-25">
                <p style="margin-bottom: 10px;"><b>ESTADO CIVIL:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative">SOLTERO (A) <span class="input-check" style="margin-left: 100px;">'.$estado_civil['S'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">CASADO (A) <span class="input-check"  style="margin-left: 107px;">'.$estado_civil['C'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">CONCUBINO (A) <span class="input-check" style="margin-left: 84.5px;">'.$estado_civil['X'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">DIVORCIADO (A) <span class="input-check" style="margin-left: 82px;">'.$estado_civil['D'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">VIUDO (A) <span class="input-check" style="margin-left: 120px;">'.$estado_civil['V'].'</span></div>
            </td>
            <td class="w-75">
                <p style="margin-bottom: 5px;"><b>GRADO DE INSTRUCCIÓN:</b></p>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. BASICA INCOMPLETA: <span class="input-check" style="margin-left: 100px;">'.$grado_instruccion['BI'].'</span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. MEDIA DIVERSIFICADA INCOMPLETA: <span class="input-check" style="margin-left: 37px;">'.$grado_instruccion['BC'].'</span></div><br>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. BASICA COMPLETA: <span class="input-check" style="margin-left: 112.5px;">'.$grado_instruccion['MI'].'</span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. MEDIA DIVERSIFICADA COMPLETA: <span class="input-check" style="margin-left: 49px;">'.$grado_instruccion['MC'].'</span></div><br>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. SUPERIOR INCOMPLETA: <span class="input-check" style="margin-left: 82px;">'.$grado_instruccion['SI'].'</span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">EDUC. SUPERIOR COMPLETA: <span class="input-check" style="margin-left: 120px;">'.$grado_instruccion['SC'].'</span></div>
                <p style="margin-top: 5px;"><b>TITULO:</b></p>
                <p class="border-bottom" style="height: 20px;">'.$datosAprendiz['titulo_acade'].'</p>
                <p style="margin-top: 5px;"><b>HA PARTICIPADO EN ALGUNA MISION. INDIQUE:</b></p>
                <p class="border-bottom" style="height: 20px;">'.$datosAprendiz['mision_participado'].'</p>
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-50" style="height: 50px;">
                <b>TELEFONO DE HABITACION:</b><br>
                '.$datosAprendiz['telefono1'].'
            </td>
            <td class="w-50">
                <b>TELEFONO CELULAR Y CORREO ELECTRÓNICO:</b><br>
                '.$datosAprendiz['telefono2'].'<br>
                '.$datosAprendiz['correo'].'
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td style="width: 35%; height: 50px;">
                <b>SALIDA OCUPACIONAL:</b><br>
                '.$datosAprendiz['oficio'].'
            </td>
            <td>
                <b>TURNO:</b><br>
                '.$turno.'
            </td>
            <td class="w-50">
                <b>NOMBRE DEL FACILITADOR:</b><br>
                '.$datosAprendiz['nacionalidad_fac'].'-'.$datosAprendiz['cedula_facilitador'].'<br>
                '.$facilitador.'
            </td>
        </tr>
    </table>
    <div style="page-break-before: always;"></div>
    <p class="font12">II.- UBICACIÓN GEOGRAFICA DE LA VIVIENDA:</p>
    <table class="w-100" border="1">
        <tr>
            <td class="w-50" style="height: 50px;">
                <b>DIRECCION EXACTA:</b><br>
                '.$datosAprendiz['direccion'].'
            </td>
            <td class="w-50">
                <b>PUNTO DE REFERENCIA:</b><br>
                '.$datosVivienda['punto_referencia'].'
            </td>
        </tr>
    </table>
    <table class="w-100" border="1">
        <tr>
            <td class="w-25" style="height: 50px;">
                <b>LOCALIDAD:</b><br>
                '.$datosAprendiz['ciudad'].' / '.$datosAprendiz['parroquia'].'
            </td>
            <td class="w-25">
                <b>MUNICIPIO:</b><br>
                '.$datosAprendiz['municipio'].'
            </td>
            <td class="w-25">
                <b>ESTADO:</b><br>
                '.$datosAprendiz['estado'].'
            </td>
            <td class="w-25">
                <p style="margin-bottom: 5px;"><b>AREA:</b></p>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">RURAL <span class="input-check">'.$tipo_area['U'].'</span></div>
                <div style="margin-bottom: 9px;" class="d-inline-block w-50 position-relative">URBANA <span class="input-check">'.$tipo_area['R'].'</span></div>
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
                <div style="margin-bottom: 9px;" class="position-relative">QUINTA <span class="input-check" style="margin-left: 90px;">'.$tipo_vivienda['Q'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">CASA <span class="input-check" style="margin-left: 102.5px;">'.$tipo_vivienda['C'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">APARTAMENTO <span class="input-check" style="margin-left: 44.3px;">'.$tipo_vivienda['A'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">RANCHO <span class="input-check" style="margin-left: 84px;">'.$tipo_vivienda['R'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">OTRO <span class="input-check" style="margin-left: 101.5px;">'.$tipo_vivienda['O'].'</span></div>
            </td>
            <td style="padding-top: 10px;">
                <div style="margin-bottom: 9px;" class="position-relative">PROPIA <span class="input-check" style="margin-left: 90px;">'.$tenencia['P'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">ALQUILADA <span class="input-check" style="margin-left: 68px;">'.$tenencia['A'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">PRESTADA <span class="input-check" style="margin-left: 70.5px;">'.$tenencia['E'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative">INVADIDA <span class="input-check" style="margin-left: 79px;">'.$tenencia['I'].'</span></div>
            </td>
            <td style="padding-top: 10px;">
                <p style="margin-bottom: 5px;"><b>AGUA:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">ACUEDUCTO <span class="input-check" style="margin-left: 30px;">'.$agua['A'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">CISTERNA <span class="input-check" style="margin-left: 30px;">'.$agua['C'].'</span></div><br>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">POZOS <span class="input-check" style="margin-left: 63.5px;">'.$agua['P'].'</span></div>
            
                <p style="margin-bottom: 5px; margin-top: 10px;"><b>ELECTRICIDAD:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">LEGAL <span class="input-check" style="margin-left: 68px;">'.$electricidad['L'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">ILEGAL <span class="input-check" style="margin-left: 49px;">'.$electricidad['I'].'</span></div>
            </td>
            <td style="padding-top: 10px;">
                <p style="margin-bottom: 5px;"><b>EXCRETAS:</b></p>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">CLOACAS <span class="input-check" style="margin-left: 38px;">'.$excretas['C'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">LETRINA <span class="input-check" style="margin-left: 45px;">'.$excretas['L'].'</span></div><br>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block w-50">POZO SEPTICO <span class="input-check" style="margin-left: 7px;">'.$excretas['P'].'</span></div>
            
                <p style="margin-bottom: 5px; margin-top: 10px;"><b>BASURA:</b></p>
                <div style="margin-bottom: 9px; width: 60%;" class="position-relative d-inline-block">ASEO URBANO <span class="input-check" style="margin-left: 9px;">'.$basura['A'].'</span></div>
                <div style="margin-bottom: 9px;" class="position-relative d-inline-block">QUEMA <span class="input-check" style="margin-left: 27px;">'.$basura['Q'].'</span></div>
                
                <p style="margin-top: 5px;"><b>OTROS:</b></p>
                <p class="border-bottom" style="height: 20px;">'.$datosVivienda['otros'].'</p>
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
                '.$datosVivienda['techo'].'
            </td>
            <td class="w-25">
                <b>PAREDES:</b><br>
                '.$datosVivienda['paredes'].'
            </td>
            <td class="w-25">
                <b>PISO:</b><br>
                '.$datosVivienda['piso'].'
            </td>
            <td class="w-25">
                <b>VIA DE ACCESO:</b><br>
                '.$datosVivienda['via_acceso'].'
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
                '.$datosVivienda['sala'].'
            </td>
            <td class="w-20">
                <b>COMEDOR:</b><br>
                '.$datosVivienda['comedor'].'
            </td>
            <td class="w-20">
                <b>COCINA:</b><br>
                '.$datosVivienda['cocina'].'
            </td>
            <td class="w-20>
                <b>BAÑOS:</b><br>
                '.$datosVivienda['banos'].'
            </td>
            <td class="w-20">
                <b>Nº DE DORMITORIOS:</b><br>
                '.$datosVivienda['n_dormitorios'].'
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
                <b>NOMBRE Y APELLIDO</b>
            </td>
            <td style="width: 50px;">
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
            <td style="width: 60px;">
                <b>TRABAJA</b>
            </td>
            <td style="width: 120px;">
                <b>INGRESO (Bs.S)</b>
            </td>
            <td style="width: 20px;">
                <b>RESPON.</b>
            </td>
        </tr>
    ';
    for ($i = 0; $i < 10; $i++) {
        $nombre_familiar = '';
        $edad_familiar = '';
        $sexo_familiar = '';
        $parentezco_familiar = '';
        $ocupacion_familiar = '';
        $trabaja_familiar = '';
        $ingresos_familiar = '';
        $responsable_familiar = '';
        if (isset($datosFamiliares[$i])) {
            // DEFINIR NOMBRE DEL FAMILIAR
            $nombre_familiar = $datosFamiliares[$i]['nombre1'];
            if ($datosFamiliares[$i]['nombre2'] != null AND $datosFamiliares[$i]['nombre2'] != '')
                $nombre_familiar .= ' '.$datosFamiliares[$i]['nombre2'];
            $nombre_familiar .= ' '.$datosFamiliares[$i]['apellido1'];
            if ($datosFamiliares[$i]['apellido2'] != null AND $datosFamiliares[$i]['apellido2'] != '')
                $nombre_familiar .= ' '.$datosFamiliares[$i]['apellido2'];

            // DEFINIR EDAD DEL FAMILIAR
            $edad_familiar   = 0;
            $year    = substr($datosFamiliares[$i]['fecha_n'], 0, 4);
            $month   = substr($datosFamiliares[$i]['fecha_n'], 5, 2);
            $day     = substr($datosFamiliares[$i]['fecha_n'], 8, 2);
            if ($year <= $yearA) {
                $edad_familiar = $yearA - $year;
                if ($month > $monthA) {
                    if ($edad_familiar != 0) 
                        $edad_familiar--;
                } else if ($month == $monthA) {
                    if ($day > $dayA)
                        if ($edad_familiar != 0) 
                            $edad_familiar--;
                }
            }
            $edad_familiar .= ' Años';

            $sexo_familiar = $datosFamiliares[$i]['sexo'];
            
            if ($sexo_familiar == 'M')
                $arreglo_parentezco = $dataParentescoM;
            else
                $arreglo_parentezco = $dataParentescoF;
            $parentezco_familiar = $arreglo_parentezco[$datosFamiliares[$i]['parentesco']];

            $ocupacion_familiar = $datosFamiliares[$i]['ocupacion'];

            if ($datosFamiliares[$i]['trabaja'] == 'S')
                $trabaja_familiar = $checked;
            else 
                $trabaja_familiar = $times;

            $ingresos_familiar = number_format($datosFamiliares[$i]['ingresos'], 2).' Bs.S';

            if ($datosAprendiz['representante'] == ($i + 1))
                $responsable_familiar = $checked;
        }
        $data .= '
        <tr>
            <td class="text-center" style="width: 20px; height: 30px;">
                '.($i + 1).'
            </td>
            <td>
                '.$nombre_familiar.'
            </td>
            <td class="text-center" style="width: 50px;">
                '.$edad_familiar.'
            </td>
            <td class="text-center" style="width: 20px;">
                '.$sexo_familiar.'
            </td>
            <td style="width: 100px;">
                '.$parentezco_familiar.'
            </td>
            <td style="width: 100px;">
                '.$ocupacion_familiar.'
            </td>
            <td class="text-center" style="width: 60px;">
                '.$trabaja_familiar.'
            </td>
            <td class="text-right" style="width: 120px;">
                '.$ingresos_familiar.'
            </td>
            <td class="text-center" style="width: 20px;">
                '.$responsable_familiar.'
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
            <td class="w-20 text-right">
                <b>'.$arreglo_ingresos3[$i].'</b>
            </td>
            <td>
                <b>'.$arreglo_egresos[$i].'</b>
            </td>
            <td class="w-20 text-right">
                <b>'.$arreglo_egresos3[$i].'</b>
            </td>
        </tr>';
    }
    $data .= '
        <tr>
            <td style="height: 40px;">
                <b>TOTAL INGRESOS:</b>
            </td>
            
            <td class="w-20 text-right">
                <b>'.number_format($total_ingresos, 2).' Bs.S</b>
            </td>
            <td>
                <b>TOTAL EGRESOS:</b>
            </td>
            <td class="w-20 text-right">
                <b>'.number_format($total_egresos, 2).' Bs.S</b>
            </td>
        </tr>
    </table>
    <p class="font12" style="margin-top: 20px;">VI.- SOLO PARA USO DE LA TRABAJADORA SOCIAL</p>
    <div class="descripcion-facilitador font12">
        <p class="font11"><b>CONDICIONES GENERALES DE LA VIVIENDA:</b></p>
        '.$datosAprendiz['condicion_vivienda'].'
    </div>
    <div class="descripcion-facilitador font12">
        <p class="font11"><b>CARACTERISTICAS GENERALES DE LAS RELACIONES FAMILIARES Y SUS CONDICIONES SOCIOECONOMICAS:</b></p>
        '.$datosAprendiz['caracteristicas_generales'].'
    </div>
    <div class="descripcion-facilitador font12">
        <p class="font11"><b>DIAGNOSTICO SOCIAL:</b></p>
        '.$datosAprendiz['diagnostico_social'].'
    </div>
    <div class="descripcion-facilitador font12">
        <p class="font11"><b>DIAGNOSTICO PRELIMINAR:</b></p>
        '.$datosAprendiz['diagnostico_preliminar'].'
    </div>
    <div class="descripcion-facilitador font12">
        <p class="font11"><b>CONCLUSIONES Y RECOMENDACIONES:</b></p>
        '.$datosAprendiz['conclusiones'].'
    </div>
    <b class="font11">AY ALGUN ENFERMO EN EL GRUPO FAMILIAR:</b>
    <div style="margin-bottom: 9px; width: 60px;" class="font11 d-inline-block position-relative">SI <span class="input-check" style="margin-left: 5px; margin-right: 7px;">'.$enfermos['S'].'</span></div>
    <div style="margin-bottom: 9px; width: 60px;" class="font11 d-inline-block position-relative">NO <span class="input-check" style="margin-left: 5px; margin-right: 7px;">'.$enfermos['N'].'</span></div>
';


/////////////////////////////////////////////////
require_once '../../library/dompdf/autoload.inc.php';
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
                text-align: center;
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
            <img src="../../images/reportes/logo-inces.jpg" style="width: 170px; height: 90px; margin-top: 15px;">
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
$dompdf->stream();
// $output = $dompdf->output()
// file_put_contents("../../pdf/".$nombre_documento, $output);
// echo $nombre_documento;
?>