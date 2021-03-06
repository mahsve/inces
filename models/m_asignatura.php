<?php
require_once 'conexion.php';
class model_asignatura extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_asignatura () {
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
            FROM t_asignatura
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA ASIGNATURA.
    public function registrarAsignatura ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_asignatura (
            nombre
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS ASIGNATURAS REGISTRADOS
    public function consultarAsignaturas ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_asignatura
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) { // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
            $resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE ASIGNATURAS REGISTRADOS EN TOTAL
    public function consultarAsignaturasTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_asignatura
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_asignatura
            WHERE codigo!='".htmlspecialchars($datos['codigo'])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    
    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function modificarAsignatura($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_asignatura SET 
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."'
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusAsignatura ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_asignatura
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