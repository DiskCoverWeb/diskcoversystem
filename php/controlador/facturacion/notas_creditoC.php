<?php 
require(dirname(__DIR__,2).'/modelo/facturacion/notas_creditoM.php');

$controlador = new notas_creditoC();
if(isset($_GET['delete_sientos_nc']))
{
	echo json_encode($controlador->delete_sientos_nc());
}

if(isset($_GET['DCBodega']))
{
	echo json_encode($controlador->DCBodega());
}

if(isset($_GET['DCMarca']))
{
	echo json_encode($controlador->DCMarca());
}

if(isset($_GET['DCContraCta']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->DCContraCta($q));
}

if(isset($_GET['DCArticulo']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->DCArticulo($q));
}

if(isset($_GET['tabla']))
{
	$parametros = array();
	echo json_encode($controlador->cargar_tabla($parametros));
}

if(isset($_GET['DCLineas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCLineas($parametros));
}

if(isset($_GET['DCTC']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCTC($parametros));
}

if(isset($_GET['DCSerie']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCSerie($parametros));
}

if(isset($_GET['Detalle_Factura']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->Detalle_Factura($parametros));
}

if(isset($_GET['Lineas_Factura']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->Lineas_Factura($parametros));
}

if(isset($_GET['DCFactura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFactura($parametros));
}

if(isset($_GET['cliente']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->Listar_Facturas_Pendientes_NC($q));
}

/**
 * 
 */
class notas_creditoC
{
	private $modelo;	
	function __construct()
	{
		$this->modelo = new notas_creditoM(); 
		// code...
	}

	function DCBodega()
	{
		$datos =  $this->modelo->catalogo_bodega();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['CodBod'],'nombre'=>$value['Bodega']);
		}
		return $list;
	}

	function DCMarca()
	{
		$datos =  $this->modelo->catalogo_marca();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['CodMar'],'nombre'=>$value['Marca']);
		}
		return $list;
	}

	function DCContraCta($query)
	{
		$datos =  $this->modelo->Catalogo_Cuentas($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id'=>$value['Codigo'],'text'=>$value['NomCuenta'],'data'=>$value);
		}
		return $list;
	}

	function DCArticulo($query)
	{
		$datos =  $this->modelo->Catalogo_Productos($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$value);
		}
		return $list;
	}

	function cargar_tabla($parametro)
	{
		return  $this->modelo->cargar_tabla($parametro,$tabla=1);
	}

	function Listar_Facturas_Pendientes_NC()
	{
		$datos = $this->modelo->Listar_Facturas_Pendientes_NC();
		$cli = array();	
		foreach ($datos as $key => $value) {
			$cli[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
		}
		return $cli;
	}

	function DClineas($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DClineas($parametro['fecha'],$parametro['cta_cxp']);
		$list = array();		
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Concepto']); 
		}
		if(count($list)==0)
		{
			$list[] = array('codigo'=>'','nombre'=>'No exsiten datos');	
		}
		return $list;
	}

	function DCTC($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DCTC($parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['TC'],'nombre'=>$value['TC']); 
		}
		return $list;
	}

	function DCSerie($parametro)
	{
		$datos = $this->modelo->DCSerie($parametro['TC'],$parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']); 
		}
		return $list;
	}

	function DCFactura($parametro)
	{
		$datos = $this->modelo->DCFactura($parametro['Serie'],$parametro['TC'],$parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Factura'],'nombre'=>$value['Factura']); 
		}
		return $list;
	}

	function Detalle_Factura($parametro)
	{
		return $this->modelo->Factura_detalle($parametro['Factura'],$parametro['Serie'],$parametro['TC'],$parametro['CodigoC']);
	}


	function delete_sientos_nc()
	{
		return $this->modelo->delete_asiento_nc();
	}

	function Lineas_Factura($parametros)
	{
		// print_r($parametro);die();
     $DocConInv = false;
     $Ln_No = 0;
     $this->modelo->delete_asiento_nc();
    
     //LblSaldo.Caption = Format(FA.Saldo_MN, "#,##0.00")
     //LblTotal.Caption = Format(FA.Total_MN, "#,##0.00")
     $datos = $this->modelo->lineas_factura($parametros['Factura'],$parametros['Serie'],$parametros['TC'],$parametros['Autorizacion']);
     // print_r($datos);die();     
      if(count($datos)  > 0)
      {
          // $FA.Cod_Ejec = .fields("Cod_Ejec")
          // $FA.Porc_C = .fields("Porc_C")
          $NoMes = $datos[0]["Mes_No"];
          $MiMes = $datos[0]["Mes"];
          $Cod_Bodega = $datos[0]["CodBodega"];
          foreach ($datos as $key => $value) 
          {
             $Ok_Inv = Leer_Codigo_Inv($value["Codigo"], $parametros['Fecha']);
              // print_r($Ok_Inv);
              // print_r($datos);die();     
             $datosNC[0]['campo'] =  "CODIGO"; 
             $datosNC[0]['dato'] = $value[ "Codigo"];
             $datosNC[1]['campo'] =  "CANT"; 
             $datosNC[1]['dato'] = $value[ "Cantidad"];
             $datosNC[2]['campo'] =  "PRODUCTO"; 
             $datosNC[2]['dato'] = $value[ "Producto"];
             $datosNC[3]['campo'] =  "SUBTOTAL"; 
             $datosNC[3]['dato'] = $value[ "Total"];
             $datosNC[4]['campo'] =  "DESCUENTO"; 
             $datosNC[4]['dato'] = $value[ "Total_Desc"] + $value["Total_Desc2"];
             $datosNC[5]['campo'] =  "TOTAL_IVA"; 
             $datosNC[5]['dato'] = $value[ "Total_IVA"];
             $datosNC[6]['campo'] =  "CodBod"; 
             $datosNC[6]['dato'] = $value[ "CodBodega"];
             $datosNC[7]['campo'] =  "CodMar"; 
             $datosNC[7]['dato'] = $value[ "CodMarca"];
             $datosNC[8]['campo'] =  "Codigo_C"; 
             $datosNC[8]['dato'] =  $parametros['CodigoC'];
             $datosNC[9]['campo'] =  "Item"; 
             $datosNC[9]['dato'] =  $_SESSION['INGRESO']['item']; // NumEmpresa
             $datosNC[10]['campo'] =  "CodigoU"; 
             $datosNC[10]['dato'] =  $_SESSION['INGRESO']['CodigoU']; // CodigoUsuario
             $datosNC[11]['campo'] =  "PVP"; 
             $datosNC[11]['dato'] = $value[ "Precio"];
             $datosNC[12]['campo'] =  "COSTO";
             $datosNC[12]['dato'] =  $Ok_Inv['datos']['Costo'];
             $datosNC[13]['campo'] =  "Cod_Ejec"; 
             $datosNC[13]['dato'] = $value[ "Cod_Ejec"];
             $datosNC[14]['campo'] =  "Porc_C"; 
             $datosNC[14]['dato'] = $value[ "Porc_C"];
             $datosNC[15]['campo'] =  "Porc_IVA"; 
             $datosNC[15]['dato'] = $value[ "Porc_IVA"];
             $datosNC[16]['campo'] =  "Mes_No"; 
             $datosNC[16]['dato'] = $value[ "Mes_No"];
             $datosNC[17]['campo'] =  "Mes"; 
             $datosNC[17]['dato'] = $value[ "Mes"];
             $datosNC[18]['campo'] =  "Anio"; 
             $datosNC[18]['dato'] = $value[ "Ticket"];
             $datosNC[19]['campo'] =  "A_No";
             $datosNC[19]['dato'] = $Ln_No;           
             if($Ok_Inv['datos']['Con_Kardex'])
             {
               $datosNC[20]['campo'] =  "Ok";
               $datosNC[20]['dato'] = $Ok_Inv['datos']['Con_Kardex'];
               $datosNC[21]['campo'] =  "Cta_Inventario"; 
               $datosNC[21]['dato']  = $Ok_tInv['Cta_Inventario'];
               $datosNC[22]['campo'] =  "Cta_Costo"; 
               $datosNC[22]['dato']  = $Ok_tInv['Cta_Costo_Venta'];
             }
             insert_generico('Asiento_NC',$datosNC);
             $Ln_No = $Ln_No + 1;
             // DocConInv = DatInv.Con_Kardex
          }
      }

     // Listar_Articulos_Malla
     // If DocConInv Then DCBodega.SetFocus Else DGAsiento_NC.SetFocus
	}
}
?>