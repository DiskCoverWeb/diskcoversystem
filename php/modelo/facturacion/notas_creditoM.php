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
}
?>