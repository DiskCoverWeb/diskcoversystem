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

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


function DLGasto($SubCta,$query=false)
{ 
    $sql = "SELECT Codigo+'  .  '+Cuenta As Nombre_Cta, TC, Codigo
            FROM Catalogo_Cuentas
            WHERE Item = '" .$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND DG = 'D' ";
            if($query)
            {
            	$sql.=" AND Cuenta LIKE '%".$query."%'";
            }
	  if($SubCta == "C"){ $sql.=" AND TC IN ('CC','I') "; }else{ $sql.=" AND TC IN ('CC','G') ";}
	  $sql.=" ORDER BY Codigo ";
	  $datos = $this->db->datos($sql);
	  return $datos;
 }

 function DLSubModulo($SubCta,$query=false)
 {  
   $sql= "SELECT Detalle, Codigo 
        FROM Catalogo_SubCtas 
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        if($query)
        {
        	$sql.=" AND Detalle LIKE '%".$query."%'";
        }
	  if($SubCta == "C" ){ $sql.=" AND TC IN ('CC','I') "; }else{ $sql.=" AND TC IN ('CC','G') ";}
	  $sql.=" ORDER BY Detalle ";
	  $datos = $this->db->datos($sql);
	  return $datos;
}

function DLCxCxP($SubCta,$query=false)
{	  
  $sql = "SELECT Codigo+'  .  '+Cuenta As Nombre_Cta, Codigo
       	 FROM Catalogo_Cuentas
       	 WHERE Item = '".$_SESSION['INGRESO']['item']."'
       	 AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
       	 AND DG = 'D'
       	 AND TC = '".$SubCta."'";
       	 if($query)
        {
        	$sql.=" AND Cuenta LIKE '%".$query."%'";
        }
       	 $sql.=" ORDER BY Codigo ";
       	   $datos = $this->db->datos($sql);
	  return $datos;
}
	
}
?>