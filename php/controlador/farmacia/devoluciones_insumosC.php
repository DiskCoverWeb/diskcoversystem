<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/devoluciones_insumosM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new devoluciones_insumosC();
if(isset($_GET['cargar_pedidos']))
{
	$parametros = $_POST['parametros'];
	$paginacion = $_POST['paginacion'];
	echo json_encode($controlador->cargar_pedidos($parametros,$paginacion));
}
if(isset($_GET['tabla_detalles']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_detalles($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['formatoEgreso']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}
if(isset($_GET['Ver_comprobante']))
{   
	$parametros= $_GET['comprobante'];
	$controlador->ver_comprobante($parametros);	
}

if(isset($_GET['datos_comprobante']))
{   
	$comprobante= $_POST['comprobante'];
	$query= $_POST['query'];
	echo json_encode($controlador->datos_comprobante($comprobante,$query));	
}
if(isset($_GET['costo']))
{   
	$parametros= $_POST['codigo'];
	echo json_encode($controlador->costo($parametros));	
}

if(isset($_GET['guardar_devolucion']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_devoluciones($parametros));	
}
if(isset($_GET['lista_devolucion']))
{   
	$comprobante= $_POST['comprobante'];
	echo json_encode($controlador->lista_devoluciones($comprobante));	
}
if(isset($_GET['lista_devolucion_dep']))
{   
	$comprobante= $_POST['comprobante'];
	echo json_encode($controlador->lista_devoluciones_x_departamento($comprobante));	
}
if(isset($_GET['eliminar_linea_dev']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea_devo($parametros));	
}
if(isset($_GET['eliminar_linea_dev_dep']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea_devo_dep($parametros));	
}

if(isset($_GET['guardar_devolucion_departamentos']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_devolucion_departamentos($parametros));	
}



class devoluciones_insumosC 
{
	private $modelo;
	private $paciente;
	private $descargos;
	function __construct()
	{
		$this->modelo = new devoluciones_insumosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
		$this->descargos = new ingreso_descargosM();
	}

	

	function cargar_pedidos($parametros,$paginacion)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe'],$paginacion);
		return $tabla = array('num_lin'=>0,'tabla'=>$datos);

	}

	

	function datos_comprobante($comprobante,$query1)
	{
		$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->modelo->lineas_trans_kardex($comprobante,$query1);
		$tr='';
		$tot=0;
		$num_lineas = count($lineas);
		foreach ($lineas as $key => $value) {
			$readonly = '';
			$key+=1;
			$registrado = $this->modelo->lista_devoluciones($comprobante);
			 $devo = $this->modelo->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
				 if(count($devo)>0)
				 {
				 	$ca= $value['Salida']-$devo[0]['Entrada'];
				 	if($ca>=0 )
				 	{
				 		$value['Salida']  = $ca;
				 	}
				 }

			foreach ($registrado['datos'] as $key2 => $value2) {				
				if($value['ID']==$value2['A_No'])
				{
					$readonly = 'readonly=""';
					break;
				}
			}
			$tr.='<tr>
			  			<td width="'.dimenciones_tabl(strlen('Codigo_Inv')).'" id="codigo_'.$key.'">'.$value['Codigo_Inv'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Producto'])).'" id="producto_'.$key.'">'.$value['Producto'].'</td>
			  			<td width="'.dimenciones_tabl(strlen('cant'.$value['Salida'])).'"><input class="form-control input-sm" value="'.$value['Salida'].'" readonly="" name="txt_salida_'.$key.'" id="txt_salida_'.$key.'"></td>
			  			<td width="'.dimenciones_tabl(strlen('Valor_Unitario')).'" class="text-right">'.number_format($value['Valor_Unitario'],2).'</td>
			  			<td width="'. dimenciones_tabl(strlen('Precio Total')).'" class="text-right">'.number_format($value['Valor_Total'],2).'</td>
        			<td  width="'.dimenciones_tabl(strlen('cant_dev')).'"><input class="form-control input-sm text-right" id="txt_cant_dev_'.$key.'"  value="0" onblur="calcular_dev(\''.$key.'\')" '.$readonly.'></td>
        			<td width="'.dimenciones_tabl(strlen('Valor utilidad')).'"><input class="form-control input-sm text-right" id="txt_valor_'.$key.'" value="0"   readonly></td>
        			<td width="'.dimenciones_tabl(strlen('total_dev')).'"><input class="form-control input-sm text-right" id="txt_gran_t_'.$key.'" value="0"  readonly=""></td>
        			<td><button onclick="calcular_dev(\''.$key.'\');guardar_devolucion(\''.$value['ID'].'\',\''.$key.'\')" id="btn_linea_'.$key.'" class="btn btn-primary"><i class="fa-icon fa fa-save"></i></button></td>
        		</tr>';
        	// $tot+=$gran;
		}
		$tr.='<tr><td colspan="6"></td><td class="text-right"><b>TOTAL</b></td><td class="text-right" id="txt_tt">'.$tot.'</td></tr>';

		return array('cliente'=>$datos,'tabla'=>$tr,'lineas'=>$num_lineas,'total'=>number_format($tot,2));

	}

	function guardar_utilidad($parametros)
	{
		$tabla = 'Trans_Kardex';
		$datos[0]['campo']='Utilidad';
		$datos[0]['dato']=$parametros['utilidad']/100;

		$campoWhere[0]['campo']='ID';
		$campoWhere[0]['valor']=$parametros['linea'];
		return update_generico($datos,$tabla,$campoWhere);

	}
	function costo($codigo)
	{
		$datos = $this->descargos->costo_producto($codigo);
		return $datos;
	}
    
    function guardar_devolucion_departamentos($parametro)
	{
		// print_r($parametro);die();

		   $linea = $this->modelo->producto_all_detalle($parametro['codigo']);
		   // print_r($linea);die();
		   $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=$parametro['codigo'];
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$parametro['producto'];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']='';
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['cantidad'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$linea[0]['Cta_Inventario'];
		   $datos[5]['campo']='SUBCTA';
		   $datos[5]['dato']=$parametro['area'];		   //proveedor cod //area de donde biene
		   $datos[6]['campo']='CodigoU';
		   $datos[6]['dato']=$_SESSION['INGRESO']['Id'];   
		   $datos[7]['campo']='Item';
		   $datos[7]['dato']=$_SESSION['INGRESO']['item'];
		   $datos[8]['campo']='A_No';
		   $datos[8]['dato']=$parametro['linea']+1;

		   $datos[9]['campo']='Fecha_DUI';
		   $datos[9]['dato']=date('Y-m-d');

		   $datos[10]['campo']='TC';
		   $datos[10]['dato']='P';
		   $datos[11]['campo']='VALOR_TOTAL';
		   $datos[11]['dato']=number_format($parametro['total'],'2');
		   $datos[12]['campo']='CANTIDAD';
		   $datos[12]['dato']=$parametro['cantidad'];
		   $datos[13]['campo']='VALOR_UNIT';
		   $datos[13]['dato']= number_format($parametro['precio'],2);
		   //round($parametro['txt_precio'],2,PHP_ROUND_HALF_DOWN);
		   $datos[14]['campo']='DH';
		   $datos[14]['dato']=1;
		   $datos[15]['campo']='CONTRA_CTA';
		   if($parametro['cc']!='')
		   {
		   	 // $cc = explode('-',$parametro['cc']);
		   	 $datos[15]['dato']=$parametro['cc'];
		   }else
		   {
		   	 $cta = buscar_en_ctas_proceso('Cta_Devoluciones');
		   	 if($cta!=-1)
		   	 {
		   	 	$datos[15]['dato']=$cta; 
		   	 }else
		   	 {
		   	 	$cta[0]['campo'] = 'Periodo'; 
		   	 	$cta[0]['dato'] = $_SESSION['INGRESO']['periodo'];
		   	 	$cta[1]['campo'] = 'Item';
		   	 	$cta[1]['dato'] = $_SESSION['INGRESO']['item'];
		   	 	$cta[2]['campo'] =	'DC';	   	 	
		   	 	$cta[2]['dato'] =  'D';
		   	 	$cta[3]['campo'] =	'Lst';	   	 	
		   	 	$cta[3]['dato'] =  0;
		   	 	$cta[4]['campo'] =	'Detalle';	   	 	
		   	 	$cta[4]['dato'] =  'Cta_Devoluciones';
		   	 	$cta[5]['campo'] =	'Codigo';	   	 	
		   	 	$cta[5]['dato'] =  '4.4.02.05.02';
		   	 	insert_generico('Ctas_Proceso',$cta);

		   	 	$datos[15]['dato']='4.4.02.05.02';  

		   	 }

		   }
		   $datos[16]['campo']='ORDEN';
		   $datos[16]['dato']=$parametro['comprobante'];

		   $datos[17]['campo']='Codigo_B';
		   $datos[17]['dato']=$linea[0]['Codigo_P'];

		   // $datos[17]['campo']='IVA';
		   // $datos[17]['dato']=bcdiv($parametro['txt_iva'],'1',4);

		   // $datos[18]['campo']='Fecha_Fab';
		   // $datos[18]['dato']=$parametro['txt_fecha_ela'];

		   
		   // $datos[19]['campo']='Fecha_Exp';
		   // $datos[19]['dato']=$parametro['txt_fecha_exp'];

		   
		   // $datos[20]['campo']='Reg_Sanitario';
		   // $datos[20]['dato']=$parametro['txt_reg_sani'];

		   
		   // $datos[21]['campo']='Lote_No';
		   // $datos[21]['dato']=$parametro['txt_lote'];

		   
		   $datos[18]['campo']='Procedencia';
		   $datos[18]['dato']='Devolucion';

		   
		   // $datos[23]['campo']='Serie_No';
		   // $datos[23]['dato']=$parametro['txt_serie'];

		   // $datos[24]['campo']='P_DESC';
		   // $datos[24]['dato']=$val_descto; 
		   // print_r($parametro);

// print_r($datos);die();
		   $resp = $this->modelo->ingresar_asiento_K($datos);
		   // print_r($resp);die();
		   if($resp ==null)
		   {
		   	return 1;
		   }else
		   {
		   	return -1;
		   }
	
	    // print_r($resp);die();
	}


	function guardar_devoluciones($parametro)
	{
		// print_r($parametro);die();

		   $linea = $this->modelo->trans_kardex_linea_all($parametro['linea']);
		   // print_r($linea);die();
		   $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=$parametro['codigo'];
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$parametro['producto'];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']='';
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['cantidad'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$linea[0]['Cta_Inv'];
		   $datos[5]['campo']='SUBCTA';
		   $datos[5]['dato']=$linea[0]['CodigoL'];		   //proveedor cod
		   $datos[6]['campo']='CodigoU';
		   $datos[6]['dato']=$_SESSION['INGRESO']['Id'];   
		   $datos[7]['campo']='Item';
		   $datos[7]['dato']=$_SESSION['INGRESO']['item'];
		   $datos[8]['campo']='A_No';
		   $datos[8]['dato']=$parametro['linea'];

		   $datos[9]['campo']='Fecha_DUI';
		   $datos[9]['dato']=date('Y-m-d');

		   $datos[10]['campo']='TC';
		   $datos[10]['dato']='P';
		   $datos[11]['campo']='VALOR_TOTAL';
		   $datos[11]['dato']=number_format($parametro['total'],'2');
		   $datos[12]['campo']='CANTIDAD';
		   $datos[12]['dato']=$parametro['cantidad'];
		   $datos[13]['campo']='VALOR_UNIT';
		   $datos[13]['dato']= number_format($parametro['precio'],2);
		   //round($parametro['txt_precio'],2,PHP_ROUND_HALF_DOWN);
		   $datos[14]['campo']='DH';
		   $datos[14]['dato']=1;
		   $datos[15]['campo']='CONTRA_CTA';
		   $datos[15]['dato']=$linea[0]['Contra_Cta'];
		   $datos[16]['campo']='ORDEN';
		   $datos[16]['dato']=$parametro['comprobante'];

		   $datos[17]['campo']='Codigo_B';
		   $datos[17]['dato']=$linea[0]['Codigo_P'];

		   // $datos[17]['campo']='IVA';
		   // $datos[17]['dato']=bcdiv($parametro['txt_iva'],'1',4);

		   // $datos[18]['campo']='Fecha_Fab';
		   // $datos[18]['dato']=$parametro['txt_fecha_ela'];

		   
		   // $datos[19]['campo']='Fecha_Exp';
		   // $datos[19]['dato']=$parametro['txt_fecha_exp'];

		   
		   // $datos[20]['campo']='Reg_Sanitario';
		   // $datos[20]['dato']=$parametro['txt_reg_sani'];

		   
		   // $datos[21]['campo']='Lote_No';
		   // $datos[21]['dato']=$parametro['txt_lote'];

		   
		   $datos[18]['campo']='Procedencia';
		   $datos[18]['dato']='Devolucion';

		   
		   // $datos[23]['campo']='Serie_No';
		   // $datos[23]['dato']=$parametro['txt_serie'];

		   // $datos[24]['campo']='P_DESC';
		   // $datos[24]['dato']=$val_descto; 
		   // print_r($parametro);

// print_r($datos);die();
		   $resp = $this->descargos->ingresar_asiento_K($datos);
		   // print_r($resp);die();
		   if($resp ==null)
		   {
		   	return 1;
		   }else
		   {
		   	return -1;
		   }
	
	    // print_r($resp);die();
	}

	function lista_devoluciones($comprobante){
		$datos = $this->modelo->lista_devoluciones($comprobante);
		$li = count($datos['datos']);
		return array('tr'=>$datos['tabla'],'lineas'=>$li);
	}

	function lista_devoluciones_x_departamento($comprobante){
		$datos = $this->modelo->lista_devoluciones_x_departamento($comprobante);
		$li = count($datos['datos']);
		return array('tr'=>$datos['tabla'],'lineas'=>$li);
	}

	function eliminar_linea_devo($parametros)
	{
		return $this->modelo->eliminar_linea_dev($parametros['codigo'],$parametros['comprobante']);
	}
	function eliminar_linea_devo_dep($parametros)
	{
		return $this->modelo->eliminar_linea_dev_dep($parametros['codigo'],$parametros['comprobante'],$parametros['No']);
	}


}