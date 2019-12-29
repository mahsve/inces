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
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='$datos[estado]' AND estatus='A'"; // SENTENTCIA
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
		$sentencia = "SELECT * FROM t_municipio WHERE codigo_estado='$datos[estado]' AND estatus='A'"; // SENTENTCIA
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
		$sentencia = "SELECT * FROM t_parroquia WHERE codigo_municipio='$datos[municipio]' AND estatus='A'"; // SENTENTCIA
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
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_personales(nacionalidad, cedula, nombre1, nombre2, apellido1, apellido2, sexo, fecha_n, lugar_n, codigo_ocupacion, estado_civil, nivel_instruccion, titulo_acade, mision_participado, codigo_ciudad, codigo_parroquia, direccion, telefono1, telefono2, correo, tipo_persona) 
        VALUES ($datos[nacionalidad], $datos[cedula], $datos[nombre_1], $datos[nombre_2], $datos[apellido_1], $datos[apellido_2], $datos[sexo], $datos[fecha_n], $datos[lugar_n], $datos[ocupacion], $datos[estado_civil], $datos[grado_instruccion], $datos[titulo], $datos[alguna_mision], $datos[ciudad], $datos[parroquia], $datos[direccion], $datos[telefono_1], $datos[telefono_2], $datos[correo], 'A')"; // SENTENTCIA
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
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_hogar (nacionalidad, cedula, punto_referencia, tipo_area, tipo_vivienda, tenencia_vivienda, agua, electricidad, excretas, basura, otros, techo, paredes, piso, via_acceso, sala, comedor, cocina, banos, n_dormitorios) 
        VALUES ($datos[nacionalidad], $datos[cedula], $datos[punto_referencia], $datos[area], $datos[tipo_vivienda], $datos[tenencia_vivienda], $datos[tipo_agua], $datos[tipo_electricidad], $datos[tipo_excreta], $datos[tipo_basura], $datos[otros], $datos[techo], $datos[pared], $datos[piso], $datos[via_acceso], $datos[sala], $datos[comedor], $datos[cocina], $datos[bano], $datos[dormitorio])"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA FICHA DEL APRENDIZ.
    public function registrarFichaAprendiz($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_informe_social (fecha, nacionalidad_aprendiz, cedula_aprendiz, codigo_oficio, turno, cedula_facilitador, condicion_vivienda, caracteristicas_generales, diagnostico_social, diagnostico_preliminar, conclusiones, enfermos) 
        VALUES ($datos[fecha], $datos[nacionalidad], $datos[cedula], $datos[oficio], $datos[turno], '25791966', $datos[condicion_vivienda], $datos[caracteristicas_generales], $datos[diagnostico_social], $datos[diagnostico_preliminar], $datos[conclusiones], $datos[enfermos])"; // SENTENTCIA
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
        $sentencia = "INSERT INTO t_familia (numero_informe, nombre1, nombre2, apellido1, apellido2, fecha_n, sexo, parentesco, codigo_ocupacion, trabaja, ingresos, representante) 
        VALUES ($datos[id_ficha], $datos[nombre_familiar], $datos[nombre_familiar], $datos[nombre_familiar], $datos[nombre_familiar], $datos[fecha_familiar], $datos[sexo_familiar], $datos[parentesco_familiar], $datos[ocupacion_familiar], $datos[trabaja_familiar], $datos[ingresos_familiar], $datos[responsable])"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarInformeSocial()
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT t_informe_social.*, t_datos_personales.*, t_oficio.nombre AS oficio, t_ciudad.codigo_estado, t_parroquia.codigo_municipio FROM t_informe_social INNER JOIN t_datos_personales ON t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula INNER JOIN t_oficio ON t_informe_social.codigo_oficio = t_oficio.codigo INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo LEFT JOIN t_parroquia ON t_datos_personales.codigo_parroquia = t_parroquia.codigo"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarDatosVivienda($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_datos_hogar WHERE nacionalidad=$datos[nacionalidad] AND cedula=$datos[cedula]"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarFamiliares($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT * FROM t_familia WHERE numero_informe=$datos[informe]"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
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