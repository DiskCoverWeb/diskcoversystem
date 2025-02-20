<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start();


/**
 * 
 */
class reporte_constructora_ComprasM
{	
	private $db;

    public function __construct(){
        $this->db = new db();
    }

    function cargar_datos($parametros)
    {
    	// print_r($parametros);die();
    	$sql = "SELECT T.Fecha,SUM(Valor_Total) as valor_compra,Orden_No,Codigo_P,SUM(Costo*Entrada) as valor_ref,Cliente 
				FROM Trans_Kardex T 
				INNER JOIN Clientes C on T.CodigoU = C.Codigo 
				WHERE T.Item = '".$_SESSION['INGRESO']['item']."'
				AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Serie_No = '999999'";
    	if($parametros['contratista'])
    	{
    		$sql.=" AND T.CodigoU = '".$parametros['contratista']."'";
    	}
    	if($parametros['orden'])
    	{
    		$sql.=" AND Orden_No = '".$parametros['orden']."'";
    	}
    	if($parametros['fecha'])
    	{
    		$sql.=" AND T.Fecha = '".$parametros['fecha']."'";
    	}
    	if($parametros['semanas'])
    	{
    		$sql.="AND DATEPART(week, T.Fecha) = ".$parametros['semanas'];
    	}
    	if($parametros['mes'])
    	{
    		$sql.="AND MONTH(T.Fecha) = ".$parametros['mes'];
    	}
    	$sql.=" Group by T.Fecha,Orden_No,Codigo_P,Cliente";
    	// print_r($sql);die();
    	return $this->db->datos($sql);
    }

    function lineas_pedidos_orden($parametros)
    {
    	$sql =" SELECT Cliente,Orden_No,DATEPART(week, T.Fecha) as semana,
    			FORMAT(T.Fecha, 'dddd, dd \"de\" MMMM \"de\" yyyy','es-ES') AS Fecha,SUM(T.Valor_Total) as total 
    			FROM Trans_Kardex T 
    			INNER JOIN Catalogo_Productos CP on T.Codigo_Inv = CP.Codigo_Inv
    			INNER JOIN Clientes C on T.CodigoU = C.Codigo 
    			WHERE T.Item = '".$_SESSION['INGRESO']['item']."'
				AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Serie_No = '999999'
				AND T.Item = CP.Item
				AND T.Periodo = CP.Periodo ";
				if($parametros['contratista'])
		    	{
		    		$sql.=" AND T.Codigo_P = '".$parametros['contratista']."'";
		    	}
		    	if($parametros['orden'])
		    	{
		    		$sql.=" AND Orden_No = '".$parametros['orden']."'";
		    	}
		    	if($parametros['fecha'])
		    	{
		    		$sql.=" AND T.Fecha = '".$parametros['fecha']."'";
		    	}
		    	if($parametros['semanas'])
		    	{
		    		$sql.="AND DATEPART(week, T.Fecha) = ".$parametros['semanas'];
		    	}
		    	if($parametros['mes'])
		    	{
		    		$sql.="AND MONTH(T.Fecha) = ".$parametros['mes'];
		    	}	
				$sql.=" group by Cliente,Orden_No,T.Fecha";

				// print_r($sql);die();


    	return $this->db->datos($sql);
    }


    function cargar_datos_historial($parametros)
    {
    	$sql =" SELECT Orden_No,FORMAT(T.Fecha, 'dddd, dd \"de\" MMMM \"de\" yyyy','es-ES') AS Fecha,
    			Cliente,Entrada,Valor_Unitario,T.Valor_Total
    			FROM Trans_Kardex T 
    			INNER JOIN Catalogo_Productos CP on T.Codigo_Inv = CP.Codigo_Inv
    			INNER JOIN Clientes C on T.Codigo_P = C.Codigo 
    			WHERE T.Item = '".$_SESSION['INGRESO']['item']."'
				AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Serie_No = '999999'
				AND T.Item = CP.Item
				AND T.Periodo = CP.Periodo ";
				if($parametros['articulos'])
		    	{
		    		$sql.=" AND T.Codigo_Inv = '".$parametros['articulos']."'";
		    	}
		    	if($parametros['orden'])
		    	{
		    		$sql.=" AND Orden_No = '".$parametros['orden']."'";
		    	}
		    	if($parametros['fecha'])
		    	{
		    		$sql.=" AND T.Fecha = '".$parametros['fecha']."'";
		    	}
		    	if($parametros['semanas'])
		    	{
		    		$sql.="AND DATEPART(week, T.Fecha) = ".$parametros['semanas'];
		    	}
		    	if($parametros['mes'])
		    	{
		    		$sql.="AND MONTH(T.Fecha) = ".$parametros['mes'];
		    	}	
				$sql.=" group by Cliente,Orden_No,T.Fecha,Entrada,Valor_Unitario,T.Valor_Total ";

				// print_r($sql);die();


    	return $this->db->datos($sql);
    }


    function lineas_pedidos($orden,$proveedor=false)
    {
    	$sql = "SELECT T.*,Producto,Cliente
    	FROM Trans_Kardex T
    	INNER JOIN Catalogo_Productos CP on T.Codigo_Inv = CP.Codigo_Inv
    	INNER JOIN Clientes C on T.Codigo_P = C.Codigo
    	WHERE T.Item = '".$_SESSION['INGRESO']['item']."'
    	AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
    	AND T.Item = CP.Item
    	AND T.Periodo = CP.Periodo ";
    	if($orden)
    	{
    		$sql.=" AND Orden_No = '".$orden."'";
    	}  
    	if($proveedor)
    	{
    		$sql.=" AND T.Codigo_P = '".$proveedor."'";
    	}    	
    	// print_r($sql);die();
    	return $this->db->datos($sql);

    }


    function orden($query,$contratista=false)
    {
    	$sql= "SELECT Orden_No as id,Orden_No as text
				FROM Trans_Kardex TK 
				WHERE Item = '".$_SESSION['INGRESO']['item']."'
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Serie_No = '999999'";
				if($query)
				{
					$sql.=" AND Orden_No like'%".$query."%'";
				}
				if($contratista)
				{
					$sql.=" AND Codigo_P = '".$contratista."'";
				}
		$sql.= " group by Orden_No";

		// print_r($sql);die();
    	return $this->db->datos($sql);
    }

    function contratistas($query)
    {
    	$sql= "SELECT TK.CodigoU as id,Cliente as text 
				FROM Trans_Kardex TK 
				inner join Clientes C ON TK.CodigoU = C.Codigo
				WHERE Item= '".$_SESSION['INGRESO']['item']."'
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Entrada <> 0 ";
				if($query)
				{
					$sql.=" AND Nombre_Completo like'%".$query."%'";
				}
		$sql.="	group by TK.CodigoU,Cliente ";
    	return $this->db->datos($sql);
    }

    function articulos($query=false)
    {
    	$sql = "SELECT Codigo_Inv as id,Producto as text
				FROM Catalogo_Productos
				WHERE Item = '".$_SESSION['INGRESO']['item']."'
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		if($query)
		{
		   $sql.=" AND Producto like '".$query."%'";
		}
		// print_r($sql);die();
		return $this->db->datos($sql);
    }
}

?>