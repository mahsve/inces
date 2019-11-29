<?php

require_once 'conexion.php';
class model_sesion extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_sesion ()
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

	// FUNCION PARA CONSULTAR LOS DATOS DE INICIO DE SESION.
	function consultarUsuario($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_usuario INNER JOIN t_datos_personales ON t_usuario.nacionalidad = t_datos_personales.nacionalidad AND t_usuario.cedula = t_datos_personales.cedula INNER JOIN t_rol ON t_usuario.codigo_rol = t_rol.codigo WHERE t_usuario.usuario='$datos[usuario]'"; // SENTENCIA.
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA BLOQUEAR EL USUARIO POR INTENTOS FALLIDOS.
	public function bloquearUsuario($datos)
	{
		$sentencia ="UPDATE t_usuario SET estatus='B' WHERE usuario='$datos[usuario]'"; // SENTENCIA.
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
	}
}