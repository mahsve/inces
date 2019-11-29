<?php

class conexion {
	private $datos;

	protected function conexion ()
	{
		$this->datos = array(
			'local'		=> "localhost",
			'user'		=> "root",
			'password'	=> '',
			'database'  => 'sistema_inces'
		);
	}

	protected function obtenerDatos ()
	{
		return $this->datos;
	}
}