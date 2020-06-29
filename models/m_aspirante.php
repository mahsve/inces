<?php
require_once 'conexion.php';
class model_aspirante extends conexion {
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_aspirante () {
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
		$sentencia = "SELECT * FROM t_ocupacion WHERE estatus='A' AND formulario='B' ORDER BY nombre ASC"; // SENTENTCIA
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
            INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado = $columna;
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
    // FUNCIONES PARA REGISTROS DEL MODULO
    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function registrarEmpresa ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_empresa (
            rif,
            nil,
            razon_social,
            codigo_actividad,
            codigo_aportante,
            telefono1,
            telefono2,
            correo,
            codigo_ciudad,
            direccion
        ) VALUES (
            '".htmlspecialchars($datos['rif'])."',
            '".htmlspecialchars($datos['nil'])."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['razon_social'])))."',
            '".htmlspecialchars($datos['actividad_economica'])."',
            '".htmlspecialchars($datos['codigo_aportante'])."',
            '".htmlspecialchars($datos['telefono_1'])."',
            '".htmlspecialchars($datos['telefono_2'])."',
            '".mb_strtolower(htmlspecialchars($datos['correo']))."',
            '".htmlspecialchars($datos['ciudad'])."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion'])))."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    public function registrarPersonaContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_datos_personales (
            nacionalidad,
            cedula,
            nombre1,
            nombre2,
            apellido1,
            apellido2,
            codigo_ciudad,
            direccion,
            telefono1,
            telefono2,
            correo,
            tipo_persona
        ) VALUES (
            '".htmlspecialchars($datos['nacionalidad_contacto'])."',
            '".htmlspecialchars($datos['cedula_contacto'])."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['nombre1_contacto'])))."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['nombre2_contacto'])))."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['apellido1_contacto'])))."',
            '".ucwords(mb_strtolower(htmlspecialchars($datos['apellido2_contacto'])))."',
            '".htmlspecialchars($datos['ciudad_contacto'])."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion_contacto'])))."',
            '".htmlspecialchars($datos['telefono1_contacto'])."',
            '".htmlspecialchars($datos['telefono2_contacto'])."',
            '".mb_strtolower(htmlspecialchars($datos['correo_contacto']))."',
            'C'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado;
    }

    // FUNCION PARA REGISTRAR LA PERSONA DE CONTACTO DE LA EMPRESA
    public function modificarPersonaContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_datos_personales SET
            nacionalidad='".htmlspecialchars($datos['nacionalidad_contacto'])."',
            cedula='".htmlspecialchars($datos['cedula_contacto'])."',
            nombre1='".ucwords(mb_strtolower(htmlspecialchars($datos['nombre1_contacto'])))."',
            nombre2='".ucwords(mb_strtolower(htmlspecialchars($datos['nombre2_contacto'])))."',
            apellido1='".ucwords(mb_strtolower(htmlspecialchars($datos['apellido1_contacto'])))."',
            apellido2='".ucwords(mb_strtolower(htmlspecialchars($datos['apellido2_contacto'])))."',
            codigo_ciudad='".htmlspecialchars($datos['ciudad_contacto'])."',
            direccion='".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion_contacto'])))."',
            telefono1='".htmlspecialchars($datos['telefono1_contacto'])."',
            telefono2='".htmlspecialchars($datos['telefono2_contacto'])."',
            correo='".mb_strtolower(htmlspecialchars($datos['correo_contacto']))."',
            tipo_persona='C'
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad_contacto2'])."'
            AND cedula='".htmlspecialchars($datos['cedula_contacto2'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado;
    }

    // FUNCTION PARA REGISTRAR LA RELACION DE LA PERSONA DE CONTACTO CON LA EMPRESA.
    public function registrarRelacionEmpresaContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO td_contacto (
            rif,
            nacionalidad,
            cedula,
            codigo_cargo
        ) VALUES (
            '".htmlspecialchars($datos['rif_empresa'])."',
            '".htmlspecialchars($datos['nacionalidad_contacto'])."',
            '".htmlspecialchars($datos['cedula_contacto'])."',
            '".htmlspecialchars($datos['cargo_contacto'])."'
        )"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado;
    }

    // FUNCTION PARA MODIFICAR LA RELACION DE LA PERSONA DE CONTACTO CON LA EMPRESA.
    public function modificarRelacionEmpresaContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE td_contacto SET
            rif='".htmlspecialchars($datos['rif_empresa'])."',
            nacionalidad='".htmlspecialchars($datos['nacionalidad_contacto'])."',
            cedula='".htmlspecialchars($datos['cedula_contacto'])."',
            codigo_cargo='".htmlspecialchars($datos['cargo_contacto'])."'
            WHERE numero='".htmlspecialchars($datos['numero_contacto'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado;
    }
    // FIN FUNCIONES PARA REGISTROS DEL MODULO
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarEmpresas ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT t_empresa.*, t_actividad_economica.nombre AS actividad_economica, t_ciudad.codigo_estado
            FROM t_empresa
            INNER JOIN t_actividad_economica ON t_empresa.codigo_actividad = t_actividad_economica.codigo
            INNER JOIN t_ciudad ON t_empresa.codigo_ciudad = t_ciudad.codigo
            WHERE ( rif LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    nil LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    razon_social LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_empresa.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
            ORDER BY ".htmlspecialchars($datos['campo_ordenar'])."
            LIMIT ".htmlspecialchars($datos["campo_numero"]).", ".htmlspecialchars($datos['campo_cantidad'])."
        ";
        $consulta = mysqli_query($this->data_conexion,$sentencia);
        while ($columna = mysqli_fetch_assoc($consulta)) {
            $sentencia2 = "SELECT td_contacto.*, t_datos_personales.*, t_ciudad.codigo_estado
                FROM td_contacto
                INNER JOIN t_datos_personales ON td_contacto.nacionalidad = t_datos_personales.nacionalidad AND td_contacto.cedula = t_datos_personales.cedula
                INNER JOIN t_ciudad ON t_datos_personales.codigo_ciudad = t_ciudad.codigo
                WHERE rif='".$columna['rif']."'
            ";
            $consulta2 = mysqli_query($this->data_conexion,$sentencia2);
            while ($columna2 = mysqli_fetch_assoc($consulta2)) {
                $columna['contactos'][] = $columna2;
            }
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
	public function consultarEmpresasTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_empresa
            WHERE ( rif LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    nil LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%' OR
                    razon_social LIKE '%".htmlspecialchars($datos['campo_busqueda'])."%')
            AND t_empresa.estatus LIKE '%".htmlspecialchars($datos['campo_estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
    //////////////////////////////////////////////////////////

    
    // FUNCION PARA REGISTRAR LA NUEVA EMPRESA.
    public function modificarEmpresa ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_empresa SET
            rif='".htmlspecialchars($datos['rif'])."',
            nil='".htmlspecialchars($datos['nil'])."',
            razon_social='".ucwords(mb_strtolower(htmlspecialchars($datos['razon_social'])))."',
            codigo_actividad='".htmlspecialchars($datos['actividad_economica'])."',
            codigo_aportante='".htmlspecialchars($datos['codigo_aportante'])."',
            telefono1='".htmlspecialchars($datos['telefono_1'])."',
            telefono2='".htmlspecialchars($datos['telefono_2'])."',
            correo='".mb_strtolower(htmlspecialchars($datos['correo']))."',
            codigo_ciudad='".htmlspecialchars($datos['ciudad'])."',
            direccion='".ucfirst(mb_strtolower(htmlspecialchars($datos['direccion'])))."'
            WHERE rif='".htmlspecialchars($datos['rif2'])."'
        "; // SENTENTCIA
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    public function consultarContactoEmpresa ($id_contacto) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT
                td_contacto.*,
                t_datos_personales.tipo_persona,
                (
                    SELECT COUNT(*)
                    FROM td_contacto AS td_contacto2
                    WHERE td_contacto2.nacionalidad = t_datos_personales.nacionalidad
                    AND td_contacto2.cedula = t_datos_personales.cedula
                ) AS relaciones
            FROM td_contacto
            INNER JOIN t_datos_personales ON
                t_datos_personales.nacionalidad = td_contacto.nacionalidad AND
                t_datos_personales.cedula       = td_contacto.cedula
            WHERE numero='".htmlspecialchars($id_contacto)."'
        "; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) {
			$resultado = $columna;
		}
		return $resultado;
    }

    // FUNCION PARA ELIMINAR A LOS CONTACTOS DE LA EMPRESA
    public function eliminarRelacionEmpresaContacto ($id_contacto) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM td_contacto WHERE numero='".htmlspecialchars($id_contacto)."'"; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado;
    }

    // FUNCION PARA ELIMINAR A LOS CONTACTOS DE LA EMPRESA
    public function eliminarDatosContacto ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "DELETE FROM t_datos_personales
            WHERE nacionalidad='".htmlspecialchars($datos['nacionalidad'])."'
            AND cedula='".htmlspecialchars($datos['cedula'])."'
        "; // SENTENTCIA
        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado;
    }

    

    
    //////////////////////////////////////////////////////////
    // FUNCION PARA CAMBIAR EL ESTATUS DE UN ASPIRANTE
    public function estatusEmpresa ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_empresa
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE rif='".htmlspecialchars($datos['rif'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
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