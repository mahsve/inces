<?php
require_once 'conexion.php';
class model_empresa extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_empresa ()
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

    // FUNCION PARA CONSULTAR LAS ACTIVIDADES ECONOMICAS.
    public function consultarActividades()
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_actividad_economica WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS ESTADOS
    public function consultarEstados()
	{
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
	public function consultarCiudades($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado=".$datos['estado']." "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO EN ESPECIFICO
	public function verificarRIF($rif)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_empresa WHERE rif='".$rif."'"; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO EN ESPECIFICO
	public function verificarCedula($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
        FROM t_datos_personales
        INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
        WHERE nacionalidad=$datos[nacionalidad] AND cedula=$datos[cedula]"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    public function registrarPersonaContacto($datos)
    {
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
            telefono1,
            correo,
            tipo_persona
        ) VALUES (
            $datos[nacionalidad],
            $datos[cedula],
            $datos[nombre_1],
            $datos[nombre_2],
            $datos[apellido_1],
            $datos[apellido_2],
            $datos[sexo],
            $datos[ciudad_c],
            $datos[telefono],
            $datos[correo],
            'C'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function registrarEmpresa($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_empresa (
            rif,
            nil,
            razon_social,
            codigo_actividad_e,
            codigo_aportante,
            telefono1,
            telefono2,
            codigo_ciudad,
            direccion,
            nacionalidad_contacto,
            persona_contacto
        ) VALUES (
            $datos[rif],
            $datos[nil],
            $datos[razon_social],
            $datos[actividad_economica],
            $datos[codigo_aportante],
            $datos[telefono_1],
            $datos[telefono_2],
            $datos[ciudad],
            $datos[direccion],
            $datos[nacionalidad],
            $datos[cedula]
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	function consultarEmpresas($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_empresa.*, t_actividad_economica.nombre AS actividad_economica, t_ciudad.codigo_estado
            FROM t_empresa
            INNER JOIN t_actividad_economica ON t_empresa.codigo_actividad_e = t_actividad_economica.codigo
            INNER JOIN t_ciudad ON t_empresa.codigo_ciudad = t_ciudad.codigo
            WHERE ( rif LIKE '%".$datos['campo']."%' OR
                    nil LIKE '%".$datos['campo']."%' OR
                    razon_social LIKE '%".$datos['campo']."%')
            AND t_empresa.estatus LIKE '%".$datos['estatus']."%' 
            ORDER BY ".$datos['ordenar_por']." 
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        ";
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta))
        {
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
	function consultarEmpresasTotal($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_empresa
            WHERE ( rif LIKE '%".$datos['campo']."%' OR
                    nil LIKE '%".$datos['campo']."%' OR
                    razon_social LIKE '%".$datos['campo']."%')
            AND estatus LIKE '%".$datos['estatus']."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    public function modificarPersonaContacto($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            nacionalidad=$datos[nacionalidad],
            cedula=$datos[cedula],
            nombre1=$datos[nombre_1],
            nombre2=$datos[nombre_2],
            apellido1=$datos[apellido_1],
            apellido2=$datos[apellido_2],
            sexo=$datos[sexo],
            codigo_ciudad=$datos[ciudad_c],
            telefono1=$datos[telefono],
            correo=$datos[correo]
            WHERE nacionalidad=$datos[nacionalidad2] AND cedula=$datos[cedula2]
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function modificarEmpresa($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_empresa SET 
            rif=$datos[rif],
            nil=$datos[nil],
            razon_social=$datos[razon_social],
            codigo_actividad_e=$datos[actividad_economica],
            codigo_aportante=$datos[codigo_aportante],
            telefono1=$datos[telefono_1],
            telefono2=$datos[telefono_2],
            codigo_ciudad=$datos[ciudad],
            direccion=$datos[direccion],
            nacionalidad_contacto=$datos[nacionalidad],
            persona_contacto=$datos[cedula]
            WHERE rif=$datos[rif2]
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusEmpresa($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_empresa SET
            estatus=$datos[estatus]
            WHERE rif=$datos[rif]
        ";
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