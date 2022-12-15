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
}
?>