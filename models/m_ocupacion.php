<?php
require_once 'conexion.php';
class model_ocupacion extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_ocupacion ()
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

    // FUNCION PARA REGISTRAR UNA NUEVA OCUPACION.
    public function registrarOcupacion($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_ocupacion (
            nombre
        ) VALUES (
            ".$datos['nombre']."
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODAS LA OCUPACIONES REGISTRADAS.
	public function consultarOcupaciones($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre LIKE '%".$datos['campo']."%'
            AND estatus LIKE '%".$datos['estatus']."%' 
            ORDER BY ".$datos['ordenar_por']." 
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OCUPACIONES REGISTRADAS EN TOTAL.
	public function consultarOcupacionesTotal($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre LIKE '%".$datos['campo']."%'
            AND estatus LIKE '%".$datos['estatus']."%'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
   
    // FUNCION PARA MODIFICAR UNA OCUPACION EXISTENTE.
    public function modificarOcupacion($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_ocupacion SET
            nombre=".$datos['nombre']."
            WHERE codigo=".$datos['codigo']."
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA OCUPACION.
    public function estatusOcupacion($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_ocupacion SET
            estatus=".$datos['estatus']."
            WHERE codigo=".$datos['codigo']."
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
}