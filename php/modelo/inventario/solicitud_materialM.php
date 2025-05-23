<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class solicitud_materialM
{
    private $conn ;

    function __construct()
    {
        $this->conn = new db();
    }

    function cargar_productos($fami=false,$query=false,$pag=false)
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
		 AND TC='P' 
		 AND LEN(Cta_Inventario)>3 
		 AND LEN(Cta_Costo_Venta)>3 ";
		 if($fami)
		 {
		 	$sql.=" AND Codigo_Inv like '".$fami."%'";
		 }
		if($query) 
		{
			$sql.=" AND Codigo_Inv+' '+Producto LIKE '%".$query."%'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function cargar_familia($query=false,$pag=false)
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

	function cargar_marca($query=false,$pag=false)
	{
		if($pag==false)
		{
			$pag = 0;
		}

		$cid = $this->conn;
		$sql = "SELECT ".Full_Fields('Catalogo_Marcas')."
		FROM Catalogo_Marcas 
		 WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		 AND item='".$_SESSION['INGRESO']['item']."' ";
		if($query) 
		{
			$sql.=" AND Marca+' '+CodMar LIKE '%".$query."%'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";

		// print_r($sql);die();
		
		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function lineas_pedido()
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, TP.Codigo_Inv, Hora, TP.Producto, TP.Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta, 
TP.Item, CodigoU, Orden_No, Cta_Venta_0, TP.TC, TP.Factura, Autorizacion, Serie, TP.Codigo_Sup, CodigoC, Opc1, Opc2, Opc3, 
TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,CM.Marca,CP.Unidad,Cantidad_Total 

		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Catalogo_Productos CP on TP.Codigo_Inv = CP.Codigo_Inv 
		WHERE CM.Item = TP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = CP.Periodo 
		AND TP.Item = CP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TP.TC = 'P' 
		AND TP.Item='".$_SESSION['INGRESO']['item']."' 
		AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function eliminar_linea($id)
	{
		$sql = "DELETE FROM Trans_Pedidos WHERE ID = '".$id."'";

		// print_r($sql);die();
		return $this->conn->String_Sql($sql);
	}

	function EliminarSolicitud($orden,$tipo=false)
	{
		$sql = "DELETE FROM Trans_Pedidos WHERE Orden_No = '".$orden."'";
		if($tipo)
		{
			$sql.=" AND TC='".$tipo."'";
		}

		// print_r($sql);die();
		return $this->conn->String_Sql($sql);
	}
	// ------------------------------------------Aprobacion de solicitud---------------------------------------------------------

	function pedidos_contratista($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'S' 
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

				$sql.=" Group by TP.Fecha,Orden_No,Cliente ORDER BY TP.Fecha ";

				// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function lineas_pedido_solicitados($orden=false,$id=false)
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, TP.Codigo_Inv, Hora, TP.Producto, TP.Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta, 
TP.Item, CodigoU, Orden_No, Cta_Venta_0, TP.TC, TP.Factura, Autorizacion, Serie, TP.Codigo_Sup, CodigoC, Opc1, Opc2, Opc3, 
TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,CM.Marca,CP.Unidad,Cantidad_Total 

		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Catalogo_Productos CP on TP.Codigo_Inv = CP.Codigo_Inv 
		WHERE CM.Item = TP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = CP.Periodo 
		AND TP.Item = CP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TP.TC = 'S' 
		AND TP.Item='".$_SESSION['INGRESO']['item']."' ";
		if($orden)
		{
			$sql.=" AND Orden_No = '".$orden."' ";
		}
				
		if($id)
		{
			$sql.=" AND TP.ID = '".$id."' ";
		}

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function pedido_solicitados($query)
	{
		$sql = "SELECT  Orden_No,Nombre_Completo
		FROM Trans_Pedidos T
		INNER JOIN Accesos A on T.CodigoU = A.Codigo
		WHERE  TC = 'S' ";
		if($query)
		{
			$sql.=" AND Orden_No+' '+Nombre_Completo like  '%".$query."%'";
		}
		$sql.="
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND Item='".$_SESSION['INGRESO']['item']."' 
		AND CodigoU ='".$_SESSION['INGRESO']['CodigoU']."' 
		group by Orden_No,Nombre_Completo ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	// -------------------------------------------envio solicitud proveedor-------------------------------------------------

	function envio_pedidos_contratista($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'E' 
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

				$sql.=" Group by TP.Fecha,Orden_No,Cliente ORDER BY TP.Fecha";

				// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}


	function pedido_solicitados_proveedor($query)
	{
		$sql = "SELECT  Orden_No,Nombre_Completo
		FROM Trans_Pedidos T
		INNER JOIN Accesos A on T.CodigoU = A.Codigo
		WHERE  TC = 'E' ";
		if($query)
		{
			$sql.=" AND Orden_No+' '+Nombre_Completo like  '%".$query."%'";
		}
		$sql.="
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND Item='".$_SESSION['INGRESO']['item']."' 
		group by Orden_No,Nombre_Completo ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}


	function lineas_pedido_solicitados_proveedor($orden)
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, TP.Codigo_Inv, Hora, TP.Producto, TP.Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta, 
TP.Item, CodigoU, Orden_No, Cta_Venta_0, TP.TC, TP.Factura, Autorizacion, Serie, TP.Codigo_Sup, CodigoC, Opc1, Opc2, Opc3, 
TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,CM.Marca,CP.Unidad 

		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Catalogo_Productos CP on TP.Codigo_Inv = CP.Codigo_Inv 
		WHERE CM.Item = TP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = CP.Periodo 
		AND TP.Item = CP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TP.TC = 'E' 
		 AND Orden_No = '".$orden."'
		AND TP.Item='".$_SESSION['INGRESO']['item']."' ";
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function Trans_Pedidos($id=false,$orden=false,$tc=false)
	{
		$sql = "SELECT  ".Full_Fields('Trans_Pedidos')." 
		FROM Trans_Pedidos 
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND Item='".$_SESSION['INGRESO']['item']."' ";
		if($tc)
		{
			$sql.=" AND TC = '".$tc."' ";
		}
		if($orden)
		{
		  $sql.=" AND Orden_No = '".$orden."' ";
		}

		if($id)
		{
		  $sql.=" AND ID = '".$id."' ";
		}

// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function proveedores($query=false,$CodigoC=false)
	{
		$sql = "SELECT CI_RUC,Cliente,CP.Codigo as 'Codigo'
		FROM Clientes C
		INNER JOIN Catalogo_CxCxP CP ON C.Codigo = CP.Codigo
		WHERE CP.Item = '".$_SESSION['INGRESO']['item']."' 
		AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND LEN(Cliente)>1 AND CP.TC  ='P' ";
		if($query)
		{
			$sql.="	AND Cliente like '%".$query."%'";
		}
		if($CodigoC)
		{
			$sql.="	AND C.Codigo = '".$CodigoC."'";
		}
		$sql.="group by CI_RUC,Cliente,CP.Codigo
		ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
		$datos = $this->conn->datos($sql);
       	return $datos;

	}

	// ----------------------------------aprobacion solicitud proveedor----------------------------------------------------

	function lista_pedido_aprobacion_solicitados_proveedor($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'T' 
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

				$sql.=" Group by TP.Fecha,Orden_No,Cliente";

				// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}


	function pedido_aprobacion_solicitados_proveedor($query)
	{
		$sql = "SELECT  Orden_No,Nombre_Completo
		FROM Trans_Pedidos T
		INNER JOIN Accesos A on T.CodigoU = A.Codigo
		WHERE  TC = 'T' ";
		if($query)
		{
			$sql.=" AND Orden_No+' '+Nombre_Completo like  '%".$query."%'";
		}
		$sql.="
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND Item='".$_SESSION['INGRESO']['item']."' 
		group by Orden_No,Nombre_Completo ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function lineas_pedido_aprobacion_solicitados_proveedor($orden=false,$codigo_inv=false,$id=false)
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, TP.Codigo_Inv, Hora, TP.Producto, TP.Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta,
				TP.Item, CodigoU, Orden_No, Cta_Venta_0, TP.TC, TP.Factura, Autorizacion, Serie, TP.Codigo_Sup, CodigoC, Opc1, Opc2,
				Opc3,TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,CM.Marca,CP.Unidad 

		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Catalogo_Productos CP on TP.Codigo_Inv = CP.Codigo_Inv 
		WHERE CM.Item = TP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = CP.Periodo 
		AND TP.Item = CP.Item 
		AND CM.Periodo = TP.Periodo 
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TP.TC = 'T' 
		AND Tp.Item='".$_SESSION['INGRESO']['item']."'  ";
		if($orden)
		{
			$sql.=" AND Orden_No = '".$orden."' ";
		}
		if($codigo_inv)
		{
			$sql.=" AND TP.Codigo_Inv = '".$codigo_inv."'";
		}
		if($id)
		{
			$sql.=" AND TP.ID = '".$id."'";
		}
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function proveedores_seleccionados_x_producto($producto,$orden=false,$proveedor=false,$cantidad=false)
	{
		$sql = "SELECT  T.*,C.*,T.ID as IDT 
		FROM Trans_Ticket  T
		INNER JOIN Clientes C ON T.CodigoC = C.Codigo
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND  Item='".$_SESSION['INGRESO']['item']."' 
		AND Codigo_Inv = '".$producto."' ";
		if($orden)
		{
			$sql.="AND Orden_No = '".$orden."'";
		}
		if($proveedor)
		{
			$sql.="AND CodigoC = '".$proveedor."'";
		}
		// if($id)
		// {
		// 	$sql.=" AND T.ID='".$id."'";
		// }
		if($cantidad)
		{
			$sql.=" AND T.Cantidad='".$cantidad."'";
		}

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}
} 