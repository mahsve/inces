<?php
require_once 'conexion.php';
class model_dashboard extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_dashboard () {
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
    public function consultarActivos () {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ficha_aprendiz WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        $resultado = mysqli_num_rows($consulta);
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarTotal () {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ficha_aprendiz"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        $resultado = mysqli_num_rows($consulta);
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarOficios () {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        $resultado = mysqli_num_rows($consulta);
		return $resultado; // RETORNAMOS LOS DATOS.
    }

	public function consultarFacilitadores () {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_datos_personales WHERE estatus='A' AND tipo_persona='F'"; // SENTENTCIA
        $consulta  = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        $resultado = mysqli_num_rows($consulta);
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    /////////////// FIN INFORMACION FORMULARIO ///////////////
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