<?php
require_once 'conexion.php';
class model_ocupacion extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_ocupacion () {
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

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO,
    public function confirmarExistenciaR ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            AND formulario='".htmlspecialchars($datos["c_formulario"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UNA NUEVA OCUPACION.
    public function registrarOcupacion ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_ocupacion (
            nombre,
            formulario
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."',
            '".htmlspecialchars($datos['c_formulario'])."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODAS LA OCUPACIONES REGISTRADAS.
	public function consultarOcupaciones ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OCUPACIONES REGISTRADAS EN TOTAL.
	public function consultarOcupacionesTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado;
    }

    public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE codigo!='".htmlspecialchars($datos['codigo'])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            AND formulario='".htmlspecialchars($datos['c_formulario'])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
   
    // FUNCION PARA MODIFICAR UNA OCUPACION EXISTENTE.
    public function modificarOcupacion ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_ocupacion SET
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."',
            formulario='".htmlspecialchars($datos['c_formulario'])."'
            WHERE codigo=".$datos['codigo']."
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA OCUPACION.
    public function estatusOcupacion ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_ocupacion
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE codigo='".htmlspecialchars($datos['codigo'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
}