<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class proveedor_bodegaM
{
	private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function add($tabla,$datos)
	{
		return insert_generico($tabla,$datos);
	}
	function update($tabla,$datos,$where)
	{
		return update_generico($datos,$tabla,$where);
	}

	function lista_clientes($query=false,$ci=false,$id=false)
	{

       $sql= "SELECT TOP 100 *
        FROM Clientes WHERE 1=1 ";

		// 02 es facturacion
		if($_SESSION['INGRESO']['modulo_']=='02')
		{
			$sql.= " AND FA <> 0 ";
		}else
		{
			$sql.=" AND Codigo <> '.' ";
		}
		if($query)
		{
			$sql.=" AND Cliente+' '+CI_RUC LIKE '%".$query."%'";
		}
		if($ci)
		{
			$sql.=" AND CI_RUC = '".$ci."' ";
		}

		if($id)
		{
			$sql.=" AND ID = '".$id."' ";
		}
		$sql.=" ORDER BY ID DESC";

		// print_r($sql);die();
		return $this->db->datos($sql);

	}

	function delete_clientes($id)
	{

       $sql= "DELETE FROM Clientes WHERE ID = '".$id."'";
		// print_r($sql);die();
		return $this->db->String_Sql($sql);

	}

	function Catalogo_CxCxP($Cta_Aux,$codigoCliente,$SubCta)
	{
		 $sql = "SELECT * 
		    FROM Catalogo_CxCxP 
		    WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		    AND Cta = '".$Cta_Aux."' 
		    AND Codigo = '".$codigoCliente."' 
		    AND TC = '".$SubCta."' ";
		return $this->db->datos($sql);

	} 



}

?>