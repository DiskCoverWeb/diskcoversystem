<?php 
require_once(dirname(__DIR__,2)."/modelo/facturacion/trans_correosM.php");


$controlador = new trans_correosC();
if(isset($_GET['guardar']))
{
	$parametros = $_POST;
	echo json_decode($controlador->guardar($parametros));
}
if(isset($_GET['alimentos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cta_procesos($query));
}
if(isset($_GET['detalle_ingreso']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->detalle_ingreso($query));
}
/**
 * 
 */
class trans_correosC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new trans_correosM();
	}

	function guardar($parametros)
	{
		print_r($parametros);die();

	}
	function cta_procesos($query)
	{
		$datos = $this->modelo->cta_procesos($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['ID'],'text'=>$value['Proceso']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;

	}

	function detalle_ingreso($query)
	{
		$datos = $this->modelo->detalle_ingreso($query);
		// $bene = array();
		// foreach ($datos as $key => $value) {
		// 	$bene[] = array('id'=>$value['ID'],'text'=>$value['Cliente']);
		// 	// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		// }
		return $datos;
	}


}

?>