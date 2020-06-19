<?php
require_once 'conexion.php';
class model_oficio extends conexion{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_oficio () {
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
            FROM t_oficio
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UN NUEVO OFICIO.
    public function registrarOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_oficio (
            nombre
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = mysqli_insert_id($this->data_conexion);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // CONSULTAMOS TODOS LOS MODULOS QUE SEAN COMPARTIDOS EN TODOS LOS OFICIOS PARA AGREGARLO AL NUEVO OFICIO REGISTRADO
    public function consultarModulosGenerales () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_modulo
            WHERE repetir_modulo='S'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA REGISTRARLE EL MODULO A TODOS LOS OFICIOS.
    public function registrarModuloTodosLosOficios ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_oficio_modulo (
            codigo_oficio,
            codigo_modulo
        ) VALUES (
            '".htmlspecialchars($datos["codigo_oficio"])."',
            '".htmlspecialchars($datos["codigo_modulo"])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarOficios ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
            t_oficio.*,
            (
                SELECT SUM(horas)
                FROM t_modulo
                INNER JOIN t_modulo_asig ON t_modulo.codigo = t_modulo_asig.codigo_modulo
                WHERE t_modulo.codigo_oficio = t_oficio.codigo
                AND t_modulo.codigo = t_modulo_asig.codigo_modulo
            ) AS horas,
            (
                SELECT COUNT(*)
                FROM t_modulo
                INNER JOIN t_modulo_asig ON t_modulo.codigo = t_modulo_asig.codigo_modulo
                WHERE t_modulo.codigo_oficio = t_oficio.codigo
                AND t_modulo.codigo = t_modulo_asig.codigo_modulo
            ) AS asignaturas
            FROM t_oficio

            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos["campo_cantidad"])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $sentencia = "SELECT
                (
                    SELECT SUM(horas)
                    FROM t_modulo_asig
                    WHERE t_modulo.codigo = t_modulo_asig.codigo_modulo
                ) AS horas,
                (
                    SELECT COUNT(*)
                    FROM t_modulo_asig
                    WHERE t_modulo.codigo = t_modulo_asig.codigo_modulo
                ) AS asignaturas
                FROM t_oficio_modulo
                
                INNER JOIN t_modulo ON t_oficio_modulo.codigo_modulo = t_modulo.codigo
                WHERE t_oficio_modulo.codigo_oficio='".$columna['codigo']."'
            "; // SENTENTCIA
            $consulta2 = mysqli_query($this->data_conexion,$sentencia);
            if ($columna2 = mysqli_fetch_assoc($consulta2)) {
                $columna['horas'] += $columna2['horas'];
                $columna['asignaturas'] += $columna2['asignaturas'];
            }

			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
	public function consultarOficiosTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_oficio
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado;
    }

    // FUNCION PARA VERIFICAR QUE NO EXISTA OTRO.
    public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE codigo!='".htmlspecialchars($datos['codigo'])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR UN OFICIO EXISTENTE.
    public function modificarOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_oficio SET
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            WHERE codigo='".htmlspecialchars($datos['codigo'])."'
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_oficio
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE codigo='".htmlspecialchars($datos['codigo'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA EMPEZAR NUEVA TRANSACCION.
    public function nuevaTransaccion () {
		mysqli_query($this->data_conexion,"START TRANSACTION");
    }

    // FUNCION PARA GUARDAR LOS CAMBIOS DE LA TRANSACCION.
    public function guardarTransaccion () {
		mysqli_query($this->data_conexion,"COMMIT");
    }
    
    // FUNCION PARA DESHACER TODA LA TRANSACCION.
    public function calcelarTransaccion () {
		mysqli_query($this->data_conexion,"ROLLBACK");
    }
}