<?php
require_once 'conexion.php';
class model_usuario extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_usuario () {
        $this->conexion(); // SE DEFINE LA CLASE PARA HEREDAR SUS ATRIBUTOS.
    }

    // FUNCION PARA ABRIR CONEXION.
    public function conectar () {
        $datos = $this->obtenerDatos(); // OBTENEMOS LOS DATOS DE CONEXION.
        $this->data_conexion = mysqli_connect($datos['local'], $datos['user'], $datos['password'], $datos['database']); // SE CREA LA CONEXION A LA BASE DE DATOS.
        mysqli_query($this->data_conexion, "SET NAMES 'utf8'");
    }

    // FUNCION PARA CERRAR CONEXION.
    public function desconectar () {
        mysqli_close($this->data_conexion);
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarUsuarios($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT t_usuario.*, t_datos_personales.*, t_rol.nombre AS rol
            FROM t_usuario
            INNER JOIN t_datos_personales ON t_usuario.nacionalidad = t_datos_personales.nacionalidad AND t_usuario.cedula = t_datos_personales.cedula
            INNER JOIN t_rol ON t_usuario.codigo_rol = t_rol.codigo
            WHERE usuario LIKE '%".$datos['campo']."%'
            AND estatus LIKE '%".$datos['estatus']."%'
            AND usuario != '".$datos['usuario']."'
            ORDER BY '".$datos['ordenar_por']."'
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
    public function consultarUsuariosTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_usuario
            WHERE usuario LIKE '%".$datos['campo']."%'
            AND estatus LIKE '%".$datos['estatus']."%'
            AND usuario != '".$datos['usuario']."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
}