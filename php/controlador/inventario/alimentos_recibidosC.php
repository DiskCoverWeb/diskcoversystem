<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/alimentos_recibidosM.php");


$controlador = new alimentos_recibidosC();
if(isset($_GET['proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->proveedores($query));
}
if(isset($_GET['guardar']))
{
	$parametros = $_POST;
	echo json_decode($controlador->guardar($parametros));
}
if(isset($_GET['guardar2']))
{
	$parametros = $_POST;
	echo json_decode($controlador->guardar2($parametros));
}
if(isset($_GET['alimentos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cta_procesos($query));
}
if(isset($_GET['detalle_ingreso']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->detalle_ingreso($query));
}
if(isset($_GET['datos_ingreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->datos_ingreso($id));
}

if(isset($_GET['autoincrementable']))
{
	$parametros = $_POST['parametros'];
	$num = ReadSetDataNum('Ingresos_Recibidos',false,false,$parametros['fecha']);
	$num = generaCeros($num,4);
	echo json_encode($num);
}
if(isset($_GET['search']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->buscar($query));

}
if(isset($_GET['guardar_recibido']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_recibido($parametros));
}
if(isset($_GET['pedido']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos($parametros));
}
if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}
/**
 * 
 */
class alimentos_recibidosC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new alimentos_recibidosM();
	}

	function guardar($parametros)
	{
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','N');
		SetAdoFields('Mensaje',$parametros['txt_comentario']);
		SetAdoFields('Fecha_P',$parametros['txt_fecha']);
		SetAdoFields('CodigoP',$parametros['ddl_ingreso']);
		SetAdoFields('Cod_C',$parametros['ddl_alimento']);
		SetAdoFields('TOTAL',$parametros['txt_cant']);
		SetAdoFields('Envio_No',substr($parametros['txt_codigo'],0,-4).generaCeros(ReadSetDataNum('Ingresos_Recibidos',false,true,$parametros['txt_fecha']),4));
		return SetAdoUpdate();

	}

	function guardar2($parametros)
	{
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','N');		
		SetAdoFields('Llamadas',$parametros['txt_comentario2']);
		if(isset($parametros['rbl_recibido']))
		{
			SetAdoFields('C',1);
		}else
		{
			SetAdoFields('C',0);
		}
		SetAdoFields('SucIng',$parametros['cbx_evaluacion']);
		SetAdoFieldsWhere('ID',$parametros['txt_id']);
		return SetAdoUpdateGeneric();

	}
	function cta_procesos($query)
	{
		$datos = $this->modelo->cta_procesos($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['TP'],'text'=>$value['Proceso']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;

	}

	function detalle_ingreso($query)
	{
		$datos = $this->modelo->detalle_ingreso();
		// $bene = array();
		// foreach ($datos as $key => $value) {
		// 	$bene[] = array('id'=>$value['ID'],'text'=>$value['Cliente']);
		// 	// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		// }
		return $datos;
	}
	function datos_ingreso($cod)
	{
		// print_r($id);die();
		$datos = $this->modelo->detalle_ingreso($cod);
		return $datos[0];
	}
	function buscar($cod)
	{
		$datos = $this->modelo->buscar_transCorreos($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("value"=>$value['ID'],"label"=>$value['Envio_No'],'Fecha'=>$value['Fecha_P']->format('Y-m-d'),'mensaje'=>$value['Mensaje'],'Codigo_P'=>$value['CodigoP'],'Total'=>$value['TOTAL'],'Cod_C'=>$value['Cod_C'],'CI_RUC'=>$value['CI_RUC'],'Cod_Ejec'=>$value['Cod_Ejec'],'Cliente'=>$value['Cliente'],'Proceso'=>$value['Proceso']);
		}
		return $result;
	}

	function proveedores($query)
	{
		$datos = $this->modelo->buscar_transCorreos($query);
		if($datos!=-1)
		{
		     $prov = array();
		     foreach ($datos as $key => $value) {
			  // print_r($value);die();
			  $prov[] = array('id'=>$value['ID'],'text'=>$value['Envio_No']);
		     }
		     return $prov;
		 }else
		 {
		 	return -1;
		 }
	}
	function guardar_recibido($parametro)
	{
		// $producto = explode('_',$parametro['ddl_producto']);
		$producto = $this->modelo->catalogo_productos($parametro['txt_referencia']);
		$num_ped = 99999;
		// print_r($producto);
		// print_r($parametro);
		// die();
		
		   SetAdoAddNew("Trans_Kardex"); 		
		   SetAdoFields('Codigo_Inv',$parametro['txt_referencia']);
		   SetAdoFields('Producto',$producto[0]['Producto']);
		   SetAdoFields('UNIDAD',$producto[0]['Unidad']); /**/
		   SetAdoFields('Salida',$parametro['txt_canti']);
		   SetAdoFields('Cta_Inv',$producto[0]['Cta_Inventario']);
		   // SetAdoFields('CodigoL',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Orden_No',$num_ped); 
		   SetAdoFields('A_No',$parametro['A_No']+1);
		   SetAdoFields('Fecha',date('Y-m-d',strtotime($parametro['txt_fecha'])));
		   // SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('Valor_Total',number_format($parametro['txt_total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['txt_canti']);
		   SetAdoFields('Valor_Unitario',number_format($parametro['txt_precio'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   SetAdoFields('Codigo_Barra',$parametro['txt_codigo'].'-'.$producto[0]['Item_Banco']);
		   // SetAdoFields('Contra_Cta',$parametro['cc']);
		   // SetAdoFields('Descuento',$parametro['descuento']);
		   // SetAdoFields('Codigo_P',$parametro['CodigoP']);
		   // SetAdoFields('Detalle',$parametro['pro']);
		   // SetAdoFields('Centro_Costo',$parametro['area']);
		   // SetAdoFields('Codigo_Dr',$parametro['solicitante']);
		   // SetAdoFields('Costo',number_format($parametro['valor'],2,'.',''));

		   // if($parametro['iva']!=0)
		   // {
		   	   // $datos[19]['campo']='IVA';
		       // $datos[19]['dato']=(round($parametro['total'],2)*1.12)-round($parametro['total'],2);
		   // }
		   // print_r($datos);die();
		   $resp = SetAdoUpdate();
		   // $num = $num_ped;
		   return  $respuesta = array('ped'=>$num_ped,'resp'=>$resp);		
	}
	function cargar_productos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false);

		// print_r($datos);die();
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '';
        $pie = ' 
        </tbody>
      </table>';
      $d='';
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();
			$iva+=number_format($value['Total_IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['Valor_Unitario']*$value['Salida'];
			$subtotal+=$sub;
			$procedimiento=$value['Detalle'];

			$total+=$value['Valor_Total'];

			$FechaInventario = $value['Fecha']->format('Y-m-d');
 	 		$CodBodega = '01';
			$costo_existencias =  Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');
		
			if($costo_existencias['respueta']!=1){$costo_existencias['datos']['Stock'] = 0; $costo_existencias['datos']['Costo'] = 0;}
			else{
				$exis = number_format($costo_existencias['datos']['Stock']-$value['Salida'],2);
				if($exis<0)
				{
					$nega = $exis;
					$negativos = true;
				}
			}
			$nega = 0;			
			

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Codigo_Inv']));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Salida']));
			$d5 =  dimenciones_tabl(strlen($value['Valor_Unitario']));
			$d6 =  dimenciones_tabl(strlen($value['Total_IVA']));
			$d7 =  dimenciones_tabl(strlen($value['Valor_Total']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.$key.'</td>
  					<td width="'.$d1.'">'.$value['Fecha']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Codigo_Inv'].'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'" class="text-right">
  					     <input type="text" class=" text-right form-control input-sm" id="txt_can_lin_'.$value['ID'].'" value="'.$value['Salida'].'" onblur="calcular_totales(\''.$value['ID'].'\');" readonly />
  					</td>
  					<td width="'.$d5.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['ID'].'\');" class="text-right form-control input-sm" id="txt_pre_lin_'.$value['ID'].'" value="'.number_format($value['Valor_Unitario'],2).'" readonly=""/>
  					</td>
  					<td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['ID'].'\');" class="text-right form-control input-sm" id="txt_iva_lin_'.$value['ID'].'" value="'.number_format($value['Total_IVA'],4).'" readonly=""/>
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="text-right form-control input-sm" id="txt_tot_lin_'.$value['ID'].'" value="'.number_format($value['Valor_Total'],4).'" readonly="" />
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="form-control input-sm" id="txt_negarivo_'.$value['ID'].'" value="'.$nega.'" readonly="" />
  					</td>
  					<td width="'.$d7.'">
  					    '.$value['Codigo_Barra'].'
  					</td>
  					<td width="90px">
  					<!--	<button class="btn btn-sm btn-primary" onclick="editar_lin(\''.$value['ID'].'\')" title="Editar linea"><span class="glyphicon glyphicon-floppy-disk"></span></button> -->
  						<button class="btn btn-sm btn-danger" title="Eliminar linea"  onclick="eliminar_lin(\''.$value['ID'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		// $tr.='<tr><td colspan="2"><button type="button" class="btn btn-primary" onclick="generar_factura(\''.$FechaInventario.'\')" id="btn_comprobante"><i class="fa fa-file-text-o"></i> Generar comprobante</button></td><td colspan="4"></td><td class="text-right">Total:</td><td><input type="text" class="form-control input-sm" value="'.$subtotal.'"></td><td colspan="2"></td></tr>';
		// print_r($datos);die();
		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'item'=>$num,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'detalle'=>$procedimiento);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="9" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'detalle'=>$procedimiento);
			return $tabla;		
		}
		
    }
   function lineas_eli($parametros)
	{
		$resp = $this->modelo->lineas_eli($parametros);
		return $resp;

	}


	  


}

?>