<?php
require_once 'conexion.php';
class model_actividad_economica extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_actividad_economica () {
        $this->conexion(); // SE DEFINE LA CLASE PARA HEREDAR SUS ATRIBUTOS.
    }

    // FUNCION PARA ABRIR CONEXION.
    public function conectar () {
        $datos = $this->obtenerDatos(); // OBTENEMOS LOS DATOS DE CONEXION.
        $this->data_conexion = mysqli_connect($datos['local'], $datos['user'], $datos['password'], $datos['database']); // SE CREA LA CONEXION A LA BASE DE DATOS.
    }

    // FUNCION PARA CERRAR CONEXION.
    public function desconectar () {
        mysqli_close($this->data_conexion);
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO,
    public function confirmarExistenciaR ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_actividad_economica
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"]), `UTF-8`))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UNA NUEVA ACTIVIDAD ECONOMICA.
    public function registrarActividadEconomica($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_actividad_economica (
            nombre
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"]), `UTF-8`))."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODAS LAS ACTIVIDADES ECONOMICAS REGISTRADAS.
	public function consultarActividadesEconomicas($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_actividad_economica
            WHERE nombre LIKE '%".htmlspecialchars($datos["campo"])."%'
            AND estatus LIKE '%".htmlspecialchars($datos["estatus"])."%' 
            ORDER BY ".$datos['ordenar_por']."
            LIMIT ".htmlspecialchars($datos["numero"]).", ".htmlspecialchars($datos["cantidad"])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) { // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE ACTIVIDADES ECONOMICAS REGISTRADAS EN TOTAL.
	public function consultarActividadesEconomicasTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_actividad_economica
            WHERE nombre LIKE '%".htmlspecialchars($datos["campo"])."%'
            AND estatus LIKE '%".htmlspecialchars($datos["estatus"])."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_actividad_economica
            WHERE codigo!='".htmlspecialchars($datos["codigo"])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"]), `UTF-8`))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LOS DATOS DE UNA ACTIVIDAD ECONOMICA EXISTENTE.
    public function modificarActividadEconomica($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_actividad_economica
            SET nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"]), `UTF-8`))."'
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusActividadEconomica($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_actividad_economica
            SET estatus='".htmlspecialchars($datos["estatus"])."'
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
}