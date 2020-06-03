<?php
require_once 'conexion.php';
class model_modulo_oficio extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_modulo_oficio () {
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

    // FUNCION PARA CONSULTAR LOS OFICIOS REGISTRADOS Y MOSTRARLOS EN EL SELECT PARA REGISTRAR NUEVOS MODULOS.
	public function consultarOficios () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO,
    public function confirmarExistenciaR ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE codigo='".strtoupper(htmlspecialchars($datos['codigo']))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }
    
    // FUNCION PARA REGISTRAR UN NUEVO MODULO DE UN OFICIO.
    public function registrarModulo ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_modulo (
            codigo,
            nombre,
            codigo_oficio
        ) VALUES (
            '".strtoupper(htmlspecialchars($datos['codigo']))."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."',
            '".htmlspecialchars($datos['oficio'])."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS MODULOS DE LOS OFICIOS REGISTRADOS.
	public function consultarModulos ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT t_modulo.*, t_oficio.nombre AS oficio
            FROM t_modulo
            INNER JOIN t_oficio ON t_modulo.codigo_oficio = t_oficio.codigo
            WHERE ( t_modulo.nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                    t_modulo.codigo LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_modulo.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR EL TOTAL DE LOS MODULOS.
    public function consultarTotalPorModulo ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_modulo
            WHERE ( nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                    codigo LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado;
    }

    // FUNCION PARA MODIFICAR UN MODULO DE UN OFICIO.
    public function modificarModulo($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_modulo SET
            codigo='".strtoupper(htmlspecialchars($datos['codigo']))."',
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            WHERE codigo='".htmlspecialchars($datos['codigo2'])."'
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UN MODULO
    public function estatusModulo($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_modulo
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