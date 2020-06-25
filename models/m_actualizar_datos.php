<?php
require_once 'conexion.php';
class model_actualizar_datos extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_actualizar_datos () {
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
    /////////////////// ACTUALIZAR USUARIO ///////////////////
    // FUNCION PARA CONSULTAR EL HISTORIAL DE CONTRASEÑAS
	public function consultarContrasenas ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_contrasenas
            WHERE usuario='".$datos['usuario']."'
            ORDER BY numero DESC
            LIMIT 10
        "; // SENTENCIA.
        $consulta = mysqli_query($this->data_conexion, $sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
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
    ///////////////// FIN ACTUALIZAR USUARIO /////////////////
    //////////////////////////////////////////////////////////

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