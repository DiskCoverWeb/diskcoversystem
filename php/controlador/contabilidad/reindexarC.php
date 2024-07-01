<?php 

include(dirname(__DIR__,2).'/modelo/contabilidad/reindexarM.php');

$controlador = new reindexarC();
if(isset($_GET['reindexarT']))
{
	echo json_encode($controlador->reindexarT());
}


class reindexarC
{

	private $modelo;	
  	private $pdf;

	function __construct()
	{
		$this->modelo = new reindexarM();
	}

	function reindexarT()
	{

		// print_r($_SESSION['INGRESO']);die();
		try {
			Reindexar_Periodo_sp(); 
			Mayorizar_Cuentas_SP();
			Presenta_Errores_Contabilidad_SP();	
			return 1;		
		} catch (Exception $e) {
			return -1;
		}
	}

}

?>