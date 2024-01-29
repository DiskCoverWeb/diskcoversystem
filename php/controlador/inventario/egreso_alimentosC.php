<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("../../modelo/inventario/egreso_alimentosM.php");
include(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new egreso_alimentosC();
 
if(isset($_GET['areas']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_areas($query));
} 
if(isset($_GET['motivos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_motivo($query));
}
if(isset($_GET['buscar_producto']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->buscar_producto($parametros));
}
if(isset($_GET['add_egresos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_egresos($parametros));
}
if(isset($_GET['listar_egresos']))
{
	echo json_encode($controlador->listar_egresos());
}
if(isset($_GET['eliminar_egreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_egreso($id));
}

/**
 * 
 */
class egreso_alimentosC
{
	private $modelo;
	private $pdf;

	function __construct()
	{
		$this->modelo = new egreso_alimentosM();
		$this->pdf = new cabecera_pdf();

	}

	function ddl_areas($query)
	{
		$datos = $this->modelo->areas($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['ID'],'text'=>$value['Proceso'],'data'=>$value);			
		}

		return $op;

	}

	function ddl_motivo($query)
	{
		$datos = $this->modelo->motivo_egreso($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['ID'],'text'=>$value['Proceso'],'data'=>$value);			
		}
		return $op;
	}

	function buscar_producto($parametros)
	{
		$datos = $this->modelo->buscar_producto($parametros['codigo']);
		return $datos;
	}

	function add_egresos($parametros)
	{
		$producto = $this->modelo->buscar_producto(false,$parametros['id']);
		$data = $producto[0];

		SetAdoAddNew('Trans_Kardex');
	    SetAdoFields('T','S');
	    SetAdoFields('Salida',$parametros['cantidad']);
	    SetAdoFields('CodBodega',$data['CodBodega']);
	    SetAdoFields('Codigo_Barra',$data['Codigo_Barra']);
	    SetAdoFields('Codigo_Inv',$data['Codigo_Inv']);	
	    SetAdoFields('Fecha',$parametros['fecha']);	
	    SetAdoFields('Codigo_P',$data['Codigo_P']);	
	    SetAdoFields('Orden_No',$data['Orden_No']);	
	    SetAdoFields('CodigoU',$data['CodigoU']);	
	    SetAdoFields('Valor_Unitario',$data['Valor_Unitario']);	
	    SetAdoFields('Valor_Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	     SetAdoFields('Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFields('Item',$_SESSION['INGRESO']['item']);		
	   return  SetAdoUpdate();		

	}	

	function listar_egresos()
	{
		$tr = '';
		$datos = $this->modelo->buscar_producto_egreso();
		foreach ($datos as $key => $value) {
			$tr.='<tr>			
			<td>'.($key+1).'</td>
			<td>'.$value['Fecha']->format('Y-m-d').'</td>
			<td>'.$value['Producto'].'</td>
			<td>'.$value['Salida'].'</td>
			<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			</tr>';
		}
		return $tr;
	}

	function eliminar_egreso($id)
	{
		return $this->modelo->eliminar($id);
		// print_r($id);die();
	}
}


?>
