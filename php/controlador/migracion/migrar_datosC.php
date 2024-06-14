<?php 
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

require_once(dirname(__DIR__,2). '/modelo/migracion/migrar_datosM.php');
$controlador = new migrar_datosC();

if(isset($_GET['generarArchivos']))
{
	echo json_encode($controlador->generarArchivos());
}
if(isset($_GET['generarSP']))
{
	echo json_encode($controlador->generarSP());
}


class migrar_datosC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new migrar_datosM();
	}

	
	function generarArchivos()
	{
		return $this->modelo->generarArchivos();
	}
	
	function generarSP()
	{
		return $this->modelo->generarSP();
	}
}

?>