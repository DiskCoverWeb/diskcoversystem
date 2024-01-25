<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("../../modelo/inventario/egreso_alimentosM.php");
include(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new egreso_alimentosC();
 
if(isset($_GET['areas']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_areas($query));
} 
if(isset($_GET['motivos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_motivo($query));
}

/**
 * 
 */
class egreso_alimentosC
{
	private $modelo;
	private $pdf;

	function __construct()
	{
		$this->modelo = new egreso_alimentosM();
		$this->pdf = new cabecera_pdf();

	}

	function ddl_areas($query)
	{
		$datos = $this->modelo->areas($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['ID'],'text'=>$value['Proceso'],'data'=>$value);			
		}

		return $op;

	}

	function ddl_motivo($query)
	{
		$datos = $this->modelo->motivo_egreso($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['ID'],'text'=>$value['Proceso'],'data'=>$value);			
		}

		return $op;

	}	
}


?>
