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
if(isset($_GET['lista_egreso_checking']))
{
	echo json_encode($controlador->lista_egreso_checking());
}
if(isset($_GET['eliminar_egreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_egreso($id));
}
if(isset($_GET['eliminar_egreso_all']))
{
	echo json_encode($controlador->eliminar_egreso_all());
}
if(isset($_GET['guardar_egreso']))
{
	echo json_encode($controlador->guardar_egreso());
}
if(isset($_GET['cargar_motivo_lista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_motivo_lista($parametros));
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
			$op[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);			
		}

		return $op;

	}

	function ddl_motivo($query)
	{
		$datos = $this->modelo->motivo_egreso($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);			
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
	    SetAdoFields('CodigoU',$data['CodigoU']);	
	    SetAdoFields('Valor_Unitario',$data['Valor_Unitario']);	

	    SetAdoFields('Codigo_Tra',$parametros['area']);	
	    SetAdoFields('Modelo',$parametros['motivo']);	
	    SetAdoFields('Detalle',$parametros['detalle']);	

	    SetAdoFields('Valor_Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	     SetAdoFields('Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFields('Item',$_SESSION['INGRESO']['item']);			
	    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);		
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
	function eliminar_egreso_all()
	{
		return $this->modelo->eliminar_all();
		// print_r($id);die();
	}

	function guardar_egreso()
	{
		// para el cheing de egreso se colocara la G
		$num = $this->modelo->numero_Registro(date('Y-m-d'));
		$registro = '001';
		if($num[0]['num']!=''){$registro = '00'.($num[0]['num']+1);}

		SetAdoAddNew('Trans_Kardex');
	    SetAdoFields('T','G');	    	    	    
	    SetAdoFields('Orden_No',str_replace('-','', date('Y-m-d')).'-'.$registro);	

	    SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);	
	    SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);	
	    SetAdoFieldsWhere('T','S');			
	   return  SetAdoUpdateGeneric();
	}

	function lista_egreso_checking()
	{
		$tr = '';
		$datos = $this->modelo->lista_egreso_checking();
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['usuario'].'
							<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-sm" onclick="modal_mensaje()">
								<img src="../../img/png/user.png" style="width:20px">
							</button>
							</span>
						</div>
					</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['Motivo'].'
							<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-sm" onclick="modal_motivo(\''.$value['Orden_No'].'\')">
								<img src="../../img/png/transporte_caja.png" style="width:20px">
							</button>
							</span>
						</div>
					</td>
					<td>'.$value['Detalle'].'</td>
					<td>
						<button type="button" class="btn btn-default btn-sm" onclick="$("#file_doc").click()">
							<img src="../../img/png/clip.png" style="width:20px">
						</button>
						<input type="file" id="file_doc" name="" style="display: none;">
					</td>
					<td>
						<select class="form-control input-sm">
							<option value="">Seleccione modulo</option>
						</select>
					</td>
					<td>
						<input type="checkbox" name="">
					</td>
				</tr>';
			// $tr.='<tr>			
			
			// <td>'.$value['Producto'].'</td>
			// <td>'.$value['Salida'].'</td>
			// <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			// </tr>';
		}
		return $tr;
	}


	function cargar_motivo_lista($parametros)
	{
		$tr='';
		$datos = $this->modelo->cargar_motivo_lista(false,false,$parametros['orden']);
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$tr.='<tr>
					<td>1</td>
					<td>'.$value['Cliente'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Stock_Bod'].'</td>
					<td>'.$value['Salida'].'</td>
					<td>'.$value['Valor_Unitario'].'</td>
					<td>'.($value['Valor_Unitario']*$value['Salida']).'</td>
					<td>
						<input type="radio" name="">
					</td>
				</tr>';
		}

		return $tr;
		// print_r($parametros);


	}
}


?>
