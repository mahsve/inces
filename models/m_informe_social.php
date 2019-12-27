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

    // FUNCION PARA CONSULTAR
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

    // FUNCION PARA CONSULTAR
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

    // FUNCION PARA CONSULTAR
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

    // FUNCION PARA CONSULTAR
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

    // FUNCION PARA CONSULTAR
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

    // FUNCION PARA CONSULTAR
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