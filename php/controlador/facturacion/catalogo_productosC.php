<?php 
require_once(dirname(__DIR__,2)."/modelo/facturacion/catalogo_productosM.php");
require_once(dirname(__DIR__,3)."/lib/fpdf/generar_codigo_barras.php");


$controlador = new catalogo_productosC();
if(isset($_GET['TVcatalogo']))
{
  $nivel = $_POST['nivel'];
  $codigo = $_POST['cod'];
  echo json_encode($controlador->TVcatalogo($nivel,$codigo));
}
if(isset($_GET['LlenarInv']))
{
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->LlenarInv($parametros));
}
if(isset($_GET['guardarINV']))
{
	$parametros = $_POST;
  echo json_encode($controlador->guardarINV($parametros));
}
if(isset($_GET['eliminarINV']))
{
	$codigo = $_POST['codigo'];
  echo json_encode($controlador->eliminarINV($codigo));
}

if(isset($_GET['cod_barras']))
{
	$codigo = $_GET['codigo'];
	$cant = $_GET['cant'];
  echo json_encode($controlador->cod_barras($codigo,$cant));
}

if(isset($_GET['cod_barras_grupo']))
{
	$codigo = $_GET['codigo'];
  echo json_encode($controlador->cod_barras_grupo($codigo));
}

/**
 * 
 */
