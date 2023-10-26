<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/almacenamiento_bodegaM.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");


$controlador = new almacenamiento_bodegaC();

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
if(isset($_GET['productos_asignados']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->productos_asignados($parametros));
}
if(isset($_GET['eliminar_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_bodega($parametros));
}
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
    	$ls='';
		foreach ($datos as $key => $value) 
		{
			$prod = $this->modelo->catalogo_productos($value['Codigo_Inv']);
			$art = $prod[0]['TDP'];
			if($art=='R' && $value['CodBodega']=='-1')
			{
				 $ls.= '<li class="list-group-item"><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'">'.$value['Producto'].'</label>
				 		<div class="btn-group pull-right">
				 				<span class="label-primary btn-sm btn">'.$value['Entrada'].'</span>
								<button type="button" class="btn btn-sm" data-toggle="tooltip" title="" data-widget="chat-pane-toggle">
				 					<i class="fa fa-info-circle"></i>
				 			</button>							
				 		</div>
				 </a>
				 <ul style="padding: 20px;">';
				 		 $ls.= $this->cargar_productos_trans_pedidos($parametros);
				 		$ls.='</ul> 
				 </li>';
				

			}else{
				if($value['CodBodega']=='.' || $value['CodBodega']=='-1')
				{
					$ls.= '<li class="list-group-item"><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'">  '.$value['Producto'].'</label>
								<div class="btn-group pull-right">
										<span class="label-primary btn-sm btn">'.$value['Entrada'].'</span>
										<button type="button" class="btn btn-sm" data-toggle="tooltip" title="" data-widget="chat-pane-toggle">
											<i class="fa fa-info-circle"></i>
									</button>
									
								</div>

					</a></li>';
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
				$ls.= '<li><a href="#" style="padding-right:0px"><label> <!-- <input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'-R" > --> '.$value['Producto'].'</label><span class="label label-danger pull-right">'.$value['Cantidad'].'</span></a></li>';
			}else{
				$ls.= '<li><a href="#" style="padding-right:0px"><label><!-- <input type="checkbox" checked disabled class="rbl_pedido" value="'.$value['ID'].'-R" > -->  '.$value['Producto'].'</label><span class="label label-danger pull-right">'.$value['Cantidad'].'</span></a></li>';
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

    function productos_asignados($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false);
    	$ls='';		
		foreach ($datos as $key => $value) 
		{
			if($value['CodBodega']!='.' && $value['CodBodega']!='-1')
			{
				$ruta = $this->ruta_bodega($value['CodBodega']);
				$ls.= '<tr>
					<td>'.$value['Producto'].'</td>
					<td>'.$ruta.'</td>
					<td>
						<button type="button" onclick="eliminar_bodega(\''.$value['ID'].'\')" class="btn btn-danger btn-sm" title="Eliminar Bodega"><i class="fa fa-trash"></i></button>
						<button type="button" onclick="$(\'#txt_cod_bodega\').val(\''.$value['CodBodega'].'\');$(\'#txt_bodega_title\').text(\''.$ruta.'\');contenido_bodega()" class="btn btn-primary btn-sm" title="Ver Bodega" ><i class="fa fa-eye"></i></button>
					</td>
				</tr>';	

			}
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
	function eliminar_bodega($parametros)
	{

		SetAdoAddNew('Trans_Kardex');
		SetAdoFields('CodBodega','-1');		
		SetAdoFieldsWhere('ID',$parametros['id']);
		return SetAdoUpdateGeneric();
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
				$ruta = $this->ruta_bodega($prefijo);
				if($hijos==1)
				{
					$html.='<li>
						       <input type="checkbox" id="c'.$prefijo.'" />
						       <label class="tree_bod_label" for="c'.$prefijo.'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.$prefijo.'\');cargar_nombre_bodega(\''.$ruta.'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
						       	<ul id="h'.$prefijo.'">
						       	</ul>
					       	</li>';
					$hijos=0;
				}else
				{
					$html.='<li><span class="tree_bod_label" onclick="cargar_nombre_bodega(\''.$ruta.'\',\''.$value['CodBod'].'\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</span></li>';
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
					$ruta = $this->ruta_bodega($prefijo);
					
					if (substr($value['CodBod'], 0, strlen($padre)) === $padre) {
						// print_r('padre');die();
					if($hijos==1)
					{
						// print_r($value2['CodBod'].'-'.$value['Bodega']);die();
						$html.='<li>
							       <input type="checkbox" id="c'.str_replace('.','_',$prefijo).'" />
							       <label class="tree_bod_label" for="c'.str_replace('.','_',$prefijo).'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.str_replace('.','_',$prefijo).'\');cargar_nombre_bodega(\''.$ruta.'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
							       	<ul id="h'.str_replace('.','_',$prefijo).'">
							       	</ul>
						       	</li>';
						$hijos=0;
					}else
					{
						$html.='<li><span class="tree_bod_label" onclick="cargar_nombre_bodega(\''.$ruta.'\',\''.$value['CodBod'].'\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</span></li>';
					}
				}
				

			}
			
		}

		return $html;

	}

	function ruta_bodega($padre)
	{
		$datos = explode('.',$padre);
		$camino = '';
		$buscar = '';
		foreach ($datos as $key => $value) {
			$camino.= $value.'.';
			$buscar.= "'".substr($camino, 0,-1)."',";
		}

		$buscar = substr($buscar, 0,-1);
		$pasos = $this->modelo->ruta_bodega_select($buscar);
		$ruta = '';
		foreach ($pasos as $key => $value) {
			$ruta.=$value['Bodega'].'/';			
		}
		$ruta = substr($ruta,0,-1);
		return $ruta;
	}




}

?>