<?php 
require(dirname(__DIR__,2).'/modelo/facturacion/notas_creditoM.php');

$controlador = new notas_creditoC();
if(isset($_GET['tabla']))
{
	$parametros = array();
	echo json_encode($controlador->cargar_tabla($parametros));
}

if(isset($_GET['cliente']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->Listar_Facturas_Pendientes_NC($q));
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

	function Listar_Facturas_Pendientes_NC()
	{
		$datos = $this->modelo->Listar_Facturas_Pendientes_NC();
		$cli = array();	
		foreach ($datos as $key => $value) {
			$cli[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
		}
		return $cli;
	}
}
?>