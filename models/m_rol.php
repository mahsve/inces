<?php
require_once 'conexion.php';
class model_rol extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_rol ()
    {
        $this->conexion(); // SE DEFINE LA CLASE PARA HEREDAR SUS ATRIBUTOS.
    }

    // FUNCION PARA ABRIR CONEXION.
    public function conectar ()
    {
        $datos = $this->obtenerDatos(); // OBTENEMOS LOS DATOS DE CONEXION.
        $this->data_conexion = mysqli_connect($datos['local'], $datos['user'], $datos['password'], $datos['database']); // SE CREA LA CONEXION A LA BASE DE DATOS.
    }

    // FUNCION PARA CERRAR CONEXION.
    public function desconectar ()
    {
        mysqli_close($this->data_conexion);
    }

    // FUNCION PARA COSULTAR LOS MODULOS DISPONIBLES Y AGREGARLOS AL ROL.
    public function consultarVistaFormulario()
    {
        $resultado  = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia  = "SELECT * FROM t_modulo_sistema ORDER BY posicion ASC"; // SENTENTCIA
        $consulta   = mysqli_query($this->data_conexion, $sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
            $sentencia2 = "SELECT * FROM t_vista WHERE codigo_modulo='".$columna['codigo']."' ORDER BY posicion ASC"; // SENTENTCIA 2
            $consulta2 = mysqli_query($this->data_conexion, $sentencia2);
            /////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////
            $vistas_modulos = [];
            while ($columna2 = mysqli_fetch_assoc($consulta2))
            {
                $vistas_modulos[] = $columna2;
            }
            $columna['vistas'] = $vistas_modulos;
            /////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UN NUEVO ROL.
    public function registrarRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_rol (
            nombre
        ) VALUES (
            ".$datos['nombre']."
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = mysqli_insert_id($this->data_conexion);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LOS MODULOS ASIGNADOS AL ROL.
    public function registrarModulosDelRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO td_rol_modulo (
            codigo_rol,
            codigo_modulo
        ) VALUES (
            ".$datos['codigo'].",
            ".$datos['modulo']."
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LAS VISTAS ASIGNADOS AL ROL.
    public function registrarVistasDelRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO td_rol_vista (
            codigo_rol,
            codigo_vista,
            registrar,
            modificar,
            act_desc,
            eliminar
        ) VALUES (
            ".$datos['codigo'].",
            ".$datos['vista'].",
            ".$datos['registrar'].",
            ".$datos['modificar'].",
            ".$datos['act_desc'].",
            ".$datos['eliminar']."
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS ROLES REGISTRADOS.
	public function consultarRoles($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_rol
            WHERE ( nombre LIKE '%".$datos['campo']."%' OR
                    codigo LIKE '%".$datos['campo']."%')
            ORDER BY ".$datos['ordenar_por']." 
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
            $sentencia2 = "SELECT * FROM td_rol_modulo WHERE codigo_rol='".$columna['codigo']."'"; // SENTENCIA 2
            $modulos = [];
            if ($consulta2 = mysqli_query($this->data_conexion,$sentencia2))
            {
                $columna['numero_m'] = mysqli_num_rows($consulta2);
                while ($columna2 = mysqli_fetch_assoc($consulta2))
                {
                    $modulos[] = $columna2;
                }
            }
            $columna['modulos'] = $modulos;
            //////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////
            $sentencia3 = "SELECT * FROM td_rol_vista WHERE codigo_rol='".$columna['codigo']."'"; // SENTENCIA 3
            $vistas = [];
            if ($consulta3 = mysqli_query($this->data_conexion,$sentencia3))
            {
                $columna['numero_v'] = mysqli_num_rows($consulta3);
                while ($columna3 = mysqli_fetch_assoc($consulta3))
                {
                    $vistas[] = $columna3;
                }
            }
            $columna['vistas'] = $vistas;
            //////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS ROLES REGISTRADOS.
	public function consultarRolesTotal($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_rol
            WHERE ( nombre LIKE '%".$datos['campo']."%' OR
                    codigo LIKE '%".$datos['campo']."%')
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR UN ROL.
    public function modificarRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_rol SET
            nombre=".$datos['nombre']."
            WHERE codigo=".$datos['codigo']."
        ";
        if (mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = true;
        }
        return $resultado;
    }

    // FUNCION PARA ELIMINAR LOS MODULOS DEL ROL.
    public function eliminarModulosDelRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM td_rol_modulo WHERE codigo_rol=".$datos['codigo']." ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
    }

    // FUNCION PARA ELIMINAR LAS VISTAS DEL ROL.
    public function eliminarVistasDelRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM td_rol_vista WHERE codigo_rol=".$datos['codigo']." ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
    }

    // FUNCION PARA ELIMINAR UN ROL.
    public function eliminarRol($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_rol WHERE codigo=".$datos['codigo']." ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA EMPEZAR NUEVA TRANSACCION.
    public function nuevaTransaccion()
    {
		mysqli_query($this->data_conexion,"START TRANSACTION");
    }

    // FUNCION PARA GUARDAR LOS CAMBIOS DE LA TRANSACCION.
    public function guardarTransaccion()
    {
		mysqli_query($this->data_conexion,"COMMIT");
    }
    
    // FUNCION PARA DESHACER TODA LA TRANSACCION.
    public function calcelarTransaccion()
    {
		mysqli_query($this->data_conexion,"ROLLBACK");
    }
}