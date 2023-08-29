<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class trans_correosM
{
	private $db;
	function __construct()
	{
	    $this->db = new db();
	}

	// function insert($tabla,$datos)
	// {
	// 	return insert_generico($tabla,$datos);
	// }

	function cta_procesos($query=false)
	{
		$sql="SELECT  TP, Proceso, Cta_Debe, Cta_Haber, ID
			FROM         Catalogo_Proceso
			WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
			AND Nivel = 99";
			if($query)
			{
				$sql.=" AND Proceso Like '%".$query."%'";
			}
			$sql.= " ORDER BY TP";
			// print_r($sql);die();
		return $this->db->datos($sql);
	}
	function detalle_ingreso($query=false)
	{
		$sql="SELECT C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo 
        FROM Clientes As C,Catalogo_CxCxP As CP 
        WHERE CP.TC = 'P' 
        AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
        AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND C.Codigo<>'.'
        AND LEN(C.Cod_Eje)>=5
        AND C.Codigo = CP.Codigo 
        GROUP BY C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo 
        ORDER BY C.Cliente";
        // print_r($sql);die();
		return $this->db->datos($sql);
	}


}
?>