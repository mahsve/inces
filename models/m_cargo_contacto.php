<?php
require_once 'conexion.php';
class model_cargo_contacto extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_cargo_contacto () {
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

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO.
    public function confirmarExistenciaR ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_cargo
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UNA NUEVA CARGO DE LA PERSONA DE CONTACTO.
    public function registrarCargoContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_cargo (
            nombre
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS CARGO REGISTRADOS.
	public function consultarCargosContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_cargo
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

    // FUNCION PARA CONSULTAR EL NUMERO DE CARGO REGISTRADOS EN TOTAL.
	public function consultarCargosContactoTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_cargo
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado;
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO CON OTRO CODIGO.
    public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_cargo
            WHERE codigo!='".htmlspecialchars($datos['codigo'])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LOS DATOS DE UN CARGO EXISTENTE.
    public function modificarCargoContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_cargo SET
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            WHERE codigo=".$datos['codigo']."
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA OCUPACION.
    public function estatusCargoContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_cargo
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