<?php
require_once 'conexion.php';
class model_aprendiz extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_aprendiz () {
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
    // FUNCION PARA CONSULTAR LAS OCUPACIONES
	public function consultarOcupaciones () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ocupacion WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS ESTADOS.
	public function consultarEstados () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_estado WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES SEGUN EL ESTADO ELEGIDO.
	public function consultarCiudades ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS MUNICIPIOS SEGUN EL ESTADO ELEGIDO.
	public function consultarMunicipios ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_municipio WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LAS PARROQUIAS SEGUN EL MUNICIPIO ELEGIDO.
	public function consultarParroquias ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_parroquia WHERE codigo_municipio='".$datos['municipio']."' AND estatus='A'"; // SENTENTCIA
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
            AND codigo_modulo=1
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $resultado[] = $columna;
        }
        return $resultado;
    }
    /////////////// FIN INFORMACION FORMULARIO ///////////////
    //////////////////////////////////////////////////////////
    
    // FUNCION PARA CONSULTAR EL APRENDIZ QUE SE DESEA INSCRIBIR DESDE FICHA SOCIAL.
	public function consultarParticipante ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $where = '';

        if (isset($datos['campo_numero'])) {
            $where = "WHERE t_informe_social.numero='".$datos['campo_numero']."' OR t_informe_social.estatus='E'";
        } else if (isset($datos['campo_busqueda'])) {
            $where = "WHERE (
                concat(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                concat(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                concat(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                concat(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                concat(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%'
            )   OR t_informe_social.estatus='E'";
        }

		$sentencia = "SELECT
            t_informe_social.*, t_informe_social.estatus AS estatus_informe,
            t_datos_personales.*,
            t_ciudad.codigo_estado,
            t_ficha_aprendiz.numero AS ficha_anterior,
            t_ficha_aprendiz.empresa_actual
            FROM t_informe_social
            INNER JOIN t_datos_personales ON t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula
            INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            LEFT JOIN t_municipio ON t_datos_personales.codigo_municipio = t_municipio.codigo
            LEFT JOIN t_parroquia ON t_datos_personales.codigo_parroquia = t_parroquia.codigo
            LEFT JOIN t_ficha_aprendiz ON t_informe_social.numero = t_ficha_aprendiz.numero_informe

            ".$where."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LAS EMPRESAS
	public function consultarEmpresas ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT t_empresa.*,
        t_actividad_economica.nombre AS actividad_economica,
        t_ciudad.nombre AS ciudad,
        t_estado.nombre AS estado
        FROM t_empresa
        INNER JOIN t_actividad_economica ON t_empresa.codigo_actividad = t_actividad_economica.codigo
        INNER JOIN t_ciudad ON t_empresa.codigo_ciudad = t_ciudad.codigo
        INNER JOIN t_estado ON t_ciudad.codigo_estado = t_estado.codigo
        WHERE rif LIKE '%".$datos['buscar']."%'
        OR razon_social LIKE '%".$datos['buscar']."%'
        AND t_empresa.estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA VERIFICAR PRIMERO SI EXISTE ESTA OCUPACION
	public function verificarOcupacion($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre='".$datos['input_registrar_ocupacion']."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UNA NUEVA OCUPACION.
    public function registrarOcupacion($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_ocupacion (
            nombre
        ) VALUES (
            '".$datos['input_registrar_ocupacion']."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UN NUEVO OFICIO.
    public function registrarAprendiz($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_ficha_aprendiz (
            fecha,
            tipo_inscripcion,
            ficha_anterior,
            correlativo,
            numero_orden,
            numero_informe,
            empresa_actual
        ) VALUES (
            '".$datos['fecha']."',
            '".$datos['tipo_ficha']."',
            NULL,
            '".$datos['correlativo']."',
            '".$datos['numero_orden']."',
            '".$datos['informe_social']."',
            '".$datos['rif']."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusAprendiz($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_informe_social SET
            estatus='A'
            WHERE numero='".$datos['informe_social']."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarPlanilla($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT
            t_ficha_aprendiz.*,
            t_ficha_aprendiz.estatus AS estatus_informe,
            t_datos_personales.*,
            t_ciudad.codigo_estado,
            t_parroquia.codigo_municipio
            FROM t_ficha_aprendiz
            INNER JOIN t_informe_social
            ON t_ficha_aprendiz.numero_informe = t_informe_social.numero
            INNER JOIN t_datos_personales
            ON t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad
            AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula
            INNER JOIN t_ciudad
            ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            LEFT JOIN t_parroquia
            ON t_datos_personales.codigo_parroquia = t_parroquia.codigo
            WHERE (
                concat(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo_busqueda']."%' OR 
                concat(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo_busqueda']."%' OR
                concat(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".$datos['campo_busqueda']."%' OR 
                concat(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".$datos['campo_busqueda']."%' OR
                concat(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".$datos['campo_busqueda']."%'
            )
            AND t_ficha_aprendiz.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'

            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
	public function consultarPlanillaTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ficha_aprendiz
            INNER JOIN t_informe_social
            ON t_ficha_aprendiz.numero_informe = t_informe_social.numero
            INNER JOIN t_datos_personales
            ON t_informe_social.nacionalidad_aprendiz = t_datos_personales.nacionalidad
            AND t_informe_social.cedula_aprendiz = t_datos_personales.cedula
            WHERE (
                concat(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo_busqueda']."%' OR 
                concat(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".$datos['campo_busqueda']."%' OR
                concat(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".$datos['campo_busqueda']."%' OR 
                concat(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".$datos['campo_busqueda']."%' OR
                concat(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".$datos['campo_busqueda']."%'
            )
            AND t_ficha_aprendiz.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR A LOS FAMILIARES DEL APRENDIZ CONSULTADO.
    public function consultarDatosEmpresa($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_empresa.*,
            t_actividad_economica.nombre AS actividad_economica,
            t_ciudad.nombre AS ciudad,
            t_estado.nombre AS estado
            FROM t_empresa
            INNER JOIN t_actividad_economica ON t_empresa.codigo_actividad = t_actividad_economica.codigo
            INNER JOIN t_ciudad ON t_empresa.codigo_ciudad = t_ciudad.codigo
            INNER JOIN t_estado ON t_ciudad.codigo_estado = t_estado.codigo
            WHERE rif='".$datos['rif']."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR UN OFICIO EXISTENTE.
    public function modificarOficio($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_oficio SET
            codigo=".$datos['codigo'].",
            nombre=".$datos['nombre']."
            WHERE codigo=".$datos['codigo2']."
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusOficio($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_oficio SET
            estatus=".$datos['estatus']."
            WHERE codigo=".$datos['codigo']."
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA EMPEZAR NUEVA TRANSACCION.
    public function nuevaTransaccion() {
		mysqli_query($this->data_conexion,"START TRANSACTION");
    }

    // FUNCION PARA GUARDAR LOS CAMBIOS DE LA TRANSACCION.
    public function guardarTransaccion() {
		mysqli_query($this->data_conexion,"COMMIT");
    }
    
    // FUNCION PARA DESHACER TODA LA TRANSACCION.
    public function calcelarTransaccion() {
		mysqli_query($this->data_conexion,"ROLLBACK");
    }
}