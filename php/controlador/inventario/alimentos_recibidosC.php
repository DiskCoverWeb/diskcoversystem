<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/alimentos_recibidosM.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");


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
if(isset($_GET['eliminar_pedido']))
{
	$parametros = $_POST;
	echo json_decode($controlador->eliminar_pedido($parametros));
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
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar($query));

}
if(isset($_GET['pedidos_proce']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_procesado($query));

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

if(isset($_GET['producto_costo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->producto_costo($parametros));
}
if(isset($_GET['editar_precio']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_precio($parametros));
}
if(isset($_GET['editar_checked']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_checked($parametros));
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
		SetAdoFields('T','P');		
		SetAdoFields('Llamadas',$parametros['txt_comentario2']);
		if($parametros['cbx_evaluacion']=='V')
		{			
			SetAdoFields('CI',1);
		}else
		{
			SetAdoFields('CI',0);
		}
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
			$bene[] = array('id'=>$value['TP'],'text'=>$value['Proceso'],'picture'=>$value['Picture']);
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
		 $result[] = array("id"=>$value['Envio_No'],"text"=>$value['Envio_No'],'data'=>$value);
		}
		return $result;
	}

	function buscar_procesado($cod)
	{
		$datos = $this->modelo->buscar_transCorreos_procesados($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("id"=>$value['Envio_No'],"text"=>$value['Envio_No'],'data'=>$value);
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

		   SetAdoFields('Cta_Inv',$parametro['txt_cta_inv']);
		   SetAdoFields('Contra_Cta',$parametro['txt_contra_cta']);
		   SetAdoFields('Codigo_P',$parametro['txt_codigo_p']);

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
  					<td width="'.$d4.'">'.number_format($value['Entrada'],2,'.','').'</td>
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


      		$canti = $canti+$value['Entrada'];	
      		$PVP = $PVP+$value['Valor_Unitario'];	
      		$total = $total+$value['Valor_Total'];	

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
  					<td width="'.$d4.'" id="txt_cant_ped_'.$value['ID'].'">'.number_format($value['Entrada'],2,'.','').'</td>
  					<td width="'.$d4.'"><input class="form-control" id="txt_pvp_linea_'.$value['ID'].'" name="txt_pvp_linea_'.$value['ID'].'" onblur="recalcular('.$value['ID'].')" input-sm" value="'.$value['Valor_Unitario'].'"></td>
  					<td width="'.$d4.'"><input class="form-control" id="txt_total_linea_'.$value['ID'].'" name="txt_total_linea_'.$value['ID'].'"  input-sm" value="'.$value['Valor_Total'].'" readonly></td>
  					<td width="90px">';
  					if($value['T']=='C')
  					{
  					  $tr.='<input type="checkbox" class="rbl_conta" name="rbl_conta" id="rbl_conta_'.$value['ID'].'" value="'.$value['ID'].'" checked  />';
  					}else
  					{
  						$tr.='<input type="checkbox" class="rbl_conta" name="rbl_conta" id="rbl_conta_'.$value['ID'].'" value="'.$value['ID'].'" />';
  					}
  					$tr.='</td>
  					<td>
  						<button class="btn btn-sm btn-primary" onclick="editar_precio('.$value['ID'].')"><i class="fa fa-save"></i></button>
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
			$productos[] = array('id'=>$Familia[0]['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$Familia);

		}
		return $productos;
		// print_r($productos);die();
	}

	function producto_costo($parametros)
	{
		$query = $parametros['cta_inv'];
		$productos  = Leer_Codigo_Inv($query,date('Y-m-d'));
		return $productos['datos'];
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
					<td>'.$value['Envio_No'].'</td>
					<td>'.$value['Fecha_P']->format('Y-m-d').'</td>
					<td>'.$value['Cliente'].'</td>
					<td>'.$value['Proceso'].'</td>
					<td>'.number_format($value['TOTAL'],2,'.','').'</td>
					<td>'.$value['Porc_C'].'</td>
					<td><button type="button" class="btn-sm btn-danger btn" onclick="eliminar_pedido(\''.$value['ID'].'\')"><i class="fa fa-trash"></i></button></td>

				  </tr>';
		}

		return $tr;
		print_r($datos);die();
	}

	function eliminar_pedido($data)
	{
		$id = $data['ID'];
		return $this->modelo->eliminar_pedido($id);
	} 
	function editar_precio($parametros)
	{
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Kardex');
		SetAdoFields('Valor_Unitario',$parametros['pvp']);		
		SetAdoFields('Valor_Total',$parametros['total']);
		SetAdoFieldsWhere('ID',$parametros['id']);
		return SetAdoUpdateGeneric();

	}

	function editar_checked($parametros)
	{
		// print_r($parametros);die();
		$op =  substr($parametros['check'],0,-1);
		$op = explode(',', $op);

		$no_op =  substr($parametros['no_check'],0,-1);
		$no_op = explode(',', $no_op);



		foreach ($op as $key => $value) {
			
			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('T','C');		
			SetAdoFieldsWhere('ID',$value);
			SetAdoUpdateGeneric();

		}
		foreach ($no_op as $key => $value) {
			
			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('T','.');		
			SetAdoFieldsWhere('ID',$value);
			SetAdoUpdateGeneric();

		}

		return 1;

		// SetAdoAddNew('Trans_Kardex');
		// SetAdoFields('T','C');		
		// SetAdoFieldsWhere('ID',$parametros['id']);
		// return SetAdoUpdateGeneric();

	}


}

?>