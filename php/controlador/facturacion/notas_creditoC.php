<?php 
require(dirname(__DIR__,2).'/modelo/facturacion/notas_creditoM.php');

$controlador = new notas_creditoC();
if(isset($_GET['tabla']))
{
	$parametros = array();
	echo json_encode($controlador->cargar_tabla($parametros));
}

/**
 * 
 */
class notas_creditoC
{
	private $modelo;	
	function __construct()
	{
		$this->modelo = new notas_creditoM(); 
		// code...
	}

	function cargar_tabla($parametro)
	{
		return  $this->modelo->cargar_tabla($parametro,$tabla=1);
	}
}
?>