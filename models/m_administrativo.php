<?php
require_once 'conexion.php';
class model_administrativo extends conexion{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_administrativo () {
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
    // FUNCION PARA CONSULTAR LAS OCUPACIONES
    public function consultarOcupaciones () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ocupacion WHERE estatus='A' AND formulario='A' ORDER BY nombre ASC"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS OFICIOS O CARRERAS
	public function consultarOficios () {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT * FROM t_oficio WHERE estatus='A' ORDER BY nombre ASC"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion, $sentencia);
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

    // FUNCION PARA CONSULTAR LAS CIUDADES DE UN ESTADO
	public function consultarCiudades ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_ciudad WHERE codigo_estado='".htmlspecialchars($datos['estado'])."'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA CONSULTAR LOS MUNICIPIOS DE UN ESTADO
	public function consultarMunicipios ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_municipio WHERE codigo_estado='".$datos['estado']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR LAS PARROQUIAS DE UN MUNICIPIO
	public function consultarParroquias ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_parroquia WHERE codigo_municipio='".$datos['municipio']."' AND estatus='A'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado[] = $columna;
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    /////////////// FIN INFORMACION FORMULARIO ///////////////
    //////////////////////////////////////////////////////////
  

    /////////////////// VERIFICAR REGISTROS ///////////////////
    // FUNCION PARA VERIFICAR QUE EL ASPIRANTE NO ESTE REGISTRADO EN EL SISTEMA
	public function verificarCedula ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_datos_personales
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado = mysqli_num_rows($consulta);
		}
		return $resultado;
    }
    ///////////////// FIN VERIFICAR REGISTROS /////////////////
    //////////////////////////////////////////////////////////

 
    //////////////////////////////////////////////////////////
    // FUNCIONES PARA REGISTROS RAPIDOS DE FORMULARIO
    // FUNCION PARA VERIFICAR PRIMERO SI EXISTE ESTA OCUPACION
    public function confirmarExistenciaR_O ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_ocupacion
            WHERE nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre_ocupacion'])))."'
            AND formulario='".htmlspecialchars($datos["formulario_ocupacion"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UNA NUEVA OCUPACION.
    public function registrarOcupacion($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_ocupacion (
            nombre,
            formulario
        ) VALUES (
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre_ocupacion'])))."',
            '".htmlspecialchars($datos['formulario_ocupacion'])."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    // FIN FUNCIONES PARA REGISTROS RAPIDOS DE FORMULARIO
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCIONES PARA REGISTROS DE PERSONAL ADMINISTRATIVO
    // FUNCION PARA REGISTRAR LOS DATOS PERSONALES DEL PERSONAL ADMINISTRATIVO
    public function registrarAdministrativo ($datos) {
        $valor_municipio = "NULL"; if ($datos['municipio'] != '') { $valor_municipio = htmlspecialchars($datos['municipio']); }
        $valor_parroquia = "NULL"; if ($datos['parroquia'] != '') { $valor_parroquia = htmlspecialchars($datos['parroquia']); }

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
            
            codigo_ciudad,
            codigo_municipio,
            codigo_parroquia,
            direccion,
            punto_referencia,

            telefono1,
            telefono2,
            correo,
            tipo_persona
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad'])."',
            '".htmlspecialchars($datos['cedula'])."',
            '".ucwords(strtolower(htmlspecialchars($datos['nombre_1'])))."',
            '".ucwords(strtolower(htmlspecialchars($datos['nombre_2'])))."',
            '".ucwords(strtolower(htmlspecialchars($datos['apellido_1'])))."',
            '".ucwords(strtolower(htmlspecialchars($datos['apellido_2'])))."',
            '".htmlspecialchars($datos['sexo'])."',
            '".htmlspecialchars($datos['fecha_n'])."',
            '".htmlspecialchars($datos['ocupacion'])."',

            '".htmlspecialchars($datos['ciudad'])."',
            $valor_municipio,
            $valor_parroquia,
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion'])))."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['punto_referencia'])))."',

            '".htmlspecialchars($datos['telefono_1'])."',
            '".htmlspecialchars($datos['telefono_2'])."',
            '".strtolower(htmlspecialchars($datos['correo']))."',
            'A'
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
    // FUNCION PARA CONSULTAR TODOS LOS FACILITADORES REGISTRADOS.
    public function consultarDatosPersonales ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
                t_datos_personales.*,
                t_ciudad.codigo_estado,
                t_parroquia.codigo_municipio
            FROM t_datos_personales
            INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            LEFT JOIN t_parroquia ON t_datos_personales.codigo_parroquia = t_parroquia.codigo
            WHERE (
                CONCAT(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(nombre1, ' ', apellido1, ' ', apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(nacionalidad, ' ', cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(nacionalidad, cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(nacionalidad, '-', cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_datos_personales.tipo_persona='A'
            AND t_datos_personales.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
            AND CONCAT(nacionalidad,'-',cedula) != '".htmlspecialchars($datos['cedula_usuario'])."'
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) { // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
            $resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO TOTAL DE FACILITADORES REGISTRADOS.
    public function consultarDatosPersonalesTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
                t_datos_personales.*,
                t_ciudad.codigo_estado,
                t_parroquia.codigo_municipio
            FROM t_datos_personales
            INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            LEFT JOIN t_parroquia ON t_datos_personales.codigo_parroquia = t_parroquia.codigo
            WHERE (
                CONCAT(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(nombre1, ' ', apellido1, ' ', apellido2) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(nacionalidad, ' ', cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR 
                CONCAT(nacionalidad, cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                CONCAT(nacionalidad, '-', cedula) LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_datos_personales.tipo_persona='A'
            AND t_datos_personales.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%'
            AND CONCAT(nacionalidad,'-',cedula) != '".htmlspecialchars($datos['cedula_usuario'])."'
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
    public function modificarAdministrativo ($datos) {
        $valor_municipio = "NULL"; if ($datos['municipio'] != '') { $valor_municipio = htmlspecialchars($datos['municipio']); }
        $valor_parroquia = "NULL"; if ($datos['parroquia'] != '') { $valor_parroquia = htmlspecialchars($datos['parroquia']); }

        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            nacionalidad='".htmlspecialchars($datos['nacionalidad'])."',
            cedula='".htmlspecialchars($datos['cedula'])."',
            nombre1='".ucwords(strtolower(htmlspecialchars($datos['nombre_1'])))."',
            nombre2='".ucwords(strtolower(htmlspecialchars($datos['nombre_2'])))."',
            apellido1='".ucwords(strtolower(htmlspecialchars($datos['apellido_1'])))."',
            apellido2='".ucwords(strtolower(htmlspecialchars($datos['apellido_2'])))."',
            sexo='".htmlspecialchars($datos['sexo'])."',
            fecha_n='".htmlspecialchars($datos['fecha_n'])."',
            codigo_ocupacion='".htmlspecialchars($datos['ocupacion'])."',
            
            codigo_ciudad='".htmlspecialchars($datos['ciudad'])."',
            codigo_municipio= $valor_municipio,
            codigo_parroquia= $valor_parroquia,
            direccion='".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion'])))."',
            punto_referencia='".ucfirst(mb_strtolower(htmlspecialchars($datos['punto_referencia'])))."',

            telefono1='".htmlspecialchars($datos['telefono_1'])."',
            telefono2='".htmlspecialchars($datos['telefono_2'])."',
            correo='".strtolower(htmlspecialchars($datos['correo']))."'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad2'])."'
            AND cedula='".htmlspecialchars($datos['cedula2'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    // FUNCIONES PARA MODIFICAR LOS DATOS PERSONALES DEL PERSONAL ADMINISTRATIVO
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCION PARA CAMBIAR EL ESTATUS DE ALGUN FACILITADOR.
    public function estatusAdministrador ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            estatus='".$datos['estatus']."'
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