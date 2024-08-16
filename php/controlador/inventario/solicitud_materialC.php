<?php
include(dirname(__DIR__,2).'/modelo/inventario/solicitud_materialM.php');

$controlador = new solicitud_materialC();

if(isset($_GET['productos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocomplet_producto($query));
}

if(isset($_GET['guardar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_linea($parametros));
}
if(isset($_GET['linea_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->linea_pedido($parametros));
}

if(isset($_GET['eliminar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea($parametros));
}

if(isset($_GET['grabar_solicitud']))
{
	echo json_encode($controlador->grabar_solicitud());
}

if(isset($_GET['pedidos_solicitados']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->pedidos_solicitados($query));
}

if(isset($_GET['lineas_pedido_solicitados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_pedido_solicitados($parametros));
}

if(isset($_GET['grabar_solicitud_proveedor']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->grabar_solicitud_proveedor($parametros));
}

if(isset($_GET['pedido_solicitados_proveedor']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->pedido_solicitados_proveedor($query));
}


if(isset($_GET['lineas_pedido_solicitados_proveedor']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_pedido_solicitados_proveedor($parametros));
}
if(isset($_GET['lista_proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->lista_proveedores($query));
}

if(isset($_GET['grabar_envio_solicitud']))
{
	$parametros = $_POST;
	echo json_encode($controlador->grabar_envio_solicitud($parametros));
}



class solicitud_materialC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new solicitud_materialM();
    }

    function autocomplet_producto($query)
	{
		$datos = $this->modelo->cargar_productos($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			// $costo =  $this->ing_descargos->costo_venta($value['Codigo_Inv']);
			// $costoTrans = $this->ing_descargos->costo_producto($value['Codigo_Inv']);

			$FechaInventario = date('Y-m-d');
		 	$CodBodega = '01';
		 	$costo_existencias = Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');

			if($costo_existencias['respueta']!=1)
			{
				$costo[0]['Existencia'] = 0;
				$costoTrans[0]['Costo'] = 0;				
			}else
			{
				$costo[0]['Existencia'] = $costo_existencias['datos']['Stock'];
				$costoTrans[0]['Costo'] = $costo_existencias['datos']['Costo'];		
			}
			

			$productos[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$value);
		}
		return $productos;
		// print_r($productos);die();
	}

	function guardar_linea($parametros)
	{
		// print_r($parametros);die();
		$producto = Leer_Codigo_Inv($parametros['productos'],$parametros['fecha']);
		if($producto['respueta']==1)
		{
			$articulo = $producto['datos'];
			// print_r($articulo);die();
				SetAdoAddNew("Trans_Pedidos");
		        SetAdoFields("Codigo_Inv",$parametros['productos']);
		        SetAdoFields("Fecha",$parametros['fecha']);
		        SetAdoFields("Producto",$articulo['Producto']);
		        SetAdoFields("Cantidad",$parametros['cantidad']);
		        SetAdoFields("Precio",$articulo['PVP']);
		        SetAdoFields("TC",'P');
		        SetAdoFields("Total", number_format($articulo['PVP'] * $parametros['cantidad'],4,'.',''));
		        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
		        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
		        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
				
				return SetAdoUpdate();
		}
	}


	function linea_pedido($parametros)
	{
		$datos = $this->modelo->lineas_pedido($parametros['fecha']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>
						<button class="btn btn-sm btn-danger" onclick="eliminar_linea(\''.$value['ID'].'\')"><i class="fa fa fa-trash"></i></button>
					</td>
				</tr>';
		}
		return $tr;
	}

	function eliminar_linea($parametros)
	{

		return $this->modelo->eliminar_linea($parametros['id']);
		print_r($parametros);die();
	}   

	function grabar_solicitud()
	{

		// print_r($_SESSION['INGRESO']);die();
		// $cod_orde = $this->modelo->
		$codigo = ReadSetDataNum("PC_SERIE_001001", false, True);

		$codigo = "PC_SERIE_001001_".$codigo;
		// print_r($codigo);die();
		$datos = $this->modelo->lineas_pedido();

		foreach ($datos as $key => $value) {

			SetAdoAddNew("Trans_Pedidos");          
        	SetAdoFields("Orden_No",$codigo);     
        	SetAdoFields("TC",'S');

        	SetAdoFieldsWhere('ID',$value['ID']);
        	SetAdoUpdateGeneric();
		}
		return 1;		
	}

	//---------------------------------------------------------aprobacion de solicitud-----------------------------------------------------
	function pedidos_solicitados($query)
	{
		$datos = $this->modelo->pedido_solicitados($query);
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['Orden_No'],'text'=>$value['Nombre_Completo'].' -- '.$value['Orden_No'],'data'=>$value);
		}

		return $lista;
		// print_r($datos);die();
	}


	function  lineas_pedido_solicitados($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_pedido_solicitados($parametros['orden']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td><input type="checkbox"></td>
					<td>
						<button class="btn btn-sm btn-danger" onclick="eliminar_linea(\''.$value['ID'].'\')"><i class="fa fa fa-trash"></i></button>
					</td>
				</tr>';
		}
		return $tr;
	}


	function grabar_solicitud_proveedor($parametros)
	{
		// print_r($parametros);die();
		// print_r($codigo);die();
		$datos = $this->modelo->lineas_pedido_solicitados($parametros['pedido']);

		foreach ($datos as $key => $value) {

			SetAdoAddNew("Trans_Pedidos");         
        	SetAdoFields("TC",'E');

        	SetAdoFieldsWhere('ID',$value['ID']);
        	SetAdoUpdateGeneric();
		}

	}

	//---------------------------------------------------------envio solicitud proveedor-----------------------------------------------------

	function pedido_solicitados_proveedor($query)
	{
		$datos = $this->modelo->pedido_solicitados_proveedor($query);
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['Orden_No'],'text'=>$value['Nombre_Completo'].' -- '.$value['Orden_No'],'data'=>$value);
		}

		return $lista;
		// print_r($datos);die();
	}

	function lista_proveedores($query)
	{
		$datos = $this->modelo->proveedores($query);

		// print_r($datos);die();
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
		}

		return $lista;
		// print_r($datos);die();
	}



	function  lineas_pedido_solicitados_proveedor($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_pedido_solicitados_proveedor($parametros['orden']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td width="28%">
					<select class="form-control select2_prove" id="ddl_selector_'.$value['ID'].'" onclick="llenarProveedores(\'ddl_selector_'.$value['ID'].'\')" name="ddl_selector_'.$value['ID'].'[]" multiple="multiple" row="2">
							<option disabled>Seleccione proveedor</option>
						</select>

					</td>
					<td>
						<button class="btn btn-sm btn-primary" onclick="eliminar_linea(\''.$value['ID'].'\')"><i class="fa fa fa-save"></i></button>
					</td>
				</tr>';
		}
		return $tr;
	}

	function grabar_envio_solicitud($parametros)
	{
		foreach ($parametros as $key => $value) {
			$id = str_replace('ddl_selector_', "", $key);
			$linea = $this->modelo->Trans_Pedidos($id,false,false);
			$linea = $linea[0];
			foreach ($value as $key2 => $value2) {

				SetAdoAddNew("Trans_Ticket");
		        SetAdoFields("Codigo_Inv",$linea['Codigo_Inv']);
		        SetAdoFields("Fecha",$linea['Fecha']);
		        SetAdoFields("Producto",$linea['Producto']);
		        SetAdoFields("Cantidad",$linea['Cantidad']);
		        SetAdoFields("Precio",$linea['Precio']);
		        SetAdoFields("CodigoC",$value2);
		        SetAdoFields("Total", $linea['Total']);
		        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
		        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
		        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
		        SetAdoFields("Orden_No",$linea['Orden_No']);				
				SetAdoUpdate();


				SetAdoAddNew("Trans_Pedidos");         
	        	SetAdoFields("TC",'T');

	        	SetAdoFieldsWhere('ID',$id);
	        	SetAdoUpdateGeneric();

			}

			// print_r($linea);die();

		}
		
		return 1;



		// print_r($parametros);die();
	}
	




}
?>