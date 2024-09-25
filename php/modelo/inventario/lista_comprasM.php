<?php 

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
/**
 * 
 */
class lista_comprasM
{
	private $conn ;
	
	function __construct()
	{
  			$this->conn = new db();
	}

	function pedidos_compra_contratista($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,TP.Fecha_Ent,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'B' 
				AND Item='".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."' ";
				}
				if($fecha)
				{
					$sql.=" AND TP.Fecha = '".$fecha."' ";
				}	
				if($contratista)
				{
					$sql.=" AND Cliente like '%".$contratista."%' ";
				}		

				$sql.=" Group by TP.Fecha,TP.Fecha_Ent,Orden_No,Cliente";
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function pedidos_compra_solicitados($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,TP.Fecha_Ent,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'B' 
				AND Item='".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."' ";
				}
				if($fecha)
				{
					$sql.=" AND TP.Fecha = '".$fecha."' ";
				}	
				if($contratista)
				{
					$sql.=" AND Cliente like '%".$contratista."%' ";
				}		

				$sql.=" Group by TP.Fecha,TP.Fecha_Ent,Orden_No,Cliente";
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function lineas_compras_solicitados($orden=false,$id=false,$codigoC=false)
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, Codigo_Inv, Hora, Producto, Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta, TP.Item, TP.CodigoU, Orden_No, Cta_Venta_0, TC, Factura, Autorizacion, Serie, Codigo_Sup, CodigoC, Opc1, Opc2, Opc3, TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,Marca,C.Cliente as 'proveedor' 
		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Clientes C on TP.CodigoC = C.Codigo

		WHERE  CM.Item = TP.Item
		AND CM.Periodo = TP.Periodo
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC = 'B' 
		AND TP.Item='".$_SESSION['INGRESO']['item']."' ";
		if($orden)
		{
			$sql.=" AND Orden_No = '".$orden."' ";
		}
				
		if($id)
		{
			$sql.=" AND TP.ID = '".$id."' ";
		}

		if($codigoC)
		{
			$sql.=" AND TP.CodigoC = '".$codigoC."' ";
		}

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function lineas_compras_solicitados_proveedores($orden=false,$id=false,$codigoC=false)
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, Codigo_Inv, Hora, Producto, Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta, TP.Item, TP.CodigoU, Orden_No, Cta_Venta_0, TC, Factura, Autorizacion, Serie, Codigo_Sup, CodigoC, Opc1, Opc2, Opc3, TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,Marca,C.Cliente as 'proveedor' 
		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Clientes C on TP.CodigoC = C.Codigo

		WHERE  CM.Item = TP.Item
		AND CM.Periodo = TP.Periodo
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC = 'B' 
		AND TP.Item='".$_SESSION['INGRESO']['item']."' ";
		if($orden)
		{
			$sql.=" AND Orden_No = '".$orden."' ";
		}
				
		if($id)
		{
			$sql.=" AND TP.ID = '".$id."' ";
		}

		if($codigoC)
		{
			$sql.=" AND TP.CodigoC = '".$codigoC."' ";
		}

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function buscar_familia($query=false,$pag=false)
	{
		if($pag==false)
		{
			$pag = 0;
		}

		$cid = $this->conn;
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Ubicacion,IVA,Reg_Sanitario 
		FROM Catalogo_Productos 
		 WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		 AND item='".$_SESSION['INGRESO']['item']."' 
		 AND TC='I' ";
		if($query) 
		{
			$sql.=" AND Codigo_Inv = '".$query."'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";

		// print_r($sql);
		// die();
		
		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function pedidos_compra_x_proveedor($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,TP.Fecha_Ent,Orden_No,SUM(Total) as Total,C.Cliente,C2.Cliente as 'proveedor',TP.CodigoC
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				inner Join Clientes C2 on TP.CodigoC = C2.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'B' 
				AND Item='".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."' ";
				}
				if($fecha)
				{
					$sql.=" AND TP.Fecha = '".$fecha."' ";
				}	
				if($contratista)
				{
					$sql.=" AND Cliente like '%".$contratista."%' ";
				}		

				$sql.=" Group by TP.Fecha,TP.Fecha_Ent,Orden_No,C.Cliente,C2.Cliente,TP.CodigoC ";

				// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}


}


?>