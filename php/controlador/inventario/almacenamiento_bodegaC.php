<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/almacenamiento_bodegaM.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");


$controlador = new almacenamiento_bodegaC();
// if(isset($_GET['proveedores']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->proveedores($query));
// }
// if(isset($_GET['guardar']))
// {
// 	$parametros = $_POST;
// 	echo json_decode($controlador->guardar($parametros));
// }
// if(isset($_GET['guardar2']))
// {
// 	$parametros = $_POST;
// 	echo json_decode($controlador->guardar2($parametros));
// }
// if(isset($_GET['guardar_pedido']))
// {
// 	$parametros = $_POST;
// 	echo json_encode($controlador->guardar_pedido($parametros));
// }
// if(isset($_GET['eliminar_pedido']))
// {
// 	$parametros = $_POST;
// 	echo json_decode($controlador->eliminar_pedido($parametros));
// }
// if(isset($_GET['alimentos']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->cta_procesos($query));
// }
// if(isset($_GET['detalle_ingreso']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->detalle_ingreso($query));
// }
// if(isset($_GET['detalle_ingreso2']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->detalle_ingreso2($query));
// }
// if(isset($_GET['datos_ingreso']))
// {
// 	$id = $_POST['id'];
// 	echo json_encode($controlador->datos_ingreso($id));
// }

// if(isset($_GET['autoincrementable']))
// {
// 	$parametros = $_POST['parametros'];
// 	$num = ReadSetDataNum('Ingresos_Recibidos',false,false,$parametros['fecha']);
// 	$num = generaCeros($num,4);
// 	echo json_encode($num);
// }
// if(isset($_GET['search']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->buscar($query));

// }
// if(isset($_GET['pedidos_proce']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->buscar_procesado($query));

