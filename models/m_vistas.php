<?php
require_once 'conexion.php';
class modelo_vistas extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function modelo_vistas () {
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

    // FUNCION PARA CONSULTAR TODOS LOS MODULOS Y MOSTRARLOS EN EL SELECT DEL FORMULARIO.
    public function consultarModulos() {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_modulo_sistema ORDER BY posicion ASC"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL TOTAL DE LAS VISTAS REGISTRADAS POR MODULOS.
	public function consultarTotalVistasPorModulo($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_vista
            WHERE codigo_modulo=".$datos['modulo']."
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UNA NUEVA VISTA.
    public function registrarVista($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_vista (
            codigo_modulo, 
            nombre, 
            enlace, 
            posicion, 
            icono
        ) VALUES (
            ".$datos['modulo'].",
            ".$datos['nombre'].",
            ".$datos['enlace'].",
            ".$datos['posicion'].",
            ".$datos['icono']."
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS VISTAS DEL SISTEMA REGISTRADOS.
	public function consultarVistas($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_vista.*, t_modulo_sistema.nombre AS modulo
            FROM t_vista
            INNER JOIN t_modulo_sistema ON t_vista.codigo_modulo = t_modulo_sistema.codigo
            WHERE t_vista.nombre LIKE '%".$datos['campo']."%' OR 
            t_modulo_sistema.nombre LIKE '%".$datos['campo']."%'
            ORDER BY t_modulo_sistema.posicion, t_vista.posicion ASC
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LAS VISTAS DEL SISTEMA REGISTRADOS EN TOTAL.
	public function consultarVistasTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_vista.*, t_modulo_sistema.nombre AS modulo
            FROM t_vista
            INNER JOIN t_modulo_sistema ON t_vista.codigo_modulo = t_modulo_sistema.codigo
            WHERE t_vista.nombre LIKE '%".$datos['campo']."%' OR 
            t_modulo_sistema.nombre LIKE '%".$datos['campo']."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS MODULOS DEL SISTEMA PARA LUEGO ASIGNARLE UNA NUEVA POSICION.
	public function consultarVistasModulosTodos() {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT * FROM t_vista ORDER BY codigo_modulo, posicion ASC"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS MODULOS DEL SISTEMA PARA LUEGO ASIGNARLE UNA NUEVA POSICION.
	public function consultarVistasModulosTodos2($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT * FROM t_vista WHERE codigo_modulo=".$datos['modulo']." ORDER BY posicion ASC"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR UNA VISTA.
    public function modificarVista($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_vista SET 
            codigo_modulo=".$datos['modulo'].", 
            nombre=".$datos['nombre'].", 
            enlace=".$datos['enlace'].", 
            icono =".$datos['icono']."
            WHERE codigo=".$datos['codigo']."
        ";
        if (mysqli_query($this->data_conexion, $sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR EL ORDEN DE LOS MODULOS DEL SISTEMA.
    public function modificarOrdenVista($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_vista SET
            posicion =".$datos['posicion']."
            WHERE codigo=".$datos['codigo']."
        ";
        if (mysqli_query($this->data_conexion, $sentencia))
        {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA ELIMINAR UNA VISTA.
    public function eliminarVista($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_vista WHERE codigo=".$datos['codigo']." ";
        mysqli_query($this->data_conexion, $sentencia); // EJECUTAMOS LA OPERACION.
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