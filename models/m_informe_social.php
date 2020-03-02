<?php
require_once 'conexion.php';
class model_informeSocial extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_informeSocial ()
    {
        $this->conexion(); // SE DEFINE LA CLASE PARA HEREDAR SUS ATRIBUTOS.
    }

    // FUNCION PARA ABRIR CONEXION.
    public function conectar ()
    {
        $datos = $this->obtenerDatos(); // OBTENEMOS LOS DATOS DE CONEXION.
        $this->data_conexion = mysqli_connect($datos['local'], $datos['user'], $datos['password'], $datos['database']); // SE CREA LA CONEXION A LA BASE DE DATOS.
    }

    // FUNCION PARA CERRAR CONEXION.
    public function desconectar ()
    {
        mysqli_close($this->data_conexion);
    }

    // FUNCION PARA CONSULTAR LAS OCUPACIONES
	public function consultarOcupaciones()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ocupacion WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS OFICIOS.
	public function consultarOficios()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS ESTADOS.
	public function consultarEstados()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_estado WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES SEGUN EL ESTADO ELEGIDO.
	public function consultarCiudades($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS MUNICIPIOS SEGUN EL ESTADO ELEGIDO.
	public function consultarMunicipios($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_municipio WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS PARROQUIAS SEGUN EL MUNICIPIO ELEGIDO.
	public function consultarParroquias($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_parroquia WHERE codigo_municipio='".$datos['municipio']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS FACILITADORES ACTIVOS.
	public function consultarFacilitadores($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
        FROM t_datos_personales
        WHERE ( concat(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['buscar']."%' OR 
                concat(nombre1, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['buscar']."%' OR
                concat(nacionalidad, ' ', cedula) LIKE '%".$datos['buscar']."%' OR 
                concat(nacionalidad, cedula) LIKE '%".$datos['buscar']."%' OR
                concat(nacionalidad, '-', cedula) LIKE '%".$datos['buscar']."%' )
        AND tipo_persona='F'
        AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LOS DATOS PERSONALES DEL APRENDIZ.
    public function registrarDatosPersonales($datos)
    {
        $resultado = false;
        $sentencia = "INSERT INTO t_datos_personales(
            nacionalidad,
            cedula,
            nombre1,
            nombre2,
            apellido1,
            apellido2,
            sexo,
            fecha_n,
            lugar_n,
            codigo_ocupacion,
            estado_civil,
            nivel_instruccion,
            titulo_acade,
            mision_participado,
            codigo_ciudad,
            codigo_parroquia,
            direccion,
            telefono1,
            telefono2,
            correo,
            tipo_persona
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".htmlspecialchars($datos['nombre_1'])."',
            '".htmlspecialchars($datos['nombre_2'])."',
            '".htmlspecialchars($datos['apellido_1'])."',
            '".htmlspecialchars($datos['apellido_2'])."',
            '".htmlspecialchars($datos['sexo'])."',
            '".htmlspecialchars($datos['fecha_n'])."',
            '".htmlspecialchars($datos['lugar_n'])."',
            '".htmlspecialchars($datos['ocupacion'])."',
            '".htmlspecialchars($datos['estado_civil'])."',
            '".htmlspecialchars($datos['grado_instruccion'])."',
            '".htmlspecialchars($datos['titulo'])."',
            '".htmlspecialchars($datos['alguna_mision'])."',
            '".htmlspecialchars($datos['ciudad'])."',
            '".htmlspecialchars($datos['parroquia'])."',
            '".htmlspecialchars($datos['direccion'])."',
            '".htmlspecialchars($datos['telefono_1'])."',
            '".htmlspecialchars($datos['telefono_2'])."',
            '".htmlspecialchars($datos['correo'])."',
            'B'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LOS DATOS DE LA VIVIENDA DEL APRENDIZ.
    public function registrarDatosVivienda($datos)
    {
        $resultado = false;
        $sentencia = "INSERT INTO t_datos_hogar (
            nacionalidad,
            cedula,
            punto_referencia,
            tipo_area,
            tipo_vivienda,
            tenencia_vivienda,
            agua,
            electricidad,
            excretas,
            basura,
            otros,
            techo,
            paredes,
            piso,
            via_acceso,
            sala,
            comedor,
            cocina,
            banos,
            n_dormitorios
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".htmlspecialchars($datos['punto_referencia'])."',
            '".htmlspecialchars($datos['area'])."',
            '".htmlspecialchars($datos['tipo_vivienda'])."',
            '".htmlspecialchars($datos['tenencia_vivienda'])."',
            '".htmlspecialchars($datos['tipo_agua'])."',
            '".htmlspecialchars($datos['tipo_electricidad'])."',
            '".htmlspecialchars($datos['tipo_excreta'])."',
            '".htmlspecialchars($datos['tipo_basura'])."',
            '".htmlspecialchars($datos['otros'])."',
            '".htmlspecialchars($datos['techo'])."',
            '".htmlspecialchars($datos['pared'])."',
            '".htmlspecialchars($datos['piso'])."',
            '".htmlspecialchars($datos['via_acceso'])."',
            '".htmlspecialchars($datos['sala'])."',
            '".htmlspecialchars($datos['comedor'])."',
            '".htmlspecialchars($datos['cocina'])."',
            '".htmlspecialchars($datos['bano'])."',
            '".htmlspecialchars($datos['dormitorio'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA FICHA DEL APRENDIZ.
    public function registrarInformeSocial($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_informe_social (
            fecha,
            nacionalidad_aprendiz,
            cedula_aprendiz,
            codigo_oficio,
            turno,
            nacionalidad_fac,
            cedula_facilitador,
            condicion_vivienda,
            caracteristicas_generales,
            diagnostico_social,
            diagnostico_preliminar,
            conclusiones,
            enfermos,
            representante
        ) VALUES (
            '".htmlspecialchars($datos['fecha'])."',
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".htmlspecialchars($datos['oficio'])."',
            '".htmlspecialchars($datos['turno'])."',
            '".htmlspecialchars($datos['f_nacionalidad'])."',
            '".htmlspecialchars($datos['f_cedula'])."',
            '".htmlspecialchars($datos['condicion_vivienda'])."',
            '".htmlspecialchars($datos['caracteristicas_generales'])."',
            '".htmlspecialchars($datos['diagnostico_social'])."',
            '".htmlspecialchars($datos['diagnostico_preliminar'])."',
            '".htmlspecialchars($datos['conclusiones'])."',
            '".htmlspecialchars($datos['enfermos'])."',
            '".htmlspecialchars($datos['responsable_apre'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = mysqli_insert_id ($this->data_conexion);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LOS DATOS DEL LOS FAMILIARES DEL APRENDIZ.
    public function registrarFamilares($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_familia (
            numero_informe,
            nombre1,
            nombre2,
            apellido1,
            apellido2,
            fecha_n,
            sexo,
            parentesco,
            codigo_ocupacion,
            trabaja,
            ingresos
        ) VALUES (
            '".htmlspecialchars($datos['id_ficha'])."',
            '".htmlspecialchars($datos['nombre_familiar1'])."',
            '".htmlspecialchars($datos['nombre_familiar2'])."',
            '".htmlspecialchars($datos['apellido_familiar1'])."',
            '".htmlspecialchars($datos['apellido_familiar2'])."',
            '".htmlspecialchars($datos['fecha_familiar'])."',
            '".htmlspecialchars($datos['sexo_familiar'])."',
            '".htmlspecialchars($datos['parentesco_familiar'])."',
            '".htmlspecialchars($datos['ocupacion_familiar'])."',
            '".htmlspecialchars($datos['trabaja_familiar'])."',
            '".htmlspecialchars($datos['ingresos_familiar'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LOS INGRESOS Y EGRESOS DE LA FAMILIA.
    public function registrarGestionDinero($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_gestion_dinero (
            numero_informe,
            descripcion,
            cantidad
        ) VALUES (
            '".htmlspecialchars($datos['id_ficha'])."',
            '".htmlspecialchars($datos['descripcion'])."',
            '".htmlspecialchars($datos['cantidad'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS APRENDICES REGISTRADOS Y MOSTRARLOS EN UNA LISTA.
    public function consultarInformeSocial($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
            t_informe_social.*, t_informe_social.estatus AS estatus_informe,
            t_datos_personales.*,
            datos_facilitador.nombre1 AS f_nombre1, datos_facilitador.nombre2 AS f_nombre2,
            datos_facilitador.apellido1 AS f_apellido1, datos_facilitador.apellido2 AS f_apellido2,
            t_oficio.nombre AS oficio,
            t_ciudad.codigo_estado,
            t_parroquia.codigo_municipio
            FROM t_informe_social
            INNER JOIN t_datos_personales ON
            t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad
            AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula
            INNER JOIN t_datos_personales datos_facilitador ON
            t_informe_social.nacionalidad_fac = datos_facilitador.nacionalidad
            AND t_informe_social.cedula_facilitador = datos_facilitador.cedula
            INNER JOIN t_oficio ON
            t_informe_social.codigo_oficio = t_oficio.codigo
            INNER JOIN t_ciudad ON
            t_datos_personales.codigo_ciudad = t_ciudad.codigo
            LEFT JOIN t_parroquia ON
            t_datos_personales.codigo_parroquia = t_parroquia.codigo
            WHERE ( concat(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo']."%' OR 
                    concat(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo']."%' OR
                    concat(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".$datos['campo']."%' OR 
                    concat(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".$datos['campo']."%' OR
                    concat(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".$datos['campo']."%'
            ) AND t_informe_social.estatus LIKE '%".$datos['estatus']."%' 
            ORDER BY ".$datos['ordenar_por']." 
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL TOTAL DE LOS APRENDICES REGISTRADOS Y REALIZAR LA PAGINACION.
    public function consultarInformeSocialTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_informe_social
            INNER JOIN t_datos_personales ON
            t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad
            AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula
            WHERE ( concat(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo']."%' OR 
                    concat(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo']."%' OR
                    concat(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".$datos['campo']."%' OR 
                    concat(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".$datos['campo']."%' OR
                    concat(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".$datos['campo']."%'
            ) AND t_informe_social.estatus LIKE '%".$datos['estatus']."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS DATOS DE LA VIVIENDA DE UN APRENDIZ EN CONCRETO.
    public function consultarDatosVivienda($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_datos_hogar
            WHERE nacionalidad='".$datos['nacionalidad']."'
            AND cedula='".$datos['cedula']."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR A LOS FAMILIARES DEL APRENDIZ CONSULTADO.
    public function consultarFamiliares($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
            t_familia.*,
            t_ocupacion.nombre AS ocupacion
            FROM t_familia
            INNER JOIN t_ocupacion ON t_familia.codigo_ocupacion = t_ocupacion.codigo
            WHERE numero_informe='".$datos['informe']."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS INGRESOS Y LOS EGRESOS DE LA FAMILIA DEL APRENDIZ.
    public function consultarDinero($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_gestion_dinero
            WHERE numero_informe='".$datos['informe']."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LOS DATOS PERSONALES DEL APRENDIZZ
    public function modificarDatosPersonales($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            nacionalidad='".htmlspecialchars($datos['nacionalidad'])."',
            cedula='".htmlspecialchars($datos['cedula'])."',
            nombre1='".htmlspecialchars($datos['nombre_1'])."',
            nombre2='".htmlspecialchars($datos['nombre_2'])."',
            apellido1='".htmlspecialchars($datos['apellido_1'])."',
            apellido2='".htmlspecialchars($datos['apellido_2'])."',
            sexo='".htmlspecialchars($datos['sexo'])."',
            fecha_n='".htmlspecialchars($datos['fecha_n'])."',
            lugar_n='".htmlspecialchars($datos['lugar_n'])."',
            codigo_ocupacion='".htmlspecialchars($datos['ocupacion'])."',
            estado_civil='".htmlspecialchars($datos['estado_civil'])."',
            nivel_instruccion='".htmlspecialchars($datos['grado_instruccion'])."',
            titulo_acade='".htmlspecialchars($datos['titulo'])."',
            mision_participado='".htmlspecialchars($datos['alguna_mision'])."',
            codigo_ciudad='".htmlspecialchars($datos['ciudad'])."',
            codigo_parroquia='".htmlspecialchars($datos['parroquia'])."',
            direccion='".htmlspecialchars($datos['direccion'])."',
            telefono1='".htmlspecialchars($datos['telefono_1'])."',
            telefono2='".htmlspecialchars($datos['telefono_2'])."',
            correo='".htmlspecialchars($datos['correo'])."'
            WHERE nacionalidad='".$datos['nacionalidad_v']."' AND cedula='".$datos['cedula_v']."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LOS DATOS DE LA VIVIENDA DEL APRENDIZ.
    public function modificarDatosVivienda($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_hogar SET 
            punto_referencia='".htmlspecialchars($datos['punto_referencia'])."',
            tipo_area='".htmlspecialchars($datos['area'])."',
            tipo_vivienda='".htmlspecialchars($datos['tipo_vivienda'])."',
            tenencia_vivienda='".htmlspecialchars($datos['tenencia_vivienda'])."',
            agua='".htmlspecialchars($datos['tipo_agua'])."',
            electricidad='".htmlspecialchars($datos['tipo_electricidad'])."',
            excretas='".htmlspecialchars($datos['tipo_excreta'])."',
            basura='".htmlspecialchars($datos['tipo_basura'])."',
            otros='".htmlspecialchars($datos['otros'])."',
            techo='".htmlspecialchars($datos['techo'])."',
            paredes='".htmlspecialchars($datos['pared'])."',
            piso='".htmlspecialchars($datos['piso'])."',
            via_acceso='".htmlspecialchars($datos['via_acceso'])."',
            sala='".htmlspecialchars($datos['sala'])."',
            comedor='".htmlspecialchars($datos['comedor'])."',
            cocina='".htmlspecialchars($datos['cocina'])."',
            banos='".htmlspecialchars($datos['bano'])."',
            n_dormitorios='".htmlspecialchars($datos['dormitorio'])."'
            WHERE nacionalidad='".$datos['nacionalidad']."' AND cedula='".$datos['cedula']."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LA FICHA DEL APRENDIZ.
    public function modificarInformeSocial($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_informe_social SET 
            fecha='".htmlspecialchars($datos['fecha'])."',
            codigo_oficio='".htmlspecialchars($datos['oficio'])."',
            turno='".htmlspecialchars($datos['turno'])."',
            nacionalidad_fac='".htmlspecialchars($datos['f_nacionalidad'])."',
            cedula_facilitador='".htmlspecialchars($datos['f_cedula'])."',
            condicion_vivienda='".htmlspecialchars($datos['condicion_vivienda'])."',
            caracteristicas_generales='".htmlspecialchars($datos['caracteristicas_generales'])."',
            diagnostico_social='".htmlspecialchars($datos['diagnostico_social'])."',
            diagnostico_preliminar='".htmlspecialchars($datos['diagnostico_preliminar'])."',
            conclusiones='".htmlspecialchars($datos['conclusiones'])."',
            enfermos='".htmlspecialchars($datos['enfermos'])."',
            representante='".htmlspecialchars($datos['responsable_apre'])."'
            WHERE numero='".$datos['informe_social']."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LOS DATOS DE LA FAMILIA DEL APRENDIZ
    public function modificarFamiliaresAprendiz($datos){
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_familia SET
            nombre1='".htmlspecialchars($datos['nombre_familiar1'])."',
            nombre2='".htmlspecialchars($datos['nombre_familiar2'])."',
            apellido1='".htmlspecialchars($datos['apellido_familiar1'])."',
            apellido2='".htmlspecialchars($datos['apellido_familiar2'])."',
            fecha_n='".htmlspecialchars($datos['fecha_familiar'])."',
            sexo='".htmlspecialchars($datos['sexo_familiar'])."',
            parentesco='".htmlspecialchars($datos['parentesco_familiar'])."',
            codigo_ocupacion='".htmlspecialchars($datos['ocupacion_familiar'])."',
            trabaja='".htmlspecialchars($datos['trabaja_familiar'])."',
            ingresos='".htmlspecialchars($datos['ingresos_familiar'])."'
            WHERE id_familiar='".$datos['id_familia']."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion, $sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA ELIMINAR UN FAMILIAR DE LA LISTA DE LA FICHA DEL APRENDIZ
    public function eliminarFamilia ($id) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_familia WHERE id_familiar='".$id."'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA ELIMINAR LOS DATOS DE LOS INGRESOS Y REGISTRAR LOS NUEVOS (ACTUALIZADOS).
    public function eliminarGestionDinero($id)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_gestion_dinero WHERE numero_informe='".$id."'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusAprendiz($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_informe_social SET
            estatus='".$datos['estatus']."'
            WHERE numero='".$datos['numero']."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA OBTENER LOS DATOS DEL APRENDIZ Y MOSTRARLO EN EL PDF.
    public function datosAprendizPDF($numero) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
            t_informe_social.*, t_informe_social.estatus AS estatus_informe,
            t_datos_personales.*,
            datos_facilitador.nombre1 AS f_nombre1, datos_facilitador.nombre2 AS f_nombre2,
            datos_facilitador.apellido1 AS f_apellido1, datos_facilitador.apellido2 AS f_apellido2,
            t_ocupacion.nombre AS ocupacion,
            t_oficio.nombre AS oficio,
            t_ciudad.nombre AS ciudad,
            t_estado.nombre AS estado,
            t_parroquia.nombre AS parroquia,
            t_municipio.nombre AS municipio
            FROM t_informe_social
            INNER JOIN t_datos_personales ON
            t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad
            AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula
            INNER JOIN t_datos_personales datos_facilitador ON
            t_informe_social.nacionalidad_fac = datos_facilitador.nacionalidad
            AND t_informe_social.cedula_facilitador = datos_facilitador.cedula
            INNER JOIN t_ocupacion ON
            t_datos_personales.codigo_ocupacion = t_ocupacion.codigo
            INNER JOIN t_oficio ON
            t_informe_social.codigo_oficio = t_oficio.codigo
            INNER JOIN t_ciudad ON
            t_datos_personales.codigo_ciudad = t_ciudad.codigo
            INNER JOIN t_estado ON
            t_ciudad.codigo_estado = t_estado.codigo
            LEFT JOIN t_parroquia ON
            t_datos_personales.codigo_parroquia = t_parroquia.codigo
            LEFT JOIN t_municipio ON
            t_parroquia.codigo_municipio = t_municipio.codigo
            WHERE t_informe_social.numero='".$numero."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA EMPEZAR NUEVA TRANSACCION.
    public function nuevaTransaccion()
    {
		mysqli_query($this->data_conexion,"START TRANSACTION");
    }

    // FUNCION PARA GUARDAR LOS CAMBIOS DE LA TRANSACCION.
    public function guardarTransaccion()
    {
		mysqli_query($this->data_conexion,"COMMIT");
    }
    
    // FUNCION PARA DESHACER TODA LA TRANSACCION.
    public function calcelarTransaccion()
    {
		mysqli_query($this->data_conexion,"ROLLBACK");
    }
}