<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
	
/**
 * 
 */
class notas_creditoM
{
	private $db;
	function __construct()
	{		
      $this->db = new db();
	}



	function cargar_tabla($parametro,$tabla = false)
	{
		$sql = "SELECT *
        FROM Asiento_NC
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
        ORDER BY A_No ";
        if($tabla)
        {
	        $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-170;
			$tbl = grilla_generica_new($sql,'Transacciones As T,Comprobantes As C,Clientes As Cl','tbl_lib',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
		}else
		{
			$tbl = $this->db->datos($sql);
		}
       return $tbl;
	}

	function Listar_Facturas_Pendientes_NC()
	{ 
		$sql = "SELECT C.Grupo, C.Codigo, C.Cliente, F.Cta_CxP, SUM(F.Total_MN) As TotFact 
       	FROM Clientes As C, Facturas As F 
       	WHERE F.Item = '".$_SESSION['INGRESO']['item']."' 
       	AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       	AND NOT F.TC IN ('DO','OP') 
       	AND F.T <> 'A' 
       	AND F.Saldo_MN <> 0 
       	AND C.Codigo = F.CodigoC 
       	GROUP BY C.Grupo, C.Codigo, C.Cliente, F.Cta_CxP 
       	ORDER BY C.Cliente ";
       	return $this->db->datos($sql);
   }

   function Dlineas($MBoxFecha,$Cta_CxP)
   {
   	   $sql = "SELECT Codigo, Concepto, CxC 
       FROM Catalogo_Lineas 
       WHERE Fact = 'NC' 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND Fecha <= '".BuscarFecha($MBoxFecha)."' 
       AND Vencimiento >= '".BuscarFecha($MBoxFecha)."' ";
       if(strlen($Cta_CxP) > 2 ){ $sql.=" AND '".$Cta_CxP."' IN (CxC,CxC_Anterior) ";}
	  	$sql.=" ORDER BY CxC, Concepto ";
	  	return $this->db->datos($sql);
   }

   function delete_asiento_nc()
   {
   	  $sql = "DELETE
	  FROM Asiento_NC 
	  WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	  AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
	  return $this->db->String_Sql($sql);
   }

   function catalogo_bodega(){
       $sql = "SELECT * 
       FROM Catalogo_Bodegas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       ORDER BY CodBod, Bodega ";
       return $this->db->datos($sql);
	}

	function catalogo_marca()
	{   
	  $sql = "SELECT * 
	  FROM Catalogo_Marcas 
	  WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	  ORDER BY Marca ";
	  return $this->db->datos($sql);
	}


	function Catalogo_Cuentas($query)
	{  
	  $sql = "SELECT Codigo,Codigo+SPACE(10)+Cuenta As NomCuenta 
	    FROM Catalogo_Cuentas 
	    WHERE SUBSTRING(Codigo,1,1) IN ('1','2','4','5') 
	    AND DG = 'D' 
	    AND Item = '".$_SESSION['INGRESO']['item']."' 
	    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
	    if($query)
	    {
	    	$sql.=" AND Cuenta like '%".$query."%'";
	    }
	    $sql.="  ORDER BY Codigo ";
	    return $this->db->datos($sql);
	}

	function Catalogo_Productos($query)
	{  
	  $sql = "SELECT Producto, Codigo_Inv, PVP, IVA, Cta_Inventario 
	    FROM Catalogo_Productos 
	    WHERE TC = 'P' 
	    AND Item = '".$_SESSION['INGRESO']['item']."' 
	    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
	    if($query)
	    {
	    	$sql.=" AND Producto like '%".$query."%'";
	    } 
	    $sql.=" ORDER BY Producto ";
	    return $this->db->datos($sql);
	}

}
?>