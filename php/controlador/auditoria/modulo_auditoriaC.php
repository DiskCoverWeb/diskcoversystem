<?php
require_once dirname(__DIR__, 2) . '/db/session_guard.php';
include('../modelo/modulo_auditoriaM.php');
$controlador = new modulo_auditoriaC();
/**
 * 
 */

if(isset($_GET['']))
{
	echo json_encode($controlador->);
}
class modulo_auditoriaC
{
	private $auditoria;	
	function __construct()
	{
		$this->auditoria = new modulo_auditoriaM();
	}
}

?>