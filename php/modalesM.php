<?php 
include(dirname(__DIR__,1).'/funciones/funciones.php');
require_once("../db/db1.php");

/**
 * 
 */
class modalesM
{
	private $db;	
	function __construct()
	{
		$this->db = new db();
	}

	function buscar_cliente($ci=false,$nombre=false)
	{
		$sql="SELECT Cliente AS nombre, CI_RUC as id, email,Direccion,Telefono,Codigo,Grupo,Ciudad,Prov,DirNumero,ID,FA
		    FROM Clientes  C
		    WHERE T <> '.' ";

		    if($nombre)
		    {
		    	$sql.=" AND  Cliente LIKE '%".$nombre."%' ";
		    }
		    if($ci)
		    {
		    	$sql.=" AND CI_RUC LIKE '".$ci."%' ";
		    }	
		$sql.=" ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

		print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	
}
?>