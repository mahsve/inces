<?php
require_once 'conexion.php';
class model_empresa extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_empresa () {
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
    // FUNCION PARA CONSULTAR LAS ACTIVIDADES ECONOMICAS.
    public function consultarActividades () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_actividad_economica WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS ESTADOS
    public function consultarEstados () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_estado"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO EN ESPECIFICO
	public function consultarCiudades ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='".htmlspecialchars($datos['estado'])."'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }
    /////////////// FIN INFORMACION FORMULARIO ///////////////
    //////////////////////////////////////////////////////////

    /////////////////// VERIFICAR REGISTROS ///////////////////
    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO EN ESPECIFICO
	public function verificarRIF($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_empresa WHERE rif='".htmlspecialchars($datos['rif'])."'"; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO EN ESPECIFICO
	public function verificarCedula($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_datos_personales
            INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado = $columna;
		}
		return $resultado;
    }
    ///////////////// FIN VERIFICAR REGISTROS /////////////////
    //////////////////////////////////////////////////////////

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    public function registrarPersonaContacto($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_personales (
            nacionalidad,
            cedula,
            nombre1,
            nombre2,
            apellido1,
            apellido2,
            sexo,
            codigo_ciudad,
            direccion,
            telefono1,
            telefono2,
            correo,
            tipo_persona
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['nombre_1'])))."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['nombre_2'])))."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['apellido_1'])))."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['apellido_2'])))."',
            '".htmlspecialchars($datos['sexo'])."',
            '".htmlspecialchars($datos['ciudad_c'])."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion_c'])))."',
            '".htmlspecialchars($datos['telefono_1_c'])."',
            '".htmlspecialchars($datos['telefono_2_c'])."',
            '".mb_strtolower(htmlspecialchars($datos['correo_c']))."',
            'C'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado;
    }

    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function registrarEmpresa($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_empresa (
            rif,
            nil,
            razon_social,
            codigo_actividad,
            codigo_aportante,
            telefono1,
            telefono2,
            correo,
            codigo_ciudad,
            direccion,
            nacionalidad_contacto,
            persona_contacto
        ) VALUES (
            '".htmlspecialchars($datos['rif'])."',
            '".htmlspecialchars($datos['nil'])."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['razon_social'])))."',
            '".htmlspecialchars($datos['actividad_economica'])."',
            '".htmlspecialchars($datos['codigo_aportante'])."',
            '".htmlspecialchars($datos['telefono_1'])."',
            '".htmlspecialchars($datos['telefono_2'])."',
            '".mb_strtolower(htmlspecialchars($datos['correo']))."',
            '".htmlspecialchars($datos['ciudad'])."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion'])))."',
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarEmpresas($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_empresa.*, t_actividad_economica.nombre AS actividad_economica, t_ciudad.codigo_estado
            FROM t_empresa
            INNER JOIN t_actividad_economica ON t_empresa.codigo_actividad = t_actividad_economica.codigo
            INNER JOIN t_ciudad ON t_empresa.codigo_ciudad = t_ciudad.codigo
            WHERE ( rif LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    nil LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    razon_social LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_empresa.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        ";
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $sentencia2 = "SELECT *
                FROM t_datos_personales
                INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
                WHERE nacionalidad='".$columna['nacionalidad_contacto']."'
                AND cedula='".$columna['persona_contacto']."'
            ";
            $consulta2 = mysqli_query($this->data_conexion,$sentencia2);
            while ($columna2 = mysqli_fetch_assoc($consulta2))
            {
                $columna['datos_personales'] = $columna2;
            }
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
	public function consultarEmpresasTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_empresa
            WHERE ( rif LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    nil LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    razon_social LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_empresa.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    public function modificarPersonaContacto($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            nacionalidad='".htmlspecialchars($datos['nacionalidad'])."',
            cedula='".htmlspecialchars($datos['cedula'])."',
            nombre1='".ucwords(mb_strtolower(htmlspecialchars($datos['nombre_1'])))."',
            nombre2='".ucwords(mb_strtolower(htmlspecialchars($datos['nombre_2'])))."',
            apellido1='".ucwords(mb_strtolower(htmlspecialchars($datos['apellido_1'])))."',
            apellido2='".ucwords(mb_strtolower(htmlspecialchars($datos['apellido_2'])))."',
            sexo='".htmlspecialchars($datos['sexo'])."',
            codigo_ciudad='".htmlspecialchars($datos['ciudad_c'])."',
            direccion='".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion_c'])))."',
            telefono1='".htmlspecialchars($datos['telefono_1_c'])."',
            telefono2='".htmlspecialchars($datos['telefono_2_c'])."',
            correo='".mb_strtolower(htmlspecialchars($datos['correo_c']))."'
            WHERE nacionalidad='".$datos['nacionalidad2']."' AND cedula='".$datos['cedula2']."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function modificarEmpresa($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_empresa SET 
            rif='".htmlspecialchars($datos['rif'])."',
            nil='".htmlspecialchars($datos['nil'])."',
            razon_social='".ucwords(mb_strtolower(htmlspecialchars($datos['razon_social'])))."',
            codigo_actividad='".htmlspecialchars($datos['actividad_economica'])."',
            codigo_aportante='".htmlspecialchars($datos['codigo_aportante'])."',
            telefono1='".htmlspecialchars($datos['telefono_1'])."',
            telefono2='".htmlspecialchars($datos['telefono_2'])."',
            correo='".mb_strtolower(htmlspecialchars($datos['correo']))."',
            codigo_ciudad='".htmlspecialchars($datos['ciudad'])."',
            direccion='".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion'])))."',
            nacionalidad_contacto='".htmlspecialchars($datos['nacionalidad'])."',
            persona_contacto='".htmlspecialchars($datos['cedula'])."'
            WHERE rif='".htmlspecialchars($datos['rif2'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusEmpresa ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_empresa
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE rif='".htmlspecialchars($datos['rif'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

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