// }
if(isset($_GET['search_contabilizado']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_contabilizado($query));

}
if(isset($_GET['asignar_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->asignar_bodega($parametros));
}
if(isset($_GET['desasignar_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->desasignar_bodega($parametros));
}
if(isset($_GET['lineas_pedido']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos($parametros));
}
if(isset($_GET['contenido_bodega']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->contenido_bodega($parametros));
}
// if(isset($_GET['pedido_checking']))
// {
// 	$parametros= $_POST['parametros'];	
// 	echo json_encode($controlador->cargar_productos_checking($parametros));
// }
// if(isset($_GET['lin_eli']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->lineas_eli($parametros));
// }
// if(isset($_GET['lin_eli_pedido']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->lineas_eli_pedido($parametros));
// }
// if(isset($_GET['autocom_pro']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->autocomplet_producto($query));
// }
// if(isset($_GET['autocom_pro2']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->autocomplet_producto2($query));
// }

// if(isset($_GET['cargar_datos']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->cargar_datos($parametros));
// }

// if(isset($_GET['producto_costo']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->producto_costo($parametros));
// }
// if(isset($_GET['editar_precio']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->editar_precio($parametros));
// }
// if(isset($_GET['editar_checked']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->editar_checked($parametros));
// }
// if(isset($_GET['actualizar_trans_kardex']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->actualizar_trans_kardex($parametros));
// }
// if(isset($_GET['eli_all_pedido']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->eli_all_pedido($parametros));
// }
// if(isset($_GET['contabilizar']))
// {
// 	$parametros = $_POST;
// 	echo json_encode($controlador->contabilizar($parametros));
// }
if(isset($_GET['lista_bodegas_arbol']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_bodegas_arbol($parametros));
}

/**
 * 
 */
class almacenamiento_bodegaC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new almacenamiento_bodegaM();
	}


	function buscar_contabilizado($cod)
	{
		$datos = $this->modelo->buscar_transCorreos_contabilizadios($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("id"=>$value['Envio_No'],"text"=>$value['Envio_No'],'data'=>$value);
		}
		return $result;
	}



	function cargar_productos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false);
    	// print_r($datos);die();
    	$ls='';
		foreach ($datos as $key => $value) 
		{
			$prod = $this->modelo->catalogo_productos($value['Codigo_Inv']);
			$art = $prod[0]['TDP'];
			if($art=='R')
			{
				$ls.= '<li><a href="#" style="padding:0px"><label><i class="fa fa-sort-down"></i> '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Entrada'].'</span></a>
						<ul class="nav nav-pills nav-stacked" style="padding-left: 20px;">';
						$ls.= $this->cargar_productos_trans_pedidos($parametros);
						$ls.='</ul></li>';
			}else{
				if($value['CodBodega']=='.' || $value['CodBodega']=='-1')
				{
					$ls.= '<li><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'">  '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Entrada'].'</span></a></li>';
				}else{
					$ls.= '<li><a href="#" style="padding:0px"><label><input type="checkbox" checked disabled class="rbl_pedido" value="'.$value['ID'].'">  '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Entrada'].'</span></a></li>';
				}
			}
      }	

      	return $ls;	
    }

    function cargar_productos_trans_pedidos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans_pedidos($parametros['num_ped'],false);
    	$ls='';		
		foreach ($datos as $key => $value) 
		{
			if($value['Codigo_Sup']=='.')
			{
				$ls.= '<li><a href="#" style="padding-right:0px"><label><input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'-R" >  '.$value['Producto'].'</label><span class="label label-danger pull-right">'.$value['Cantidad'].'</span></a></li>';
			}else{
				$ls.= '<li><a href="#" style="padding-right:0px"><label><input type="checkbox" checked disabled class="rbl_pedido" value="'.$value['ID'].'-R" >  '.$value['Producto'].'</label><span class="label label-danger pull-right">'.$value['Cantidad'].'</span></a></li>';
			}
		}
		
		return $ls;
    }

    function contenido_bodega($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false,false,$parametros['bodega']);    	
    	$datos2 = $this->modelo->cargar_pedidos_trans_pedidos($parametros['num_ped'],false,$parametros['bodega']);

    	$ls='';
		foreach ($datos as $key => $value) 
		{			
			$ls.= '<li><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido_des" value="'.$value['ID'].'">  '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Entrada'].'</span></a></li>';
				
      }	
      foreach ($datos2 as $key => $value) 
		{			
			$ls.= '<li><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido_des" value="'.$value['ID'].'-R">  '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Cantidad'].'</span></a></li>';
				
      }	

      	return $ls;	
    }

  

	function asignar_bodega($parametros)
	{
		$id = substr($parametros['id'],0,-1);
		$id = explode(',',$id);
		foreach ($id as $key => $value) {
			$tipo = explode('-', $value);
			if(count($tipo)>1)
			{
				SetAdoAddNew('Trans_Pedidos');
				SetAdoFields('Codigo_Sup',$parametros['bodegas']);		
				SetAdoFieldsWhere('ID',$tipo[0]);
				SetAdoUpdateGeneric();
				//a transpedidos
			}else{
				// a transkardex
				// print_r($value);die();
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('CodBodega',$parametros['bodegas']);		
				SetAdoFieldsWhere('ID',$value);
				SetAdoUpdateGeneric();
			}
		}
		return 1;
	}

	function desasignar_bodega($parametros)
	{
		$id = substr($parametros['id'],0,-1);
		$id = explode(',',$id);
		foreach ($id as $key => $value) {
			$tipo = explode('-', $value);
			if(count($tipo)>1)
			{
				SetAdoAddNew('Trans_Pedidos');
				SetAdoFields('Codigo_Sup','.');		
				SetAdoFieldsWhere('ID',$tipo[0]);
				SetAdoUpdateGeneric();
				//a transpedidos
			}else{
				// a transkardex
				// print_r($parametros);die();
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('CodBodega','-1');		
				SetAdoFieldsWhere('ID',$value);
				SetAdoUpdateGeneric();
			}
		}
		return 1;
	}


	function lista_bodegas_arbol($parametros)
	{
		// print_r($parametros);die();
		$nivel_solicitado = $parametros['nivel'];
		$padre = str_replace('_','.',$parametros['padre']);
		$datos = $this->modelo->bodegas();

		// analiza cuantos niveles tiene
		$niveles = 0;
		foreach ($datos as $key => $value) {
			$niv = explode('.', $value['CodBod']);
			if(count($niv)>$niveles)
			{
				$niveles = count($niv);
			}
		}
		
		//separa los niveles en grupos
		$grupo_nivel = array();
		for ($i=1; $i <= $niveles ; $i++) { 
			$grupo_nivel[$i] = array();
			foreach ($datos as $key => $value) {
				$niv = explode('.', $value['CodBod']);
				if(count($niv)==$i)
				{
					array_push($grupo_nivel[$i], $value);
				}
			}
		}

		// print_r($nivel_solicitado);die();


		$hijos = 0;
		$html = '';
		foreach ($grupo_nivel[$nivel_solicitado] as $key => $value) {
			if($padre=='')
			{
				$prefijo = $value['CodBod'];
				foreach ($grupo_nivel[$nivel_solicitado+1] as $key2 => $value2) {
					if (substr($value2['CodBod'], 0, strlen($prefijo)) === $prefijo) {
						$hijos = 1;
						break;
					} 
				}
				if($hijos==1)
				{
					$html.='<li>
						       <input type="checkbox" id="c'.$prefijo.'" />
						       <label class="tree_bod_label" for="c'.$prefijo.'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.$prefijo.'\');cargar_nombre_bodega(\''.$value['Bodega'].'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
						       	<ul id="h'.$prefijo.'">
						       	</ul>
					       	</li>';
					$hijos=0;
				}else
				{
					$html.='<li><span class="tree_bod_label" onclick="cargar_nombre_bodega(\''.$value['Bodega'].'\',\''.$value['CodBod'].'\')">'.$value['Bodega'].'</span></li>';
				}
			}else{
				//cuando viene con padre
					$prefijo = $value['CodBod'];
					if(isset($grupo_nivel[$nivel_solicitado+1]))
					{

						foreach ($grupo_nivel[$nivel_solicitado+1] as $key2 => $value2) {
							if (substr($value2['CodBod'], 0, strlen($padre)) === $padre) {
								$hijos = 1;
								break;
							} 
						}
					}
					
					if (substr($value['CodBod'], 0, strlen($padre)) === $padre) {
						// print_r('padre');die();
					if($hijos==1)
					{
						// print_r($value2['CodBod'].'-'.$value['Bodega']);die();
						$html.='<li>
							       <input type="checkbox" id="c'.str_replace('.','_',$prefijo).'" />
							       <label class="tree_bod_label" for="c'.str_replace('.','_',$prefijo).'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.str_replace('.','_',$prefijo).'\');cargar_nombre_bodega(\''.$value['Bodega'].'\',\'.\')">'.$value['Bodega'].'</label>
							       	<ul id="h'.str_replace('.','_',$prefijo).'">
							       	</ul>
						       	</li>';
						$hijos=0;
					}else
					{
						$html.='<li><span class="tree_bod_label" onclick="cargar_nombre_bodega(\''.$value['Bodega'].'\',\''.$value['CodBod'].'\')">'.$value['Bodega'].'</span></li>';
					}
				}
				

			}
			
		}

		return $html;

	}


