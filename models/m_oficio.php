<?php
require_once 'conexion.php';
class model_oficio extends conexion{
    private $data_conexion; // VARIABLE QUE CONTENDRA LA CONEXION.

    // DEFINIMOS EL CONSTRUCTOR.
    public function model_oficio (){
        $this->conexion(); // SE DEFINE LA CLASE PARA HEREDAR SUS ATRIBUTOS.
    }

    // FUNCION PARA ABRIR CONEXION.
    public function conectar (){
        $datos = $this->obtenerDatos(); // OBTENEMOS LOS DATOS DE CONEXION.
        $this->data_conexion = mysqli_connect($datos['local'], $datos['user'], $datos['password'], $datos['database']); // SE CREA LA CONEXION A LA BASE DE DATOS.
    }

    // FUNCION PARA CERRAR CONEXION.
    public function desconectar (){
        mysqli_close($this->data_conexion);
    }

    // FUNCION PARA VERIFICAR QUE NO ESTE REGISTRADO EL MISMO DATO,
    public function confirmarExistenciaR ($datos) {
        $resultado = 0; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_oficio
            WHERE codigo='".htmlspecialchars($datos["codigo"])."'
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia)) {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA REGISTRAR UN NUEVO OFICIO.
    public function registrarOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "INSERT INTO t_oficio (
            codigo,
            nombre
        ) VALUES (
            '".strtoupper(htmlspecialchars($datos["codigo"]))."',
            '".ucfirst(mb_strtolower(htmlspecialchars($datos["nombre"]), `UTF-8`))."'
        )";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CONSULTAR TODOS LOS OFICIOS REGISTRADOS
	public function consultarOficios ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_oficio
            WHERE (
                nombre LIKE '%".htmlspecialchars($datos['campo'])."%' OR
                codigo LIKE '%".htmlspecialchars($datos['campo'])."%')
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

    // FUNCION PARA CONSULTAR EL NUMERO DE OFICIOS REGISTRADOS EN TOTAL
	public function consultarOficiosTotal ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
		$sentencia = "SELECT *
            FROM t_oficio
            WHERE (
                nombre LIKE '%".htmlspecialchars($datos['campo'])."%' OR
                codigo LIKE '%".htmlspecialchars($datos['campo'])."%')
            AND estatus LIKE '%".htmlspecialchars($datos['estatus'])."%' 
        "; // SENTENTCIA
        if ($consulta = mysqli_query($this->data_conexion, $sentencia))
        {
			$resultado = mysqli_num_rows($consulta);
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA MODIFICAR UN OFICIO EXISTENTE.
    public function modificarOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_oficio SET
            codigo='".strtoupper(htmlspecialchars($datos['codigo']))."',
            nombre='".ucfirst(mb_strtolower(htmlspecialchars($datos['nombre']), `UTF-8`))."'
            WHERE codigo='".htmlspecialchars($datos['codigo2'])."'
        ";
        if (mysqli_query($this->data_conexion,$sentencia)) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }

    // FUNCION PARA CAMBIAR EL ESTATUS DE UNA ACTIVIDAD ECONOMICA.
    public function estatusOficio ($datos) {
        $resultado = false; // VARIABLE PARA GUARDAR LOS DATOS.
        $sentencia = "UPDATE t_oficio
            SET estatus='".htmlspecialchars($datos['estatus'])."'
            WHERE codigo='".htmlspecialchars($datos['codigo'])."'
        ";
        mysqli_query($this->data_conexion,$sentencia); // EJECUTAMOS LA OPERACION.
        if (mysqli_affected_rows($this->data_conexion) > 0) {
            $resultado = true;
        }
		return $resultado; // RETORNAMOS LOS DATOS.
    }
}