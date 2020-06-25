<?php
require_once 'conexion.php';
class model_sesion extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_sesion () {
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
    ///////////// CONSULTAR Y ACTUALIZAR USUARIO /////////////
	// FUNCION PARA CONSULTAR LOS DATOS DE INICIO DE SESION.
	public function consultarUsuario ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT t_usuario.*, t_rol.*, t_datos_personales.*, t_usuario.estatus AS estatus_usuario
            FROM t_usuario
            INNER JOIN t_rol ON t_usuario.codigo_rol = t_rol.codigo
            INNER JOIN t_datos_personales
                ON t_usuario.nacionalidad = t_datos_personales.nacionalidad
                AND t_usuario.cedula = t_datos_personales.cedula
            WHERE t_usuario.usuario='".htmlspecialchars($datos["usuario"])."'
        "; // SENTENCIA.
        $consulta = mysqli_query($this->data_conexion, $sentencia);
        if ($columna = mysqli_fetch_assoc($consulta)) {
            // ESTABLECEMOS UN HISTORIAL DE CONTRASEÑAS POR SI NO ENCUENTRA NINGUNO
            $columna['historial_contrasenas'] = [];

            // CONSULTAMOS LAS ACTUALIZACIONES DE CONTRASEÑAS.
            $sentencia = "SELECT *
                FROM t_contrasenas
                WHERE usuario='".$columna['usuario']."'
                ORDER BY numero DESC
                LIMIT 1
            "; // SENTENCIA.
            $consulta2 = mysqli_query($this->data_conexion, $sentencia);
            if ($columna2 = mysqli_fetch_assoc($consulta2)) {
                $columna['historial_contrasenas'] = $columna2;
            }

            // ALMACENAMOS TODO EN LA VARIABLE RESULTADO.
			$resultado = $columna;
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LAS ASIGNATURAS EN LA TABLA DE DETALLES DEL MODULO.
    public function registrarBitacora ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_bitacora (
            usuario,
            fecha,
            hora,
            navegador,
            operacion
        ) VALUES (
            '".htmlspecialchars($datos["usuario"])."',
            '".htmlspecialchars($datos["fecha"])."',
            '".htmlspecialchars($datos["hora"])."',
            '".htmlspecialchars($datos["navegador"])."',
            '".htmlspecialchars($datos["operacion"])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA ACTUALIZAR LOS INTENTOS FALLIDOS DEL USUARIO AL INTENTAR INICIAR SESION
    public function actualizarIntentos ($datos) {
        $resultado = false;
        $sentencia ="UPDATE t_usuario SET
            intentos='".htmlspecialchars($datos["intentos"])."'
            WHERE usuario='".htmlspecialchars($datos["usuario"])."'
        "; // SENTENCIA.
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado;
    }

    // FUNCION PARA BLOQUEAR EL USUARIO POR INTENTOS FALLIDOS.
	public function bloquearUsuario ($datos) {
        $resultado = false;
		$sentencia ="UPDATE t_usuario SET
            intentos='".htmlspecialchars($datos["intentos"])."',
            estatus='B'
            WHERE usuario='".htmlspecialchars($datos["usuario"])."'
        "; // SENTENCIA.
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
        return $resultado;
    }
    /////////// FIN CONSULTAR Y ACTUALIZAR USUARIO ///////////
    //////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////
    /////////////// CONSULTAR PERMISOS Y MENU ////////////////
    // FUNCION PARA CONSULTAR PERMISOS, ENTRAR UNA VISTA, REGISTRAR, MODIFICAR, ELIMINAR, CAMBIAR ESTATUS.
    function consultarPermisos ($datos) {
        $resultado = false;
		$sentencia = "SELECT *
            FROM t_vista
            INNER JOIN td_rol_vista ON t_vista.codigo = td_rol_vista.codigo_vista
            WHERE td_rol_vista.codigo_rol='".$datos['codigo_rol']."' AND
            t_vista.enlace='".$datos['text_vista']."'
        ";
        $consulta = mysqli_query($this->data_conexion, $sentencia);
        if ($columna = mysqli_fetch_assoc($consulta))
        {
			$resultado = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA TRAER LOS MODULOS A LOS EL USUARIO TIENE PERMISO.
    function consultarMenu ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_modulo_sistema.*
            FROM td_rol_modulo
            INNER JOIN t_modulo_sistema ON td_rol_modulo.codigo_modulo = t_modulo_sistema.codigo
            WHERE td_rol_modulo.codigo_rol='".$datos['codigo_rol']."'
            ORDER BY t_modulo_sistema.posicion ASC
        ";
        ////////////////////////////////////////////////////////////
        $consulta  = mysqli_query($this->data_conexion, $sentencia);
        while ($columna = mysqli_fetch_assoc($consulta))
        {
            $vistas = [];
            $sentencia2 = "SELECT t_vista.*
                FROM td_rol_vista
                INNER JOIN t_vista ON td_rol_vista.codigo_vista = t_vista.codigo
                WHERE td_rol_vista.codigo_rol='".$datos['codigo_rol']."' AND
                t_vista.codigo_modulo='".$columna['codigo']."'
                ORDER BY t_vista.posicion ASC
            ";
            ////////////////////////////////////////////////////////////
            $consulta2  = mysqli_query($this->data_conexion, $sentencia2); // REALIZAMOS LA CONSULTA.
            while ($columna2 = mysqli_fetch_assoc($consulta2)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
            {
                $vistas[] = $columna2; // GUARDAMOS LOS DATOS EN LA VARIABLE.
            }
            $columna['vistas'] = $vistas;
            $resultado[] = $columna;
        }
		return $resultado;
    }
    //////////////// FIN CONSULTAR REGISTROS /////////////////
    //////////////////////////////////////////////////////////
}