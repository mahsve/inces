<?php
require_once 'conexion.php';
class model_empresa extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_empresa ()
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

    // FUNCION PARA CONSULTAR LAS EMPRESAS
	function consultarEmpresas()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_empresa"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS ESTADOS
	function consultarEstados()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_estado"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO EN ESPECIFICO
	function consultarCiudades($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='".$datos['estado']."'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS ACTIVIDADES ECONOMICAS.
    function consultarActividades()
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_actividad_economica"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    function registrarPersonaContacto($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_personales (nacionalidad, cedula, nombre1, nombre2, apellido1, apellido2, sexo, codigo_ciudad, telefono1, tipo_persona) VALUES ('".$datos['nacionalidad']."', '".$datos['cedula']."', '".$datos['nombre_1']."', '".$datos['nombre_2']."', '".$datos['apellido_1']."', '".$datos['apellido_2']."', '".$datos['sexo']."', '".$datos['ciudad_c']."', '".$datos['telefono']."', 'C')"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    function registrarEmpresa($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_empresa (rif, nil, razon_social, codigo_actividad_e, codigo_aportante, telefono1, telefono2, codigo_ciudad, direccion, nacionalidad_contacto, persona_contacto) VALUES ('".$datos['rif']."', '".$datos['nil']."', '".$datos['razon_social']."', '".$datos['actividad_economica']."', '".$datos['codigo_aportante']."', '".$datos['telefono_1']."', '".$datos['telefono_2']."', '".$datos['ciudad']."', '".$datos['direccion']."', '".$datos['nacionalidad']."', '".$datos['cedula']."')"; // SENTENTCIA
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