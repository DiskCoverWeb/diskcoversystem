<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class alimentos_recibidosM
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
	function detalle_ingreso($cod=false,$query=false,$ci=false)
	{
		$sql="SELECT C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo,C.Actividad,C.Cod_Ejec 
        FROM Clientes As C,Catalogo_CxCxP As CP 
        WHERE CP.TC = 'P' 
        AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
        AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND C.Codigo<>'.'
        AND LEN(C.Cod_Ejec)<=5
        AND C.Codigo = CP.Codigo";
        if($cod)
        {
        	$sql.=" AND C.Codigo = '".$cod."'";
        } 
        if($ci)
        {
        	$sql.=" AND C.CI_RUC = '".$ci."'";
        } 
        $sql.=" GROUP BY C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo,C.Actividad,C.Cod_Ejec   
        ORDER BY C.Cliente";
        // print_r($sql);die();
		return $this->db->datos($sql);
	}

	function buscar_transCorreos($cod)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec 
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		where Envio_No  like  '%".$cod."%'";
		return $this->db->datos($sql);
	}
	//------------------viene de trasnkardex--------------------

	function cargar_pedidos_trans($orden,$fecha=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT T.*,P.Producto 
     FROM Trans_Kardex  T ,Catalogo_Productos P
     WHERE Orden_No = '".$orden."' ";
     // AND T.CodigoL = '".$SUBCTA."'
     // AND T.Codigo_P = '".$paciente."'
     $sql.="AND Numero =0
     AND T.Item = P.Item
     AND T.Periodo = P.Periodo
	 AND T.Codigo_Inv = P.Codigo_Inv";
     if($fecha)
     {
     	$sql.=" AND T.Fecha = '".$fecha."'";
     }     
     $sql.=" ORDER BY T.ID DESC";
     // print_r($sql);die();

     return $this->db->datos($sql);
       
	}
	function lineas_eli($parametros)
	{
		$sql = "DELETE FROM Trans_Kardex WHERE Orden_No='".$parametros['ped']."' and ID ='".$parametros['lin']."'";
		return $this->db->String_Sql($sql);
	}


}
?>