<?php 
// include(dirname(__DIR__,2).'/db/db1.php');//
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class egreso_alimentosM
{
	
	private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function areas($query)
	{
		$sql = "SELECT   Proceso, Cmds, ID
				FROM      Catalogo_Proceso
				WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				AND Nivel = 95
				AND TP = 'AREAEGRE' ";
			if($query)
			{
				$sql.=" AND Proceso like '%".$query."%' ";
			}
				$sql.=" ORDER BY Nivel, Proceso";
		return $this->db->datos($sql);
	}
	function motivo_egreso($query)
	{
		$sql = "SELECT   Proceso, Cmds, ID
			FROM      Catalogo_Proceso
			WHERE   Item = '".$_SESSION['INGRESO']['item']."' 
			AND Nivel = 94
			AND TP = 'MOTIVOS' ";
			if($query)
			{
				$sql.=" AND Proceso like '%".$query."%' ";
			}
			$sql.=" ORDER BY Nivel, TP, Cmds, Proceso";

		return $this->db->datos($sql);
	}

}

?>