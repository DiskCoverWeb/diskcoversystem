<?php 
$_SESSION['INGRESO']['modulo_']='99';
include (dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/articulosM.php');
/**
 * 
 */
$controlador = new articulosC();
if(isset($_GET['productos']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos($parametros));
}
if(isset($_GET['search']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar($query));

}
if(isset($_GET['familias']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->familias($query));
}
if(isset($_GET['familias2']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->familias2($query));
}

if(isset($_GET['cuenta']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->catalogo_cuenta($query));
}

if(isset($_GET['producto_nuevo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->Ingresar_producto($parametros));
}

if(isset($_GET['proveedor_nuevo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->Ingresar_proveedor($parametros));
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
if(isset($_GET['proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->proveedores($query));
}

if(isset($_GET['add_producto']))
{
	$parametros = $_POST;
	echo json_encode($controlador->agreagar_producto($parametros));
}

if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}

if(isset($_GET['generar_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_factura($parametros));
}

if(isset($_GET['buscar_ultimo']))
{
	$parametros = $_POST['cta'];
	echo json_encode($controlador->buscar_ultimo($parametros));
}

if(isset($_GET['cuenta_asignar']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	$cuenta = $_GET['cuenta_asignar'];
	echo json_encode($controlador->cuentas_asignar($cuenta,$query));
}

if(isset($_GET['eliminar_ingreso']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_factura($parametros));
}

if(isset($_GET['Articulos_imagen']))
{
	$parametros = $_POST;
	echo json_encode($controlador->agregar_articulo_foto($_FILES,$_POST));
}

if(isset($_GET['num_com']))
{
	$fecha = $_POST['fecha'];
	echo json_encode(numero_comprobante1('Diario',true,false,$fecha));
}

if(isset($_GET['eliminar_articulos']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_articulos($id));
}
if(isset($_GET['detalle_articulos']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->detalle_articulos($id));
}

if(isset($_GET['familia_new']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->familia_new($parametros));
}



class articulosC 
{
	private $modelo;
	private $ing_descargos;
	function __construct()
	{
		$this->modelo = new articulosM();
		$this->ing_descargos = new ingreso_descargosM();
		$this->paciente = new pacienteM();
	}

	function autocomplet_producto($query)
	{
		$datos = $this->modelo->cargar_productos($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			$Familia = $this->modelo->familia_pro(substr($value['Codigo_Inv'],0,5));
			$costo =  $this->ing_descargos->costo_venta($value['Codigo_Inv']);
			$costoTrans = $this->ing_descargos->costo_producto($value['Codigo_Inv']);
			if(empty($Familia))
			{
				$Familia[0]['Producto'] = '-';
				$Familia[0]['Codigo_Inv'] = '.';
			}
			if(empty($costo))
			{
				$costo[0]['Existencia'] = 0;
			}
			if(empty($costoTrans))
			{
				$costoTrans[0]['Costo'] = 0;				
			}


			$productos[] = array('id'=>$Familia[0]['Producto'].'_'.$Familia[0]['Codigo_Inv'].'_'.$value['Codigo_Inv'].'_'.$costoTrans[0]['Costo'].'_'.$value['Cta_Inventario'].'_'.$value['Producto'].'_'.$value['Unidad'].'_'.$value['Ubicacion'].'_'.$value['IVA'].'_'.$costo[0]['Existencia'].'_'.$value['Reg_Sanitario'].'_'.$value['Maximo'].'_'.$value['Minimo'],'text'=>$value['Producto']);

		}
		return $productos;
		// print_r($productos);die();
	}


	function crear_tabla_datos($orden,$prove)
	{
		$show = 'none';
    	$cabecera_tabla = '
    	<div class="table-responsive">

  		<table class="table table-hover" id="tbl_style">
  			<thead>
  				<th>ITEM</th>
  				<th>FECHA</th>
  				<th>REFERENCIA</th>
  				<th>DESCRIPCION</th>
          <th class="text-right">CANTIDAD</th>
          <th class="text-right">PRECIO</th>
  		 <th class="text-right">DCTO</th>
  		 <th class="text-right">SUB TOTAL</th>
          <th class="text-right">IVA</th>
  				<th class="text-right">TOTAL</th>
  				<th></th>
  			</thead>
  			<tbody>';

    	$datos = $this->modelo->cargar_productos_pedido($orden,$prove);
		// $paginacion = paginancion('Asiento_K',$parametros['fun'],$parametros['pag']);

    	$num_reg = count($datos);
    	$tr='';
    	$subtotal=0;$ivatotal = 0;$total=0;
    	$d7=0;
    	$d=0;
    	$dcto = 0;
    	foreach ($datos as $key => $value) {

    		// print_r($value['Fecha_DUI']->format('Y-m-d'));
    		$show = 'block';
    		$parametros = array('codigo'=>$value['SUBCTA'],'query'=>'');
    		$nombre =  $this->paciente->cargar_paciente_proveedor($parametros);
    		// print_r($nombre);die();
    		$provee = $nombre[0]['Cliente'];
    		$subtotal+=bcdiv(($value['VALOR_UNIT']*$value['CANTIDAD'])-$value['P_DESC'],'1',2);
    		$ivatotal+=$value['IVA'];
    		$total+=$value['VALOR_TOTAL'];
    		$dcto+=$value['P_DESC'];

    		$fecha = $value['Fecha_DUI']->format('Y-m-d');

    		$su = bcdiv(($value['VALOR_UNIT']*$value['CANTIDAD'])-number_format($value['P_DESC'],2),'1',2);

			$d =   dimenciones_tabl(strlen($value['A_No']));
			$d1 =   dimenciones_tabl(strlen($fecha));
			$d2 =   dimenciones_tabl(strlen($value['CODIGO_INV']));
			$d3 =   dimenciones_tabl(strlen($value['PRODUCTO']));
			$d4 =   dimenciones_tabl(strlen($value['CANTIDAD']));
			$d5 =   dimenciones_tabl(strlen($value['VALOR_UNIT']));
			$d6 =   dimenciones_tabl(strlen($value['IVA']));
			$d7 =   dimenciones_tabl(strlen($value['VALOR_TOTAL']));
    		$tr.='<tr>
  					<td width="'.$d.'">'.$value['A_No'].'</td>
  					<td width="'.$d1.'">'.$fecha.'</td>
  					<td width="'.$d2.'">'.$value['CODIGO_INV'].'</td>
  					<td width="'.$d3.'">'.$value['PRODUCTO'].'</td>
  					<td width="'.$d4.'" class="text-right">'.$value['CANTIDAD'].'</td>
  					<td width="'.$d5.'" class="text-right">'.$value['VALOR_UNIT'].'</td>
  					<td width="'.$d7.'" class="text-right">'.$value['P_DESC'].'</td>
  					<td width="'.$d6.'" class="text-right">'.$su.'</td>
  					<td width="'.$d7.'" class="text-right">'.$value['IVA'].'</td>
  					<td width="'.$d7.'" class="text-right">'.$value['VALOR_TOTAL'].'</td>
  					<td width="10px">
  						<!-- <button class="btn btn-sm btn-primary" onclick="editar_lin()" title="Editar paciente"><span class="glyphicon glyphicon-floppy-disk"></span></button> -->
  						<button class="btn btn-sm btn-danger" title="Eliminar paciente"  onclick="eliminar_lin(\''.$value['A_No'].'\',\''.$orden.'\',\''.$prove.'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr>
  					<td width="'.$d.'" colspan="5"></td>
  					<td width="'.$d7.'"><b>TOTALES</b></td>
  					<td width="'.$d7.'" class="text-right">'.number_format($dcto,2,'.','').'</td>
  					<td width="'.$d7.'" class="text-right">'.number_format($subtotal,2,'.','').'</td>
  					<td width="'.$d7.'" class="text-right">'.number_format($ivatotal,2,'.','').'</td>
  					<td width="'.$d7.'" class="text-right">'.number_format($total,2,'.','').'</td>
  				</tr>';
		if($num_reg==0)
		{
			$tr.= '<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>';
		}else
		{

		}

		$footer_tabla ='</tbody></table>
		<input type="hidden" id="iva_'.$orden.'" value="'.$ivatotal.'"/>		 
		</div>
		<div class="col-sm-7" style=" display:'.$show.'">
		<button type="button" class="btn btn-danger" onclick="eliminar_todo(\''.$orden.'\',\''.$prove.'\')"><i class="fa fa-trash"></i> Eliminar Todo</button>
		<button type="button" class="btn btn-primary" onclick="generar_factura(\''.$orden.'\',\''.$prove.'\')" ><i class="fa fa-archive"></i> Registrar Ingreso</button>
		<button type="button" class="btn btn-primary" onclick="subir(\''.$orden.'\',\''.$prove.'\')"  data-toggle="modal" data-target="#modal_de_foto"><i class="fa fa-file"></i> Subir Documento</button>
		</div>';

		 return $cabecera_tabla.$tr.$footer_tabla;
	}

    function cargar_productos($parametros)
    {
    	$ordenes = $this->modelo->cargar_productos_pedido_TAB();
    	$datos = $this->modelo->cargar_productos_pedido();
    	
         $tab=' <ul class="nav nav-tabs">';
         $content = '<div class="tab-content">';
    	foreach ($ordenes as $key => $value) {
    		if($value['SUBCTA']!='.')
    		{
    		$prove = $this->modelo->proveedores(false,$value['SUBCTA']);
    		if($key==0)
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['ORDEN'].'" class="tab-pane fade in active">';
    			$tab.='<li class="active"><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['ORDEN'].'">'.$prove[0]['Cliente'].' Num Factura: '.$value['ORDEN'].'</a></li>';
    		}else
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['ORDEN'].'" class="tab-pane fade">';
    			$tab.='<li><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['ORDEN'].'">'.$prove[0]['Cliente'].' Num Factura: '.$value['ORDEN'].'</a></li>';
    		}

    		    $content.= $this->crear_tabla_datos($value['ORDEN'],$value['SUBCTA']);
    		$content.='</div>';
    	  }
    	}
    	$tab.='</ul>';
    	$content.='</div>';
    	$tabs_tabla = $tab.$content;
    	if(!isset($datos[0]['A_No']))
    	{
    		$datos[0]['A_No'] = 0;
    	}
    	if(count($ordenes)==0)
    	{
    		$tabs_tabla =$this->crear_tabla_datos('1','1');
    	}
		$tabla = array('pag'=>'','tabla'=>$tabs_tabla,'item'=>$datos[0]['A_No']);	
			// print_r($tabla);die();
		return $tabla;		
    }

	function proveedores($query)
	{
		$datos = $this->modelo->proveedores($query);
		if($datos!=-1)
		{
		     $prov = array();
		     foreach ($datos as $key => $value) {
			  // print_r($value);die();
			  $prov[] = array('id'=>$value['CI_RUC'].'-'.$value['Cta'].'-'.$value['Codigo'],'text'=>$value['Cliente']);
		     }
		     return $prov;
		 }else
		 {
		 	return -1;
		 }
	}

	function familias($query)
	{
		$datos = $this->modelo->familia_pro(false,$query);
		$familias = array();
		$format_inv= count(explode('.', $_SESSION['INGRESO']['Formato_Inventario']))-1;
		foreach ($datos as $key => $value) {

			$fa =count(explode('.',$value['Codigo_Inv']));
			if($format_inv == $fa)
			{
				$tiene = $this->modelo->familia_con_productos($value['Codigo_Inv']);
				if($tiene[0]['cant']!=0)
				{
				  $familias[] = array('id'=>$value['Codigo_Inv'].'-'.$value['Cta_Inventario'],'text'=>$value['Producto']);
				}
			}			
		}
		return $familias;
	}

	function familias2($query)
	{
		$datos = $this->modelo->familia_pro(false,$query);
		$familias = array();
		$format_inv= count(explode('.', $_SESSION['INGRESO']['Formato_Inventario']))-1;
		foreach ($datos as $key => $value) {
			$familias[] = array('id'=>$value['Codigo_Inv'].'-'.$value['Cta_Inventario'],'text'=>$value['Producto']);	
		}
		return $familias;
	}
	function catalogo_cuenta($query)
	{
		$datos = $this->modelo->catalogo_cuentas(false,$query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);			
		}
		return $cta;
	}

	function Ingresar_producto($parametros)
	{
		if(!isset($parametros['ddl_cta_venta']))   { $parametros['ddl_cta_venta'] = '';}
		if(!isset($parametros['ddl_cta_ventas_0'])){ $parametros['ddl_cta_ventas_0'] = '';}
		if(!isset($parametros['ddl_cta_vnt_anti'])){ $parametros['ddl_cta_vnt_anti'] = '';}


		$cta_i = explode('-',$parametros['ddl_familia_modal']);
		$datos[0]['campo']='Periodo';
		$datos[0]['dato']=$_SESSION['INGRESO']['periodo'];
		$datos[1]['campo']='TC';
		$datos[1]['dato']='P';
		$datos[2]['campo']='Codigo_Inv';
		$datos[2]['dato']=$cta_i[0].'.'.$parametros['txt_ref'];
		$datos[3]['campo']='Producto';
		$datos[3]['dato']=strtoupper($parametros['txt_nombre']);
		$datos[4]['campo']='Unidad';
		$datos[4]['dato']=$parametros['txt_uni'];
		$datos[5]['campo']='Minimo';
		$datos[5]['dato']=$parametros['txt_min'];
		$datos[6]['campo']='Maximo';
		$datos[6]['dato']=$parametros['txt_max'];
		$datos[7]['campo']='Cta_Costo_Venta';
		$datos[7]['dato']=$parametros['ddl_cta_CV'];
		$datos[8]['campo']='INV';
		$datos[8]['dato']='1';
		$datos[9]['campo']='Stock_Actual';
		$datos[9]['dato']=0;
		$datos[10]['campo']='Item';
		$datos[10]['dato']=$_SESSION['INGRESO']['item'];
		$datos[11]['campo']='Cta_Inventario';
		$datos[11]['dato']=$parametros['ddl_cta_inv']; 
		$datos[12]['campo']='Reg_Sanitario';
		$datos[12]['dato']=$parametros['txt_reg_sanitario']; 
		$datos[13]['campo']='Codigo_Barras';
		$datos[13]['dato']=$parametros['txt_cod_barras']; 

		$datos[14]['campo']='Cta_Ventas';
		$datos[14]['dato']= $parametros['ddl_cta_venta']; 

		$datos[15]['campo']='Cta_Ventas_0';
		$datos[15]['dato']= $parametros['ddl_cta_ventas_0']; 

		$datos[16]['campo']='Cta_Ventas_Anticipadas';
		$datos[16]['dato']= $parametros['ddl_cta_vnt_anti']; 

		$datos[17]['campo']='T';
		$datos[17]['dato']='N'; 

		// print_r($parametros);die();
		if($parametros['txt_id']!='')
		{
			$where[0]['campo'] = 'ID';
			$where[0]['valor'] = $parametros['txt_id'];

			// print_r($datos);die();
			$pro = $this->modelo->update('Catalogo_Productos',$datos,$where);
		}else
		{
		 $pro = $this->modelo->guardar('Catalogo_Productos',$datos);
		}
		if($pro =='')
		{
			return 1;
		}else
		{
			return -1;
		}



	}
	function Ingresar_proveedor($parametros)
	{

		$codigo = digito_verificador_nuevo($parametros['txt_ruc'],1);
		// print_r($codigo);die();
		$existe = $this->modelo->clientes_all(false,$codigo['Codigo']);
		$cli = '';
		if(empty($existe))
		{
		    $datos[0]['campo'] = 'FA';
		    $datos[0]['dato'] = '1';
		    $datos[1]['campo'] = 'T';
		    $datos[1]['dato'] = 'N';
		    $datos[2]['campo'] = 'Codigo';
		    $datos[2]['dato'] = $codigo['Codigo'];
		    $datos[3]['campo'] = 'Cliente';
		    $datos[3]['dato'] = $parametros['txt_nombre_prove'];
		    $datos[4]['campo'] = 'CI_RUC';
		    $datos[4]['dato'] = $parametros['txt_ruc'];
		    $datos[5]['campo'] = 'Email';
		    $datos[5]['dato'] = $parametros['txt_email'];
		    $datos[6]['campo'] = 'Telefono';
		    $datos[6]['dato'] = $parametros['txt_telefono'];
		    $datos[7]['campo'] = 'Direccion';
		    $datos[7]['dato'] = $parametros['txt_direccion'];
		    $datos[8]['campo'] = 'Fecha';
		    $datos[8]['dato'] = strval(date('Y-m-d'));
		    $cli = $this->modelo->guardar('Clientes',$datos);
		}else{$cli =1;}

		 $exist = $this->modelo->catalogo_Cxcxp($codigo['Codigo']);

		 if(empty($exist))
		 {
		 	$datos1[0]['campo'] = 'Codigo';
		    $datos1[0]['dato'] = $codigo['Codigo'];
		    $datos1[1]['campo'] = 'Cta';
		    $datos1[1]['dato'] = $this->modelo->buscar_cta_proveedor();
		    $datos1[2]['campo'] = 'Item';
		    $datos1[2]['dato'] = $_SESSION['INGRESO']['item'];
		    $datos1[3]['campo'] = 'Periodo';
		    $datos1[3]['dato'] = $_SESSION['INGRESO']['periodo'];
		    $datos1[3]['campo'] = 'TC';
		    $datos1[3]['dato'] = 'P';
		    $cta = $this->modelo->guardar('Catalogo_CxCxP',$datos1);
		 }else{$cta = 1;}

		 if($cta == 1 && $cli ==1)
		 {
		 	return -2;
		 }else
		 {
		 	return 1;
		 }

	}

	function agreagar_producto($parametro)
	{
		// print_r($parametro);die();

		   $val_descto = (($parametro['txt_precio']*$parametro['txt_canti'])*$parametro['txt_descto'])/100;
		   $pro = $this->modelo->buscar_cta_proveedor();
		  $producto = explode('_',$parametro['ddl_producto']);
		  $prove = explode('-', $parametro['ddl_proveedor']);
		   $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=$parametro['txt_referencia'];
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$producto[5];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']=$parametro['txt_unidad']; 
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['txt_canti'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$producto[4];
		   $datos[5]['campo']='SUBCTA';
		   $datos[5]['dato']=$prove[2];		   
		   $datos[6]['campo']='CodigoU';
		   $datos[6]['dato']=$_SESSION['INGRESO']['Id'];   
		   $datos[7]['campo']='Item';
		   $datos[7]['dato']=$_SESSION['INGRESO']['item'];
		   $datos[8]['campo']='A_No';
		   $datos[8]['dato']=$parametro['A_No']+1;

		   $datos[9]['campo']='Fecha_DUI';
		   $datos[9]['dato']=$parametro['txt_fecha'];

		   $datos[10]['campo']='TC';
		   $datos[10]['dato']='P';
		   $datos[11]['campo']='VALOR_TOTAL';
		   $datos[11]['dato']=bcdiv($parametro['txt_total'],'1',4);
		   $datos[12]['campo']='CANTIDAD';
		   $datos[12]['dato']=$parametro['txt_canti'];
		   $datos[13]['campo']='VALOR_UNIT';
		   $datos[13]['dato']= bcdiv($parametro['txt_precio'], '1', 7);
		   //round($parametro['txt_precio'],2,PHP_ROUND_HALF_DOWN);
		   $datos[14]['campo']='DH';
		   $datos[14]['dato']=1;
		   $datos[15]['campo']='CONTRA_CTA';
		   $datos[15]['dato']=$pro;
		   $datos[16]['campo']='ORDEN';
		   $datos[16]['dato']=$parametro['txt_num_fac'];
		   $datos[17]['campo']='IVA';
		   $datos[17]['dato']=bcdiv($parametro['txt_iva'],'1',4);

		   $datos[18]['campo']='Fecha_Fab';
		   $datos[18]['dato']=$parametro['txt_fecha_ela'];

		   
		   $datos[19]['campo']='Fecha_Exp';
		   $datos[19]['dato']=$parametro['txt_fecha_exp'];

		   
		   $datos[20]['campo']='Reg_Sanitario';
		   $datos[20]['dato']=$parametro['txt_reg_sani'];

		   
		   $datos[21]['campo']='Lote_No';
		   $datos[21]['dato']=$parametro['txt_lote'];

		   
		   $datos[22]['campo']='Procedencia';
		   $datos[22]['dato']=$parametro['txt_procedencia'];

		   
		   $datos[23]['campo']='Serie_No';
		   $datos[23]['dato']=$parametro['txt_serie'];

		   $datos[24]['campo']='P_DESC';
		   $datos[24]['dato']=$val_descto; 

		   // $datos[25]['campo']='UNIDAD';
		   // $datos[25]['dato']=$parametro['txt_unidad']; 

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

	function lineas_eli($parametros)
	{
		$resp = $this->modelo->lineas_eli($parametros);
		return $resp;
	}

	function generar_factura($parametros)
	{
		 if($parametros['iva_exist']!=0)
		 {
		   $cuenta_iva =$this->modelo->buscar_cta_iva_inventario();
		   if($cuenta_iva!=-1)
		   {
		   	$ruc = $this->modelo->proveedores(false,$parametros['prove']);
			$res = $this->generar_factura_entrada($parametros['num_fact'],$ruc[0]['CI_RUC'],$parametros['prove']);
			return $res;
		   }else
		   {
		   	  return array('resp'=>-2);
		   }
		 }else{
		  
			$ruc = $this->modelo->proveedores(false,$parametros['prove']);
			if(count($ruc)==0)
			{
				$ruc = $this->modelo->clientes_all($query=false,$parametros['prove']);
			}
			if(count($ruc)==0)
			{
				$ruc[0]['CI_RUC'] = '.';
			}
			$res = $this->generar_factura_entrada($parametros['num_fact'],$ruc[0]['CI_RUC'],$parametros['prove']);
			return $res;
		}
			// print_r('expression');die();		
	}

	function generar_factura_entrada($orden,$ruc,$CodigoPrv)
	{
		// print_r($CodigoPrv);die();
		if($this->modelo->misma_fecha($orden,$CodigoPrv)==-1)
		{
			return array('resp'=>-3,'com'=>'');
		}
		$ruc1 = $ruc;

		//esto se realiza  solo para devoluciones en donde CodigoPrV tiene que ser el codigo de la sub cuenta traido desde la vista
		if($ruc=='.')
		{
			$ruc1 = $CodigoPrv;
		}
		$asientos_SC = $this->modelo->datos_asiento_SC($orden,$CodigoPrv);

		$parametros_debe = array();
		$parametros_haber = array();
		$nombre='';
		// $fecha=date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->ing_descargos->catalogo_cuentas($value['CONTRA_CTA']);
			 // print_r($cuenta);die();
			 if(count($cuenta)==0){ $cuenta[0]['Cuenta'] = '.'; $cuenta[0]['TC'] = 'CD';$cuenta[0]['Cuenta']='.'; $cuenta[0]['TC']='.';}
			 $sub = $this->modelo->proveedores($query=false,$value['SUBCTA']);
			 if(count($sub)==0){$sub[0]['Cliente']='.';}
			 $nombre=$sub[0]['Cliente'];
			 // print_r($sub);die();
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 2, //debito o credito (1 o 2);
                    'sub'=> $ruc1, //Codigo se trae catalogo subcuenta o ruc del proveedor en caso de que se este ingresando
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_DUI']->format('Y-m-d'), //fecha 
                    'fac2'=>$orden,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Cliente'],//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $cuenta[0]['TC'],                        
                  );
                  $this->ing_descargos->generar_asientos_SC($parametros);
		}
		
		// print_r($asientos_SC);die();

		//asientos para el debe
		$asiento_debe = $this->modelo->datos_asiento_haber($orden,$CodigoPrv);
		$tiene_iva =false;
		foreach ($asiento_debe as $key => $value) {
			 if($value['IVA']!=0)
			 {
			 	$tiene_iva = true;
			 	break;
			 }
		}

		if($tiene_iva ==true)
		{
			$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');	
			$total_iva = $this->modelo->iva_comprobante($orden,$CodigoPrv);
			$cuenta_iva =$this->modelo->buscar_cta_iva_inventario();
		    foreach ($total_iva as $key => $value) 
		    {
				    $parametros_debe = array(
				     "va" =>bcdiv($value['IVA'],'1',2),//valor que se trae del otal sumado
                      "dconcepto1" =>'Cta_IVA_Inventario',
                      "codigo" => $cuenta_iva, // cuenta de codigo de 
                      "cuenta" => 'Cta_IVA_Inventario', // detalle de cuenta;
                      "efectivo_as" =>$fecha, // observacion si TC de catalogo de cuenta
                      "chq_as" => 0,
                      "moneda" => 1,
                      "tipo_cue" => 1,
                      "cotizacion" => 0,
                      "con" => 0,// depende de moneda
                      "t_no" => '99',
			    );
				     $this->ing_descargos->ingresar_asientos($parametros_debe);
		    }

		    $siento_debe_con_iva = $this->modelo->datos_asiento_haber_CON_IVA($orden,$CodigoPrv);
		   foreach ($siento_debe_con_iva as $key => $value) 
		    {
			    // print_r($value);die();
			       $cuenta = $this->ing_descargos->catalogo_cuentas($value['cuenta']);		
			       // print_r($cuenta);die();
				    $parametros_debe = array(
				     "va" =>bcdiv($value['sub'],'1',2),//valor que se trae del otal sumado
                      "dconcepto1" =>$cuenta[0]['Cuenta'],
                      "codigo" => $value['cuenta'], // cuenta de codigo de 
                      "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                      "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                      "chq_as" => 0,
                      "moneda" => 1,
                      "tipo_cue" => 1,
                      "cotizacion" => 0,
                      "con" => 0,// depende de moneda
                      "t_no" => '99',
			    );
				     $this->ing_descargos->ingresar_asientos($parametros_debe);
		    }
		}else
		{
		    $fecha = $asiento_debe[0]['fecha']->format('Y-m-d');	
		    foreach ($asiento_debe as $key => $value) 
		    {
			    // print_r($value);die();
			       $cuenta = $this->ing_descargos->catalogo_cuentas($value['cuenta']);		
			       // print_r($cuenta);die();
				    $parametros_debe = array(
				     "va" =>round($value['total'],2),//valor que se trae del otal sumado
                      "dconcepto1" =>$cuenta[0]['Cuenta'],
                      "codigo" => $value['cuenta'], // cuenta de codigo de 
                      "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                      "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                      "chq_as" => 0,
                      "moneda" => 1,
                      "tipo_cue" => 1,
                      "cotizacion" => 0,
                      "con" => 0,// depende de moneda
                      "t_no" => '99',
			    );
				     $this->ing_descargos->ingresar_asientos($parametros_debe);
		    }
        }
        // asiento para el haber
		$asiento_haber  =  $this->modelo->datos_asiento_debe($orden,$CodigoPrv);
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->ing_descargos->catalogo_cuentas($value['cuenta']);
			if(count($cuenta)==0){$cuenta[0]['Cuenta']='.';}			
				$parametros_haber = array(
                  "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
                );
                $this->ing_descargos->ingresar_asientos($parametros_haber);
		}
		// print_r($fecha);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->ing_descargos->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		// print_r($debe.'-'.$haber);die();
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Entrada de inventario por '.$nombre.' de la factura '.$orden.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=>'.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002 '' hasta que no se cambie la funcion ese debe ser el formato'
        	        );

                $resp = $this->ing_descargos->generar_comprobantes($parametro_comprobante);
                // print_r('ssss');die();
                // $cod = explode('-',$num_comprobante);
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_entrada($orden,$num_comprobante,$fecha,$CodigoPrv,$nombre)==1)
                	{
                		$resp = $this->modelo->eliminar_aiseto_K($orden,$CodigoPrv);
                		if($resp==1)
                		{
                			$this->modelo->eliminar_aiseto();
                			$this->modelo->eliminar_aiseto_sc($orden);                			
                			mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		}else
                		{
                			return array('resp'=>-1,'com'=>'No se pudo eliminar asiento_K');
                		}
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {

			     $this->modelo->eliminar_aiseto();
			     $this->modelo->eliminar_aiseto_sc($orden);
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{
				// print_r($debe."-".$haber); 

			     $this->modelo->eliminar_aiseto();
			     $this->modelo->eliminar_aiseto_sc($orden);
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			$this->modelo->eliminar_aiseto();
			$this->modelo->eliminar_aiseto_sc($orden);
			return array('resp'=>-1,'com'=>'No coinciden');

		}
	}


//en proceso
	function ingresar_trans_kardex_entrada($orden,$comprobante,$fechaC,$CodigoPrv,$nombre)
    {
		$datos_K = $this->modelo->cargar_pedidos($orden,$CodigoPrv);
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		foreach ($datos_K as $key => $value) {
		   $datos_inv = $this->ing_descargos->lista_hijos_id($value['CODIGO_INV']);
		    $cant[2] = 0;
		   if(count($datos_inv)>0)
		   {
		   	 $cant = explode(',',$datos_inv[0]['id']);
		   }
		   			
		    $datos[0]['campo'] ='Codigo_Inv';
		    $datos[0]['dato'] =$value['CODIGO_INV']; 
		    $datos[1]['campo'] ='Fecha';
		    $datos[1]['dato'] =$fechaC; 
		    $datos[2]['campo'] ='Numero';
		    $datos[2]['dato'] =$comprobante;  
		    $datos[3]['campo'] ='T';
		    $datos[3]['dato'] ='N'; 
		    $datos[4]['campo'] ='TP';
		    $datos[4]['dato'] ='CD'; 
		    $datos[5]['campo'] ='Codigo_P';
		    $datos[5]['dato'] =$_SESSION['INGRESO']['CodigoU']; 
		    $datos[6]['campo'] ='Cta_Inv';
		    $datos[6]['dato'] =$value['CTA_INVENTARIO']; 
		    $datos[7]['campo'] ='Contra_Cta';
		    $datos[7]['dato'] =$value['CONTRA_CTA']; 
		    $datos[8]['campo'] ='Periodo';
		    $datos[8]['dato'] =$_SESSION['INGRESO']['periodo']; 
		    $datos[9]['campo'] ='Entrada';
		    $datos[9]['dato'] =$value['CANTIDAD']; 
		    $datos[10]['campo'] ='Valor_Unitario';
		    $datos[10]['dato'] =round($value['VALOR_UNIT'],2); 
		    $datos[11]['campo'] ='Valor_Total';
		    $datos[11]['dato'] =round($value['VALOR_TOTAL'],2); 
		    $datos[12]['campo'] ='Costo';
		    $datos[12]['dato'] =round($value['VALOR_UNIT'],2); 
		    $datos[13]['campo'] ='Total';
		    $datos[13]['dato'] =round($value['VALOR_TOTAL'],2);
		    $datos[14]['campo'] ='Existencia';
		    $datos[14]['dato'] =round(($cant[2]),2)+intval($value['CANTIDAD']);
		    $datos[15]['campo'] ='CodigoU';
		    $datos[15]['dato'] =$_SESSION['INGRESO']['CodigoU'];
		    $datos[16]['campo'] ='Item';
		    $datos[16]['dato'] =$_SESSION['INGRESO']['item'];
		    $datos[17]['campo'] ='CodBodega';
		    $datos[17]['dato'] ='01';
		    $datos[18]['campo'] ='CodigoL';
		    $datos[18]['dato'] =$value['SUBCTA'];
		    $datos[19]['campo'] ='Detalle';
		    $datos[19]['dato'] ='Entrada de inventario por '.$nombre.' de la factura '.$orden.' el dia '.$fechaC;

		    $datos[20]['campo'] ='Fecha_Exp';
		    $datos[20]['dato'] =$value['Fecha_Exp']->format('Y-m-d');

		    $datos[21]['campo'] ='Fecha_Fab';
		    $datos[21]['dato'] =$value['Fecha_Fab']->format('Y-m-d');

		    $datos[22]['campo'] ='Reg_Sanitario';
		    $datos[22]['dato'] =$value['Reg_Sanitario'];

		    $datos[23]['campo'] ='Lote_No';
		    $datos[23]['dato'] =$value['Lote_No'];

		    $datos[24]['campo'] ='Procedencia';
		    $datos[24]['dato'] =$value['Procedencia'];

		    $datos[25]['campo'] ='Serie_No';
		    $datos[25]['dato'] =$value['Serie_No'];

		    $datos[26]['campo'] ='Factura';
		    $datos[26]['dato'] =$value['ORDEN'];


		    // print_r($this->ing_descargos->insertar_trans_kardex($datos));die();
		     if($this->ing_descargos->insertar_trans_kardex($datos)!="")
		     {
		     	$resp = 0;
		     } 
	}
	return $resp;

}

function cuentas_asignar($cuenta,$query)
{
	$datos = $this->modelo->cuentas_asignar($cuenta,$query);
	$cta = array();
	foreach ($datos as $key => $value) {
		$cta[] = array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);
	}
	return $cta;
}
function buscar_ultimo($cta)
{
	$dato =$this->modelo->buscar_ultimo($cta);	
	$format_inv= explode('.', $_SESSION['INGRESO']['Formato_Inventario']);
	$pos = count($format_inv);
	$num_car =strlen($format_inv[$pos-1]);
	$ceros = str_repeat('0',$num_car-1);
	$num =$ceros.'1';
	if(!empty($dato))
	{
		$numero = explode('.',$dato[0]['Codigo_Inv']);
		$numero = $numero[$pos-1]+1;
		$num = $numero;
		$numero = strlen($numero);
		$falta = $num_car-$numero;
		if($falta>0)
		{
			$ceros = str_repeat('0',$falta);
			$num = $ceros.''.$num;
		}		
		$cod_in = $cta.'.'.$num;
		$existe = true;
	    while ($existe == true) {
		    $cod = $this->modelo->buscar_cod_existente($cod_in);
		    if(empty($cod))
		    {
			    $existe =false;
			    break;
		    }else{
		    	 $num = $ceros.($num+1);
		         $cod_in = $cta.'.'.$num;
		    }
		   
	    }

	    // print_r($num);die();

	}
	return $num;	
}

function eliminar_factura($parametros)
{
	$resp = $this->modelo->eliminar_aiseto_K($parametros['orden'],$parametros['pro']);
	return $resp;
}


 function agregar_articulo_foto($file,$post)
   {
   	// print_r($file);
   	// print_r($post);die();
   	$ruta='../../vista/TEMP/';//ruta carpeta donde queremos copiar las imágenes
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if($file['file']['type']=="image/jpeg" || $file['file']['type']=="image/pjpeg" || $file['file']['type']=="image/gif" || $file['file']['type']=="image/png" || $file['file']['type']=="application/pdf")
      {
   	     $uploadfile_temporal=$file['file']['tmp_name'];
   	     $tipo = explode('/', $file['file']['type']);
         $nombre = $post['txt_nom_img'].'.'.$tipo[1];
        
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		     $base=1;
   		     // if($post['txt_id']!='')
   		     // 	{
   		     // 		$base = $this->modelo->img_guardar($nuevo_nom,$post['txt_id']);
   		     // 	} else
   		     // 	{
   		     // 		$base = $this->modelo->img_guardar($nuevo_nom,'',$post['txt_nom_img']);
   		     // 	}  		     
   		     if($base==1)
   		     {
   		     	return 1;
   		     }else
   		     {
   		     	return -1;
   		     }

   	     }
   	     else
   	     {
   		     return -1;
   	     } 
     }else
     {
     	return -2;
     }

  }

  function autocompletar($query)
	{

		$datos = $this->modelo->clientes_all($query);
		// print_r($datos);die();
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ID'],"label"=>$value['Cliente'],'dir'=>$value['Direccion'],'tel'=>$value['Telefono'],'email'=>$value['Email'],'CI'=>$value['CI_RUC']);
		}
		return $result;
	}

   function eliminar_articulos($id)
   {
   	return $this->modelo->eliminar_articulos($id);
   }

   function detalle_articulos($id)
   {

   	$datos = $this->modelo->articulos($id);
   	$cta_inv='';
   	if($datos[0]['Cta_Inventario']!='.' && $datos[0]['Cta_Inventario']!='')
   	{
   		$cta_inv = $this->modelo->catalogo_cuentas($datos[0]['Cta_Inventario'],$query=false);
   	}
   	$cta_CV='';
   	if($datos[0]['Cta_Costo_Venta']!='.' && $datos[0]['Cta_Costo_Venta']!='')
   	{
   		$cta_CV = $this->modelo->catalogo_cuentas($datos[0]['Cta_Costo_Venta'],$query=false);
   	}
   	$cta_V = '';
   	if($datos[0]['Cta_Ventas']!='.' && $datos[0]['Cta_Ventas']!='')
   	{
   		$cta_V = $this->modelo->catalogo_cuentas($datos[0]['Cta_Ventas'],$query=false);
   	}
   	$cta_V0 = '';
   	if($datos[0]['Cta_Ventas_0']!='.' && $datos[0]['Cta_Ventas_0']!='')
   	{
   		$cta_V0 = $this->modelo->catalogo_cuentas($datos[0]['Cta_Ventas_0'],$query=false);
   	}
   	$cta_VA = '';
   	if($datos[0]['Cta_Ventas_0']!='.' && $datos[0]['Cta_Ventas_0']!='')
   	{
   		$cta_VA = $this->modelo->catalogo_cuentas($datos[0]['Cta_Ventas_Anticipadas'],$query=false);
   	}
   	$codi = explode('.',$datos[0]['Codigo_Inv']);
   	$partes = count($codi);
   	$f = '';
   	for ($i=0; $i < $partes-1; $i++) { 
   		$f.=$codi[$i].'.';
   	}
   	$f = substr($f,0,-1);
   	$fami = $this->modelo->familia_pro($f,$query = false);

   	return array('datos'=>$datos,'inv'=>$cta_inv,'cv'=>$cta_CV,'v'=>$cta_V,'v0'=>$cta_V0,'va'=>$cta_VA,'fami'=>$fami,'num'=>$codi[$partes-1]);
   }

   function familia_new($parametros)
   {
   	$codigo = $parametros['codigo'];
   	$nombre = $parametros['nombre'];


   	if(count($this->modelo->familia_pro($codigo,$query=false,$exacto=false))>0)
   	{

   	// print_r($codigo);die();
   		return -2;
   	}
   	if(count($this->modelo->familia_pro($Codigo=false,$nombre,$exacto=true))>0)
   	{

   	// print_r($nombre);die();
   		return -3;
   	}

   	 $datos[0]['campo']='Codigo_Inv';
	 $datos[0]['dato']=$codigo;
	 $datos[1]['campo']='Producto';
	 $datos[1]['dato']= strtoupper($nombre);
	 $datos[2]['campo']='Item';
	 $datos[2]['dato']=$_SESSION['INGRESO']['item'];
	 $datos[3]['campo']='Periodo';
	 $datos[3]['dato']=$_SESSION['INGRESO']['periodo'];
	 $datos[4]['campo']='TC';
	 $datos[4]['dato']='I';
	 $datos[5]['campo']='INV';
	 $datos[5]['dato']='1';
	 $datos[6]['campo']='Cta_Inventario';
	 $datos[6]['dato']='0';

	 // print_r($datos);die();
	 if($this->modelo->guardar($table='Catalogo_Productos',$datos)==null)
	 {
	 	return 1;
	 }	   

   }


}