<?php
require_once 'conexion.php';
class model_modulo_oficio extends conexion
{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_modulo_oficio ()
    {
        $this->conexion(); // SE DEFINE LA CLASE PARA HEREDAR SUS ATRIBUTOS.
    }

    // FUNCION PARA ABRIR CONEXION.
    public function conectar ()
    {
        $datos = $this->obtenerDatos(); // OBTENEMOS LOS DATOS DE CONEXION.
        $this->data_conexion = mysqli_connect($datos['local'], $datos['user'], $datos['password'], $datos['database']); // SE CREA LA CONEXION A LA BASE DE DATOS.
        mysqli_query($this->data_conexion, "SET NAMES 'utf8'");
        
    }

    // FUNCION PARA CERRAR CONEXION.
    public function desconectar ()
    {
        mysqli_close($this->data_conexion);
    }

    // FUNCION PARA CONSULTAR TODOS LOS MODULOS DE LOS OFICIOS REGISTRADOS.
	function consultarModulos($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "  SELECT t_modulo.codigoModuloOficio, t_modulo.nombre, t_modulo.estatus, t_oficio.nombre AS oficio 
                        FROM t_modulo INNER JOIN t_oficio ON t_modulo.codigo_oficio = t_oficio.codigo    
                        WHERE (
                            nombre LIKE '%".htmlspecialchars($datos['campo'])."%' OR
                            codigoModuloOficio LIKE '%".htmlspecialchars($datos['campo'])."%')
                        AND estatus LIKE '%".htmlspecialchars($datos['estatus'])."%' 
                        ORDER BY ".$datos['ordenar_por']." 
                        LIMIT ".htmlspecialchars($datos['numero']).", ".htmlspecialchars($datos['cantidad'])."
                    "; // SENTENTCIA
         $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) { // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
            $resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
        }
        return $resultado; // RETORNAMOS LOS DATOS.
       
    }

   
    // FUNCION PARA CONSULTAR LOS OFICIOS REGISTRADOS Y MOSTRARLOS EN EL SELECT PARA REGISTRAR NUEVOS MODULOS.
	function consultarOficios()
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_oficio"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        while ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado[] = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UN NUEVO MODULO DE UN OFICIO.
    function registrarModulo($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_modulo (
            codigo,
            codigoModuloOficio,
            nombre,
            codigo_oficio
        ) VALUES (
            '".$datos['codigo']."', 
            '".$datos['codigoModuloOficio']."', 
            '".$datos['nombre']."', 
            '".$datos['oficio']."')";

        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR UNA OCUPACION EN CONCRETO.
	public function consultarModulo($datos)
	{
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT * FROM t_modulo WHERE codigo='".$datos['codigo']."'"; // SENTENTCIA
        $consulta = mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if ($columna = mysqli_fetch_assoc($consulta)) // CONVERTIRMOS LOS DATOS EN UN ARREGLO.
        {
			$resultado = $columna; // GUARDAMOS LOS DATOS EN LA VARIABLE.
		}
		return $resultado; // RETORNAMOS LOS DATOS.
    }


      // FUNCION PARA CONSULTAR EL TOTAL DE LOS MODULOS.
    public function consultarTotalPorModulo($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
           
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }

   
    // FUNCION PARA MODIFICAR UN MODULO DE UN OFICIO.
    function modificarModulo($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE
                        t_modulo
                    SET
                        codigo = '".htmlspecialchars($datos['codigo'])."',
                        codigoOficio = '".htmlspecialchars($datos['codigoOficio'])."',
                        nombre = '".htmlspecialchars($datos['razon_social'])."',
                        codigo_oficio = '".htmlspecialchars($datos['codigo_oficio'])."',
                       
                    WHERE
                        codigo = '".htmlspecialchars($datos['codigo'])."'
        "; // SENTENTCIA

        mysqli_query($this->data_conexion,$sentencia); // REALIZAMOS LA CONSULTA.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UN MODULO
    public function estatusModulo($datos)
    {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.   

        $sentencia = "UPDATE t_modulo
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE codigoModuloOficio='".htmlspecialchars($datos['codigoModuloOficio'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0)
        {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

     public function confirmarExistenciaM ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE codigo!='".htmlspecialchars($datos["codigo"])."'
            AND nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
        }
        return $resultado; // RETORNAMOS LOS DATOS.
    }



    public function confirmarExistencia($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "SELECT *
            FROM t_modulo
            WHERE codigoModuloOficio='".ucfirst(mb_strtolower(htmlspecialchars($datos["codigoModuloOficio"])))."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
            $resultado = mysqli_num_rows($consulta);
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