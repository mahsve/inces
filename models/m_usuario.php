<?php
require_once 'conexion.php';
class model_usuario extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_usuario () {
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

    //////////////////////////////////////////////////////////
    ///////////////// INFORMACION FORMULARIO /////////////////
    // FUNCION PARA CONSULTAR LOS ROLES DISPONIBLES PARA LOS USUARIOS
    public function consultarRoles () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_rol WHERE estatus='A' ORDER BY nombre ASC"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }
    /////////////// FIN INFORMACION FORMULARIO ///////////////
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCIONES PARA REGISTROS DE PERSONAL ADMINISTRATIVO
    // FUNCION PARA REGISTRAR LOS DATOS PERSONALES DEL PERSONAL ADMINISTRATIVO
    public function registrarUsuario ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_usuario (
            usuario,
            contrasena,
            nacionalidad,
            cedula,
            codigo_rol
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad'].'-'.$datos['cedula'])."',
            '".htmlspecialchars($datos['contrasena'])."',
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".htmlspecialchars($datos['rol_usuario'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }
    // FIN FUNCIONES PARA REGISTROS DE PERSONAL ADMINISTRATIVO
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarUsuarios($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
                t_datos_personales.nacionalidad,
                t_datos_personales.cedula,
                t_datos_personales.nombre1,
                t_datos_personales.nombre2,
                t_datos_personales.apellido1,
                t_datos_personales.apellido2,
                t_datos_personales.tipo_persona,
                t_datos_personales.estatus AS estatus_persona,
                t_usuario.usuario,
                t_usuario.codigo_rol,
                t_usuario.estatus,
                t_rol.nombre AS rol
            FROM t_datos_personales
            LEFT JOIN t_usuario ON t_datos_personales.nacionalidad = t_usuario.nacionalidad AND t_datos_personales.cedula = t_usuario.cedula
            LEFT JOIN t_rol ON t_usuario.codigo_rol = t_rol.codigo
            WHERE (
                CONCAT(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND (
                t_usuario.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
                OR t_usuario.estatus IS NULL
            )
            AND CONCAT(t_datos_personales.nacionalidad,'-',t_datos_personales.cedula) != '".htmlspecialchars($datos['cedula_usuario'])."'
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
    public function consultarUsuariosTotal($datos) {
        $sentencia = "SELECT
                t_datos_personales.nacionalidad,
                t_datos_personales.cedula,
                t_datos_personales.nombre1,
                t_datos_personales.nombre2,
                t_datos_personales.apellido1,
                t_datos_personales.apellido2,
                t_datos_personales.tipo_persona,
                t_datos_personales.estatus AS estatus_persona,
                t_usuario.usuario,
                t_usuario.estatus,
                t_rol.nombre AS rol
            FROM t_datos_personales
            LEFT JOIN t_usuario ON t_datos_personales.nacionalidad = t_usuario.nacionalidad AND t_datos_personales.cedula = t_usuario.cedula
            LEFT JOIN t_rol ON t_usuario.codigo_rol = t_rol.codigo
            WHERE (
                CONCAT(t_datos_personales.nombre1, ' ', t_datos_personales.nombre2, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(t_datos_personales.nombre1, ' ', t_datos_personales.apellido1, ' ', t_datos_personales.apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(t_datos_personales.nacionalidad, ' ', t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(t_datos_personales.nacionalidad, t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(t_datos_personales.nacionalidad, '-', t_datos_personales.cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND (
                t_usuario.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
                OR t_usuario.estatus IS NULL
            )
            AND CONCAT(t_datos_personales.nacionalidad,'-',t_datos_personales.cedula) != '".htmlspecialchars($datos['cedula_usuario'])."'
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCIONES PARA MODIFICAR LOS DATOS PERSONALES DEL PERSONAL ADMINISTRATIVO
    // FUNCION PARA MODIFICAR LOS DATOS PERSONALES DEL FACILITADOR.
    public function modificarUsuario ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_usuario SET
            codigo_rol='".htmlspecialchars($datos['rol_usuario'])."'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    // FUNCIONES PARA MODIFICAR LOS DATOS PERSONALES DEL PERSONAL ADMINISTRATIVO
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCION PARA CANCELAR UN USUARIO
    public function cancelarUsuario ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_usuario
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    // FUNCION PARA RESTABLECER CONTRASEÑA DE UN USUARIO
    public function restablecerUsuario ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_usuario SET
            estatus='".htmlspecialchars($datos['estatus'])."',
            contrasena='".htmlspecialchars($datos['contrasena'])."'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    //////////////////////////////////////////////////////////
}