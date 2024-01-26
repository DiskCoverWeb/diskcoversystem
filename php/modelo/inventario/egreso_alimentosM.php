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
	function buscar_producto()
	{
		$sql = "SELECT TK.*,C.Cliente,CP.Producto,CP.Unidad 
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C on TK.Codigo_P = C.Codigo
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo
			AND TK.T ='E'  ";
		return $this->db->datos($sql);
	}

}

?>