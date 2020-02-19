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
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='$datos[estado]' AND estatus='A'"; // SENTENTCIA
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
		$sentencia = "SELECT * FROM t_municipio WHERE codigo_estado='$datos[estado]' AND estatus='A'"; // SENTENTCIA
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
		$sentencia = "SELECT * FROM t_parroquia WHERE codigo_municipio='$datos[municipio]' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN UN ARREGLO.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }
 
    // FUNCION PARA REGISTRAR LOS DATOS PERSONALES DEL APRENDIZ.
    public function registrarDatosPersonales($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_personales(
            nacionalidad,
            cedula,
            nombre1,
            nombre2,
            apellido1,
            apellido2,
            sexo,
            fecha_n,
            lugar_n,
            estado_civil,
            nivel_instruccion,
            titulo_acade,
            mision_participado,
            codigo_ciudad,
            codigo_parroquia,
            direccion,
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
            $datos[fecha_n],
            $datos[lugar_n],
            $datos[estado_civil],
            $datos[grado_instruccion],
            $datos[titulo],
            $datos[alguna_mision],
            $datos[ciudad],
            $datos[parroquia],
            $datos[direccion],
            $datos[telefono_1],
            $datos[correo], 
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
		$sentencia = "SELECT *
            FROM t_datos_personales
            WHERE ( concat(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['campo']."%' OR 
                    concat(nombre1, ' ', apellido1, ' ', apellido2) LIKE '%".$datos['campo']."%' OR
                    concat(nacionalidad, ' ', cedula) LIKE '%".$datos['campo']."%' OR 
                    concat(nacionalidad, cedula) LIKE '%".$datos['campo']."%' OR
                    concat(nacionalidad, '-', cedula) LIKE '%".$datos['campo']."%' )
            AND tipo_persona='F'
            AND estatus='".$datos['estatus']."'
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
                    concat(nacionalidad, '-', cedula) LIKE '%".$datos['campo']."%'
            ) AND tipo_persona='F'
            ORDER BY ".$datos['ordenar_por']." 
            LIMIT ".$datos['numero'].", ".$datos['cantidad']."
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function modificarDatosPersonales($datos)
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
            fecha_n=$datos[fecha_n],
            lugar_n=$datos[lugar_n],
            codigo_ocupacion=$datos[ocupacion],
            estado_civil=$datos[estado_civil],
            nivel_instruccion=$datos[grado_instruccion],
            titulo_acade=$datos[titulo],
            mision_participado=$datos[alguna_mision],
            codigo_ciudad=$datos[ciudad],
            codigo_parroquia=$datos[parroquia],
            direccion=$datos[direccion],
            telefono1=$datos[telefono_1],
            telefono2=$datos[telefono_2],
            correo=$datos[correo]
            WHERE nacionalidad=$datos[nacionalidad_v] AND cedula=$datos[cedula_v]
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

   
    // FUNCION PARA REGISTRAR LA FICHA DEL APRENDIZ.
    public function modificarInformeSocial($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_informe_social SET 
            fecha=$datos[fecha],
            codigo_oficio=$datos[oficio],
            turno=$datos[turno],
            condicion_vivienda=$datos[condicion_vivienda],
            caracteristicas_generales=$datos[caracteristicas_generales],
            diagnostico_social=$datos[diagnostico_social],
            diagnostico_preliminar=$datos[diagnostico_preliminar],
            conclusiones=$datos[conclusiones],
            enfermos=$datos[enfermos],
            representante=$datos[responsable_apre]
            WHERE numero=$datos[informe_social]
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia))
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