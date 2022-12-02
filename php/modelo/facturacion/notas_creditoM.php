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

   funtion Dlineas()
   {
   	   sSQL = "SELECT Codigo, Concepto, CxC 
       FROM Catalogo_Lineas 
       WHERE Fact = 'NC' 
       AND Item = '" & NumEmpresa & "' 
       AND Periodo = '" & Periodo_Contable & "' 
       AND Fecha <= #" & BuscarFecha(MBoxFecha) & "# 
       AND Vencimiento >= #" & BuscarFecha(MBoxFecha) & "# "
  If Len(FA.Cta_CxP) > 2 Then sSQL = sSQL & "AND '" & FA.Cta_CxP & "' IN (CxC,CxC_Anterior) "
  sSQL = sSQL & "ORDER BY CxC, Concepto "
  SelectDB_Combo DCLinea,
   }

}
?>