class catalogo_productosC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new catalogo_productosM();
		$this->barras = new generar_codigo_barras();
	}

	function TVcatalogo($nl='',$codigo=false)
	{
		if($nl==''){$nl=1;}
		$cuenta  = $_SESSION['INGRESO']['Formato_Inventario'];
		$partes = explode('.',$cuenta);
		$len = strlen($partes[0]);
		$productos = $this->modelo->TVCatalogo(false,'I',$len);


		$h = '';
		if($codigo=='false')
		{
		$nnl=$nl+1;
			foreach ($productos as $key => $value) {
				$hijo = $this->exite_hijo($value['Codigo_Inv']);
				 if(count($hijo)>0)
				 {
				 		$h.='<li  title="Presione Suprimir para eliminar">
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.$value['Codigo_Inv'].'">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.$value['Codigo_Inv'].'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';
				 }else
				 {
				 	 $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar"><a href="">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';
				 }
			}

		}else
		{
				// print_r($codigo);
			$datos =  $this->exite_hijo($codigo);
			// print_r($datos);
			 // print_r($nl); die();
			$nnl=$nl+1;
			foreach ($datos as $key => $value) {

			// print_r(count(explode('.', $value['Codigo_Inv'])));
			// print_r($nnl);
			// die();
				if(count(explode('.', $value['Codigo_Inv']))==$nl)
				{
				$hijo = $this->exite_hijo($value['Codigo_Inv']);
				 if(count($hijo)>0)
				 {
				 		$h.='<li title="Presione Suprimir para eliminar" >
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.str_replace('.','_',$value['Codigo_Inv']).'">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.str_replace('.','_',$value['Codigo_Inv']).'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';
				 }else
				 {
				 	 if($value['TC']=='I')
				 	 {
				 	 	$h.='<li>
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.str_replace('.','_',$value['Codigo_Inv']).'">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.str_replace('.','_',$value['Codigo_Inv']).'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';

				 	 }else{
				 	 $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar" ><a href="#" onclick="detalle('.$nnl.',\''.$value['Codigo_Inv'].'\')">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';
				 	}
				 }
				}
			}
		}
		

		return $h;


	}
	function exite_hijo($codigo)
	{
		$productos = $this->modelo->TVCatalogo($codigo);
		return $productos;
	}
	function LlenarInv($parametros)
	{
		 $codigo = $parametros['codigo'];
		 $detalle = $this->modelo->TVCatalogo($query=false,$TC=false,$len=false,$codigo);
		 return $detalle;		
	}

	function guardarINV($parametros)
	{
		// print_r($parametros);die();
		if(substr($parametros['txt_codigo'],-1)=='.'){ $parametros['txt_codigo'] = substr($parametros['txt_codigo'],0,-1);}
		$codigoInv = $this->modelo->TVCatalogo($query=false,$TC=false,$len=false,$codigo=$parametros['txt_codigo']);
	 
	  	$datos[0]['campo']= 'Codigo_Inv';
	  	$datos[0]['dato']= $parametros['txt_codigo'];
	  	$datos[1]['campo']= 'Producto';
	  	$datos[1]['dato']= $parametros['txt_concepto'];
	  	$datos[2]['campo']= 'TC';
	  	$datos[2]['dato']= $parametros['cbx_tipo'];
	  	$datos[3]['campo']= 'Unidad';
	  	$datos[3]['dato']= $parametros['txt_unidad'];
	  	$datos[4]['campo']= 'Maximo';
	  	$datos[4]['dato']= $parametros['maximo'];
	  	$datos[5]['campo']= 'Minimo';
	  	$datos[5]['dato']= $parametros['minimo'];
	  	$datos[6]['campo']= 'Gramaje';
	  	$datos[6]['dato']= $parametros['txt_gramaje'];
	  	$datos[7]['campo']= 'PVP';
	  	$datos[7]['dato']= $parametros['pvp'];
	  	$datos[8]['campo']= 'PVP_2';
	  	$datos[8]['dato']= $parametros['pvp2'];
	  	$datos[9]['campo']= 'PVP_3';
	  	$datos[9]['dato']= $parametros['pvp3'];
	  	$datos[10]['campo']= 'Marca';
	  	$datos[10]['dato']= $parametros['txt_marca'];
	  	$datos[11]['campo']= 'Reg_Sanitario';
	  	$datos[11]['dato']= $parametros['txt_reg_sanitario'];
	  	$datos[12]['campo']= 'Codigo_IESS';
	  	$datos[12]['dato']= $parametros['txt_iess'];
	  	$datos[13]['campo']= 'Codigo_RES';
	  	$datos[13]['dato']= $parametros['txt_codres'];
	  	$datos[14]['campo']= 'Codigo_Barra';
	  	$datos[14]['dato']= $parametros['txt_barras'];
	  	$datos[15]['campo']= 'Cta_Inventario';
	  	$datos[15]['dato']= $parametros['cta_inventario'];
	  	$datos[16]['campo']= 'Cta_Costo_Venta';
	  	$datos[16]['dato']= $parametros['cta_costo_venta'];
	  	$datos[17]['campo']= 'Cta_Ventas';
	  	$datos[17]['dato']= $parametros['cta_venta'];
	  	$datos[18]['campo']= 'Cta_Ventas_0';
	  	$datos[18]['dato']= $parametros['cta_tarifa_0'];
	  	$datos[19]['campo']= 'Cta_Ventas_Ant';
	  	$datos[19]['dato']= $parametros['cta_venta_anterior'];
	  	$datos[20]['campo']= 'Cta_Ventas_Anticipadas';
	  	$datos[20]['dato']= '.'; //revisar
	  	$datos[21]['campo']= 'Detalle';
	  	$datos[21]['dato']= '';
	  	$datos[22]['campo']= 'PX';
	  	$datos[22]['dato']= $parametros['txt_posx'];
	  	$datos[23]['campo']= 'PY';
	  	$datos[23]['dato']= $parametros['txt_posy'];
	  	$datos[24]['campo']= 'Item_Banco';
	  	$datos[24]['dato']= $parametros['txt_codbanco'];
	  	$datos[25]['campo']= 'Desc_Item';
	  	$datos[25]['dato']= $parametros['txt_descripcion'];
	  	$datos[26]['campo']= 'Utilidad';
	  	$datos[26]['dato']= $parametros['txt_utilidad'];
	  	$datos[27]['campo']= 'Ayuda';
	  	$datos[27]['dato']= $parametros['txt_formula'];
	  	$datos[28]['campo']= 'Ubicacion';
	  	$datos[28]['dato']= $parametros['txt_ubicacion'];
	  	$datos[29]['campo']= 'Periodo';
	  	$datos[29]['dato']= $_SESSION['INGRESO']['periodo'];
	  	$datos[30]['campo']= 'Item';
	  	$datos[30]['dato']= $_SESSION['INGRESO']['item'];
	  	$datos[31]['campo']= 'IVA';
	  	if(isset($parametros['rbl_iva']) && $parametros['rbl_iva'] =='on'){ $datos[31]['dato']= 1; }else{ $datos[31]['dato']= 0;}
	  	$datos[32]['campo']= 'INV';
	  	if(isset($parametros['rbl_inv']) && $parametros['rbl_inv'] =='on'){ $datos[32]['dato']= 1; }else{ $datos[32]['dato']= 0;}
	  	$datos[33]['campo']= 'Div';
	  	if(isset($parametros['cbx_calcular']) && $parametros['cbx_calcular'] =='div'){ $datos[33]['dato']= 1; }else{ $datos[33]['dato']= 0;}
	  	$datos[34]['campo']= 'Agrupacion';
	  	if(isset($parametros['rbl_agrupacion']) && $parametros['rbl_agrupacion'] =='on'){ $datos[34]['dato']= 1; }else{ $datos[34]['dato']= 0;}
	  	$datos[35]['campo']= 'Por_Reservas';
	  	if(isset($parametros['rbl_reserva']) && $parametros['rbl_reserva'] =='on'){ $datos[35]['dato']= 1; }else{ $datos[35]['dato']= 0;}


	  if(count($codigoInv)>0)
	  {	  	
	  	 $where[0]['campo'] ='ID'; 
	  	 $where[0]['valor'] = $codigoInv[0]['ID'];
	  	 return update_generico($datos,'Catalogo_Productos',$where);
	  }else
	  {
	  	if(insert_generico('Catalogo_Productos',$datos)==null){	return 1;	}else{return -1;}
	  }
		// print_r($parametros);die();
	}

	function cod_barras($codigo,$cant)
	{
		$codigoInv = $this->modelo->TVCatalogo($query=false,$TC=false,$len=false,$codigo=$codigo);
		if(count($codigoInv)>0)
		{
			$this->barras->generar_barras($cant,$codigoInv[0]);
		}
	 
		// print_r($parametros);die();
	}

	function cod_barras_grupo($codigo)
	{
		$hijos = $this->exite_hijo($codigo);
		if(count($hijos)>0)
		{			
				$this->barras->generar_barras_grupo($hijos);
	  }else
	  {
	  	return -1;
	  }
	 
		// print_r($parametros);die();
	}

	function eliminarINV($codigo)
	{
		// $codigoInv = $this->modelo->TVCatalogo($query=$codigo,$TC=false,$len=false,$codigo=false);
		// if(count($codigoInv)>0)
		// {			
		// 	return -1;
		// }else
		// {
			$tk = $this->modelo->trans_kardex($codigo);
			// print_r($tk);
			if(count($tk)>0)
			{
				return -1;

			}else
			{
				$df =  $this->modelo->detalle_factura($codigo);
				// print_r($df);die();
				if(count($df)>0)
				{
					return -1;
				}else
				{
					return  $this->modelo->eliminar_cuenta($codigo);
				}
			}
		// }
	 
	}
}

?>