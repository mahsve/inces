<?php
require_once 'conexion.php';
class model_modulo extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_modulo () {
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

    
    ///////////////// INFORMACION FORMULARIO /////////////////
    // FUNCION PARA CONSULTAR LOS OFICIOS.
	public function consultarOficios () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS ASIGNATURAS.
    public function consultarAsignaturas ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_asignatura
            WHERE nombre LIKE '%".htmlspecialchars($datos["buscar"])."%'
            ORDER BY nombre ASC
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $resultado[] = $columna;
        }
        return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS MODULOS Y ORDENARLOS CORRECTAMENTE.
    public function consultarModulosOrden ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.

        // SE CONSULTAN TODOS LOS MODULOS QUE ESTEN EN LA TABLA DETALLES DE TODOS LOS OFICIOS.
        $sentencia = "SELECT *
            FROM t_oficio_modulo
            INNER JOIN t_modulo ON t_oficio_modulo.codigo_modulo = t_modulo.codigo
            WHERE t_oficio_modulo.codigo_oficio='".htmlspecialchars($datos["oficio"])."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $resultado[] = $columna;
        }

        // SE CONSULTAN TODOS LOS MODULOS DE ESTE OFICIO DETERMINADO
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE codigo_oficio='".htmlspecialchars($datos["oficio"])."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $resultado[] = $columna;
        }

        // SE PROCEDE A ORDENAR POR POSICION
        $n; $i; $k; $aux;
        $n = count($resultado);
        for ($k = 1; $k < $n; $k++) {
            for ($i = 0; $i < ($n - $k); $i++) {
                if ($resultado[$i]['orden'] > $resultado[$i + 1]['orden']) {
                    $aux = $resultado[$i];
                    $resultado[$i] = $resultado[$i + 1];
                    $resultado[$i + 1] = $aux;
                }
            }
        }

        return $resultado;
    }

    public function modificarOrdenModulos ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_modulo SET
            orden='".htmlspecialchars($datos["posicion_modulo"])."'
            WHERE codigo='".htmlspecialchars($datos["codigo_modulo"])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }
    /////////////// FIN INFORMACION FORMULARIO ///////////////
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCIONES PARA REGISTRAR UN NUEVO MODULO.
    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MODULO REPETIDO PARA TODOS LOS OFICIOS.
    public function confirmarExistenciaSinOficio ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            AND repetir_modulo='".htmlspecialchars($datos["repeticion_modulo"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MODULO PARA EL MISMO OFICIO.
    public function confirmarExistenciaConOficio ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            AND codigo_oficio='".htmlspecialchars($datos["oficio"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR EL MODULO SIN OFICIO YA QUE SE REGISTRARA EN UNA TABLA DETALLES (OFICIOS - MODULOS)
    public function registrarModuloSinOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_modulo (
            nombre,
            repetir_modulo,
            orden
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."',
            '".htmlspecialchars($datos["repeticion_modulo"])."',
            2
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = mysqli_insert_id($this->data_conexion);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR EL MODULO CON UN OFICIO DETERMINADO.
    public function registrarModuloConOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_modulo (
            nombre,
            codigo_oficio,
            orden
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."',
            '".htmlspecialchars($datos["oficio"])."',
            2
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = mysqli_insert_id($this->data_conexion);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LAS ASIGNATURAS EN LA TABLA DE DETALLES DEL MODULO.
    public function registrarAsignatura ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_modulo_asig (
            codigo_modulo,
            codigo_asignatura,
            horas
        ) VALUES (
            '".htmlspecialchars($datos["codigo_modulo"])."',
            '".htmlspecialchars($datos["codigo_asignatura"])."',
            '".htmlspecialchars($datos["cantidad_horas"])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS PARA AGREGARLE EL MODULO COMPARTIDO
    public function consultarOficiosTodos () {
        $resultado = []; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado; // RETORNAMOS LOS DATOS.
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
    // FIN FUNCIONES PARA REGISTRAR UN NUEVO MODULO.
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCION PARA CONSULTAR TODOS LOS ASIGNATURAS REGISTRADOS
    public function consultarModulos ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
            t_modulo.*,
            (
                SELECT SUM(horas)
                FROM t_modulo_asig
                WHERE t_modulo.codigo = t_modulo_asig.codigo_modulo
            ) AS horas,
            t_oficio.nombre AS oficio
            
            FROM t_modulo
            LEFT JOIN t_oficio ON t_modulo.codigo_oficio = t_oficio.codigo
            WHERE t_modulo.nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND t_modulo.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            // AGREGAMOS UNA NUEVA POSICION PARA GUARDAR LAS ASIGNATURAS DE ESTE MODULO
            $columna['asignaturas'] = [];

            // CONSULTAMOS LAS ASIGNATURAS
            $sentencia = "SELECT t_modulo_asig.*, t_asignatura.nombre
                FROM t_modulo_asig
                INNER JOIN t_asignatura ON t_modulo_asig.codigo_asignatura = t_asignatura.codigo
                WHERE codigo_modulo='".$columna['codigo']."'
            "; // SENTENTCIA
            $consulta2 = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
            while ($columna2 = mysqli_fetch_assoc($consulta2)) {
                $columna['asignaturas'][] = $columna2;
            }

            $resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE ASIGNATURAS REGISTRADOS EN TOTAL
    public function consultarModulosTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
            t_modulo.*,
            (
                SELECT COUNT(*)
                FROM t_modulo_asig
                WHERE t_modulo.codigo = t_modulo_asig.codigo_modulo
            ) AS horas
            
            FROM t_modulo
            WHERE nombre LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            AND estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCIONES PARA MODIFICAR UN MODULO
    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MODULO CON ESTA CARACTERISTICAS CON OTRO CODIGO.
    public function confirmarExistenciaMSinOficio ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE codigo!='".htmlspecialchars($datos['codigo'])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            AND repetir_modulo='".htmlspecialchars($datos["repeticion_modulo"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MODULO PARA EL MISMO OFICIO CON OTRO CODIGO
    public function confirmarExistenciaMConOficio ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE codigo!='".htmlspecialchars($datos['codigo'])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre'])))."'
            AND codigo_oficio='".htmlspecialchars($datos["oficio"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR EL MODULO SIN OFICIO YA QUE SE REGISTRARA EN UNA TABLA DETALLES (OFICIOS - MODULOS)
    public function modificarModuloSinOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_modulo SET
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."',
            repetir_modulo='".htmlspecialchars($datos["repeticion_modulo"])."',
            codigo_oficio=NULL
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR EL MODULO CON UN OFICIO DETERMINADO.
    public function modificarModuloConOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_modulo SET
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."',
            codigo_oficio='".htmlspecialchars($datos["oficio"])."',
            repetir_modulo=NULL
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR LAS ASIGNATURAS EN LA TABLA DE DETALLES DEL MODULO.
    public function modificarAsignatura ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_modulo_asig SET
            horas='".htmlspecialchars($datos["cantidad_horas"])."'
            WHERE codigo='".htmlspecialchars($datos["codigo_registro"])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA ELIMINAR LAS ASIGNATURAS SELECCIONADAS POR EL USUARIO
    public function eliminarAsignatura ($id_asignatura) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_modulo_asig WHERE codigo='".htmlspecialchars($id_asignatura)."'"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA ELIMINAR LOS MODULOS QUE ESTAN COMPARTIDOS EN VARIOS OFICIOS PARA AGREGARLOS NUEVAMENTE ACTUALIZADOS
    public function eliminarDetallesOficioModulos ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_oficio_modulo WHERE codigo_modulo='".htmlspecialchars($datos["codigo"])."'"; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }
    // FUNCIONES PARA MODIFICAR UN MODULO
    //////////////////////////////////////////////////////////

    
    //////////////////////////////////////////////////////////
    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusModulo ($datos) {
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
    //////////////////////////////////////////////////////////


    ///////////////////// TRANSACCIONES /////////////////////
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
    /////////////////// FIN TRANSACCIONES ////////////////////
    //////////////////////////////////////////////////////////
}