// 	function lista_bodegas_arbol2($parametros)
// 	{
// 		// print_r($parametros);die();
// 		$datos = $this->modelo->bodegas();

// 		// analiza cuantos niveles tiene
// 		$niveles = 0;
// 		foreach ($datos as $key => $value) {
// 			$niv = explode('.', $value['CodBod']);
// 			if(count($niv)>$niveles)
// 			{
// 				$niveles = count($niv);
// 			}
// 		}

// 		//separa los niveles en grupos
// 		$grupo_nivel = array();
// 		for ($i=1; $i <= $niveles ; $i++) { 
// 			$grupo_nivel[$i] = array();
// 			foreach ($datos as $key => $value) {
// 				$niv = explode('.', $value['CodBod']);
// 				if(count($niv)==$i)
// 				{
// 					array_push($grupo_nivel[$i], $value);
// 				}
// 			}
// 		}

// 		//cracion del arbol desde el ultimo nivel hasta el primero
// 		$detalle = '';
// 		$grupo = '';
// 		for ($i=$niveles; $i >=1  ; $i--) { 
// 			foreach ($grupo_nivel[$i] as $key => $value) {

// 				//averiguo el nivel superior
// 				$niv = explode('.', $value['CodBod']);
// 				array_splice($niv, $niveles-1, 1);
// 				$nivel_grupo = '';
// 				foreach ($niv as $key2 => $value2) {
// 					$nivel_grupo.=$value2.'.';
// 				}
// 				$nivel_grupo = substr($nivel_grupo,0,-1);

// 				//agrego os detalles al grupo
// 				$grupo = '';
// 				foreach ($grupo_nivel[$i]  as $key3 => $value3) {
// 					if (strpos($value3['CodBod'], $nivel_grupo ) !== false) {
// 						$detalle.= '<li><span class="tree_bod_label">'.$value['Bodega'].'</span></li>';		
// 					}
// 				}


// 				print_r($nivel_grupo);die();
// 			}

// 			print_r($grupo_nivel[$i]);die();

// 		}


// 		print_r($grupo_nivel);die();
// 	}


}

?>