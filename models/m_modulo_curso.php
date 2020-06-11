<?php
require_once 'conexion.php';
class model_modulo_curso extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_modulo_curso () {
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

    // FUNCION PARA CONSULTAR LOS OFICIOS ESPECIFICOS
    public function consultarOficios () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT * FROM t_oficio"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $resultado[] = $columna;
        }
        return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS OFICIOS ESPECIFICOS
    public function consultarAsignaturas ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_asignatura
            WHERE codigo_oficio='".$datos['oficio']."'
            AND codigo_modulo='".$datos['modulo']."'
        "; // SENTENTCIA
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
            FROM td_modulo
            WHERE anio_modulo='".htmlspecialchars($datos["anio_modulo"])."'
            AND parte_anio='".htmlspecialchars($datos["p_anio_modulo"])."'
            AND codigo_oficio='".htmlspecialchars($datos["oficio"])."'
            AND codigo_modulo='".htmlspecialchars($datos["modulo"])."'
            AND codigo_seccion='".htmlspecialchars($datos["sesion"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA ASIGNATURA.
    public function registrarModuloCurso ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO td_modulo (
            descripcion,
            anio_modulo,
            parte_anio,
            codigo_oficio,
            codigo_modulo,
            codigo_seccion
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["descripcion"])))."',
            '".htmlspecialchars($datos['anio_modulo'])."',
            '".htmlspecialchars($datos['p_anio_modulo'])."',
            '".htmlspecialchars($datos['oficio'])."',
            '".htmlspecialchars($datos['modulo'])."',
            '".htmlspecialchars($datos['sesion'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = mysqli_insert_id($this->data_conexion);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA ASIGNATURA.
    public function registrarModuloCursoAsig ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO td_asignatura (
            codigo_modulo,
            codigo_asignatura
        ) VALUES (
            '".htmlspecialchars($datos['modulo'])."',
            '".htmlspecialchars($datos['asignatura'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS ASIGNATURAS REGISTRADOS
    public function consultarModulosCursos ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT td_modulo.*, t_oficio.nombre AS oficio
            FROM td_modulo
            INNER JOIN t_oficio ON td_modulo.codigo_oficio = t_oficio.codigo
            WHERE td_modulo.descripcion LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND td_modulo.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $columna['detalles_asignaturas'] = [];

            $sentencia2 = "SELECT *
                FROM td_asignatura
                WHERE codigo_modulo='".$columna['codigo']."'
            "; // SENTENTCIA
            $consulta2 = mysqli_query($this->data_conexion, $sentencia2); // REALIZAMOS LA CONSULTA.
            while ($columna2 = mysqli_fetch_assoc($consulta2)) {
                $columna['detalles_asignaturas'][] = $columna2;
            }

            $resultado[] = $columna;
        }
        return $resultado;
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE ASIGNATURAS REGISTRADOS EN TOTAL
    public function consultarModulosCursosTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT td_modulo.*, t_oficio.nombre AS oficio
            FROM td_modulo
            INNER JOIN t_oficio ON td_modulo.codigo_oficio = t_oficio.codigo
            WHERE td_modulo.descripcion LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND td_modulo.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO,
    public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM td_modulo
            WHERE anio_modulo='".htmlspecialchars($datos["anio_modulo"])."'
            AND parte_anio='".htmlspecialchars($datos["p_anio_modulo"])."'
            AND codigo_oficio='".htmlspecialchars($datos["oficio"])."'
            AND codigo_modulo='".htmlspecialchars($datos["modulo"])."'
            AND codigo_seccion='".htmlspecialchars($datos["sesion"])."'
            AND codigo!='".htmlspecialchars($datos["codigo"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }
    
    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function modificarModuloCurso($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE td_modulo SET
            descripcion='".ucfirst(mb_strtolower(htmlspecialchars($datos["descripcion"])))."',
            anio_modulo='".htmlspecialchars($datos['anio_modulo'])."',
            parte_anio='".htmlspecialchars($datos['p_anio_modulo'])."',
            codigo_oficio='".htmlspecialchars($datos['oficio'])."',
            codigo_modulo='".htmlspecialchars($datos['modulo'])."',
            codigo_seccion='".htmlspecialchars($datos['sesion'])."'
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function eliminaModuloCursoAsig ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM td_asignatura WHERE codigo='".htmlspecialchars($datos['codigo'])."'"; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusModuloCurso ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE td_modulo
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