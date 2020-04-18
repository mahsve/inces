<?php
require_once 'conexion.php';
class model_facilitador extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_facilitador ()
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

    // FUNCION PARA CONSULTAR LAS OCUPACIONES
	public function consultarOcupaciones()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ocupacion WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS OFICIOS.
	public function consultarOficios()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS ESTADOS.
	public function consultarEstados()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_estado WHERE estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS CIUDADES SEGUN EL ESTADO ELEGIDO.
	public function consultarCiudades($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LOS MUNICIPIOS SEGUN EL ESTADO ELEGIDO.
	public function consultarMunicipios($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_municipio WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS PARROQUIAS SEGUN EL MUNICIPIO ELEGIDO.
	public function consultarParroquias($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_parroquia WHERE codigo_municipio='".$datos['municipio']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }
 
    // FUNCION PARA REGISTRAR LOS DATOS PERSONALES DEL APRENDIZ.
    public function registrarFacilitador($datos)
    {
        if ($datos['parroquia'] != '')
            $datos['parroquia'] = htmlspecialchars($datos['parroquia']);
        else
            $datos['parroquia'] = 'NULL';

        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_personales (
            nacionalidad,
            cedula,
            nombre1,
            nombre2,
            apellido1,
            apellido2,
            sexo,
            fecha_n,
            codigo_ocupacion,
            estado_civil,
            nivel_instruccion,
            titulo_acade,
            mision_participado,
            codigo_ciudad,
            codigo_parroquia,
            direccion,
            telefono1,
            telefono2,
            correo,
            tipo_persona
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".htmlspecialchars($datos['nombre_1'])."',
            '".htmlspecialchars($datos['nombre_2'])."',
            '".htmlspecialchars($datos['apellido_1'])."',
            '".htmlspecialchars($datos['apellido_2'])."',
            '".htmlspecialchars($datos['sexo'])."',
            '".htmlspecialchars($datos['fecha_n'])."',
            '".htmlspecialchars($datos['ocupacion'])."',
            '".htmlspecialchars($datos['estado_civil'])."',
            '".htmlspecialchars($datos['grado_instruccion'])."',
            '".htmlspecialchars($datos['titulo'])."',
            '".htmlspecialchars($datos['alguna_mision'])."',
            '".htmlspecialchars($datos['ciudad'])."',
            $datos[parroquia],
            '".htmlspecialchars($datos['direccion'])."',
            '".htmlspecialchars($datos['telefono_1'])."',
            '".htmlspecialchars($datos['telefono_2'])."',
            '".htmlspecialchars($datos['correo'])."',
            'F'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarDatosPersonales ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT t_datos_personales.*, t_ciudad.codigo_estado, t_parroquia.codigo_municipio
            FROM t_datos_personales
            INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            LEFT JOIN t_parroquia ON t_datos_personales.codigo_parroquia = t_parroquia.codigo
            WHERE ( concat(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['campo']."%' OR 
                    concat(nombre1, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['campo']."%' OR
                    concat(nacionalidad, ' ', cedula) LIKE '%".$datos['campo']."%' OR 
                    concat(nacionalidad, cedula) LIKE '%".$datos['campo']."%' OR
                    concat(nacionalidad, '-', cedula) LIKE '%".$datos['campo']."%' )
            AND t_datos_personales.tipo_persona='F'
            AND t_datos_personales.estatus LIKE '%".$datos['estatus']."%'
            ORDER BY '".$datos['ordenar_por']."'
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarDatosPersonalesTotal($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_datos_personales
            WHERE ( concat(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['campo']."%' OR 
                    concat(nombre1, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['campo']."%' OR
                    concat(nacionalidad, ' ', cedula) LIKE '%".$datos['campo']."%' OR 
                    concat(nacionalidad, cedula) LIKE '%".$datos['campo']."%' OR
                    concat(nacionalidad, '-', cedula) LIKE '%".$datos['campo']."%' )
            AND t_datos_personales.tipo_persona='F'
            AND t_datos_personales.estatus LIKE '%".$datos['estatus']."%'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function modificarFacilitador($datos)
    {
        if ($datos['parroquia'] != '')
            $datos['parroquia'] = htmlspecialchars($datos['parroquia']);
        else
            $datos['parroquia'] = 'NULL';
            
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            nacionalidad='".htmlspecialchars($datos['nacionalidad'])."',
            cedula='".htmlspecialchars($datos['cedula'])."',
            nombre1='".htmlspecialchars($datos['nombre_1'])."',
            nombre2='".htmlspecialchars($datos['nombre_2'])."',
            apellido1='".htmlspecialchars($datos['apellido_1'])."',
            apellido2='".htmlspecialchars($datos['apellido_2'])."',
            sexo='".htmlspecialchars($datos['sexo'])."',
            fecha_n='".htmlspecialchars($datos['fecha_n'])."',
            codigo_ocupacion='".htmlspecialchars($datos['ocupacion'])."',
            estado_civil='".htmlspecialchars($datos['estado_civil'])."',
            nivel_instruccion='".htmlspecialchars($datos['grado_instruccion'])."',
            titulo_acade='".htmlspecialchars($datos['titulo'])."',
            mision_participado='".htmlspecialchars($datos['alguna_mision'])."',
            codigo_ciudad='".htmlspecialchars($datos['ciudad'])."',
            codigo_parroquia=$datos[parroquia],
            direccion='".htmlspecialchars($datos['direccion'])."',
            telefono1='".htmlspecialchars($datos['telefono_1'])."',
            telefono2='".htmlspecialchars($datos['telefono_2'])."',
            correo='".htmlspecialchars($datos['correo'])."'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad2'])."'
            AND cedula='".htmlspecialchars($datos['cedula2'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function estatusFacilitador ($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            estatus='".$datos['estatus']."'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
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