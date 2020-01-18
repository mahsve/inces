<?php
require_once 'conexion.php';
class model_usuario extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_usuario ()
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

    // FUNCION PARA CONSULTAR Y MOSTRAR UNA LISTAS DE LOS USUARIO REGISTRADOS.
	function consultarUsuario()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_usuario.usuario, t_usuario.estatus, t_rol.nombre, t_datos_personales.* FROM t_usuario INNER JOIN t_datos_personales ON t_usuario.nacionalidad = t_datos_personales.nacionalidad AND t_usuario.cedula = t_datos_personales.cedula INNER JOIN t_rol ON t_usuario.codigo_rol = t_rol.codigo"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA TRAER LOS PERMISOS SOBRE LA VISTA
    public function permisos($datos)
    {
        $this->conectar();
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT td_rol_vista.* FROM td_rol_vista INNER JOIN t_vista ON td_rol_vista.codigo_vista = t_vista.codigo WHERE t_vista.enlace LIKE '%".$datos['vista']."%'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
        $this->desconectar();
        return $resultado; // RETORNAMOS LOS DATOS.
    }
}