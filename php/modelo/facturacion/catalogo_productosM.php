<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class catalogo_productosM
{
	
	function __construct()
	{
	    $this->db = new db();
	}

	function TVCatalogo($query=false,$TC=false,$len=false,$codigo=false)
	{
		
		// print_r($cuenta);die();
	   $sql="SELECT *
       FROM Catalogo_Productos 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
       if($TC)
       {
       	 $sql.=" AND TC='".$TC."'";
       }
       if($len)
       {
       	 $sql.=" AND LEN(Codigo_Inv) =".$len;
       }
       if($query)
       	{
       		$sql.=" AND Codigo_Inv like '".$query.".%'";
       	}
       	if($codigo)
       	{
       		$sql.=" AND Codigo_Inv = '".$codigo."'";
       	}
       	$sql.=" ORDER BY Codigo_Inv";
       	// print_r($sql);die();
       	return $this->db->datos($sql);
	}

	function eliminar_cuenta($id)
	{
		$sql="DELETE FROM Catalogo_Productos WHERE Codigo_Inv like '".$id."%'";
		return $this->db->String_Sql($sql);
	}

	function trans_kardex($codigo)
	{
		 $sql="SELECT * 
          	FROM Trans_Kardex 
          	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          	AND Codigo_Inv like '".$codigo."%' ";
       	return $this->db->datos($sql);
	}

	function detalle_factura($codigo)
	{
          $sql="SELECT * 
          FROM Detalle_Factura 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND Codigo like '" .$codigo."%' ";     
          return $this->db->datos($sql);
	}
}
?>