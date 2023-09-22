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
if(isset($_GET['detalle_ingreso2']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->detalle_ingreso2($query));
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
if(isset($_GET['pedido_checking']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos_checking($parametros));
}
if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}
if(isset($_GET['autocom_pro']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocomplet_producto($query));
}

if(isset($_GET['cargar_datos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos($parametros));
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
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','N');
		SetAdoFields('Mensaje',$parametros['txt_comentario']);
		SetAdoFields('Fecha_P',$parametros['txt_fecha']);
		SetAdoFields('CodigoP',$parametros['ddl_ingreso']);
		SetAdoFields('Cod_C',$parametros['ddl_alimento']);
		SetAdoFields('Porc_C',$parametros['txt_temperatura']);
		SetAdoFields('Cod_R',$parametros['cbx_estado_tran']);
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
	function detalle_ingreso2($query)
	{
		$datos = $this->modelo->detalle_ingreso(false,$query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$datos);
		}
		return $bene;
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
		 $result[] = array("value"=>$value['ID'],"label"=>$value['Envio_No'],'Fecha'=>$value['Fecha_P']->format('Y-m-d'),'mensaje'=>$value['Mensaje'],'Codigo_P'=>$value['CodigoP'],'Total'=>$value['TOTAL'],'Cod_C'=>$value['Cod_C'],'CI_RUC'=>$value['CI_RUC'],'Cod_Ejec'=>$value['Cod_Ejec'],'Cliente'=>$value['Cliente'],'Proceso'=>$value['Proceso'],'Porc_C'=>$value['Porc_C'],'Cod_R'=>$value['Cod_R']);
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
		$num_ped = $parametro['txt_codigo'];
		// print_r($producto);
		// print_r($parametro);
		// die();
		
		   SetAdoAddNew("Trans_Kardex"); 		
		   SetAdoFields('Codigo_Inv',$parametro['txt_referencia']);
		   SetAdoFields('Producto',$producto[0]['Producto']);
		   SetAdoFields('UNIDAD',$producto[0]['Unidad']); /**/
		   SetAdoFields('Entrada',$parametro['txt_cantidad']);
		   SetAdoFields('Cta_Inv',$producto[0]['Cta_Inventario']);
		   SetAdoFields('Fecha_Fab',$parametro['txt_fecha_cla']);	
		   SetAdoFields('Fecha_Exp',$parametro['txt_fecha_exp']);	   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Orden_No',$num_ped); 
		   SetAdoFields('A_No',$parametro['A_No']+1);
		   SetAdoFields('Fecha',date('Y-m-d',strtotime($parametro['txt_fecha'])));
		   SetAdoFields('Valor_Total',number_format($producto[0]['PVP']*$parametro['txt_cantidad'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['txt_cantidad']);
		   SetAdoFields('Valor_Unitario',number_format($producto[0]['PVP'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   // SetAdoFields('DH',2);
		   SetAdoFields('Codigo_Barra',$parametro['txt_codigo'].'-'.$producto[0]['Item_Banco']);
		   SetAdoFields('CodBodega',-1);


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
		   return  $respuesta = array('ped'=>$num_ped,'resp'=>$resp,'total_add'=>'1');		
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
      $canti = 0;
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();

      		$canti = $canti+$value['Entrada'];
			$iva+=number_format($value['Total_IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['Valor_Unitario']*$value['Entrada'];
			$subtotal+=$sub;
			$procedimiento=$value['Detalle'];

			$total+=$value['Valor_Total'];

			$FechaInventario = $value['Fecha']->format('Y-m-d');
 	 		$CodBodega = '01';
			$costo_existencias =  Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');
		
			if($costo_existencias['respueta']!=1){$costo_existencias['datos']['Stock'] = 0; $costo_existencias['datos']['Costo'] = 0;}
			else{
				$exis = number_format($costo_existencias['datos']['Stock']-$value['Entrada'],2);
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
			$d1 =  dimenciones_tabl(strlen($value['Fecha_Exp']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Entrada']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.($key+1).'</td>
  					<td width="'.$d1.'">'.$value['Fecha_Exp']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'">'.$value['Entrada'].'</td>
  					<td width="90px">
  					<!--	<button class="btn btn-sm btn-primary" onclick="editar_lin(\''.$value['ID'].'\')" title="Editar linea"><span class="glyphicon glyphicon-floppy-disk"></span></button> -->
  						<button class="btn btn-sm btn-danger" title="Eliminar linea"  onclick="eliminar_lin(\''.$value['ID'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr>
  				<td colspan="4"><b>TOTALES</b></td>	
  				<td>'.$canti.'</td>	
  				<td></td>		
  			</tr>';

		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'item'=>$num,'cant_total'=>$canti);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="9" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'cant_total'=>0);
			return $tabla;		
		}		
    }


    function cargar_productos_checking($parametros)
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
      $canti = 0;
      $PVP = 0;      
      $total = 0;
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();


      		$canti = $canti+$value['Salida'];	
      		$PVP = $PVP+$value['Valor_Unitario'];	
      		$total = $total+$value['Valor_Total'];	

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha_Exp']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Salida']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.($key+1).'</td>
  					<td width="'.$d1.'">'.$value['Fecha_Exp']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'">'.$value['Salida'].'</td>
  					<td width="'.$d4.'">'.$value['Valor_Unitario'].'</td>
  					<td width="'.$d4.'">'.$value['Valor_Total'].'</td>
  					<td width="90px">
  					<input type="checkbox" class="rbl_conta" name="rbl_conta" id="rbl_conta_'.$value['ID'].'" value="'.$value['ID'].'" />
  					</td>
  				</tr>';
			
		}
		$tr.='<tr>
  				<td colspan="4"><b>TOTALES</b></td>	
  				<td>'.$canti.'</td>	
  				<td>'.$PVP.'</td>	
  				<td>'.$total.'</td>	
  				<td></td>		
  			</tr>';

		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'item'=>$num,'cant_total'=>$canti);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="9" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'cant_total'=>0);
			return $tabla;		
		}		
    }


   function lineas_eli($parametros)
	{
		$resp = $this->modelo->lineas_eli($parametros);
		return $resp;

	}
	function autocomplet_producto($query)
	{
		$datos = $this->modelo->cargar_productos($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			$Familia = $this->modelo->familia_pro(substr($value['Codigo_Inv'],0,5));
			// $costo =  $this->ing_descargos->costo_venta($value['Codigo_Inv']);
			// $costoTrans = $this->ing_descargos->costo_producto($value['Codigo_Inv']);

			// $FechaInventario = date('Y-m-d');
		 	// $CodBodega = '01';
		 	// $costo_existencias = Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');

			// if(empty($Familia))
			// {
			// 	$Familia[0]['Producto'] = '-';
			// 	$Familia[0]['Codigo_Inv'] = '.';
			// }
			// if($costo_existencias['respueta']!=1)
			// {
			// 	$costo[0]['Existencia'] = 0;
			// 	$costoTrans[0]['Costo'] = 0;				
			// }else
			// {
			// 	$costo[0]['Existencia'] = $costo_existencias['datos']['Stock'];
			// 	$costoTrans[0]['Costo'] = $costo_existencias['datos']['Costo'];		
			// }			

			$productos[] = array('id'=>$Familia[0]['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$Familia);

		}
		return $productos;
		// print_r($productos);die();
	}

	function cargar_datos($parametros)
	{
		$query = $parametros['query'];
		$fecha = $parametros['fecha'];

		$datos = $this->modelo->buscar_transCorreos($query,$fecha);
		$tr= '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.$value['Fecha_P']->format('Y-m-d').'</td>
					<td>'.$value['Cliente'].'</td>
					<td>'.$value['Proceso'].'</td>
					<td>'.$value['TOTAL'].'</td>
				<!--	<td>'.$value['Envio_No'].'</td> -->
					<td>'.$value['Porc_C'].'</td>

				  </tr>';
		}

		return $tr;
		print_r($datos);die();
	}


	  


}

?>